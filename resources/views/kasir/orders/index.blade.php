@extends('layouts.kasir')
@section('title', 'Daftar Order')
@section('content')
<div class="orders-container">
    <!-- Enhanced Header -->
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

    <!-- Enhanced Filters -->
    <div class="filters-section">
        <div class="filters-header">
            <h3 class="filters-title">
                <i class="fas fa-filter"></i>
                Filter Order
            </h3>
        </div>
        <div class="filters-content">
            <form method="GET" class="filters-form">
                <div class="filter-group">
                    <label><i class="fas fa-info-circle"></i> Status Order</label>
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
                    <label><i class="fas fa-credit-card"></i> Status Pembayaran</label>
                    <select name="payment_status" class="form-select">
                        <option value="">Semua</option>
                        <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Belum Bayar</option>
                        <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Sudah Bayar</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label><i class="fas fa-utensils"></i> Tipe Order</label>
                    <select name="order_type" class="form-select">
                        <option value="">Semua Tipe</option>
                        <option value="dine_in" {{ request('order_type') == 'dine_in' ? 'selected' : '' }}>Dine In</option>
                        <option value="takeaway" {{ request('order_type') == 'takeaway' ? 'selected' : '' }}>Takeaway</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label><i class="fas fa-calendar"></i> Tanggal</label>
                    <input type="date" name="date" value="{{ request('date') }}" class="form-input">
                </div>
                
                <div class="filter-group">
                    <label><i class="fas fa-search"></i> Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="No. Order, Nama, Telepon..." class="form-input">
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
            </form>
        </div>
    </div>

    <!-- Enhanced Orders List -->
    <div class="orders-section">
        <div class="section-header">
            <h3 class="section-title">
                <i class="fas fa-list"></i>
                Daftar Order
            </h3>
            <div class="section-actions">
                <span class="results-count">{{ $orders->total() }} order ditemukan</span>
            </div>
        </div>
        
        <div class="orders-list">
            @forelse($orders as $order)
            <div class="order-card" data-order-id="{{ $order->id }}">
                <div class="order-header">
                    <div class="order-info">
                        <h3 class="order-number">
                            <i class="fas fa-receipt"></i>
                            #{{ $order->order_number }}
                        </h3>
                        <div class="order-meta">
                            <span class="order-date">
                                <i class="fas fa-calendar-alt"></i>
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </span>
                            <span class="customer-name">
                                <i class="fas fa-user"></i>
                                {{ $order->customer_name }}
                            </span>
                            <span class="order-type">
                                @if($order->table_number)
                                    <i class="fas fa-chair"></i>
                                    Dine In - Meja {{ $order->table_number }}
                                @else
                                    <i class="fas fa-shopping-bag"></i>
                                    Takeaway
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="order-badges">
                        <span class="badge status-{{ $order->status }}">
                            @if($order->status === 'pending')
                                <i class="fas fa-clock"></i>
                            @elseif($order->status === 'confirmed')
                                <i class="fas fa-check-circle"></i>
                            @elseif($order->status === 'preparing')
                                <i class="fas fa-utensils"></i>
                            @elseif($order->status === 'ready')
                                <i class="fas fa-bell"></i>
                            @elseif($order->status === 'completed')
                                <i class="fas fa-check-double"></i>
                            @else
                                <i class="fas fa-times-circle"></i>
                            @endif
                            {{ ucfirst($order->status) }}
                        </span>
                        <span class="badge payment-{{ $order->payment_status }}">
                            @if($order->payment_status === 'paid')
                                <i class="fas fa-check-circle"></i>
                                Lunas
                            @else
                                <i class="fas fa-exclamation-circle"></i>
                                Belum Bayar
                            @endif
                        </span>
                    </div>
                </div>

                <div class="order-items">
                    @forelse($order->orderItems->take(3) as $item)
                        <div class="order-item">
                            <span class="item-name">
                                <i class="fas fa-utensils"></i>
                                {{ $item->menu_name }}
                            </span>
                            <span class="item-qty">x{{ $item->quantity }}</span>
                            <span class="item-price">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </div>
                    @empty
                        <div class="no-items">
                            <i class="fas fa-inbox"></i>
                            <em>Tidak ada item</em>
                        </div>
                    @endforelse

                    @if($order->orderItems->count() > 3)
                        <div class="more-items">
                            <i class="fas fa-plus"></i>
                            +{{ $order->orderItems->count() - 3 }} item lainnya
                        </div>
                    @endif
                </div>

                <div class="order-footer">
                    <div class="order-total">
                        <i class="fas fa-calculator"></i>
                        <strong>Total: Rp {{ number_format($order->total, 0, ',', '.') }}</strong>
                    </div>
                    <div class="order-actions">
                        <a href="{{ route('kasir.orders.show', $order) }}" class="btn btn-outline">
                            <i class="fas fa-eye"></i>
                            Detail
                        </a>

                        @if($order->status == 'pending')
                            <button onclick="confirmOrder('{{ $order->id }}')" class="btn btn-success">
                                <i class="fas fa-check"></i>
                                Konfirmasi
                            </button>
                        @endif

                        @if($order->status == 'ready' && $order->payment_status == 'unpaid')
                            <a href="{{ route('kasir.transactions.create', $order) }}" class="btn btn-primary">
                                <i class="fas fa-credit-card"></i>
                                Bayar
                            </a>
                        @endif

                        @if($order->status != 'completed' && $order->status != 'cancelled')
                            <button onclick="cancelOrder('{{ $order->id }}')" class="btn btn-danger">
                                <i class="fas fa-times"></i>
                                Batal
                            </button>
                        @endif
                    </div>
                </div>
            </div>
            @empty
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
            @endforelse
        </div>
    </div>

    @if($orders->hasPages())
        <div class="pagination-wrapper">
            <div class="pagination-info">
                Menampilkan {{ $orders->firstItem() }} - {{ $orders->lastItem() }} 
                dari {{ $orders->total() }} order
            </div>
            <div class="pagination-links">
                {{ $orders->appends(request()->query())->links() }}
            </div>
        </div>
    @endif
