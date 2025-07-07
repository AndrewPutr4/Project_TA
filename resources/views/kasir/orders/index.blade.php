@extends('layouts.kasir')
@section('title', 'Daftar Order')
@section('content')
<div class="orders-container">
    <!-- Header -->
    <div class="page-header">
        <div class="header-content">
            <h1 class="page-title">Daftar Order</h1>
            <div class="header-actions">
                <button class="btn btn-primary" onclick="refreshOrders()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="23,4 23,10 17,10"></polyline>
                        <polyline points="1,20 1,14 7,14"></polyline>
                        <path d="M20.49,9A9,9,0,0,0,5.64,5.64L1,10m22,4L18.36,18.36A9,9,0,0,1,3.51,15"></path>
                    </svg>
                    Refresh
                </button>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters-section">
        <form method="GET" class="filters-form">
            <div class="filter-group">
                <label>Status Order</label>
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
                <label>Status Pembayaran</label>
                <select name="payment_status" class="form-select">
                    <option value="">Semua</option>
                    <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Belum Bayar</option>
                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Sudah Bayar</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Tanggal</label>
                <input type="date" name="date" value="{{ request('date') }}" class="form-input">
            </div>
            <div class="filter-group">
                <label>Cari</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="No. Order, Nama, Telepon..." class="form-input">
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('kasir.orders.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>
    </div>

    <!-- Orders List -->
    <div class="orders-list">
        @forelse($orders as $order)
        <div class="order-card" data-order-id="{{ $order->id }}">
            <div class="order-header">
                <div class="order-info">
                    <h3 class="order-number">#{{ $order->order_number }}</h3>
                    <div class="order-meta">
                        <span class="order-date">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                        <span class="customer-name">{{ $order->customer_name }}</span>
                        @if($order->table_number)
                            <span class="table-number">Meja {{ $order->table_number }}</span>
                        @endif
                    </div>
                </div>
                <div class="order-badges">
                    <span class="badge {{ $order->status_badge }}">
                        {{ ucfirst($order->status) }}
                    </span>
                    <span class="badge {{ $order->payment_status_badge }}">
                        {{ $order->payment_status == 'paid' ? 'Lunas' : 'Belum Bayar' }}
                    </span>
                </div>
            </div>
            
            <div class="order-items">
                @forelse($order->orderItems->take(3) as $item)
                    <div class="order-item">
                        <span class="item-name">{{ $item->menu_name }}</span>
                        <span class="item-qty">x{{ $item->quantity }}</span>
                        <span class="item-price">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                    </div>
                @empty
                    <div class="no-items">
                        <em>Tidak ada item</em>
                    </div>
                @endforelse
                
                @if($order->orderItems->count() > 3)
                    <div class="more-items">
                        +{{ $order->orderItems->count() - 3 }} item lainnya
                    </div>
                @endif
            </div>
            
            <div class="order-footer">
                <div class="order-total">
                    <strong>Total: Rp {{ number_format($order->total, 0, ',', '.') }}</strong>
                </div>
                <div class="order-actions">
                    <a href="{{ route('kasir.orders.show', $order) }}" class="btn btn-sm btn-outline">Detail</a>
                    
                    @if($order->status == 'ready' && $order->payment_status == 'unpaid')
                        <a href="{{ route('kasir.transactions.create', $order) }}" class="btn btn-sm btn-success">Bayar</a>
                    @endif
                    
                    @if($order->status != 'completed' && $order->status != 'cancelled')
                        <div class="status-dropdown">
                            <select onchange="updateOrderStatus({{ $order->id }}, this.value)" class="form-select-sm">
                                <option value="">Ubah Status</option>
                                @if($order->status == 'pending')
                                    <option value="confirmed">Konfirmasi</option>
                                @endif
                                @if(in_array($order->status, ['pending', 'confirmed']))
                                    <option value="preparing">Siapkan</option>
                                @endif
                                @if(in_array($order->status, ['confirmed', 'preparing']))
                                    <option value="ready">Siap</option>
                                @endif
                                @if($order->status == 'ready')
                                    <option value="completed">Selesaikan</option>
                                @endif
                                <option value="cancelled">Batalkan</option>
                            </select>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @empty
            <div class="empty-state">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M16 16s-1.5-2-4-2-4 2-4 2"></path>
                    <line x1="9" y1="9" x2="9.01" y2="9"></line>
                    <line x1="15" y1="9" x2="15.01" y2="9"></line>
                </svg>
                <h3>Tidak ada order</h3>
                <p>Belum ada order yang masuk saat ini</p>
            </div>
        @endforelse
    </div>

    @if($orders->hasPages())
        <div class="pagination-wrapper">
            {{ $orders->appends(request()->query())->links() }}
        </div>
    @endif
</div>

<style>
.orders-container {
    max-width: 1200px;
    margin: 0 auto;
}

.page-header {
    margin-bottom: 2rem;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.page-title {
    font-size: 1.875rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
}

.header-actions {
    display: flex;
    gap: 1rem;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
}

.btn-primary {
    background: #3b82f6;
    color: white;
}

.btn-primary:hover {
    background: #2563eb;
}

.btn-secondary {
    background: #f1f5f9;
    color: #64748b;
    border: 1px solid #e2e8f0;
}

.btn-secondary:hover {
    background: #e2e8f0;
}

.btn-outline {
    background: transparent;
    color: #3b82f6;
    border: 1px solid #3b82f6;
}

.btn-outline:hover {
    background: #3b82f6;
    color: white;
}

.btn-success {
    background: #10b981;
    color: white;
}

.btn-success:hover {
    background: #059669;
}

.btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.8125rem;
}

.filters-section {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 2rem;
}

.filters-form {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    align-items: end;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.filter-group label {
    font-size: 0.875rem;
    font-weight: 600;
    color: #374151;
}

.form-input, .form-select {
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 0.875rem;
    transition: border-color 0.2s ease;
}

.form-input:focus, .form-select:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-select-sm {
    padding: 0.375rem 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 4px;
    font-size: 0.8125rem;
    background: white;
}

.filter-actions {
    display: flex;
    gap: 0.5rem;
}

.orders-list {
    display: grid;
    gap: 1rem;
}

.order-card {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 1.5rem;
    transition: all 0.2s ease;
}

.order-card:hover {
    border-color: #cbd5e1;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.order-number {
    font-size: 1.125rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 0.25rem 0;
}

.order-meta {
    display: flex;
    gap: 1rem;
    font-size: 0.875rem;
    color: #64748b;
}

.order-badges {
    display: flex;
    gap: 0.5rem;
}

.badge {
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.bg-yellow-100 { background: #fef3c7; }
.text-yellow-800 { color: #92400e; }
.bg-blue-100 { background: #dbeafe; }
.text-blue-800 { color: #1e40af; }
.bg-orange-100 { background: #fed7aa; }
.text-orange-800 { color: #9a3412; }
.bg-cyan-100 { background: #cffafe; }
.text-cyan-800 { color: #155e75; }
.bg-green-100 { background: #dcfce7; }
.text-green-800 { color: #166534; }
.bg-gray-100 { background: #f3f4f6; }
.text-gray-800 { color: #1f2937; }
.bg-red-100 { background: #fee2e2; }
.text-red-800 { color: #991b1b; }

.order-items {
    margin-bottom: 1rem;
}

.order-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid #f1f5f9;
    font-size: 0.875rem;
}

.order-item:last-child {
    border-bottom: none;
}

.item-name {
    flex: 1;
    color: #374151;
}

.item-qty {
    color: #64748b;
    margin: 0 1rem;
}

.item-price {
    font-weight: 600;
    color: #1e293b;
}

.more-items {
    padding: 0.5rem 0;
    font-size: 0.875rem;
    color: #64748b;
    font-style: italic;
}

.no-items {
    padding: 0.5rem 0;
    font-size: 0.875rem;
    color: #64748b;
    text-align: center;
}

.order-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 1rem;
    border-top: 1px solid #f1f5f9;
}

.order-total {
    font-size: 1.125rem;
    color: #1e293b;
}

.order-actions {
    display: flex;
    gap: 0.75rem;
    align-items: center;
}

.status-dropdown select {
    min-width: 120px;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: #64748b;
}

.empty-state svg {
    margin: 0 auto 1rem auto;
    color: #cbd5e1;
}

.empty-state h3 {
    font-size: 1.25rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
}

@media (max-width: 768px) {
    .filters-form {
        grid-template-columns: 1fr;
    }
    
    .filter-actions {
        grid-column: 1 / -1;
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
}
</style>

<script>
// Store CSRF token for AJAX requests
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

function refreshOrders() {
    window.location.reload();
}

function updateOrderStatus(orderId, status) {
    if (!status) return;
    
    if (!confirm('Apakah Anda yakin ingin mengubah status menjadi ' + status + '?')) {
        // Reset select value if cancelled
        event.target.value = '';
        return;
    }
    
    // Construct URL properly
    const url = '/kasir/orders/' + orderId + '/status';
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ 
            status: status 
        })
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
            alert('Gagal mengupdate status: ' + (data.message || 'Unknown error'));
            // Reset select value on error
            event.target.value = '';
        }
    })
    .catch(function(error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengupdate status');
        // Reset select value on error
        event.target.value = '';
    });
}

// Auto refresh every 30 seconds
setInterval(function() {
    refreshOrders();
}, 30000);
</script>
@endsection
