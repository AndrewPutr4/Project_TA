@extends('layouts.kasir')

@section('content')
<div class="pos-system">
    {{-- Top Navigation Bar --}}
    <header class="pos-header">
        <div class="header-left">
            <div class="store-info">
                <h1 class="store-name">KASIR SYSTEM</h1>
                <span class="store-subtitle">Point of Sale</span>
            </div>
        </div>
        <div class="header-center">
            <form method="GET" action="{{ url('/kasir/dashboard') }}" class="search-bar">
                <div class="search-input-group">
                    <svg class="search-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                    <input type="text" 
                           name="search" 
                           placeholder="Cari produk..." 
                           class="search-input" 
                           value="{{ request('search') }}">
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                </div>
            </form>
        </div>
        <div class="header-right">
            <div class="session-info">
                <div class="session-detail">
                    <span class="session-label">Kasir</span>
                    <span class="session-value">Admin</span>
                </div>
                <div class="session-detail">
                    <span class="session-label">Waktu</span>
                    <span class="session-value" id="current-time">{{ date('H:i') }}</span>
                </div>
            </div>
        </div>
    </header>

    <div class="pos-body">
        {{-- Categories Sidebar --}}
        <aside class="categories-panel">
            <div class="panel-title">
                <h3>KATEGORI PRODUK</h3>
            </div>
            <nav class="categories-list">
                <a href="{{ url('/kasir/dashboard') }}" 
                   class="category-item {{ !request('category') ? 'active' : '' }}">
                    <span class="category-label">SEMUA PRODUK</span>
                    <span class="category-count">{{ $foods->count() }}</span>
                </a>
                @foreach($categories as $category)
                    @php
                        $isActive = request('category') == $category->id;
                        $categoryCount = $foods->where('category_id', $category->id)->count();
                    @endphp
                    <a href="{{ url('/kasir/dashboard?category=' . $category->id) }}"
                       class="category-item {{ $isActive ? 'active' : '' }}">
                        <span class="category-label">{{ strtoupper($category->name) }}</span>
                        <span class="category-count">{{ $categoryCount }}</span>
                    </a>
                @endforeach
            </nav>
        </aside>

        {{-- Products Grid --}}
        <main class="products-panel">
            <div class="products-header">
                <div class="products-info">
                    <h2 class="products-title">DAFTAR PRODUK</h2>
                    <span class="products-count">{{ $foods->count() }} produk tersedia</span>
                </div>
            </div>
            
            <div class="products-grid">
                @forelse($foods as $food)
                    <div class="product-card" data-product-id="{{ $food->id }}">
                        <div class="product-image">
                            <img src="{{ $food->image ? asset('storage/' . $food->image) : asset('storage/menus/no-image.png') }}" 
                                 alt="{{ $food->name }}">
                            <div class="product-overlay">
                                <button class="quick-add-btn" 
                                        data-food-id="{{ $food->id }}" 
                                        data-food-name="{{ $food->name }}" 
                                        data-food-price="{{ $food->price }}">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                    TAMBAH
                                </button>
                            </div>
                        </div>
                        <div class="product-info">
                            <h4 class="product-name">{{ $food->name }}</h4>
                            <div class="product-price">Rp{{ number_format($food->price, 0, ',', '.') }}</div>
                            <button class="add-product-btn" 
                                    data-food-id="{{ $food->id }}" 
                                    data-food-name="{{ $food->name }}" 
                                    data-food-price="{{ $food->price }}">
                                TAMBAH KE KERANJANG
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="no-products">
                        <div class="no-products-icon">
                            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg>
                        </div>
                        <h3>TIDAK ADA PRODUK</h3>
                        <p>Silakan pilih kategori lain atau ubah kata kunci pencarian</p>
                    </div>
                @endforelse
            </div>
        </main>

        {{-- Order Panel --}}
        <aside class="order-panel">
            <div class="panel-title">
                <h3>DETAIL TRANSAKSI</h3>
            </div>

            <div class="transaction-info">
                <div class="transaction-row">
                    <span class="transaction-label">NO. TRANSAKSI</span>
                    <span class="transaction-value">#{{ date('Ymd') }}001</span>
                </div>
                <div class="transaction-row">
                    <span class="transaction-label">MEJA</span>
                    <span class="transaction-value">A1</span>
                </div>
                <div class="transaction-row">
                    <span class="transaction-label">PELANGGAN</span>
                    <span class="transaction-value">UMUM</span>
                </div>
            </div>

            <div class="order-items-container" id="order-items">
                <div class="empty-order">
                    <div class="empty-order-icon">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                            <circle cx="9" cy="21" r="1"></circle>
                            <circle cx="20" cy="21" r="1"></circle>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                        </svg>
                    </div>
                    <p>BELUM ADA ITEM</p>
                    <small>Pilih produk untuk memulai transaksi</small>
                </div>
            </div>

            <div class="order-calculation">
                <div class="calculation-row">
                    <span>SUBTOTAL</span>
                    <span id="subtotal">Rp 0</span>
                </div>
                <div class="calculation-row">
                    <span>PAJAK (10%)</span>
                    <span id="tax">Rp 0</span>
                </div>
                <div class="calculation-row">
                    <span>DISKON</span>
                    <span id="discount">Rp 0</span>
                </div>
                <div class="calculation-divider"></div>
                <div class="calculation-total">
                    <span>TOTAL BAYAR</span>
                    <span id="total">Rp 0</span>
                </div>
            </div>

            <div class="order-actions">
                <button class="action-btn secondary" id="clear-order-btn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="3,6 5,6 21,6"></polyline>
                        <path d="m19,6v14a2,2 0 0,1 -2,2H7a2,2 0 0,1 -2,-2V6m3,0V4a2,2 0 0,1 2,-2h4a2,2 0 0,1 2,2v2"></path>
                    </svg>
                    BERSIHKAN
                </button>
                <button class="action-btn primary" id="process-payment-btn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                        <line x1="1" y1="10" x2="23" y2="10"></line>
                    </svg>
                    PROSES BAYAR
                </button>
            </div>
        </aside>
    </div>
</div>

<!-- Clear Order Confirmation Modal -->
<div class="modal-overlay" id="clearOrderModal">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="3,6 5,6 21,6"></polyline>
                    <path d="m19,6v14a2,2 0 0,1 -2,2H7a2,2 0 0,1 -2,-2V6m3,0V4a2,2 0 0,1 2,-2h4a2,2 0 0,1 2,2v2"></path>
                </svg>
            </div>
            <h3 class="modal-title">Konfirmasi Bersihkan</h3>
            <p class="modal-message">Apakah Anda yakin ingin menghapus semua item dari keranjang? Tindakan ini tidak dapat dibatalkan.</p>
        </div>
        <div class="modal-body">
            <div class="modal-actions">
                <button type="button" class="modal-btn modal-btn-cancel" id="cancelClear">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                    Batal
                </button>
                <button type="button" class="modal-btn modal-btn-confirm" id="confirmClear">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="20,6 9,17 4,12"></polyline>
                    </svg>
                    Ya, Bersihkan
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Reset dan Base Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f8fafc;
    color: #1e293b;
    line-height: 1.5;
    font-size: 14px;
}

/* POS System Container */
.pos-system {
    height: 100vh;
    display: flex;
    flex-direction: column;
    background-color: #ffffff;
    overflow: hidden;
}

/* Header Styles */
.pos-header {
    background-color: #ffffff;
    border-bottom: 2px solid #e2e8f0;
    padding: 16px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    min-height: 80px;
    flex-shrink: 0;
}

.header-left .store-info {
    display: flex;
    flex-direction: column;
}

.store-name {
    font-size: 20px;
    font-weight: 700;
    color: #1e40af;
    letter-spacing: 0.5px;
}

.store-subtitle {
    font-size: 12px;
    color: #6b7280;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.header-center {
    flex: 1;
    max-width: 400px;
    margin: 0 32px;
}

.search-bar {
    width: 100%;
}

.search-input-group {
    position: relative;
    display: flex;
    align-items: center;
}

.search-icon {
    position: absolute;
    left: 12px;
    color: #9ca3af;
    z-index: 1;
}

.search-input {
    width: 100%;
    padding: 12px 16px 12px 40px;
    border: 2px solid #d1d5db;
    border-radius: 8px;
    font-size: 14px;
    background-color: #ffffff;
    transition: all 0.2s ease;
}

.search-input:focus {
    outline: none;
    border-color: #1e40af;
    box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.1);
}

.header-right .session-info {
    display: flex;
    gap: 24px;
}

.session-detail {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
}

.session-label {
    font-size: 12px;
    color: #6b7280;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.session-value {
    font-size: 14px;
    font-weight: 600;
    color: #1e293b;
}

/* Body Layout */
.pos-body {
    flex: 1;
    display: grid;
    grid-template-columns: 300px 1fr 380px;
    grid-template-rows: 1fr;
    height: calc(100vh - 80px);
    overflow: hidden;
    background-color: #f8fafc;
}

/* Categories Panel */
.categories-panel {
    background-color: #ffffff;
    border-right: 1px solid #e2e8f0;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.panel-title {
    padding: 20px 16px;
    border-bottom: 1px solid #e2e8f0;
    background-color: #f8fafc;
    flex-shrink: 0;
}

.panel-title h3 {
    font-size: 14px;
    font-weight: 700;
    color: #374151;
    letter-spacing: 0.5px;
}

.categories-list {
    flex: 1;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
}

.category-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px;
    text-decoration: none;
    color: #374151;
    border-bottom: 1px solid #f1f5f9;
    transition: all 0.2s ease;
    min-height: 60px;
}

.category-item:hover {
    background-color: #f8fafc;
    color: #1e40af;
}

.category-item.active {
    background-color: #1e40af;
    color: #ffffff;
    border-left: 4px solid #1e3a8a;
}

.category-label {
    font-size: 14px;
    font-weight: 600;
    letter-spacing: 0.5px;
    flex: 1;
    margin-right: 12px;
    line-height: 1.4;
}

.category-count {
    background-color: #e5e7eb;
    color: #4b5563;
    padding: 4px 12px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
    min-width: 32px;
    text-align: center;
}

.category-item.active .category-count {
    background-color: rgba(255, 255, 255, 0.2);
    color: #ffffff;
}

/* Products Panel */
.products-panel {
    background-color: #f8fafc;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.products-header {
    padding: 20px 24px;
    background-color: #ffffff;
    border-bottom: 1px solid #e2e8f0;
    flex-shrink: 0;
}

.products-info {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.products-title {
    font-size: 18px;
    font-weight: 700;
    color: #1e293b;
    letter-spacing: 0.5px;
}

.products-count {
    font-size: 14px;
    color: #6b7280;
    font-weight: 500;
}

/* PERBAIKAN: Grid Container dengan Tinggi Card yang Cukup */
.products-grid {
    flex: 1;
    padding: 20px;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); /* Diperbesar untuk ruang lebih */
    gap: 20px; /* Gap diperbesar */
    overflow-y: auto;
    align-content: start;
}

/* PERBAIKAN: Product Card dengan Tinggi yang Cukup */
.product-card {
    background-color: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.2s ease;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    position: relative;
    height: auto;
    min-height: 340px; /* PENTING: Tinggi minimum diperbesar untuk button */
}

