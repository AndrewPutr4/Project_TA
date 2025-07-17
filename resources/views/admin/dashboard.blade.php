@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<style>
/* Responsive for dashboard */
@media (max-width: 900px) {
    .head-title {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
        padding: 18px 10px !important;
    }
    .head-title .left h1 {
        font-size: 1.5rem;
    }
    .btn-download {
        width: 100%;
        justify-content: center;
        font-size: 1rem;
        padding: 10px 0;
    }
    .box-info {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    .box-info li {
        flex-direction: row;
        padding: 18px 12px;
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
        padding: 10px 8px;
        font-size: 0.95rem;
    }
}
@media (max-width: 600px) {
    .head-title {
        padding: 10px 4px !important;
    }
    .box-info li {
        flex-direction: column;
        align-items: flex-start;
        padding: 12px 6px;
    }
    .order {
        border-radius: 8px;
    }
    table th, table td {
        padding: 7px 4px;
        font-size: 0.9rem;
    }
    .box-info {
        gap: 8px;
    }
    .table-data {
        gap: 8px;
    }
    /* Tambahan agar dashboard benar-benar full di mobile */
    body, #content, main {
        width: 100vw !important;
        max-width: 100vw !important;
        min-width: 0 !important;
        margin: 0 !important;
        padding: 0 !important;
        box-shadow: none !important;
        border-radius: 0 !important;
        background: #f8f9fa !important;
    }
}
</style>
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
                        <td colspan="3" style="text-align:center;">Tidak ada kasir yang sedang bertugas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection