@extends('layouts.kasir')
@section('title', 'Detail Order #' . $order->order_number)
@section('content')

<div class="order-detail-container">
    {{-- Header Section --}}
    <div class="page-header">
        <div class="header-content">
            <div class="header-left">
                <a href="{{ route('kasir.orders.index') }}" class="back-btn">
                    <i class="fas fa-arrow-left"></i>
                    Kembali
                </a>
                <div class="header-text">
                    <h1 class="page-title">Detail Order #{{ $order->order_number }}</h1>
                    <p class="page-subtitle">Informasi lengkap pesanan customer</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="order-detail-grid">
        {{-- Order Info Card --}}
        <div class="order-info-card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fas fa-info-circle"></i>
                    <h2>Informasi Order</h2>
                </div>
                <div class="order-badges">
                    <span class="badge status-{{ $order->status }}">
                        <i class="fas fa-circle"></i>
                        {{ ucfirst($order->status) }}
                    </span>
                    <span class="badge payment-{{ $order->payment_status }}">
                        <i class="fas fa-{{ $order->payment_status == 'paid' ? 'check-circle' : 'clock' }}"></i>
                        {{ $order->payment_status == 'paid' ? 'Lunas' : 'Belum Bayar' }}
                    </span>
                </div>
            </div>
            <div class="card-content">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-hashtag"></i>
                        </div>
                        <div class="info-details">
                            <label>Nomor Order</label>
                            <span>{{ $order->order_number }}</span>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="info-details">
                            <label>Tanggal & Waktu</label>
                            <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="info-details">
                            <label>Nama Customer</label>
                            <span>{{ $order->customer_name }}</span>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="info-details">
                            <label>No. Telepon</label>
                            <span>{{ $order->customer_phone ?: '-' }}</span>
                        </div>
                    </div>
                    @if($order->table_number)
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-chair"></i>
                        </div>
                        <div class="info-details">
                            <label>No. Meja</label>
                            <span>Meja {{ $order->table_number }}</span>
                        </div>
                    </div>
                    @endif
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-{{ $order->table_number ? 'utensils' : 'shopping-bag' }}"></i>
                        </div>
                        <div class="info-details">
                            <label>Tipe Order</label>
                            <span>{{ $order->table_number ? 'Dine In' : 'Takeaway' }}</span>
                        </div>
                    </div>
                    @if($order->customer_address)
                    <div class="info-item full-width">
                        <div class="info-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="info-details">
                            <label>Alamat</label>
                            <span>{{ $order->customer_address }}</span>
                        </div>
                    </div>
                    @endif
                    @if($order->notes)
                    <div class="info-item full-width">
                        <div class="info-icon">
                            <i class="fas fa-sticky-note"></i>
                        </div>
                        <div class="info-details">
                            <label>Catatan</label>
                            <span>{{ $order->notes }}</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Order Summary Card --}}
        <div class="order-summary-card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fas fa-calculator"></i>
                    <h2>Ringkasan Pembayaran</h2>
                </div>
            </div>
            <div class="card-content">
                <div class="summary-list">
                    <div class="summary-item">
                        <div class="summary-label">
                            <i class="fas fa-receipt"></i>
                            <span>Subtotal</span>
                        </div>
                        <span class="summary-value">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @if($order->service_fee > 0)
                    <div class="summary-item">
                        <div class="summary-label">
                            <i class="fas fa-concierge-bell"></i>
                            <span>Biaya Layanan</span>
                        </div>
                        <span class="summary-value">Rp {{ number_format($order->service_fee, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <div class="summary-item total">
                        <div class="summary-label">
                            <i class="fas fa-money-check-alt"></i>
                            <span>Total Pembayaran</span>
                        </div>
                        <span class="summary-value">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                </div>
                
                {{-- Payment Status Indicator --}}
                <div class="payment-status-indicator">
                    @if($order->payment_status == 'paid')
                        <div class="status-paid">
                            <i class="fas fa-check-circle"></i>
                            <span>Pembayaran Lunas</span>
                        </div>
                    @else
                        <div class="status-unpaid">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>Menunggu Pembayaran</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Order Items Card --}}
        <div class="order-items-card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fas fa-list"></i>
                    <h2>Item Pesanan</h2>
                </div>
                <span class="item-count">
                    <i class="fas fa-shopping-cart"></i>
                    {{ $order->items ? $order->items->count() : 0 }} item
                </span>
            </div>
            <div class="card-content">
                <div class="items-list">
                    @forelse($order->items as $item)
                    <div class="order-item">
                        <div class="item-image">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <div class="item-info">
                            <h3 class="item-name">{{ $item->menu_name }}</h3>
                            @if($item->menu_description)
                            <p class="item-description">{{ $item->menu_description }}</p>
                            @endif
                            <div class="item-meta">
                                <span class="item-price">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                <span class="item-separator">×</span>
                                <span class="item-qty">{{ $item->quantity }}</span>
                            </div>
                        </div>
                        <div class="item-total">
                            <span class="total-label">Subtotal</span>
                            <span class="total-value">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-inbox"></i>
                        </div>
                        <p class="empty-text">Tidak ada item dalam pesanan ini.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Status Update Card --}}
        @if($order->status != 'completed' && $order->status != 'cancelled')
        <div class="status-update-card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fas fa-tasks"></i>
                    <h2>Update Status Order</h2>
                </div>
            </div>
            <div class="card-content">
                <div class="status-flow">
                    <div class="flow-item {{ $order->status == 'pending' ? 'active' : ($order->status != 'pending' ? 'completed' : '') }}">
                        <div class="flow-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <span>Pending</span>
                    </div>
                    <div class="flow-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                    <div class="flow-item {{ $order->status == 'confirmed' ? 'active' : (in_array($order->status, ['preparing', 'ready', 'completed']) ? 'completed' : '') }}">
                        <div class="flow-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <span>Confirmed</span>
                    </div>
                    <div class="flow-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                    <div class="flow-item {{ $order->status == 'preparing' ? 'active' : (in_array($order->status, ['ready', 'completed']) ? 'completed' : '') }}">
                        <div class="flow-icon">
                            <i class="fas fa-fire"></i>
                        </div>
                        <span>Preparing</span>
                    </div>
                    <div class="flow-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                    <div class="flow-item {{ $order->status == 'ready' ? 'active' : ($order->status == 'completed' ? 'completed' : '') }}">
                        <div class="flow-icon">
                            <i class="fas fa-bell"></i>
                        </div>
                        <span>Ready</span>
                    </div>
                    <div class="flow-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                    <div class="flow-item {{ $order->status == 'completed' ? 'completed' : '' }}">
                        <div class="flow-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <span>Completed</span>
                    </div>
                </div>
                
                <div class="status-buttons">
                    @if($order->status == 'pending')
                    <button onclick="updateStatus('confirmed')" class="btn btn-primary">
                        <i class="fas fa-check"></i>
                        <span>Konfirmasi Order</span>
                    </button>
                    @endif
                    @if($order->status == 'confirmed')
                    <button onclick="updateStatus('preparing')" class="btn btn-warning">
                        <i class="fas fa-fire"></i>
                        <span>Mulai Persiapan</span>
                    </button>
                    @endif
                    @if($order->status == 'preparing')
                    <button onclick="updateStatus('ready')" class="btn btn-info">
                        <i class="fas fa-bell"></i>
                        <span>Siap Disajikan</span>
                    </button>
                    @endif
                    @if($order->status == 'ready')
                    <button onclick="updateStatus('completed')" class="btn btn-success">
                        <i class="fas fa-check-circle"></i>
                        <span>Selesai</span>
                    </button>
                    @endif
                    @if($order->status != 'cancelled')
                    <button onclick="updateStatus('cancelled')" class="btn btn-danger">
                        <i class="fas fa-times-circle"></i>
                        <span>Batalkan Order</span>
                    </button>
                    @endif
                </div>
            </div>
        </div>
        @endif

        {{-- Payment Action Card --}}
        @if($order->status == 'ready' && $order->payment_status == 'unpaid')
        <div class="payment-card">
            <div class="card-header">
                <div class="card-title">
                    <i class="fas fa-credit-card"></i>
                    <h2>Proses Pembayaran</h2>
                </div>
            </div>
            <div class="card-content">
                <div class="payment-info">
                    <div class="payment-amount">
                        <span class="amount-label">Total yang harus dibayar:</span>
                        <span class="amount-value">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                    <p class="payment-note">
                        <i class="fas fa-info-circle"></i>
                        Order sudah siap disajikan. Silakan proses pembayaran untuk menyelesaikan transaksi.
                    </p>
                </div>
                <a href="{{ route('kasir.transactions.create', $order) }}" class="btn btn-success btn-large">
                    <i class="fas fa-credit-card"></i>
                    <span>Proses Pembayaran</span>
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Enhanced Styles --}}
<style>
:root {
    --primary-color: #f59e0b;
    --primary-dark: #d97706;
    --primary-light: #fbbf24;
    --secondary-color: #6b7280;
    --success-color: #10b981;
    --danger-color: #ef4444;
    --warning-color: #f59e0b;
    --info-color: #3b82f6;
    --gray-50: #f9fafb;
    --gray-100: #f3f4f6;
    --gray-200: #e5e7eb;
    --gray-300: #d1d5db;
    --gray-400: #9ca3af;
    --gray-500: #6b7280;
    --gray-600: #4b5563;
    --gray-700: #374151;
    --gray-800: #1f2937;
    --gray-900: #111827;
    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

* {
    box-sizing: border-box;
}

.order-detail-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 1.5rem;
    background: linear-gradient(135deg, var(--gray-50) 0%, var(--gray-100) 100%);
    min-height: 100vh;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
}

