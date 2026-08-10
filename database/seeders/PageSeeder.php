<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            [
                'title' => 'Legal Notice',
                'slug' => 'legal-notice',
                'content' => '<h1>Legal Notice</h1><p>Legal notice content goes here.</p>',
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'content' => '<h1>Privacy Policy</h1><p>Privacy policy content goes here.</p>',
            ],
            [
                'title' => 'Cookie Policy',
                'slug' => 'cookie-policy',
                'content' => '<h1>Cookie Policy</h1><p>Cookie policy content goes here.</p>',
            ],
            [
                'title' => 'About Us',
                'slug' => 'about',
                'content' => '<h1>About RaxxO BD</h1><p>About us content goes here.</p>',
            ]
        ];

        foreach ($pages as $page) {
            \App\Models\Page::firstOrCreate(
                ['slug' => $page['slug']],
                $page
            );
        }
    }
}
