<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'scent_family',
        'concentration',
        'sizes',
        'price',
        'short_description',
        'description',
        'notes_top',
        'notes_heart',
        'notes_base',
        'primary_image_url',
        'secondary_image_url',
        'gender',
        'is_featured',
        'is_new_arrival',
        'stock',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image_url',
    ];

    protected $casts = [
        'sizes' => 'array',
        'price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_new_arrival' => 'boolean',
        'stock' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order', 'asc');
    }

    public function campaigns()
    {
        return $this->belongsToMany(Campaign::class, 'campaign_product');
    }
}
