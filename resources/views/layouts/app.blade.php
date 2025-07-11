<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Warung Bakso Selingsing')</title>
    
    <link href="{{ asset('assets/img/favicon.png') }}" rel="icon">
    <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">
    
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Amatic+SC:wght@400;700&display=swap" rel="stylesheet">
    
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">
</head>

<body class="@yield('body-class', '')">
    <header id="header" class="header d-flex align-items-center sticky-top">
        <div class="container position-relative d-flex align-items-center justify-content-between">
            <a href="{{ route('home') }}" class="logo d-flex align-items-center me-auto me-xl-0">
                <h1 class="sitename">Warung Bakso Selingsing</h1>
                <span>.</span>
            </a>
            
            <nav id="navmenu" class="navmenu">
                <ul>
                    {{-- ✅ CORRECTION: Changed 'Beranda' to 'home' and 'Menu' to 'home' to match your routes file --}}
                    <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Menu</a></li>
                    <li><a href="{{ route('orders.history') }}" class="{{ request()->routeIs('orders.*') ? 'active' : '' }}">Pesanan Saya</a></li>
                    {{-- <li><a href="#">Kontak</a></li> --}} {{-- Removed link to prevent errors --}}
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>
            
            <a class="btn-getstarted" href="{{ route('customer.welcome') }}">
                <i class="bi bi-cart"></i> kembali ke menu
            </a>
        </div>
    </header>

    @yield('content')

    <footer id="footer" class="footer dark-background">
        <div class="container">
            <div class="row gy-3">
                <div class="col-lg-3 col-md-6 d-flex">
                    <i class="bi bi-geo-alt icon"></i>
                    <div class="address">
                        <h4>Alamat</h4>
                        <p>Jl. Selingsing No. 123</p>
                        <p>Jakarta Selatan, 12345</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 d-flex">
                    <i class="bi bi-telephone icon"></i>
                    <div>
                        <h4>Kontak</h4>
                        <p>
                            <strong>Telepon:</strong> <span>+62 812-3456-7890</span><br>
                            <strong>Email:</strong> <span>info@warungbakso.com</span><br>
                        </p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 d-flex">
                    <i class="bi bi-clock icon"></i>
                    <div>
                        <h4>Jam Buka</h4>
                        <p>
                            <strong>Senin-Sabtu:</strong> <span>10:00 - 22:00</span><br>
                            <strong>Minggu:</strong> <span>10:00 - 21:00</span>
                        </p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4>Ikuti Kami</h4>
                    <div class="social-links d-flex">
                        <a href="#" class="twitter"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="whatsapp"><i class="bi bi-whatsapp"></i></a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="container copyright text-center mt-4">
            <p>© <span>Copyright</span> <strong class="px-1 sitename">Warung Bakso Selingsing</strong> <span>All Rights Reserved</span></p>
        </div>
    </footer>

    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    
    @stack('scripts')
</body>
</html>