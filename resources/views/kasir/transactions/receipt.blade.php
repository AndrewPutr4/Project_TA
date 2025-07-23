@extends('layouts.kasir')

@section('title', 'Struk Pembayaran #' . $transaction->transaction_number)

@section('content')
<div class="receipt-container">
    <div class="page-header">
        <div class="header-content">
            <div class="header-left">
                <a href="{{ route('kasir.orders.index') }}" class="back-btn">
                    <i class="fas fa-arrow-left"></i>
                    Kembali ke Order
                </a>
                <div class="header-text">
                    <h1 class="page-title">Struk Pembayaran</h1>
                    <p class="page-subtitle">Transaksi #{{ $transaction->transaction_number }}</p>
                </div>
            </div>
            <div class="header-actions">
                <button onclick="printReceipt()" class="btn btn-print">
                    <i class="fas fa-print"></i>
                    Cetak Struk
                </button>
            </div>
        </div>
    </div>

    <div class="receipt-wrapper">
        <div class="receipt-card" id="receipt-content">
            {{-- Store Header --}}
            <div class="store-header">
                <h2 class="store-name">WARUNG BAKSO SELINGSING</h2>
                <p class="store-address">Jl. Contoh No. 123, Kota Contoh</p>
                <p class="store-contact">Telp: (021) 1234-5678</p>
                <div class="receipt-divider"></div>
            </div>

            {{-- Transaction Info --}}
            <div class="transaction-info">
                <div class="info-row">
                    <span class="label">No. Transaksi:</span>
                    <span class="value">#{{ $transaction->transaction_number }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Tanggal:</span>
                    <span class="value">{{ $transaction->transaction_date->format('d/m/Y H:i:s') }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Kasir:</span>
                    <span class="value">{{ $transaction->kasir->name ?? 'Admin' }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Customer:</span>
                    <span class="value">{{ $transaction->customer_name }}</span>
                </div>
                @if($transaction->order->table_number)
                    <div class="info-row">
                        <span class="label">Meja:</span>
                        <span class="value">{{ $transaction->order->table_number }}</span>
                    </div>
                @endif
                <div class="receipt-divider"></div>
            </div>

            {{-- Items List --}}
            <div class="items-section">
                <h3 class="section-title">DETAIL PESANAN</h3>
                @foreach($transaction->order->orderItems as $item)
                    <div class="item-row">
                        <div class="item-info">
                            <div class="item-name">{{ $item->menu_name }}</div>
                            <div class="item-detail">
                                {{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="item-total">
                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                        </div>
                    </div>
                @endforeach
                <div class="receipt-divider"></div>
            </div>

            {{-- Summary --}}
            <div class="summary-section">
                <div class="summary-row">
                    <span class="label">Subtotal:</span>
                    <span class="value">Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
                </div>
                @if($transaction->service_fee > 0)
                    <div class="summary-row">
                        <span class="label">Biaya Layanan:</span>
                        <span class="value">Rp {{ number_format($transaction->service_fee, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="summary-row total-row">
                    <span class="label">TOTAL:</span>
                    <span class="value">Rp {{ number_format($transaction->total, 0, ',', '.') }}</span>
                </div>
                <div class="receipt-divider"></div>
            </div>

            {{-- Payment Info --}}
            <div class="payment-section">
                <div class="payment-row">
                    <span class="label">Metode Bayar:</span>
                    <span class="value">
                        @if($transaction->payment_method === 'cash')
                            Tunai
                        @elseif($transaction->payment_method === 'midtrans')
                            Cashless (Digital)
                        @else
                            {{ ucfirst($transaction->payment_method) }}
                        @endif
                    </span>
                </div>
                @if($transaction->payment_method === 'cash')
                    <div class="payment-row">
                        <span class="label">Uang Diterima:</span>
                        <span class="value">Rp {{ number_format($transaction->cash_received, 0, ',', '.') }}</span>
                    </div>
                    <div class="payment-row">
                        <span class="label">Kembalian:</span>
                        <span class="value">Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="receipt-divider"></div>
            </div>

            {{-- Footer --}}
            <div class="receipt-footer">
                <p class="thank-you">TERIMA KASIH ATAS KUNJUNGAN ANDA!</p>
                <p class="footer-note">Barang yang sudah dibeli tidak dapat dikembalikan</p>
                <p class="footer-note">Simpan struk ini sebagai bukti pembayaran</p>

                @if($transaction->notes)
                    <div class="notes-section">
                        <p class="notes-label">Catatan:</p>
                        <p class="notes-text">{{ $transaction->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="receipt-actions">
            <button onclick="printReceipt()" class="btn btn-primary btn-large">
                <i class="fas fa-print"></i>
                Cetak Struk
            </button>
            <a href="{{ route('kasir.orders.index') }}" class="btn btn-secondary btn-large">
                <i class="fas fa-list"></i>
                Kembali ke Daftar Order
            </a>
            <a href="{{ route('kasir.dashboard') }}" class="btn btn-success btn-large">
                <i class="fas fa-plus"></i>
                Order Baru
            </a>
        </div>
    </div>
</div>

{{-- Styles --}}
<style>
:root {
    --primary-color: #f59e0b;
    --primary-dark: #d97706;
    --warning-bg: #fffbeb;
    --warning-border: #fed7aa;
    --warning-text: #92400e;
}

.receipt-container {
    max-width: 800px;
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
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    color: white;
    padding: 1.5rem 2rem;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
}

.header-left {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    flex: 1;
}

.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    background: rgba(255,255,255,0.2);
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 600;
    transition: all 0.2s ease;
    border: 1px solid rgba(255,255,255,0.3);
}

.back-btn:hover {
    background: rgba(255,255,255,0.3);
    transform: translateY(-1px);
    color: white;
    text-decoration: none;
}

.page-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin: 0 0 0.25rem 0;
}

.page-subtitle {
    font-size: 0.875rem;
    opacity: 0.9;
    margin: 0;
}

.btn-print {
    background: rgba(255,255,255,0.2);
    color: white;
    border: 1px solid rgba(255,255,255,0.3);
}

.btn-print:hover {
    background: rgba(255,255,255,0.3);
    border-color: rgba(255,255,255,0.5);
}

.receipt-wrapper {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(245, 158, 11, 0.1);
    overflow: hidden;
}

.receipt-card {
    padding: 2rem;
    font-family: 'Courier New', monospace;
    line-height: 1.4;
}

.store-header {
    text-align: center;
    margin-bottom: 1.5rem;
}

.store-name {
    font-size: 1.5rem;
    font-weight: bold;
    color: var(--warning-text);
    margin: 0 0 0.5rem 0;
}

.store-address, .store-contact {
    font-size: 0.875rem;
    color: #6b7280;
    margin: 0.25rem 0;
}

.receipt-divider {
    border-top: 2px dashed #d1d5db;
    margin: 1rem 0;
}

.transaction-info {
    margin-bottom: 1.5rem;
}

.info-row, .summary-row, .payment-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.label {
    color: #6b7280;
    font-weight: 500;
}

.value {
    color: #1f2937;
    font-weight: 600;
}

.section-title {
    font-size: 1rem;
    font-weight: bold;
    color: var(--warning-text);
    margin: 0 0 1rem 0;
    text-align: center;
}

.item-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 0.75rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #f3f4f6;
}

.item-row:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.item-info {
    flex: 1;
}

.item-name {
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.25rem;
}

.item-detail {
    font-size: 0.8rem;
    color: #6b7280;
}

.item-total {
    font-weight: 600;
    color: var(--primary-color);
    margin-left: 1rem;
}

.summary-section {
    margin-bottom: 1.5rem;
}

.total-row {
    font-size: 1rem;
    font-weight: bold;
    color: var(--warning-text);
    padding-top: 0.5rem;
    border-top: 1px solid #e5e7eb;
}

.payment-section {
    margin-bottom: 1.5rem;
}

.receipt-footer {
    text-align: center;
    margin-top: 2rem;
}

.thank-you {
    font-size: 1rem;
    font-weight: bold;
    color: var(--warning-text);
    margin: 0 0 1rem 0;
}

.footer-note {
    font-size: 0.8rem;
    color: #6b7280;
    margin: 0.25rem 0;
}

.notes-section {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #e5e7eb;
}

.notes-label {
    font-weight: 600;
    color: #1f2937;
    margin: 0 0 0.5rem 0;
}

.notes-text {
    color: #6b7280;
    font-style: italic;
    margin: 0;
}

.receipt-actions {
    padding: 1.5rem;
    background: var(--warning-bg);
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.875rem 1.5rem;
    border: none;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
}

.btn-large {
    padding: 1rem 1.5rem;
    font-size: 0.95rem;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
    color: white;
    text-decoration: none;
}

.btn-secondary {
    background: #6b7280;
    color: white;
}

.btn-secondary:hover {
    background: #4b5563;
    transform: translateY(-1px);
    color: white;
    text-decoration: none;
}

.btn-success {
    background: #10b981;
    color: white;
}

.btn-success:hover {
    background: #059669;
    transform: translateY(-1px);
    color: white;
    text-decoration: none;
}

/* Print Styles */
@media print {
    .receipt-container {
        background: white;
        padding: 0;
        margin: 0;
        max-width: none;
    }
    
    .page-header,
    .receipt-actions {
        display: none !important;
    }
    
    .receipt-wrapper {
        box-shadow: none;
        border-radius: 0;
    }
    
    .receipt-card {
        padding: 1rem;
        font-size: 12px;
    }
    
    .store-name {
        font-size: 18px;
    }
    
    .section-title {
        font-size: 14px;
    }
}

/* Responsive */
@media (max-width: 768px) {
    .receipt-container {
        padding: 0.5rem;
    }
    
    .header-content {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
        padding: 1.25rem;
    }
    
    .header-left {
        flex-direction: column;
        gap: 1rem;
        align-items: center;
    }
    
    .receipt-card {
        padding: 1.5rem;
    }
    
    .receipt-actions {
        grid-template-columns: 1fr;
        padding: 1rem;
    }
}
</style>

<script>
function printReceipt() {
    // Hide non-printable elements
    const header = document.querySelector('.page-header');
    const actions = document.querySelector('.receipt-actions');
    
    header.style.display = 'none';
    actions.style.display = 'none';
    
    // Print
    window.print();
    
    // Show elements back
    setTimeout(() => {
        header.style.display = 'block';
        actions.style.display = 'grid';
    }, 1000);
}

// Auto focus and keyboard shortcuts
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Receipt page loaded');
    
    // Add keyboard shortcut for print
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
            e.preventDefault();
            printReceipt();
        }
    });
});
</script>
@endsection
