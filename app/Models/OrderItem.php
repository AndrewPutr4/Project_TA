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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'menu_options' => 'array',
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'order_date' => 'datetime',
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

    /**
     * Alias untuk kompatibilitas
     */
    public function getMenuPriceAttribute()
    {
        return $this->price;
    }
}
