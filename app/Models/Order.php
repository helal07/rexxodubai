<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'city',
        'postal_code',
        'subtotal',
        'total_amount',
        'shipping_cost',
        'discount_amount',
        'status',
        'payment_status',
        'payment_method',
        'courier_name',
        'courier_tracking_id',
        'courier_consignment_id',
        'courier_status',
        'courier_response',
        'dispatched_at',
    ];

    protected $casts = [
        'total_amount'     => 'decimal:2',
        'shipping_cost'    => 'decimal:2',
        'courier_response' => 'array',
        'dispatched_at'    => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
