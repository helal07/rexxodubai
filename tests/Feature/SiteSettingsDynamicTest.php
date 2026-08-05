<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SiteSettingsDynamicTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_fetch_settings_via_api()
    {
        Setting::create(['key' => 'siteName', 'value' => 'ReXxo Luxury Bd']);
        Setting::create(['key' => 'logo_url', 'value' => '/uploads/settings/custom_logo.png']);
        Setting::create(['key' => 'favicon_url', 'value' => '/uploads/settings/custom_favicon.ico']);

        $response = $this->getJson('/api/settings');

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'siteName' => 'ReXxo Luxury Bd',
            'logo_url' => '/uploads/settings/custom_logo.png',
            'favicon_url' => '/uploads/settings/custom_favicon.ico',
        ]);
    }

    public function test_can_update_settings_and_branding_via_api()
    {
        $payload = [
            'siteName' => 'Imperia Perfumes BD',
            'tagline' => 'Royal Extraits',
            'logo_url' => 'https://example.com/imperia_logo.png',
            'favicon_url' => 'https://example.com/imperia_favicon.ico',
            'phone' => '+880 1999 888 777',
        ];

        $response = $this->postJson('/api/settings', [
            'settings' => $payload
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('settings', [
            'key' => 'siteName',
            'value' => 'Imperia Perfumes BD',
        ]);
        $this->assertDatabaseHas('settings', [
            'key' => 'logo_url',
            'value' => 'https://example.com/imperia_logo.png',
        ]);
        $this->assertDatabaseHas('settings', [
            'key' => 'favicon_url',
            'value' => 'https://example.com/imperia_favicon.ico',
        ]);
    }

    public function test_can_upload_logo_and_favicon_files()
    {
        Storage::fake('public');

        $logo = UploadedFile::fake()->image('brand_logo.png', 200, 80);
        $favicon = UploadedFile::fake()->image('brand_favicon.png', 32, 32);

        $response = $this->post('/api/settings', [
            'logo_file' => $logo,
            'favicon_file' => $favicon,
            'siteName' => 'Royal Brand BD',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('settings', [
            'key' => 'siteName',
            'value' => 'Royal Brand BD',
        ]);

        $logoSetting = Setting::where('key', 'logo_url')->first();
        $this->assertNotNull($logoSetting);
        $this->assertStringContainsString('/uploads/settings/logo_', $logoSetting->value);

        $faviconSetting = Setting::where('key', 'favicon_url')->first();
        $this->assertNotNull($faviconSetting);
        $this->assertStringContainsString('/uploads/settings/favicon_', $faviconSetting->value);
    }

    public function test_admin_and_storefront_views_render_dynamic_branding()
    {
        Setting::create(['key' => 'siteName', 'value' => 'Dynamo Royale']);
        Setting::create(['key' => 'favicon_url', 'value' => '/custom-favicon.ico']);
        Setting::create(['key' => 'logo_url', 'value' => '/custom-logo.png']);

        // Admin login page
        $loginRes = $this->get('/admin');
        $loginRes->assertStatus(200);
        $loginRes->assertSee('Dynamo Royale');
        $loginRes->assertSee('/custom-favicon.ico');
        $loginRes->assertSee('/custom-logo.png');

        // Authenticated admin dashboard
        $user = User::factory()->create(['is_admin' => true]);
        $dashRes = $this->actingAs($user)->get('/admin');
        $dashRes->assertStatus(200);
        $dashRes->assertSee('Dynamo Royale');
        $dashRes->assertSee('/custom-favicon.ico');

        // Storefront
        $storeRes = $this->get('/');
        $storeRes->assertStatus(200);
        $storeRes->assertSee('Dynamo Royale');
        $storeRes->assertSee('/custom-favicon.ico');
    }
}
