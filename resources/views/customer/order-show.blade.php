@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $order->order_number . ' - Warung Bakso Selingsing')

@section('content')
<main class="main">
    <section class="order-detail-section py-5">
        <div class="container">
            <div class="row">
                <!-- Order Header -->
                <div class="col-12 mb-4">
                    <div class="order-header-card">
                        <div class="d-flex justify-content-between align-items-start flex-wrap">
                            <div>
                                <h2 class="order-title">Detail Pesanan</h2>
                                <h3 class="order-number">#{{ $order->order_number }}</h3>
                                <p class="order-date text-muted">
                                    <i class="bi bi-calendar3 me-2"></i>
                                    {{ $order->created_at->format('d F Y, H:i') }} WIB
                                </p>
                            </div>
                            <div class="order-status-badge">
                                <span class="badge badge-{{ strtolower($order->status) }}">
                                    <i class="bi bi-{{ $order->status_icon }} me-1"></i>
                                    {{ $order->status_text }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Progress -->
                <div class="col-12 mb-4">
                    <div class="progress-card">
                        <h5 class="mb-3">Status Pesanan</h5>
                        <div class="order-progress">
                            <div class="progress-step {{ $order->status_step >= 1 ? 'completed' : '' }}">
                                <div class="step-icon">
                                    <i class="bi bi-receipt"></i>
                                </div>
                                <div class="step-content">
                                    <h6>Pesanan Diterima</h6>
                                    <small>{{ $order->created_at->format('H:i') }}</small>
                                </div>
                            </div>
                            
                            <div class="progress-step {{ $order->status_step >= 2 ? 'completed' : '' }}">
                                <div class="step-icon">
                                    <i class="bi bi-fire"></i>
                                </div>
                                <div class="step-content">
                                    <h6>Sedang Dimasak</h6>
                                    <small>{{ $order->cooking_started_at ? $order->cooking_started_at->format('H:i') : '-' }}</small>
                                </div>
                            </div>
                            
                            <div class="progress-step {{ $order->status_step >= 3 ? 'completed' : '' }}">
                                <div class="step-icon">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                                <div class="step-content">
                                    <h6>Siap Diambil</h6>
                                    <small>{{ $order->ready_at ? $order->ready_at->format('H:i') : '-' }}</small>
                                </div>
                            </div>
                            
                            <div class="progress-step {{ $order->status_step >= 4 ? 'completed' : '' }}">
                                <div class="step-icon">
                                    <i class="bi bi-bag-check"></i>
                                </div>
                                <div class="step-content">
                                    <h6>Selesai</h6>
                                    <small>{{ $order->completed_at ? $order->completed_at->format('H:i') : '-' }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Order Items -->
                    <div class="col-lg-8 mb-4">
                        <div class="items-card">
                            <h5 class="card-title mb-4">
                                <i class="bi bi-basket3 me-2"></i>
                                Item Pesanan
                            </h5>
                            
                            <div class="order-items">
                                @foreach($order->items as $item)
                                    <div class="order-item">
                                        <div class="item-image">
                                            <img src="{{ $item->menu->image_url ?? '/placeholder.svg?height=60&width=60' }}" 
                                                 alt="{{ $item->menu->name }}" 
                                                 class="img-fluid rounded">
                                        </div>
                                        <div class="item-details">
                                            <h6 class="item-name">{{ $item->menu->name }}</h6>
                                            <p class="item-description text-muted">{{ $item->menu->description }}</p>
                                            @if($item->notes)
                                                <p class="item-notes">
                                                    <i class="bi bi-chat-left-text me-1"></i>
                                                    <em>{{ $item->notes }}</em>
                                                </p>
                                            @endif
                                        </div>
                                        <div class="item-quantity">
                                            <span class="quantity-badge">{{ $item->quantity }}x</span>
                                        </div>
                                        <div class="item-price">
                                            <div class="unit-price text-muted">
                                                @Rp {{ number_format($item->price, 0, ',', '.') }}
                                            </div>
                                            <div class="total-price fw-bold">
                                                Rp {{ number_format($item->total_price, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary & Customer Info -->
                    <div class="col-lg-4">
                        <!-- Customer Information -->
                        <div class="customer-card mb-4">
                            <h5 class="card-title mb-3">
                                <i class="bi bi-person me-2"></i>
                                Informasi Pelanggan
                            </h5>
                            <div class="customer-info">
                                <div class="info-item">
                                    <label>Nama:</label>
                                    <span>{{ $order->customer_name }}</span>
                                </div>
                                @if($order->customer_phone)
                                    <div class="info-item">
                                        <label>Telepon:</label>
                                        <span>{{ $order->customer_phone }}</span>
                                    </div>
                                @endif
                                <div class="info-item">
                                    <label>Tipe Pesanan:</label>
                                    <span class="badge badge-outline">
                                        <i class="bi bi-{{ $order->order_type == 'dine_in' ? 'house' : 'bag' }} me-1"></i>
                                        {{ $order->order_type == 'dine_in' ? 'Makan di Tempat' : 'Bungkus' }}
                                    </span>
                                </div>
                                @if($order->table_number)
                                    <div class="info-item">
                                        <label>Nomor Meja:</label>
                                        <span class="table-number">{{ $order->table_number }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Order Summary -->
                        <div class="summary-card">
                            <h5 class="card-title mb-3">
                                <i class="bi bi-calculator me-2"></i>
                                Ringkasan Pembayaran
                            </h5>
                            <div class="summary-details">
                                <div class="summary-row">
                                    <span>Subtotal</span>
                                    <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                                </div>
                                @if($order->tax_amount > 0)
                                    <div class="summary-row">
                                        <span>Pajak ({{ $order->tax_percentage }}%)</span>
                                        <span>Rp {{ number_format($order->tax_amount, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                                @if($order->service_charge > 0)
                                    <div class="summary-row">
                                        <span>Biaya Layanan</span>
                                        <span>Rp {{ number_format($order->service_charge, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                                @if($order->discount_amount > 0)
                                    <div class="summary-row discount">
                                        <span>Diskon</span>
                                        <span>-Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                                <hr>
                                <div class="summary-row total">
                                    <span>Total</span>
                                    <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                </div>
                                <div class="summary-row">
                                    <span>Metode Pembayaran</span>
                                    <span class="payment-method">
                                        <i class="bi bi-{{ $order->payment_method_icon }} me-1"></i>
                                        {{ $order->payment_method_text }}
                                    </span>
                                </div>
                                <div class="summary-row">
                                    <span>Status Pembayaran</span>
                                    <span class="badge badge-{{ strtolower($order->payment_status) }}">
                                        {{ $order->payment_status_text }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="action-buttons mt-4">
                            @if($order->status == 'pending' || $order->status == 'confirmed')
                                <button class="btn btn-outline-danger btn-sm w-100 mb-2" 
                                        onclick="cancelOrder('{{ $order->id }}')">
                                    <i class="bi bi-x-circle me-1"></i>
                                    Batalkan Pesanan
                                </button>
                            @endif
                            
                            <a href="{{ route('orders.print', $order->id) }}" 
                               class="btn btn-outline-primary btn-sm w-100 mb-2" target="_blank">
                                <i class="bi bi-printer me-1"></i>
                                Cetak Struk
                            </a>
                            
                            <a href="{{ route('home') }}" class="btn btn-warning btn-sm w-100">
                                <i class="bi bi-arrow-left me-1"></i>
                                Kembali ke Menu
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
    .order-detail-section {
        background: linear-gradient(135deg, #f8fafc 0%, #fffbe6 100%);
        min-height: 100vh;
    }

    .order-header-card, .progress-card, .items-card, .customer-card, .summary-card {
        background: #fff;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        border: 1px solid #e9ecef;
    }

    .order-title {
        color: #2c3e50;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .order-number {
        color: #e67e22;
        font-weight: 800;
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }

    .order-status-badge .badge {
        font-size: 1rem;
        padding: 0.75rem 1.5rem;
        border-radius: 25px;
    }

    .badge-pending { background: #fff3cd; color: #856404; border: 1px solid #ffeaa7; }
    .badge-confirmed { background: #cce5ff; color: #004085; border: 1px solid #74c0fc; }
    .badge-cooking { background: #ffe0b3; color: #cc5500; border: 1px solid #ffb366; }
    .badge-ready { background: #d4edda; color: #155724; border: 1px solid #95d5a0; }
    .badge-completed { background: #d1ecf1; color: #0c5460; border: 1px solid #7dd3fc; }
    .badge-cancelled { background: #f8d7da; color: #721c24; border: 1px solid #f1aeb5; }

    .badge-outline {
        background: transparent;
        border: 1px solid #dee2e6;
        color: #6c757d;
    }

    .order-progress {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin: 2rem 0;
    }

    .order-progress::before {
        content: '';
        position: absolute;
        top: 25px;
        left: 25px;
        right: 25px;
        height: 2px;
        background: #e9ecef;
        z-index: 1;
    }

    .progress-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        z-index: 2;
        flex: 1;
    }

    .step-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.5rem;
        transition: all 0.3s ease;
    }

    .progress-step.completed .step-icon {
        background: #28a745;
        color: white;
    }

    .step-content {
        text-align: center;
    }

    .step-content h6 {
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
        font-weight: 600;
    }

    .step-content small {
        color: #6c757d;
        font-size: 0.8rem;
    }

    .order-item {
        display: flex;
        align-items: center;
        padding: 1.5rem 0;
        border-bottom: 1px solid #e9ecef;
    }

    .order-item:last-child {
        border-bottom: none;
    }

    .item-image {
        width: 60px;
        height: 60px;
        margin-right: 1rem;
        flex-shrink: 0;
    }

    .item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border: 2px solid #e9ecef;
    }

    .item-details {
        flex: 1;
        margin-right: 1rem;
    }

    .item-name {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.25rem;
    }

    .item-description {
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
    }

    .item-notes {
        font-size: 0.85rem;
        color: #e67e22;
        margin-bottom: 0;
    }

    .quantity-badge {
        background: #e67e22;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        margin-right: 1rem;
    }

    .item-price {
        text-align: right;
        min-width: 120px;
    }

    .unit-price {
        font-size: 0.9rem;
    }

    .total-price {
        font-size: 1.1rem;
        color: #e67e22;
    }

    .customer-info .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f8f9fa;
    }

    .customer-info .info-item:last-child {
        border-bottom: none;
    }

    .customer-info label {
        font-weight: 600;
        color: #6c757d;
        margin-bottom: 0;
    }

    .table-number {
        background: #e67e22;
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-weight: 600;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f8f9fa;
    }

    .summary-row:last-child {
        border-bottom: none;
    }

    .summary-row.discount {
        color: #28a745;
    }

    .summary-row.total {
        font-size: 1.2rem;
        font-weight: 700;
        color: #e67e22;
        border-top: 2px solid #e9ecef;
        margin-top: 0.5rem;
        padding-top: 1rem;
    }

    .payment-method {
        font-weight: 600;
    }

    .action-buttons .btn {
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .action-buttons .btn:hover {
        transform: translateY(-1px);
    }

    @media (max-width: 768px) {
        .order-header-card, .progress-card, .items-card, .customer-card, .summary-card {
            padding: 1.5rem;
            margin-bottom: 1rem;
        }

        .order-progress {
            flex-direction: column;
            gap: 1rem;
        }

        .order-progress::before {
            display: none;
        }

        .progress-step {
            flex-direction: row;
            text-align: left;
        }

        .step-icon {
            margin-right: 1rem;
            margin-bottom: 0;
        }

        .order-item {
            flex-wrap: wrap;
            gap: 1rem;
        }

        .item-details {
            flex: 1 1 100%;
            margin-right: 0;
        }

        .quantity-badge {
            margin-right: 0;
        }
    }
</style>

<script>
    function cancelOrder(orderId) {
        if (confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')) {
            fetch(`/orders/${orderId}/cancel`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Gagal membatalkan pesanan. Silakan coba lagi.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan. Silakan coba lagi.');
            });
        }
    }

    // Auto refresh status every 30 seconds
    setInterval(function() {
        if (document.querySelector('.badge-pending, .badge-confirmed, .badge-cooking')) {
            location.reload();
        }
    }, 30000);
</script>
@endsection
