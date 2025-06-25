<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <title>AdminHub - Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #6366f1;
            --primary-dark: #4f46e5;
            --secondary-color: #8b5cf6;
            --accent-color: #06b6d4;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --dark-color: #1f2937;
            --light-color: #f8fafc;
            --white: #ffffff;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --gray-900: #0f172a;
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 80px;
            --navbar-height: 70px;
            --border-radius: 12px;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }

        [data-theme="dark"] {
            --light-color: #1e293b;
            --white: #0f172a;
            --gray-100: #334155;
            --gray-200: #475569;
            --gray-300: #64748b;
            --dark-color: #f8fafc;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--light-color);
            color: var(--dark-color);
            transition: all 0.3s ease;
        }

        /* SIDEBAR */
        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            z-index: 2000;
            transition: all 0.3s ease;
            overflow: hidden;
            box-shadow: var(--shadow-xl);
        }

        #sidebar.hide {
            width: var(--sidebar-collapsed-width);
        }

        #sidebar .brand {
            display: flex;
            align-items: center;
            height: var(--navbar-height);
            padding: 0 24px;
            color: var(--white);
            text-decoration: none;
            font-size: 1.5rem;
            font-weight: 700;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        #sidebar .brand i {
            font-size: 2rem;
            margin-right: 12px;
            transition: all 0.3s ease;
        }

        #sidebar.hide .brand .text {
            opacity: 0;
            pointer-events: none;
        }

        #sidebar .side-menu {
            list-style: none;
            padding: 20px 0;
        }

        #sidebar .side-menu.top {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 20px;
            padding-bottom: 20px;
        }

        #sidebar .side-menu li {
            margin: 4px 0;
        }

        #sidebar .side-menu li a,
        #sidebar .side-menu li button {
            display: flex;
            align-items: center;
            padding: 16px 24px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            border-radius: 0 25px 25px 0;
            margin-right: 20px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            background: none;
            border: none;
            width: 100%;
            font-family: inherit;
            font-size: 0.95rem;
            cursor: pointer;
        }

        #sidebar .side-menu li a::before,
        #sidebar .side-menu li button::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            z-index: -1;
        }

        #sidebar .side-menu li a:hover::before,
        #sidebar .side-menu li button:hover::before,
        #sidebar .side-menu li.active a::before {
            width: 100%;
        }

        #sidebar .side-menu li a:hover,
        #sidebar .side-menu li button:hover,
        #sidebar .side-menu li.active a {
            color: var(--white);
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            transform: translateX(8px);
        }

        #sidebar .side-menu li a i,
        #sidebar .side-menu li button i {
            font-size: 1.4rem;
            margin-right: 16px;
            min-width: 24px;
            transition: all 0.3s ease;
        }

        #sidebar.hide .side-menu li a .text,
        #sidebar.hide .side-menu li button .text {
            opacity: 0;
            pointer-events: none;
        }

        #sidebar.hide .side-menu li a,
        #sidebar.hide .side-menu li button {
            justify-content: center;
            margin-right: 0;
            border-radius: var(--border-radius);
        }

        /* CONTENT */
        #content {
            position: relative;
            width: calc(100% - var(--sidebar-width));
            left: var(--sidebar-width);
            transition: all 0.3s ease;
            min-height: 100vh;
        }

        #sidebar.hide ~ #content {
            width: calc(100% - var(--sidebar-collapsed-width));
            left: var(--sidebar-collapsed-width);
        }

        /* NAVBAR */
        #content nav {
            height: var(--navbar-height);
            background: var(--white);
            padding: 0 24px;
            display: flex;
            align-items: center;
            gap: 24px;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: var(--shadow);
            border-bottom: 1px solid var(--gray-200);
        }

        #content nav .bx-menu {
            font-size: 1.8rem;
            cursor: pointer;
            color: var(--gray-600);
            transition: all 0.3s ease;
            padding: 8px;
            border-radius: var(--border-radius);
        }

        #content nav .bx-menu:hover {
            background: var(--gray-100);
            color: var(--primary-color);
        }

        #content nav .nav-link {
            font-size: 1rem;
            font-weight: 500;
            color: var(--gray-600);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        #content nav .nav-link:hover {
            color: var(--primary-color);
        }

        #content nav form {
            flex: 1;
            max-width: 400px;
        }

        #content nav .form-input {
            position: relative;
            display: flex;
            align-items: center;
        }

        #content nav .form-input input {
            width: 100%;
            padding: 12px 16px 12px 48px;
            border: 2px solid var(--gray-200);
            border-radius: 25px;
            font-size: 0.95rem;
            background: var(--gray-100);
            transition: all 0.3s ease;
            outline: none;
        }

        #content nav .form-input input:focus {
            border-color: var(--primary-color);
            background: var(--white);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        #content nav .form-input .search-btn {
            position: absolute;
            left: 16px;
            background: none;
            border: none;
            color: var(--gray-400);
            font-size: 1.2rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        #content nav .form-input .search-btn:hover {
            color: var(--primary-color);
        }

        /* SWITCH MODE */
        #content nav .switch-mode {
            position: relative;
            width: 50px;
            height: 26px;
            background: var(--gray-300);
            border-radius: 13px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        #content nav .switch-mode::before {
            content: '';
            position: absolute;
            top: 3px;
            left: 3px;
            width: 20px;
            height: 20px;
            background: var(--white);
            border-radius: 50%;
            transition: all 0.3s ease;
            box-shadow: var(--shadow);
        }

        #content nav #switch-mode:checked + .switch-mode {
            background: var(--primary-color);
        }

        #content nav #switch-mode:checked + .switch-mode::before {
            left: 27px;
        }

        /* NOTIFICATION */
        #content nav .notification {
            position: relative;
            color: var(--gray-600);
            font-size: 1.4rem;
            text-decoration: none;
            padding: 8px;
            border-radius: var(--border-radius);
            transition: all 0.3s ease;
        }

        #content nav .notification:hover {
            background: var(--gray-100);
            color: var(--primary-color);
        }

        #content nav .notification .num {
            position: absolute;
            top: -2px;
            right: -2px;
            background: var(--danger-color);
            color: var(--white);
            font-size: 0.7rem;
            font-weight: 600;
            padding: 2px 6px;
            border-radius: 10px;
            min-width: 18px;
            text-align: center;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        /* PROFILE */
        #content nav .profile {
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        #content nav .profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid var(--primary-color);
            transition: all 0.3s ease;
        }

        #content nav .profile:hover img {
            transform: scale(1.1);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.2);
        }

        /* MAIN CONTENT */
        main {
            padding: 32px 24px;
            background: var(--light-color);
            min-height: calc(100vh - var(--navbar-height));
        }

        /* RESPONSIVE */
        @media screen and (max-width: 768px) {
            #sidebar {
                width: var(--sidebar-collapsed-width);
            }

            #content {
                width: calc(100% - var(--sidebar-collapsed-width));
                left: var(--sidebar-collapsed-width);
            }

            #content nav {
                padding: 0 16px;
                gap: 16px;
            }

            #content nav form {
                max-width: 200px;
            }

            main {
                padding: 20px 16px;
            }
        }

        @media screen and (max-width: 576px) {
            #sidebar {
                width: 0;
                overflow: hidden;
            }

            #sidebar.show {
                width: var(--sidebar-width);
            }

            #content {
                width: 100%;
                left: 0;
            }

            #content nav form {
                display: none;
            }
        }

        /* ANIMATIONS */
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        main {
            animation: slideInRight 0.5s ease;
        }

        /* SCROLLBAR */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--gray-100);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--gray-300);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--gray-400);
        }

        /* DARK MODE STYLES */
        [data-theme="dark"] #sidebar {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        }

        [data-theme="dark"] #content nav {
            background: var(--gray-800);
            border-bottom-color: var(--gray-700);
        }

        [data-theme="dark"] #content nav .form-input input {
            background: var(--gray-700);
            border-color: var(--gray-600);
            color: var(--gray-200);
        }

        [data-theme="dark"] #content nav .form-input input:focus {
            background: var(--gray-600);
        }

        [data-theme="dark"] main {
            background: var(--gray-900);
        }

        /* TOOLTIP */
        #sidebar.hide .side-menu li a,
        #sidebar.hide .side-menu li button {
            position: relative;
        }

        #sidebar.hide .side-menu li a::after,
        #sidebar.hide .side-menu li button::after {
            content: attr(data-tooltip);
            position: absolute;
            left: 100%;
            top: 50%;
            transform: translateY(-50%);
            background: var(--dark-color);
            color: var(--white);
            padding: 8px 12px;
            border-radius: var(--border-radius);
            font-size: 0.85rem;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: all 0.3s ease;
            margin-left: 10px;
            z-index: 1000;
        }

        #sidebar.hide .side-menu li a:hover::after,
        #sidebar.hide .side-menu li button:hover::after {
            opacity: 1;
        }
    </style>
