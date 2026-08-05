<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $catRose = Category::where('slug', 'floral-rose')->first() ?? Category::first();
        $catLeather = Category::where('slug', 'woody-leather')->first() ?? Category::first();
        $catVanilla = Category::where('slug', 'amber-vanilla')->first() ?? Category::first();
        $catOud = Category::where('slug', 'rare-oud')->first() ?? Category::first();
        $catCitrus = Category::where('slug', 'fresh-citrus')->first() ?? Category::first();
        $catGifts = Category::where('slug', 'discovery-quads')->first() ?? Category::first();

        // Products List
        $productsData = [
            [
                'name' => 'L\'Ombre d\'Ambre',
                'slug' => 'l-ombre-d-ambre',
                'category_id' => $catVanilla ? $catVanilla->id : null,
                'scent_family' => 'Amber Spice',
                'concentration' => 'Eau de Parfum',
                'sizes' => ['50ml', '100ml'],
                'price' => 240.00,
                'short_description' => 'Dark resinous amber softened by smoked vanilla bean and dry cedar.',
                'description' => 'L\'Ombre d\'Ambre is a study in shadow and light. Sculpted around aged labdanum and benzoin resin, it opens with a prickle of pink pepper before resolving into velvet amber and dry cedar wood.',
                'notes_top' => 'Pink Pepper, Cardamom, Bergamot',
                'notes_heart' => 'Labdanum, Tuscan Iris, Incense',
                'notes_base' => 'Smoked Vanilla, Amber Resins, Cedarwood',
                'primary_image_url' => 'https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&w=1000&q=80',
                'secondary_image_url' => 'https://images.unsplash.com/photo-1523293182086-7651a899d37f?auto=format&fit=crop&w=1000&q=80',
                'gender' => 'women',
                'is_featured' => true,
                'is_new_arrival' => true,
                'stock' => 45,
                'gallery' => [
                    'https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&w=1000&q=80',
                    'https://images.unsplash.com/photo-1547887537-6158d64c35b3?auto=format&fit=crop&w=1000&q=80',
                ],
            ],
            [
                'name' => 'Cuir Noir Extrait',
                'slug' => 'cuir-noir',
                'category_id' => $catLeather ? $catLeather->id : null,
                'scent_family' => 'Woody Leather',
                'concentration' => 'Parfum',
                'sizes' => ['100ml'],
                'price' => 310.00,
                'short_description' => 'Sartorial leather infused with birch tar, cade wood, and dark cocoa.',
                'description' => 'Inspired by architectural leatherwork and quiet nighttime rain, Cuir Noir carries an uncompromising profile of polished leather, cade wood smoke, and bitter cocoa.',
                'notes_top' => 'Bitter Almond, Italian Bergamot',
                'notes_heart' => 'Black Leather, Saffron, Violet Leaf',
                'notes_base' => 'Birch Tar, Patchouli, Dark Musk',
                'primary_image_url' => 'https://images.unsplash.com/photo-1523293182086-7651a899d37f?auto=format&fit=crop&w=1000&q=80',
                'secondary_image_url' => 'https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&w=1000&q=80',
                'gender' => 'men',
                'is_featured' => true,
                'is_new_arrival' => false,
                'stock' => 30,
                'gallery' => [
                    'https://images.unsplash.com/photo-1523293182086-7651a899d37f?auto=format&fit=crop&w=1000&q=80',
                ],
            ],
            [
                'name' => 'Velours de Rose',
                'slug' => 'velours-de-rose',
                'category_id' => $catRose ? $catRose->id : null,
                'scent_family' => 'Floral Amber',
                'concentration' => 'Eau de Parfum',
                'sizes' => ['50ml', '100ml'],
                'price' => 260.00,
                'short_description' => 'Damask rose steeped in golden amber and white musk vapor.',
                'description' => 'Velours de Rose presents a architectural rose — dewy yet structured, balanced by dry wood and translucent amber tones.',
                'notes_top' => 'Mandarin, Blackcurrant, Pink Pepper',
                'notes_heart' => 'Damask Rose, Turkish Rose Absolute, Geranium',
                'notes_base' => 'Ambergris, White Musk, Sandalwood',
                'primary_image_url' => 'https://images.unsplash.com/photo-1547887537-6158d64c35b3?auto=format&fit=crop&w=1000&q=80',
                'secondary_image_url' => 'https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&w=1000&q=80',
                'gender' => 'women',
                'is_featured' => false,
                'is_new_arrival' => true,
                'stock' => 50,
                'gallery' => [
                    'https://images.unsplash.com/photo-1547887537-6158d64c35b3?auto=format&fit=crop&w=1000&q=80',
                ],
            ],
            [
                'name' => 'Vapour d\'Oud',
                'slug' => 'vapour-d-oud',
                'category_id' => $catOud ? $catOud->id : null,
                'scent_family' => 'Rare Oud',
                'concentration' => 'Parfum',
                'sizes' => ['100ml'],
                'price' => 380.00,
                'short_description' => 'Aged Cambodian oud diffused with white incense and mineral amber.',
                'description' => 'An ethereal interpretation of precious agarwood. Vapour d\'Oud floats off the skin in crisp mineral waves before anchoring into deep resinous oud.',
                'notes_top' => 'Mineral Accord, White Pepper',
                'notes_heart' => 'Oman Incense, Papyrus, Cypress',
                'notes_base' => 'Cambodian Oud, Guaiac Wood, Vetiver',
                'primary_image_url' => 'https://images.unsplash.com/photo-1588405748880-12d1d2a59f75?auto=format&fit=crop&w=1000&q=80',
                'secondary_image_url' => 'https://images.unsplash.com/photo-1523293182086-7651a899d37f?auto=format&fit=crop&w=1000&q=80',
                'gender' => 'unisex',
                'is_featured' => true,
                'is_new_arrival' => true,
                'stock' => 20,
                'gallery' => [
                    'https://images.unsplash.com/photo-1588405748880-12d1d2a59f75?auto=format&fit=crop&w=1000&q=80',
                ],
            ],
            [
                'name' => 'Cèdre Brut',
                'slug' => 'cedre-brut',
                'category_id' => $catCitrus ? $catCitrus->id : null,
                'scent_family' => 'Woody Citrus',
                'concentration' => 'Eau de Parfum',
                'sizes' => ['50ml', '100ml'],
                'price' => 220.00,
                'short_description' => 'Raw Atlas cedar cut with crisp bergamot and dry vetiver root.',
                'description' => 'Cèdre Brut captures the tactile sensation of unpolished timber and mountain air. Clean, invigorating, and sharply tailored.',
                'notes_top' => 'Calabrian Bergamot, Lemon Zest',
                'notes_heart' => 'Atlas Cedar, Juniper Berry',
                'notes_base' => 'Haitian Vetiver, Iso E Super, Amber',
                'primary_image_url' => 'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?auto=format&fit=crop&w=1000&q=80',
                'secondary_image_url' => 'https://images.unsplash.com/photo-1547887537-6158d64c35b3?auto=format&fit=crop&w=1000&q=80',
                'gender' => 'men',
                'is_featured' => false,
                'is_new_arrival' => false,
                'stock' => 60,
                'gallery' => [
                    'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?auto=format&fit=crop&w=1000&q=80',
                ],
            ],
            [
                'name' => 'The Discovery Quad',
                'slug' => 'the-discovery-quad',
                'category_id' => $catGifts ? $catGifts->id : null,
                'scent_family' => 'Curated Selection',
                'concentration' => 'Eau de Parfum',
                'sizes' => ['4x15ml'],
                'price' => 140.00,
                'short_description' => 'Four signature perfumes in 15ml refillable glass atomizers.',
                'description' => 'A complete introduction to the ReXxo Bd perfume house. Includes L\'Ombre d\'Ambre, Cuir Noir, Velours de Rose, and Cèdre Brut.',
                'notes_top' => 'Various Top Notes',
                'notes_heart' => 'Various Heart Notes',
                'notes_base' => 'Various Base Notes',
                'primary_image_url' => 'https://images.unsplash.com/photo-1616949755610-8c9bbc08f138?auto=format&fit=crop&w=1000&q=80',
                'secondary_image_url' => 'https://images.unsplash.com/photo-1588405748880-12d1d2a59f75?auto=format&fit=crop&w=1000&q=80',
                'gender' => 'unisex',
                'is_featured' => true,
                'is_new_arrival' => true,
                'stock' => 100,
                'gallery' => [
                    'https://images.unsplash.com/photo-1616949755610-8c9bbc08f138?auto=format&fit=crop&w=1000&q=80',
                ],
            ],
        ];

        foreach ($productsData as $pData) {
            $gallery = $pData['gallery'] ?? [];
            unset($pData['gallery']);

            $product = Product::updateOrCreate(['slug' => $pData['slug']], $pData);

            if (!empty($gallery)) {
                $product->images()->delete();
                foreach ($gallery as $idx => $img) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_url' => $img,
                        'sort_order' => $idx,
                    ]);
                }
            }
        }
    }
}
