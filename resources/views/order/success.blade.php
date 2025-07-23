@extends('layouts.app')

@section('title', 'Pesanan Berhasil - Warung Bakso Selingsing')
@section('body-class', 'success-page')

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
                        <h2 class="text-success mb-3">🎉 Pesanan Berhasil Dibuat!</h2>
                        <p class="text-muted mb-4">Terima kasih telah memesan di Warung Bakso Selingsing. Pesanan Anda sedang diproses.</p>
                        
                        @if(session('order_number'))
                            <!-- Order Details -->
                            <div class="order-details mb-4">
                                <div class="order-number-card">
                                    <h5 class="mb-2">📋 Nomor Pesanan</h5>
                                    <h3 class="text-warning fw-bold">{{ session('order_number') }}</h3>
                                    <small class="text-muted">Simpan nomor ini untuk referensi pesanan Anda</small>
                                </div>
                                
                                @if(session('estimated_time'))
                                    <div class="estimated-time mt-3">
                                        <div class="time-card">
                                            <i class="bi bi-clock-history me-2"></i>
                                            <span>⏰ Estimasi waktu: <strong>{{ session('estimated_time') }} menit</strong></span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                        
                        <!-- Order Summary -->
                        @if(session('order_summary'))
                            <div class="order-summary mb-4">
                                <div class="summary-card">
                                    <h6 class="mb-3">📊 Ringkasan Pesanan</h6>
                                    <div class="summary-items">
                                        @foreach(session('order_summary.items') as $item)
                                            <div class="summary-item d-flex justify-content-between">
                                                <span>🍜 {{ $item['name'] }} x{{ $item['quantity'] }}</span>
                                                <span class="fw-bold">Rp {{ number_format($item['total'], 0, ',', '.') }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                    <hr>
                                    <div class="summary-total d-flex justify-content-between fw-bold">
                                        <span>💰 Total</span>
                                        <span class="text-warning">Rp {{ number_format(session('order_summary.total'), 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Payment Instructions -->
                        <div class="payment-instructions mb-4">
                            <div class="payment-card">
                                <div class="payment-icon">
                                    <i class="bi bi-cash-coin"></i>
                                </div>
                                <h5>Pembayaran Tunai</h5>
                                <p>Silahkan menuju ke kasir untuk melakukan pembayaran tunai</p>
                            </div>
                        </div>
                        
                        <!-- Instructions -->
                        <div class="instructions mb-4">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>📋 Langkah Selanjutnya:</strong>
                                <ul class="list-unstyled mt-2 mb-0 text-start">
                                    <li><i class="bi bi-check2 me-2"></i>Pesanan Anda sedang diproses oleh dapur</li>
                                    <li><i class="bi bi-cash me-2"></i>Silahkan menuju ke kasir untuk melakukan pembayaran tunai</li>
                                </ul>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="action-buttons">
                            <a href="{{ route('customer.welcome') }}" class="btn btn-warning btn-lg me-2">
                                <i class="bi bi-arrow-left me-1"></i> 🍜 Pesan Lagi
                            </a>
                            @if(session('order_number'))
                                <a href="{{ route('order.show', session('order_number')) }}" class="btn btn-outline-primary btn-lg">
                                    <i class="bi bi-receipt me-1"></i> 📄 Lihat Detail
                                </a>
                            @endif
                        </div>
                        
                        <!-- Contact Info -->
                        <div class="contact-info mt-4">
                            <small class="text-muted">
                                Butuh bantuan? Hubungi kami di <strong>📞 0813-5375-9061</strong>
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
        background: linear-gradient(135deg, #fffbf0 0%, #fef3c7 100%);
        min-height: 100vh;
    }

    .success-card {
        background: #fff;
        border-radius: 25px;
        box-shadow: 0 15px 50px rgba(251, 191, 36, 0.15), 0 8px 25px rgba(0,0,0,0.05);
        padding: 3.5rem 3rem;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
        border: 2px solid rgba(251, 191, 36, 0.1);
        position: relative;
        overflow: hidden;
    }

    .success-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(251, 191, 36, 0.05) 0%, transparent 70%);
        animation: rotate 20s linear infinite;
    }

    @keyframes rotate {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .success-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 60px rgba(251, 191, 36, 0.2), 0 10px 30px rgba(0,0,0,0.08);
    }

    .success-icon i {
        font-size: 7rem;
        color: #28a745;
        background: linear-gradient(135deg, #d4fc79 0%, #96e6a1 100%);
        border-radius: 50%;
        padding: 1.5rem;
        box-shadow: 0 8px 30px rgba(40,167,69,0.3);
        animation: successPulse 2s infinite;
        position: relative;
        z-index: 2;
    }

    @keyframes successPulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.08); }
    }

    .order-number-card {
        background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        border: 3px solid #fbbf24;
        border-radius: 20px;
        padding: 2rem;
        margin: 1.5rem 0;
        box-shadow: 0 8px 25px rgba(251, 191, 36, 0.2);
        position: relative;
        overflow: hidden;
    }

    .order-number-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        animation: shimmer 3s infinite;
    }

    @keyframes shimmer {
        0% { left: -100%; }
        100% { left: 100%; }
    }

    .order-number-card h3 {
        font-size: 3rem;
        letter-spacing: 3px;
        color: #d97706;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        font-weight: 900;
    }

    .time-card {
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        border: 2px solid #2196f3;
        border-radius: 15px;
        padding: 1rem 1.5rem;
        color: #1976d2;
        font-weight: 600;
        box-shadow: 0 4px 15px rgba(33, 150, 243, 0.2);
    }

    .summary-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #fff 100%);
        border: 2px solid #fbbf24;
        border-radius: 18px;
        padding: 2rem;
        text-align: left;
        box-shadow: 0 6px 20px rgba(251, 191, 36, 0.1);
    }

    .summary-item {
        padding: 0.75rem 0;
        border-bottom: 1px solid #e9ecef;
        font-size: 1.05rem;
    }

    .summary-item:last-child {
        border-bottom: none;
    }

    .summary-total {
        font-size: 1.3rem;
        color: #d97706;
        background: rgba(251, 191, 36, 0.1);
        padding: 1rem;
        border-radius: 10px;
        margin-top: 1rem;
    }

    .payment-card {
        background: linear-gradient(135deg, #fffbf0 0%, #fef3c7 100%);
        border: 2px solid #fbbf24;
        border-radius: 15px;
        padding: 1.5rem;
        position: relative;
        overflow: hidden;
    }

    .payment-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: rotate 15s linear infinite;
    }

    .payment-icon {
        font-size: 2.5rem;
        color: #fbbf24;
        margin-bottom: 1rem;
        position: relative;
        z-index: 1;
    }

    .payment-card h5 {
        color: #92400e;
        font-weight: 600;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 1;
    }

    .payment-card p {
        color: #a16207;
        margin-bottom: 0;
        position: relative;
        z-index: 1;
    }

    .instructions .alert {
        text-align: left;
        border-radius: 12px;
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        border: 1px solid #2196f3;
        color: #1565c0;
    }

    .instructions .alert strong {
        color: #0d47a1;
        font-size: 1.1rem;
        display: block;
        margin-bottom: 0.5rem;
    }

    .instructions .alert ul li {
        padding: 0.5rem 0;
        font-weight: 500;
    }

    .action-buttons .btn {
        border-radius: 30px;
        padding: 1.2rem 3rem;
        font-weight: 700;
        margin: 0.5rem;
        font-size: 1.15rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .action-buttons .btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }

    .action-buttons .btn:hover::before {
        left: 100%;
    }

    .action-buttons .btn-warning {
        background: linear-gradient(45deg, #fbbf24 0%, #f59e0b 100%);
        border: none;
        color: #fff;
        box-shadow: 0 6px 20px rgba(251, 191, 36, 0.3);
    }

    .action-buttons .btn-warning:hover {
        background: linear-gradient(45deg, #f59e0b 0%, #d97706 100%);
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(251, 191, 36, 0.4);
        color: #fff;
    }

    .action-buttons .btn-outline-primary {
        border: 3px solid #2196f3;
        color: #2196f3;
        background: #fff;
    }

    .action-buttons .btn-outline-primary:hover {
        background: #2196f3;
        color: #fff;
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(33,150,243,0.3);
    }

    .contact-info {
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 10px;
        border-left: 4px solid #fbbf24;
    }

    @media (max-width: 768px) {
        .success-card {
            padding: 2.5rem 2rem;
            margin: 1rem;
            border-radius: 20px;
        }
        
        .success-icon i {
            font-size: 5rem;
            padding: 1rem;
        }
        
        .order-number-card {
            padding: 1.5rem;
        }
        
        .order-number-card h3 {
            font-size: 2.2rem;
            letter-spacing: 2px;
        }
        
        .action-buttons .btn {
            display: block;
            width: 100%;
            margin: 0.75rem 0;
            padding: 1rem 2rem;
        }
        
        .payment-card::before {
            animation-duration: 10s;
        }
    }

    @media (max-width: 480px) {
        .success-card {
            padding: 2rem 1.5rem;
        }
        
        .success-icon i {
            font-size: 4rem;
        }
        
        .order-number-card h3 {
            font-size: 1.8rem;
        }
        
        .summary-card, .payment-card {
            padding: 1.5rem;
        }
    }
</style>

<script>
    // Add interactive effects
    document.addEventListener('DOMContentLoaded', function() {
        // Add entrance animation
        const successCard = document.querySelector('.success-card');
        successCard.style.opacity = '0';
        successCard.style.transform = 'translateY(30px)';
        
        setTimeout(() => {
            successCard.style.transition = 'all 0.8s ease';
            successCard.style.opacity = '1';
            successCard.style.transform = 'translateY(0)';
        }, 100);
        
        // Copy order number to clipboard when clicked
        const orderNumber = document.querySelector('.order-number-card h3');
        if (orderNumber) {
            orderNumber.addEventListener('click', function() {
                const text = this.textContent;
                navigator.clipboard.writeText(text).then(() => {
                    // Show copied notification
                    const notification = document.createElement('div');
                    notification.textContent = 'Nomor pesanan disalin!';
                    notification.style.cssText = `
                        position: fixed;
                        bottom: 20px;
                        left: 50%;
                        transform: translateX(-50%);
                        background: #fbbf24;
                        color: white;
                        padding: 10px 20px;
                        border-radius: 25px;
                        font-weight: 600;
                        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
                        z-index: 1000;
                        animation: fadeInOut 2s forwards;
                    `;
                    document.body.appendChild(notification);
                    
                    setTimeout(() => {
                        notification.remove();
                    }, 2000);
                });
            });
        }
        
        // Add click tracking for buttons
        document.querySelectorAll('.action-buttons .btn').forEach(button => {
            button.addEventListener('click', function() {
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
            });
        });
        
        // Add animation to payment instructions
        const paymentCard = document.querySelector('.payment-card');
        if (paymentCard) {
            paymentCard.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
                this.style.boxShadow = '0 15px 35px rgba(251, 191, 36, 0.2)';
            });
            
            paymentCard.addEventListener('mouseleave', function() {
                this.style.transform = '';
                this.style.boxShadow = '';
            });
        }
    });
    
    // Add fade in/out animation
    document.head.insertAdjacentHTML('beforeend', `
        <style>
            @keyframes fadeInOut {
                0% { opacity: 0; transform: translate(-50%, 20px); }
                20% { opacity: 1; transform: translate(-50%, 0); }
                80% { opacity: 1; transform: translate(-50%, 0); }
                100% { opacity: 0; transform: translate(-50%, -20px); }
            }
        </style>
    `);
</script>
@endsection
