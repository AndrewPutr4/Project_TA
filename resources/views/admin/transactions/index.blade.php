@extends('layouts.admin')

@section('content')
<style>
    main {
        padding: 32px 24px;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: calc(100vh - 70px);
    }

    .head-title {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 32px;
        flex-wrap: wrap;
        gap: 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 40px;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(102, 126, 234, 0.3);
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
        background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
        color: white !important;
        text-decoration: none;
        border-radius: 15px;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 8px 25px rgba(86, 171, 47, 0.3);
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
        box-shadow: 0 15px 35px rgba(86, 171, 47, 0.4);
        color: white !important;
        text-decoration: none;
    }

    .btn-download i {
        font-size: 1.2rem;
    }

    .table-data {
        display: grid;
        grid-template-columns: 1fr;
        gap: 24px;
    }

    .order {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .order:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    }

    .order .head {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 25px 30px;
        border-bottom: 1px solid #e9ecef;
        position: relative;
    }

    .order .head h3 {
        margin: 0;
        color: #495057;
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
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        border-bottom: 1px solid #f0f0f0;
        vertical-align: middle;
        font-size: 0.95rem;
    }

    table tbody tr {
        transition: all 0.3s ease;
        position: relative;
    }

    table tbody tr:hover {
        background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 100%);
        transform: scale(1.01);
    }

    table tbody tr:nth-child(even) {
        background: rgba(248, 249, 250, 0.5);
    }

    table tbody tr:nth-child(even):hover {
        background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 100%);
    }

    /* Enhanced styling for customer names */
    table td:first-child {
        font-weight: 600;
        color: #495057;
        position: relative;
    }

    table td:first-child::before {
        content: '👤';
        margin-right: 8px;
        font-size: 1.1rem;
    }

    /* Total amount styling */
    table td:nth-child(2) {
        font-weight: 700;
        font-size: 1.1rem;
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        color: #155724;
        border-radius: 8px;
        text-align: center;
        position: relative;
    }

    table td:nth-child(2)::before {
        content: '💰';
        margin-right: 5px;
    }

    /* Date styling */
    table td:nth-child(3) {
        font-family: 'Courier New', monospace;
        font-weight: 600;
        background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        color: #856404;
        border-radius: 8px;
        text-align: center;
        position: relative;
    }

    table td:nth-child(3)::before {
        content: '📅';
        margin-right: 5px;
    }

    /* Action buttons styling */
    table td:last-child a {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 16px;
        background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);
        color: white !important;
        text-decoration: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(23, 162, 184, 0.3);
        margin-right: 8px;
    }

    table td:last-child a:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(23, 162, 184, 0.4);
        color: white !important;
        text-decoration: none;
    }

    table td:last-child button {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 16px;
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);
    }

    table td:last-child button:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(255, 107, 107, 0.4);
    }

    table td:last-child form {
        display: inline;
    }

    /* Empty state styling */
    table td[colspan="4"] {
        text-align: center;
        padding: 80px 20px;
        color: #6c757d;
        font-size: 1.1rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        position: relative;
    }

    table td[colspan="4"]::before {
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
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
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
    }

    @keyframes sparkle {
        0%, 100% { opacity: 0.5; transform: translateY(-50%) scale(1); }
        50% { opacity: 1; transform: translateY(-50%) scale(1.2); }
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
                border: 1px solid #ccc;
                margin-bottom: 10px;
                padding: 15px;
                border-radius: 10px;
                background: white;
            }

            table td {
                border: none;
                position: relative;
                padding: 10px 10px 10px 50%;
                text-align: left;
            }

            table td:before {
                content: attr(data-label) ": ";
                position: absolute;
                left: 6px;
                width: 45%;
                padding-right: 10px;
                white-space: nowrap;
                font-weight: bold;
                color: #495057;
            }
        }
    }

    /* Loading animation */
    @keyframes shimmer {
        0% { background-position: -200px 0; }
        100% { background-position: calc(200px + 100%) 0; }
    }

    .loading {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
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
</style>

<main>
    <div class="head-title">
        <div class="left">
            <h1>Transaksi Pelanggan</h1>
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

    <!-- Add Filter Form -->
    <div class="filter-section" style="margin-bottom: 20px; background: white; padding: 20px; border-radius: 15px;">
        <form action="{{ route('admin.transactions.index') }}" method="GET" class="filter-form">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
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
                <div style="display: flex; align-items: flex-end;">
                    <button type="submit" class="btn-filter" style="padding: 8px 15px; background: var(--blue); color: white; border: none; border-radius: 8px; cursor: pointer;">
                        <i class='bx bx-filter-alt'></i> Filter
                    </button>
                </div>
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
                            <a href="{{ route('admin.transactions.edit', $transaction) }}" style="color:var(--blue);margin-right:8px;"><i class='bx bx-edit'></i>Edit</a>
                            <form action="{{ route('admin.transactions.destroy', $transaction) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" onclick="return confirm('Yakin hapus?')" style="background:none;border:none;color:var(--red);cursor:pointer;"><i class='bx bx-trash'></i>Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    @if($transactions->isEmpty())
                    <tr>
                        <td colspan="5" style="text-align:center;">Belum ada transaksi.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</main>

<script>
// Add loading animation to buttons
document.querySelectorAll('.btn-download, table a, table button').forEach(btn => {
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
            scrollBtn.style.cssText = `
                position: fixed;
                bottom: 30px;
                right: 30px;
                width: 50px;
                height: 50px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                border: none;
                border-radius: 50%;
                cursor: pointer;
                z-index: 1000;
                box-shadow: 0 5px 20px rgba(102, 126, 234, 0.3);
                transition: all 0.3s ease;
            `;
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
        this.style.boxShadow = '0 5px 15px rgba(0,0,0,0.1)';
    });
    
    row.addEventListener('mouseleave', function() {
        this.style.boxShadow = 'none';
    });
});

// Add transaction value highlighting
document.querySelectorAll('table tbody tr').forEach(row => {
    const totalCell = row.querySelector('td:nth-child(2)');
    if (totalCell) {
        const amount = parseInt(totalCell.textContent.replace(/[^\d]/g, ''));
        if (amount > 500000) {
            totalCell.style.background = 'linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%)';
            totalCell.style.color = '#856404';
            totalCell.style.fontWeight = '800';
        } else if (amount > 100000) {
            totalCell.style.background = 'linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%)';
            totalCell.style.color = '#0c5460';
        }
    }
});

// Add real-time date formatting
document.querySelectorAll('table tbody tr td:nth-child(3)').forEach(dateCell => {
    const dateText = dateCell.textContent.trim();
    const today = new Date().toLocaleDateString('id-ID');
    const cellDate = new Date(dateText.split(' ')[0].split('-').reverse().join('-')).toLocaleDateString('id-ID');
    
    if (cellDate === today) {
        dateCell.style.background = 'linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%)';
        dateCell.style.color = '#155724';
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
</script>
@endsection