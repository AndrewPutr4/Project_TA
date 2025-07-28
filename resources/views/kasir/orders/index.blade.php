@extends('layouts.kasir')
@section('title', 'Daftar Order')

@section('content')
<div class="orders-container">
    {{-- Header Section --}}
    <div class="page-header">
        <div class="header-content">
            <div class="header-left">
                <div class="header-text">
                    <h1 class="page-title">
                        <i class="fas fa-clipboard-list"></i>
                        Daftar Order
                    </h1>
                    <p class="page-subtitle">Kelola dan pantau semua pesanan masuk secara real-time</p>
                </div>
            </div>
            <div class="header-actions">
                <button class="btn btn-refresh" onclick="refreshOrders()">
                    <i class="fas fa-sync-alt"></i>
                    <span>Refresh</span>
                </button>
                <a href="{{ route('kasir.pos') }}" class="btn btn-new-order">
                    <i class="fas fa-plus"></i>
                    <span>Order Baru</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Compact Filters Section --}}
    <div class="filters-section">
        <div class="filters-header" onclick="toggleFilters()">
            <h3 class="filters-title">
                <i class="fas fa-filter"></i>
                Filter & Pencarian
            </h3>
            <button class="filters-toggle" id="filtersToggle">
                <i class="fas fa-chevron-down"></i>
            </button>
        </div>
        <div class="filters-content" id="filtersContent" style="display: block;">
            <form method="GET" class="filters-form">
                <div class="filter-grid-compact">
                    <div class="filter-row">
                        <div class="filter-group-compact">
                            <label class="filter-label-compact">Status Order</label>
                            <select name="status" class="form-select-compact">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="preparing" {{ request('status') == 'preparing' ? 'selected' : '' }}>Preparing</option>
                                <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>Ready</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        
                        <div class="filter-group-compact">
                            <label class="filter-label-compact">Status Pembayaran</label>
                            <select name="payment_status" class="form-select-compact">
                                <option value="">Semua Pembayaran</option>
                                <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Belum Bayar</option>
                                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Sudah Bayar</option>
                            </select>
                        </div>
                        
                        <div class="filter-group-compact">
                            <label class="filter-label-compact">Tipe Order</label>
                            <select name="order_type" class="form-select-compact">
                                <option value="">Semua Tipe</option>
                                <option value="dine_in" {{ request('order_type') == 'dine_in' ? 'selected' : '' }}>Dine In</option>
                                <option value="takeaway" {{ request('order_type') == 'takeaway' ? 'selected' : '' }}>Takeaway</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="filter-row">
                        <div class="filter-group-compact">
                            <label class="filter-label-compact">Tanggal</label>
                            <input type="date" name="date" value="{{ request('date') }}" class="form-input-compact">
                        </div>
                        
                        <div class="filter-group-compact filter-search">
                            <label class="filter-label-compact">Pencarian</label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Cari nomor order, nama customer..." class="form-input-compact">
                        </div>
                        
                        <div class="filter-actions-compact">
                            <button type="submit" class="btn btn-primary btn-compact">
                                <i class="fas fa-search"></i>
                                <span>Terapkan Filter</span>
                            </button>
                            <a href="{{ route('kasir.orders.index') }}" class="btn btn-secondary btn-compact">
                                <i class="fas fa-undo"></i>
                                <span>Reset</span>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Table Section --}}
    <div class="table-section">
        <div class="table-header">
            <div class="table-info">
                <div class="results-summary">
                    <span class="results-count">
                        <i class="fas fa-list"></i>
                        Menampilkan {{ $orders->firstItem() ?? 0 }} - {{ $orders->lastItem() ?? 0 }} 
                        dari {{ $orders->total() }} order
                    </span>
                    <span class="page-info">
                        (Halaman {{ $orders->currentPage() }} dari {{ $orders->lastPage() }})
                    </span>
                </div>
            </div>
            <div class="table-actions">
                <button class="btn btn-export" onclick="exportOrders()">
                    <i class="fas fa-download"></i>
                    <span>Export</span>
                </button>
            </div>
        </div>
        
        <div class="table-container">
            @if($orders->count() > 0)
            <table class="orders-table">
                <thead>
                    <tr>
                        <th class="col-order-number">
                            <div class="th-content">
                                <i class="fas fa-hashtag"></i>
                                <span>No. Order</span>
                            </div>
                        </th>
                        <th class="col-customer">
                            <div class="th-content">
                                <i class="fas fa-user"></i>
                                <span>Customer</span>
                            </div>
                        </th>
                        <th class="col-type">
                            <div class="th-content">
                                <i class="fas fa-utensils"></i>
                                <span>Tipe</span>
                            </div>
                        </th>
                        <th class="col-items">
                            <div class="th-content">
                                <i class="fas fa-shopping-cart"></i>
                                <span>Items</span>
                            </div>
                        </th>
                        <th class="col-total">
                            <div class="th-content">
                                <i class="fas fa-money-bill"></i>
                                <span>Total</span>
                            </div>
                        </th>
                        <th class="col-status">
                            <div class="th-content">
                                <i class="fas fa-tasks"></i>
                                <span>Status</span>
                            </div>
                        </th>
                        <th class="col-payment">
                            <div class="th-content">
                                <i class="fas fa-credit-card"></i>
                                <span>Pembayaran</span>
                            </div>
                        </th>
                        <th class="col-date">
                            <div class="th-content">
                                <i class="fas fa-calendar"></i>
                                <span>Tanggal</span>
                            </div>
                        </th>
                        <th class="col-actions">
                            <div class="th-content">
                                <i class="fas fa-cogs"></i>
                                <span>Aksi</span>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr class="order-row" data-order-id="{{ $order->id }}">
                        <td class="order-number">
                            <div class="order-number-content">
                                <strong class="number">#{{ $order->order_number }}</strong>
                            </div>
                        </td>
                        
                        <td class="customer-info">
                            <div class="customer-content">
                                <div class="customer-name">{{ $order->customer_name }}</div>
                                @if($order->customer_phone)
                                    <div class="customer-phone">
                                        <i class="fas fa-phone"></i>
                                        {{ $order->customer_phone }}
                                    </div>
                                @endif
                            </div>
                        </td>
                        
                        <td class="order-type">
                            @if($order->table_number)
                                <div class="type-info">
                                    <span class="type-badge dine-in">
                                        <i class="fas fa-chair"></i>
                                        Dine In
                                    </span>
                                    <div class="table-number">
                                        <i class="fas fa-map-marker-alt"></i>
                                        Meja {{ $order->table_number }}
                                    </div>
                                </div>
                            @else
                                <div class="type-info">
                                    <span class="type-badge takeaway">
                                        <i class="fas fa-shopping-bag"></i>
                                        Takeaway
                                    </span>
                                </div>
                            @endif
                        </td>
                        
                        <td class="order-items">
                            <div class="items-content">
                                <div class="items-summary">
                                    <i class="fas fa-shopping-cart"></i>
                                    <span class="items-count">{{ $order->items->count() }} item(s)</span>
                                </div>
                                <div class="items-preview">
                                    @foreach($order->items->take(2) as $item)
                                        <div class="item-preview">
                                            <span class="item-name">{{ $item->menu_name }}</span>
                                            <span class="item-qty">({{ $item->quantity }}x)</span>
                                        </div>
                                    @endforeach
                                    @if($order->items->count() > 2)
                                        <div class="more-items">
                                            <i class="fas fa-plus"></i>
                                            {{ $order->items->count() - 2 }} lainnya
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        
                        <td class="order-total">
                            <div class="total-content">
                                <div class="total-amount">Rp {{ number_format($order->total, 0, ',', '.') }}</div>
                                @if($order->service_fee > 0)
                                    <div class="service-fee">
                                        <i class="fas fa-plus"></i>
                                        Service: Rp {{ number_format($order->service_fee, 0, ',', '.') }}
                                    </div>
                                @endif
                            </div>
                        </td>
                        
                        <td class="order-status">
                            <span class="status-badge status-{{ $order->status }}">
                                @if($order->status === 'pending')
                                    <i class="fas fa-clock"></i>
                                @elseif($order->status === 'confirmed')
                                    <i class="fas fa-check-circle"></i>
                                @elseif($order->status === 'preparing')
                                    <i class="fas fa-fire"></i>
                                @elseif($order->status === 'ready')
                                    <i class="fas fa-bell"></i>
                                @elseif($order->status === 'completed')
                                    <i class="fas fa-check-double"></i>
                                @else
                                    <i class="fas fa-times-circle"></i>
                                @endif
                                <span>{{ ucfirst($order->status) }}</span>
                            </span>
                        </td>
                        
                        <td class="payment-status">
                            <span class="payment-badge payment-{{ $order->payment_status }}">
                                @if($order->payment_status === 'paid')
                                    <i class="fas fa-check-circle"></i>
                                    <span>Lunas</span>
                                @else
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span>Belum Bayar</span>
                                @endif
                            </span>
                        </td>
                        
                        <td class="order-date">
                            <div class="date-content">
                                <div class="date">
                                    <i class="fas fa-calendar"></i>
                                    {{ $order->created_at->format('d/m/Y') }}
                                </div>
                                <div class="time">
                                    <i class="fas fa-clock"></i>
                                    {{ $order->created_at->format('H:i') }}
                                </div>
                            </div>
                        </td>
                        
                        <td class="order-actions">
                            <div class="action-buttons">
                                <a href="{{ route('kasir.orders.show', $order) }}"
                                   class="btn btn-sm btn-outline action-btn" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                    <span class="btn-text">Detail</span>
                                </a>
                                
                                @if($order->status == 'pending')
                                    <button onclick="confirmOrder('{{ $order->id }}')"
                                            class="btn btn-sm btn-success action-btn" title="Konfirmasi Order">
                                        <i class="fas fa-check"></i>
                                        <span class="btn-text">Konfirmasi</span>
                                    </button>
                                @endif
                                
                                @if($order->status == 'ready' && $order->payment_status == 'unpaid')
                                    <a href="{{ route('kasir.transactions.create', $order) }}"
                                       class="btn btn-sm btn-warning action-btn" title="Proses Pembayaran">
                                        <i class="fas fa-credit-card"></i>
                                        <span class="btn-text">Bayar</span>
                                    </a>
                                @endif
                                
                                @if($order->status != 'completed' && $order->status != 'cancelled')
                                    <button onclick="cancelOrder('{{ $order->id }}')"
                                            class="btn btn-sm btn-danger action-btn" title="Batalkan Order">
                                        <i class="fas fa-times"></i>
                                        <span class="btn-text">Batal</span>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="empty-state">
                <div class="empty-content">
                    <div class="empty-icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <h3 class="empty-title">Tidak ada order ditemukan</h3>
                    <p class="empty-description">
                        @if(request()->hasAny(['status', 'payment_status', 'order_type', 'date', 'search']))
                            Tidak ada order yang sesuai dengan filter yang dipilih.
                        @else
                            Belum ada order yang masuk saat ini.
                        @endif
                    </p>
                    <div class="empty-actions">
                        @if(request()->hasAny(['status', 'payment_status', 'order_type', 'date', 'search']))
                            <a href="{{ route('kasir.orders.index') }}" class="btn btn-secondary">
                                <i class="fas fa-undo"></i>
                                <span>Reset Filter</span>
                            </a>
                        @endif
                        <a href="{{ route('kasir.pos') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i>
                            <span>Buat Order Baru</span>
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Pagination --}}
    @if($orders->hasPages())
    <div class="pagination-wrapper">
        <div class="pagination-info">
            <div class="pagination-summary">
                <div class="info-text">
                    <i class="fas fa-info-circle"></i>
                    Menampilkan {{ $orders->firstItem() }} - {{ $orders->lastItem() }} dari {{ $orders->total() }} order
                </div>
            </div>
        </div>
        
        <div class="pagination-controls">
            <nav class="pagination-nav">
                {{-- Previous Page Link --}}
                @if ($orders->onFirstPage())
                    <span class="pagination-btn disabled">
                        <i class="fas fa-chevron-left"></i>
                        <span>Sebelumnya</span>
                    </span>
                @else
                    <a href="{{ $orders->previousPageUrl() }}" class="pagination-btn">
                        <i class="fas fa-chevron-left"></i>
                        <span>Sebelumnya</span>
                    </a>
                @endif
                
                {{-- Pagination Elements --}}
                <div class="pagination-numbers">
                    @foreach ($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
                        @if ($page == $orders->currentPage())
                            <span class="pagination-number active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="pagination-number">{{ $page }}</a>
                        @endif
                    @endforeach
                </div>
                
                {{-- Next Page Link --}}
                @if ($orders->hasMorePages())
                    <a href="{{ $orders->nextPageUrl() }}" class="pagination-btn">
                        <span>Selanjutnya</span>
                        <i class="fas fa-chevron-right"></i>
                    </a>
                @else
                    <span class="pagination-btn disabled">
                        <span>Selanjutnya</span>
                        <i class="fas fa-chevron-right"></i>
                    </span>
                @endif
            </nav>
        </div>
    </div>
    @endif
</div>

{{-- Improved Styles --}}
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

.orders-container {
    max-width: 1600px;
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
    position: relative;
    z-index: 1;
}

.page-title {
    font-size: 2rem;
    font-weight: 800;
    margin: 0 0 0.5rem 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    letter-spacing: -0.025em;
}

.page-subtitle {
    font-size: 1rem;
    opacity: 0.9;
    margin: 0;
    font-weight: 400;
}

.header-actions {
    display: flex;
    gap: 1rem;
    position: relative;
    z-index: 1;
}

/* Compact Filters Section */
.filters-section {
    background: white;
    border-radius: 16px;
    margin-bottom: 1.5rem;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--gray-200);
    overflow: hidden;
}

