@extends('layouts.kasir')
@section('title', 'Detail Transaksi')

@section('content')
<div class="order-detail-container">
    <div class="page-header">
        <div class="header-left">
            <a href="{{ route('kasir.transactions.index') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <h1 class="page-title">Detail Transaksi</h1>
        </div>
        <div class="header-actions">
            <a href="{{ route('kasir.transactions.receipt', $transaction) }}" target="_blank" class="btn btn-primary">
                <i class="fas fa-print"></i> Cetak Struk
            </a>
        </div>
    </div>

    <div class="order-detail-grid">
        <div class="order-info-card">
            <div class="card-header">
                <h2>Informasi Transaksi</h2>
            </div>
            <div class="card-content">
                <div class="info-grid">
                    <div class="info-item">
                        <label>No. Transaksi</label>
                        {{-- ✅ PERBAIKAN 1: Memotong nomor transaksi agar lebih pendek --}}
                        <span>#{{ substr($transaction->transaction_number, 0, 8) }}</span>
                    </div>
                    <div class="info-item">
                        <label>No. Order</label>
                        <span>#{{ $transaction->order->order_number }}</span>
                    </div>
                    <div class="info-item">
                        <label>Tanggal</label>
                        <span>{{ $transaction->created_at->format('d F Y, H:i') }}</span>
                    </div>
                    <div class="info-item">
                        <label>Kasir</label>
                        {{-- ✅ PERBAIKAN 2: Menampilkan "Midtrans" jika kasir tidak ada --}}
                        <span>{{ $transaction->kasir->name ?? 'Midtrans' }}</span>
                    </div>
                    <div class="info-item">
                        <label>Customer</label>
                        <span>{{ $transaction->customer_name }}</span>
                    </div>
                     <div class="info-item">
                        <label>Metode Pembayaran</label>
                        <span>{{ ucfirst($transaction->payment_method) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="order-summary-card">
            <div class="card-header">
                <h2>Ringkasan Pembayaran</h2>
            </div>
            <div class="card-content">
                <div class="summary-list">
                    <div class="summary-item">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @if($transaction->service_fee > 0)
                    <div class="summary-item">
                        <span>Biaya Layanan</span>
                        <span>Rp {{ number_format($transaction->service_fee, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <div class="summary-item total">
                        <span>Total</span>
                        <span>Rp {{ number_format($transaction->total, 0, ',', '.') }}</span>
                    </div>
                     @if($transaction->payment_method == 'cash')
                    <div class="summary-item">
                        <span>Uang Diterima</span>
                        <span>Rp {{ number_format($transaction->cash_received, 0, ',', '.') }}</span>
                    </div>
                     <div class="summary-item">
                        <span>Kembalian</span>
                        <span>Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="order-items-card">
            <div class="card-header">
                <h2>Item yang Dibeli</h2>
                <span class="item-count">{{ $transaction->order->orderItems->count() }} item</span>
            </div>
            <div class="card-content">
                <div class="items-list">
                    @foreach($transaction->order->orderItems as $item)
                    <div class="order-item">
                        <div class="item-info">
                            <h3 class="item-name">{{ $item->menu_name }}</h3>
                        </div>
                        <div class="item-details">
                            <div class="item-price">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                            <div class="item-qty">x{{ $item->quantity }}</div>
                            <div class="item-subtotal">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection