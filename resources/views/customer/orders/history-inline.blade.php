@extends('layouts.app')
@section('title', 'Riwayat Pesanan Anda')

@section('content')
<style>
    .order-history-page * { box-sizing: border-box !important; }
    .order-history-page { font-family: 'Inter', sans-serif !important; background-color: #f8fafc !important; color: #1e293b !important; line-height: 1.6 !important; min-height: calc(100vh - 200px) !important; }
    .order-history-page .container { max-width: 900px !important; margin: 0 auto !important; padding: 1.5rem !important; }
    .order-history-page .page-header { display: flex !important; justify-content: space-between !important; align-items: center !important; margin-bottom: 2.5rem !important; padding-bottom: 1.5rem !important; border-bottom: 1px solid #e2e8f0 !important; flex-wrap: wrap !important; gap: 1rem !important; }
    .order-history-page .page-title { font-size: 2rem !important; font-weight: 800 !important; color: #1e293b !important; margin: 0 !important; }
    .order-history-page .back-button { display: inline-flex !important; align-items: center !important; gap: 0.5rem !important; padding: 0.75rem 1.25rem !important; background: white !important; color: #1e293b !important; text-decoration: none !important; border-radius: 12px !important; font-weight: 600 !important; border: 1px solid #e2e8f0 !important; box-shadow: 0 4px 12px rgba(0,0,0,0.05) !important; }
    .order-history-page .back-button:hover { background: #f97316 !important; color: white !important; text-decoration: none !important; }
    .order-history-page .order-list { display: grid !important; gap: 1.5rem !important; margin-bottom: 3rem !important; }
    .order-history-page .order-card { background: white !important; border-radius: 16px !important; box-shadow: 0 8px 25px rgba(0,0,0,0.07) !important; border: 1px solid #e2e8f0 !important; overflow: hidden !important; transition: all 0.3s ease !important; }
    .order-history-page .order-card:hover { transform: translateY(-5px) !important; box-shadow: 0 12px 35px rgba(0,0,0,0.1) !important; }
    .order-history-page .order-card-header { display: flex !important; justify-content: space-between !important; align-items: center !important; padding: 1rem 1.5rem !important; background: #f1f5f9 !important; border-bottom: 1px solid #e2e8f0 !important; flex-wrap: wrap !important; gap: 0.5rem !important; }
    .order-history-page .order-info { display: flex !important; align-items: center !important; gap: 0.75rem !important; }
    .order-history-page .order-icon { width: 32px !important; height: 32px !important; border-radius: 8px !important; display: flex !important; align-items: center !important; justify-content: center !important; font-size: 1.125rem !important; color: white !important; background: linear-gradient(135deg, #10b981, #047857) !important; }
    .order-history-page .order-number { font-weight: 700 !important; color: #1e293b !important; margin: 0 !important; }
    .order-history-page .order-status { padding: 0.3rem 0.8rem !important; border-radius: 20px !important; font-size: 0.8rem !important; font-weight: 600 !important; text-transform: uppercase !important; background-color: #d1fae5 !important; color: #059669 !important; }
    .order-history-page .order-card-body { padding: 1.5rem !important; display: grid !important; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)) !important; gap: 1rem !important; }
    .order-history-page .info-block { display: flex !important; align-items: center !important; gap: 1rem !important; padding: 1rem !important; background: linear-gradient(135deg, #f8fafc, #e2e8f0) !important; border-radius: 12px !important; border: 1px solid #e2e8f0 !important; transition: all 0.3s ease !important; }
    .order-history-page .info-block:hover { transform: translateY(-2px) !important; box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important; background: white !important; }
    .order-history-page .info-icon { width: 48px !important; height: 48px !important; border-radius: 12px !important; display: flex !important; align-items: center !important; justify-content: center !important; font-size: 1.5rem !important; color: white !important; box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important; }
    .order-history-page .info-block:nth-child(1) .info-icon { background: linear-gradient(135deg, #3b82f6, #1d4ed8) !important; }
    .order-history-page .info-block:nth-child(2) .info-icon { background: linear-gradient(135deg, #10b981, #047857) !important; }
    .order-history-page .info-block:nth-child(3) .info-icon { background: linear-gradient(135deg, #f59e0b, #d97706) !important; }
    .order-history-page .info-content { display: flex !important; flex-direction: column !important; flex: 1 !important; }
    .order-history-page .info-label { font-size: 0.875rem !important; color: #64748b !important; margin-bottom: 0.25rem !important; font-weight: 500 !important; }
    .order-history-page .info-value { font-size: 1rem !important; font-weight: 700 !important; color: #1e293b !important; margin: 0 !important; }
    .order-history-page .order-card-footer { padding: 1rem 1.5rem !important; background: #f8fafc !important; border-top: 1px solid #e2e8f0 !important; text-align: right !important; }
    .order-history-page .detail-button { display: inline-flex !important; align-items: center !important; gap: 0.5rem !important; padding: 0.75rem 1.5rem !important; background: linear-gradient(135deg, #f97316, #ea580c) !important; color: white !important; text-decoration: none !important; border-radius: 12px !important; font-weight: 600 !important; box-shadow: 0 4px 12px rgba(249,115,22,0.3) !important; transition: all 0.3s ease !important; }
    .order-history-page .detail-button:hover { transform: translateY(-2px) scale(1.02) !important; box-shadow: 0 8px 20px rgba(249,115,22,0.4) !important; color: white !important; text-decoration: none !important; }
    .order-history-page .no-orders { background: white !important; border-radius: 16px !important; padding: 3rem 2rem !important; text-align: center !important; border: 2px dashed #e2e8f0 !important; margin-bottom: 3rem !important; }
    .order-history-page .no-orders i { font-size: 3rem !important; color: #f97316 !important; margin-bottom: 1rem !important; }
    .order-history-page .no-orders h3 { font-size: 1.5rem !important; font-weight: 700 !important; color: #1e293b !important; margin-bottom: 0.5rem !important; }
    .order-history-page .no-orders p { color: #64748b !important; margin-bottom: 1.5rem !important; }
    @media (max-width: 768px) {
        .order-history-page .container { padding: 1rem !important; }
        .order-history-page .page-header { flex-direction: column !important; text-align: center !important; gap: 1.5rem !important; }
        .order-history-page .page-title { font-size: 1.75rem !important; }
        .order-history-page .order-card-header { flex-direction: column !important; text-align: center !important; gap: 1rem !important; }
        .order-history-page .order-card-body { grid-template-columns: 1fr !important; }
        .order-history-page .info-icon { width: 40px !important; height: 40px !important; font-size: 1.25rem !important; }
        .order-history-page .order-card-footer { text-align: center !important; }
    }
</style>

<div class="order-history-page">
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
                            <div class="order-info">
                                <div class="order-icon">
                                    @if($order->status == 'completed')
                                        <i class="bi bi-check-circle-fill"></i>
                                    @elseif($order->status == 'pending')
                                        <i class="bi bi-clock-fill"></i>
                                    @elseif($order->status == 'preparing')
                                        <i class="bi bi-fire"></i>
                                    @elseif($order->status == 'ready')
                                        <i class="bi bi-bell-fill"></i>
                                    @else
                                        <i class="bi bi-receipt"></i>
                                    @endif
                                </div>
                                <span class="order-number">Pesanan #{{ $order->order_number }}</span>
                            </div>
                            <span class="order-status status-{{ $order->status ?? 'default' }}">{{ ucfirst($order->status) }}</span>
                        </div>
                        <div class="order-card-body">
                            <div class="info-block">
                                <div class="info-icon">
                                    <i class="bi bi-calendar-event"></i>
                                </div>
                                <div class="info-content">
                                    <span class="info-label">Tanggal</span>
                                    <span class="info-value">{{ $order->created_at->format('d M Y, H:i') }}</span>
                                </div>
                            </div>
                            <div class="info-block">
                                <div class="info-icon">
                                    <i class="bi bi-basket3"></i>
                                </div>
                                <div class="info-content">
                                    <span class="info-label">Jumlah Item</span>
                                    <span class="info-value">{{ $order->items->count() }} item</span>
                                </div>
                            </div>
                            <div class="info-block">
                                <div class="info-icon">
                                    <i class="bi bi-cash-coin"></i>
                                </div>
                                <div class="info-content">
                                    <span class="info-label">Total Bayar</span>
                                    <span class="info-value">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="order-card-footer">
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