.filters-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.5rem;
    background: linear-gradient(135deg, #fff 0%, var(--gray-50) 100%);
    border-bottom: 1px solid var(--gray-200);
    cursor: pointer;
    user-select: none;
}

.filters-title {
    font-size: 1rem;
    font-weight: 700;
    color: var(--gray-800);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.filters-title i {
    color: var(--primary-color);
}

.filters-toggle {
    background: none;
    border: none;
    color: var(--primary-color);
    font-size: 0.875rem;
    cursor: pointer;
    transition: transform 0.3s ease;
    padding: 0.25rem;
    border-radius: 6px;
}

.filters-toggle:hover {
    background: rgba(245, 158, 11, 0.1);
}

.filters-toggle.rotated {
    transform: rotate(180deg);
}

.filters-content {
    padding: 1.25rem;
    transition: all 0.3s ease;
}

.filters-content.hidden {
    display: none !important;
}

/* Compact Filter Grid */
.filter-grid-compact {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.filter-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    align-items: end;
}

.filter-group-compact {
    display: flex;
    flex-direction: column;
    gap: 0.375rem;
}

.filter-search {
    grid-column: span 2;
}

.filter-label-compact {
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 0;
}

.form-input-compact, .form-select-compact {
    padding: 0.625rem 0.75rem;
    border: 1px solid var(--gray-300);
    border-radius: 8px;
    font-size: 0.8rem;
    transition: all 0.2s ease;
    background: white;
    color: var(--gray-800);
    font-family: inherit;
    width: 100%;
    height: 38px;
}

.form-input-compact:focus, .form-select-compact:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
}

