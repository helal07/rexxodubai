<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultSettings = [
            'siteName' => 'REXXO BD',
            'tagline' => 'Fine Fragrance & Luxury Extraits',
            'logo_url' => '',
            'favicon_url' => '/favicon.ico',
            'phone' => '+880 1700 000 000',
            'whatsapp' => '8801700000000',
            'email' => 'client.service.bd@rexxobd.com',
            'currency' => 'USD ($)',
            'announcement' => 'Complimentary luxury gift box & free worldwide express delivery on orders over $250.',
            'footerText' => 'ReXxo Bd Perfumes Ltd. All Rights Reserved.',
            'tax_rate' => '0',
            'facebook_url' => 'https://facebook.com/rexxobd',
            'instagram_url' => 'https://instagram.com/rexxobd',
            'tiktok_url' => 'https://tiktok.com/@rexxobd',
            'hero_video_url' => 'https://assets.mixkit.co/videos/preview/mixkit-perfume-bottle-in-a-dark-environment-42525-large.mp4',
            'hero_video' => 'https://assets.mixkit.co/videos/preview/mixkit-perfume-bottle-in-a-dark-environment-42525-large.mp4',
            'hero_poster_url' => 'https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&w=2400&q=90',
            'hero_subtitle' => 'NEW COLLECTION',
            'hero_title' => 'Fall Winter 2026',
            'hero_link_1_text' => 'FOR HER',
            'hero_link_1_url' => '/perfumes?gender=women',
            'hero_link_2_text' => 'FOR HIM',
            'hero_link_2_url' => '/perfumes?gender=men',
        ];

        foreach ($defaultSettings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }
}
