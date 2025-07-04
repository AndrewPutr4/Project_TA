@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@push('styles')
<style>
    .cart-header {
        background: linear-gradient(135deg, #ff6b6b, #ffa500);
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        color: white;
        box-shadow: 0 4px 20px rgba(255, 107, 107, 0.3);
    }

    .back-btn {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        cursor: pointer;
        text-decoration: none;
    }

    .header-info h1 {
        font-size: 20px;
        font-weight: 700;
        margin: 0;
    }

    .header-info p {
        font-size: 14px;
        opacity: 0.8;
        margin: 0;
    }

    .clear-form {
        margin-left: auto;
    }

    .clear-btn {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        padding: 8px 12px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 16px;
    }

    .alert {
        border-radius: 10px;
        padding: 12px 18px;
        margin: 18px 0 10px 0;
        font-size: 15px;
        font-weight: 600;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        border-left: 5px solid #ff6b6b;
        background: #fff7f7;
        color: #dc2626;
    }
    .alert-success {
        border-left-color: #10b981;
        background: #f0fdf4;
        color: #059669;
    }
    .alert-error {
        border-left-color: #dc2626;
        background: #fef2f2;
        color: #dc2626;
    }

    .cart-content {
        padding: 20px;
        padding-bottom: 200px;
    }

    .cart-item {
        background: white;
        border-radius: 16px;
        padding: 16px;
        margin-bottom: 18px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        display: flex;
        gap: 16px;
        align-items: center;
        border: 1px solid #f1f5f9;
        position: relative;
    }
    .cart-item:not(:last-child)::after {
        content: "";
        position: absolute;
        left: 16px;
        right: 16px;
        bottom: -9px;
        height: 1px;
        background: #f1f5f9;
        border-radius: 2px;
    }

    .item-image img {
        width: 80px;
        height: 80px;
        border-radius: 12px;
        object-fit: cover;
        border: 1px solid #f1f5f9;
        background: #f8fafc;
    }

    .item-details {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .item-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 2px;
    }

    .item-name {
        font-size: 16px;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        line-height: 1.2;
    }

    .remove-form {
        margin: 0;
    }

    .remove-btn {
        background: none;
        border: none;
        color: #dc2626;
        cursor: pointer;
        font-size: 18px;
        padding: 4px 6px;
        border-radius: 4px;
        transition: background 0.15s;
    }
    .remove-btn:hover {
        background: #fee2e2;
    }

    .item-price {
        font-size: 15px;
        font-weight: 700;
        color: #dc2626;
        margin-bottom: 4px;
    }

    .quantity-form {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 2px;
    }

    .qty-btn {
        background: #f1f5f9;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 18px;
        font-weight: bold;
        transition: background 0.15s;
    }
    .qty-btn:hover {
        background: #ffe4e6;
    }

    .quantity-display {
        font-weight: 600;
        min-width: 28px;
        text-align: center;
        margin: 0 8px;
        font-size: 16px;
        color: #1e293b;
        background: #f8fafc;
        border-radius: 6px;
        padding: 2px 0;
    }

    .empty-cart {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 60px 20px;
        text-align: center;
        min-height: 60vh;
    }

    .empty-cart-icon {
        font-size: 80px;
        margin-bottom: 20px;
        opacity: 0.5;
    }

    .empty-cart h2 {
        font-size: 24px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 8px;
    }

    .empty-cart p {
        color: #64748b;
        margin-bottom: 30px;
        font-size: 16px;
    }

    .start-shopping-btn {
        background: linear-gradient(135deg, #ff6b6b, #ffa500);
        color: white;
        padding: 14px 28px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 700;
        font-size: 16px;
    }

    .order-summary {
        position: fixed;
        bottom: 80px;
        left: 50%;
        transform: translateX(-50%);
        max-width: 414px;
        width: 100%;
        padding: 0 20px;
    }

    .summary-card {
        background: white;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        margin-bottom: 12px;
    }

    .summary-card h3 {
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 16px 0;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        font-size: 14px;
    }

    .summary-row.total {
        font-size: 16px;
        font-weight: 700;
        color: #dc2626;
        border-top: 1px solid #e2e8f0;
        padding-top: 12px;
        margin-top: 12px;
    }

    .checkout-form {
        background: white;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .form-group {
        margin-bottom: 16px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 16px;
        outline: none;
        font-family: inherit;
    }

    .form-group textarea {
        resize: vertical;
        min-height: 80px;
    }

    .checkout-btn {
        width: 100%;
        background: linear-gradient(135deg, #ff6b6b, #ffa500);
        color: white;
        border: none;
        padding: 16px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 16px;
        cursor: pointer;
    }
</style>
@endpush

@section('content')
<div class="customer-app-container">
    <!-- Header -->
    <div class="cart-header">
        <a href="{{ route('home') }}" class="back-btn">
            <span>←</span>
        </a>
        <div class="header-info">
            <h1>Keranjang Belanja</h1>
            <p>{{ count($cartItems) }} item dipilih</p>
        </div>
        @if(count($cartItems) > 0)
            <form method="POST" action="{{ route('cart.clear') }}" class="clear-form">
                @csrf
                <button type="submit" class="clear-btn" onclick="return confirm('Yakin ingin mengosongkan keranjang?')">
                    🗑️
                </button>
            </form>
        @endif
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif

    @if(count($cartItems) == 0)
        <!-- Empty Cart State -->
        <div class="empty-cart">
            <div class="empty-cart-icon">🛒</div>
            <h2>Keranjang Kosong</h2>
            <p>Belum ada item yang ditambahkan ke keranjang</p>
            <a href="{{ route('home') }}" class="start-shopping-btn">
                🍽️ Mulai Belanja
            </a>
        </div>
    @else
        <!-- Cart Content -->
        <div class="cart-content">
            @foreach($cartItems as $item)
                <div class="cart-item">
                    <div class="item-image">
                        <img src="{{ $item['menu']->image_url }}" alt="{{ $item['menu']->name }}">
                    </div>
                    <div class="item-details">
                        <div class="item-header">
                            <h3 class="item-name">{{ $item['menu']->name }}</h3>
                            <form method="POST" action="{{ route('cart.remove') }}" class="remove-form">
                                @csrf
                                <input type="hidden" name="menu_id" value="{{ $item['id'] }}">
                                <button type="submit" class="remove-btn" onclick="return confirm('Hapus item ini?')">🗑️</button>
                            </form>
                        </div>
                        <div class="item-price">{{ $item['menu']->formatted_price }}</div>
                        <div class="quantity-form">
                            <form method="POST" action="{{ route('cart.update') }}" style="display: inline;">
                                @csrf
                                <input type="hidden" name="menu_id" value="{{ $item['id'] }}">
                                <input type="hidden" name="quantity" value="{{ $item['quantity'] - 1 }}">
                                <button type="submit" class="qty-btn">−</button>
                            </form>
                            <span class="quantity-display">{{ $item['quantity'] }}</span>
                            <form method="POST" action="{{ route('cart.update') }}" style="display: inline;">
                                @csrf
                                <input type="hidden" name="menu_id" value="{{ $item['id'] }}">
                                <input type="hidden" name="quantity" value="{{ $item['quantity'] + 1 }}">
                                <button type="submit" class="qty-btn">+</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Order Summary -->
        <div class="order-summary">
            <div class="summary-card">
                <h3>📋 Ringkasan Pesanan</h3>
                <div class="summary-row">
                    <span>Subtotal ({{ count($cartItems) }} item)</span>
                    <span>Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="summary-row">
                    <span>🚚 Biaya Pengiriman</span>
                    <span>Rp{{ number_format($deliveryFee, 0, ',', '.') }}</span>
                </div>
                <div class="summary-row">
                    <span>⚡ Biaya Layanan</span>
                    <span>Rp{{ number_format($serviceFee, 0, ',', '.') }}</span>
                </div>
                <div class="summary-row total">
                    <span>Total</span>
                    <span>Rp{{ number_format($total, 0, ',', '.') }}</span>
                </div>
            </div>
            
            <div class="checkout-form">
                <form method="POST" action="{{ route('order.checkout') }}">
                    @csrf
                    <div class="form-group">
                        <label for="customerName">Nama Lengkap *</label>
                        <input type="text" id="customerName" name="customer_name" required>
                    </div>
                    <div class="form-group">
                        <label for="customerPhone">Nomor Telepon *</label>
                        <input type="tel" id="customerPhone" name="customer_phone" required>
                    </div>
                    <div class="form-group">
                        <label for="customerAddress">Alamat Lengkap *</label>
                        <textarea id="customerAddress" name="customer_address" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="table_number">Nomor Meja (Opsional, untuk dine-in)</label>
                        <select id="table_number" name="table_number" class="form-control">
                            <option value="">Pilih Nomor Meja</option>
                            @for($i = 1; $i <= 20; $i++)
                                <option value="{{ $i }}">{{ 'Meja ' . $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="orderNotes">Catatan (Opsional)</label>
                        <textarea id="orderNotes" name="notes" rows="2" placeholder="Contoh: Pedas sedang, tanpa bawang..."></textarea>
                    </div>
                    <button type="submit" class="checkout-btn">
                        🛍️ Pesan Sekarang - Rp{{ number_format($total, 0, ',', '.') }}
                    </button>
                </form>
            </div>
        </div>
    @endif

    <!-- Bottom Navigation -->
    <div class="bottom-nav">
        <a href="{{ route('home') }}" class="nav-item">
            <span class="nav-icon">🏠</span>
            <span class="nav-label">Beranda</span>
        </a>
        <a href="{{ route('cart.index') }}" class="nav-item active">
            <span class="nav-icon">🛒</span>
            <span class="nav-label">Keranjang</span>
            @if(count($cartItems) > 0)
                <span class="cart-badge">{{ array_sum(array_column($cartItems, 'quantity')) }}</span>
            @endif
        </a>
        <a href="#" class="nav-item">
            <span class="nav-icon">❤️</span>
            <span class="nav-label">Favorit</span>
        </a>
        <a href="#" class="nav-item">
            <span class="nav-icon">👤</span>
            <span class="nav-label">Profil</span>
        </a>
    </div>
</div>
@endsection
