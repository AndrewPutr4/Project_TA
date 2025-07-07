@extends('layouts.kasir')

@section('title', 'Detail Order #' . $order->order_number)

@section('content')
<div class="order-detail-container">
    <div class="page-header">
        <div class="header-content">
            <div class="header-left">
                <a href="{{ route('kasir.orders.index') }}" class="back-btn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="15,18 9,12 15,6"></polyline>
                    </svg>
                    Kembali
                </a>
                <h1 class="page-title">Order #{{ $order->order_number }}</h1>
            </div>
            <div class="header-actions">
                <button onclick="printOrder()" class="btn btn-outline">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="6,9 6,2 18,2 18,9"></polyline>
                        <path d="M6,18H4a2,2,0,0,1-2-2V11a2,2,0,0,1,2-2H20a2,2,0,0,1,2,2v5a2,2,0,0,1-2,2H18"></path>
                        <rect x="6" y="14" width="12" height="8"></rect>
                    </svg>
                    Print
                </button>
            </div>
        </div>
    </div>

    <div class="order-detail-grid">
        <!-- Order Info -->
        <div class="order-info-card">
            <div class="card-header">
                <h2>Informasi Order</h2>
                <div class="order-badges">
                    <span class="badge {{ $order->status_badge }}">
                        {{ ucfirst($order->status) }}
                    </span>
                    <span class="badge {{ $order->payment_status_badge }}">
                        {{ $order->payment_status == 'paid' ? 'Lunas' : 'Belum Bayar' }}
                    </span>
                </div>
            </div>
            <div class="card-content">
                <div class="info-grid">
                    <div class="info-item">
                        <label>Nomor Order</label>
                        <span>{{ $order->order_number }}</span>
                    </div>
                    <div class="info-item">
                        <label>Tanggal</label>
                        <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="info-item">
                        <label>Nama Customer</label>
                        <span>{{ $order->customer_name }}</span>
                    </div>
                    <div class="info-item">
                        <label>No. Telepon</label>
                        <span>{{ $order->customer_phone }}</span>
                    </div>
                    @if($order->table_number)
                    <div class="info-item">
                        <label>No. Meja</label>
                        <span>{{ $order->table_number }}</span>
                    </div>
                    @endif
                    @if($order->customer_address)
                    <div class="info-item full-width">
                        <label>Alamat</label>
                        <span>{{ $order->customer_address }}</span>
                    </div>
                    @endif
                    @if($order->notes)
                    <div class="info-item full-width">
                        <label>Catatan</label>
                        <span>{{ $order->notes }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="order-summary-card">
            <div class="card-header">
                <h2>Ringkasan</h2>
            </div>
            <div class="card-content">
                <div class="summary-list">
                    <div class="summary-item">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @if($order->service_fee > 0)
                    <div class="summary-item">
                        <span>Biaya Layanan</span>
                        <span>Rp {{ number_format($order->service_fee, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <div class="summary-item total">
                        <span>Total</span>
                        <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="order-items-card">
            <div class="card-header">
                <h2>Item Pesanan</h2>
                <span class="item-count">{{ $order->orderItems->count() }} item</span>
            </div>
            <div class="card-content">
                <div class="items-list">
                    @foreach($order->orderItems as $item)
                    <div class="order-item">
                        <div class="item-info">
                            <h3 class="item-name">{{ $item->menu_name }}</h3>
                            @if($item->menu_description)
                            <p class="item-description">{{ $item->menu_description }}</p>
                            @endif
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

        <!-- Status Update -->
        @if($order->status != 'completed' && $order->status != 'cancelled')
        <div class="status-update-card">
            <div class="card-header">
                <h2>Update Status</h2>
            </div>
            <div class="card-content">
                <div class="status-buttons">
                    @if($order->status == 'pending')
                    <button onclick="updateStatus('confirmed')" class="btn btn-primary">Konfirmasi Order</button>
                    @endif
                    @if($order->status == 'confirmed')
                    <button onclick="updateStatus('preparing')" class="btn btn-warning">Mulai Persiapan</button>
                    @endif
                    @if($order->status == 'preparing')
                    <button onclick="updateStatus('ready')" class="btn btn-success">Siap Disajikan</button>
                    @endif
                    @if($order->status == 'ready')
                    <button onclick="updateStatus('completed')" class="btn btn-success">Selesai</button>
                    @endif
                    @if($order->status != 'cancelled')
                    <button onclick="updateStatus('cancelled')" class="btn btn-danger">Batalkan</button>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Payment Action -->
        @if($order->status == 'ready' && $order->payment_status == 'unpaid')
        <div class="payment-card">
            <div class="card-header">
                <h2>Pembayaran</h2>
            </div>
            <div class="card-content">
                <a href="{{ route('kasir.transactions.create', $order) }}" class="btn btn-success btn-large">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                        <line x1="1" y1="10" x2="23" y2="10"></line>
                    </svg>
                    Proses Pembayaran
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
.order-detail-container {
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

.header-left {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: #f1f5f9;
    color: #64748b;
    text-decoration: none;
    border-radius: 6px;
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.2s ease;
}

.back-btn:hover {
    background: #e2e8f0;
    color: #475569;
}

.page-title {
    font-size: 1.875rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
}

.order-detail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
}

.order-info-card,
.order-items-card,
.order-summary-card,
.status-update-card,
.payment-card {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
}

.order-items-card {
    grid-column: 1 / -1;
}

.status-update-card {
    grid-column: 1 / -1;
}

.payment-card {
    grid-column: 1 / -1;
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid #f1f5f9;
    background: #f8fafc;
}

.card-header h2 {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
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

.item-count {
    font-size: 0.875rem;
    color: #64748b;
    font-weight: 500;
}

.card-content {
    padding: 1.5rem;
}

.info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.info-item.full-width {
    grid-column: 1 / -1;
}

.info-item label {
    font-size: 0.875rem;
    font-weight: 600;
    color: #64748b;
}

.info-item span {
    font-size: 0.875rem;
    color: #1e293b;
}

.items-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.order-item {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 1rem;
    background: #f8fafc;
    border-radius: 8px;
    border: 1px solid #f1f5f9;
}

.item-info {
    flex: 1;
}

.item-name {
    font-size: 1rem;
    font-weight: 600;
    color: #1e293b;
    margin: 0 0 0.25rem 0;
}

.item-description {
    font-size: 0.8125rem;
    color: #64748b;
    margin: 0.25rem 0;
}

.item-details {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 0.25rem;
    min-width: 120px;
}

.item-price {
    font-size: 0.875rem;
    color: #64748b;
}

.item-qty {
    font-size: 0.875rem;
    color: #64748b;
}

.item-subtotal {
    font-size: 1rem;
    font-weight: 600;
    color: #1e293b;
}

.summary-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.875rem;
}

.summary-item.total {
    padding-top: 0.75rem;
    border-top: 1px solid #e2e8f0;
    font-size: 1.125rem;
    font-weight: 700;
    color: #1e293b;
}

.status-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
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

.btn-large {
    padding: 1rem 2rem;
    font-size: 1rem;
    width: 100%;
    justify-content: center;
}

.btn-primary {
    background: #3b82f6;
    color: white;
}

.btn-primary:hover {
    background: #2563eb;
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

.btn-warning {
    background: #f59e0b;
    color: white;
}

.btn-warning:hover {
    background: #d97706;
}

.btn-success {
    background: #10b981;
    color: white;
}

.btn-success:hover {
    background: #059669;
}

.btn-danger {
    background: #ef4444;
    color: white;
}

.btn-danger:hover {
    background: #dc2626;
}

/* Badge Colors */
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

@media (max-width: 768px) {
    .order-detail-grid {
        grid-template-columns: 1fr;
    }
    
    .header-content {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .order-item {
        flex-direction: column;
        gap: 1rem;
    }
    
    .item-details {
        align-items: flex-start;
        width: 100%;
    }
    
    .status-buttons {
        flex-direction: column;
    }
    
    .btn {
        justify-content: center;
    }
}
</style>

<script>
function updateStatus(status) {
    if (!confirm('Apakah Anda yakin ingin mengubah status menjadi ' + status + '?')) {
        return;
    }
    
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
            location.reload();
        } else {
            alert('Gagal mengupdate status: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengupdate status');
    });
}

function printOrder() {
    window.print();
}
</script>
@endsection
