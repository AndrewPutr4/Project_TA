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
                        <td colspan="3" style="text-align:center;">Tidak ada kasir yang sedang bertugas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection