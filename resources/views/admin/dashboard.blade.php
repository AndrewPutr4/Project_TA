@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="head-title">
    <div class="left">
        <h1>Dashboard</h1>
        <ul class="breadcrumb">
            <li><a href="#">Dashboard</a></li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li><a class="active" href="#">Home</a></li>
        </ul>
    </div>
    <a href="#" class="btn-download">
        <i class='bx bxs-cloud-download'></i>
        <span class="text">Download PDF</span>
    </a>
</div>

<ul class="box-info">
    <li>
        <i class='bx bxs-calendar-check'></i>
        <span class="text">
            <h3>{{ number_format($newOrdersCount) }}</h3>
            <p>New Orders</p>
        </span>
    </li>
    <li>
        <i class='bx bxs-group'></i>
        <span class="text">
            <h3>{{ number_format($visitorsCount) }}</h3>
            <p>Visitors</p>
        </span>
    </li>
    <li>
        <i class='bx bxs-dollar-circle'></i>
        <span class="text">
            <h3>Rp{{ number_format($totalSales, 0, ',', '.') }}</h3>
            <p>Total Sales</p>
        </span>
    </li>
</ul>

<div class="table-data">
    <div class="order">
        <div class="head">
            <h3>Kasir yang Sedang Bertugas</h3>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Nama Kasir</th>
                    <th>Waktu Mulai</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($activeShifts as $shift)
                <tr>
                    <td>{{ $shift->kasir_name ?? 'N/A' }}</td>
                    <td>{{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }}</td>
                    <td><span class="status process">Aktif</span></td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="text-align:center; padding: 2rem; color: #92400e;">
                        <i class='bx bx-user-x' style="font-size: 2rem; display: block; margin-bottom: 0.5rem; opacity: 0.5;"></i>
                        Tidak ada kasir yang sedang bertugas.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
/* Additional responsive styles for dashboard */
@media (max-width: 900px) {
    .head-title {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
        padding: 1.5rem;
    }
    
    .head-title .left h1 {
        font-size: 2rem;
    }
    
    .btn-download {
        width: 100%;
        justify-content: center;
        font-size: 1rem;
        padding: 12px 0;
    }
    
    .box-info {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .box-info li {
        flex-direction: row;
        padding: 20px 16px;
        text-align: left;
    }
    
    .table-data {
        flex-direction: column;
        gap: 16px;
    }
    
    .order {
        border-radius: 12px;
        padding: 0;
    }
    
    table th, table td {
        padding: 12px 16px;
        font-size: 0.9rem;
    }
}

@media (max-width: 600px) {
    .head-title {
        padding: 1rem;
    }
    
    .head-title .left h1 {
        font-size: 1.75rem;
    }
    
    .box-info li {
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 16px 12px;
    }
    
    .box-info li i {
        font-size: 2.5rem;
        padding: 16px;
        margin-bottom: 8px;
    }
    
    .order {
        border-radius: 8px;
    }
    
    table th, table td {
        padding: 10px 12px;
        font-size: 0.85rem;
    }
    
    .box-info {
        gap: 12px;
    }
    
    .table-data {
        gap: 12px;
    }
}

@media (max-width: 480px) {
    .head-title {
        padding: 0.75rem;
    }
    
    .head-title .left h1 {
        font-size: 1.5rem;
    }
    
    .box-info li {
        padding: 12px 8px;
    }
    
    .box-info li .text h3 {
        font-size: 1.5rem;
    }
    
    .box-info li .text p {
        font-size: 0.9rem;
    }
    
    table th, table td {
        padding: 8px 10px;
        font-size: 0.8rem;
    }
    
    .table-data .head {
        padding: 16px;
    }
    
    .table-data .head h3 {
        font-size: 1.2rem;
    }
}

/* Loading animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.box-info li {
    animation: fadeInUp 0.6s ease forwards;
}

.box-info li:nth-child(1) { animation-delay: 0.1s; }
.box-info li:nth-child(2) { animation-delay: 0.2s; }
.box-info li:nth-child(3) { animation-delay: 0.3s; }

.table-data .order {
    animation: fadeInUp 0.6s ease forwards;
    animation-delay: 0.4s;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add hover effects to stat cards
    const statCards = document.querySelectorAll('.box-info li');
    statCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
    
    // Add click animation to download button
    const downloadBtn = document.querySelector('.btn-download');
    if (downloadBtn) {
        downloadBtn.addEventListener('click', function(e) {
            e.preventDefault();
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
        });
    }
    
    console.log('✅ Dashboard loaded with yellow theme and responsive design');
});
</script>
@endsection
