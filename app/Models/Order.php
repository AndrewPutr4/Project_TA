<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_date', 'table_number', 'total', 'status'
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}