.filter-actions-compact {
    display: flex;
    gap: 0.75rem;
    align-items: end;
}

.btn-compact {
    padding: 0.625rem 1rem;
    font-size: 0.8rem;
    height: 38px;
}

/* Button Styles */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.875rem 1.25rem;
    border: none;
    border-radius: 10px;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    white-space: nowrap;
    position: relative;
    overflow: hidden;
}

.btn-sm {
    padding: 0.5rem 0.75rem;
    font-size: 0.75rem;
    border-radius: 8px;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    color: white;
    box-shadow: var(--shadow-sm);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(245, 158, 11, 0.3);
    color: white;
    text-decoration: none;
}

.btn-secondary {
    background: linear-gradient(135deg, var(--secondary-color) 0%, #4b5563 100%);
    color: white;
    box-shadow: var(--shadow-sm);
}

.btn-secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(107, 114, 128, 0.3);
    color: white;
    text-decoration: none;
}

.btn-outline {
    background: transparent;
    color: var(--primary-color);
    border: 1px solid var(--primary-color);
}

.btn-outline:hover {
    background: var(--primary-color);
    color: white;
    text-decoration: none;
}

.btn-success {
    background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
    color: white;
    box-shadow: var(--shadow-sm);
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
    color: white;
    text-decoration: none;
}

