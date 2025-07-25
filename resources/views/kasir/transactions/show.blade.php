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
                {{-- ✅ PERBAIKAN: Ganti orderItems menjadi items --}}
                <span class="item-count">{{ $transaction->order->items->count() }} item</span>
            </div>
            <div class="card-content">
                <div class="items-list">
                    {{-- ✅ PERBAIKAN: Ganti orderItems menjadi items --}}
                    @foreach($transaction->order->items as $item)
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

<style>
/* ... (CSS Anda tidak perlu diubah) ... */
.order-detail-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 1rem;
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    min-height: 100vh;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
    padding: 2rem;
    border-radius: 16px;
    box-shadow: 0 10px 25px rgba(245, 158, 11, 0.3);
    position: relative;
    overflow: hidden;
}

.page-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: rotate 20s linear infinite;
}

@keyframes rotate {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.header-left {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    position: relative;
    z-index: 2;
}

.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    background: rgba(255,255,255,0.2);
    color: white;
    text-decoration: none;
    border-radius: 12px;
    font-size: 0.875rem;
    font-weight: 600;
    transition: all 0.3s ease;
    border: 1px solid rgba(255,255,255,0.3);
}

.back-btn:hover {
    background: rgba(255,255,255,0.3);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.page-title {
    font-size: 2rem;
    font-weight: 800;
    margin: 0;
}

.header-actions {
    position: relative;
    z-index: 2;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 12px;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    position: relative;
    overflow: hidden;
}

.btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.btn:hover::before {
    left: 100%;
}

.btn-primary {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(245, 158, 11, 0.3);
}

.order-detail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    grid-template-rows: auto auto;
}

.order-items-card {
    grid-column: 1 / -1;
}

.order-info-card, .order-summary-card, .order-items-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(245, 158, 11, 0.15);
    transition: all 0.3s ease;
    border: 2px solid rgba(245, 158, 11, 0.1);
}

.order-info-card:hover, .order-summary-card:hover, .order-items-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(245, 158, 11, 0.2);
}

.card-header {
    padding: 1.5rem;
    border-bottom: 2px solid #fde68a;
    background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-header h2 {
    font-size: 1.25rem;
    font-weight: 700;
    color: #92400e;
    margin: 0;
}

.item-count {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 600;
}

.card-content {
    padding: 2rem;
}

.info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.info-item label {
    font-size: 0.875rem;
    font-weight: 600;
    color: #a16207;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-item span {
    font-size: 1rem;
    font-weight: 700;
    color: #92400e;
}

.summary-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #fde68a;
    font-size: 0.875rem;
}

.summary-item:last-child {
    border-bottom: none;
}

.summary-item.total {
    font-size: 1.25rem;
    font-weight: 700;
    color: #92400e;
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    margin: 1rem -2rem -2rem -2rem;
    padding: 1.5rem 2rem;
    border-radius: 0 0 12px 12px;
    border-bottom: none;
}

.items-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.order-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
    border-radius: 12px;
    border: 2px solid #fde68a;
    transition: all 0.3s ease;
}

.order-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(245, 158, 11, 0.15);
}

.item-info {
    flex: 1;
}

.item-name {
    font-size: 1.125rem;
    font-weight: 700;
    color: #92400e;
    margin: 0;
}

.item-details {
    display: flex;
    align-items: center;
    gap: 1rem;
    font-size: 0.875rem;
}

.item-price {
    color: #a16207;
    font-weight: 500;
}

.item-qty {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-weight: 600;
}

.item-subtotal {
    font-weight: 700;
    color: #92400e;
}

@media (max-width: 768px) {
    .order-detail-container {
        padding: 0.5rem;
    }

    .page-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }

    .header-left {
        flex-direction: column;
        gap: 1rem;
        align-items: center;
    }

    .page-title {
        font-size: 1.5rem;
    }

    .order-detail-grid {
        grid-template-columns: 1fr;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }

    .order-item {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }

    .item-details {
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .card-content {
        padding: 1rem;
    }

    .page-header {
        padding: 1.5rem;
    }
}
</style>
@endsection
