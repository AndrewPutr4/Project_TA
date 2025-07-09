@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@push('styles')
<style>
    :root {
        --brand-primary: #ff6b6b;
        --text-dark: #1e293b;
        --bg-main: #f8fafc;
        --bg-white: #ffffff;
        --border-color: #e2e8f0;
        --error: #ef4444;
        --shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
    }
    body, html { background-color: var(--bg-main); }
    .customer-app-container { padding-bottom: 150px; }
    .cart-header { background: var(--bg-white); padding: 15px 20px; display: flex; align-items: center; gap: 15px; color: var(--text-dark); border-bottom: 1px solid var(--border-color); position: sticky; top: 0; z-index: 10; }
    .back-btn { background: none; border: none; font-size: 24px; cursor: pointer; color: var(--text-dark); text-decoration: none; }
    .header-info h1 { font-size: 20px; font-weight: 600; margin: 0; }
    .clear-btn { margin-left: auto; background: none; border: none; font-size: 20px; cursor: pointer; color: var(--text-light); }
    .cart-content-area { padding: 20px; }
    .cart-item { background: var(--bg-white); border-radius: 16px; padding: 16px; margin-bottom: 16px; box-shadow: var(--shadow); display: flex; gap: 16px; align-items: center; }
    .item-image img { width: 80px; height: 80px; border-radius: 12px; object-fit: cover; }
    .item-details { flex: 1; }
    .item-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 4px; }
    .item-name { font-size: 16px; font-weight: 600; color: var(--text-dark); }
    .remove-btn { background: none; border: none; color: var(--error); cursor: pointer; font-size: 20px; }
    .item-footer { display: flex; justify-content: space-between; align-items: center; margin-top: 8px; }
    .item-price { font-size: 16px; font-weight: 700; color: var(--brand-primary); }
    .quantity-controls { display: flex; align-items: center; gap: 12px; }
    .qty-btn { background: var(--bg-main); border: 1px solid var(--border-color); width: 30px; height: 30px; border-radius: 50%; cursor: pointer; font-size: 18px; font-weight: bold; color: var(--text-dark); line-height: 1; }
    .quantity-display { font-weight: 600; font-size: 16px; }
    .checkout-form-wrapper { background: var(--bg-white); border-radius: 16px; padding: 20px; margin-top: 24px; box-shadow: var(--shadow); }
    .checkout-form-wrapper h3 { font-size: 18px; font-weight: 600; color: var(--text-dark); margin-bottom: 20px; }
    .form-group { margin-bottom: 16px; }
    .form-group label { display: block; font-weight: 500; color: var(--text-dark); margin-bottom: 8px; font-size: 14px; }
    .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 12px 14px; border: 1px solid var(--border-color); border-radius: 10px; font-size: 16px; background: var(--bg-main); font-family: inherit; }
    .form-group input:focus, .form-group textarea:focus, .form-group select:focus { outline: none; border-color: var(--brand-primary); }
    .form-group textarea { resize: vertical; min-height: 80px; }
    .bottom-checkout-bar { position: fixed; bottom: 0; left: 50%; transform: translateX(-50%); max-width: 414px; width: 100%; background: var(--bg-white); padding: 15px 20px; border-top: 1px solid var(--border-color); box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.08); z-index: 20; }
    .summary-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; font-size: 14px; }
    .summary-row.total { font-size: 18px; font-weight: 700; color: var(--text-dark); margin-top: 10px; }
    .summary-row.total .total-amount { color: var(--brand-primary); }
    .checkout-btn-bottom { width: 100%; background: linear-gradient(135deg, var(--brand-primary), #ffa500); color: var(--bg-white); border: none; padding: 16px; border-radius: 12px; font-weight: 600; font-size: 16px; cursor: pointer; margin-top: 15px; }
    .empty-cart { text-align: center; padding: 80px 20px; }
    .empty-cart-icon { font-size: 60px; margin-bottom: 20px; opacity: 0.4; }
    .empty-cart h2 { font-size: 22px; font-weight: 600; color: var(--text-dark); margin-bottom: 8px; }
    .empty-cart p { color: var(--text-light); margin-bottom: 30px; font-size: 16px; }
    .start-shopping-btn { background: linear-gradient(135deg, var(--brand-primary), #ffa500); color: white; padding: 14px 28px; border-radius: 12px; text-decoration: none; font-weight: 600; font-size: 16px; }
</style>
@endpush

@section('content')
<div class="customer-app-container">
    <div class="cart-header">
        <a href="{{ route('home') }}" class="back-btn" title="Kembali">←</a>
        <div class="header-info">
            <h1>Keranjang Saya</h1>
        </div>
        @if(count($cartItems) > 0)
            <form method="POST" action="{{ route('cart.clear') }}" onsubmit="return confirm('Anda yakin ingin mengosongkan keranjang?')">
                @csrf
                <button type="submit" class="clear-btn" title="Kosongkan">🗑️</button>
            </form>
        @endif
    </div>

    @if(session('error'))
        <div class="alert alert-danger" style="margin: 20px; padding: 15px; border-radius: 8px; background-color:#f8d7da; color: #721c24;">{{ session('error') }}</div>
    @endif

    @if(empty($cartItems))
        <div class="empty-cart">
            <div class="empty-cart-icon">🛍️</div>
            <h2>Keranjang Anda kosong</h2>
            <p>Ayo jelajahi menu dan temukan favoritmu!</p>
            <a href="{{ route('home') }}" class="start-shopping-btn">Lihat Menu</a>
        </div>
    @else
        {{-- Formulir ini akan mengirim data ke OrderController --}}
        <form id="checkoutForm" method="POST" action="{{ route('order.checkout') }}">
            @csrf
            <div class="cart-content-area">
                @foreach($cartItems as $item)
                <div class="cart-item">
                    <div class="item-image"><img src="{{ $item['menu']->image_url }}" alt="{{ $item['menu']->name }}" onerror="this.src='https://placehold.co/160x160/f1f5f9/64748b?text=Gambar'"></div>
                    <div class="item-details">
                        <div class="item-header">
                            <h3 class="item-name">{{ $item['menu']->name }}</h3>
                            <form method="POST" action="{{ route('cart.remove') }}" onsubmit="return confirm('Hapus item ini?')">
                                @csrf
                                <input type="hidden" name="menu_id" value="{{ $item['id'] }}">
                                <button type="submit" class="remove-btn" title="Hapus">×</button>
                            </form>
                        </div>
                        <div class="item-footer">
                            <span class="item-price">Rp{{ number_format($item['menu']->price * $item['quantity'], 0, ',', '.') }}</span>
                            <div class="quantity-controls">
                                <form method="POST" action="{{ route('cart.update') }}" style="display:contents">@csrf<input type="hidden" name="menu_id" value="{{ $item['id'] }}"><input type="hidden" name="quantity" value="{{ $item['quantity'] - 1 }}"><button type="submit" class="qty-btn">-</button></form>
                                <span class="quantity-display">{{ $item['quantity'] }}</span>
                                <form method="POST" action="{{ route('cart.update') }}" style="display:contents">@csrf<input type="hidden" name="menu_id" value="{{ $item['id'] }}"><input type="hidden" name="quantity" value="{{ $item['quantity'] + 1 }}"><button type="submit" class="qty-btn">+</button></form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                <div class="checkout-form-wrapper">
                    <h3>Detail Pesanan</h3>
                    <div class="form-group"><label for="customer_name">Nama Lengkap*</label><input type="text" id="customer_name" name="customer_name" required value="{{ old('customer_name') }}"></div>
                    <div class="form-group"><label for="customer_phone">Nomor Telepon*</label><input type="tel" id="customer_phone" name="customer_phone" required value="{{ old('customer_phone') }}"></div>
                    <div class="form-group">
                        <label for="table_number">Nomor Meja (Opsional, untuk makan di tempat)</label>
                        <select id="table_number" name="table_number">
                            <option value="">Pilih Nomor Meja</option>
                            @for ($i = 1; $i <= 20; $i++)
                                <option value="{{ $i }}" {{ old('table_number') == $i ? 'selected' : '' }}>Meja {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="form-group"><label for="customer_address">Alamat Pengiriman (Jika diantar)</label><textarea id="customer_address" name="customer_address" rows="3">{{ old('customer_address') }}</textarea></div>
                    <div class="form-group"><label for="notes">Catatan Tambahan</label><textarea id="notes" name="notes" rows="2" placeholder="Contoh: jangan pakai sambal">{{ old('notes') }}</textarea></div>
                </div>
            </div>
        </form>

        <div class="bottom-checkout-bar">
            <div style="width: 100%;">
                <div class="summary-row"><span>Subtotal</span><span>Rp{{ number_format($subtotal, 0, ',', '.') }}</span></div>
                <div class="summary-row"><span>Biaya Layanan</span><span>Rp{{ number_format($serviceFee, 0, ',', '.') }}</span></div>
                <div class="summary-row total"><span class="total-label">Total</span><span class="total-amount">Rp{{ number_format($total, 0, ',', '.') }}</span></div>
                <button type="submit" form="checkoutForm" class="checkout-btn-bottom">Pesan Sekarang</button>
            </div>
        </div>
    @endif
</div>
@endsection