.product-card:hover {
    border-color: #1e40af;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

/* PERBAIKAN: Image Container dengan Proporsi yang Sesuai */
.product-image {
    position: relative;
    height: 180px; /* Diperbesar sedikit */
    overflow: hidden;
    background-color: #f1f5f9;
    flex-shrink: 0;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-card:hover .product-image img {
    transform: scale(1.05);
}

.product-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.2s ease;
}

.product-card:hover .product-overlay {
    opacity: 1;
}

.quick-add-btn {
    background-color: #1e40af;
    color: #ffffff;
    border: none;
    padding: 10px 18px; /* Padding diperbesar */
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.quick-add-btn:hover {
    background-color: #1e3a8a;
    transform: translateY(-1px);
}

/* PERBAIKAN: Product Info dengan Ruang yang Cukup */
.product-info {
    padding: 18px; /* Padding diperbesar */
    display: flex;
    flex-direction: column;
    flex: 1;
    justify-content: space-between;
    min-height: 140px; /* PENTING: Minimum height untuk konten */
}

.product-name {
    font-size: 15px; /* Font diperbesar sedikit */
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 10px; /* Margin diperbesar */
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    min-height: 42px; /* Height minimum untuk 2 baris */
}

.product-price {
    font-size: 17px; /* Font diperbesar */
    font-weight: 700;
    color: #1e40af;
    margin-bottom: 14px; /* Margin diperbesar */
}

/* PERBAIKAN: Button dengan Ukuran yang Lebih Besar dan Jelas */
.add-product-btn {
    width: 100%;
    background-color: #1e40af; /* Warna biru langsung */
    color: #ffffff; /* Text putih */
    border: 1px solid #1e40af;
    padding: 12px 16px; /* Padding diperbesar */
    border-radius: 8px; /* Border radius diperbesar */
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-top: auto; /* Selalu di bawah */
    min-height: 44px; /* PENTING: Minimum height untuk button */
    display: flex;
    align-items: center;
    justify-content: center;
}

.add-product-btn:hover {
    background-color: #1e3a8a;
    border-color: #1e3a8a;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(30, 64, 175, 0.2);
}

.add-product-btn:active {
    transform: translateY(0);
}

.no-products {
    grid-column: 1 / -1;
    text-align: center;
    padding: 48px 32px;
    color: #6b7280;
}

.no-products-icon {
    margin-bottom: 16px;
    opacity: 0.5;
}

.no-products h3 {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 8px;
    color: #4b5563;
}

/* Order Panel */
.order-panel {
    background-color: #ffffff;
    border-left: 1px solid #e2e8f0;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.transaction-info {
    padding: 20px;
    border-bottom: 1px solid #e2e8f0;
    background-color: #f8fafc;
    flex-shrink: 0;
}

.transaction-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.transaction-row:last-child {
    margin-bottom: 0;
}

.transaction-label {
    font-size: 12px;
    color: #6b7280;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.transaction-value {
    font-size: 14px;
    font-weight: 600;
    color: #1e293b;
}

.order-items-container {
    flex: 1;
    padding: 20px;
    overflow-y: auto;
}

.empty-order {
    text-align: center;
    padding: 32px 16px;
    color: #9ca3af;
}

.empty-order-icon {
    margin-bottom: 16px;
    opacity: 0.5;
}

.empty-order p {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 8px;
    color: #6b7280;
}

.empty-order small {
    font-size: 12px;
    color: #9ca3af;
}

.order-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 0;
    border-bottom: 1px solid #f1f5f9;
}

.order-item:last-child {
    border-bottom: none;
}

.order-item-info {
    flex: 1;
}

.order-item-name {
    font-size: 14px;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 4px;
}

.order-item-price {
    font-size: 12px;
    color: #6b7280;
}

.quantity-controls {
    display: flex;
    align-items: center;
    gap: 8px;
}

.quantity-btn {
    width: 32px;
    height: 32px;
    border: 1px solid #d1d5db;
    background-color: #ffffff;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 14px;
    font-weight: 600;
}

.quantity-btn:hover {
    background-color: #1e40af;
    color: #ffffff;
    border-color: #1e40af;
}

.quantity-display {
    font-weight: 600;
    min-width: 24px;
    text-align: center;
    font-size: 14px;
}

.order-item-total {
    font-weight: 600;
    color: #1e40af;
    font-size: 14px;
    min-width: 80px;
    text-align: right;
}

.remove-item-btn {
    background: none;
    border: none;
    color: #dc2626;
    cursor: pointer;
    padding: 8px;
    border-radius: 6px;
    transition: all 0.2s ease;
}

.remove-item-btn:hover {
    background-color: #dc2626;
    color: #ffffff;
}

.order-calculation {
    padding: 20px;
    border-top: 1px solid #e2e8f0;
    background-color: #f8fafc;
    flex-shrink: 0;
}

.calculation-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 12px;
    font-size: 14px;
    color: #4b5563;
}

.calculation-row:last-child {
    margin-bottom: 0;
}

.calculation-divider {
    height: 1px;
    background-color: #d1d5db;
    margin: 16px 0;
}

.calculation-total {
    display: flex;
    justify-content: space-between;
    font-size: 18px;
    font-weight: 700;
    color: #1e293b;
    padding-top: 12px;
    border-top: 2px solid #d1d5db;
}

.order-actions {
    padding: 20px;
    display: flex;
    gap: 12px;
    flex-shrink: 0;
}

