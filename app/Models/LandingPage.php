<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingPage extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'hero_title',
        'subtitle',
        'regular_price',
        'offer_price',
        'offer_end_date',
        'theme_color',
        'text_color',
        'background_color',
        'other_color',
        'primary_button_text',
        'secondary_button_text',
        'youtube_video_url',
        'youtube_autoplay',
        'features',
        'feature_images',
        'why_choose_us',
        'media_banners',
        'reviews',
        'gallery_images',
        'faqs',
        'short_description',
        'long_description',
        'homepage_product_title',
        'show_product_section',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'features' => 'array',
            'feature_images' => 'array',
            'why_choose_us' => 'array',
            'media_banners' => 'array',
            'reviews' => 'array',
            'gallery_images' => 'array',
            'faqs' => 'array',
            'regular_price' => 'decimal:2',
            'offer_price' => 'decimal:2',
            'offer_end_date' => 'datetime',
            'youtube_autoplay' => 'boolean',
            'show_product_section' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'landing_page_product')
            ->withPivot(['regular_price', 'offer_price'])
            ->withTimestamps();
    }
}
