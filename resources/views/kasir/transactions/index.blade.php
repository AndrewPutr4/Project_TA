@extends('layouts.kasir')

@section('title', 'Daftar Transaksi')

@section('content')
<div class="transactions-container">
    <!-- Header -->
    <div class="page-header">
        <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Orderan Pelanggan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No. Order</th>
                            <th>Nama Pelanggan</th>
                            <th>No. Meja</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Waktu Pesan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Loop untuk setiap pesanan --}}
                        @forelse ($orders as $order)
                        <tr>
                            <td>{{ $order->order_number }}</td>
                            <td>{{ $order->customer_name }}</td>
                            <td>{{ $order->table_number ?? 'Take Away' }}</td>
                            <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                            <td>
                                {{-- Contoh badge untuk status --}}
                                @if($order->status == 'pending')
                                    <span class="badge badge-warning">{{ ucfirst($order->status) }}</span>
                                @elseif($order->status == 'completed')
                                    <span class="badge badge-success">{{ ucfirst($order->status) }}</span>
                                @else
                                    <span class="badge badge-secondary">{{ ucfirst($order->status) }}</span>
                                @endif
                            </td>
                            <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                            <td>
                                <a href="#" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada pesanan masuk.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 12V8H6a2 2 0 0 1-2-2c0-1.1.9-2 2-2h12v4"></path>
                    <path d="M4 6v12c0 1.1.9 2 2 2h14v-4"></path>
                    <path d="M18 12a2 2 0 0 0-2 2c0 1.1.9 2 2 2h4v-4h-4z"></path>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value" id="cashTransactions">0</div>
                <div class="stat-label">Pembayaran Tunai</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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
        {{ $transactions->appends(request()->query())->links() }}
    </div>
    @endif
</div>

<style>
.transactions-container {
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

.page-title {
    font-size: 1.875rem;
    font-weight: 700;
    color: #1e293b;
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
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.stat-icon {
    width: 48px;
    height: 48px;
    background: #eff6ff;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #3b82f6;
}

.stat-content {
    flex: 1;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.875rem;
    color: #64748b;
}

.filters-section {
    background: white;
    border: 1px solid #e2e8f0;
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
    color: #374151;
}

.form-input, .form-select {
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 0.875rem;
    transition: border-color 0.2s ease;
}

.form-input:focus, .form-select:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
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
}

.btn-primary {
    background: #3b82f6;
    color: white;
}

.btn-primary:hover {
    background: #2563eb;
}

.btn-secondary {
    background: #f1f5f9;
    color: #64748b;
    border: 1px solid #e2e8f0;
}

.btn-secondary:hover {
    background: #e2e8f0;
}

.btn-outline {
    background: transparent;
    color: #3b82f6;
    border: 1px solid #3b82f6;
}

.btn-outline:hover {
    background: #3b82f6;
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
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 1.5rem;
    transition: all 0.2s ease;
}

.transaction-card:hover {
    border-color: #cbd5e1;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
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
    color: #1e293b;
    margin: 0 0 0.25rem 0;
}

.transaction-meta {
    display: flex;
    gap: 1rem;
    font-size: 0.875rem;
    color: #64748b;
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

.bg-yellow-100 { background: #fef3c7; }
.text-yellow-800 { color: #92400e; }
.bg-green-100 { background: #dcfce7; }
.text-green-800 { color: #166534; }
.bg-red-100 { background: #fee2e2; }
.text-red-800 { color: #991b1b; }

.transaction-details {
    margin-bottom: 1rem;
    padding: 1rem;
    background: #f8fafc;
    border-radius: 8px;
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
    color: #64748b;
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
    border-top: 1px solid #f1f5f9;
}

.transaction-total {
    font-size: 1.125rem;
    color: #1e293b;
}

.transaction-actions {
    display: flex;
    gap: 0.75rem;
    align-items: center;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: #64748b;
}

.empty-state svg {
    margin: 0 auto 1rem auto;
    color: #cbd5e1;
}

.empty-state h3 {
    font-size: 1.25rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
}

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
}
</style>

<script>
function refreshTransactions() {
    window.location.reload();
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
});

// Auto refresh stats every 30 seconds
setInterval(loadStats, 30000);
</script>
@endsection
