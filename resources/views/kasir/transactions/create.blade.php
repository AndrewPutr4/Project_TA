@extends('layouts.kasir')
@section('title', 'Pembayaran Order #' . $order->order_number)
@section('content')

<div class="payment-container">
    {{-- Header Section --}}
    <div class="page-header">
        <div class="header-content">
            <div class="header-left">
                <a href="{{ route('kasir.orders.index') }}" class="back-btn">
                    <i class="fas fa-arrow-left"></i>
                    Kembali
                </a>
                <div class="header-text">
                    <h1 class="page-title">Pembayaran Order #{{ $order->order_number }}</h1>
                    <p class="page-subtitle">Pilih metode pembayaran untuk menyelesaikan transaksi</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="payment-grid">
        {{-- Left Column: Order Summary --}}
        <div class="order-summary-card">
            <div class="card-header">
                <h2><i class="fas fa-receipt"></i> Ringkasan Order</h2>
            </div>
            <div class="card-content">
                {{-- Customer Info --}}
                <div class="customer-info">
                    <div class="customer-details">
                        <h3><i class="fas fa-user"></i> {{ $order->customer_name }}</h3>
                        @if($order->table_number)
                            <p><i class="fas fa-chair"></i> Dine In - Meja {{ $order->table_number }}</p>
                        @else
                            <p><i class="fas fa-shopping-bag"></i> Takeaway</p>
                        @endif
                        <p class="order-time"><i class="fas fa-clock"></i> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                {{-- Items Summary --}}
                <div class="items-summary">
                    <h4><i class="fas fa-list"></i> Item Pesanan ({{ $order->items->count() }} item)</h4>
                    <div class="items-list">
                        @forelse($order->items as $item)
                            <div class="item-row">
                                <div class="item-info">
                                    <span class="item-name">{{ $item->menu_name }}</span>
                                    <span class="item-price">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                </div>
                                <div class="item-quantity">
                                    <span class="qty-badge">{{ $item->quantity }}x</span>
                                </div>
                                <div class="item-total">
                                    <span class="total-price">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="empty-state">
                                <i class="fas fa-inbox"></i>
                                <p>Tidak ada item dalam pesanan ini.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Summary Totals --}}
                <div class="summary-totals">
                    <div class="summary-row">
                        <span><i class="fas fa-calculator"></i> Subtotal</span>
                        <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @if($order->service_fee > 0)
                        <div class="summary-row">
                            <span><i class="fas fa-concierge-bell"></i> Biaya Layanan</span>
                            <span>Rp {{ number_format($order->service_fee, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="summary-row total-row">
                        <span><i class="fas fa-money-check-alt"></i> Total Pembayaran</span>
                        <span id="finalTotal" data-total="{{ $order->total }}">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Payment Form --}}
        <div class="payment-form-card">
            <div class="card-header">
                <h2><i class="fas fa-credit-card"></i> Metode Pembayaran</h2>
            </div>
            <div class="card-content">
                <form id="paymentForm" method="POST" action="{{ route('kasir.transactions.store', $order) }}">
                    @csrf
                    
                    {{-- Payment Method Selection --}}
                    <div class="form-group">
                        <label class="form-label">Pilih Metode Pembayaran</label>
                        <div class="payment-methods">
                            <label class="payment-method active">
                                <input type="radio" name="payment_method" value="cash" checked>
                                <div class="method-card">
                                    <div class="method-icon cash">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </div>
                                    <div class="method-info">
                                        <span class="method-name">Tunai</span>
                                        <span class="method-desc">Pembayaran dengan uang cash</span>
                                    </div>
                                    <div class="method-check">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                </div>
                            </label>
                            
                            <label class="payment-method">
                                <input type="radio" name="payment_method" value="cashless">
                                <div class="method-card">
                                    <div class="method-icon cashless">
                                        <i class="fas fa-qrcode"></i>
                                    </div>
                                    <div class="method-info">
                                        <span class="method-name">Non-Tunai</span>
                                        <span class="method-desc">QRIS, Kartu, E-Wallet</span>
                                    </div>
                                    <div class="method-check">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Cash Payment Section --}}
                    <div id="cashPaymentSection" class="payment-section">
                        <div class="form-group">
                            <label for="cash_received" class="form-label">
                                <i class="fas fa-hand-holding-usd"></i> Uang Diterima
                            </label>
                            <div class="input-wrapper">
                                <span class="input-prefix">Rp</span>
                                <input type="number" name="cash_received" id="cash_received" class="form-input" placeholder="0" step="1" min="0">
                            </div>
                            @error('cash_received')
                                <span class="error-message">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    {{ $message }}
                                </span>
                            @enderror
                            
                            {{-- Quick Amount Buttons --}}
                            <div class="quick-amounts">
                                <label class="quick-label">Nominal Cepat:</label>
                                <div class="quick-buttons">
                                    <button type="button" class="quick-btn" data-amount="50000">
                                        <span>50K</span>
                                    </button>
                                    <button type="button" class="quick-btn" data-amount="100000">
                                        <span>100K</span>
                                    </button>
                                    <button type="button" class="quick-btn" data-amount="200000">
                                        <span>200K</span>
                                    </button>
                                    <button type="button" class="quick-btn" data-amount="500000">
                                        <span>500K</span>
                                    </button>
                                    <button type="button" class="quick-btn exact-amount" data-amount="{{ $order->total }}">
                                        <span>Uang Pas</span>
                                    </button>
                                </div>
                            </div>
                            
                            {{-- Change Display --}}
                            <div class="change-display" id="changeDisplay" style="display: none;">
                                <div class="change-info">
                                    <i class="fas fa-coins"></i>
                                    <span class="change-label">Kembalian:</span>
                                    <span class="change-amount" id="changeAmount">Rp 0</span>
                                </div>
                            </div>
                        </div>

                        {{-- Notes --}}
                        <div class="form-group">
                            <label for="notes" class="form-label">
                                <i class="fas fa-sticky-note"></i> Catatan Transaksi (Opsional)
                            </label>
                            <textarea name="notes" id="notes" class="form-textarea" rows="3" 
                                      placeholder="Tambahkan catatan untuk transaksi ini..."></textarea>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="form-actions">
                        <button type="submit" class="btn-submit" id="submitBtn">
                            <i class="fas fa-check-circle"></i>
                            <span>Proses Pembayaran</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Enhanced Styles --}}