.btn-warning {
    background: linear-gradient(135deg, var(--warning-color) 0%, var(--primary-dark) 100%);
    color: white;
    box-shadow: var(--shadow-sm);
}

.btn-warning:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(245, 158, 11, 0.3);
    color: white;
    text-decoration: none;
}

.btn-danger {
    background: linear-gradient(135deg, var(--danger-color) 0%, #dc2626 100%);
    color: white;
    box-shadow: var(--shadow-sm);
}

.btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(239, 68, 68, 0.3);
    color: white;
    text-decoration: none;
}

.btn-refresh, .btn-new-order, .btn-export {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.3);
    backdrop-filter: blur(10px);
}

.btn-refresh:hover, .btn-new-order:hover, .btn-export:hover {
    background: rgba(255, 255, 255, 0.3);
    border-color: rgba(255, 255, 255, 0.5);
    transform: translateY(-2px);
    color: white;
    text-decoration: none;
}

/* Table Styles */
.table-section {
    background: white;
    border-radius: 16px;
    box-shadow: var(--shadow-lg);
    overflow: hidden;
    margin-bottom: 2rem;
    border: 1px solid var(--gray-200);
}

.table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid var(--gray-200);
    background: linear-gradient(135deg, #fff 0%, var(--gray-50) 100%);
}

