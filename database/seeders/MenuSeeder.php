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
        if (MenuItem::count() > 0) {
            return; // Safe policy: Never overwrite existing menu items
        }

        // 1. Men Perfumes
        $men = MenuItem::create([
            'label' => 'Men Perfumes',
            'url' => '/perfumes?category=men-perfumes',
            'sort_order' => 1,
            'is_active' => true,
        ]);
        MenuItem::create(['parent_id' => $men->id, 'label' => 'Eau de Parfum', 'url' => '/perfumes?category=men-eau-de-parfum', 'sort_order' => 1, 'is_active' => true]);
        MenuItem::create(['parent_id' => $men->id, 'label' => 'Parfum Extraits', 'url' => '/perfumes?category=men-parfum-extraits', 'sort_order' => 2, 'is_active' => true]);
        MenuItem::create(['parent_id' => $men->id, 'label' => 'Woody & Smoked Leather', 'url' => '/perfumes?category=woody-leather', 'sort_order' => 3, 'is_active' => true]);
        MenuItem::create(['parent_id' => $men->id, 'label' => 'Fresh Citrus & Vetiver', 'url' => '/perfumes?category=fresh-citrus', 'sort_order' => 4, 'is_active' => true]);

        // 2. Women Perfumes
        $women = MenuItem::create([
            'label' => 'Women Perfumes',
            'url' => '/perfumes?category=women-perfumes',
            'sort_order' => 2,
            'is_active' => true,
        ]);
        MenuItem::create(['parent_id' => $women->id, 'label' => 'Floral & Damask Rose', 'url' => '/perfumes?category=floral-rose', 'sort_order' => 1, 'is_active' => true]);
        MenuItem::create(['parent_id' => $women->id, 'label' => 'Amber & Bourbon Vanilla', 'url' => '/perfumes?category=amber-vanilla', 'sort_order' => 2, 'is_active' => true]);
        MenuItem::create(['parent_id' => $women->id, 'label' => 'Parfum Extraits', 'url' => '/perfumes?category=women-parfum-extraits', 'sort_order' => 3, 'is_active' => true]);
        MenuItem::create(['parent_id' => $women->id, 'label' => 'Gourmand & White Musk', 'url' => '/perfumes?category=gourmand-musk', 'sort_order' => 4, 'is_active' => true]);

        // 3. Unisex & Rare Oud
        $unisex = MenuItem::create([
            'label' => 'Unisex & Rare Oud',
            'url' => '/perfumes?category=unisex-rare-oud',
            'sort_order' => 3,
            'is_active' => true,
        ]);
        MenuItem::create(['parent_id' => $unisex->id, 'label' => 'Cambodian & Laotian Oud', 'url' => '/perfumes?category=rare-oud', 'sort_order' => 1, 'is_active' => true]);
        MenuItem::create(['parent_id' => $unisex->id, 'label' => 'Incense & Silver Resins', 'url' => '/perfumes?category=incense-resins', 'sort_order' => 2, 'is_active' => true]);
        MenuItem::create(['parent_id' => $unisex->id, 'label' => 'Private Reserve Flacons', 'url' => '/perfumes?category=private-reserve', 'sort_order' => 3, 'is_active' => true]);

        // 4. Gifts & Discovery
        $gifts = MenuItem::create([
            'label' => 'Gifts & Sets',
            'url' => '/perfumes?category=gifts-sets',
            'sort_order' => 4,
            'is_active' => true,
        ]);
        MenuItem::create(['parent_id' => $gifts->id, 'label' => 'Discovery Quads', 'url' => '/perfumes?category=discovery-quads', 'sort_order' => 1, 'is_active' => true]);
        MenuItem::create(['parent_id' => $gifts->id, 'label' => 'Luxury Gift Coffrets', 'url' => '/perfumes?category=gift-coffrets', 'sort_order' => 2, 'is_active' => true]);
        MenuItem::create(['parent_id' => $gifts->id, 'label' => 'Pocket Atomizers', 'url' => '/perfumes?category=pocket-atomizers', 'sort_order' => 3, 'is_active' => true]);

        // 5. Iconic Editions
        $editions = MenuItem::create([
            'label' => 'Iconic Editions',
            'url' => '/perfumes?category=iconic-editions',
            'sort_order' => 5,
            'is_active' => true,
        ]);
        MenuItem::create(['parent_id' => $editions->id, 'label' => 'The Alchemy Series', 'url' => '/perfumes?category=alchemy-series', 'sort_order' => 1, 'is_active' => true]);
        MenuItem::create(['parent_id' => $editions->id, 'label' => 'Night Flacons', 'url' => '/perfumes?category=night-flacons', 'sort_order' => 2, 'is_active' => true]);

        // 6. All Fragrances
        MenuItem::create([
            'label' => 'All Fragrances',
            'url' => '/perfumes',
            'sort_order' => 6,
            'is_active' => true,
        ]);
    }
}