</div>

<style>
/* Enhanced Global Styles */
.orders-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 1rem;
    background: #f8fafc;
    min-height: 100vh;
}

/* Enhanced Header */
.page-header {
    margin-bottom: 2rem;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem;
    border-radius: 16px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.header-text {
    flex: 1;
}

.page-title {
    font-size: 2.25rem;
    font-weight: 800;
    margin: 0 0 0.5rem 0;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.page-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    margin: 0;
}

.header-actions {
    display: flex;
    gap: 1rem;
}

.btn-refresh {
    background: rgba(255,255,255,0.2);
    color: white;
    border: 1px solid rgba(255,255,255,0.3);
    backdrop-filter: blur(10px);
}

.btn-refresh:hover {
    background: rgba(255,255,255,0.3);
    transform: translateY(-2px);
}

/* Enhanced Filters */
.filters-section {
    background: white;
    border-radius: 16px;
    margin-bottom: 2rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.filters-header {
    padding: 1.5rem;
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
}

.filters-title {
    font-size: 1.25rem;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.filters-content {
    padding: 1.5rem;
}

.filters-form {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    align-items: end;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.filter-group label {
    font-size: 0.875rem;
    font-weight: 600;
    color: #374151;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-input, .form-select {
    padding: 0.875rem;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    font-size: 0.875rem;
    transition: all 0.2s ease;
    background: white;
}

.form-input:focus, .form-select:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.filter-actions {
    display: flex;
    gap: 1rem;
    grid-column: 1 / -1;
    justify-content: flex-end;
}

/* Enhanced Buttons */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.875rem 1.5rem;
    border: none;
    border-radius: 12px;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
}

.btn-secondary {
    background: #f8fafc;
    color: #64748b;
    border: 2px solid #e2e8f0;
}

.btn-secondary:hover {
    background: #e2e8f0;
    transform: translateY(-1px);
}

.btn-outline {
    background: transparent;
    color: #3b82f6;
    border: 2px solid #3b82f6;
}

.btn-outline:hover {
    background: #3b82f6;
    color: white;
    transform: translateY(-2px);
}

.btn-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
}

.btn-danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
}

.btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(239, 68, 68, 0.4);
}

