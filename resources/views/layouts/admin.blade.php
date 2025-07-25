<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdminHub - @yield('title', 'Dashboard')</title>
    
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #f59e0b;
            --primary-dark: #d97706;
            --primary-light: #fbbf24;
            --warning-bg: #fffbeb;
            --warning-border: #fed7aa;
            --warning-text: #92400e;
            --white: #ffffff;
            --light: #f8fafc;
            --gray-100: #f1f3f4;
            --gray-200: #e9ecef;
            --gray-800: #343a40;
            --shadow: 0 4px 6px rgba(245, 158, 11, 0.1);
            --border-radius: 12px;
            --border-radius-lg: 16px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 80px;
            --navbar-height: 70px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background: var(--light);
            color: var(--gray-800);
            overflow-x: hidden;
        }

        a { text-decoration: none; }
        ul { list-style: none; }

        /* --- SIDEBAR --- */
        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            z-index: 2000;
            transition: var(--transition);
            display: flex;
            flex-direction: column;
            box-shadow: 2px 0 16px rgba(245, 158, 11, 0.15);
        }

        #sidebar.hide {
            width: var(--sidebar-collapsed-width);
        }

        #sidebar .brand {
            height: var(--navbar-height);
            display: flex;
            align-items: center;
            padding: 0 24px;
            color: var(--white);
            font-size: 1.5rem;
            font-weight: 700;
            white-space: nowrap;
            letter-spacing: 1px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        #sidebar .brand i {
            font-size: 2rem;
            margin-right: 12px;
            min-width: 24px;
        }

        #sidebar.hide .brand .text {
            display: none;
        }

        #sidebar .side-menu {
            padding: 20px 10px 10px 10px;
            flex-grow: 1;
        }

        #sidebar .side-menu li {
            margin-bottom: 6px;
        }

        #sidebar .side-menu li a,
        #sidebar .side-menu li button {
            display: flex;
            align-items: center;
            width: 100%;
            padding: 13px 18px;
            border-radius: var(--border-radius);
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.85);
            white-space: nowrap;
            background: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            text-align: left;
            font-weight: 500;
            gap: 12px;
            position: relative;
        }

        #sidebar .side-menu li a i,
        #sidebar .side-menu li button i {
            min-width: 32px;
            font-size: 1.3rem;
            display: flex;
            justify-content: center;
        }

        #sidebar .side-menu li.active a,
        #sidebar .side-menu li a:hover,
        #sidebar .side-menu li button:hover {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            box-shadow: 0 2px 12px rgba(245, 158, 11, 0.2);
        }

        #sidebar .side-menu li.active a::before {
            content: '';
            position: absolute;
            left: 0;
            top: 8px;
            bottom: 8px;
            width: 5px;
            border-radius: 4px;
            background: #fff;
        }

        #sidebar.hide .side-menu .text {
            display: none;
        }

        #sidebar .side-menu.bottom {
            margin-top: auto;
            padding-bottom: 18px;
        }

        #sidebar .side-menu.bottom li {
            margin-bottom: 0;
        }

        /* Scrollbar sidebar */
        #sidebar .side-menu {
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,0.3) transparent;
        }

        #sidebar .side-menu::-webkit-scrollbar {
            width: 6px;
        }

        #sidebar .side-menu::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.3);
            border-radius: 8px;
        }

        #sidebar .side-menu::-webkit-scrollbar-track {
            background: transparent;
        }

        /* --- CONTENT --- */
        #content {
            position: relative;
            width: calc(100% - var(--sidebar-width));
            left: var(--sidebar-width);
            transition: var(--transition);
        }

        #sidebar.hide ~ #content {
            width: calc(100% - var(--sidebar-collapsed-width));
            left: var(--sidebar-collapsed-width);
        }

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
            border-bottom: 1px solid var(--warning-border);
        }

        #content nav .bx-menu {
            font-size: 1.8rem;
            cursor: pointer;
            color: var(--primary-color);
            transition: all 0.2s ease;
        }

        #content nav .bx-menu:hover {
            color: var(--primary-dark);
            transform: scale(1.1);
        }

        #content nav .nav-link {
            color: var(--warning-text);
            font-weight: 600;
            font-size: 1.1rem;
        }

        #content nav .profile {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 16px;
            background: var(--warning-bg);
            border: 2px solid var(--warning-border);
            border-radius: 12px;
            transition: all 0.2s ease;
        }

        #content nav .profile:hover {
            background: #fef3c7;
            border-color: var(--primary-color);
            transform: translateY(-1px);
        }

        #content nav .profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary-color);
        }

        #content nav .profile span {
            font-weight: 600;
            color: var(--warning-text);
        }

        main {
            padding: 32px 24px;
            min-height: calc(100vh - var(--navbar-height));
        }

        /* --- STYLING KONTEN (DASHBOARD) --- */
        .head-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 32px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 2rem;
            border-radius: var(--border-radius-lg);
            box-shadow: 0 8px 25px rgba(245, 158, 11, 0.3);
        }

        .head-title .left h1 {
            font-size: 2.5rem;
            font-weight: 800;
            color: white;
            margin: 0;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 8px;
        }

        .breadcrumb li a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-weight: 500;
            padding: 4px 8px;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .breadcrumb li a:hover,
        .breadcrumb li a.active {
            color: white;
            background: rgba(255, 255, 255, 0.2);
        }

        .breadcrumb li i {
            color: rgba(255, 255, 255, 0.6);
        }

        .btn-download {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: rgba(255, 255, 255, 0.2);
            color: var(--white);
            border-radius: var(--border-radius);
            font-weight: 600;
            border: 2px solid rgba(255, 255, 255, 0.3);
            transition: all 0.2s ease;
        }

        .btn-download:hover {
            background: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.5);
            transform: translateY(-2px);
            color: white;
            text-decoration: none;
        }

        .box-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }

        .box-info li {
            background: var(--white);
            padding: 32px 24px;
            border-radius: var(--border-radius-lg);
            display: flex;
            align-items: center;
            gap: 20px;
            border: 2px solid var(--warning-border);
            transition: all 0.3s ease;
        }

        .box-info li:hover {
            border-color: var(--primary-color);
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(245, 158, 11, 0.2);
        }

        .box-info li i {
            font-size: 3rem;
            padding: 20px;
            border-radius: 50%;
            color: var(--white);
        }

        .box-info li:nth-child(1) i { 
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%); 
        }
        .box-info li:nth-child(2) i { 
            background: linear-gradient(135deg, #10b981 0%, #059669 100%); 
        }
        .box-info li:nth-child(3) i { 
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); 
        }

        .box-info li .text h3 { 
            font-size: 2rem; 
            font-weight: 700; 
            color: var(--warning-text);
        }
        .box-info li .text p { 
            font-size: 1rem; 
            color: #92400e;
            opacity: 0.8;
        }

        .table-data {
            display: flex;
            gap: 24px;
            flex-wrap: wrap;
        }

        .table-data > div {
            flex-grow: 1;
            background: var(--white);
            border-radius: var(--border-radius-lg);
            overflow: hidden;
            border: 2px solid var(--warning-border);
        }

        .table-data .order { flex-basis: 60%; }

        .table-data .head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 24px;
            border-bottom: 1px solid var(--warning-border);
            background: var(--warning-bg);
        }

        .table-data .head h3 {
            color: var(--warning-text);
            font-weight: 700;
        }

        .table-data table { 
            width: 100%; 
            border-collapse: collapse; 
        }

        .table-data th, .table-data td { 
            padding: 16px 24px; 
            text-align: left; 
        }

        .table-data th {
            background: var(--warning-bg);
            color: var(--warning-text);
            font-weight: 600;
            border-bottom: 2px solid var(--warning-border);
        }

        .table-data td {
            border-bottom: 1px solid #f1f5f9;
        }

        .table-data tbody tr:hover {
            background: var(--warning-bg);
        }

        .status.process {
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            background: var(--warning-bg);
            color: var(--warning-text);
            border: 1px solid var(--warning-border);
            font-weight: 600;
        }

        /* Modal Profile */
        .modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0,0,0,0.5);
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(5px);
        }

        .modal-content {
            background: #fff;
            border-radius: 18px;
            max-width: 400px;
            width: 90%;
            margin: auto;
            padding: 0;
            box-shadow: 0 20px 60px rgba(245, 158, 11, 0.3);
            animation: modalSlideIn 0.3s ease;
            border: 2px solid var(--warning-border);
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-50px) scale(0.9);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 25px 30px;
            border-radius: 16px 16px 0 0;
            position: relative;
        }

        .modal-header h4 {
            margin: 0;
            font-size: 1.3rem;
            font-weight: 600;
        }

        .modal-header button {
            position: absolute;
            top: 15px;
            right: 20px;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: rgba(255,255,255,0.8);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .modal-header button:hover {
            color: white;
            transform: scale(1.1);
        }

        .modal-body {
            padding: 30px;
        }

        .modal-form-group {
            margin-bottom: 20px;
        }

        .modal-form-group label {
            font-weight: 600;
            color: var(--warning-text);
            font-size: 0.95rem;
            display: block;
            margin-bottom: 8px;
        }

        .modal-form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--warning-border);
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
        }

        .modal-form-group input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
        }

        .modal-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 25px;
        }

        .modal-actions button {
            padding: 12px 24px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .modal-actions button[type="submit"] {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
        }

        .modal-actions button[type="submit"]:hover {
            background: linear-gradient(135deg, var(--primary-dark) 0%, #b45309 100%);
            transform: translateY(-2px);
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            #sidebar {
                width: 240px;
            }
            #content {
                width: calc(100% - 240px);
                left: 240px;
            }
            #sidebar.hide ~ #content {
                width: calc(100% - var(--sidebar-collapsed-width));
                left: var(--sidebar-collapsed-width);
            }
        }

        @media (max-width: 1024px) {
            main {
                padding: 24px 20px;
            }
            #content nav {
                padding: 0 20px; /* Adjusted padding for smaller screens */
            }
            .head-title {
                flex-direction: column;
                gap: 20px;
                align-items: flex-start;
                padding: 1.5rem;
            }
            .head-title .left h1 {
                font-size: 2rem;
            }
            .btn-download {
                width: 100%;
                justify-content: center;
            }
            .box-info {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 20px;
            }
            .table-data {
                flex-direction: column;
            }
        }

        @media (max-width: 900px) {
            #sidebar {
                width: 85vw;
                max-width: 320px;
                left: -85vw;
                transition: left 0.3s ease;
            }
            #sidebar:not(.hide) {
                left: 0;
            }
            #sidebar.hide {
                left: -85vw;
            }
            #content {
                left: 0;
                width: 100vw;
            }
            #sidebar.hide ~ #content {
                left: 0;
                width: 100vw;
            }
            
            /* Overlay for mobile */
            #sidebar:not(.hide)::after {
                content: '';
                position: fixed;
                top: 0;
                left: 85vw;
                width: 15vw;
                height: 100vh;
                background: rgba(0,0,0,0.5);
                z-index: -1;
            }
        }

        @media (max-width: 768px) {
            main {
                padding: 20px 16px;
            }
            #content nav {
                padding: 0 16px;
            }
            .head-title {
                padding: 1.25rem;
            }
            .head-title .left h1 {
                font-size: 1.75rem;
            }
            .box-info {
                grid-template-columns: 1fr;
                gap: 16px;
            }
            .box-info li {
                padding: 24px 20px;
                flex-direction: column;
                text-align: center;
            }
            .box-info li i {
                font-size: 2.5rem;
                padding: 16px;
            }
            .table-data .head {
                padding: 20px;
            }
            .table-data th, .table-data td {
                padding: 12px 16px;
                font-size: 0.9rem;
            }
            .modal-content {
                margin: 20px;
                width: calc(100% - 40px);
            }
            .modal-body {
                padding: 20px;
            }
        }

        @media (max-width: 600px) {
            main {
                padding: 16px 12px;
            }
            #content nav {
                padding: 0 12px;
            }
            .head-title {
                padding: 1rem;
            }
            .head-title .left h1 {
                font-size: 1.5rem;
            }
            .box-info li {
                padding: 20px 16px;
            }
            .box-info li .text h3 {
                font-size: 1.5rem;
            }
            .table-data .head {
                padding: 16px;
            }
            .table-data th, .table-data td {
                padding: 10px 12px;
                font-size: 0.85rem;
            }
            #content nav .profile span {
                display: none;
            }
        }

        @media (max-width: 480px) {
            #sidebar {
                width: 100vw;
                left: -100vw;
            }
            #sidebar:not(.hide) {
                left: 0;
            }
            #sidebar.hide {
                left: -100vw;
            }
            main {
                padding: 12px 8px;
            }
            .head-title {
                padding: 0.75rem;
            }
            .head-title .left h1 {
                font-size: 1.25rem;
            }
            .box-info li {
                padding: 16px 12px;
            }
            .modal-content {
                margin: 10px;
                width: calc(100% - 20px);
            }
        }

        /* Touch improvements */
        @media (hover: none) and (pointer: coarse) {
            #sidebar .side-menu li a:hover,
            #sidebar .side-menu li button:hover {
                background: none;
            }
            #sidebar .side-menu li a:active,
            #sidebar .side-menu li button:active {
                background: rgba(255, 255, 255, 0.2);
            }
        }
    </style>
