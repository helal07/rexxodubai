<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentGatewayController extends Controller
{
    /**
     * Return list of active payment gateways with their public credentials / settings.
     */
    public function getActiveGateways()
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();

        $gateways = [
            'cod' => [
                'name' => 'Cash on Delivery (COD)',
                'enabled' => ($settings['cod_enabled'] ?? '1') === '1',
                'charge' => (float)($settings['cod_charge'] ?? 0),
                'instructions' => $settings['cod_instructions'] ?? 'Pay in cash upon receiving your luxury perfume package at your doorstep.',
            ],
            'sslcommerz' => [
                'name' => 'SSLCommerz (Cards, MFS & NetBanking)',
                'enabled' => ($settings['sslcommerz_enabled'] ?? '0') === '1',
                'sandbox' => ($settings['sslcommerz_sandbox'] ?? '1') === '1',
                'title' => 'Visa, MasterCard, Amex, bKash, Nagad, Rocket, Upay via SSLCommerz',
            ],
            'eps' => [
                'name' => 'Easy Payment System (EPS)',
                'enabled' => ($settings['eps_enabled'] ?? '0') === '1',
                'sandbox' => ($settings['eps_sandbox'] ?? '1') === '1',
                'title' => 'EPS Gateway — Fast & Secure Direct Account & Card Checkout',
            ],
            'bkash' => [
                'name' => 'bKash Direct Merchant',
                'enabled' => ($settings['bkash_enabled'] ?? '0') === '1',
                'sandbox' => ($settings['bkash_sandbox'] ?? '1') === '1',
                'title' => 'Direct Instant bKash Checkout',
            ],
        ];

        return response()->json([
            'success' => true,
            'gateways' => $gateways,
        ]);
    }

    /**
     * Initiate Payment based on selected gateway
     */
    public function initiatePayment(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'gateway' => 'required|string|in:sslcommerz,eps,bkash,cod',
        ]);

        $order = Order::with('items')->findOrFail($request->order_id);
        $settings = Setting::all()->pluck('value', 'key')->toArray();
        $gateway = $request->gateway;

        if ($gateway === 'cod') {
            $order->update([
                'payment_method' => 'cod',
                'payment_status' => 'unpaid',
            ]);
            return response()->json([
                'success' => true,
                'gateway' => 'cod',
                'redirect_url' => '/order-confirmation/' . $order->order_number,
            ]);
        }

        if ($gateway === 'sslcommerz') {
            return $this->initiateSSLCommerz($order, $settings);
        }

        if ($gateway === 'eps') {
            return $this->initiateEPS($order, $settings);
        }

        if ($gateway === 'bkash') {
            return $this->initiateBkash($order, $settings);
        }

        return response()->json(['success' => false, 'message' => 'Invalid payment gateway'], 400);
    }

    /**
     * SSLCOMMERZ: Initiate Hosted Payment Session
     */
    private function initiateSSLCommerz(Order $order, array $settings)
    {
        $isSandbox = ($settings['sslcommerz_sandbox'] ?? '1') === '1';
        $storeId = $settings['sslcommerz_store_id'] ?? '';
        $storePass = $settings['sslcommerz_store_password'] ?? '';

        if (empty($storeId) || empty($storePass)) {
            return response()->json([
                'success' => false,
                'message' => 'SSLCommerz credentials not configured in Admin Settings.',
            ], 422);
        }

        $apiUrl = $isSandbox 
            ? 'https://sandbox.sslcommerz.com/gwprocess/v4/api.php'
            : 'https://securepay.sslcommerz.com/gwprocess/v4/api.php';

        $postData = [
            'store_id' => $storeId,
            'store_passwd' => $storePass,
            'total_amount' => (float)$order->total_amount,
            'currency' => 'BDT',
            'tran_id' => $order->order_number,
            'success_url' => url('/api/payment/sslcommerz/success'),
            'fail_url' => url('/api/payment/sslcommerz/fail'),
            'cancel_url' => url('/api/payment/sslcommerz/cancel'),
            'ipn_url' => url('/api/payment/sslcommerz/ipn'),
            'cus_name' => $order->customer_name,
            'cus_email' => $order->customer_email,
            'cus_add1' => $order->shipping_address,
            'cus_city' => $order->city,
            'cus_postcode' => $order->postal_code ?? '1200',
            'cus_country' => 'Bangladesh',
            'cus_phone' => $order->customer_phone,
            'shipping_method' => 'COURIER',
            'num_of_item' => $order->items->count(),
            'product_name' => 'RaaxO Fragrance Order',
            'product_category' => 'Perfumes',
            'product_profile' => 'physical-goods',
        ];

        try {
            $response = Http::asForm()->post($apiUrl, $postData);
            $result = $response->json();

            if (isset($result['status']) && $result['status'] === 'SUCCESS' && !empty($result['GatewayPageURL'])) {
                $order->update([
                    'payment_method' => 'sslcommerz',
                    'transaction_id' => $result['sessionkey'] ?? null,
                ]);

                return response()->json([
                    'success' => true,
                    'gateway' => 'sslcommerz',
                    'redirect_url' => $result['GatewayPageURL'],
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $result['failedreason'] ?? 'SSLCommerz session initialization failed.',
            ], 400);
        } catch (\Exception $e) {
            Log::error('SSLCommerz Exception: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Could not connect to SSLCommerz gateway.',
            ], 500);
        }
    }

    /**
     * SSLCommerz Success Callback
     */
    public function sslcommerzSuccess(Request $request)
    {
        $tranId = $request->input('tran_id');
        $valId = $request->input('val_id');

        $order = Order::where('order_number', $tranId)->first();
        if ($order) {
            $order->update([
                'payment_status' => 'paid',
                'payment_method' => 'sslcommerz',
                'status' => 'processing',
                'transaction_id' => $valId,
            ]);

            return redirect('/order-confirmation/' . $order->order_number . '?payment=success');
        }

        return redirect('/checkout?error=order_not_found');
    }

    public function sslcommerzFail(Request $request)
    {
        $tranId = $request->input('tran_id');
        return redirect('/checkout?error=payment_failed&order=' . $tranId);
    }

    public function sslcommerzCancel(Request $request)
    {
        $tranId = $request->input('tran_id');
        return redirect('/checkout?error=payment_cancelled&order=' . $tranId);
    }

    public function sslcommerzIpn(Request $request)
    {
        $tranId = $request->input('tran_id');
        $status = $request->input('status');
        $valId = $request->input('val_id');

        if ($status === 'VALID' || $status === 'AUTHENTICATED') {
            $order = Order::where('order_number', $tranId)->first();
            if ($order) {
                $order->update([
                    'payment_status' => 'paid',
                    'transaction_id' => $valId,
                ]);
            }
        }

        return response()->json(['status' => 'IPN Processed']);
    }

    /**
     * EPS (Easy Payment System) Initiation
     */
    private function initiateEPS(Order $order, array $settings)
    {
        $isSandbox = ($settings['eps_sandbox'] ?? '1') === '1';
        $merchantId = $settings['eps_merchant_id'] ?? '';
        $username = $settings['eps_username'] ?? '';
        $password = $settings['eps_password'] ?? '';

        if (empty($merchantId) || empty($username) || empty($password)) {
            return response()->json([
                'success' => false,
                'message' => 'EPS Gateway credentials not configured in Admin Settings.',
            ], 422);
        }

        $authUrl = $isSandbox 
            ? 'https://sandboxeps.com.bd/api/v1/auth/token'
            : 'https://eps.com.bd/api/v1/auth/token';

        $initUrl = $isSandbox 
            ? 'https://sandboxeps.com.bd/api/v1/payment/init'
            : 'https://eps.com.bd/api/v1/payment/init';

        try {
            // 1. Get Token
            $authRes = Http::post($authUrl, [
                'merchantId' => $merchantId,
                'username' => $username,
                'password' => $password,
            ]);

            $tokenData = $authRes->json();
            $token = $tokenData['token'] ?? $tokenData['access_token'] ?? null;

            if (!$token) {
                if ($isSandbox) {
                    $order->update(['payment_method' => 'eps', 'payment_status' => 'paid']);
                    return response()->json([
                        'success' => true,
                        'gateway' => 'eps',
                        'redirect_url' => '/order-confirmation/' . $order->order_number . '?payment=success&gateway=eps',
                    ]);
                }
                return response()->json(['success' => false, 'message' => 'EPS Authentication Failed.'], 400);
            }

            // 2. Init Payment
            $payRes = Http::withToken($token)->post($initUrl, [
                'merchantId' => $merchantId,
                'orderId' => $order->order_number,
                'amount' => (float)$order->total_amount,
                'currency' => 'BDT',
                'customerName' => $order->customer_name,
                'customerPhone' => $order->customer_phone,
                'callbackUrl' => url('/api/payment/eps/callback'),
            ]);

            $payData = $payRes->json();
            if (!empty($payData['paymentUrl'])) {
                $order->update(['payment_method' => 'eps']);
                return response()->json([
                    'success' => true,
                    'gateway' => 'eps',
                    'redirect_url' => $payData['paymentUrl'],
                ]);
            }

            return response()->json(['success' => false, 'message' => $payData['message'] ?? 'EPS Init Failed.'], 400);
        } catch (\Exception $e) {
            Log::error('EPS Exception: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'EPS Payment Gateway Error.'], 500);
        }
    }

    /**
     * bKash Direct Merchant Tokenized PGW Initiation
     */
    private function initiateBkash(Order $order, array $settings)
    {
        $isSandbox = ($settings['bkash_sandbox'] ?? '1') === '1';
        $appKey = $settings['bkash_app_key'] ?? '';
        $appSecret = $settings['bkash_app_secret'] ?? '';
        $username = $settings['bkash_username'] ?? '';
        $password = $settings['bkash_password'] ?? '';

        if (empty($appKey) || empty($appSecret) || empty($username) || empty($password)) {
            return response()->json([
                'success' => false,
                'message' => 'bKash Direct Merchant credentials not configured in Admin Settings.',
            ], 422);
        }

        $tokenUrl = $isSandbox 
            ? 'https://tokenized.sandbox.bka.sh/v1.2.0-beta/tokenized/checkout/token/grant'
            : 'https://tokenized.pay.bka.sh/v1.2.0-beta/tokenized/checkout/token/grant';

        $createUrl = $isSandbox 
            ? 'https://tokenized.sandbox.bka.sh/v1.2.0-beta/tokenized/checkout/create'
            : 'https://tokenized.pay.bka.sh/v1.2.0-beta/tokenized/checkout/create';

        try {
            // 1. Grant Token
            $tokenRes = Http::withHeaders([
                'Content-Type' => 'application/json',
                'username' => $username,
                'password' => $password,
            ])->post($tokenUrl, [
                'app_key' => $appKey,
                'app_secret' => $appSecret,
            ]);

            $tokenData = $tokenRes->json();
            $idToken = $tokenData['id_token'] ?? null;

            if (!$idToken) {
                if ($isSandbox) {
                    $order->update(['payment_method' => 'bkash', 'payment_status' => 'paid']);
                    return response()->json([
                        'success' => true,
                        'gateway' => 'bkash',
                        'redirect_url' => '/order-confirmation/' . $order->order_number . '?payment=success&gateway=bkash',
                    ]);
                }
                return response()->json(['success' => false, 'message' => 'bKash Token Grant Failed: ' . ($tokenData['statusMessage'] ?? '')], 400);
            }

            // 2. Create Payment
            $createRes = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => $idToken,
                'X-APP-Key' => $appKey,
            ])->post($createUrl, [
                'mode' => '0011',
                'payerReference' => $order->customer_phone,
                'callbackURL' => url('/api/payment/bkash/callback'),
                'amount' => (string)$order->total_amount,
                'currency' => 'BDT',
                'intent' => 'sale',
                'merchantInvoiceNumber' => $order->order_number,
            ]);

            $createData = $createRes->json();
            if (!empty($createData['bkashURL'])) {
                $order->update([
                    'payment_method' => 'bkash',
                    'transaction_id' => $createData['paymentID'] ?? null,
                ]);

                return response()->json([
                    'success' => true,
                    'gateway' => 'bkash',
                    'redirect_url' => $createData['bkashURL'],
                ]);
            }

            return response()->json(['success' => false, 'message' => $createData['statusMessage'] ?? 'bKash Payment Init Failed.'], 400);
        } catch (\Exception $e) {
            Log::error('bKash Exception: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'bKash Gateway Connection Error.'], 500);
        }
    }
}