/* Enhanced Orders Section */
.orders-section {
    margin-bottom: 2rem;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.section-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.results-count {
    color: #6b7280;
    font-size: 0.875rem;
}

.orders-list {
    display: grid;
    gap: 1.5rem;
}

.order-card {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    position: relative;
    overflow: hidden;
}

.order-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #667eea, #764ba2);
}

.order-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.15);
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1.5rem;
}

.order-number {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1f2937;
    margin: 0 0 0.75rem 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.order-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    font-size: 0.875rem;
    color: #6b7280;
}

.order-meta > span {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.order-type {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-weight: 600;
    background: #dbeafe;
    color: #1e40af;
}

.order-badges {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.status-pending { background: #fef3c7; color: #92400e; }
.status-confirmed { background: #dbeafe; color: #1e40af; }
.status-preparing { background: #fed7aa; color: #9a3412; }
.status-ready { background: #cffafe; color: #155e75; }
.status-completed { background: #dcfce7; color: #166534; }
.status-cancelled { background: #fee2e2; color: #991b1b; }

.payment-paid { background: #dcfce7; color: #166534; }
.payment-unpaid { background: #fef3c7; color: #92400e; }

.order-items {
    margin-bottom: 1.5rem;
    padding: 1.5rem;
    background: #f8fafc;
    border-radius: 12px;
}

.order-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f1f5f9;
    font-size: 0.875rem;
}

.order-item:last-child {
    border-bottom: none;
}

.item-name {
    flex: 1;
    color: #374151;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.item-qty {
    color: #6b7280;
    margin: 0 1rem;
    font-weight: 600;
}

.item-price {
    font-weight: 600;
    color: #1f2937;
}

.more-items {
    padding: 0.75rem 0;
    font-size: 0.875rem;
    color: #6b7280;
    font-style: italic;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.no-items {
    padding: 1rem 0;
    font-size: 0.875rem;
    color: #6b7280;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.order-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 1.5rem;
    border-top: 2px solid #f1f5f9;
}

.order-total {
    font-size: 1.25rem;
    color: #1f2937;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.order-actions {
    display: flex;
    gap: 1rem;
    align-items: center;
}

/* Enhanced Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.empty-icon {
    font-size: 4rem;
    color: #d1d5db;
    margin-bottom: 1.5rem;
}

.empty-state h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #374151;
    margin-bottom: 0.75rem;
}

.empty-state p {
    color: #6b7280;
    margin-bottom: 2rem;
}

/* Enhanced Pagination */
.pagination-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: white;
    padding: 1.5rem;
    border-radius: 16px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.pagination-info {
    color: #6b7280;
    font-size: 0.875rem;
}

.pagination-links {
    display: flex;
    gap: 0.5rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .orders-container {
        padding: 0.5rem;
    }
    
    .header-content {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .page-title {
        font-size: 1.75rem;
    }
    
    .filters-form {
        grid-template-columns: 1fr;
    }
    
    .filter-actions {
        grid-column: 1;
        justify-content: stretch;
    }
    
    .filter-actions .btn {
        flex: 1;
    }
    
    .order-header {
        flex-direction: column;
        gap: 1rem;
    }
    
    .order-badges {
        align-self: flex-start;
    }
    
    .order-footer {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }
    
    .order-actions {
        justify-content: space-between;
    }
    
    .pagination-wrapper {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .section-header {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
}

@media (max-width: 480px) {
    .order-meta {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .order-actions {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .order-actions .btn {
        width: 100%;
        justify-content: center;
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
    const confirmBtn = event.target;
    const originalText = confirmBtn.innerHTML;
    confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
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
            window.location.href = '/kasir/transactions/create/' + orderId;
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
    const cancelBtn = event.target;
    const originalText = cancelBtn.innerHTML;
    cancelBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Cancelling...';
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
