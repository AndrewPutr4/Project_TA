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
    
    <!-- Add this line to render pushed styles -->
    @stack('styles')
    
    <style>
        /* Header dengan tema kuning */
        #header {
            background: linear-gradient(135deg, #fffbf0 0%, #fef3c7 100%);
            box-shadow: 0 2px 15px rgba(245, 158, 11, 0.1);
            border-bottom: 1px solid rgba(245, 158, 11, 0.1);
        }
        
        .logo h1.sitename {
            color: #1e293b; /* Changed to a dark black/charcoal color */
            font-weight: 800;
            font-size: 1.8rem;
        }
        
        .logo span {
            color: #1e293b; /* Changed to a dark black/charcoal color */
            font-weight: 800;
        }
        
        .navmenu ul li a {
            color: #92400e;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .navmenu ul li a:hover,
        .navmenu ul li a.active {
            color: #f59e0b;
            background: rgba(245, 158, 11, 0.1);
            border-radius: 8px;
            padding: 8px 15px;
        }
        
        .btn-getstarted {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 25px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .btn-getstarted::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn-getstarted:hover::before {
            left: 100%;
        }
        
        .btn-getstarted:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4);
            color: white;
            text-decoration: none;
        }
        
        /* Mobile responsive header */
        @media (max-width: 768px) {
            .container {
                padding: 0 15px;
            }
            
            .logo h1.sitename {
                font-size: 1.4rem;
            }
            
            .btn-getstarted {
                padding: 10px 18px;
                font-size: 0.9rem;
            }
        }
        
        @media (max-width: 480px) {
            .logo h1.sitename {
                font-size: 1.2rem;
            }
            
            .btn-getstarted {
                padding: 8px 15px;
                font-size: 0.85rem;
            }
            
            .btn-getstarted .bi {
                margin-right: 5px;
            }
        }
    </style>
</head>
<body class="@yield('body-class', '')">
    <header id="header" class="header d-flex align-items-center sticky-top">
        <div class="container position-relative d-flex align-items-center justify-content-between">
            <a href="{{ route('home') }}" class="logo d-flex align-items-center">
                <h1 class="sitename">Warung Bakso Selingsing</h1>
                <span>.</span>
            </a>
            
            <nav id="navmenu" class="navmenu d-none d-md-block">
                <ul>
                    <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Menu</a></li>
                    <li><a href="{{ route('orders.history') }}" class="{{ request()->routeIs('orders.*') ? 'active' : '' }}">Riwayat Pesanan</a></li>
                </ul>
            </nav>
            
            <a class="btn-getstarted" href="{{ route('customer.welcome') }}">
                <i class="bi bi-arrow-left"></i> Kembali ke Menu
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
                            <strong>Monday-Sunday:</strong> <span>10.00 AM - 22.00 PM</span><br>
                        </p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4>Ikuti Kami</h4>
                    <div class="social-links d-flex">
                        <a href="https://www.facebook.com/share/1CHSi3ebgg/?mibextid=wwXIfr" class="facebook"><i class="bi bi-facebook"></i></a>
                        <a href="https://www.instagram.com/bakso_balung_slingsing?igsh=dzU5ZzZwc3ZycHRm" class="instagram"><i class="bi bi-instagram"></i></a>
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
