<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing items to avoid duplicates
        MenuItem::truncate();

        // 1. All Fragrances
        MenuItem::create([
            'label' => 'All Fragrances',
            'url' => '/perfumes',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        // 2. Men Perfume
        MenuItem::create([
            'label' => 'Men Perfume',
            'url' => '/perfumes?gender=men',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        // 3. Women Perfume
        MenuItem::create([
            'label' => 'Women Perfume',
            'url' => '/perfumes?gender=women',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        // 4. Gifts & Sets
        MenuItem::create([
            'label' => 'Gifts & Sets',
            'url' => '/perfumes?category=gifts',
            'sort_order' => 4,
            'is_active' => true,
        ]);

        // 5. Contact & Support
        MenuItem::create([
            'label' => 'Contact & Support',
            'url' => '/about',
            'sort_order' => 5,
            'is_active' => true,
        ]);
    }
}
