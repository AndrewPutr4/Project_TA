@extends('layouts.kasir')

@section('title', 'Daftar Order')

@section('content')
<div class="orders-container">
    <div class="page-header">
        <div class="header-content">
            <div class="header-text">
                <h1 class="page-title">
                    <i class="fas fa-clipboard-list"></i>
                    Daftar Order
                </h1>
                <p class="page-subtitle">Kelola dan pantau semua pesanan masuk</p>
            </div>
            <div class="header-actions">
                <button class="btn btn-refresh" onclick="refreshOrders()">
                    <i class="fas fa-sync-alt"></i>
                    Refresh
                </button>
            </div>
        </div>
    </div>

    <div class="filters-section">
        <div class="filters-content">
            <form method="GET" class="filters-form">
                <div class="filter-row">
                    <div class="filter-group">
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="preparing" {{ request('status') == 'preparing' ? 'selected' : '' }}>Preparing</option>
                            <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>Ready</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <select name="payment_status" class="form-select">
                            <option value="">Status Pembayaran</option>
                            <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Belum Bayar</option>
                            <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Sudah Bayar</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <select name="order_type" class="form-select">
                            <option value="">Tipe Order</option>
                            <option value="dine_in" {{ request('order_type') == 'dine_in' ? 'selected' : '' }}>Dine In</option>
                            <option value="takeaway" {{ request('order_type') == 'takeaway' ? 'selected' : '' }}>Takeaway</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <input type="date" name="date" value="{{ request('date') }}" class="form-input">
                    </div>
                    
                    <div class="filter-group">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari order..." class="form-input">
                    </div>
                    
                    <div class="filter-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                            Filter
                        </button>
                        <a href="{{ route('kasir.orders.index') }}" class="btn btn-secondary">
                            <i class="fas fa-undo"></i>
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="table-section">
        <div class="table-header">
            <div class="table-info">
                <span class="results-count">
                    Menampilkan {{ $orders->firstItem() ?? 0 }} - {{ $orders->lastItem() ?? 0 }} 
                    dari {{ $orders->total() }} order
                </span>
                <span class="page-info">
                    (Halaman {{ $orders->currentPage() }} dari {{ $orders->lastPage() }})
                </span>
            </div>
        </div>

        <div class="table-container">
            @if($orders->count() > 0)
            <table class="orders-table">
                <thead>
                    <tr>
                        <th width="120">No. Order</th>
                        <th width="180">Customer</th>
                        <th width="120">Tipe</th>
                        <th width="200">Items</th>
                        <th width="120">Total</th>
                        <th width="120">Status</th>
                        <th width="120">Pembayaran</th>
                        <th width="100">Tanggal</th>
                        <th width="200">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr class="order-row" data-order-id="{{ $order->id }}">
                        <td class="order-number">
                            <strong>#{{ $order->order_number }}</strong>
                        </td>
                        <td class="customer-info">
                            <div class="customer-name">{{ $order->customer_name }}</div>
                        </td>
                        <td class="order-type">
                            @if($order->table_number)
                                <span class="type-badge dine-in">
                                    <i class="fas fa-chair"></i>
                                    Dine In
                                </span>
                                <div class="table-number">Meja {{ $order->table_number }}</div>
                            @else
                                <span class="type-badge takeaway">
                                    <i class="fas fa-shopping-bag"></i>
                                    Takeaway
                                </span>
                            @endif
                        </td>
                        <td class="order-items">
                            <div class="items-summary">
                                {{ $order->orderItems->count() }} item(s)
                            </div>
                            <div class="items-preview">
                                @foreach($order->orderItems->take(2) as $item)
                                    <div class="item-preview">{{ $item->menu_name }} ({{ $item->quantity }}x)</div>
                                @endforeach
                                @if($order->orderItems->count() > 2)
                                    <div class="more-items">+{{ $order->orderItems->count() - 2 }} lainnya</div>
                                @endif
                            </div>
                        </td>
                        <td class="order-total">
                            <strong>Rp {{ number_format($order->total, 0, ',', '.') }}</strong>
                        </td>
                        <td class="order-status">
                            <span class="status-badge status-{{ $order->status }}">
                                @if($order->status === 'pending')<i class="fas fa-clock"></i>
                                @elseif($order->status === 'confirmed')<i class="fas fa-check-circle"></i>
                                @elseif($order->status === 'preparing')<i class="fas fa-utensils"></i>
                                @elseif($order->status === 'ready')<i class="fas fa-bell"></i>
                                @elseif($order->status === 'completed')<i class="fas fa-check-double"></i>
                                @else<i class="fas fa-times-circle"></i>
                                @endif
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="payment-status">
                            <span class="payment-badge payment-{{ $order->payment_status }}">
                                @if($order->payment_status === 'paid')
                                    <i class="fas fa-check-circle"></i> Lunas
                                @else
                                    <i class="fas fa-exclamation-circle"></i> Belum Bayar
                                @endif
                            </span>
                        </td>
                        <td class="order-date">
                            <div class="date">{{ $order->created_at->format('d/m/Y') }}</div>
                            <div class="time">{{ $order->created_at->format('H:i') }}</div>
                        </td>
                        <td class="order-actions">
                            <div class="action-buttons">
                                <a href="{{ route('kasir.orders.show', $order) }}" class="btn btn-sm btn-outline">
                                    <i class="fas fa-eye"></i>
                                    <span class="btn-text">Detail</span>
                                </a>
                                @if($order->status == 'pending')
                                    <button onclick="confirmOrder('{{ $order->id }}')" class="btn btn-sm btn-success">
                                        <i class="fas fa-check"></i>
                                        <span class="btn-text">Konfirmasi</span>
                                    </button>
                                @endif
                                
                                @if($order->status != 'completed' && $order->status != 'cancelled')
                                    <button onclick="cancelOrder('{{ $order->id }}')" class="btn btn-sm btn-danger">
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
                <div class="empty-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <h3>Tidak ada order</h3>
                <p>Belum ada order yang masuk saat ini</p>
                <a href="{{ route('kasir.pos') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Buat Order Baru
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Custom Pagination -->
    @if($orders->hasPages())
    <div class="pagination-wrapper">
        <div class="pagination-info">
            <div class="info-text">
                Menampilkan {{ $orders->firstItem() }} - {{ $orders->lastItem() }} dari {{ $orders->total() }} order
            </div>
            <div class="per-page-info">
                {{ $orders->perPage() }} data per halaman
            </div>
        </div>
        
        <div class="pagination-controls">
            <nav class="pagination-nav">
                {{-- Previous Page Link --}}
                @if ($orders->onFirstPage())
                    <span class="pagination-btn disabled">
                        <i class="fas fa-chevron-left"></i>
                        Sebelumnya
                    </span>
                @else
                    <a href="{{ $orders->previousPageUrl() }}" class="pagination-btn">
                        <i class="fas fa-chevron-left"></i>
                        Sebelumnya
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
                        Selanjutnya
                        <i class="fas fa-chevron-right"></i>
                    </a>
                @else
                    <span class="pagination-btn disabled">
                        Selanjutnya
                        <i class="fas fa-chevron-right"></i>
                    </span>
                @endif
            </nav>
        </div>
    </div>
    @endif
