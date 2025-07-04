@extends('layouts.app')

@section('title', 'Detail Pesanan')

@push('styles')
<style>
    .order-header {
        background: linear-gradient(135deg, #ff6b6b, #ffa500);
        padding: 20px;
        color: white;
        box-shadow: 0 4px 20px rgba(255, 107, 107, 0.3);
    }

    .order-header h1 {
        font-size: 20px;
        font-weight: 700;
        margin: 0 0 8px 0;
    }

    .order-number {
        font-size: 14px;
        opacity: 0.9;
    }

    .order-content {
        padding: 20px;
    }

    .status-card {
        background: white;
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        text-align: center;
    }

    .status-icon {
        font-size: 60px;
        margin-bottom: 16px;
    }

    .status-text {
        font-size: 18px;
        font-weight: 700;
        color: #f59e0b;
        margin-bottom: 8px;
    }

    .status-desc {
        color: #64748b;
        font-size: 14px;
    }

    .customer-info {
        background: white;
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .info-title {
        font-size: 16px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 12px;
    }

    .info-row {
        display: flex;
        margin-bottom: 8px;
    }

    .info-label {
        font-weight: 600;
        color: #64748b;
        min-width: 80px;
    }

    .info-value {
        color: #1e293b;
    }

    .items-card {
        background: white;
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .order-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .order-item:last-child {
        border-bottom: none;
    }

    .item-info {
        flex: 1;
    }

    .item-name {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 4px;
    }

    .item-qty {
        color: #64748b;
        font-size: 14px;
    }

    .item-total {
        font-weight: 700;
        color: #dc2626;
    }

    .summary-card {
        background: white;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        font-size: 14px;
    }

    .summary-row.total {
        font-size: 16px;
        font-weight: 700;
        color: #dc2626;
        border-top: 1px solid #e2e8f0;
        padding-top: 12px;
        margin-top: 12px;
    }
</style>
@endpush

@section('content')
<!-- Header -->
<div class="order-header">
    <h1>Detail Pesanan</h1>
    <div class="order-number">{{ $order->order_number }}</div>
</div>

<div class="order-content">
    <!-- Status -->
    <div class="status-card">
        <div class="status-icon">⏳</div>
        <div class="status-text">{{ ucfirst($order->status) }}</div>
        <div class="status-desc">Pesanan sedang diproses</div>
    </div>

    <!-- Customer Info -->
    <div class="customer-info">
        <div class="info-title">📋 Informasi Pelanggan</div>
        <div class="info-row">
            <span class="info-label">Nama:</span>
            <span class="info-value">{{ $order->customer_name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Telepon:</span>
            <span class="info-value">{{ $order->customer_phone }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Alamat:</span>
            <span class="info-value">{{ $order->customer_address }}</span>
        </div>
        @if($order->notes)
            <div class="info-row">
                <span class="info-label">Catatan:</span>
                <span class="info-value">{{ $order->notes }}</span>
            </div>
        @endif
    </div>

    <!-- Order Items -->
    <div class="items-card">
        <div class="info-title">🍽️ Item Pesanan</div>
        @foreach($order->items as $item)
            <div class="order-item">
                <div class="item-info">
                    <div class="item-name">{{ $item['name'] }}</div>
                    <div class="item-qty">{{ $item['quantity'] }}x Rp{{ number_format($item['price'], 0, ',', '.') }}</div>
                </div>
                <div class="item-total">Rp{{ number_format($item['total'], 0, ',', '.') }}</div>
            </div>
        @endforeach
    </div>

    <!-- Summary -->
    <div class="summary-card">
        <div class="info-title">💰 Ringkasan Pembayaran</div>
        <div class="summary-row">
            <span>Subtotal</span>
            <span>Rp{{ number_format($order->subtotal, 0, ',', '.') }}</span>
        </div>
        <div class="summary-row">
            <span>Biaya Pengiriman</span>
            <span>Rp{{ number_format($order->delivery_fee, 0, ',', '.') }}</span>
        </div>
        <div class="summary-row">
            <span>Biaya Layanan</span>
            <span>Rp{{ number_format($order->service_fee, 0, ',', '.') }}</span>
        </div>
        <div class="summary-row total">
            <span>Total</span>
            <span>Rp{{ number_format($order->total, 0, ',', '.') }}</span>
        </div>
    </div>
</div>
@endsection