/* Header Styles */
.page-header {
    margin-bottom: 2rem;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    color: white;
    padding: 2rem;
    border-radius: 20px;
    box-shadow: var(--shadow-xl);
    position: relative;
    overflow: hidden;
}

.header-content::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 200px;
    height: 200px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    transform: translate(50%, -50%);
}

.header-left {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    position: relative;
    z-index: 1;
}

.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.25rem;
    background: rgba(255, 255, 255, 0.2);
    color: white;
    text-decoration: none;
    border-radius: 12px;
    font-size: 0.875rem;
    font-weight: 600;
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.3);
    backdrop-filter: blur(10px);
}

.back-btn:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    color: white;
    text-decoration: none;
}

.page-title {
    font-size: 2rem;
    font-weight: 800;
    margin: 0 0 0.5rem 0;
    letter-spacing: -0.025em;
}

.page-subtitle {
    font-size: 1rem;
    opacity: 0.9;
    margin: 0;
    font-weight: 400;
}

.btn-print {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.3);
    padding: 0.75rem 1.25rem;
    border-radius: 12px;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.btn-print:hover {
    background: rgba(255, 255, 255, 0.3);
    border-color: rgba(255, 255, 255, 0.5);
    transform: translateY(-2px);
}

/* Grid Layout */
.order-detail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    align-items: start;
}

/* Card Styles */
.order-info-card,
.order-items-card,
.order-summary-card,
.status-update-card,
.payment-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: var(--shadow-lg);
    transition: all 0.3s ease;
    border: 1px solid var(--gray-200);
}

.order-info-card:hover,
.order-items-card:hover,
.order-summary-card:hover,
.status-update-card:hover,
.payment-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-xl);
}

.order-items-card,
.status-update-card,
.payment-card {
    grid-column: 1 / -1;
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem 2rem;
    border-bottom: 2px solid var(--gray-100);
    background: linear-gradient(135deg, #fff 0%, var(--gray-50) 100%);
}

.card-title {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.card-title h2 {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--gray-800);
    margin: 0;
}

.card-title i {
    color: var(--primary-color);
    font-size: 1.125rem;
}

.order-badges {
    display: flex;
    gap: 0.75rem;
}

.badge {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.badge i {
    font-size: 0.625rem;
}

/* Status Badge Colors */
.status-pending {
    background: #fef3c7;
    color: #92400e;
    border: 1px solid #fde68a;
}

.status-confirmed {
    background: #dbeafe;
    color: #1e40af;
    border: 1px solid #bfdbfe;
}

.status-preparing {
    background: #fed7aa;
    color: #9a3412;
    border: 1px solid #fdba74;
}

.status-ready {
    background: #cffafe;
    color: #155e75;
    border: 1px solid #a5f3fc;
}

.status-completed {
    background: #dcfce7;
    color: #166534;
    border: 1px solid #bbf7d0;
}

.status-cancelled {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #fecaca;
}

.payment-paid {
    background: #dcfce7;
    color: #166534;
    border: 1px solid #bbf7d0;
}

.payment-unpaid {
    background: #fef3c7;
    color: #92400e;
    border: 1px solid #fde68a;
}

.item-count {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: var(--gray-600);
    background: var(--gray-100);
    padding: 0.5rem 1rem;
    border-radius: 12px;
    font-weight: 500;
}

.card-content {
    padding: 2rem;
}

/* Info Grid */
.info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

.info-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1rem;
    background: var(--gray-50);
    border-radius: 12px;
    border: 1px solid var(--gray-200);
    transition: all 0.2s ease;
}

.info-item:hover {
    background: var(--gray-100);
    transform: translateY(-1px);
    box-shadow: var(--shadow-sm);
}

.info-item.full-width {
    grid-column: 1 / -1;
}

.info-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.875rem;
    flex-shrink: 0;
}