</div>

<style>
    :root {
        --primary-color: #f59e0b;
        --primary-dark: #d97706;
        --primary-light: #fbbf24;
        --warning-bg: #fffbeb;
        --warning-border: #fed7aa;
        --warning-text: #92400e;
        --success-color: #10b981;
        --danger-color: #ef4444;
    }

    /* Global Styles */
    .orders-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 1rem;
        background: #f8fafc;
        min-height: 100vh;
    }

    /* Header */
    .page-header {
        margin-bottom: 1.5rem;
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        padding: 1.5rem 2rem;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
    }

    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0 0 0.25rem 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .page-subtitle {
        font-size: 0.95rem;
        opacity: 0.9;
        margin: 0;
    }

    /* Filters */
    .filters-section {
        background: white;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 8px rgba(245, 158, 11, 0.1);
        padding: 1.25rem;
        border: 2px solid var(--warning-border);
    }

    .filter-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1rem;
        align-items: center;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
    }

    .form-input, .form-select {
        padding: 0.75rem;
        border: 2px solid var(--warning-border);
        border-radius: 8px;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        background: white;
    }

    .form-input:focus, .form-select:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
    }

    .filter-actions {
        display: flex;
        gap: 0.75rem;
        grid-column: span 2;
    }

    /* Button Styles */
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.25rem;
        border: none;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        white-space: nowrap;
        border: 2px solid transparent;
    }

    .btn-sm {
        padding: 0.5rem 0.75rem;
        font-size: 0.8rem;
    }

    .btn-sm .btn-text {
        font-size: 0.75rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        border-color: var(--primary-color);
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, var(--primary-dark) 0%, #b45309 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
    }

    .btn-secondary {
        background: var(--warning-bg);
        color: var(--warning-text);
        border-color: var(--warning-border);
    }

    .btn-secondary:hover {
        background: #fef3c7;
        border-color: var(--primary-color);
    }

    .btn-outline {
        background: transparent;
        color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .btn-outline:hover {
        background: var(--primary-color);
        color: white;
    }

    .btn-success {
        background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
        color: white;
        border-color: var(--success-color);
    }

    .btn-success:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
    }

    .btn-danger {
        background: linear-gradient(135deg, var(--danger-color) 0%, #dc2626 100%);
        color: white;
        border-color: var(--danger-color);
    }

    .btn-danger:hover {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
    }

    .btn-refresh {
        background: rgba(255,255,255,0.2);
        color: white;
        border-color: rgba(255,255,255,0.3);
    }

    .btn-refresh:hover {
        background: rgba(255,255,255,0.3);
        border-color: rgba(255,255,255,0.5);
    }

    /* Table Styles */
    .table-section {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(245, 158, 11, 0.1);
        overflow: hidden;
        margin-bottom: 1.5rem;
        border: 2px solid var(--warning-border);
    }

    .table-header {
        padding: 1.25rem;
        border-bottom: 1px solid var(--warning-border);
        background: var(--warning-bg);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .results-count {
        color: var(--warning-text);
        font-size: 0.875rem;
        font-weight: 600;
    }

    .page-info {
        color: #92400e;
        font-size: 0.8rem;
        margin-left: 0.5rem;
        opacity: 0.8;
    }

    .table-container {
        overflow-x: auto;
    }

    .orders-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.875rem;
    }

    .orders-table th {
        background: var(--warning-bg);
        padding: 1rem 0.75rem;
        text-align: left;
        font-weight: 600;
        color: var(--warning-text);
        border-bottom: 2px solid var(--warning-border);
        white-space: nowrap;
    }

    .orders-table td {
        padding: 1rem 0.75rem;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: top;
    }

    .order-row:hover {
        background: var(--warning-bg);
    }

    .order-number strong {
        color: var(--primary-color);
        font-size: 0.95rem;
    }

    .customer-info {
        min-width: 150px;
    }

    .customer-name {
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.25rem;
    }

    .type-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .type-badge.dine-in {
        background: #dbeafe;
        color: #1e40af;
    }

    .type-badge.takeaway {
        background: var(--warning-bg);
        color: var(--warning-text);
        border: 1px solid var(--warning-border);
    }

    .table-number {
        font-size: 0.75rem;
        color: #6b7280;
    }

    .items-summary {
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.25rem;
    }

    .items-preview {
        font-size: 0.8rem;
        color: #6b7280;
    }

    .item-preview {
        margin-bottom: 0.125rem;
    }

    .more-items {
        font-style: italic;
        color: #9ca3af;
    }

    .order-total strong {
        color: var(--primary-color);
        font-size: 0.95rem;
    }

    .status-badge, .payment-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.375rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: capitalize;
    }

    .status-pending { background: var(--warning-bg); color: var(--warning-text); border: 1px solid var(--warning-border); }
    .status-confirmed { background: #dbeafe; color: #1e40af; }
    .status-preparing { background: #fed7aa; color: #9a3412; }
    .status-ready { background: #cffafe; color: #155e75; }
    .status-completed { background: #dcfce7; color: #166534; }
    .status-cancelled { background: #fee2e2; color: #991b1b; }

    .payment-paid { background: #dcfce7; color: #166534; }
    .payment-unpaid { background: var(--warning-bg); color: var(--warning-text); border: 1px solid var(--warning-border); }

    .order-date {
        min-width: 80px;
    }

    .date {
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.125rem;
    }

    .time {
        font-size: 0.8rem;
        color: #6b7280;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
    }

    .empty-icon {
        font-size: 3rem;
        color: var(--primary-color);
        margin-bottom: 1rem;
        opacity: 0.6;
    }

    .empty-state h3 {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--warning-text);
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #6b7280;
        margin-bottom: 1.5rem;
    }

    /* Enhanced Pagination */
    .pagination-wrapper {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(245, 158, 11, 0.1);
        padding: 1.5rem;
        margin-bottom: 2rem;
        border: 2px solid var(--warning-border);
    }

    .pagination-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--warning-border);
    }

    .info-text {
        color: var(--warning-text);
        font-size: 0.875rem;
        font-weight: 600;
    }

    .per-page-info {
        color: #92400e;
        font-size: 0.8rem;
        opacity: 0.8;
    }

    .pagination-controls {
        display: flex;
        justify-content: center;
    }

    .pagination-nav {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .pagination-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1rem;
        border: 2px solid var(--warning-border);
        border-radius: 8px;
        color: var(--warning-text);
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s ease;
        background: white;
    }

    .pagination-btn:hover:not(.disabled) {
        background: var(--warning-bg);
        border-color: var(--primary-color);
        color: var(--primary-color);
        transform: translateY(-1px);
    }

    .pagination-btn.disabled {
        color: #9ca3af;
        cursor: not-allowed;
        opacity: 0.6;
    }

    .pagination-numbers {
        display: flex;
        gap: 0.25rem;
        margin: 0 1rem;
    }

    .pagination-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border: 2px solid var(--warning-border);
        border-radius: 8px;
        color: var(--warning-text);
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s ease;
        background: white;
    }

    .pagination-number:hover {
        background: var(--warning-bg);
        border-color: var(--primary-color);
        color: var(--primary-color);
    }

    .pagination-number.active {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        border-color: var(--primary-color);
        font-weight: 600;
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .orders-table {
            font-size: 0.8rem;
        }
        
        .orders-table th,
        .orders-table td {
            padding: 0.75rem 0.5rem;
        }
        
        .btn-text {
            display: none;
        }
    }

    @media (max-width: 768px) {
        .orders-container {
            padding: 0.5rem;
        }
        
        .header-content {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
            padding: 1.25rem;
        }
        
        .page-title {
            font-size: 1.5rem;
        }
        
        .filter-row {
            grid-template-columns: 1fr;
        }
        
        .filter-actions {
            grid-column: 1;
            justify-content: stretch;
        }
        
        .filter-actions .btn {
            flex: 1;
        }
        
        .table-container {
            overflow-x: scroll;
        }
        
        .orders-table {
            min-width: 800px;
        }
        
        .pagination-info {
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
            margin: 0 0 1rem 0;
        }
    }

    @media (max-width: 480px) {
        .action-buttons {
            flex-direction: column;
        }
        
        .action-buttons .btn {
            width: 100%;
            justify-content: center;
        }
        
        .btn-text {
            display: inline;
        }
        
        .pagination-numbers {
            gap: 0.125rem;
        }
        
        .pagination-number {
            width: 35px;
            height: 35px;
            font-size: 0.8rem;
        }
    }
</style>

<script>
// Store CSRF token for AJAX requests
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

function refreshOrders() {
    // Add loading state
    const refreshBtn = document.querySelector('.btn-refresh');
    const originalText = refreshBtn.innerHTML;
    refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Refreshing...';
    refreshBtn.disabled = true;
    
    setTimeout(function() {
        window.location.reload();
    }, 500);
}

function confirmOrder(orderId) {
    if (!confirm('Konfirmasi order ini dan lanjut ke pembayaran?')) {
        return;
    }
    
    // Show loading state
    const confirmBtn = event.target.closest('button');
    const originalText = confirmBtn.innerHTML;
    confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span class="btn-text">Processing...</span>';
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
            // Redirect to payment page
            window.location.href = data.redirect_url;
        } else {
            alert('Gagal mengkonfirmasi order: ' + (data.message || 'Unknown error'));
            confirmBtn.innerHTML = originalText;
            confirmBtn.disabled = false;
        }
    })
    .catch(function(error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengkonfirmasi order');
        confirmBtn.innerHTML = originalText;
        confirmBtn.disabled = false;
    });
}