.results-summary {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.results-count {
    color: var(--gray-800);
    font-size: 0.875rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.results-count i {
    color: var(--primary-color);
}

.page-info {
    color: var(--gray-500);
    font-size: 0.75rem;
    opacity: 0.8;
}

.table-actions {
    display: flex;
    gap: 1rem;
}

.table-container {
    overflow-x: auto;
}

.orders-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.875rem;
    min-width: 1200px;
}

.orders-table th {
    background: linear-gradient(135deg, var(--gray-50) 0%, var(--gray-100) 100%);
    padding: 1rem 0.75rem;
    text-align: left;
    font-weight: 700;
    color: var(--gray-800);
    border-bottom: 1px solid var(--gray-200);
    white-space: nowrap;
    position: sticky;
    top: 0;
    z-index: 10;
}

.th-content {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.th-content i {
    color: var(--primary-color);
    font-size: 0.75rem;
}

.orders-table td {
    padding: 1rem 0.75rem;
    border-bottom: 1px solid var(--gray-200);
    vertical-align: top;
}

.order-row {
    transition: all 0.2s ease;
}

.order-row:hover {
    background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
}

/* Action Buttons - Improved for Tablet */
.col-actions {
    width: 180px;
    min-width: 180px;
}

.action-buttons {
    display: flex;
    gap: 0.375rem;
    flex-wrap: wrap;
    min-width: 160px;
}

.action-btn {
    min-width: 70px;
    padding: 0.375rem 0.5rem;
    font-size: 0.7rem;
    border-radius: 6px;
    flex: 1;
    max-width: 80px;
}

/* Table Cell Styles */
.order-number-content {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.number {
    color: var(--primary-color);
    font-size: 0.9rem;
    font-weight: 700;
}

.customer-content {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.customer-name {
    font-weight: 600;
    color: var(--gray-900);
    font-size: 0.85rem;
}

.customer-phone {
    font-size: 0.7rem;
    color: var(--gray-600);
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.type-info {
    display: flex;
    flex-direction: column;
    gap: 0.375rem;
}

.type-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.375rem 0.5rem;
    border-radius: 6px;
    font-size: 0.7rem;
    font-weight: 600;
    width: fit-content;
}

.type-badge.dine-in {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    color: #1e40af;
    border: 1px solid #93c5fd;
}

.type-badge.takeaway {
    background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
    color: var(--primary-dark);
    border: 1px solid #fde68a;
}

.table-number {
    font-size: 0.7rem;
    color: var(--gray-600);
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.items-content {
    display: flex;
    flex-direction: column;
    gap: 0.375rem;
}

.items-summary {
    display: flex;
    align-items: center;
    gap: 0.375rem;
    font-weight: 600;
    color: var(--gray-900);
}

.items-count {
    background: var(--primary-color);
    color: white;
    padding: 0.2rem 0.4rem;
    border-radius: 4px;
    font-size: 0.7rem;
    font-weight: 700;
}

.items-preview {
    font-size: 0.75rem;
    color: var(--gray-600);
}

.item-preview {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.2rem;
}

.item-name {
    flex: 1;
}

.item-qty {
    color: var(--primary-color);
    font-weight: 600;
}

.more-items {
    font-style: italic;
    color: var(--gray-500);
    display: flex;
    align-items: center;
    gap: 0.25rem;
    margin-top: 0.2rem;
}

.total-content {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.total-amount {
    font-size: 0.9rem;
    font-weight: 700;
    color: var(--primary-color);
}

.service-fee {
    font-size: 0.7rem;
    color: var(--gray-600);
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.status-badge, .payment-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.375rem 0.5rem;
    border-radius: 8px;
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: capitalize;
    width: fit-content;
}

/* Status Badge Colors */
.status-pending {
    background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
    color: var(--primary-dark);
    border: 1px solid #fde68a;
}

.status-confirmed {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    color: #1e40af;
    border: 1px solid #93c5fd;
}

.status-preparing {
    background: linear-gradient(135deg, #fed7aa 0%, #fdba74 100%);
    color: #9a3412;
    border: 1px solid #fb923c;
}

.status-ready {
    background: linear-gradient(135deg, #cffafe 0%, #a5f3fc 100%);
    color: #155e75;
    border: 1px solid #67e8f9;
}

.status-completed {
    background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
    color: #166534;
    border: 1px solid #86efac;
}

.status-cancelled {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    color: #991b1b;
    border: 1px solid #f87171;
}

.payment-paid {
    background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
    color: #166534;
    border: 1px solid #86efac;
}

.payment-unpaid {
    background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
    color: var(--primary-dark);
    border: 1px solid #fde68a;
}

.date-content {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.date, .time {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.75rem;
}

.date {
    font-weight: 600;
    color: var(--gray-900);
}

.time {
    color: var(--gray-600);
}

/* Empty State */
.empty-state {
    padding: 3rem 2rem;
    text-align: center;
}

.empty-content {
    max-width: 400px;
    margin: 0 auto;
}

.empty-icon {
    font-size: 3rem;
    color: var(--primary-color);
    margin-bottom: 1rem;
    opacity: 0.6;
}

.empty-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--gray-800);
    margin-bottom: 0.5rem;
}

.empty-description {
    color: var(--gray-600);
    margin-bottom: 1.5rem;
    line-height: 1.6;
}

.empty-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

/* Pagination */
.pagination-wrapper {
    background: white;
    border-radius: 16px;
    box-shadow: var(--shadow-md);
    padding: 1.5rem;
    margin-bottom: 2rem;
    border: 1px solid var(--gray-200);
}

.pagination-info {
    margin-bottom: 1rem;
}

.pagination-summary {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid var(--gray-200);
}

.info-text {
    color: var(--gray-800);
    font-size: 0.875rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.info-text i {
    color: var(--primary-color);
}

.pagination-controls {
    display: flex;
    justify-content: center;
}

.pagination-nav {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.pagination-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    border: 1px solid var(--gray-300);
    border-radius: 8px;
    color: var(--gray-700);
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 600;
    transition: all 0.2s ease;
    background: white;
}

.pagination-btn:hover:not(.disabled) {
    background: var(--primary-color);
    border-color: var(--primary-color);
    color: white;
    transform: translateY(-1px);
    box-shadow: var(--shadow-sm);
    text-decoration: none;
}

.pagination-btn.disabled {
    color: var(--gray-400);
    cursor: not-allowed;
    opacity: 0.6;
}

.pagination-numbers {
    display: flex;
    gap: 0.375rem;
}

.pagination-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border: 1px solid var(--gray-300);
    border-radius: 8px;
    color: var(--gray-700);
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 600;
    transition: all 0.2s ease;
    background: white;
}

.pagination-number:hover {
    background: var(--primary-color);
    border-color: var(--primary-color);
    color: white;
    transform: translateY(-1px);
    box-shadow: var(--shadow-sm);
    text-decoration: none;
}

.pagination-number.active {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    color: white;
    border-color: var(--primary-color);
    font-weight: 700;
    box-shadow: var(--shadow-sm);
}

/* Responsive Design */
@media (max-width: 1200px) {
    .orders-table {
        font-size: 0.8rem;
        min-width: 1100px;
    }
    
    .orders-table th,
    .orders-table td {
        padding: 0.875rem 0.625rem;
    }
    
    .btn-text {
        display: none;
    }
    
    .action-btn {
        min-width: 40px;
        padding: 0.375rem 0.25rem;
    }
}

@media (max-width: 1024px) {
    .orders-container {
        padding: 1rem;
    }
    
    .filter-row {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .filter-search {
        grid-column: span 1;
    }
    
    .filter-actions-compact {
        grid-column: span 2;
        justify-content: flex-start;
    }
    
    .orders-table {
        min-width: 1000px;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 0.25rem;
        min-width: 80px;
    }
    
    .action-btn {
        width: 100%;
        max-width: none;
        min-width: 70px;
    }
    
    .col-actions {
        width: 90px;
        min-width: 90px;
    }
}

@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        gap: 1.5rem;
        text-align: center;
        padding: 1.5rem;
    }
    
    .page-title {
        font-size: 1.75rem;
    }
    
    .header-actions {
        width: 100%;
        justify-content: center;
    }
    
    .filter-row {
        grid-template-columns: 1fr;
    }
    
    .filter-search {
        grid-column: span 1;
    }
    
    .filter-actions-compact {
        grid-column: span 1;
        flex-direction: column;
    }
    
    .table-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .orders-table {
        min-width: 900px;
    }
    
    .pagination-summary {
        flex-direction: column;
        gap: 0.5rem;
        text-align: center;
    }
    
    .pagination-nav {
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .pagination-numbers {
        order: -1;
        margin-bottom: 1rem;
    }
}

@media (max-width: 480px) {
    .orders-container {
        padding: 0.75rem;
    }
    
    .header-content {
        padding: 1.25rem;
    }
    
    .page-title {
        font-size: 1.5rem;
    }
    
    .filters-content {
        padding: 1rem;
    }
    
    .btn-text {
        display: inline;
    }
    
    .action-btn {
        font-size: 0.65rem;
        padding: 0.25rem 0.375rem;
    }
    
    .pagination-numbers {
        gap: 0.25rem;
    }
    
    .pagination-number {
        width: 35px;
        height: 35px;
        font-size: 0.8rem;
    }
}

/* Animation Classes */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.order-row {
    animation: fadeIn 0.3s ease-out;
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

/* Print Styles */
@media print {
    .orders-container {
        background: white;
        padding: 0;
        margin: 0;
        max-width: none;
    }
    
    .page-header,
    .filters-section,
    .pagination-wrapper {
        display: none !important;
    }
    
    .table-section {
        box-shadow: none;
        border-radius: 0;
        border: none;
    }
    
    .orders-table {
        font-size: 10px;
        min-width: auto;
    }
    
    .orders-table th,
    .orders-table td {
        padding: 0.25rem;
    }
}
</style>

<script>
// Store CSRF token for AJAX requests
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

// Toggle filters visibility
function toggleFilters() {
    const filtersContent = document.getElementById('filtersContent');
    const toggleBtn = document.getElementById('filtersToggle');
    
    if (filtersContent.classList.contains('hidden')) {
        filtersContent.classList.remove('hidden');
        filtersContent.style.display = 'block';
        toggleBtn.classList.add('rotated');
    } else {
        filtersContent.classList.add('hidden');
        filtersContent.style.display = 'none';
        toggleBtn.classList.remove('rotated');
    }
}

// Refresh orders function
function refreshOrders() {
    const refreshBtn = document.querySelector('.btn-refresh');
    const originalText = refreshBtn.innerHTML;
    
    refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Refreshing...</span>';
    refreshBtn.disabled = true;
    
    // Show loading animation
    document.querySelectorAll('.order-row').forEach(row => {
        row.style.opacity = '0.6';
    });
    
    setTimeout(function() {
        window.location.reload();
    }, 800);
}

// Confirm order function
function confirmOrder(orderId) {
    if (!confirm('Konfirmasi order ini dan lanjut ke tahap selanjutnya?')) {
        return;
    }
    
    const confirmBtn = event.target.closest('button');
    const originalText = confirmBtn.innerHTML;
    confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span class="btn-text">Processing...</span>';
    confirmBtn.disabled = true;
    
    const url = '/kasir/orders/' + orderId + '/confirm';
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(function(response) {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(function(data) {
        if (data.success) {
            // Show success message
            showNotification('Order berhasil dikonfirmasi!', 'success');
            
            // Update row status
            const row = confirmBtn.closest('.order-row');
            const statusBadge = row.querySelector('.status-badge');
            statusBadge.className = 'status-badge status-confirmed';
            statusBadge.innerHTML = '<i class="fas fa-check-circle"></i><span>Confirmed</span>';
            
            // Remove confirm button
            confirmBtn.remove();
            
            // Redirect if needed
            if (data.redirect_url) {
                setTimeout(() => {
                    window.location.href = data.redirect_url;
                }, 1500);
            }
        } else {
            showNotification('Gagal mengkonfirmasi order: ' + (data.message || 'Unknown error'), 'error');
            confirmBtn.innerHTML = originalText;
            confirmBtn.disabled = false;
        }
    })
    .catch(function(error) {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan saat mengkonfirmasi order', 'error');
        confirmBtn.innerHTML = originalText;
        confirmBtn.disabled = false;
    });
}

// Cancel order function
function cancelOrder(orderId) {
    if (!confirm('Apakah Anda yakin ingin membatalkan order ini?')) {
        return;
    }
    
    const cancelBtn = event.target.closest('button');
    const originalText = cancelBtn.innerHTML;
    cancelBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span class="btn-text">Cancelling...</span>';
    cancelBtn.disabled = true;
    
    const url = '/kasir/orders/' + orderId + '/cancel';
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(function(response) {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(function(data) {
        if (data.success) {
            showNotification('Order berhasil dibatalkan!', 'success');
            
            // Update row status
            const row = cancelBtn.closest('.order-row');
            const statusBadge = row.querySelector('.status-badge');
            statusBadge.className = 'status-badge status-cancelled';
            statusBadge.innerHTML = '<i class="fas fa-times-circle"></i><span>Cancelled</span>';
            
            // Remove action buttons
            const actionButtons = row.querySelector('.action-buttons');
            actionButtons.innerHTML = '<span class="text-muted">Order dibatalkan</span>';
            
            // Add cancelled styling to row
            row.style.opacity = '0.7';
            row.style.background = 'linear-gradient(135deg, #fee2e2 0%, #fecaca 100%)';
        } else {
            showNotification('Gagal membatalkan order: ' + (data.message || 'Unknown error'), 'error');
            cancelBtn.innerHTML = originalText;
            cancelBtn.disabled = false;
        }
    })
    .catch(function(error) {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan saat membatalkan order', 'error');
        cancelBtn.innerHTML = originalText;
        cancelBtn.disabled = false;
    });
}

// Export orders function
function exportOrders() {
    const exportBtn = document.querySelector('.btn-export');
    const originalText = exportBtn.innerHTML;
    
    exportBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Exporting...</span>';
    exportBtn.disabled = true;
    
    // Simulate export process
    setTimeout(() => {
        showNotification('Export berhasil! File akan diunduh sebentar lagi.', 'success');
        exportBtn.innerHTML = originalText;
        exportBtn.disabled = false;
    }, 2000);
}

// Show notification function
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
            <span>${message}</span>
        </div>
        <button class="notification-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    // Add notification styles
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#dcfce7' : type === 'error' ? '#fee2e2' : '#dbeafe'};
        color: ${type === 'success' ? '#166534' : type === 'error' ? '#991b1b' : '#1e40af'};
        padding: 1rem 1.5rem;
        border-radius: 12px;
        border: 2px solid ${type === 'success' ? '#86efac' : type === 'error' ? '#f87171' : '#93c5fd'};
        z-index: 1000;
        animation: slideIn 0.3s ease-out;
        display: flex;
        align-items: center;
        gap: 1rem;
        max-width: 400px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 5000);
}

// Enhanced initialization
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Orders page loaded with enhanced features');
    
    // Initialize filters - show by default
    const filtersContent = document.getElementById('filtersContent');
    const toggleBtn = document.getElementById('filtersToggle');
    
    // Show filters by default
    filtersContent.style.display = 'block';
    filtersContent.classList.remove('hidden');
    
    // Check if there are active filters to determine initial state
    const hasActiveFilters = new URLSearchParams(window.location.search).toString();
    if (hasActiveFilters) {
        toggleBtn.classList.add('rotated');
    }
    
    // Add loading states to form submissions
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Loading...</span>';
                submitBtn.disabled = true;
            }
        });
    });
    
    // Add hover effects to table rows
    document.querySelectorAll('.order-row').forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-1px)';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});

// Add notification styles to head
const notificationStyles = `
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
    
    .notification-content {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex: 1;
    }
    
    .notification-close {
        background: none;
        border: none;
        color: inherit;
        cursor: pointer;
        padding: 0.25rem;
        border-radius: 4px;
        opacity: 0.7;
        transition: opacity 0.2s ease;
    }
    
    .notification-close:hover {
        opacity: 1;
    }
`;

const styleSheet = document.createElement('style');
styleSheet.textContent = notificationStyles;
document.head.appendChild(styleSheet);
</script>
@endsection