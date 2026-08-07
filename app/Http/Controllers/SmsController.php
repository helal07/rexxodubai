<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsController extends Controller
{
    /**
     * Retrieve the current site settings (cached).
     */
    private function getSettings(): array
    {
        return Cache::remember('global_site_settings', 3600, function () {
            return Setting::all()->pluck('value', 'key')->toArray();
        });
    }

    // ──────────────────────────────────────────────────────────────────────────
    // PUBLIC: Test connection from Admin dashboard (POST /admin/sms/test)
    // ──────────────────────────────────────────────────────────────────────────
    public function testConnection(Request $request)
    {
        $request->validate([
            'gateway' => 'required|in:bulksmsbd,mimsms',
            'phone'   => 'required|string|min:11|max:15',
            'message' => 'nullable|string|max:320',
        ]);

        $gateway = $request->input('gateway');
        $phone   = $request->input('phone');
        $message = $request->input('message', 'TEST: SMS API connection is working! — RaaxO Admin');

        $result = $this->sendSms($gateway, $phone, $message);

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // INTERNAL: Send an SMS via the specified gateway
    // ──────────────────────────────────────────────────────────────────────────
    public function sendSms(string $gateway, string $phone, string $message): array
    {
        $settings = $this->getSettings();

        try {
            if ($gateway === 'bulksmsbd') {
                return $this->sendViaBulkSmsBd($phone, $message, $settings);
            } elseif ($gateway === 'mimsms') {
                return $this->sendViaMimSms($phone, $message, $settings);
            }
        } catch (\Throwable $e) {
            Log::error("SMS send error [{$gateway}]: " . $e->getMessage());
            return ['success' => false, 'message' => 'Exception: ' . $e->getMessage()];
        }

        return ['success' => false, 'message' => 'Unknown gateway: ' . $gateway];
    }

    // ──────────────────────────────────────────────────────────────────────────
    // GATEWAY: BulkSMS BD (bulksmsbd.net)
    // API docs: https://bulksmsbd.net/docs
    // ──────────────────────────────────────────────────────────────────────────
    private function sendViaBulkSmsBd(string $phone, string $message, array $settings): array
    {
        $apiKey   = $settings['bulksmsbd_api_key']   ?? '';
        $senderId = $settings['bulksmsbd_sender_id'] ?? '';
        $baseUrl  = $settings['bulksmsbd_base_url']  ?? 'https://bulksmsbd.net/api/smsapi';

        if (empty($apiKey)) {
            return ['success' => false, 'message' => 'BulkSMS BD API Key is not configured. Please save your API key in API Settings first.'];
        }

        // Normalize BD phone: strip country code prefix if present
        $phone = $this->normalizeBdPhone($phone);

        $response = Http::timeout(15)->get($baseUrl, [
            'api_key'   => $apiKey,
            'type'      => 'text',
            'number'    => $phone,
            'senderid'  => $senderId,
            'message'   => $message,
        ]);

        $body = $response->body();

        // BulkSMS BD returns a numeric code or JSON
        if ($response->successful()) {
            // Possible response codes: 202 = success
            if (str_contains($body, '202') || $response->status() === 200) {
                return ['success' => true, 'message' => "BulkSMS BD: Message sent to {$phone}. Response: {$body}"];
            }
            return ['success' => false, 'message' => "BulkSMS BD error: {$body}"];
        }

        return ['success' => false, 'message' => "BulkSMS BD HTTP {$response->status()}: {$body}"];
    }

    // ──────────────────────────────────────────────────────────────────────────
    // GATEWAY: MiM SMS (mimsms.com)
    // API docs: https://mimsms.com/developer
    // ──────────────────────────────────────────────────────────────────────────
    private function sendViaMimSms(string $phone, string $message, array $settings): array
    {
        $apiKey   = $settings['mimsms_api_key']   ?? '';
        $senderId = $settings['mimsms_sender_id'] ?? '';
        $type     = $settings['mimsms_type']      ?? 'text';
        $baseUrl  = $settings['mimsms_base_url']  ?? 'https://api.mimsms.com/api/SmSAPI';

        if (empty($apiKey)) {
            return ['success' => false, 'message' => 'MiM SMS API Key is not configured. Please save your API key in API Settings first.'];
        }

        $phone = $this->normalizeBdPhone($phone);

        $response = Http::timeout(15)->get($baseUrl, [
            'ApiKey'      => $apiKey,
            'ClientId'    => '',          // Optional: MiM SMS Client ID (leave empty if not required)
            'SenderId'    => $senderId,
            'Message'     => $message,
            'MobileNo'    => $phone,
            'Type'        => $type,
        ]);

        $body = $response->body();

        if ($response->successful()) {
            // MiM SMS returns JSON with Status field
            $json = $response->json();
            if (isset($json['Status']) && $json['Status'] === 'Submitted') {
                return ['success' => true, 'message' => "MiM SMS: Message sent to {$phone}."];
            }
            if (str_contains($body, 'Submitted') || str_contains($body, 'success')) {
                return ['success' => true, 'message' => "MiM SMS: Message sent to {$phone}. Response: {$body}"];
            }
            return ['success' => false, 'message' => "MiM SMS error: {$body}"];
        }

        return ['success' => false, 'message' => "MiM SMS HTTP {$response->status()}: {$body}"];
    }

    // ──────────────────────────────────────────────────────────────────────────
    // ORDER NOTIFICATION: Called from OrderController on status change / creation
    // ──────────────────────────────────────────────────────────────────────────
    public function sendOrderNotification(Order $order, string $event = 'new_order'): void
    {
        $settings = $this->getSettings();

        // Check if event notifications are enabled
        $eventKey = 'sms_on_' . $event;
        if (($settings[$eventKey] ?? '0') !== '1') {
            return;
        }

        // Determine which gateway to use (prioritise enabled one)
        $gateway = null;
        if (($settings['sms_bulksmsbd_enabled'] ?? '0') === '1') {
            $gateway = 'bulksmsbd';
        } elseif (($settings['sms_mimsms_enabled'] ?? '0') === '1') {
            $gateway = 'mimsms';
        }

        if (!$gateway) {
            return; // No gateway enabled
        }

        // Get template for this event
        $templateKey = 'sms_template_' . $event;
        $template = $settings[$templateKey]
            ?? $this->defaultTemplate($event);

        // Replace placeholders
        $message = $this->buildMessage($template, $order, $settings);

        // Get customer phone
        $phone = $order->phone ?? $order->customer_phone ?? null;
        if (empty($phone)) {
            Log::warning("SMS notification skipped: Order #{$order->id} has no phone number.");
            return;
        }

        $result = $this->sendSms($gateway, $phone, $message);

        Log::info("SMS notification [{$event}] for Order #{$order->id} via {$gateway}: " . json_encode($result));
    }

    // ──────────────────────────────────────────────────────────────────────────
    // HELPERS
    // ──────────────────────────────────────────────────────────────────────────

    private function normalizeBdPhone(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone); // strip non-digits
        if (str_starts_with($phone, '880') && strlen($phone) === 13) {
            $phone = '0' . substr($phone, 3); // 8801712345678 → 01712345678
        }
        return $phone;
    }

    private function buildMessage(string $template, Order $order, array $settings): string
    {
        $company    = $settings['siteName'] ?? $settings['site_name'] ?? 'RaaxO BD';
        $trackingId = $order->courier_tracking_id ?? $order->tracking_id ?? 'N/A';

        return str_replace(
            ['{name}', '{order_id}', '{amount}', '{company}', '{tracking_id}', '{courier}'],
            [
                $order->customer_name ?? $order->name ?? 'Customer',
                $order->id,
                number_format($order->total_amount ?? $order->total ?? 0),
                $company,
                $trackingId,
                $order->courier ?? 'courier',
            ],
            $template
        );
    }

    private function defaultTemplate(string $event): string
    {
        return match ($event) {
            'new_order'  => "Dear {name}, your order #{order_id} of \u09f3{amount} BDT has been confirmed! Thank you. — {company}",
            'dispatch'   => "Dear {name}, order #{order_id} dispatched! Track: {tracking_id}. — {company}",
            'delivered'  => "Dear {name}, order #{order_id} delivered! Thank you for shopping with {company}.",
            'cancelled'  => "Dear {name}, your order #{order_id} has been cancelled. Contact us for assistance. — {company}",
            default      => "Order #{order_id} update from {company}.",
        };
    }
}