.action-btn {
    flex: 1;
    padding: 16px 12px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 12px;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.action-btn.secondary {
    background-color: #f1f5f9;
    color: #374151;
    border: 1px solid #d1d5db;
}

.action-btn.secondary:hover {
    background-color: #dc2626;
    color: #ffffff;
    border-color: #dc2626;
}

.action-btn.primary {
    background-color: #059669;
    color: #ffffff;
}

.action-btn.primary:hover {
    background-color: #047857;
    transform: translateY(-1px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.action-btn:disabled {
    background-color: #d1d5db;
    color: #9ca3af;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

/* Modal Styles */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.modal-overlay.active {
    opacity: 1;
    visibility: visible;
}

.modal-content {
    background: white;
    border-radius: 16px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    max-width: 400px;
    width: 90%;
    margin: 16px;
    transform: scale(0.9) translateY(20px);
    transition: all 0.3s ease;
    border: 1px solid #e2e8f0;
}

.modal-overlay.active .modal-content {
    transform: scale(1) translateY(0);
}

.modal-header {
    padding: 24px 24px 16px 24px;
    text-align: center;
}

.modal-icon {
    width: 64px;
    height: 64px;
    background: #fef2f2;
    border: 2px solid #fecaca;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px auto;
    color: #dc2626;
}

.modal-title {
    font-size: 20px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 8px;
}

.modal-message {
    font-size: 14px;
    color: #4b5563;
    line-height: 1.5;
}

.modal-body {
    padding: 0 24px 24px 24px;
}

.modal-actions {
    display: flex;
    gap: 12px;
    justify-content: center;
}

.modal-btn {
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    min-width: 100px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.modal-btn-cancel {
    background: #f1f5f9;
    color: #374151;
    border: 1px solid #d1d5db;
}

.modal-btn-cancel:hover {
    background: #e5e7eb;
    color: #1f2937;
}

.modal-btn-confirm {
    background: #dc2626;
    color: white;
}

.modal-btn-confirm:hover {
    background: #b91c1c;
}

.modal-btn-confirm:active,
.modal-btn-cancel:active {
    transform: translateY(1px);
}

.modal-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

/* Notification */
.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    background-color: #ffffff;
    padding: 16px 20px;
    border-radius: 8px;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    gap: 12px;
    transform: translateX(100%);
    transition: transform 0.3s ease;
    z-index: 1000;
    min-width: 320px;
    border-left: 4px solid #1e40af;
}

.notification.show {
    transform: translateX(0);
}

.notification.success {
    border-left-color: #059669;
}

.notification.error {
    border-left-color: #dc2626;
}

.notification.info {
    border-left-color: #1e40af;
}

/* Responsive Design */
@media (max-width: 1400px) {
    .pos-body {
        grid-template-columns: 280px 1fr 360px;
    }
    
    .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    }
}

@media (max-width: 1200px) {
    .pos-body {
        grid-template-columns: 260px 1fr 340px;
    }
    
    .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    }
    
    .product-card {
        min-height: 320px; /* Sedikit dikurangi untuk layar kecil */
    }
}

@media (max-width: 992px) {
    .pos-header {
        flex-direction: column;
        gap: 16px;
        padding: 16px;
    }
    
    .header-center {
        order: -1;
        max-width: 100%;
        margin: 0;
    }
    
    .pos-body {
        grid-template-columns: 1fr;
        grid-template-rows: auto 1fr auto;
    }
    
    .categories-panel {
        border-right: none;
        border-bottom: 1px solid #e2e8f0;
        max-height: 200px;
    }
    
    .categories-list {
        max-height: 150px;
    }
    
    .category-item {
        min-height: 50px;
        padding: 12px 16px;
    }
    
    .order-panel {
        border-left: none;
        border-top: 1px solid #e2e8f0;
        max-height: 400px;
    }
    
    .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    }
    
    .product-card {
        min-height: 300px;
    }
}

@media (max-width: 768px) {
    .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 16px;
        padding: 16px;
    }
    
    .product-card {
        min-height: 280px;
    }
    
    .product-info {
        padding: 14px;
        min-height: 120px;
    }
    
    .session-info {
        flex-direction: column;
        gap: 8px;
    }
    
    .category-item {
        min-height: 45px;
        padding: 8px 12px;
    }
    
    .category-label {
        font-size: 12px;
    }
    
    .modal-content {
        margin: 8px;
    }

    .modal-header {
        padding: 20px 20px 12px 20px;
    }

    .modal-body {
        padding: 0 20px 20px 20px;
    }

    .modal-icon {
        width: 56px;
        height: 56px;
    }

    .modal-title {
        font-size: 18px;
    }

    .modal-actions {
        flex-direction: column;
    }

    .modal-btn {
        width: 100%;
    }
}

