<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Checkout - Warung Bakso Selingsing</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    
    <link href="{{ asset('assets/img/favicon.png') }}" rel="icon">
    <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">
    
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Amatic+SC:wght@400;700&display=swap" rel="stylesheet">
    
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
    
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">
</head>

<body class="checkout-page">
    <header id="header" class="header d-flex align-items-center sticky-top">
        <div class="container position-relative d-flex align-items-center justify-content-between">
            <a href="{{ route('home') }}" class="logo d-flex align-items-center">
                <h1 class="sitename">Warung Bakso Selingsing</h1>
                <span>.</span>
            </a>
            <a class="btn btn-back-menu" href="{{ route('home') }}">
                <i class="bi bi-arrow-left"></i> Kembali ke Menu
            </a>
        </div>
    </header>

    <main class="main">
        <section class="checkout-section py-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="checkout-card">
                            <h2 class="text-center mb-4">
                                <i class="bi bi-cart-check text-warning"></i>
                                Checkout Pesanan
                            </h2>

                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <div class="cart-items mb-4">
                                <h4 class="mb-3">
                                    <i class="bi bi-bag-check"></i> Pesanan Anda
                                </h4>
                                
                                @php
                                    $cart = session('cart', []);
                                    $subtotal = 0;
                                @endphp

                                @if(empty($cart))
                                    <div class="alert alert-warning text-center">
                                        <i class="bi bi-cart-x fs-1 d-block mb-2"></i>
                                        <p class="mb-0">Keranjang belanja Anda kosong!</p>
                                        <a href="{{ route('home') }}" class="btn btn-warning mt-2">Pilih Menu</a>
                                    </div>
                                @else
                                    <div class="cart-list">
                                        @foreach($cart as $id => $item)
                                            @if(isset($item['menu']))
                                                @php
                                                    $menu = $item['menu'];
                                                    $itemTotal = $menu->price * $item['quantity'];
                                                    $subtotal += $itemTotal;
                                                @endphp
                                                <div class="cart-item">
                                                    <div class="cart-item-image">
                                                        <img src="{{ $menu->image ? asset('storage/'.$menu->image) : 'https://placehold.co/80x80?text=No+Image' }}"
                                                             alt="{{ $menu->name }}" class="img-fluid">
                                                    </div>
                                                    <div class="cart-item-info">
                                                        <h6 class="mb-1">{{ $menu->name }}</h6>
                                                        <small class="text-muted">{{ $menu->description ?? '-' }}</small>
                                                        <div class="price-info mt-1">
                                                            <span class="text-muted">Rp{{ number_format($menu->price, 0, ',', '.') }} x {{ $item['quantity'] }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="cart-item-price">
                                                        <span class="quantity-badge">{{ $item['quantity'] }}</span>
                                                        <div class="fw-bold text-warning">Rp{{ number_format($itemTotal, 0, ',', '.') }}</div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>

                                    <div class="order-summary mt-4 p-3 bg-light rounded">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Subtotal:</span>
                                            <span>Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Biaya Layanan:</span>
                                            <span>Rp{{ number_format(2000, 0, ',', '.') }}</span>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between">
                                            <strong>Total:</strong>
                                            <strong class="text-warning fs-5">Rp{{ number_format($subtotal + 2000, 0, ',', '.') }}</strong>
                                        </div>
                                    </div>

                                    <form action="{{ route('order.checkout') }}" method="POST" id="checkout-form" class="mt-4">
                                        @csrf
                                        <div class="customer-info">
                                            <h4 class="mb-3">
                                                <i class="bi bi-person-fill"></i> Informasi Pelanggan
                                            </h4>
                                            
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="customer_name" class="form-label">Nama Pelanggan *</label>
                                                    <input type="text"
                                                           class="form-control @error('customer_name') is-invalid @enderror"
                                                           id="customer_name"
                                                           name="customer_name"
                                                           value="{{ old('customer_name') }}"
                                                           placeholder="Masukkan nama Anda"
                                                           required>
                                                    @error('customer_name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                
                                                <div class="col-md-6 mb-3">
                                                    <label for="table_number" class="form-label">Nomor Meja *</label>
                                                    <select class="form-select @error('table_number') is-invalid @enderror"
                                                            id="table_number"
                                                            name="table_number"
                                                            required>
                                                        <option value="">Pilih Nomor Meja</option>
                                                        @for($i = 1; $i <= 10; $i++)
                                                            <option value="{{ $i }}" {{ old('table_number') == $i ? 'selected' : '' }}>
                                                                Meja {{ $i }}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                    @error('table_number')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="notes" class="form-label">Catatan Khusus (Opsional)</label>
                                                <textarea class="form-control @error('notes') is-invalid @enderror"
                                                          id="notes"
                                                          name="notes"
                                                          rows="3"
                                                          placeholder="Catatan khusus untuk pesanan Anda...">{{ old('notes') }}</textarea>
                                                @error('notes')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="payment-method mt-4">
                                            <h4 class="mb-3">
                                                <i class="bi bi-credit-card"></i> Metode Pembayaran
                                            </h4>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="radio" name="payment_method" id="cash" value="cash" checked>
                                                <label class="form-check-label" for="cash">
                                                    <i class="bi bi-cash"></i> Bayar Tunai (Cash)
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="payment_method" id="cashless" value="cashless">
                                                <label class="form-check-label" for="cashless">
                                                    <i class="bi bi-qr-code-scan"></i> Qris (Cashless)
                                                </label>
                                            </div>
                                        </div>

                                        <div class="d-grid gap-2 mt-4">
                                            <button type="submit" class="btn btn-warning btn-lg fw-bold" id="place-order-btn">
                                                <i class="bi bi-check-circle"></i> Pesan Sekarang
                                                <span class="ms-2">Rp{{ number_format($subtotal + 2000, 0, ',', '.') }}</span>
                                            </button>
                                        </div>
                                    </form>
                                @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer id="footer" class="footer dark-background">
        <div class="container">
            <div class="row gy-3">
                <div class="col-lg-3 col-md-6 d-flex">
                    <i class="bi bi-geo-alt icon"></i>
                    <div class="address">
                        <h4>Address</h4>
                        <p>Jl. Raya Abianbase No.80, Dalung</p>
                        <p>Kec. Kuta Utara, Kabupaten Badung, Bali</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 d-flex">
                    <i class="bi bi-telephone icon"></i>
                    <div>
                        <h4>Contact</h4>
                        <p>
                            <strong>Phone:</strong> <span>0813-5375-9061</span><br>
                            <strong>Email:</strong> <span>baksobalung779@gmail.com</span><br>
                        </p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 d-flex">
                    <i class="bi bi-clock icon"></i>
                    <div>
                        <h4>Opening Hours</h4>
                        <p>
                            <strong>Open Everyday</strong><br>
                            <strong>Monday-Sanday:</strong> <span>10.00 AM - 22.00 PM</span><br>
                        </p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h4>Follow Us</h4>
                    <div class="social-links d-flex">
                        <a href="https://www.facebook.com/share/1CHSi3ebgg/?mibextid=wwXIfr" class="facebook"><i class="bi bi-facebook"></i></a>
                        <a href="https://www.instagram.com/bakso_balung_slingsing?igsh=dzU5ZzZwc3ZycHRm" class="instagram"><i class="bi bi-instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>
    <script src="{{ asset('assets/vendor/aos/aos.js') }}"></script>
    <script src="{{ asset('assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>
    <script src="{{ asset('assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <style>
        /* Header Styling */
        #header {
            background: linear-gradient(135deg, #fffbf0 0%, #fef3c7 100%);
            box-shadow: 0 2px 15px rgba(245, 158, 11, 0.1);
            padding: 1rem 0;
        }

        #header .container {
            max-width: 1200px;
        }

        /* Logo Styling */
        .logo {
            text-decoration: none;
            color: #f59e0b !important;
        }

        .logo .sitename {
            font-size: 1.8rem;
            font-weight: 700;
            color: #f59e0b;
            margin: 0;
            font-family: 'Amatic SC', cursive;
        }

        .logo span {
            color: #d97706;
            font-size: 2rem;
            font-weight: 900;
        }

        /* Back Button Styling */
        .btn-back-menu {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white !important;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
            position: relative;
            overflow: hidden;
        }

        .btn-back-menu::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn-back-menu:hover::before {
            left: 100%;
        }

        .btn-back-menu:hover {
            background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(245, 158, 11, 0.4);
            color: white !important;
            text-decoration: none;
        }

        .btn-back-menu:active {
            transform: translateY(0);
        }

        .btn-back-menu i {
            font-size: 1rem;
            transition: transform 0.3s ease;
        }

        .btn-back-menu:hover i {
            transform: translateX(-3px);
        }

        /* Responsive Header */
        @media (max-width: 768px) {
            #header .container {
                padding: 0 1rem;
            }

            .logo .sitename {
                font-size: 1.4rem;
            }

            .btn-back-menu {
                padding: 0.6rem 1.2rem;
                font-size: 0.85rem;
                border-radius: 20px;
            }
        }

        @media (max-width: 576px) {
            #header .container {
                flex-direction: column;
                gap: 1rem;
                align-items: center !important;
            }

            .logo {
                order: 1;
            }

            .btn-back-menu {
                order: 2;
                width: auto;
                align-self: center;
            }
        }

        /* Checkout Card Styling */
        .checkout-card {
            background: linear-gradient(135deg, #ffffff 0%, #fffbf0 100%);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(245, 158, 11, 0.1);
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid rgba(245, 158, 11, 0.1);
        }

        .checkout-card h2 {
            color: #f59e0b;
            font-weight: 700;
        }

        .cart-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            border: 2px solid #fef3c7;
            border-radius: 15px;
            margin-bottom: 0.75rem;
            background: linear-gradient(135deg, #ffffff 0%, #fffbf0 100%);
            transition: all 0.3s ease;
        }

        .cart-item:hover {
            box-shadow: 0 5px 20px rgba(245, 158, 11, 0.15);
            transform: translateY(-2px);
            border-color: #f59e0b;
        }

        .cart-item-image {
            flex-shrink: 0;
            margin-right: 1rem;
        }

        .cart-item-image img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 10px;
            border: 2px solid #fef3c7;
        }

        .cart-item-info {
            flex-grow: 1;
        }

        .cart-item-info h6 {
            margin: 0;
            font-weight: 600;
            color: #92400e;
        }

        .cart-item-info small {
            color: #a16207;
            font-size: 0.85rem;
        }

        .cart-item-price {
            text-align: right;
            flex-shrink: 0;
        }

        .quantity-badge {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            padding: 0.25rem 0.6rem;
            border-radius: 15px;
            font-size: 0.875rem;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 0.25rem;
            box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
        }

        .order-summary {
            border: 2px dashed #f59e0b;
            background: linear-gradient(135deg, #fffbf0 0%, #fef3c7 100%) !important;
            border-radius: 15px;
        }

        .customer-info, .payment-method {
            border-top: 2px solid #fef3c7;
            padding-top: 1.5rem;
        }

        .customer-info h4, .payment-method h4 {
            color: #f59e0b;
            font-weight: 600;
        }

        .form-label {
            font-weight: 600;
            color: #92400e;
        }

        .form-control:focus, .form-select:focus {
            border-color: #f59e0b;
            box-shadow: 0 0 0 0.2rem rgba(245, 158, 11, 0.25);
        }

        .form-check-input:checked {
            background-color: #f59e0b;
            border-color: #f59e0b;
        }

        .form-check-input:focus {
            border-color: #f59e0b;
            box-shadow: 0 0 0 0.2rem rgba(245, 158, 11, 0.25);
        }

        #place-order-btn {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            border: none;
            padding: 1rem 2rem;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(245, 158, 11, 0.3);
        }

        #place-order-btn:hover {
            background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(245, 158, 11, 0.4);
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .checkout-card {
                padding: 1.5rem;
                margin: 1rem;
                border-radius: 15px;
            }

            .cart-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
                padding: 1rem 0.75rem;
            }

            .cart-item-image {
                margin-right: 0;
                align-self: center;
            }

            .cart-item-price {
                text-align: left;
                width: 100%;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .cart-item-info {
                text-align: center;
                width: 100%;
            }
        }

        /* Alert Styling */
        .alert-warning {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border: 2px solid #f59e0b;
            color: #92400e;
            border-radius: 15px;
        }

        .alert-warning .btn-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            border: none;
            color: white;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Form validation
            const form = document.getElementById('checkout-form');
            const submitBtn = document.getElementById('place-order-btn');
            
            if (form) {
                form.addEventListener('submit', function(e) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Memproses...';
                });
            }

            // Add loading animation to back button
            const backBtn = document.querySelector('.btn-back-menu');
            if (backBtn) {
                backBtn.addEventListener('click', function() {
                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        this.style.transform = 'scale(1)';
                    }, 150);
                });
            }
        });
    </script>
</body>
</html>
