@extends('layouts.app')

@section('title', 'Pesanan Berhasil')

@push('styles')
<style>
    .success-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 60px 20px;
        text-align: center;
        min-height: 80vh;
    }

    .success-icon {
        font-size: 100px;
        margin-bottom: 30px;
        animation: bounce 2s infinite;
    }

    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% {
            transform: translateY(0);
        }
        40% {
            transform: translateY(-30px);
        }
        60% {
            transform: translateY(-15px);
        }
    }

    .success-title {
        font-size: 28px;
        font-weight: 700;
        color: #10b981;
        margin-bottom: 12px;
    }

    .success-message {
        color: #64748b;
        margin-bottom: 20px;
        font-size: 16px;
        line-height: 1.5;
    }

    .order-info {
        background: #f8fafc;
        border-radius: 16px;
        padding: 20px;
        margin: 20px 0;
        border: 1px solid #e2e8f0;
    }

    .order-number {
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 8px;
    }

    .order-status {
        color: #f59e0b;
        font-weight: 600;
    }

    .action-buttons {
        display: flex;
        gap: 12px;
        margin-top: 30px;
    }

    .btn-home {
        background: linear-gradient(135deg, #ff6b6b, #ffa500);
        color: white;
        padding: 14px 28px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 700;
        font-size: 16px;
    }

    .btn-track {
        background: #f1f5f9;
        color: #475569;
        padding: 14px 28px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        font-size: 16px;
    }
</style>
@endpush

@section('content')
<div class="success-container">
    <div class="success-icon">✅</div>
    <h1 class="success-title">Pesanan Berhasil!</h1>
    <p class="success-message">
        Terima kasih! Pesanan Anda telah berhasil dibuat dan sedang menunggu konfirmasi dari restoran.
    </p>
    
    @if(session('order_number'))
        <div class="order-info">
            <div class="order-number">
                Nomor Pesanan: {{ session('order_number') }}
            </div>
            <div class="order-status">
                Status: Menunggu Konfirmasi
            </div>
        </div>
    @endif
    
    <div class="action-buttons">
        <a href="{{ route('home') }}" class="btn-home">
            🏠 Kembali ke Beranda
        </a>
        @if(session('order_id'))
            <a href="{{ route('order.show', session('order_id')) }}" class="btn-track">
                📋 Lihat Detail
            </a>
        @endif
    </div>
</div>
@endsection
