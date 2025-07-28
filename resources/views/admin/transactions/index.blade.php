@extends('layouts.admin')

@section('content')
<style>
    main {
        padding: 32px 24px;
        background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
        min-height: calc(100vh - 70px);
    }

    .head-title {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 32px;
        flex-wrap: wrap;
        gap: 20px;
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        padding: 40px;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(245, 158, 11, 0.3);
        position: relative;
        overflow: hidden;
    }

    .head-title::before {
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

    .head-title .left {
        position: relative;
        z-index: 2;
    }

    .head-title .left h1 {
        font-size: 2.8rem;
        font-weight: 800;
        margin: 0 0 15px 0;
        display: flex;
        align-items: center;
        gap: 15px;
        color: white;
    }

    .head-title .left h1::before {
        content: '💳';
        font-size: 2.5rem;
    }

    .breadcrumb {
        display: flex;
        align-items: center;
        list-style: none;
        gap: 8px;
        margin: 0;
        padding: 0;
    }

    .breadcrumb li a {
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        padding: 5px 10px;
        border-radius: 8px;
    }

    .breadcrumb li a:hover,
    .breadcrumb li a.active {
        color: white;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
    }

    .breadcrumb li i {
        color: rgba(255, 255, 255, 0.6);
        font-size: 0.9rem;
    }

    .btn-download {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 15px 30px;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white !important;
        text-decoration: none;
        border-radius: 15px;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
        position: relative;
        overflow: hidden;
        z-index: 2;
    }

    .btn-download::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }

    .btn-download:hover::before {
        left: 100%;
    }

    .btn-download:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(16, 185, 129, 0.4);
        color: white !important;
        text-decoration: none;
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
    }

    .btn-download i {
        font-size: 1.2rem;
    }

    .filter-section {
        margin-bottom: 20px;
        background: white;
        padding: 25px 30px;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(245, 158, 11, 0.1);
        border: 2px solid #fed7aa;
    }

    .filter-form {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        align-items: end;
    }

    .filter-form label {
        display: block;
        font-weight: 600;
        color: #92400e;
        margin-bottom: 8px;
        font-size: 0.9rem;
    }

    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #fed7aa;
        border-radius: 10px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: #fffbeb;
        color: #92400e;
    }

    .form-control:focus {
        outline: none;
        border-color: #f59e0b;
        background: white;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
    }

    .btn-filter {
        padding: 12px 20px;
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
    }

    .btn-filter:hover {
        background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(245, 158, 11, 0.4);
    }

    .table-data {
        display: grid;
        grid-template-columns: 1fr;
        gap: 24px;
    }

    .order {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(245, 158, 11, 0.1);
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid #fed7aa;
    }

    .order:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 50px rgba(245, 158, 11, 0.15);
        border-color: #f59e0b;
    }

    .order .head {
        background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
        padding: 25px 30px;
        border-bottom: 1px solid #fed7aa;
        position: relative;
    }

    .order .head h3 {
        margin: 0;
        color: #92400e;
        font-size: 1.5rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .order .head h3::before {
        content: '📊';
        font-size: 1.3rem;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
    }

    table thead {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }

    table th {
        padding: 20px 25px;
        text-align: left;
        font-weight: 600;
        font-size: 0.95rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    table td {
        padding: 20px 25px;
        border-bottom: 1px solid #fed7aa;
        vertical-align: middle;
        font-size: 0.95rem;
    }

    table tbody tr {
        transition: all 0.3s ease;
        position: relative;
    }

    table tbody tr:hover {
        background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
        transform: scale(1.01);
    }

    table tbody tr:nth-child(even) {
        background: rgba(255, 251, 235, 0.5);
    }

    table tbody tr:nth-child(even):hover {
        background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
    }

    /* Enhanced styling for customer names */
    table td:first-child {
        font-weight: 600;
        color: #92400e;
        position: relative;
    }

    table td:first-child::before {
        content: '👤';
        margin-right: 8px;
        font-size: 1.1rem;
    }

    /* Kasir column styling */
    table td:nth-child(2) {
        font-weight: 600;
        color: #92400e;
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        border-radius: 8px;
        text-align: center;
        position: relative;
    }

    table td:nth-child(2)::before {
        content: '👨‍💼';
        margin-right: 5px;
    }

    /* Total amount styling */
    table td:nth-child(3) {
        font-weight: 700;
        font-size: 1.1rem;
        background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
        color: #166534;
        border-radius: 8px;
        text-align: center;
        position: relative;
    }

    table td:nth-child(3)::before {
        content: '💰';
        margin-right: 5px;
    }

    /* Date styling */
    table td:nth-child(4) {
        font-family: 'Courier New', monospace;
        font-weight: 600;
        background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
        color: #92400e;
        border-radius: 8px;
        text-align: center;
        position: relative;
        border: 2px solid #fed7aa;
    }

    table td:nth-child(4)::before {
        content: '📅';
        margin-right: 5px;
    }

    /* Action buttons styling */
    table td:last-child a {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 16px;
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white !important;
        text-decoration: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        margin-right: 8px;
        border: 2px solid transparent;
    }

    table td:last-child a:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
        color: white !important;
        text-decoration: none;
        background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
    }

    table td:last-child button {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 16px;
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
        border: 2px solid transparent;
    }

    table td:last-child button:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(239, 68, 68, 0.4);
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    }

    table td:last-child form {
        display: inline;
    }

    /* Empty state styling */
    table td[colspan="5"] {
        text-align: center;
        padding: 80px 20px;
        color: #92400e;
        font-size: 1.1rem;
        background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
        position: relative;
        border: 2px dashed #fed7aa;
        border-radius: 12px;
    }

    table td[colspan="5"]::before {
        content: '💳';
        display: block;
        font-size: 4rem;
        margin-bottom: 20px;
        opacity: 0.3;
    }

    /* Add transaction status indicators */
    table tbody tr::after {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    table tbody tr:hover::after {
        opacity: 1;
    }

    /* Add floating action for high value transactions */
    table tbody tr[data-high-value] {
        position: relative;
    }

    table tbody tr[data-high-value]::before {
        content: '⭐';
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 1.2rem;
        animation: sparkle 2s infinite;
        z-index: 1;
    }

    @keyframes sparkle {
        0%, 100% { opacity: 0.5; transform: translateY(-50%) scale(1); }
        50% { opacity: 1; transform: translateY(-50%) scale(1.2); }
    }

    /* Scroll to top button */
    .scroll-top {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        z-index: 1000;
        box-shadow: 0 5px 20px rgba(245, 158, 11, 0.3);
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .scroll-top:hover {
        transform: scale(1.1);
        box-shadow: 0 8px 25px rgba(245, 158, 11, 0.4);
        background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
    }

    /* Responsive design */
    @media (max-width: 768px) {
        main {
            padding: 20px 15px;
        }

        .head-title {
            padding: 25px 20px;
            flex-direction: column;
            align-items: flex-start;
        }

        .head-title .left h1 {
            font-size: 2rem;
        }

        .btn-download {
            width: 100%;
            justify-content: center;
        }

        .filter-section {
            padding: 20px;
        }

        .filter-form {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        table th,
        table td {
            padding: 15px 10px;
            font-size: 0.85rem;
        }

        table td:last-child {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        table td:last-child a,
        table td:last-child button {
            width: 100%;
            justify-content: center;
            margin-right: 0;
        }

        /* Stack table cells on very small screens */
        @media (max-width: 480px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }

            table thead tr {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }

            table tr {
                border: 2px solid #fed7aa;
                margin-bottom: 15px;
                padding: 20px;
                border-radius: 15px;
                background: white;
                box-shadow: 0 4px 15px rgba(245, 158, 11, 0.1);
            }

            table td {
                border: none;
                position: relative;
                padding: 12px 12px 12px 50%;
                text-align: left;
                border-bottom: 1px solid #fed7aa;
            }

            table td:last-child {
                border-bottom: none;
            }

            table td:before {
                content: attr(data-label) ": ";
                position: absolute;
                left: 6px;
                width: 45%;
                padding-right: 10px;
                white-space: nowrap;
                font-weight: bold;
                color: #92400e;
            }
        }
    }

    /* Loading animation */
    @keyframes shimmer {
        0% { background-position: -200px 0; }
        100% { background-position: calc(200px + 100%) 0; }
    }

    .loading {
        background: linear-gradient(90deg, #fffbeb 25%, #fef3c7 50%, #fffbeb 75%);
        background-size: 200px 100%;
        animation: shimmer 1.5s infinite;
    }

    /* Add subtle animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .order {
        animation: fadeInUp 0.6s ease forwards;
    }

    .filter-section {
        animation: fadeInUp 0.4s ease forwards;
    }

    /* Delete Confirmation Modal Styles */
    .delete-modal {
        visibility: hidden; /* Controlled by JS */
        opacity: 0; /* Controlled by JS for fade effect */
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex; /* Always flex, visibility/opacity controls show/hide */
        justify-content: center;
        align-items: center;
        z-index: 1000;
        backdrop-filter: blur(5px);
        transition: visibility 0.3s ease, opacity 0.3s ease; /* Smooth transition */
    }

    .delete-modal.show { /* Class added by JS to show the modal */
        visibility: visible;
        opacity: 1;
    }

    .delete-modal .modal-content {
        background: white;
        border-radius: 15px;
        width: 90%;
        max-width: 500px;
        animation: modalSlideIn 0.3s ease forwards; /* Animation for content */
        border: 2px solid var(--warning-border);
        overflow: hidden; /* Ensures rounded corners on children */
    }

    .delete-modal .modal-header {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); /* Red gradient */
        color: white;
        padding: 1.5rem;
        border-radius: 15px 15px 0 0;
        text-align: center;
        position: relative;
    }

    .delete-modal .warning-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        display: block;
    }

    .delete-modal .modal-body {
        padding: 2rem;
        text-align: center;
    }

    .delete-modal .warning-text {
        color: #ef4444;
        font-weight: 600;
        margin-top: 1rem;
    }

    .delete-modal .modal-footer {
        padding: 1.5rem;
        display: flex;
        justify-content: center; /* Center buttons */
        gap: 1rem; /* Space between buttons */
        border-top: 1px solid #e5e7eb;
        background: #f8fafc; /* Light background for footer */
        border-radius: 0 0 15px 15px; /* Match content border-radius */
    }

    .delete-modal .modal-footer .btn { /* General button style within modal footer */
        padding: 12px 24px;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .delete-modal .modal-footer .btn-secondary { /* Style for 'Batal' button */
        background: #6c757d; /* Gray */
        color: white;
    }

    .delete-modal .modal-footer .btn-secondary:hover {
        background: #5a6268; /* Darker gray on hover */
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    .delete-modal .modal-footer .btn-danger { /* Style for 'Hapus Menu' button */
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); /* Red gradient */
        color: white;
    }

    .delete-modal .modal-footer .btn-danger:hover {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); /* Darker red on hover */
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
    }
</style>


<main>
    <div class="head-title">
        <div class="left">
            <h1>Laporan Transaksi</h1>
            <ul class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Transaksi</a></li>
            </ul>
        </div>
        <a href="{{ route('admin.transactions.export') }}" class="btn-download">
            <i class='bx bx-download'></i>
            <span class="text">Download PDF</span>
        </a>
    </div>

    <!-- Filter Form -->
    <div class="filter-section">
        <form action="{{ route('admin.transactions.index') }}" method="GET" class="filter-form">
            <div>
                <label>Dari Tanggal:</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
            </div>
            <div>
                <label>Sampai Tanggal:</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
            </div>
            <div>
                <label>Kasir:</label>
                <select name="kasir_id" class="form-control">
                    <option value="">Semua Kasir</option>
                    @foreach($kasirs as $kasir)
                        <option value="{{ $kasir->id }}" {{ request('kasir_id') == $kasir->id ? 'selected' : '' }}>
                            {{ $kasir->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit" class="btn-filter">
                    <i class='bx bx-filter-alt'></i> Filter
                </button>
            </div>
        </form>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Daftar Transaksi</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Nama Pelanggan</th>
                        <th>Kasir</th>
                        <th>Total</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $transaction)
                    <tr @if($transaction->total > 100000) data-high-value="true" @endif>
                        <td data-label="Nama Pelanggan">{{ $transaction->customer_name }}</td>
                        <td data-label="Kasir">{{ $transaction->kasir->name ?? 'N/A' }}</td>
                        <td data-label="Total">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                        <td data-label="Tanggal">{{ $transaction->created_at->format('d-m-Y H:i') }}</td>
                        <td data-label="Aksi">
                            {{-- Modifikasi tombol hapus untuk memicu modal --}}
                            <button type="button" class="btn-delete" onclick="showDeleteModal('{{ $transaction->id }}', '{{ $transaction->transaction_number }}')">
                                <i class='bx bx-trash'></i>Hapus
                            </button>
                        </td>
                    </tr>
                    @endforeach
                    @if($transactions->isEmpty())
                    <tr>
                        <td colspan="5" style="text-align:center;">
                            <strong>Belum ada transaksi.</strong><br>
                            <small>Transaksi akan muncul di sini setelah kasir melakukan penjualan.</small>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</main>

{{-- Delete Confirmation Modal --}}
<div class="delete-modal" id="deleteModal">
    <div class="modal-content">
        <div class="modal-header">
            <i class='bx bx-error-circle warning-icon'></i>
            <h3>Konfirmasi Hapus</h3>
        </div>
        <div class="modal-body">
            <p>Apakah Anda yakin ingin menghapus transaksi "<span id="transactionToDelete"></span>"?</p>
            <p class="warning-text">Tindakan ini tidak dapat dibatalkan!</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-cancel" onclick="closeDeleteModal()">
                <i class='bx bx-x'></i> Batal
            </button>
            <form id="deleteForm" method="POST" style="display: inline;">
                @csrf 
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class='bx bx-trash'></i> Hapus Transaksi
                </button>
            </form>
        </div>
    </div>
</div>

<script>
// Add loading animation to buttons
document.querySelectorAll('.btn-download, .btn-filter, table a, table button').forEach(btn => {
    btn.addEventListener('click', function() {
        this.style.transform = 'scale(0.95)';
        setTimeout(() => {
            this.style.transform = 'scale(1)';
        }, 150);
    });
});

// Add smooth scroll to top functionality
window.addEventListener('scroll', function() {
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    if (scrollTop > 300) {
        if (!document.querySelector('.scroll-top')) {
            const scrollBtn = document.createElement('button');
            scrollBtn.className = 'scroll-top';
            scrollBtn.innerHTML = '<i class="bx bx-up-arrow-alt"></i>';
            scrollBtn.addEventListener('click', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
            scrollBtn.addEventListener('mouseenter', () => {
                scrollBtn.style.transform = 'scale(1.1)';
            });
            scrollBtn.addEventListener('mouseleave', () => {
                scrollBtn.style.transform = 'scale(1)';
            });
            document.body.appendChild(scrollBtn);
        }
    } else {
        const scrollBtn = document.querySelector('.scroll-top');
        if (scrollBtn) {
            scrollBtn.remove();
        }
    }
});

// Enhanced table interactions
document.querySelectorAll('table tbody tr').forEach(row => {
    row.addEventListener('mouseenter', function() {
        this.style.boxShadow = '0 8px 25px rgba(245, 158, 11, 0.15)';
    });
    
    row.addEventListener('mouseleave', function() {
        this.style.boxShadow = 'none';
    });
});

// Add transaction value highlighting
document.querySelectorAll('table tbody tr').forEach(row => {
    const totalCell = row.querySelector('td:nth-child(3)');
    if (totalCell) {
        const amount = parseInt(totalCell.textContent.replace(/[^\d]/g, ''));
        if (amount > 500000) {
            totalCell.style.background = 'linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%)';
            totalCell.style.color = '#92400e';
            totalCell.style.fontWeight = '800';
            totalCell.style.border = '2px solid #f59e0b';
        } else if (amount > 100000) {
            totalCell.style.background = 'linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%)';
            totalCell.style.color = '#166534';
            totalCell.style.border = '2px solid #bbf7d0';
        }
    }
});

// Add real-time date formatting
document.querySelectorAll('table tbody tr td:nth-child(4)').forEach(dateCell => {
    const dateText = dateCell.textContent.trim();
    const today = new Date().toLocaleDateString('id-ID');
    const cellDate = new Date(dateText.split(' ')[0].split('-').reverse().join('-')).toLocaleDateString('id-ID');
    
    if (cellDate === today) {
        dateCell.style.background = 'linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%)';
        dateCell.style.color = '#166534';
        dateCell.style.border = '2px solid #10b981';
        dateCell.innerHTML = '🔥 ' + dateText + ' (Hari ini)';
    }
});

// Add transaction counter animation
function animateTransactionCount() {
    const rows = document.querySelectorAll('table tbody tr:not([data-empty])');
    let count = 0;
    
    rows.forEach((row, index) => {
        setTimeout(() => {
            row.style.opacity = '0';
            row.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                row.style.transition = 'all 0.5s ease';
                row.style.opacity = '1';
                row.style.transform = 'translateY(0)';
                count++;
            }, 100);
        }, index * 100);
    });
}

// Run animation when page loads
window.addEventListener('load', () => {
    setTimeout(animateTransactionCount, 500);
});

// Add form validation
document.querySelector('.filter-form').addEventListener('submit', function(e) {
    const startDate = document.querySelector('input[name="start_date"]').value;
    const endDate = document.querySelector('input[name="end_date"]').value;
    
    if (startDate && endDate && new Date(startDate) > new Date(endDate)) {
        e.preventDefault();
        alert('Tanggal mulai tidak boleh lebih besar dari tanggal akhir!');
        return false;
    }
});

// Delete Confirmation Modal Script
function showDeleteModal(transactionId, transactionNumber) {
    const modal = document.getElementById('deleteModal');
    const transactionToDelete = document.getElementById('transactionToDelete');
    const deleteForm = document.getElementById('deleteForm');
    
    transactionToDelete.textContent = '#' + transactionNumber; // Display transaction number
    deleteForm.action = `/admin/transactions/${transactionId}`; // Set form action
    modal.classList.add('show'); // Show modal with animation
    document.body.style.overflow = 'hidden'; // Prevent page scrolling
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.classList.remove('show'); // Hide modal with animation
    document.body.style.overflow = ''; // Restore page scrolling
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('deleteModal');
    if (event.target == modal) {
        closeDeleteModal();
    }
}

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});

console.log('✅ Transactions page loaded with yellow theme');
</script>
@endsection
