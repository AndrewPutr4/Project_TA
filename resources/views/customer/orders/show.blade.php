@extends('layouts.app')
@section('title', 'Detail Pesanan')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    :root {
        --primary-orange: #f97316;
        --dark-orange: #ea580c;
        --light-orange: #fff7ed;
        --red-color: #ef4444;
        --green-color: #10b981;
        --blue-color: #3b82f6;
        --purple-color: #8b5cf6;
        --light-gray: #f8fafc;
        --medium-gray: #e2e8f0;
        --dark-gray: #1e293b;
        --text-secondary: #64748b;
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        color: var(--dark-gray);
        line-height: 1.6;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 900px;
        margin: 0 auto;
        padding: 2rem;
    }

    /* Page Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        background: white;
        padding: 1.5rem;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .page-title {
        font-size: 2rem;
        font-weight: 800;
        color: var(--dark-gray);
        margin: 0;
    }

    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem 1.5rem;
        background: linear-gradient(135deg, var(--primary-orange), var(--dark-orange));
        color: white;
        text-decoration: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 16px rgba(249, 115, 22, 0.3);
    }

    .back-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(249, 115, 22, 0.4);
        color: white;
    }

    /* Order Summary */
    .order-summary {
        background: linear-gradient(135deg, var(--primary-orange), var(--red-color));
        color: white;
        padding: 2.5rem;
        border-radius: 20px;
        margin-bottom: 2rem;
        box-shadow: 0 12px 40px rgba(249, 115, 22, 0.4);
        position: relative;
        overflow: hidden;
    }

    .order-summary::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        pointer-events: none;
    }

    .order-summary-content {
        position: relative;
        z-index: 1;
    }

    .order-title {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 1rem;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .order-meta {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-top: 1.5rem;
    }

    .meta-item {
        background: rgba(255, 255, 255, 0.15);
        padding: 1rem;
        border-radius: 12px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .meta-label {
        font-size: 0.875rem;
        opacity: 0.8;
        margin-bottom: 0.25rem;
    }

    .meta-value {
        font-size: 1.125rem;
        font-weight: 600;
    }

    /* Card Styles */
    .card-base {
        background: white;
        border-radius: 20px;
        margin-bottom: 2rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
        border: 1px solid #f1f5f9;
        overflow: hidden;
        position: relative;
    }

    .card-base::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-orange), var(--red-color));
    }

    .card-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 2rem 2rem 1rem 2rem;
        border-bottom: 1px solid var(--medium-gray);
    }

    .card-icon {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        color: white;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
    }

    .card-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--dark-gray);
    }

    .card-content {
        padding: 2rem;
    }

    /* Status Card */
    .status-card {
        text-align: center;
        background: linear-gradient(135deg, #fff7ed, #fed7aa);
        border: 2px solid var(--primary-orange);
    }

    .status-card::before {
        background: linear-gradient(90deg, var(--primary-orange), #fbbf24);
    }

    .status-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        display: block;
        animation: bounce 2s infinite;
    }

    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
        40% { transform: translateY(-10px); }
        60% { transform: translateY(-5px); }
    }

    .status-text {
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--dark-orange);
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .status-desc {
        color: var(--text-secondary);
        font-size: 1.125rem;
        font-weight: 500;
    }

    /* Info Card */
    .info-card .card-icon {
        background: linear-gradient(135deg, #60a5fa, var(--blue-color));
    }

    .info-card::before {
        background: linear-gradient(90deg, var(--blue-color), #60a5fa);
    }

    .info-grid {
        display: grid;
        gap: 1.5rem;
    }

    .info-row {
        display: flex;
        align-items: center;
        padding: 1rem;
        background: var(--light-gray);
        border-radius: 12px;
        border-left: 4px solid var(--blue-color);
    }

    .info-label {
        font-weight: 600;
        color: var(--text-secondary);
        width: 140px;
        flex-shrink: 0;
    }

    .info-value {
        font-weight: 600;
        color: var(--dark-gray);
        font-size: 1.1rem;
    }

    /* Items Card */
    .items-card .card-icon {
        background: linear-gradient(135deg, #34d399, var(--green-color));
    }

    .items-card::before {
        background: linear-gradient(90deg, var(--green-color), #34d399);
    }

    .items-list {
        display: grid;
        gap: 1rem;
    }

    .order-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem;
        background: var(--light-gray);
        border-radius: 16px;
        border: 1px solid var(--medium-gray);
        transition: all 0.3s ease;
    }

    .order-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        background: white;
    }

    .item-details {
        flex: 1;
    }

    .item-name {
        font-weight: 700;
        color: var(--dark-gray);
        margin-bottom: 0.5rem;
        font-size: 1.25rem;
    }

    .item-qty-price {
        color: var(--text-secondary);
        font-size: 1rem;
        font-weight: 500;
    }

    .item-total {
        font-weight: 800;
        color: var(--red-color);
        font-size: 1.375rem;
        background: linear-gradient(135deg, #fef2f2, #fee2e2);
        padding: 0.75rem 1.25rem;
        border-radius: 12px;
        border: 1px solid #fecaca;
    }

    /* Summary Card */
    .summary-card .card-icon {
        background: linear-gradient(135deg, #f87171, var(--red-color));
    }

    .summary-card::before {
        background: linear-gradient(90deg, var(--red-color), #f87171);
    }

    .summary-list {
        display: grid;
        gap: 1rem;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
        background: var(--light-gray);
        border-radius: 12px;
        font-size: 1.125rem;
    }

    .summary-label {
        color: var(--text-secondary);
        font-weight: 500;
    }

    .summary-value {
        font-weight: 600;
        color: var(--dark-gray);
    }

    .summary-total {
        background: linear-gradient(135deg, #fef2f2, #fee2e2);
        border: 2px solid var(--red-color);
        font-size: 1.5rem;
        font-weight: 800;
    }

    .summary-total .summary-label,
    .summary-total .summary-value {
        color: var(--red-color);
        font-weight: 800;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .container {
            padding: 1rem;
        }

        .page-header {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .page-title {
            font-size: 1.5rem;
        }

        .order-title {
            font-size: 1.5rem;
        }

        .order-meta {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .card-header {
            padding: 1.5rem 1.5rem 1rem 1.5rem;
        }

        .card-content {
            padding: 1.5rem;
        }

        .card-title {
            font-size: 1.25rem;
        }

        .status-text {
            font-size: 1.5rem;
        }

        .info-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .info-label {
            width: auto;
        }

        .order-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .item-total {
            align-self: flex-end;
            font-size: 1.25rem;
        }

        .summary-row {
            font-size: 1rem;
        }

        .summary-total {
            font-size: 1.25rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Detail Pesanan Anda</h1>
        <a href="{{ route('orders.history') }}" class="back-button">
            <i class="bi bi-receipt"></i> Riwayat Pesanan
        </a>
    </div>

    <!-- Order Summary -->
    <div class="order-summary">
        <div class="order-summary-content">
            <h2 class="order-title">Pesanan #{{ $order->order_number }}</h2>
            <div class="order-meta">
                <div class="meta-item">
                    <div class="meta-label">Tanggal Pesanan</div>
                    <div class="meta-value">{{ $order->created_at->format('d M Y, H:i') }}</div>
                </div>
                <div class="meta-item">
                    <div class="meta-label">Status Pembayaran</div>
                    <div class="meta-value">{{ ucfirst($order->payment_status ?? 'Pending') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Section -->
    <div class="status-card card-base">
        <div class="card-content">
            <div class="status-icon">
                @if($order->status == 'completed') 🍜 @else ⏳ @endif
            </div>
            <div class="status-text">{{ ucfirst($order->status) }}</div>
            <div class="status-desc">
                @if($order->status == 'pending')
                    Pesanan Anda menunggu konfirmasi.
                @elseif($order->status == 'confirmed')
                    Pesanan Anda telah dikonfirmasi dan sedang disiapkan.
                @elseif($order->status == 'preparing')
                    Pesanan Anda sedang dimasak di dapur.
                @elseif($order->status == 'ready')
                    Pesanan Anda siap untuk diantar/diambil.
                @elseif($order->status == 'completed')
                    Pesanan telah selesai. Terima kasih!
                @elseif($order->status == 'cancelled')
                    Pesanan telah dibatalkan.
                @endif
            </div>
        </div>
    </div>

    <!-- Customer Information -->
    <div class="info-card card-base">
        <div class="card-header">
            <div class="card-icon"><i class="bi bi-person-circle"></i></div>
            <h2 class="card-title">Informasi Pelanggan</h2>
        </div>
        <div class="card-content">
            <div class="info-grid">
                <div class="info-row">
                    <span class="info-label">Nama:</span>
                    <span class="info-value">{{ $order->customer_name }}</span>
                </div>
                @if($order->table_number)
                <div class="info-row">
                    <span class="info-label">Nomor Meja:</span>
                    <span class="info-value">Meja {{ $order->table_number }}</span>
                </div>
                @endif
                @if($order->notes)
                <div class="info-row">
                    <span class="info-label">Catatan:</span>
                    <span class="info-value">{{ $order->notes }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Order Items -->
    <div class="items-card card-base">
        <div class="card-header">
            <div class="card-icon"><i class="bi bi-basket3-fill"></i></div>
            <h2 class="card-title">Item Pesanan</h2>
        </div>
        <div class="card-content">
            <div class="items-list">
                @forelse($order->items as $item)
                <div class="order-item">
                    <div class="item-details">
                        <div class="item-name">{{ $item->menu_name }}</div>
                        <div class="item-qty-price">
                            {{ $item->quantity }} porsi × Rp{{ number_format($item->price, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="item-total">
                        Rp{{ number_format($item->subtotal, 0, ',', '.') }}
                    </div>
                </div>
                @empty
                <div class="order-item">
                    <div class="item-details">
                        <div class="item-name">Tidak ada item dalam pesanan ini.</div>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Payment Summary -->
    <div class="summary-card card-base">
        <div class="card-header">
            <div class="card-icon"><i class="bi bi-receipt-cutoff"></i></div>
            <h2 class="card-title">Ringkasan Pembayaran</h2>
        </div>
        <div class="card-content">
            <div class="summary-list">
                <div class="summary-row">
                    <span class="summary-label">Subtotal</span>
                    <span class="summary-value">Rp{{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Biaya Layanan</span>
                    <span class="summary-value">Rp{{ number_format($order->service_fee, 0, ',', '.') }}</span>
                </div>
                <div class="summary-row summary-total">
                    <span class="summary-label">Total Pembayaran</span>
                    <span class="summary-value">Rp{{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
