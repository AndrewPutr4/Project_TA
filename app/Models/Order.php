<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str; // <-- 1. Pastikan Str di-import

class Order extends Model
{
    use HasFactory;

    /**
     * ✅ 2. Konfigurasi untuk menggunakan UUID sebagai Primary Key.
     * Baris-baris ini sangat penting.
     */
    public $incrementing = false; // Memberitahu Laravel bahwa ID bukan angka yang berurutan.
    protected $keyType = 'string'; // Memberitahu Laravel bahwa tipe ID adalah string.
    protected $primaryKey = 'id'; // (Opsional, tapi baik untuk kejelasan)

    /**
     * ✅ 3. Gunakan $fillable untuk keamanan.
     * Ini adalah daftar kolom yang boleh diisi melalui Order::create().
     * 'id' dan 'order_number' tidak ada di sini karena dibuat otomatis.
     */
    protected $fillable = [
        'customer_name',
        'customer_phone',
        'customer_address',
        'table_number',
        'notes',
        'subtotal',
        'service_fee',
        'total',
        'status',
        'payment_status',
        'order_date',
    ];

    protected $casts = [
        'order_date' => 'date',
        // 'subtotal', 'service_fee', dan 'total' lebih baik disimpan sebagai integer (sen)
        // untuk menghindari masalah pembulatan desimal.
    ];

    /**
     * ✅ 4. Logika Pembuatan ID dan Nomor Pesanan Otomatis.
     * Ini adalah inti dari perbaikan. Kode ini akan berjalan otomatis
     * setiap kali Anda memanggil Order::create().
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            // Jika ID belum ada, buat UUID baru.
            if (empty($order->id)) {
                $order->id = (string) Str::uuid();
            }
            // Buat juga nomor pesanan yang unik.
            $order->order_number = 'ORD-' . now()->format('Ymd') . '-' . substr(str_replace('-', '', $order->id), 0, 6);
        });
    }

    /**
     * Relasi utama ke OrderItems
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Relasi ke Transaction
     */
    public function transaction()
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