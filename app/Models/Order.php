<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    /**
     * Konfigurasi untuk menggunakan UUID sebagai Primary Key.
     */
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'id';

    /**
     * Mass assignable attributes.
     */
    protected $fillable = [
        'customer_name',
        'table_number',
        'notes',
        'subtotal',
        'service_fee',
        'total',
        'status',
        'payment_method', // <-- DITAMBAHKAN
        'payment_status',
        'order_date',
        'order_number',
    ];

    /**
     * Attribute casting.
     */
    protected $casts = [
        'order_date' => 'datetime',
        'subtotal' => 'decimal:2',
        'service_fee' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    /**
     * Boot method untuk auto-generate ID dan order number.
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($order) {
            // Generate UUID untuk ID jika belum ada
            if (empty($order->id)) {
                $order->id = (string) Str::uuid();
            }
            
            // Generate order number jika belum ada
            if (empty($order->order_number)) {
                $order->order_number = 'ORD-' . now()->format('Ymd') . '-' . strtoupper(substr(str_replace('-', '', $order->id), 0, 6));
            }
        });
    }

    /**
     * Relasi ke OrderItems
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Relasi ke Transaction
     */
    public function transaction(): HasOne
    {
        return $this->hasOne(Transaction::class);
    }

    /**
     * Accessor untuk status badge CSS class.
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
     * Accessor untuk payment status badge CSS class.
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
     * Accessor untuk order type berdasarkan table_number
     */
    public function getOrderTypeAttribute(): string
    {
        return $this->table_number ? 'dine_in' : 'takeaway';
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
     * Scope untuk pencarian (tanpa customer_phone)
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }
        return $query;
    }

    /**
     * Scope untuk filter hari ini
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', now()->toDateString());
    }

    /**
     * Scope untuk filter berdasarkan order type
     */
    public function scopeByOrderType($query, $orderType)
    {
        if ($orderType === 'dine_in') {
            return $query->whereNotNull('table_number');
        } elseif ($orderType === 'takeaway') {
            return $query->whereNull('table_number');
        }
        return $query;
    }
}