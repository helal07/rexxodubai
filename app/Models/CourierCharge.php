<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourierCharge extends Model
{
    protected $fillable = [
        'district_name',
        'charge',
        'zone_type',
        'is_active',
    ];

    protected $casts = [
        'charge'    => 'float',
        'is_active' => 'boolean',
    ];

    /**
     * Get the delivery charge for a specific city/district name.
     * Returns default charge if no exact match is found.
     */
    public static function getChargeForCity(string $city): float
    {
        $record = static::where('is_active', true)
            ->where('district_name', 'like', '%' . trim($city) . '%')
            ->first();

        if ($record) {
            return (float) $record->charge;
        }

        // Fallback: outside Dhaka rate
        $fallback = static::where('is_active', true)
            ->where('zone_type', 'outside_dhaka')
            ->first();

        return $fallback ? (float) $fallback->charge : 120.00;
    }
}