</head>
<body>
    <section id="sidebar">
        <a href="{{ route('admin.dashboard') }}" class="brand">
            <i class='bx bxs-smile'></i>
            <span class="text">Admin</span>
        </a>
        <ul class="side-menu top">
            <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}">
                    <i class='bx bxs-dashboard'></i>
                    <span class="text">Dashboard</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('admin.menus.*') ? 'active' : '' }}">
                <a href="{{ route('admin.menus.index') }}">
                    <i class='bx bxs-shopping-bag-alt'></i>
                    <span class="text">Menu</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('admin.kasir.*') ? 'active' : '' }}">
                <a href="{{ route('admin.kasir.index') }}">
                    <i class='bx bxs-user-plus'></i>
                    <span class="text">Register Kasir</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('admin.shifts.*') ? 'active' : '' }}">
                <a href="{{ route('admin.shifts.index') }}">
                    <i class='bx bxs-doughnut-chart'></i>
                    <span class="text">Shift Kasir</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}">
                <a href="{{ route('admin.transactions.index') }}">
                    <i class='bx bxs-message-dots'></i>
                    <span class="text">Laporan Transaksi</span>
                </a>
            </li>
        </ul>
        <ul class="side-menu bottom">
            <li>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit">
                        <i class='bx bxs-log-out-circle'></i>
                        <span class="text">Logout</span>
                    </button>
                </form>
            </li>
        </ul>
    </section>

    <section id="content">
        <nav>
            <i class='bx bx-menu'></i>
            <a href="#" class="nav-link">@yield('title', 'Page')</a>
            <div style="margin-left: auto;"></div>
            <div class="profile" id="profileBtn" style="cursor:pointer;">
                <img src="{{ asset('img/people.png') }}" alt="Profile">
                <span>{{ auth()->user()->name ?? 'Admin' }}</span>
            </div>
        </nav>

        <main>
            @yield('content')
        </main>
    </section>

    <!-- Modal Profile -->
    <div id="profileModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h4>{{ auth()->user()->name ?? 'Admin' }}</h4>
                <button onclick="closeProfileModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('admin.profile.updatePassword') }}">
                    @csrf
                    @method('PUT') {{-- Added PUT method --}}
                    {{-- Removed current_password field as requested --}}
                    <div class="modal-form-group">
                        <label for="password">Password Baru</label> {{-- Renamed to 'password' --}}
                        <input type="password" id="password" name="password" required minlength="6" placeholder="Minimal 6 karakter">
                    </div>
                    <div class="modal-form-group">
                        <label for="password_confirmation">Konfirmasi Password</label> {{-- Renamed to 'password_confirmation' --}}
                        <input type="password" id="password_confirmation" name="password_confirmation" required minlength="6" placeholder="Ulangi password baru">
                    </div>
                    <div class="modal-actions">
                        <button type="submit">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const menuButton = document.querySelector('#content nav .bx-menu');
        
        if(menuButton) {
            menuButton.addEventListener('click', () => {
                sidebar.classList.toggle('hide');
            });
        }

        // Auto-close sidebar on mobile when menu item is clicked
        const sidebarLinks = document.querySelectorAll('#sidebar .side-menu a');
        sidebarLinks.forEach(link => {
            link.addEventListener('click', function() {
                // Only auto-close on mobile/tablet
                if (window.innerWidth <= 900) {
                    sidebar.classList.add('hide');
                }
            });
        });

        // Auto-close sidebar on mobile when logout button is clicked
        const logoutButton = document.querySelector('#sidebar .side-menu button[type="submit"]');
        if (logoutButton) {
            logoutButton.addEventListener('click', function() {
                if (window.innerWidth <= 900) {
                    sidebar.classList.add('hide');
                }
            });
        }

        // Profile modal logic
        const profileBtn = document.getElementById('profileBtn');
        const profileModal = document.getElementById('profileModal');
        
        if(profileBtn && profileModal) {
            profileBtn.addEventListener('click', () => {
                profileModal.style.display = 'flex';
            });

            window.closeProfileModal = function() {
                profileModal.style.display = 'none';
            };

            profileModal.addEventListener('click', function(e) {
                if(e.target === profileModal) closeProfileModal();
            });

            document.addEventListener('keydown', function(e) {
                if(e.key === 'Escape') closeProfileModal();
            });
        }

        // Close sidebar on mobile when clicking outside
        document.addEventListener('click', function(e) {
            if(window.innerWidth <= 900) {
                const sidebar = document.getElementById('sidebar');
                const menuButton = document.querySelector('#content nav .bx-menu');
                
                // Check if click is outside sidebar and not on menu button
                if (!sidebar.contains(e.target) && !menuButton.contains(e.target)) {
                    sidebar.classList.add('hide');
                }
            }
        });

        // Handle window resize - ensure proper state
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            if (window.innerWidth > 900) {
                // On desktop, show sidebar
                sidebar.classList.remove('hide');
            } else {
                // On mobile, hide sidebar by default
                sidebar.classList.add('hide');
            }
        });

        // Initialize sidebar state based on screen size
        function initializeSidebar() {
            const sidebar = document.getElementById('sidebar');
            if (window.innerWidth <= 900) {
                sidebar.classList.add('hide');
            } else {
                sidebar.classList.remove('hide');
            }
        }

        // Run initialization when DOM is loaded
        document.addEventListener('DOMContentLoaded', initializeSidebar);

        // Add smooth transition for better UX
        sidebar.style.transition = 'left 0.3s ease';

        console.log('✅ Admin layout loaded with yellow theme, responsive design, and auto-close sidebar');
    </script>
    @stack('scripts')
</body>
</html>
