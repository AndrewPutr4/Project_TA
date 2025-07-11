@extends('layouts.app')

@section('title', 'Riwayat Pesanan Saya - Warung Bakso Selingsing')

@section('content')
<main class="main">
    <section class="order-history-section py-5" style="background-color: #f8f9fa;">
        <div class="container" data-aos="fade-up">

            <div class="section-title text-center mb-5">
                <h2>Riwayat Pesanan</h2>
                <p><span>Daftar</span> <span class="description-title">Pesanan Anda</span></p>
            </div>

            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-body p-4 p-md-5">
                    @if($orders->isEmpty())
                        <div class="text-center py-5">
                            <i class="bi bi-cart-x" style="font-size: 5rem; color: #e0e0e0;"></i>
                            <h4 class="mt-4 fw-bold">Anda Belum Memiliki Pesanan</h4>
                            <p class="text-muted">Semua pesanan yang Anda buat di sesi ini akan muncul di sini.</p>
                            <a href="{{ route('home') }}" class="btn btn-warning btn-lg mt-3 rounded-pill px-4">
                                <i class="bi bi-arrow-left me-2"></i> Kembali ke Menu
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">No. Pesanan</th>
                                        <th scope="col">Tanggal</th>
                                        <th scope="col" class="text-end">Total</th>
                                        <th scope="col" class="text-center">Status</th>
                                        <th scope="col" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td class="fw-bold">#{{ $order->id }}</td>
                                            <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                                            <td class="text-end">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                            <td class="text-center">
                                                @php
                                                    $statusClass = '';
                                                    $statusText = '';
                                                    switch($order->status) {
                                                        case 'pending': 
                                                            $statusClass = 'bg-warning text-dark'; 
                                                            $statusText = 'Menunggu';
                                                            break;
                                                        case 'processing': 
                                                            $statusClass = 'bg-info text-dark'; 
                                                            $statusText = 'Diproses';
                                                            break;
                                                        case 'completed': 
                                                            $statusClass = 'bg-success text-white'; 
                                                            $statusText = 'Selesai';
                                                            break;
                                                        case 'cancelled': 
                                                            $statusClass = 'bg-danger text-white'; 
                                                            $statusText = 'Dibatalkan';
                                                            break;
                                                        default: 
                                                            $statusClass = 'bg-secondary text-white'; 
                                                            $statusText = ucfirst($order->status);
                                                            break;
                                                    }
                                                @endphp
                                                <span class="badge rounded-pill px-3 py-2 {{ $statusClass }}">{{ $statusText }}</span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                    <i class="bi bi-eye me-1"></i> Lihat Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
