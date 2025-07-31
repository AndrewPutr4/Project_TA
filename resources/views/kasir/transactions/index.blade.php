@extends('layouts.kasir')

@section('title', 'Daftar Transaksi')

@section('content')
<div class="transactions-container">
    <!-- Header -->
    <div class="page-header">
        <div class="header-content">
            <h1 class="page-title">Daftar Transaksi</h1>
            <div class="header-actions">
                <button class="btn btn-primary" onclick="refreshTransactions()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="23 4 23 10 17 10"></polyline>
                        <polyline points="1 20 1 14 7 14"></polyline>
                        <path d="M3.51 9a9 9 0 0 1 14.85-3.51L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
                    </svg>
                    Refresh
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                    <line x1="1" y1="10" x2="23" y2="10"></line>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value" id="totalTransactions">0</div>
                <div class="stat-label">Total Transaksi Hari Ini</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="1" x2="12" y2="23"></line>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value" id="totalRevenue">Rp 0</div>
                <div class="stat-label">Total Pendapatan</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                     <path d="M20 12V8H6a2 2 0 0 1-2-2c0-1.1.9-2 2-2h12v4"></path><path d="M4 6v12c0 1.1.9 2 2 2h14v-4"></path><path d="M18 12a2 2 0 0 0-2 2c0 1.1.9 2 2 2h4v-4h-4z"></path>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value" id="cashTransactions">0</div>
                <div class="stat-label">Pembayaran Tunai</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                    <line x1="8" y1="21" x2="16" y2="21"></line>
                    <line x1="12" y1="17" x2="12" y2="21"></line>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value" id="digitalTransactions">0</div>
                <div class="stat-label">Pembayaran Digital</div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters-section">
        <form method="GET" class="filters-form">
            <div class="filter-group">
                <label>Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div class="filter-group">
                <label>Metode Pembayaran</label>
                <select name="payment_method" class="form-select">
                    <option value="">Semua Metode</option>
                    <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Tunai</option>
                    <option value="card" {{ request('payment_method') == 'card' ? 'selected' : '' }}>Kartu</option>
                    <option value="qris" {{ request('payment_method') == 'qris' ? 'selected' : '' }}>QRIS</option>
                    <option value="transfer" {{ request('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                </select>
            </div>

            <div class="filter-group">
                <label>Tanggal</label>
                <input type="date" name="date" value="{{ request('date') }}" class="form-input">
            </div>

            <div class="filter-group">
                <label>Cari</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="No. Transaksi, Nama..." class="form-input">
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('kasir.transactions.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>
    </div>

    <!-- Transactions List -->
    <div class="transactions-list">
        @forelse($transactions as $transaction)
        <div class="transaction-card" data-transaction-id="{{ $transaction->id }}">
            <div class="transaction-header">
                <div class="transaction-info">
                    <h3 class="transaction-number">#{{ $transaction->transaction_number }}</h3>
                    <div class="transaction-meta">
                        <span class="transaction-date">{{ $transaction->transaction_date->format('d/m/Y H:i') }}</span>
                        <span class="customer-name">{{ $transaction->customer_name }}</span>
                        <span class="payment-method">{{ $transaction->payment_method_display }}</span>
                    </div>
                </div>
                <div class="transaction-badges">
                    <span class="badge {{ $transaction->status_badge }}">
                        {{ ucfirst($transaction->status) }}
                    </span>
                </div>
            </div>

            <div class="transaction-details">
                <div class="detail-row">
                    <span class="detail-label">Order:</span>
                    <span class="detail-value">#{{ $transaction->order->order_number }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Kasir:</span>
                    <span class="detail-value">{{ $transaction->kasir->name ?? 'N/A' }}</span>
                </div>
                @if($transaction->payment_method === 'cash')
                <div class="detail-row">
                    <span class="detail-label">Diterima:</span>
                    <span class="detail-value">Rp {{ number_format($transaction->cash_received, 0, ',', '.') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Kembalian:</span>
                    <span class="detail-value">Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</span>
                </div>
                @endif
            </div>

            <div class="transaction-footer">
                <div class="transaction-total">
                    <strong>Total: Rp {{ number_format($transaction->total, 0, ',', '.') }}</strong>
                </div>
                <div class="transaction-actions">
                    <a href="{{ route('kasir.transactions.show', $transaction) }}" class="btn btn-sm btn-outline">Detail</a>
                    <a href="{{ route('kasir.transactions.receipt', $transaction) }}" class="btn btn-sm btn-primary" target="_blank">Struk</a>
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                <line x1="1" y1="10" x2="23" y2="10"></line>
            </svg>
            <h3>Belum ada transaksi</h3>
            <p>Transaksi akan muncul di sini setelah pembayaran diproses</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($transactions->hasPages())
    <div class="pagination-wrapper">
        <div class="pagination-info">
            <div class="pagination-summary">
                <div class="info-text">
                    <i class="fas fa-info-circle"></i>
                    Menampilkan {{ $transactions->firstItem() }} - {{ $transactions->lastItem() }} dari {{ $transactions->total() }} transaksi
                </div>
            </div>
        </div>
        
        <div class="pagination-controls">
            <nav class="pagination-nav">
                {{-- Previous Page Link --}}
                @if ($transactions->onFirstPage())
                    <span class="pagination-btn disabled">
                        <i class="fas fa-chevron-left"></i>
                        <span>Sebelumnya</span>
                    </span>
                @else
                    <a href="{{ $transactions->previousPageUrl() }}" class="pagination-btn">
                        <i class="fas fa-chevron-left"></i>
                        <span>Sebelumnya</span>
                    </a>
                @endif
                
                {{-- Pagination Elements --}}
                <div class="pagination-numbers">
                    @foreach ($transactions->getUrlRange(1, $transactions->lastPage()) as $page => $url)
                        @if ($page == $transactions->currentPage())
                            <span class="pagination-number active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="pagination-number">{{ $page }}</a>
                        @endif
                    @endforeach
                </div>
                
                {{-- Next Page Link --}}
                @if ($transactions->hasMorePages())
                    <a href="{{ $transactions->nextPageUrl() }}" class="pagination-btn">
                        <span>Selanjutnya</span>
                        <i class="fas fa-chevron-right"></i>
                    </a>
                @else
                    <span class="pagination-btn disabled">
                        <span>Selanjutnya</span>
                        <i class="fas fa-chevron-right"></i>
                    </span>
                @endif
            </nav>
        </div>
    </div>
    @endif
</div>

<style>
    :root {
        --primary-color: #f59e0b;
        --primary-dark: #d97706;
        --primary-light: #fbbf24;
        --warning-bg: #fffbeb;
        --warning-border: #fed7aa;
        --warning-text: #92400e;
        --success-color: #10b981;
        --danger-color: #ef4444;
        --gray-200: #e5e7eb;
        --gray-300: #d1d5db;
        --gray-400: #9ca3af;
        --gray-700: #374151;
        --gray-800: #1f2937;
    }

    .transactions-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 1rem;
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

    .page-title {
        font-size: 1.875rem;
        font-weight: 700;
        color: white;
        margin: 0;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border: 2px solid var(--warning-border);
        border-radius: 12px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.2s ease;
    }

    .stat-card:hover {
        border-color: var(--primary-color);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.2);
        transform: translateY(-2px);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        background: var(--warning-bg);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
        border: 2px solid var(--warning-border);
    }

    .stat-content {
        flex: 1;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.875rem;
        color: var(--warning-text);
        font-weight: 500;
    }

    .filters-section {
        background: white;
        border: 2px solid var(--warning-border);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .filters-form {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        align-items: end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .filter-group label {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--warning-text);
    }

    .form-input, .form-select {
        padding: 0.75rem;
        border: 2px solid var(--warning-border);
        border-radius: 8px;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        background: white;
    }

    .form-input:focus, .form-select:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
    }

    .filter-actions {
        display: flex;
        gap: 0.5rem;
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
        border: 2px solid transparent;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        border-color: var(--primary-color);
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, var(--primary-dark) 0%, #b45309 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
    }

    .btn-secondary {
        background: var(--warning-bg);
        color: var(--warning-text);
        border-color: var(--warning-border);
    }

    .btn-secondary:hover {
        background: #fef3c7;
        border-color: var(--primary-color);
    }

    .btn-outline {
        background: transparent;
        color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .btn-outline:hover {
        background: var(--primary-color);
        color: white;
    }

    .btn-sm {
        padding: 0.5rem 1rem;
        font-size: 0.8125rem;
    }

    .transactions-list {
        display: grid;
        gap: 1rem;
    }

    .transaction-card {
        background: white;
        border: 2px solid var(--warning-border);
        border-radius: 12px;
        padding: 1.5rem;
        transition: all 0.2s ease;
    }

    .transaction-card:hover {
        border-color: var(--primary-color);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.2);
        transform: translateY(-1px);
    }

    .transaction-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .transaction-number {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--primary-color);
        margin: 0 0 0.25rem 0;
    }

    .transaction-meta {
        display: flex;
        gap: 1rem;
        font-size: 0.875rem;
        color: var(--warning-text);
        font-weight: 500;
    }

    .transaction-badges {
        display: flex;
        gap: 0.5rem;
    }

    .badge {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .bg-yellow-100 { 
        background: var(--warning-bg); 
        color: var(--warning-text);
        border: 1px solid var(--warning-border);
    }
    .text-yellow-800 { color: var(--warning-text); }
    .bg-green-100 { background: #dcfce7; }
    .text-green-800 { color: #166534; }
    .bg-red-100 { background: #fee2e2; }
    .text-red-800 { color: #991b1b; }

    .transaction-details {
        margin-bottom: 1rem;
        padding: 1rem;
        background: var(--warning-bg);
        border-radius: 8px;
        border: 1px solid var(--warning-border);
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }

    .detail-row:last-child {
        margin-bottom: 0;
    }

    .detail-label {
        color: var(--warning-text);
        font-weight: 500;
    }

    .detail-value {
        font-weight: 600;
        color: #1e293b;
    }

    .transaction-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1rem;
        border-top: 1px solid var(--warning-border);
    }

    .transaction-total {
        font-size: 1.125rem;
        color: var(--primary-color);
    }

    .transaction-actions {
        display: flex;
        gap: 0.75rem;
        align-items: center;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--warning-text);
    }

    .empty-state svg {
        margin: 0 auto 1rem auto;
        color: var(--primary-color);
        opacity: 0.6;
    }

    .empty-state h3 {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--warning-text);
        margin-bottom: 0.5rem;
    }

    /* ===== MULAI: STYLE PAGINATION BARU ===== */
    .pagination-wrapper {
        background: white;
        border: 2px solid var(--warning-border);
        border-radius: 12px;
        padding: 1.5rem;
        margin-top: 2rem;
    }

    .pagination-info {
        margin-bottom: 1rem;
    }

    .pagination-summary {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--gray-200);
    }

    .info-text {
        color: var(--gray-800);
        font-size: 0.875rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-text i {
        color: var(--primary-color);
    }

    .pagination-controls {
        display: flex;
        justify-content: center;
    }

    .pagination-nav {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .pagination-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1rem;
        border: 1px solid var(--gray-300);
        border-radius: 8px;
        color: var(--gray-700);
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 600;
        transition: all 0.2s ease;
        background: white;
    }

    .pagination-btn:hover:not(.disabled) {
        background: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        text-decoration: none;
    }

    .pagination-btn.disabled {
        color: var(--gray-400);
        cursor: not-allowed;
        opacity: 0.6;
    }

    .pagination-numbers {
        display: flex;
        gap: 0.375rem;
    }

    .pagination-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border: 1px solid var(--gray-300);
        border-radius: 8px;
        color: var(--gray-700);
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 600;
        transition: all 0.2s ease;
        background: white;
    }

    .pagination-number:hover {
        background: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        text-decoration: none;
    }

    .pagination-number.active {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        border-color: var(--primary-color);
        font-weight: 700;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }
    /* ===== SELESAI: STYLE PAGINATION BARU ===== */

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .filters-form {
            grid-template-columns: 1fr;
        }
        
        .filter-actions {
            grid-column: 1 / -1;
        }
        
        .transaction-header {
            flex-direction: column;
            gap: 1rem;
        }
        
        .transaction-badges {
            align-self: flex-start;
        }
        
        .transaction-footer {
            flex-direction: column;
            gap: 1rem;
            align-items: stretch;
        }
        
        .transaction-actions {
            justify-content: space-between;
        }

        .pagination-nav {
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .pagination-numbers {
            order: -1;
            width: 100%;
            justify-content: center;
            margin-bottom: 1rem;
        }
    }
</style>

<script>
function refreshTransactions() {
    const refreshBtn = event.target.closest('button');
    const originalText = refreshBtn.innerHTML;
    refreshBtn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="animation: spin 1s linear infinite;"><path d="M21 12a9 9 0 11-6.219-8.56"/></svg> Refreshing...';
    refreshBtn.disabled = true;
    
    setTimeout(() => {
        window.location.reload();
    }, 500);
}

// Load stats
function loadStats() {
    fetch('/kasir/api/transactions/stats')
        .then(response => response.json())
        .then(data => {
            document.getElementById('totalTransactions').textContent = data.total_transactions;
            document.getElementById('totalRevenue').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(data.total_revenue);
            document.getElementById('cashTransactions').textContent = data.cash_transactions;
            document.getElementById('digitalTransactions').textContent = data.digital_transactions;
        })
        .catch(error => {
            console.error('Error loading stats:', error);
        });
}

// Load stats on page load
document.addEventListener('DOMContentLoaded', function() {
    loadStats();
    console.log('✅ Transactions page loaded with yellow theme');
});

// Auto refresh stats every 30 seconds
setInterval(loadStats, 30000);

// Add CSS for spin animation
const style = document.createElement('style');
style.textContent = `
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
`;
document.head.appendChild(style);
</script>
@endsection
