<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $fillable = [
        'name',
        'title',
        'subtitle',
        'banner_image_url',
        'button_text',
        'button_link',
        'is_active',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'campaign_product');
    }
}
