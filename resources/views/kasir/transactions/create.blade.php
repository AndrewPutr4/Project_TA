@extends('layouts.kasir')

@section('title', 'Pembayaran Order #' . $order->order_number)

@section('content')
<div class="payment-container">
    <div class="page-header">
        <div class="header-content">
            <div class="header-left">
                <a href="{{ route('kasir.orders.index') }}" class="back-btn">
                    <i class="fas fa-arrow-left"></i>
                    Kembali
                </a>
                <div class="header-text">
                    <h1 class="page-title">Pembayaran Order #{{ $order->order_number }}</h1>
                    <p class="page-subtitle">Proses pembayaran untuk pesanan</p>
                </div>
            </div>
        </div>
    </div>

    <div class="payment-grid">
        <div class="order-summary-card">
            <div class="card-header">
                <h2><i class="fas fa-receipt"></i> Ringkasan Order</h2>
            </div>
            <div class="card-content">
                <div class="customer-info">
                    <h3><i class="fas fa-user"></i> {{ $order->customer_name }}</h3>
                    @if($order->table_number)
                    <p><i class="fas fa-chair"></i> Dine In - Meja {{ $order->table_number }}</p>
                    @else
                    <p><i class="fas fa-shopping-bag"></i> Takeaway</p>
                    @endif
                </div>

                <div class="items-summary">
                    <h4><i class="fas fa-list"></i> Item Pesanan</h4>
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
                        <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @if($order->service_fee > 0)
                    <div class="summary-row">
                        <span>Biaya Layanan</span>
                        <span>Rp {{ number_format($order->service_fee, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <div class="summary-row total">
                        <span><i class="fas fa-calculator"></i> Total Pembayaran</span>
                        <span id="finalTotal" data-total="{{ $order->total }}">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="payment-form-card">
            <div class="card-header">
                <h2><i class="fas fa-credit-card"></i> Form Pembayaran</h2>
            </div>
            <div class="card-content">
                <form method="POST" action="{{ route('kasir.transactions.store', $order) }}" id="paymentForm">
                    @csrf

                    <div class="form-group">
                        <label>Metode Pembayaran</label>
                        <div class="payment-methods">
                            <label class="payment-method">
                                <input type="radio" name="payment_method" value="cash" checked>
                                <div class="method-card cash-method">
                                    <div class="method-icon"><i class="fas fa-money-bill-wave"></i></div>
                                    <div class="method-info">
                                        <span class="method-title">Tunai</span>
                                        <span class="method-desc">Pembayaran dengan uang cash</span>
                                    </div>
                                </div>
                            </label>
                            <label class="payment-method">
                                <input type="radio" name="payment_method" value="cashless">
                                <div class="method-card cashless-method">
                                    <div class="method-icon"><i class="fas fa-credit-card"></i></div>
                                    <div class="method-info">
                                        <span class="method-title">Cashless</span>
                                        <span class="method-desc">Via Midtrans (QRIS, Kartu, dll)</span>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="form-group" id="cashReceivedGroup">
                        <label for="cash_received"><i class="fas fa-hand-holding-usd"></i> Uang Diterima</label>
                        <input type="number" name="cash_received" id="cash_received" class="form-input" placeholder="Masukkan jumlah uang yang diterima" step="1000" min="0">
                        @error('cash_received')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                                                
                        <div class="quick-amounts">
                            <label>Jumlah Cepat:</label>
                            <div class="quick-buttons">
                                <button type="button" class="quick-btn" data-amount="50000">50K</button>
                                <button type="button" class="quick-btn" data-amount="100000">100K</button>
                                <button type="button" class="quick-btn" data-amount="200000">200K</button>
                                <button type="button" class="quick-btn" data-amount="500000">500K</button>
                                <button type="button" class="quick-btn" data-amount="{{ $order->total }}">Pas</button>
                            </div>
                        </div>

                        <div class="change-display" id="changeDisplay" style="display: none;">
                            <i class="fas fa-coins"></i>
                            <span>Kembalian: <strong id="changeAmount">Rp 0</strong></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="notes"><i class="fas fa-sticky-note"></i> Catatan (Opsional)</label>
                        <textarea name="notes" id="notes" class="form-textarea" rows="3" placeholder="Catatan tambahan untuk transaksi ini"></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-success btn-large" id="submitBtn">
                            <i class="fas fa-check-circle"></i>
                            Proses Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
/* Tema Kuning/Amber untuk Halaman Pembayaran */
.payment-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 1rem;
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    min-height: 100vh;
}

.page-header { 
    margin-bottom: 2rem; 
}

.header-content { 
    display: flex; 
    justify-content: space-between; 
    align-items: center; 
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); 
    color: white; 
    padding: 2rem; 
    border-radius: 16px; 
    box-shadow: 0 10px 25px rgba(245, 158, 11, 0.3);
    position: relative;
    overflow: hidden;
}

.header-content::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: rotate 20s linear infinite;
}

@keyframes rotate {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.header-left { 
    display: flex; 
    align-items: center; 
    gap: 1.5rem; 
    flex: 1;
    position: relative;
    z-index: 2;
}

.back-btn { 
    display: inline-flex; 
    align-items: center; 
    gap: 0.5rem; 
    padding: 0.75rem 1rem; 
    background: rgba(255,255,255,0.2); 
    color: white; 
    text-decoration: none; 
    border-radius: 12px; 
    font-size: 0.875rem; 
    font-weight: 600; 
    transition: all 0.3s ease; 
    border: 1px solid rgba(255,255,255,0.3);
}

.back-btn:hover { 
    background: rgba(255,255,255,0.3); 
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.header-text { 
    flex: 1; 
}

.page-title { 
    font-size: 2rem; 
    font-weight: 800; 
    margin: 0 0 0.5rem 0; 
}

.page-subtitle { 
    font-size: 1rem; 
    opacity: 0.9; 
    margin: 0; 
}

.payment-grid { 
    display: grid; 
    grid-template-columns: 1fr 1fr; 
    gap: 2rem; 
}

.order-summary-card, .payment-form-card { 
    background: white; 
    border-radius: 16px; 
    overflow: hidden; 
    box-shadow: 0 10px 25px rgba(245, 158, 11, 0.15);
    transition: all 0.3s ease;
    border: 2px solid rgba(245, 158, 11, 0.1);
}

.order-summary-card:hover, .payment-form-card:hover { 
    transform: translateY(-5px); 
    box-shadow: 0 20px 40px rgba(245, 158, 11, 0.2);
}

.card-header { 
    padding: 1.5rem; 
    border-bottom: 2px solid #fde68a; 
    background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
}

.card-header h2 { 
    font-size: 1.25rem; 
    font-weight: 700; 
    color: #92400e; 
    margin: 0; 
    display: flex; 
    align-items: center; 
    gap: 0.75rem; 
}

.card-content { 
    padding: 2rem; 
}

.customer-info { 
    margin-bottom: 2rem; 
    padding: 1.5rem; 
    background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); 
    border-radius: 12px; 
    border: 2px solid #fde68a;
}

.customer-info h3 { 
    font-size: 1.25rem; 
    font-weight: 700; 
    color: #92400e; 
    margin: 0 0 0.75rem 0; 
    display: flex; 
    align-items: center; 
    gap: 0.5rem; 
}

.customer-info p { 
    font-size: 0.875rem; 
    color: #a16207; 
    margin: 0.5rem 0; 
    display: flex; 
    align-items: center; 
    gap: 0.5rem; 
}

.items-summary { 
    margin-bottom: 2rem; 
}

.items-summary h4 { 
    font-size: 1rem; 
    font-weight: 600; 
    color: #92400e; 
    margin: 0 0 1rem 0; 
    display: flex; 
    align-items: center; 
    gap: 0.5rem; 
}

.item-row { 
    display: flex; 
    justify-content: space-between; 
    align-items: center; 
    padding: 1rem 0; 
    border-bottom: 1px solid #fde68a; 
    font-size: 0.875rem; 
}

.item-row:last-child { 
    border-bottom: none; 
}

.item-name { 
    flex: 1; 
    color: #92400e; 
    font-weight: 500; 
}

.item-qty { 
    color: #a16207; 
    margin: 0 1rem; 
    font-weight: 600; 
}

.item-total { 
    font-weight: 700; 
    color: #92400e; 
}

.summary-totals { 
    padding-top: 1.5rem; 
    border-top: 2px solid #fde68a; 
}

.summary-row { 
    display: flex; 
    justify-content: space-between; 
    align-items: center; 
    padding: 0.75rem 0; 
    font-size: 0.875rem; 
}

.summary-row.total { 
    padding: 1rem 0; 
    border-top: 2px solid #fde68a; 
    font-size: 1.25rem; 
    font-weight: 700; 
    color: #92400e; 
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); 
    margin: 1rem -1rem -1rem -1rem; 
    padding: 1.5rem; 
    border-radius: 0 0 12px 12px;
}

.form-group { 
    margin-bottom: 2rem; 
}

.form-group label { 
    display: block; 
    font-size: 0.875rem; 
    font-weight: 600; 
    color: #92400e; 
    margin-bottom: 0.75rem; 
    display: flex; 
    align-items: center; 
    gap: 0.5rem; 
}

.payment-methods { 
    display: grid; 
    grid-template-columns: 1fr 1fr; 
    gap: 1.5rem; 
}

.method-card { 
    display: flex; 
    align-items: center; 
    gap: 1rem; 
    padding: 1.5rem; 
    border: 2px solid #fde68a; 
    border-radius: 12px; 
    transition: all 0.3s ease; 
    background: white; 
    min-height: 100px; 
    cursor: pointer; 
}

.payment-method input[type="radio"] { 
    display: none; 
}

.payment-method input[type="radio"]:checked + .method-card { 
    border-color: #f59e0b; 
    background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); 
    transform: translateY(-2px); 
    box-shadow: 0 8px 25px rgba(245, 158, 11, 0.2);
}

.method-card:hover { 
    border-color: #fbbf24; 
    transform: translateY(-1px); 
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.1);
}

.method-icon { 
    width: 60px; 
    height: 60px; 
    border-radius: 12px; 
    display: flex; 
    align-items: center; 
    justify-content: center; 
    flex-shrink: 0; 
    font-size: 1.5rem; 
}

.cash-method .method-icon { 
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); 
    color: white; 
}

.cashless-method .method-icon { 
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); 
    color: white; 
}

.method-info { 
    flex: 1; 
    display: flex; 
    flex-direction: column; 
    gap: 0.5rem; 
}

.method-title { 
    font-size: 1.125rem; 
    font-weight: 700; 
    color: #92400e; 
}

.method-desc { 
    font-size: 0.875rem; 
    color: #a16207; 
    line-height: 1.4; 
}

.form-input, .form-textarea, .form-select { 
    width: 100%; 
    padding: 0.875rem; 
    border: 2px solid #fde68a; 
    border-radius: 12px; 
    font-size: 0.875rem; 
    transition: all 0.3s ease; 
    background: white;
    color: #92400e;
}

.form-input:focus, .form-textarea:focus, .form-select:focus { 
    outline: none; 
    border-color: #f59e0b; 
    box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.15);
    transform: translateY(-2px);
}

.quick-amounts { 
    margin-top: 1rem; 
}

.quick-amounts label { 
    font-size: 0.8125rem; 
    color: #a16207; 
    margin-bottom: 0.75rem; 
}

.quick-buttons { 
    display: flex; 
    gap: 0.75rem; 
    flex-wrap: wrap; 
}

.quick-btn { 
    padding: 0.5rem 1rem; 
    background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); 
    border: 2px solid #fde68a; 
    border-radius: 8px; 
    font-size: 0.8125rem; 
    font-weight: 600; 
    cursor: pointer; 
    transition: all 0.3s ease; 
    color: #92400e;
}

.quick-btn:hover { 
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); 
    border-color: #fbbf24; 
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.2);
}

.quick-btn:active { 
    transform: translateY(0); 
}

.change-display { 
    margin-top: 1rem; 
    padding: 1rem; 
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); 
    border: 2px solid #86efac; 
    border-radius: 12px; 
    color: #065f46; 
    font-size: 0.875rem; 
    display: flex; 
    align-items: center; 
    gap: 0.5rem; 
}

.change-display.insufficient { 
    background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%); 
    border-color: #fecaca; 
    color: #dc2626; 
}

.error-message { 
    display: block; 
    margin-top: 0.5rem; 
    font-size: 0.8125rem; 
    color: #ef4444; 
    font-weight: 500; 
}

.btn { 
    display: inline-flex; 
    align-items: center; 
    gap: 0.75rem; 
    padding: 0.875rem 1.5rem; 
    border: none; 
    border-radius: 12px; 
    font-size: 0.875rem; 
    font-weight: 600; 
    cursor: pointer; 
    transition: all 0.3s ease; 
    text-decoration: none;
    position: relative;
    overflow: hidden;
}

.btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.btn:hover::before {
    left: 100%;
}

.btn-large { 
    padding: 1.25rem 2rem; 
    font-size: 1rem; 
    width: 100%; 
    justify-content: center; 
}

.btn-success { 
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); 
    color: white; 
}

.btn-success:hover { 
    transform: translateY(-3px); 
    box-shadow: 0 10px 25px rgba(245, 158, 11, 0.4);
}

.btn-success:disabled { 
    background: #9ca3af; 
    cursor: not-allowed; 
    transform: none; 
    box-shadow: none; 
}

.form-actions { 
    margin-top: 2rem; 
    padding-top: 2rem; 
    border-top: 2px solid #fde68a; 
}

@media (max-width: 768px) { 
    .payment-container { 
        padding: 0.5rem; 
    } 
    
    .payment-grid { 
        grid-template-columns: 1fr; 
    } 
    
    .payment-methods { 
        grid-template-columns: 1fr; 
        gap: 1rem; 
    } 
    
    .method-card { 
        min-height: 80px; 
        padding: 1rem; 
    } 
    
    .method-icon { 
        width: 50px; 
        height: 50px; 
    } 
    
    .method-title { 
        font-size: 1rem; 
    } 
    
    .method-desc { 
        font-size: 0.8125rem; 
    } 
    
    .header-content { 
        flex-direction: column; 
        gap: 1rem; 
        text-align: center; 
    } 
    
    .header-left { 
        flex-direction: column; 
        gap: 1rem; 
        align-items: center; 
    } 
    
    .page-title { 
        font-size: 1.5rem; 
    } 
    
    .quick-buttons { 
        justify-content: center; 
    } 
}