.info-details {
    flex: 1;
}

.info-details label {
    display: block;
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: 0.025em;
    margin-bottom: 0.25rem;
}

.info-details span {
    font-size: 0.875rem;
    color: var(--gray-900);
    font-weight: 600;
}

/* Summary List */
.summary-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background: var(--gray-50);
    border-radius: 12px;
    border: 1px solid var(--gray-200);
}

.summary-label {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: var(--gray-700);
    font-weight: 500;
}

.summary-label i {
    color: var(--primary-color);
}

.summary-value {
    font-weight: 700;
    color: var(--gray-900);
}

.summary-item.total {
    background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
    border-color: #fde68a;
    font-size: 1.125rem;
}

.summary-item.total .summary-label {
    color: var(--primary-dark);
    font-weight: 700;
}

.summary-item.total .summary-value {
    color: var(--primary-dark);
    font-size: 1.25rem;
}

/* Payment Status Indicator */
.payment-status-indicator {
    padding: 1rem;
    border-radius: 12px;
    text-align: center;
}

.status-paid {
    background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
    color: #166534;
    border: 2px solid #86efac;
}

.status-unpaid {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    color: #92400e;
    border: 2px solid #fbbf24;
}

.status-paid i,
.status-unpaid i {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
    display: block;
}

.status-paid span,
.status-unpaid span {
    font-weight: 700;
    font-size: 1rem;
}

/* Items List */
.items-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.order-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    background: var(--gray-50);
    border-radius: 16px;
    border: 1px solid var(--gray-200);
    transition: all 0.3s ease;
}

.order-item:hover {
    background: var(--gray-100);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.item-image {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.item-info {
    flex: 1;
}

.item-name {
    font-size: 1rem;
    font-weight: 700;
    color: var(--gray-900);
    margin: 0 0 0.5rem 0;
}

.item-description {
    font-size: 0.875rem;
    color: var(--gray-600);
    margin: 0 0 0.75rem 0;
    line-height: 1.4;
}

.item-meta {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 0.875rem;
}

.item-price {
    color: var(--gray-700);
    font-weight: 600;
}

.item-separator {
    color: var(--gray-400);
    font-weight: 700;
}

.item-qty {
    background: var(--primary-color);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 8px;
    font-weight: 700;
    font-size: 0.75rem;
}

.item-total {
    text-align: right;
    flex-shrink: 0;
}

.total-label {
    display: block;
    font-size: 0.75rem;
    color: var(--gray-500);
    font-weight: 500;
    margin-bottom: 0.25rem;
}

.total-value {
    font-size: 1rem;
    font-weight: 700;
    color: var(--gray-900);
}

.empty-state {
    text-align: center;
    padding: 3rem 2rem;
    color: var(--gray-500);
}

.empty-icon {
    margin-bottom: 1rem;
}

.empty-icon i {
    font-size: 3rem;
    color: var(--gray-400);
}

.empty-text {
    font-size: 1rem;
    margin: 0;
}

/* Status Flow */
.status-flow {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: var(--gray-50);
    border-radius: 16px;
    border: 1px solid var(--gray-200);
}

.flow-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    text-align: center;
    min-width: 80px;
}

.flow-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    transition: all 0.3s ease;
    border: 2px solid var(--gray-300);
    background: white;
    color: var(--gray-400);
}

