<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Category::count() > 0) {
            return; // Safe policy: Never overwrite existing categories
        }
        $categoriesStructure = [
            [
                'name' => 'Men Perfumes',
                'slug' => 'men-perfumes',
                'description' => 'Architectural, smoky, leather, and wood extraits crafted for refined presence.',
                'image_url' => 'https://images.unsplash.com/photo-1523293182086-7651a899d37f?auto=format&fit=crop&w=800&q=80',
                'sort_order' => 1,
                'subcategories' => [
                    ['name' => 'Eau de Parfum', 'slug' => 'men-eau-de-parfum', 'description' => 'Concentrated daily signature fragrances.'],
                    ['name' => 'Parfum Extraits', 'slug' => 'men-parfum-extraits', 'description' => 'Pure extrait strength with lasting 24-hour sillage.'],
                    ['name' => 'Woody & Smoked Leather', 'slug' => 'woody-leather', 'description' => 'Birch tar, cade wood, and dark cocoa.'],
                    ['name' => 'Fresh Citrus & Vetiver', 'slug' => 'fresh-citrus', 'description' => 'Calabrian bergamot and crisp Haitian vetiver.'],
                ],
            ],
            [
                'name' => 'Women Perfumes',
                'slug' => 'women-perfumes',
                'description' => 'Sculpted florals, velvet damask roses, and warm liquid amber vapor.',
                'image_url' => 'https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&w=800&q=80',
                'sort_order' => 2,
                'subcategories' => [
                    ['name' => 'Floral & Damask Rose', 'slug' => 'floral-rose', 'description' => 'Rare May roses harvested at dawn.'],
                    ['name' => 'Amber & Bourbon Vanilla', 'slug' => 'amber-vanilla', 'description' => 'Golden resinous amber tempered by Madagascar vanilla.'],
                    ['name' => 'Parfum Extraits', 'slug' => 'women-parfum-extraits', 'description' => 'Pure luxury extraits in heavy crystal flacons.'],
                    ['name' => 'Gourmand & White Musk', 'slug' => 'gourmand-musk', 'description' => 'Silken musk and delicate honey accords.'],
                ],
            ],
            [
                'name' => 'Unisex & Rare Oud',
                'slug' => 'unisex-rare-oud',
                'description' => 'Genderless high-perfumery blending rare agarwood, mineral incense, and crystalline musks.',
                'image_url' => 'https://images.unsplash.com/photo-1588405748880-12d1d2a59f75?auto=format&fit=crop&w=800&q=80',
                'sort_order' => 3,
                'subcategories' => [
                    ['name' => 'Cambodian & Laotian Oud', 'slug' => 'rare-oud', 'description' => 'Aged wild agarwood extractions.'],
                    ['name' => 'Incense & Silver Resins', 'slug' => 'incense-resins', 'description' => 'Translucent Oman frankincense and cold vapor.'],
                    ['name' => 'Private Reserve Flacons', 'slug' => 'private-reserve', 'description' => 'Limited vintage batches numbered by hand.'],
                ],
            ],
            [
                'name' => 'Gifts & Sets',
                'slug' => 'gifts-sets',
                'description' => 'Curated discovery sets and luxury coffrets housed in hard-edged papercraft gift cases.',
                'image_url' => 'https://images.unsplash.com/photo-1616949755610-8c9bbc08f138?auto=format&fit=crop&w=800&q=80',
                'sort_order' => 4,
                'subcategories' => [
                    ['name' => 'Discovery Quads', 'slug' => 'discovery-quads', 'description' => 'Four 15ml refillable glass atomizers.'],
                    ['name' => 'Luxury Gift Coffrets', 'slug' => 'gift-coffrets', 'description' => 'Signature 100ml flacon with travel leather case.'],
                    ['name' => 'Pocket Atomizers', 'slug' => 'pocket-atomizers', 'description' => 'Solid brass and leather pocket sprays.'],
                ],
            ],
            [
                'name' => 'Iconic Editions',
                'slug' => 'iconic-editions',
                'description' => 'Master collections inspired by haute couture silhouette and sculptural glasscraft.',
                'image_url' => 'https://images.unsplash.com/photo-1547887537-6158d64c35b3?auto=format&fit=crop&w=800&q=80',
                'sort_order' => 5,
                'subcategories' => [
                    ['name' => 'The Alchemy Series', 'slug' => 'alchemy-series', 'description' => 'Dark amber glass flacons with gold-etched typography.'],
                    ['name' => 'Night Flacons', 'slug' => 'night-flacons', 'description' => 'Deep obsidian glass designed for evening intensity.'],
                ],
            ],
        ];

        foreach ($categoriesStructure as $catData) {
            $subcategories = $catData['subcategories'] ?? [];
            unset($catData['subcategories']);

            $parent = Category::updateOrCreate(
                ['slug' => $catData['slug']],
                [
                    'parent_id' => null,
                    'name' => $catData['name'],
                    'description' => $catData['description'],
                    'image_url' => $catData['image_url'],
                    'sort_order' => $catData['sort_order'],
                    'is_active' => true,
                ]
            );

            foreach ($subcategories as $index => $subData) {
                Category::updateOrCreate(
                    ['slug' => $subData['slug']],
                    [
                        'parent_id' => $parent->id,
                        'name' => $subData['name'],
                        'description' => $subData['description'] ?? null,
                        'sort_order' => $index + 1,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