<style>
:root {
    --primary-color: #f59e0b;
    --primary-dark: #d97706;
    --primary-light: #fbbf24;
    --secondary-color: #3b82f6;
    --success-color: #10b981;
    --danger-color: #ef4444;
    --warning-color: #f59e0b;
    --gray-50: #f9fafb;
    --gray-100: #f3f4f6;
    --gray-200: #e5e7eb;
    --gray-300: #d1d5db;
    --gray-400: #9ca3af;
    --gray-500: #6b7280;
    --gray-600: #4b5563;
    --gray-700: #374151;
    --gray-800: #1f2937;
    --gray-900: #111827;
    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

* {
    box-sizing: border-box;
}

.payment-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 1.5rem;
    min-height: 100vh;
    background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
}

/* Header Styles */
.page-header {
    margin-bottom: 2rem;
}

.header-content {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    color: white;
    padding: 2rem;
    border-radius: 20px;
    box-shadow: var(--shadow-xl);
    position: relative;
    overflow: hidden;
}

.header-content::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 200px;
    height: 200px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    transform: translate(50%, -50%);
}

.header-left {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    position: relative;
    z-index: 1;
}

.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.25rem;
    background: rgba(255, 255, 255, 0.2);
    color: white;
    text-decoration: none;
    border-radius: 12px;
    font-size: 0.875rem;
    font-weight: 600;
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.3);
    backdrop-filter: blur(10px);
}

.back-btn:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    color: white;
    text-decoration: none;
}

.page-title {
    font-size: 2rem;
    font-weight: 800;
    margin: 0 0 0.5rem 0;
    letter-spacing: -0.025em;
}

.page-subtitle {
    font-size: 1rem;
    opacity: 0.9;
    margin: 0;
    font-weight: 400;
}

/* Grid Layout */
.payment-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    align-items: start;
}

/* Card Styles */
.order-summary-card, 
.payment-form-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: var(--shadow-lg);
    transition: all 0.3s ease;
    border: 1px solid var(--gray-200);
}

.order-summary-card:hover, 
.payment-form-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-xl);
}