.flow-item.active .flow-icon {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    border-color: var(--primary-color);
    color: white;
    transform: scale(1.1);
    box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
}

.flow-item.completed .flow-icon {
    background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
    border-color: var(--success-color);
    color: white;
}

.flow-item span {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--gray-600);
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.flow-item.active span {
    color: var(--primary-dark);
}

.flow-item.completed span {
    color: var(--success-color);
}

.flow-arrow {
    color: var(--gray-400);
    font-size: 0.875rem;
}

/* Status Buttons */
.status-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    justify-content: center;
}

/* Payment Card */
.payment-info {
    margin-bottom: 2rem;
}

.payment-amount {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
    border-radius: 12px;
    border: 2px solid #fde68a;
    margin-bottom: 1rem;
}

.amount-label {
    font-size: 1rem;
    color: var(--primary-dark);
    font-weight: 600;
}

.amount-value {
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--primary-dark);
}

.payment-note {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 0.875rem;
    color: var(--gray-600);
    margin: 0;
    padding: 1rem;
    background: var(--gray-50);
    border-radius: 8px;
    border: 1px solid var(--gray-200);
}

.payment-note i {
    color: var(--info-color);
    flex-shrink: 0;
}

/* Button Styles */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    padding: 1rem 1.5rem;
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

.btn-large {
    padding: 1.25rem 2rem;
    font-size: 1rem;
    width: 100%;
}

.btn-primary {
    background: linear-gradient(135deg, var(--info-color) 0%, #1d4ed8 100%);
    color: white;
    box-shadow: var(--shadow-md);
}

.btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 35px rgba(59, 130, 246, 0.4);
    color: white;
    text-decoration: none;
}

.btn-warning {
    background: linear-gradient(135deg, var(--warning-color) 0%, var(--primary-dark) 100%);
    color: white;
    box-shadow: var(--shadow-md);
}

.btn-warning:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 35px rgba(245, 158, 11, 0.4);
    color: white;
    text-decoration: none;
}

.btn-info {
    background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
    color: white;
    box-shadow: var(--shadow-md);
}

.btn-info:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 35px rgba(6, 182, 212, 0.4);
    color: white;
    text-decoration: none;
}

.btn-success {
    background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
    color: white;
    box-shadow: var(--shadow-md);
}

.btn-success:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 35px rgba(16, 185, 129, 0.4);
    color: white;
    text-decoration: none;
}

.btn-danger {
    background: linear-gradient(135deg, var(--danger-color) 0%, #dc2626 100%);
    color: white;
    box-shadow: var(--shadow-md);
}

.btn-danger:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 35px rgba(239, 68, 68, 0.4);
    color: white;
    text-decoration: none;
}

/* Print Styles */
@media print {
    .order-detail-container {
        background: white;
        padding: 0;
        margin: 0;
        max-width: none;
    }
    
    .page-header,
    .status-update-card,
    .payment-card {
        display: none !important;
    }
    
    .order-detail-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .card-content {
        padding: 1rem;
        font-size: 12px;
    }
    
    .page-title {
        font-size: 18px;
    }
}