</head>
<body>
    <!-- SIDEBAR -->
    <section id="sidebar">
        <a href="#" class="brand">
            <i class='bx bxs-smile'></i>
            <span class="text">AdminHub</span>
        </a>
        <ul class="side-menu top">
            <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}" data-tooltip="Dashboard">
                    <i class='bx bxs-dashboard'></i>
                    <span class="text">Dashboard</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('admin.menus.*') ? 'active' : '' }}">
                <a href="{{ route('admin.menus.index') }}" data-tooltip="Menu Makanan & Minuman">
                    <i class='bx bxs-shopping-bag-alt'></i>
                    <span class="text">Menu Makanan & Minuman</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('admin.kasir.*') ? 'active' : '' }}">
                <a href="{{ route('admin.kasir.index') }}" data-tooltip="Register Kasir">
                    <i class='bx bxs-user-plus'></i>
                    <span class="text">Register Kasir</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('admin.shifts.*') ? 'active' : '' }}">
                <a href="{{ route('admin.shifts.index') }}" data-tooltip="Shift Kasir">
                    <i class='bx bxs-doughnut-chart'></i>
                    <span class="text">Shift Kasir</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}">
                <a href="{{ route('admin.transactions.index') }}" data-tooltip="Transaksi Pelanggan">
                    <i class='bx bxs-message-dots'></i>
                    <span class="text">Transaksi Pelanggan</span>
                </a>
            </li>
        </ul>
        <ul class="side-menu">
            <li>
                <a href="#" data-tooltip="Settings">
                    <i class='bx bxs-cog'></i>
                    <span class="text">Settings</span>
                </a>
            </li>
            <li>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" data-tooltip="Logout">
                        <i class='bx bxs-log-out-circle'></i>
                        <span class="text">Logout</span>
                    </button>
                </form>
            </li>
        </ul>
    </section>
    <!-- SIDEBAR -->

    <!-- CONTENT -->
    <section id="content">
        <!-- NAVBAR -->
        <nav>
            <i class='bx bx-menu' id="menu-toggle"></i>
            <a href="#" class="nav-link">Categories</a>
            <form action="#">
                <div class="form-input">
                    <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
                    <input type="search" placeholder="Search anything...">
                </div>
            </form>
            <input type="checkbox" id="switch-mode" hidden>
            <label for="switch-mode" class="switch-mode"></label>
            <a href="#" class="notification">
                <i class='bx bxs-bell'></i>
                <span class="num">8</span>
            </a>
            <a href="#" class="profile">
                <img src="{{ asset('img/people.png') }}" alt="Profile">
            </a>
        </nav>
        <!-- NAVBAR -->

        <main>
            @yield('content')
        </main>
    </section>
    <!-- CONTENT -->

    <script>
        // Sidebar Toggle
        const menuToggle = document.getElementById('menu-toggle');
        const sidebar = document.getElementById('sidebar');
        
        menuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('hide');
        });

        // Dark Mode Toggle
        const switchMode = document.getElementById('switch-mode');
        
        // Check for saved theme preference or default to light mode
        const currentTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', currentTheme);
        
        if (currentTheme === 'dark') {
            switchMode.checked = true;
        }

        switchMode.addEventListener('change', function() {
            if (this.checked) {
                document.documentElement.setAttribute('data-theme', 'dark');
                localStorage.setItem('theme', 'dark');
            } else {
                document.documentElement.setAttribute('data-theme', 'light');
                localStorage.setItem('theme', 'light');
            }
        });

        // Mobile Sidebar Toggle
        if (window.innerWidth <= 576) {
            menuToggle.addEventListener('click', function() {
                sidebar.classList.toggle('show');
            });

            // Close sidebar when clicking outside
            document.addEventListener('click', function(e) {
                if (!sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
                    sidebar.classList.remove('show');
                }
            });
        }

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add loading animation to forms
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Loading...';
                    submitBtn.disabled = true;
                }
            });
        });

        // Auto-hide notifications after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-20px)';
                setTimeout(() => {
                    alert.remove();
                }, 300);
            });
        }, 5000);
    </script>
</body>
</html>