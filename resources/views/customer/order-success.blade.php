@extends('layouts.app')

@section('title', 'Pesanan Berhasil - Warung Bakso Selingsing')

@section('content')
<main class="main">
    <section class="success-section py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10">
                    <div class="success-card text-center">
                        <!-- Success Icon -->
                        <div class="success-icon mb-4">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        
                        <!-- Success Message -->
                        <h2 class="text-success mb-3">Pesanan Berhasil Dibuat!</h2>
                        <p class="text-muted mb-4">Terima kasih telah memesan di Warung Bakso Selingsing. Pesanan Anda sedang diproses.</p>
                        
                        @if(session('order_number'))
                            <!-- Order Details -->
                            <div class="order-details mb-4">
                                <div class="order-number-card">
                                    <h5 class="mb-2">Nomor Pesanan</h5>
                                    <h3 class="text-warning fw-bold">{{ session('order_number') }}</h3>
                                    <small class="text-muted">Simpan nomor ini untuk referensi pesanan Anda</small>
                                </div>
                                
                                @if(session('estimated_time'))
                                    <div class="estimated-time mt-3">
                                        <div class="time-card">
                                            <i class="bi bi-clock-history me-2"></i>
                                            <span>Estimasi waktu: <strong>{{ session('estimated_time') }} menit</strong></span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                        
                        <!-- Order Summary -->
                        @if(session('order_summary'))
                            <div class="order-summary mb-4">
                                <div class="summary-card">
                                    <h6 class="mb-3">Ringkasan Pesanan</h6>
                                    <div class="summary-items">
                                        @foreach(session('order_summary.items') as $item)
                                            <div class="summary-item d-flex justify-content-between">
                                                <span>{{ $item['name'] }} x{{ $item['quantity'] }}</span>
                                                <span>Rp {{ number_format($item['total'], 0, ',', '.') }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                    <hr>
                                    <div class="summary-total d-flex justify-content-between fw-bold">
                                        <span>Total</span>
                                        <span>Rp {{ number_format(session('order_summary.total'), 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Instructions -->
                        <div class="instructions mb-4">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Langkah Selanjutnya:</strong>
                                <ul class="list-unstyled mt-2 mb-0 text-start">
                                    <li><i class="bi bi-check2 me-2"></i>Pesanan Anda sedang diproses oleh dapur</li>
                                    <li><i class="bi bi-clock me-2"></i>Silakan menunggu konfirmasi dari kasir</li>
                                    <li><i class="bi bi-bell me-2"></i>Anda akan dipanggil saat pesanan siap</li>
                                    <li><i class="bi bi-receipt me-2"></i>Tunjukkan nomor pesanan saat mengambil</li>
                                </ul>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="action-buttons">
                            <a href="{{ route('home') }}" class="btn btn-warning btn-lg me-2">
                                <i class="bi bi-arrow-left me-1"></i> Pesan Lagi
                            </a>
                            @if(session('order_id'))
                                <a href="{{ route('orders.show', session('order_id')) }}" class="btn btn-outline-primary btn-lg">
                                    <i class="bi bi-receipt me-1"></i> Lihat Detail
                                </a>
                            @endif
                        </div>
                        
                        <!-- Contact Info -->
                        <div class="contact-info mt-4">
                            <small class="text-muted">
                                Butuh bantuan? Hubungi kami di <strong>+62 812-3456-7890</strong>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
    body.success-page {
        background: linear-gradient(135deg, #f8fafc 0%, #fffbe6 100%);
        min-height: 100vh;
    }

    .success-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(255,193,7,0.15), 0 4px 16px rgba(0,0,0,0.05);
        padding: 3rem 2.5rem;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }

    .success-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 50px rgba(255,193,7,0.2), 0 6px 20px rgba(0,0,0,0.08);
    }

    .success-icon i {
        font-size: 6rem;
        color: #28a745;
        background: linear-gradient(135deg, #d4fc79 0%, #96e6a1 100%);
        border-radius: 50%;
        padding: 1rem;
        box-shadow: 0 4px 20px rgba(40,167,69,0.2);
        animation: successPulse 2s infinite;
    }

    @keyframes successPulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    .order-number-card {
        background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        border: 2px solid #ffc107;
        border-radius: 15px;
        padding: 1.5rem;
        margin: 1rem 0;
        box-shadow: 0 4px 15px rgba(255,193,7,0.1);
    }

    .order-number-card h3 {
        font-size: 2.5rem;
        letter-spacing: 2px;
        color: #e67e22;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
    }

    .time-card {
        background: #e3f2fd;
        border: 1px solid #2196f3;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        color: #1976d2;
        font-weight: 500;
    }

    .summary-card {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 12px;
        padding: 1.5rem;
        text-align: left;
    }

    .summary-item {
        padding: 0.5rem 0;
        border-bottom: 1px solid #e9ecef;
    }

    .summary-item:last-child {
        border-bottom: none;
    }

    .summary-total {
        font-size: 1.1rem;
        color: #e67e22;
    }

    .instructions .alert {
        text-align: left;
        border-radius: 12px;
        background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
        border: 1px solid #2196f3;
        color: #1565c0;
    }

    .action-buttons .btn {
        border-radius: 25px;
        padding: 1rem 2.5rem;
        font-weight: 600;
        margin: 0.5rem;
        font-size: 1.1rem;
        transition: all 0.3s ease;
    }

    .action-buttons .btn-warning {
        background: linear-gradient(45deg, #ffc107 0%, #ff9800 100%);
        border: none;
        color: #fff;
        box-shadow: 0 4px 15px rgba(255,193,7,0.3);
    }

    .action-buttons .btn-warning:hover {
        background: linear-gradient(45deg, #ffb300 0%, #f57c00 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255,193,7,0.4);
    }

    .action-buttons .btn-outline-primary {
        border: 2px solid #2196f3;
        color: #2196f3;
        background: #fff;
    }

    .action-buttons .btn-outline-primary:hover {
        background: #2196f3;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(33,150,243,0.3);
    }

    .contact-info {
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 10px;
        border-left: 4px solid #28a745;
    }

    @media (max-width: 768px) {
        .success-card {
            padding: 2rem 1.5rem;
            margin: 1rem;
        }
        
        .success-icon i {
            font-size: 4rem;
        }
        
        .order-number-card h3 {
            font-size: 2rem;
        }
        
        .action-buttons .btn {
            display: block;
            width: 100%;
            margin: 0.5rem 0;
        }
    }
</style>

<script>
    // Add some interactive effects
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-hide success message after 5 minutes
        setTimeout(function() {
            const alert = document.querySelector('.alert-info');
            if (alert) {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0.7';
            }
        }, 300000); // 5 minutes
        
        // Add click tracking for buttons
        document.querySelectorAll('.action-buttons .btn').forEach(button => {
            button.addEventListener('click', function() {
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
            });
        });
    });
</script>
@endsection