.card-header {
    background: linear-gradient(135deg, #fff 0%, var(--gray-50) 100%);
    border-bottom: 2px solid var(--gray-100);
    padding: 1.5rem 2rem;
}

.card-header h2 {
    color: var(--gray-800);
    font-size: 1.25rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin: 0;
}

.card-content {
    padding: 2rem;
}

/* Customer Info */
.customer-info {
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
    border-radius: 16px;
    border: 2px solid #fde68a;
    position: relative;
}

.customer-details h3 {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--primary-dark);
    margin: 0 0 0.75rem 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.customer-details p {
    font-size: 0.875rem;
    color: #a16207;
    margin: 0.5rem 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.order-time {
    font-weight: 500;
}

/* Items Summary */
.items-summary {
    margin-bottom: 2rem;
}

.items-summary h4 {
    font-size: 1rem;
    font-weight: 600;
    color: var(--gray-700);
    margin: 0 0 1rem 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.items-list {
    max-height: 300px;
    overflow-y: auto;
    padding-right: 0.5rem;
}

.items-list::-webkit-scrollbar {
    width: 6px;
}

.items-list::-webkit-scrollbar-track {
    background: var(--gray-100);
    border-radius: 3px;
}

.items-list::-webkit-scrollbar-thumb {
    background: var(--gray-300);
    border-radius: 3px;
}

.items-list::-webkit-scrollbar-thumb:hover {
    background: var(--gray-400);
}

.item-row {
    display: grid;
    grid-template-columns: 1fr auto auto;
    gap: 1rem;
    align-items: center;
    padding: 1rem 0;
    border-bottom: 1px solid var(--gray-200);
    font-size: 0.875rem;
}

.item-row:last-child {
    border-bottom: none;
}

.item-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.item-name {
    font-weight: 600;
    color: var(--gray-800);
}

.item-price {
    font-size: 0.75rem;
    color: var(--gray-500);
}

.qty-badge {
    background: var(--primary-color);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
}

.total-price {
    font-weight: 700;
    color: var(--gray-800);
}

.empty-state {
    text-align: center;
    padding: 2rem;
    color: var(--gray-500);
}

.empty-state i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    display: block;
}

/* Summary Totals */
.summary-totals {
    padding-top: 1.5rem;
    border-top: 2px solid var(--gray-200);
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.75rem;
    font-size: 0.875rem;
}

.summary-row span:first-child {
    color: var(--gray-600);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.summary-row span:last-child {
    font-weight: 600;
    color: var(--gray-800);
}

.total-row {
    font-size: 1.125rem;
    font-weight: 700;
    color: var(--primary-dark);
    margin-top: 1rem;
    padding: 1.5rem;
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    border-radius: 12px;
    margin-left: -1rem;
    margin-right: -1rem;
    margin-bottom: -1rem;
}

/* Form Styles */
.form-group {
    margin-bottom: 2rem;
}

.form-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Payment Methods */
.payment-methods {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.payment-method {
    cursor: pointer;
    position: relative;
}

.payment-method input[type="radio"] {
    display: none;
}

.method-card {
    background: white;
    border: 2px solid var(--gray-200);
    border-radius: 16px;
    padding: 1.5rem;
    transition: all 0.3s ease;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 1rem;
    position: relative;
    min-height: 80px;
}

.method-card:hover {
    border-color: var(--primary-color);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.payment-method.active .method-card,
.payment-method input[type="radio"]:checked + .method-card {
    border-color: var(--primary-color);
    background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
    box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1);
}

.method-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.method-icon.cash {
    background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
    color: white;
}

.method-icon.cashless {
    background: linear-gradient(135deg, var(--secondary-color) 0%, #1d4ed8 100%);
    color: white;
}

.method-info {
    flex: 1;
}

.method-name {
    display: block;
    font-weight: 600;
    color: var(--gray-800);
    margin-bottom: 0.25rem;
}

.method-desc {
    display: block;
    font-size: 0.75rem;
    color: var(--gray-500);
}

.method-check {
    color: var(--primary-color);
    font-size: 1.25rem;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.payment-method.active .method-check,
.payment-method input[type="radio"]:checked + .method-card .method-check {
    opacity: 1;
}

/* Input Styles */
.input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.input-prefix {
    position: absolute;
    left: 1rem;
    color: var(--gray-500);
    font-weight: 600;
    z-index: 1;
}

.form-input, 
.form-textarea {
    width: 100%;
    padding: 1rem 1rem 1rem 3rem;
    border: 2px solid var(--gray-200);
    border-radius: 12px;
    font-size: 0.875rem;
    transition: all 0.3s ease;
    background: white;
    color: var(--gray-800);
    font-family: inherit;
}

.form-input:focus, 
.form-textarea:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1);
    transform: translateY(-1px);
}

.form-textarea {
    padding-left: 1rem;
    resize: vertical;
    min-height: 80px;
}

/* Quick Amounts */
.quick-amounts {
    margin-top: 1rem;
}

.quick-label {
    font-size: 0.8125rem;
    color: var(--gray-600);
    margin-bottom: 0.75rem;
    display: block;
    font-weight: 500;
}

.quick-buttons {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.quick-btn {
    padding: 0.75rem 1rem;
    background: white;
    border: 2px solid var(--gray-200);
    border-radius: 10px;
    font-size: 0.8125rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    color: var(--gray-700);
    position: relative;
    overflow: hidden;
}

.quick-btn:hover {
    border-color: var(--primary-color);
    background: var(--gray-50);
    transform: translateY(-1px);
    box-shadow: var(--shadow-sm);
}

.quick-btn:active {
    transform: translateY(0);
}

.exact-amount {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    color: white;
    border-color: var(--primary-color);
}

.exact-amount:hover {
    background: linear-gradient(135deg, var(--primary-dark) 0%, #b45309 100%);
    border-color: var(--primary-dark);
}

/* Change Display */
.change-display {
    margin-top: 1rem;
    padding: 1rem;
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    border: 2px solid #86efac;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.change-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: #065f46;
    font-weight: 600;
}

.change-display.insufficient {
    background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
    border-color: #fecaca;
}

.change-display.insufficient .change-info {
    color: var(--danger-color);
}

.change-amount {
    margin-left: auto;
    font-size: 1.125rem;
    font-weight: 700;
}

/* Error Message */
.error-message {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 0.5rem;
    font-size: 0.8125rem;
    color: var(--danger-color);
    font-weight: 500;
}

/* Submit Button */
.form-actions {
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 2px solid var(--gray-100);
}

.btn-submit {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    padding: 1.25rem 2rem;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    color: white;
    border: none;
    border-radius: 16px;
    font-size: 1rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.btn-submit:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 35px rgba(245, 158, 11, 0.4);
}

.btn-submit:active {
    transform: translateY(-1px);
}

.btn-submit:disabled {
    background: var(--gray-400);
    color: var(--gray-200);
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.btn-submit::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
}

.btn-submit:hover::before {
    left: 100%;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .payment-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .payment-methods {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .payment-container {
        padding: 1rem;
    }
    
    .header-left {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .page-title {
        font-size: 1.5rem;
    }
    
    .card-content {
        padding: 1.5rem;
    }
    
    .quick-buttons {
        justify-content: center;
    }
    
    .quick-btn {
        flex: 1;
        min-width: 60px;
    }
}

@media (max-width: 480px) {
    .payment-container {
        padding: 0.75rem;
    }
    
    .header-content {
        padding: 1.5rem;
    }
    
    .card-content {
        padding: 1rem;
    }
    
    .method-card {
        padding: 1rem;
        flex-direction: column;
        text-align: center;
        gap: 0.75rem;
    }
    
    .method-info {
        text-align: center;
    }
}

/* Animation Classes */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.payment-section {
    animation: fadeIn 0.3s ease-out;
}

/* Loading State */
.btn-submit.loading {
    pointer-events: none;
}

.btn-submit.loading i {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}
</style>

{{-- Midtrans Script --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentForm = document.getElementById('paymentForm');
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    const paymentMethodLabels = document.querySelectorAll('.payment-method');
    const cashReceivedInput = document.getElementById('cash_received');
    const changeDisplay = document.getElementById('changeDisplay');
    const changeAmount = document.getElementById('changeAmount');
    const submitBtn = document.getElementById('submitBtn');
    const quickBtns = document.querySelectorAll('.quick-btn');
    const finalTotalEl = document.getElementById('finalTotal');
    const originalTotal = parseFloat(finalTotalEl.dataset.total) || 0;

    // Toggle payment method selection
    function togglePaymentMethod() {
        const selectedMethod = document.querySelector('input[name="payment_method"]:checked').value;
        
        // Update active states
        paymentMethodLabels.forEach(label => {
            label.classList.remove('active');
        });
        
        document.querySelector(`input[name="payment_method"][value="${selectedMethod}"]`).closest('.payment-method').classList.add('active');
        
        // Show/hide cash input
        const cashSection = document.getElementById('cashPaymentSection');
        if (selectedMethod === 'cash') {
            cashSection.style.display = 'block';
            cashReceivedInput.required = true;
        } else {
            cashSection.style.display = 'none';
            cashReceivedInput.required = false;
            changeDisplay.style.display = 'none';
        }
    }

    paymentMethods.forEach(method => {
        method.addEventListener('change', togglePaymentMethod);
    });

    // Calculate change
    function calculateChange() {
        const selectedMethod = document.querySelector('input[name="payment_method"]:checked').value;
        if (selectedMethod !== 'cash') return;

        const cashReceived = parseFloat(cashReceivedInput.value) || 0;
        
        if (cashReceived > 0) {
            const change = cashReceived - originalTotal;
            changeDisplay.style.display = 'block';
            
            if (change >= 0) {
                changeAmount.textContent = 'Rp ' + change.toLocaleString('id-ID');
                changeDisplay.className = 'change-display';
            } else {
                changeAmount.textContent = 'Kurang Rp ' + Math.abs(change).toLocaleString('id-ID');
                changeDisplay.className = 'change-display insufficient';
            }
        } else {
            changeDisplay.style.display = 'none';
        }
    }

    cashReceivedInput.addEventListener('input', calculateChange);

    // Quick amount buttons
    quickBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            cashReceivedInput.value = this.dataset.amount;
            calculateChange();
            
            // Add visual feedback
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
    });

    // Form submission
    paymentForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const selectedMethod = document.querySelector('input[name="payment_method"]:checked').value;
        
        // Disable submit button
        submitBtn.disabled = true;
        submitBtn.classList.add('loading');
        submitBtn.innerHTML = '<i class="fas fa-spinner"></i><span>Memproses...</span>';

        if (selectedMethod === 'cash') {
            const cashReceived = parseFloat(cashReceivedInput.value) || 0;
            
            if (cashReceived < originalTotal) {
                alert('Jumlah uang yang diterima kurang dari total pembayaran!\nDiterima: Rp ' + cashReceived.toLocaleString('id-ID') + '\nTotal: Rp ' + originalTotal.toLocaleString('id-ID'));
                
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.classList.remove('loading');
                submitBtn.innerHTML = '<i class="fas fa-check-circle"></i><span>Proses Pembayaran</span>';
                return;
            }
            
            // Submit form for cash payment
            this.submit();
            
        } else if (selectedMethod === 'cashless') {
            // Handle Midtrans payment
            fetch('{{ route("kasir.transactions.createMidtransSnapToken", $order) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.error || !data.snap_token) {
                    alert('Error: ' + (data.error || 'Gagal mendapatkan token pembayaran.'));
                    
                    // Re-enable submit button
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('loading');
                    submitBtn.innerHTML = '<i class="fas fa-check-circle"></i><span>Proses Pembayaran</span>';
                    return;
                }

                snap.pay(data.snap_token, {
                    onSuccess: function(result) {
                        // Create hidden input for Midtrans payload
                        let hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = 'midtrans_payload';
                        hiddenInput.value = JSON.stringify(result);
                        paymentForm.appendChild(hiddenInput);
                        
                        // Submit form using native method to avoid event listener loop
                        HTMLFormElement.prototype.submit.call(paymentForm);
                    },
                    onPending: function(result) {
                        alert("Menunggu pembayaran Anda!");
                        
                        // Re-enable submit button
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('loading');
                        submitBtn.innerHTML = '<i class="fas fa-check-circle"></i><span>Proses Pembayaran</span>';
                    },
                    onError: function(result) {
                        alert("Pembayaran gagal!");
                        
                        // Re-enable submit button
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('loading');
                        submitBtn.innerHTML = '<i class="fas fa-check-circle"></i><span>Proses Pembayaran</span>';
                    },
                    onClose: function() {
                        alert('Anda menutup popup tanpa menyelesaikan pembayaran.');
                        
                        // Re-enable submit button
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('loading');
                        submitBtn.innerHTML = '<i class="fas fa-check-circle"></i><span>Proses Pembayaran</span>';
                    }
                });
            })
            .catch(error => {
                console.error('Fetch error:', error);
                alert('Tidak dapat terhubung ke server. Periksa koneksi Anda.');
                
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.classList.remove('loading');
                submitBtn.innerHTML = '<i class="fas fa-check-circle"></i><span>Proses Pembayaran</span>';
            });
        }
    });

    // Initialize
    togglePaymentMethod();
});
</script>

@endsection