/* Responsive Design */
@media (max-width: 1024px) {
    .order-detail-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .status-flow {
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .flow-item {
        min-width: 60px;
    }
    
    .flow-icon {
        width: 35px;
        height: 35px;
        font-size: 0.875rem;
    }
    
    .status-buttons {
        flex-direction: column;
    }
}

@media (max-width: 768px) {
    .order-detail-container {
        padding: 1rem;
    }
    
    .header-content {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
        padding: 1.5rem;
    }
    
    .header-left {
        flex-direction: column;
        gap: 1rem;
        align-items: center;
    }
    
    .card-content {
        padding: 1.5rem;
    }
    
    .order-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .item-total {
        align-self: flex-end;
        text-align: right;
    }
    
    .payment-amount {
        flex-direction: column;
        gap: 0.75rem;
        text-align: center;
    }
    
    .status-flow {
        flex-direction: column;
        gap: 1rem;
    }
    
    .flow-arrow {
        transform: rotate(90deg);
    }
}

@media (max-width: 480px) {
    .order-detail-container {
        padding: 0.75rem;
    }
    
    .header-content {
        padding: 1.25rem;
    }
    
    .card-content {
        padding: 1.25rem;
    }
    
    .page-title {
        font-size: 1.5rem;
    }
    
    .order-badges {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .badge {
        justify-content: center;
    }
}

/* Animation Classes */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.order-detail-grid > * {
    animation: fadeIn 0.5s ease-out;
}

/* Loading State */
.btn.loading {
    pointer-events: none;
}

.btn.loading i {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}
</style>

<script>
function updateStatus(status) {
    if (!confirm('Apakah Anda yakin ingin mengubah status menjadi ' + status + '?')) {
        return;
    }
    
    // Add loading state to all buttons
    const buttons = document.querySelectorAll('.status-buttons .btn');
    buttons.forEach(btn => {
        btn.disabled = true;
        btn.classList.add('loading');
    });
    
    fetch('/kasir/orders/{{ $order->id }}/status', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            const successMsg = document.createElement('div');
            successMsg.className = 'alert alert-success';
            successMsg.innerHTML = '<i class="fas fa-check-circle"></i> Status berhasil diupdate!';
            successMsg.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: #dcfce7;
                color: #166534;
                padding: 1rem 1.5rem;
                border-radius: 12px;
                border: 2px solid #86efac;
                z-index: 1000;
                animation: slideIn 0.3s ease-out;
            `;
            document.body.appendChild(successMsg);
            
            // Remove success message and reload
            setTimeout(() => {
                successMsg.remove();
                location.reload();
            }, 2000);
        } else {
            alert('Gagal mengupdate status: ' + data.message);
            // Re-enable buttons
            buttons.forEach(btn => {
                btn.disabled = false;
                btn.classList.remove('loading');
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengupdate status');
        // Re-enable buttons
        buttons.forEach(btn => {
            btn.disabled = false;
            btn.classList.remove('loading');
        });
    });
}

function printOrder() {
    // Add print styles
    const printStyles = `
        @media print {
            .page-header, .status-update-card, .payment-card { display: none !important; }
            .order-detail-grid { grid-template-columns: 1fr !important; }
            body { font-size: 12px !important; }
        }
    `;
    
    const styleSheet = document.createElement('style');
    styleSheet.textContent = printStyles;
    document.head.appendChild(styleSheet);
    
    window.print();
    
    // Remove print styles after printing
    setTimeout(() => {
        document.head.removeChild(styleSheet);
    }, 1000);
}

// Add keyboard shortcuts
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Order detail page loaded');
    
    // Add keyboard shortcut for print
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
            e.preventDefault();
            printOrder();
        }
    });
    
    // Add hover effects to cards
    const cards = document.querySelectorAll('.order-info-card, .order-summary-card, .order-items-card, .status-update-card, .payment-card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});

// Add slide in animation for alerts
const slideInKeyframes = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
`;

const animationStyleSheet = document.createElement('style');
animationStyleSheet.textContent = slideInKeyframes;
document.head.appendChild(animationStyleSheet);
</script>

@endsection
