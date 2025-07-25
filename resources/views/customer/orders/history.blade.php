@extends('layouts.app')

@section('title', 'Riwayat Pesanan Anda')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    :root {
        --primary-orange: #f97316;
        --dark-orange: #ea580c;
        --light-gray: #f8fafc;
        --medium-gray: #e2e8f0;
        --dark-gray: #1e293b;
        --text-secondary: #64748b;
        --green-color: #10b981;
        --yellow-color: #f59e0b;
        --red-color: #ef4444;
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background-color: var(--light-gray);
        color: var(--dark-gray);
        line-height: 1.6;
    }

    .container {
        max-width: 900px;
        margin: 0 auto;
        padding: 1.5rem;
    }

    /* Page Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid var(--medium-gray);
    }

    .page-title {
        font-size: 2rem;
        font-weight: 800;
        color: var(--dark-gray);
    }
    
    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.25rem;
        background: white;
        color: var(--dark-gray);
        text-decoration: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        border: 1px solid var(--medium-gray);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .back-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        background: var(--primary-orange);
        color: white;
        border-color: var(--primary-orange);
    }

    /* Order List */
    .order-list {
        display: grid;
        gap: 1.5rem;
    }

    .order-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.07);
        border: 1px solid var(--medium-gray);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .order-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.1);
    }

    .order-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
        background: #f1f5f9;
        border-bottom: 1px solid var(--medium-gray);
    }

    .order-number {
        font-weight: 700;
        color: var(--dark-gray);
    }

    .order-status {
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .status-pending { background-color: #fef3c7; color: #d97706; }
    .status-completed { background-color: #d1fae5; color: #059669; }
    .status-cancelled { background-color: #fee2e2; color: #dc2626; }
    /* Add other statuses as needed */
    .status-confirmed { background-color: #dbeafe; color: #2563eb; }
    .status-preparing { background-color: #ffedd5; color: #f97316; }
    .status-ready { background-color: #cffafe; color: #0891b2; }
    .status-default { background-color: #e5e7eb; color: #4b5563; }


    .order-card-body {
        padding: 1.5rem;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1.5rem;
    }

    .info-block {
        display: flex;
        flex-direction: column;
    }

    .info-label {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin-bottom: 0.25rem;
    }

    .info-value {
        font-size: 1rem;
        font-weight: 600;
    }

    .order-card-footer {
        padding: 1rem 1.5rem;
        background: var(--light-gray);
        border-top: 1px solid var(--medium-gray);
        text-align: right;
    }

    .detail-button {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.6rem 1.2rem;
        background: var(--primary-orange);
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.3s ease;
    }

    .detail-button:hover {
        background: var(--dark-orange);
        transform: scale(1.05);
    }

    .no-orders {
        background: white;
        border-radius: 16px;
        padding: 3rem;
        text-align: center;
        border: 2px dashed var(--medium-gray);
    }

    .no-orders i {
        font-size: 3rem;
        color: var(--primary-orange);
        margin-bottom: 1rem;
    }

    .no-orders h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--dark-gray);
        margin-bottom: 0.5rem;
    }

    .no-orders p {
        color: var(--text-secondary);
        margin-bottom: 1.5rem;
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="page-header">
        <h1 class="page-title">Riwayat Pesanan</h1>
        <a href="{{ route('customer.welcome') }}" class="back-button">
            <i class="bi bi-arrow-left"></i> Kembali ke Menu
        </a>
    </div>

    @if($orders->isEmpty())
        <div class="no-orders">
            <i class="bi bi-cart-x"></i>
            <h3>Anda Belum Memiliki Pesanan</h3>
            <p>Semua pesanan yang Anda buat di sesi ini akan muncul di sini.</p>
            <a href="{{ route('customer.welcome') }}" class="detail-button">
                <i class="bi bi-cup-hot-fill"></i> Pesan Sekarang
            </a>
        </div>
    @else
        <div class="order-list">
            @foreach($orders as $order)
                <div class="order-card">
                    <div class="order-card-header">
                        <span class="order-number">Pesanan #{{ $order->order_number }}</span>
                        <span class="order-status status-{{ $order->status ?? 'default' }}">{{ ucfirst($order->status) }}</span>
                    </div>
                    <div class="order-card-body">
                        <div class="info-block">
                            <span class="info-label">Tanggal</span>
                            <span class="info-value">{{ $order->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="info-block">
                            <span class="info-label">Jumlah Item</span>
                            <span class="info-value">{{ $order->items->count() }} item</span>
                        </div>
                        <div class="info-block">
                            <span class="info-label">Total Bayar</span>
                            <span class="info-value">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="order-card-footer">
                        {{-- ✅ PERBAIKAN: Mengganti nama rute dari 'orders.show' menjadi 'order.show' --}}
                        <a href="{{ route('order.show', $order->id) }}" class="detail-button">
                            Lihat Detail <i class="bi bi-arrow-right-short"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
