<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_number',
        'customer_name',
        'customer_phone',
        'customer_address',
        'table_number',
        'notes',
        'subtotal',
        'service_fee',
        //'delivery_fee',
        'total',
        'status',
        'payment_status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
    ];

    /**
     * Mendefinisikan relasi "has many" ke model OrderItem.
     */
    public function itemsRelation()
    {
        return $this->hasMany(OrderItem::class);
    }
}