function cancelOrder(orderId) {
    if (!confirm('Apakah Anda yakin ingin membatalkan order ini?')) {
        return;
    }
    
    // Show loading state
    const cancelBtn = event.target.closest('button');
    const originalText = cancelBtn.innerHTML;
    cancelBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span class="btn-text">Cancelling...</span>';
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
            location.reload();
        } else {
            alert('Gagal membatalkan order: ' + (data.message || 'Unknown error'));
            cancelBtn.innerHTML = originalText;
            cancelBtn.disabled = false;
        }
    })
    .catch(function(error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat membatalkan order');
        cancelBtn.innerHTML = originalText;
        cancelBtn.disabled = false;
    });
}

// Enhanced initialization
document.addEventListener('DOMContentLoaded', function() {
    // Add loading states to buttons
    document.querySelectorAll('.btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            if (this.type === 'submit') {
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
                this.disabled = true;
                
                setTimeout(function() {
                    this.innerHTML = originalText;
                    this.disabled = false;
                }.bind(this), 2000);
            }
        });
    });
    
    console.log('✅ Orders page loaded with yellow theme');
});

// Auto refresh every 30 seconds
setInterval(function() {
    refreshOrders();
}, 30000);

// Add keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + R for refresh
    if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
        e.preventDefault();
        refreshOrders();
    }
    
    // F5 for refresh
    if (e.key === 'F5') {
        e.preventDefault();
        refreshOrders();
    }
});
</script>
@endsection
