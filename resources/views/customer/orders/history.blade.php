@extends('layouts.app')
@section('title', 'Riwayat Pesanan Anda')

@section('content')
<style>
    .order-history-page * { box-sizing: border-box !important; }
    .order-history-page { font-family: 'Inter', sans-serif !important; background-color: #f8fafc !important; color: #1e293b !important; line-height: 1.6 !important; min-height: calc(100vh - 200px) !important; }
    .order-history-page .container { max-width: 1000px !important; margin: 0 auto !important; padding: 1.5rem !important; }
    .order-history-page .page-header { display: flex !important; justify-content: space-between !important; align-items: center !important; margin-bottom: 2.5rem !important; padding-bottom: 1.5rem !important; border-bottom: 1px solid #e2e8f0 !important; flex-wrap: wrap !important; gap: 1rem !important; }
    .order-history-page .page-title { font-size: 2rem !important; font-weight: 800 !important; color: #1e293b !important; margin: 0 !important; }
    .order-history-page .back-button { display: inline-flex !important; align-items: center !important; gap: 0.5rem !important; padding: 0.75rem 1.25rem !important; background: white !important; color: #1e293b !important; text-decoration: none !important; border-radius: 12px !important; font-weight: 600 !important; border: 1px solid #e2e8f0 !important; box-shadow: 0 4px 12px rgba(0,0,0,0.05) !important; }
    .order-history-page .back-button:hover { background: #f97316 !important; color: white !important; text-decoration: none !important; }
    .order-history-page .order-list { display: grid !important; gap: 1.5rem !important; margin-bottom: 3rem !important; } /* Reduced gap for more compact look */
    .order-history-page .order-card { background: white !important; border-radius: 20px !important; box-shadow: 0 10px 30px rgba(0,0,0,0.08) !important; border: 1px solid #e2e8f0 !important; overflow: hidden !important; transition: all 0.3s ease !important; }
    .order-history-page .order-card:hover { transform: translateY(-5px) !important; box-shadow: 0 15px 35px rgba(0,0,0,0.12) !important; }
    
    /* Order Card Header */
    .order-history-page .order-card-header { display: flex !important; justify-content: space-between !important; align-items: center !important; padding: 1.25rem 2rem !important; background: linear-gradient(135deg, #f1f5f9, #e2e8f0) !important; border-bottom: 1px solid #e2e8f0 !important; flex-wrap: wrap !important; gap: 1rem !important; }
    .order-history-page .order-info { display: flex !important; align-items: center !important; gap: 1rem !important; }
    .order-history-page .order-icon { width: 36px !important; height: 36px !important; border-radius: 10px !important; display: flex !important; align-items: center !important; justify-content: center !important; font-size: 1.125rem !important; color: white !important; box-shadow: 0 4px 10px rgba(0,0,0,0.2) !important; }
    .order-history-page .status-completed .order-icon { background: linear-gradient(135deg, #10b981, #047857) !important; }
    .order-history-page .status-pending .order-icon { background: linear-gradient(135deg, #f59e0b, #d97706) !important; }
    .order-history-page .status-preparing .order-icon { background: linear-gradient(135deg, #f97316, #ea580c) !important; }
    .order-history-page .status-ready .order-icon { background: linear-gradient(135deg, #06b6d4, #0891b2) !important; }
    .order-history-page .status-cancelled .order-icon { background: linear-gradient(135deg, #ef4444, #dc2626) !important; }
    .order-history-page .status-default .order-icon { background: linear-gradient(135deg, #6b7280, #4b5563) !important; }
    
    .order-history-page .order-number { font-size: 1.125rem !important; font-weight: 700 !important; color: #1e293b !important; margin: 0 !important; }
    .order-history-page .order-status { padding: 0.4rem 0.9rem !important; border-radius: 20px !important; font-size: 0.8rem !important; font-weight: 600 !important; text-transform: uppercase !important; letter-spacing: 0.5px !important; }
    .order-history-page .status-pending { background-color: #fef3c7 !important; color: #d97706 !important; }
    .order-history-page .status-completed { background-color: #d1fae5 !important; color: #059669 !important; }
    .order-history-page .status-cancelled { background-color: #fee2e2 !important; color: #dc2626 !important; }
    .order-history-page .status-confirmed { background-color: #dbeafe !important; color: #2563eb !important; }
    .order-history-page .status-preparing { background-color: #ffedd5 !important; color: #f97316 !important; }
    .order-history-page .status-ready { background-color: #cffafe !important; color: #0891b2 !important; }
    .order-history-page .status-default { background-color: #e5e7eb !important; color: #4b5563 !important; }
    
    /* Order Card Body - Horizontal Layout */
    .order-history-page .order-card-body {
        padding: 1.5rem 2rem !important; /* Adjusted padding */
        display: grid !important;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)) !important; /* More columns for horizontal layout */
        gap: 1rem !important; /* Smaller gap between info blocks */
    }
    .order-history-page .info-block {
        display: flex !important;
        flex-direction: column !important; /* Stack label and value vertically within block */
        align-items: flex-start !important;
        gap: 0.25rem !important; /* Smaller gap between label and value */
        padding: 0.75rem 1rem !important; /* Compact padding */
        background: #f8fafc !important;
        border-radius: 12px !important;
        border: 1px solid #e2e8f0 !important;
        transition: all 0.3s ease !important;
        position: relative !important;
        overflow: hidden !important;
    }
    .order-history-page .info-block::before { content: '' !important; position: absolute !important; top: 0 !important; left: 0 !important; width: 4px !important; height: 100% !important; background: var(--accent-color, #3b82f6) !important; }
    .order-history-page .info-block:hover { transform: translateY(-2px) !important; box-shadow: 0 6px 15px rgba(0,0,0,0.08) !important; background: white !important; }
    
    .order-history-page .info-icon { display: none !important; } /* Hide icons in history card body for compactness */
    
    /* Different colors for different info types */
    .order-history-page .info-block:nth-child(1) { --accent-color: #3b82f6; } /* Date */
    .order-history-page .info-block:nth-child(2) { --accent-color: #10b981; } /* Customer Name */
    .order-history-page .info-block:nth-child(3) { --accent-color: #f59e0b; } /* Item Count */
    .order-history-page .info-block:nth-child(4) { --accent-color: #ef4444; } /* Total Payment */
    
    .order-history-page .info-content { display: flex !important; flex-direction: column !important; flex: 1 !important; min-width: 0 !important; }
    .order-history-page .info-label { font-size: 0.8rem !important; color: #64748b !important; margin-bottom: 0.25rem !important; font-weight: 500 !important; text-transform: uppercase !important; letter-spacing: 0.5px !important; }
    .order-history-page .info-value { font-size: 1rem !important; font-weight: 700 !important; color: #1e293b !important; margin: 0 !important; word-break: break-word !important; }
    
    /* Order Card Footer */
    .order-history-page .order-card-footer { padding: 1.25rem 2rem !important; background: linear-gradient(135deg, #f8fafc, #f1f5f9) !important; border-top: 1px solid #e2e8f0 !important; display: flex !important; justify-content: space-between !important; align-items: center !important; flex-wrap: wrap !important; gap: 1rem !important; }
    .order-history-page .order-summary { display: flex !important; align-items: center !important; gap: 0.5rem !important; font-size: 1rem !important; font-weight: 600 !important; color: #1e293b !important; }
    .order-history-page .detail-button { display: inline-flex !important; align-items: center !important; gap: 0.75rem !important; padding: 0.875rem 1.5rem !important; background: linear-gradient(135deg, #f97316, #ea580c) !important; color: white !important; text-decoration: none !important; border-radius: 14px !important; font-weight: 600 !important; font-size: 0.9rem !important; box-shadow: 0 4px 12px rgba(249,115,22,0.3) !important; transition: all 0.3s ease !important; position: relative !important; overflow: hidden !important; }
    .order-history-page .detail-button::before { content: '' !important; position: absolute !important; top: 0 !important; left: -100% !important; width: 100% !important; height: 100% !important; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent) !important; transition: left 0.5s !important; }
    .order-history-page .detail-button:hover::before { left: 100% !important; }
    .order-history-page .detail-button:hover { transform: translateY(-2px) scale(1.02) !important; box-shadow: 0 8px 20px rgba(249,115,22,0.4) !important; color: white !important; text-decoration: none !important; }
    .order-history-page .detail-button i { transition: transform 0.3s ease !important; }
    .order-history-page .detail-button:hover i { transform: translateX(3px) !important; }
    
    /* No Orders State */
    .order-history-page .no-orders { background: white !important; border-radius: 20px !important; padding: 4rem 2rem !important; text-align: center !important; border: 2px dashed #e2e8f0 !important; margin-bottom: 3rem !important; }
    .order-history-page .no-orders i { font-size: 4rem !important; color: #f97316 !important; margin-bottom: 1.5rem !important; }
    .order-history-page .no-orders h3 { font-size: 1.75rem !important; font-weight: 700 !important; color: #1e293b !important; margin-bottom: 1rem !important; }
    .order-history-page .no-orders p { color: #64748b !important; font-size: 1.125rem !important; margin-bottom: 2rem !important; }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .order-history-page .container { padding: 1rem !important; }
        .order-history-page .page-header { flex-direction: column !important; text-align: center !important; gap: 1.5rem !important; }
        .order-history-page .page-title { font-size: 1.75rem !important; }
        .order-history-page .order-card-header { flex-direction: column !important; text-align: center !important; gap: 1rem !important; padding: 1rem !important; }
        .order-history-page .order-card-body {
            grid-template-columns: 1fr !important; /* Stack vertically on small screens */
            padding: 1rem !important;
            gap: 0.75rem !important;
        }
        .order-history-page .info-block {
            padding: 0.75rem 1rem !important;
            flex-direction: row !important; /* Keep label and value side-by-side */
            justify-content: space-between !important;
            align-items: center !important;
        }
        .order-history-page .info-label {
            font-size: 0.75rem !important;
            margin-bottom: 0 !important;
        }
        .order-history-page .info-value {
            font-size: 0.9rem !important;
        }
        .order-history-page .order-card-footer { flex-direction: column !important; text-align: center !important; padding: 1rem !important; }
        .order-history-page .detail-button { padding: 0.75rem 1.25rem !important; font-size: 0.875rem !important; }
        .order-history-page .order-summary { font-size: 0.9rem !important; }
    }
    
    @media (max-width: 480px) {
        .order-history-page .container { padding: 0.75rem !important; }
        .order-history-page .page-title { font-size: 1.5rem !important; }
        .order-history-page .order-card { border-radius: 16px !important; }
        .order-history-page .order-card-header,
        .order-history-page .order-card-body,
        .order-history-page .order-card-footer {
            padding: 0.75rem !important;
        }
        .order-history-page .order-number { font-size: 1rem !important; }
        .order-history-page .order-status { font-size: 0.7rem !important; padding: 0.3rem 0.7rem !important; }
        .order-history-page .info-block { padding: 0.6rem 0.8rem !important; }
        .order-history-page .info-label { font-size: 0.7rem !important; }
        .order-history-page .info-value { font-size: 0.85rem !important; }
        .order-history-page .no-orders { padding: 1.5rem 1rem !important; }
        .order-history-page .no-orders i { font-size: 2.5rem !important; }
        .order-history-page .no-orders h3 { font-size: 1.25rem !important; }
    }
</style>

<div class="order-history-page">
    <div class="container">
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
                            <div class="order-info">
                                <div class="order-icon status-{{ $order->status ?? 'default' }}">
                                    @if($order->status == 'completed')
                                        <i class="bi bi-check-circle-fill"></i>
                                    @elseif($order->status == 'pending')
                                        <i class="bi bi-clock-fill"></i>
                                    @elseif($order->status == 'preparing')
                                        <i class="bi bi-fire"></i>
                                    @elseif($order->status == 'ready')
                                        <i class="bi bi-bell-fill"></i>
                                    @elseif($order->status == 'cancelled')
                                        <i class="bi bi-x-circle-fill"></i>
                                    @else
                                        <i class="bi bi-receipt"></i>
                                    @endif
                                </div>
                                <span class="order-number">Pesanan #{{ $order->order_number }}</span>
                            </div>
                            <span class="order-status status-{{ $order->status ?? 'default' }}">{{ ucfirst($order->status) }}</span>
                        </div>
                        
                        <div class="order-card-body">
                            <!-- Date -->
                            <div class="info-block">
                                <div class="info-content">
                                    <span class="info-label">Tanggal</span>
                                    <span class="info-value">{{ $order->created_at->format('d M Y, H:i') }}</span>
                                </div>
                            </div>

                            <!-- Customer Name -->
                            <div class="info-block">
                                <div class="info-content">
                                    <span class="info-label">Pelanggan</span>
                                    <span class="info-value">{{ $order->customer_name ?? 'Tidak ada nama' }}</span>
                                </div>
                            </div>

                            <!-- Item Count -->
                            <div class="info-block">
                                <div class="info-content">
                                    <span class="info-label">Jumlah Item</span>
                                    <span class="info-value">{{ $order->items->count() }} item</span>
                                </div>
                            </div>

                            <!-- Total Payment -->
                            <div class="info-block">
                                <div class="info-content">
                                    <span class="info-label">Total Bayar</span>
                                    <span class="info-value">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="order-card-footer">
                            <div class="order-summary">
                                <i class="bi bi-receipt-cutoff"></i>
                                <span>{{ $order->items->count() }} item • Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                            </div>
                            <a href="{{ route('order.show', $order->id) }}" class="detail-button">
                                Lihat Detail <i class="bi bi-arrow-right-short"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
