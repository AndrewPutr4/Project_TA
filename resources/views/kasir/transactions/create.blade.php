@extends('layouts.kasir')
@section('title', 'Pembayaran Order #' . $order->order_number)
@section('content')
<div class="payment-container">
    <div class="page-header">
        <div class="header-content">
            <div class="header-left">
                <a href="{{ route('kasir.orders.show', $order) }}" class="back-btn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="15,18 9,12 15,6"></polyline>
                    </svg>
                    Kembali
                </a>
                <h1 class="page-title">Pembayaran Order #{{ $order->order_number }}</h1>
            </div>
        </div>
    </div>

    <div class="payment-grid">
        <!-- Order Summary -->
        <div class="order-summary-card">
            <div class="card-header">
                <h2>Ringkasan Order</h2>
            </div>
            <div class="card-content">
                <div class="customer-info">
                    <h3>{{ $order->customer_name }}</h3>
                    <p>{{ $order->customer_phone }}</p>
                    @if($order->table_number)
                    <p>Meja {{ $order->table_number }}</p>
                    @endif
                </div>
                
                <div class="items-summary">
                    @foreach($order->orderItems as $item)
                    <div class="item-row">
                        <span class="item-name">{{ $item->menu_name }}</span>
                        <span class="item-qty">x{{ $item->quantity }}</span>
                        <span class="item-total">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>
                
                <div class="summary-totals">
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    @if($serviceFee > 0)
                    <div class="summary-row">
                        <span>Biaya Layanan</span>
                        <span>Rp {{ number_format($serviceFee, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <div class="summary-row">
                        <span>Pajak (10%)</span>
                        <span>Rp {{ number_format($tax, 0, ',', '.') }}</span>
                    </div>
                    <div class="summary-row total">
                        <span>Total</span>
                        <span id="finalTotal">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Form -->
        <div class="payment-form-card">
            <div class="card-header">
                <h2>Form Pembayaran</h2>
            </div>
            <div class="card-content">
                <form method="POST" action="{{ route('kasir.transactions.store', $order) }}" id="paymentForm">
                    @csrf
                    
                    <!-- Payment Method -->
                    <div class="form-group">
                        <label>Metode Pembayaran</label>
                        <div class="payment-methods">
                            <label class="payment-method">
                                <input type="radio" name="payment_method" value="cash" checked>
                                <div class="method-card">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="12" y1="1" x2="12" y2="23"></line>
                                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                    </svg>
                                    <span>Tunai</span>
                                </div>
                            </label>
                            <label class="payment-method">
                                <input type="radio" name="payment_method" value="card">
                                <div class="method-card">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                        <line x1="1" y1="10" x2="23" y2="10"></line>
                                    </svg>
                                    <span>Kartu</span>
                                </div>
                            </label>
                            <label class="payment-method">
                                <input type="radio" name="payment_method" value="qris">
                                <div class="method-card">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                        <rect x="7" y="7" width="3" height="3"></rect>
                                        <rect x="14" y="7" width="3" height="3"></rect>
                                        <rect x="7" y="14" width="3" height="3"></rect>
                                        <rect x="14" y="14" width="3" height="3"></rect>
                                    </svg>
                                    <span>QRIS</span>
                                </div>
                            </label>
                            <label class="payment-method">
                                <input type="radio" name="payment_method" value="transfer">
                                <div class="method-card">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                        <polyline points="7,10 12,15 17,10"></polyline>
                                        <line x1="12" y1="15" x2="12" y2="3"></line>
                                    </svg>
                                    <span>Transfer</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Cash Received (only for cash payment) -->
                    <div class="form-group" id="cashReceivedGroup">
                        <label for="cash_received">Uang Diterima</label>
                        <input type="number" name="cash_received" id="cash_received" class="form-input"
                               placeholder="Masukkan jumlah uang yang diterima" step="1000" min="0">
                        @error('cash_received')
                        <span class="error-message">{{ $message }}</span>
                        @enderror
                        <div class="change-display" id="changeDisplay" style="display: none;">
                            <span>Kembalian: <strong id="changeAmount">Rp 0</strong></span>
                        </div>
                    </div>

                    <!-- Discount -->
                    <div class="form-group">
                        <label for="discount">Diskon (Opsional)</label>
                        <input type="number" name="discount" id="discount" class="form-input"
                               placeholder="Masukkan jumlah diskon" step="1000" min="0" max="{{ $total }}">
                        @error('discount')
                        <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div class="form-group">
                        <label for="notes">Catatan (Opsional)</label>
                        <textarea name="notes" id="notes" class="form-textarea" rows="3"
                                  placeholder="Catatan tambahan untuk transaksi ini"></textarea>
                        @error('notes')
                        <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="form-actions">
                        <button type="submit" class="btn btn-success btn-large" id="submitBtn">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20,6 9,17 4,12"></polyline>
                            </svg>
                            Proses Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.payment-container {
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

.payment-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
}

.order-summary-card,
.payment-form-card {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
}

.card-header {
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

.card-content {
    padding: 1.5rem;
}

.customer-info {
    margin-bottom: 1.5rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid #f1f5f9;
}

.customer-info h3 {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1e293b;
    margin: 0 0 0.5rem 0;
}

.customer-info p {
    font-size: 0.875rem;
    color: #64748b;
    margin: 0.25rem 0;
}

.items-summary {
    margin-bottom: 1.5rem;
}

.item-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f1f5f9;
    font-size: 0.875rem;
}

.item-row:last-child {
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

.item-total {
    font-weight: 600;
    color: #1e293b;
}

.summary-totals {
    padding-top: 1.5rem;
    border-top: 1px solid #e2e8f0;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    font-size: 0.875rem;
}

.summary-row.total {
    padding-top: 1rem;
    border-top: 1px solid #e2e8f0;
    font-size: 1.125rem;
    font-weight: 700;
    color: #1e293b;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    font-size: 0.875rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
}

.payment-methods {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.payment-method {
    cursor: pointer;
}

.payment-method input[type="radio"] {
    display: none;
}

.method-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    transition: all 0.2s ease;
    background: white;
}

.payment-method input[type="radio"]:checked + .method-card {
    border-color: #3b82f6;
    background: #eff6ff;
    color: #3b82f6;
}

.method-card:hover {
    border-color: #cbd5e1;
}

.method-card span {
    font-size: 0.875rem;
    font-weight: 500;
}

.form-input,
.form-textarea {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 0.875rem;
    transition: border-color 0.2s ease;
}

.form-input:focus,
.form-textarea:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.change-display {
    margin-top: 0.5rem;
    padding: 0.75rem;
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-radius: 6px;
    color: #166534;
    font-size: 0.875rem;
}

.error-message {
    display: block;
    margin-top: 0.25rem;
    font-size: 0.8125rem;
    color: #ef4444;
}

.form-actions {
    margin-top: 2rem;
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

.btn-success {
    background: #10b981;
    color: white;
}

.btn-success:hover {
    background: #059669;
}

.btn-success:disabled {
    background: #9ca3af;
    cursor: not-allowed;
}

.quick-amounts {
    margin-top: 0.5rem;
}

.quick-amounts label {
    font-size: 0.8125rem;
    color: #64748b;
    margin-bottom: 0.5rem;
}

.quick-buttons {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.quick-btn {
    padding: 0.375rem 0.75rem;
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    border-radius: 4px;
    font-size: 0.8125rem;
    cursor: pointer;
    transition: all 0.2s ease;
}

.quick-btn:hover {
    background: #e2e8f0;
    border-color: #cbd5e1;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

@media (max-width: 768px) {
    .payment-grid {
        grid-template-columns: 1fr;
    }
    
    .payment-methods {
        grid-template-columns: 1fr;
    }
    
    .header-content {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get DOM elements
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    const cashReceivedGroup = document.getElementById('cashReceivedGroup');
    const cashReceivedInput = document.getElementById('cash_received');
    const discountInput = document.getElementById('discount');
    const changeDisplay = document.getElementById('changeDisplay');
    const changeAmount = document.getElementById('changeAmount');
    const finalTotal = document.getElementById('finalTotal');
    const submitBtn = document.getElementById('submitBtn');
    
    // Store original total from server-side variable
    const originalTotal = {{ $total }};
    let currentTotal = originalTotal;
    
    // Handle payment method change
    paymentMethods.forEach(function(method) {
        method.addEventListener('change', function() {
            if (this.value === 'cash') {
                cashReceivedGroup.style.display = 'block';
                cashReceivedInput.required = true;
            } else {
                cashReceivedGroup.style.display = 'none';
                cashReceivedInput.required = false;
                changeDisplay.style.display = 'none';
            }
        });
    });
    
    // Calculate change function
    function calculateChange() {
        const cashReceived = parseFloat(cashReceivedInput.value) || 0;
        const discount = parseFloat(discountInput.value) || 0;
        currentTotal = originalTotal - discount;
        
        // Update final total display
        finalTotal.textContent = 'Rp ' + currentTotal.toLocaleString('id-ID');
        
        const selectedMethod = document.querySelector('input[name="payment_method"]:checked');
        if (selectedMethod && selectedMethod.value === 'cash' && cashReceived > 0) {
            const change = cashReceived - currentTotal;
            if (change >= 0) {
                changeAmount.textContent = 'Rp ' + change.toLocaleString('id-ID');
                changeDisplay.style.display = 'block';
                changeDisplay.style.background = '#f0fdf4';
                changeDisplay.style.borderColor = '#bbf7d0';
                changeDisplay.style.color = '#166534';
            } else {
                changeAmount.textContent = 'Kurang Rp ' + Math.abs(change).toLocaleString('id-ID');
                changeDisplay.style.display = 'block';
                changeDisplay.style.background = '#fef2f2';
                changeDisplay.style.borderColor = '#fecaca';
                changeDisplay.style.color = '#dc2626';
            }
        } else {
            changeDisplay.style.display = 'none';
        }
    }
    
    // Event listeners for calculation
    cashReceivedInput.addEventListener('input', calculateChange);
    discountInput.addEventListener('input', calculateChange);
    
    // Form validation
    document.getElementById('paymentForm').addEventListener('submit', function(e) {
        const selectedMethod = document.querySelector('input[name="payment_method"]:checked');
        
        if (selectedMethod && selectedMethod.value === 'cash') {
            const cashReceived = parseFloat(cashReceivedInput.value) || 0;
            const discount = parseFloat(discountInput.value) || 0;
            const total = originalTotal - discount;
            
            if (cashReceived < total) {
                e.preventDefault();
                alert('Jumlah uang yang diterima kurang dari total pembayaran!');
                return;
            }
        }
        
        // Disable submit button to prevent double submission
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="animation: spin 1s linear infinite;"><path d="M21 12a9 9 0 11-6.219-8.56"/></svg> Memproses...';
    });
    
    // Quick amount buttons for cash
    const quickAmounts = [50000, 100000, 200000, 500000];
    const quickButtonsContainer = document.createElement('div');
    quickButtonsContainer.className = 'quick-amounts';
    quickButtonsContainer.innerHTML = '<label>Jumlah Cepat:</label><div class="quick-buttons"></div>';
    
    const quickButtons = quickButtonsContainer.querySelector('.quick-buttons');
    quickAmounts.forEach(function(amount) {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'quick-btn';
        btn.textContent = 'Rp ' + amount.toLocaleString('id-ID');
        btn.addEventListener('click', function() {
            cashReceivedInput.value = amount;
            calculateChange();
        });
        quickButtons.appendChild(btn);
    });
    
    cashReceivedGroup.appendChild(quickButtonsContainer);
});
</script>
@endsection
