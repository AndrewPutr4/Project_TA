<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'order_date' => 'date',
        'subtotal' => 'decimal:2',
        'service_fee' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    /**
     * Relasi utama ke OrderItems
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Alias untuk kompatibilitas dengan kode lama
     */
    public function itemsRelation(): HasMany
    {
        return $this->orderItems();
    }

    /**
     * Relasi ke Transaction
     */
    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    /**
     * Accessor for status badge CSS class.
     */
    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            'pending'   => 'bg-yellow-100 text-yellow-800',
            'confirmed' => 'bg-blue-100 text-blue-800',
            'preparing' => 'bg-orange-100 text-orange-800',
            'ready'     => 'bg-cyan-100 text-cyan-800',
            'completed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
        ];

        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Accessor for payment status badge CSS class.
     */
    public function getPaymentStatusBadgeAttribute(): string
    {
        $badges = [
            'unpaid'   => 'bg-red-100 text-red-800',
            'paid'     => 'bg-green-100 text-green-800',
            'refunded' => 'bg-gray-100 text-gray-800',
        ];

        return $badges[$this->payment_status] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeByStatus($query, $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }
        return $query;
    }

    /**
     * Scope untuk filter berdasarkan payment status
     */
    public function scopeByPaymentStatus($query, $paymentStatus)
    {
        if ($paymentStatus) {
            return $query->where('payment_status', $paymentStatus);
        }
        return $query;
    }

    /**
     * Scope untuk pencarian
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }
        return $query;
    }
}
