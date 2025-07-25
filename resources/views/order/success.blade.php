{{-- resources/views/order/success.blade.php --}}
@extends('layouts.app')

@section('title', 'Pesanan Berhasil - Warung Bakso Selingsing')
@section('body-class', 'success-page')

{{-- Push CSS dan JS jika sudah dipindahkan --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/success.css') }}">
@endpush

@section('content')
<main class="main">
    <section class="success-section py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10">
                    <div class="success-card text-center">
                        <div class="success-icon mb-4">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        
                        <h2 class="text-success mb-3">🎉 Pesanan Berhasil Dibuat!</h2>
                        <p class="text-muted mb-4">Terima kasih telah memesan. Pesanan Anda sedang kami siapkan.</p>
                        
                        {{-- PERBAIKAN: Gunakan objek $order --}}
                        <div class="order-details mb-4">
                            <div class="order-number-card">
                                <h5 class="mb-2">📋 Nomor Pesanan</h5>
                                <h3 class="text-warning fw-bold">{{ $order->order_number }}</h3>
                                <small class="text-muted">Simpan nomor ini untuk referensi pesanan Anda</small>
                            </div>
                            
                            <div class="estimated-time mt-3">
                                <div class="time-card">
                                    <i class="bi bi-clock-history me-2"></i>
                                    <span>⏰ Estimasi waktu: <strong>15 menit</strong></span> {{-- Bisa dibuat dinamis jika perlu --}}
                                </div>
                            </div>
                        </div>
                        
                        {{-- PERBAIKAN: Gunakan relasi $order->items --}}
                        <div class="order-summary mb-4">
                            <div class="summary-card">
                                <h6 class="mb-3">📊 Ringkasan Pesanan</h6>
                                <div class="summary-items">
                                    @forelse($order->items as $item)
                                        <div class="summary-item d-flex justify-content-between">
                                            <span>🍜 {{ $item->menu_name }} x{{ $item->quantity }}</span>
                                            <span class="fw-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                        </div>
                                    @empty
                                        <p>Tidak ada item dalam pesanan ini.</p>
                                    @endforelse
                                </div>
                                <hr>
                                <div class="summary-total d-flex justify-content-between fw-bold">
                                    <span>💰 Total</span>
                                    <span class="text-warning">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        
                        {{-- PERBAIKAN: Tampilkan instruksi secara dinamis --}}
                        <div class="instructions mb-4">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>📋 Langkah Selanjutnya:</strong>
                                @if($order->payment_method == 'cash')
                                    <ul class="list-unstyled mt-2 mb-0 text-start">
                                        <li><i class="bi bi-check2 me-2"></i>Pesanan Anda sedang diproses oleh dapur.</li>
                                        <li><i class="bi bi-cash me-2"></i>Silakan menuju ke kasir untuk melakukan pembayaran tunai.</li>
                                    </ul>
                                @else {{-- Untuk 'cashless' / QRIS --}}
                                    <p class="mt-2 mb-0">Pembayaran Anda berhasil! Pesanan Anda sedang kami proses. Silakan tunggu.</p>
                                @endif
                            </div>
                        </div>

                        <div class="action-buttons">
                            <a href="{{ route('customer.welcome') }}" class="btn btn-warning btn-lg me-2">
                                <i class="bi bi-arrow-left me-1"></i> 🍜 Pesan Lagi
                            </a>
                            <a href="{{ route('order.show', $order->id) }}" class="btn btn-outline-primary btn-lg">
                                <i class="bi bi-receipt me-1"></i> 📄 Lihat Detail
                            </a>
                        </div>
                        
                        <div class="contact-info mt-4">
                            <small class="text-muted">Butuh bantuan? Hubungi kami di <strong>📞 0813-5375-9061</strong></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@push('scripts')
    <script src="{{ asset('js/success.js') }}"></script>
@endpush