@media (max-width: 480px) { 
    .card-content { 
        padding: 1rem; 
    } 
    
    .method-card { 
        flex-direction: column; 
        text-align: center; 
        gap: 0.75rem; 
        min-height: auto; 
    } 
    
    .quick-buttons { 
        flex-direction: column; 
    } 
    
    .quick-btn { 
        width: 100%; 
        justify-content: center; 
    } 
}

@keyframes spin { 
    from { transform: rotate(0deg); } 
    to { transform: rotate(360deg); } 
}

.loading { 
    animation: spin 1s linear infinite; 
}
</style>
@endsection

@push('scripts')
{{-- Script Midtrans dan Logika Pembayaran --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // === Inisialisasi Variabel ===
    const paymentForm = document.getElementById('paymentForm');
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    const cashReceivedGroup = document.getElementById('cashReceivedGroup');
    const cashReceivedInput = document.getElementById('cash_received');
    const changeDisplay = document.getElementById('changeDisplay');
    const changeAmount = document.getElementById('changeAmount');
    const submitBtn = document.getElementById('submitBtn');
    const quickBtns = document.querySelectorAll('.quick-btn');
    const finalTotalEl = document.getElementById('finalTotal');
    const originalTotal = parseFloat(finalTotalEl.dataset.total) || 0;
        
    // === Fungsi untuk Menampilkan/Sembunyikan Input Uang Tunai ===
    function toggleCashInput() {
        if (document.querySelector('input[name="payment_method"]:checked').value === 'cash') {
            cashReceivedGroup.style.display = 'block';
            cashReceivedInput.required = true;
        } else {
            cashReceivedGroup.style.display = 'none';
            cashReceivedInput.required = false;
            changeDisplay.style.display = 'none';
        }
    }
    paymentMethods.forEach(method => method.addEventListener('change', toggleCashInput));
        
    // === Fungsi untuk Menghitung Kembalian ===
    function calculateChange() {
        if (document.querySelector('input[name="payment_method"]:checked').value !== 'cash') return;
        const cashReceived = parseFloat(cashReceivedInput.value) || 0;
                
        if (cashReceived > 0) {
            const change = cashReceived - originalTotal;
            changeDisplay.style.display = 'flex';
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
    quickBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            cashReceivedInput.value = this.dataset.amount;
            calculateChange();
        });
    });
        
    // === LOGIKA UTAMA: PENANGANAN SUBMIT FORM ===
    paymentForm.addEventListener('submit', function(e) {
        e.preventDefault(); 
        const selectedMethod = document.querySelector('input[name="payment_method"]:checked').value;
        const form = this;
                
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
                
        // --- Jika Metode Pembayaran adalah TUNAI ---
        if (selectedMethod === 'cash') {
            const cashReceived = parseFloat(cashReceivedInput.value) || 0;
            if (cashReceived < originalTotal) {
                alert('Jumlah uang yang diterima kurang dari total pembayaran!');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-check-circle"></i> Proses Pembayaran';
                return;
            }
            form.submit(); // Langsung kirim form ke server
        }
                 
        // --- Jika Metode Pembayaran adalah CASHLESS (MIDTRANS) ---
        else if (selectedMethod === 'cashless') {
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
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-check-circle"></i> Proses Pembayaran';
                    return;
                }
                // Tampilkan Popup Pembayaran Midtrans
                snap.pay(data.snap_token, {
                    onSuccess: function(result){
                        // Buat input tersembunyi untuk menyimpan detail hasil dari Midtrans
                        let hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = 'midtrans_payload';
                        hiddenInput.value = JSON.stringify(result);
                        form.appendChild(hiddenInput);
                                                
                        // Kirim form ke server untuk dicatat
                        form.submit();
                    },
                    onPending: function(result){
                        alert("Menunggu pembayaran Anda!");
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="fas fa-check-circle"></i> Proses Pembayaran';
                    },
                    onError: function(result){
                        alert("Pembayaran gagal!");
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="fas fa-check-circle"></i> Proses Pembayaran';
                    },
                    onClose: function(){
                        alert('Anda menutup popup tanpa menyelesaikan pembayaran.');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="fas fa-check-circle"></i> Proses Pembayaran';
                    }
                });
            }).catch(error => {
                alert('Terjadi kesalahan koneksi. Pastikan Anda terhubung ke internet.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-check-circle"></i> Proses Pembayaran';
            });
        }
    });
        
    // Panggil fungsi sekali saat halaman dimuat
    toggleCashInput();
});
</script>
@endpush
