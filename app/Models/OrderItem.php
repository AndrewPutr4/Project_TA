<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id', 'menus_id', 'price', 'quantity', 'subtotal', 'order_date'
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menus_id');
    }
}