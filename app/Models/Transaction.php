<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $fillable = [
        'transaction_number',
        'order_id',
        'kasir_id',
        'customer_name',
        'customer_phone',
        'subtotal',
        'tax',
        'service_fee',
        'discount',
        'total',
        'cash_received',
        'change_amount',
        'payment_method',
        'status',
        'notes',
        'transaction_date',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'service_fee' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'cash_received' => 'decimal:2',
        'change_amount' => 'decimal:2',
        'transaction_date' => 'datetime',
    ];

    /**
     * Relationship with Order
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relationship with Kasir (User)
     */
    public function kasir()
    {
        return $this->belongsTo(User::class, 'kasir_id');
    }

    /**
     * Create transaction from order data
     */
    public static function createFromOrder(Order $order, array $paymentData, ?int $kasirId)
    {
        $subtotal = $order->subtotal;
        $serviceFee = $order->service_fee ?? 0;
        $tax = $subtotal * 0.1; // 10% tax
        $discount = $paymentData['discount'] ?? 0;
        $total = $subtotal + $serviceFee + $tax - $discount;

        // Calculate cash received and change for cash payments
        $cashReceived = $paymentData['payment_method'] === 'cash' ? ($paymentData['cash_received'] ?? $total) : $total;
        $changeAmount = $paymentData['payment_method'] === 'cash' ? max(0, $cashReceived - $total) : 0;

        return self::create([
            'transaction_number' => self::generateTransactionNumber(),
            'order_id' => $order->id,
            'kasir_id' => $kasirId,
            'customer_name' => $order->customer_name,
            'customer_phone' => $order->customer_phone,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'service_fee' => $serviceFee,
            'discount' => $discount,
            'total' => $total,
            'cash_received' => $cashReceived,
            'change_amount' => $changeAmount,
            'payment_method' => $paymentData['payment_method'],
            'status' => 'completed',
            'notes' => $paymentData['notes'] ?? null,
            'transaction_date' => now(),
        ]);
    }

    /**
     * Generate unique transaction number
     */
    public static function generateTransactionNumber()
    {
        $date = Carbon::now()->format('Ymd');
        $lastTransaction = self::whereDate('created_at', Carbon::today())
                              ->orderBy('created_at', 'desc')
                              ->first();
        
        // ✅ FIX: Perbaiki akses ke ID dengan null check
        $sequence = 1;
        if ($lastTransaction && $lastTransaction->transaction_number) {
            $lastSequence = (int)substr($lastTransaction->transaction_number, -4);
            $sequence = $lastSequence + 1;
        }
        
        return 'TRX' . $date . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get status badge class for UI
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'completed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
        ];

        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Get payment method display name
     */
    public function getPaymentMethodDisplayAttribute()
    {
        $methods = [
            'cash' => 'Tunai',
            'card' => 'Kartu',
            'qris' => 'QRIS',
            'transfer' => 'Transfer',
        ];

        return $methods[$this->payment_method] ?? 'Unknown';
    }

    /**
     * Scope untuk filter berdasarkan tanggal
     */
    public function scopeByDate($query, $date)
    {
        if ($date) {
            return $query->whereDate('transaction_date', $date);
        }
        return $query;
    }

    /**
     * Scope untuk filter berdasarkan payment method
     */
    public function scopeByPaymentMethod($query, $method)
    {
        if ($method) {
            return $query->where('payment_method', $method);
        }
        return $query;
    }
}
