<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'menu_id',
        'menu_name',
        'menu_description',
        'price',
        'quantity',
        'subtotal',
    ];

    /**
     * Mendefinisikan relasi "belongs to" ke model Order.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Mendefinisikan relasi "belongs to" ke model Menu.
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
