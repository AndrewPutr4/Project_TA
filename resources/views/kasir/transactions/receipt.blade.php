<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Struk Pembayaran #{{ $transaction->transaction_number }}</title>
    
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    {{-- Inter Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <div class="receipt-container">
        {{-- Header Section --}}
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

        {{-- Receipt Content --}}
        <div class="receipt-wrapper">
            <div class="receipt-card" id="receipt-content">
                {{-- Store Header --}}
                <div class="store-header">
                    <h2 class="store-name">WARUNG BAKSO SELINGSING</h2>
                    <div class="store-info">
                        <p class="store-address">Jl. Contoh No. 123, Kota Contoh</p>
                        <p class="store-contact">Telp: (021) 1234-5678</p>
                    </div>
                    <div class="receipt-divider"></div>
                </div>

                {{-- Transaction Info --}}
                <div class="transaction-info">
                    <div class="transaction-header">
                        <h3>DETAIL TRANSAKSI</h3>
                    </div>
                    <div class="info-grid">
                        <div class="info-row">
                            <span class="label">No. Transaksi</span>
                            <span class="value">#{{ $transaction->transaction_number }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Tanggal & Waktu</span>
                            <span class="value">{{ $transaction->transaction_date->format('d/m/Y H:i:s') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Kasir</span>
                            <span class="value">{{ $transaction->kasir->name ?? 'Admin' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Customer</span>
                            <span class="value">{{ $transaction->customer_name }}</span>
                        </div>
                        @if($transaction->order->table_number)
                            <div class="info-row">
                                <span class="label">No. Meja</span>
                                <span class="value">{{ $transaction->order->table_number }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="receipt-divider"></div>
                </div>

                {{-- Items List --}}
                <div class="items-section">
                    <div class="section-header">
                        <h3 class="section-title">DETAIL PESANAN</h3>
                        <span class="item-count">{{ $transaction->order->items->count() }} item</span>
                    </div>
                    
                    <div class="items-list">
                        @forelse($transaction->order->items as $item)
                            <div class="item-row">
                                <div class="item-info">
                                    <div class="item-name">{{ $item->menu_name }}</div>
                                    <div class="item-detail">
                                        <span class="quantity">{{ $item->quantity }}x</span>
                                        <span class="price">@ Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                <div class="item-total">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </div>
                            </div>
                        @empty
                            <div class="empty-state">
                                <i class="fas fa-inbox"></i>
                                <p>Pesanan tidak memiliki item.</p>
                            </div>
                        @endforelse
                    </div>
                    <div class="receipt-divider"></div>
                </div>

                {{-- Summary --}}
                <div class="summary-section">
                    <div class="summary-rows">
                        <div class="summary-row">
                            <span class="label">Subtotal</span>
                            <span class="value">Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
                        </div>
                        @if($transaction->service_fee > 0)
                            <div class="summary-row">
                                <span class="label">Biaya Layanan</span>
                                <span class="value">Rp {{ number_format($transaction->service_fee, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        <div class="summary-row total-row">
                            <span class="label">TOTAL BAYAR</span>
                            <span class="value">Rp {{ number_format($transaction->total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="receipt-divider"></div>
                </div>

                {{-- Payment Info --}}
                <div class="payment-section">
                    <div class="payment-header">
                        <h3>PEMBAYARAN</h3>
                    </div>
                    <div class="payment-info">
                        <div class="payment-row">
                            <span class="label">Metode Bayar</span>
                            <span class="value payment-method">
                                @if($transaction->payment_method === 'cash')
                                    <i class="fas fa-money-bill-wave"></i> Tunai
                                @elseif($transaction->payment_method === 'midtrans')
                                    <i class="fas fa-credit-card"></i> Cashless (Digital)
                                @else
                                    <i class="fas fa-credit-card"></i> {{ ucfirst($transaction->payment_method) }}
                                @endif
                            </span>
                        </div>
                        @if($transaction->payment_method === 'cash')
                            <div class="payment-row">
                                <span class="label">Uang Diterima</span>
                                <span class="value">Rp {{ number_format($transaction->cash_received, 0, ',', '.') }}</span>
                            </div>
                            <div class="payment-row change-row">
                                <span class="label">Kembalian</span>
                                <span class="value">Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="receipt-divider"></div>
                </div>

                {{-- Footer --}}
                <div class="receipt-footer">
                    <div class="thank-you-section">
                        <p class="thank-you">TERIMA KASIH ATAS KUNJUNGAN ANDA!</p>
                    </div>
                    @if($transaction->notes)
                        <div class="notes-section">
                            <div class="notes-header">
                                <i class="fas fa-sticky-note"></i>
                                <span>Catatan:</span>
                            </div>
                            <p class="notes-text">{{ $transaction->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="receipt-actions">
                <button onclick="printReceipt()" class="btn btn-primary btn-large">
                    <i class="fas fa-print"></i>
                    <span>Cetak Struk</span>
                </button>
                <a href="{{ route('kasir.orders.index') }}" class="btn btn-secondary btn-large">
                    <i class="fas fa-list"></i>
                    <span>Daftar Order</span>
                </a>
                <a href="{{ route('kasir.dashboard') }}" class="btn btn-success btn-large">
                    <i class="fas fa-plus"></i>
                    <span>Order Baru</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Enhanced Styles --}}
    <style>
    :root {
        --primary-color: #f59e0b;
        --primary-dark: #d97706;
        --primary-light: #fbbf24;
        --secondary-color: #6b7280;
        --success-color: #10b981;
        --danger-color: #ef4444;
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

    .receipt-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 1.5rem;
        background: linear-gradient(135deg, var(--gray-50) 0%, var(--gray-100) 100%);
        min-height: 100vh;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    /* Header Styles */
    .page-header {
        margin-bottom: 2rem;
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
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

    .btn-print {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
        padding: 0.75rem 1.25rem;
        border-radius: 12px;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }

    .btn-print:hover {
        background: rgba(255, 255, 255, 0.3);
        border-color: rgba(255, 255, 255, 0.5);
        transform: translateY(-2px);
    }

    /* Receipt Card */
    .receipt-wrapper {
        background: white;
        border-radius: 20px;
        box-shadow: var(--shadow-xl);
        overflow: hidden;
        border: 1px solid var(--gray-200);
    }

    .receipt-card {
        padding: 2.5rem;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        line-height: 1.6;
        color: var(--gray-800);
    }

    /* Store Header */
    .store-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .store-logo {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 1.5rem;
        color: white;
        box-shadow: var(--shadow-md);
    }

    .store-name {
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--gray-900);
        margin: 0 0 1rem 0;
        letter-spacing: -0.025em;
    }

    .store-info {
        margin-bottom: 1rem;
    }

    .store-address, 
    .store-contact {
        font-size: 0.875rem;
        color: var(--gray-600);
        margin: 0.25rem 0;
        font-weight: 500;
    }

    .receipt-divider {
        border-top: 2px dashed var(--gray-300);
        margin: 1.5rem 0;
        position: relative;
    }

    .receipt-divider::before,
    .receipt-divider::after {
        content: '';
        position: absolute;
        top: -8px;
        width: 16px;
        height: 16px;
        background: var(--gray-50);
        border-radius: 50%;
    }

    .receipt-divider::before {
        left: -8px;
    }

    .receipt-divider::after {
        right: -8px;
    }

    /* Transaction Info */
    .transaction-info {
        margin-bottom: 2rem;
    }

    .transaction-header {
        text-align: center;
        margin-bottom: 1rem;
    }

    .transaction-header h3 {
        font-size: 1rem;
        font-weight: 700;
        color: var(--gray-700);
        margin: 0;
        letter-spacing: 0.05em;
    }

    .info-grid {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.875rem;
    }

    .info-row .label {
        color: var(--gray-600);
        font-weight: 500;
    }

    .info-row .value {
        color: var(--gray-900);
        font-weight: 600;
    }

    /* Items Section */
    .items-section {
        margin-bottom: 2rem;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .section-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--gray-700);
        margin: 0;
        letter-spacing: 0.05em;
    }

    .item-count {
        font-size: 0.75rem;
        color: var(--gray-500);
        background: var(--gray-100);
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-weight: 600;
    }

    .items-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .item-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 1rem;
        background: var(--gray-50);
        border-radius: 12px;
        border: 1px solid var(--gray-200);
        transition: all 0.2s ease;
    }

    .item-row:hover {
        background: var(--gray-100);
        transform: translateY(-1px);
        box-shadow: var(--shadow-sm);
    }

    .item-info {
        flex: 1;
    }

    .item-name {
        font-weight: 600;
        color: var(--gray-900);
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }

    .item-detail {
        display: flex;
        gap: 1rem;
        font-size: 0.75rem;
        color: var(--gray-600);
    }

    .quantity {
        background: var(--primary-color);
        color: white;
        padding: 0.125rem 0.5rem;
        border-radius: 6px;
        font-weight: 600;
    }

    .price {
        font-weight: 500;
    }

    .item-total {
        font-weight: 700;
        color: var(--gray-900);
        font-size: 0.875rem;
        margin-left: 1rem;
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
        color: var(--gray-400);
    }

    /* Summary Section */
    .summary-section {
        margin-bottom: 2rem;
    }

    .summary-rows {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.875rem;
    }

    .summary-row .label {
        color: var(--gray-600);
        font-weight: 500;
    }

    .summary-row .value {
        color: var(--gray-900);
        font-weight: 600;
    }

    .total-row {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--primary-dark);
        padding: 1rem;
        background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
        border-radius: 12px;
        border: 2px solid #fde68a;
        margin-top: 0.5rem;
    }

    /* Payment Section */
    .payment-section {
        margin-bottom: 2rem;
    }

    .payment-header {
        text-align: center;
        margin-bottom: 1rem;
    }

    .payment-header h3 {
        font-size: 1rem;
        font-weight: 700;
        color: var(--gray-700);
        margin: 0;
        letter-spacing: 0.05em;
    }

    .payment-info {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .payment-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.875rem;
    }

    .payment-row .label {
        color: var(--gray-600);
        font-weight: 500;
    }

    .payment-row .value {
        color: var(--gray-900);
        font-weight: 600;
    }

    .payment-method {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .change-row {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        padding: 0.75rem;
        border-radius: 8px;
        border: 1px solid #86efac;
    }

    .change-row .value {
        color: #065f46;
        font-weight: 700;
    }

    /* Footer */
    .receipt-footer {
        text-align: center;
        margin-top: 2rem;
    }

    .thank-you-section {
        margin-bottom: 1.5rem;
    }

    .thank-you-section i {
        font-size: 1.5rem;
        color: #ef4444;
        margin-bottom: 0.5rem;
        display: block;
    }

    .thank-you {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--primary-dark);
        margin: 0;
        letter-spacing: 0.025em;
    }

    .footer-notes {
        margin-bottom: 1.5rem;
    }

    .footer-note {
        font-size: 0.75rem;
        color: var(--gray-600);
        margin: 0.5rem 0;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .notes-section {
        margin: 1.5rem 0;
        padding: 1rem;
        background: var(--gray-50);
        border-radius: 8px;
        border: 1px solid var(--gray-200);
    }

    .notes-header {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        font-weight: 600;
        color: var(--gray-700);
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }

    .notes-text {
        color: var(--gray-600);
        font-style: italic;
        margin: 0;
        font-size: 0.875rem;
    }

    .qr-section {
        margin-top: 1.5rem;
    }

    .qr-text {
        font-size: 0.75rem;
        color: var(--gray-600);
        margin: 0 0 0.5rem 0;
    }

    .qr-placeholder {
        width: 60px;
        height: 60px;
        background: var(--gray-100);
        border: 2px dashed var(--gray-300);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        font-size: 1.5rem;
        color: var(--gray-400);
    }

    /* Action Buttons */
    .receipt-actions {
        padding: 2rem;
        background: linear-gradient(135deg, var(--gray-50) 0%, var(--gray-100) 100%);
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        padding: 1rem 1.5rem;
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

    .btn-large {
        padding: 1.25rem 2rem;
        font-size: 1rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        box-shadow: var(--shadow-md);
    }

    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(245, 158, 11, 0.4);
        color: white;
        text-decoration: none;
    }

    .btn-secondary {
        background: linear-gradient(135deg, var(--secondary-color) 0%, #4b5563 100%);
        color: white;
        box-shadow: var(--shadow-md);
    }

    .btn-secondary:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(107, 114, 128, 0.4);
        color: white;
        text-decoration: none;
    }

    .btn-success {
        background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
        color: white;
        box-shadow: var(--shadow-md);
    }

    .btn-success:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(16, 185, 129, 0.4);
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
            border: none;
        }
        
        .receipt-card {
            padding: 1rem;
            font-size: 12px;
        }
        
        .store-name {
            font-size: 18px;
        }
        
        .section-title,
        .transaction-header h3,
        .payment-header h3 {
            font-size: 14px;
        }
        
        .receipt-divider::before,
        .receipt-divider::after {
            display: none;
        }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .receipt-container {
            padding: 1rem;
        }
        
        .header-content {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
            padding: 1.5rem;
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
            padding: 1.5rem;
        }
        
        .item-row {
            flex-direction: column;
            gap: 0.75rem;
            align-items: flex-start;
        }
        
        .item-total {
            margin-left: 0;
            align-self: flex-end;
        }
    }

    @media (max-width: 480px) {
        .receipt-container {
            padding: 0.75rem;
        }
        
        .header-content {
            padding: 1.25rem;
        }
        
        .receipt-card {
            padding: 1.25rem;
        }
        
        .page-title {
            font-size: 1.5rem;
        }
        
        .store-name {
            font-size: 1.5rem;
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

    .receipt-card {
        animation: fadeIn 0.5s ease-out;
    }

    /* Loading State */
    .btn.loading {
        pointer-events: none;
    }

    .btn.loading i {
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
        
        // Add print button animation
        const printBtns = document.querySelectorAll('.btn-print, .btn-primary');
        printBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                this.classList.add('loading');
                setTimeout(() => {
                    this.classList.remove('loading');
                }, 2000);
            });
        });
    });
    </script>
</body>
</html>