@media (max-width: 480px) {
    .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        padding: 12px;
    }
    
    .product-card {
        min-height: 260px;
    }
    
    .product-info {
        padding: 12px;
        min-height: 100px;
    }
    
    .add-product-btn {
        padding: 10px 12px;
        font-size: 11px;
        min-height: 40px;
    }
    
    .pos-header {
        padding: 12px;
    }
    
    .store-name {
        font-size: 18px;
    }
    
    .category-item {
        min-height: 40px;
        padding: 8px;
    }
    
    .categories-list {
        max-height: 120px;
    }
}

/* Scrollbar Styling */
::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}

::-webkit-scrollbar-track {
    background-color: #f1f5f9;
}

::-webkit-scrollbar-thumb {
    background-color: #9ca3af;
    border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
    background-color: #6b7280;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let orderItems = [];
    let orderTotal = 0;

    // Event Listeners
    document.addEventListener('click', function(e) {
        if (e.target.closest('.add-product-btn') || e.target.closest('.quick-add-btn')) {
            const btn = e.target.closest('.add-product-btn') || e.target.closest('.quick-add-btn');
            const productId = parseInt(btn.dataset.foodId);
            const productName = btn.dataset.foodName;
            const productPrice = parseInt(btn.dataset.foodPrice);
            addToOrder(productId, productName, productPrice);
        }
    });

    document.getElementById('clear-order-btn').addEventListener('click', clearOrder);
    document.getElementById('process-payment-btn').addEventListener('click', processPayment);

    function addToOrder(id, name, price) {
        const existingItem = orderItems.find(item => item.id === id);
        
        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            orderItems.push({
                id: id,
                name: name,
                price: price,
                quantity: 1
            });
        }
        
        updateOrderDisplay();
        showNotification(`${name} ditambahkan ke transaksi`, 'success');
    }

    function removeFromOrder(id) {
        orderItems = orderItems.filter(item => item.id !== id);
        updateOrderDisplay();
        showNotification('Item dihapus dari transaksi', 'info');
    }

    function updateQuantity(id, quantity) {
        const item = orderItems.find(item => item.id === id);
        if (item) {
            item.quantity = Math.max(0, quantity);
            if (item.quantity === 0) {
                removeFromOrder(id);
                return;
            }
        }
        updateOrderDisplay();
    }

    function updateOrderDisplay() {
        const orderContainer = document.getElementById('order-items');
        const subtotalEl = document.getElementById('subtotal');
        const taxEl = document.getElementById('tax');
        const discountEl = document.getElementById('discount');
        const totalEl = document.getElementById('total');
        const paymentBtn = document.getElementById('process-payment-btn');
        
        if (orderItems.length === 0) {
            orderContainer.innerHTML = `
                <div class="empty-order">
                    <div class="empty-order-icon">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                            <circle cx="9" cy="21" r="1"></circle>
                            <circle cx="20" cy="21" r="1"></circle>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                        </svg>
                    </div>
                    <p>BELUM ADA ITEM</p>
                    <small>Pilih produk untuk memulai transaksi</small>
                </div>
            `;
            subtotalEl.textContent = 'Rp 0';
            taxEl.textContent = 'Rp 0';
            discountEl.textContent = 'Rp 0';
            totalEl.textContent = 'Rp 0';
            paymentBtn.disabled = true;
            return;
        }
        
        let subtotal = 0;
        let itemsHtml = '';
        
        orderItems.forEach(item => {
            const itemTotal = item.price * item.quantity;
            subtotal += itemTotal;
            
            itemsHtml += `
                <div class="order-item">
                    <div class="order-item-info">
                        <div class="order-item-name">${item.name}</div>
                        <div class="order-item-price">Rp ${formatNumber(item.price)}</div>
                    </div>
                    <div class="quantity-controls">
                        <button class="quantity-btn" onclick="updateQuantity(${item.id}, ${item.quantity - 1})">−</button>
                        <span class="quantity-display">${item.quantity}</span>
                        <button class="quantity-btn" onclick="updateQuantity(${item.id}, ${item.quantity + 1})">+</button>
                    </div>
                    <div class="order-item-total">Rp ${formatNumber(itemTotal)}</div>
                    <button class="remove-item-btn" onclick="removeFromOrder(${item.id})">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3,6 5,6 21,6"></polyline>
                            <path d="m19,6v14a2,2 0 0,1 -2,2H7a2,2 0 0,1 -2,-2V6m3,0V4a2,2 0 0,1 2,-2h4a2,2 0 0,1 2,2v2"></path>
                        </svg>
                    </button>
                </div>
            `;
        });
        
        const tax = subtotal * 0.1;
        const discount = 0;
        const total = subtotal + tax - discount;
        
        orderContainer.innerHTML = itemsHtml;
        subtotalEl.textContent = 'Rp ' + formatNumber(subtotal);
        taxEl.textContent = 'Rp ' + formatNumber(tax);
        discountEl.textContent = 'Rp ' + formatNumber(discount);
        totalEl.textContent = 'Rp ' + formatNumber(total);
        paymentBtn.disabled = false;
    }

    function clearOrder() {
        if (orderItems.length === 0) return;
        
        // Show modal instead of confirm dialog
        const clearModal = document.getElementById('clearOrderModal');
        clearModal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    // Clear Order Modal functionality
    const clearOrderBtn = document.getElementById('clear-order-btn');
    const clearOrderModal = document.getElementById('clearOrderModal');
    const cancelClear = document.getElementById('cancelClear');
    const confirmClear = document.getElementById('confirmClear');

    // Hide modal when cancel button is clicked
    cancelClear.addEventListener('click', function() {
        hideClearModal();
    });

    // Hide modal when clicking outside
    clearOrderModal.addEventListener('click', function(e) {
        if (e.target === clearOrderModal) {
            hideClearModal();
        }
    });

    // Hide modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && clearOrderModal.classList.contains('active')) {
            hideClearModal();
        }
    });

    // Confirm clear order
    confirmClear.addEventListener('click', function() {
        // Add loading state
        confirmClear.disabled = true;
        confirmClear.innerHTML = `
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="animation: spin 1s linear infinite;">
                <path d="M21 12a9 9 0 11-6.219-8.56"/>
            </svg>
            Menghapus...
        `;
        
        cancelClear.disabled = true;
        
        // Clear order after short delay
        setTimeout(() => {
            orderItems = [];
            updateOrderDisplay();
            showNotification('Transaksi dibersihkan', 'info');
            hideClearModal();
        }, 500);
    });

    function hideClearModal() {
        clearOrderModal.classList.remove('active');
        document.body.style.overflow = '';
        
        // Reset button states
        confirmClear.disabled = false;
        confirmClear.innerHTML = `
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="20,6 9,17 4,12"></polyline>
            </svg>
            Ya, Bersihkan
        `;
        cancelClear.disabled = false;
    }

    function processPayment() {
        if (orderItems.length === 0) {
            showNotification('Belum ada item untuk diproses!', 'error');
            return;
        }
        
        showNotification('Memproses pembayaran...', 'info');
        
        setTimeout(() => {
            showNotification('Pembayaran berhasil diproses!', 'success');
            orderItems = [];
            updateOrderDisplay();
        }, 2000);
    }

    function formatNumber(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }

    function showNotification(message, type = 'info') {
        const existingNotifications = document.querySelectorAll('.notification');
        existingNotifications.forEach(notif => notif.remove());
        
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.innerHTML = `<span>${message}</span>`;
        
        document.body.appendChild(notification);
        
        setTimeout(() => notification.classList.add('show'), 100);
        
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => {
                if (document.body.contains(notification)) {
                    document.body.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }

    // Make functions global for onclick handlers
    window.updateQuantity = updateQuantity;
    window.removeFromOrder = removeFromOrder;

    // Update time every minute
    function updateTime() {
        const timeEl = document.getElementById('current-time');
        if (timeEl) {
            const now = new Date();
            timeEl.textContent = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit'
            });
        }
    }
    
    setInterval(updateTime, 60000);
});
</script>
@endsection