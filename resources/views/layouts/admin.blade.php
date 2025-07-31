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
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,0.3) transparent;
        }
        
        #sidebar .side-menu::-webkit-scrollbar { width: 6px; }
        #sidebar .side-menu::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.3); border-radius: 8px; }
        #sidebar .side-menu::-webkit-scrollbar-track { background: transparent; }

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

        /* --- MAIN CONTENT (Yielded) --- */
        main {
            padding: 32px 24px;
            min-height: calc(100vh - var(--navbar-height));
        }

        /* --- Animation for Modal Content --- */
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

        /* --- KODE CSS UNTUK KONTEN DASHBOARD --- */

        /* Style untuk Judul Halaman dan Breadcrumb */
        .head-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }
        .head-title .left h1 {
            font-size: 36px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        .head-title .left .breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .head-title .left .breadcrumb li a {
            color: #888;
        }
        .head-title .left .breadcrumb li a.active {
            color: var(--primary-color);
            font-weight: 600;
        }

        /* Style untuk Kotak Info (Orders, Visitors, Sales) */
        .box-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
            margin-top: 36px;
        }
        .box-info li {
            padding: 24px;
            background: white;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 24px;
            border: 2px solid var(--warning-border);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .box-info li:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(245, 158, 11, 0.1);
        }
        .box-info li i {
            width: 80px;
            height: 80px;
            border-radius: 10px;
            font-size: 36px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
        }
        .box-info li:nth-child(1) i { background: #3b82f6; } /* Biru */
        .box-info li:nth-child(2) i { background: #10b981; } /* Hijau */
        .box-info li:nth-child(3) i { background: var(--primary-dark); } /* Oranye */

        .box-info li .text h3 {
            font-size: 24px;
            font-weight: 600;
        }
        .box-info li .text p {
            color: #555;
        }

        /* Style untuk Tabel Data */
        .table-data {
            display: flex;
            gap: 24px;
            margin-top: 24px;
            width: 100%;
        }
        .table-data > .order {
            flex-grow: 1;
            border-radius: 20px;
            background: white;
            padding: 24px;
            overflow-x: auto;
            border: 2px solid var(--warning-border);
        }
        .table-data .head {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
        }
        .table-data .head h3 {
            margin-right: auto;
            font-size: 24px;
            font-weight: 600;
        }
        .table-data table {
            width: 100%;
            border-collapse: collapse;
        }
        .table-data table th {
            padding-bottom: 12px;
            font-size: 13px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        .table-data table td {
            padding: 16px 0;
        }
        .table-data table tr:hover {
            background: #f9f9f9;
        }
        .table-data .status {
            font-size: 10px;
            padding: 6px 16px;
            color: white;
            border-radius: 20px;
            font-weight: 700;
            background: #10b981;
        }

        /* Style Responsif untuk Konten Dashboard */
        @media (max-width: 900px) {
            .box-info {
                grid-template-columns: 1fr;
            }
            .table-data {
                flex-direction: column;
            }
        }
        @media (max-width: 600px) {
            .box-info li {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
            .head-title .left h1 {
                font-size: 1.75rem;
            }
        }

        /* --- MODAL STYLES --- */
        .logout-modal {
            visibility: hidden;
            opacity: 0;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 3000; /* Pastikan modal di atas segalanya */
            backdrop-filter: blur(5px);
            transition: visibility 0.3s ease, opacity 0.3s ease;
        }

        .logout-modal.show {
            visibility: visible;
            opacity: 1;
        }

        .logout-modal .modal-content {
            background: white;
            border-radius: 15px;
            width: 90%;
            max-width: 450px;
            animation: modalSlideIn 0.3s ease forwards;
            border: 2px solid var(--modal-border-color, #ef4444); /* Default ke merah */
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        }

        .logout-modal .modal-header {
            background: var(--modal-header-bg, linear-gradient(135deg, #ef4444 0%, #dc2626 100%));
            color: white;
            padding: 1.5rem;
            text-align: center;
        }

        .logout-modal .modal-header .warning-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            display: block;
        }
        
        .logout-modal .modal-header h3 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 700;
        }

        .logout-modal .modal-body {
            padding: 2rem;
            text-align: center;
            color: #4b5563;
        }
        
        .logout-modal .modal-body p:first-child {
            font-size: 1.1rem;
            margin-bottom: 1rem;
        }

        .logout-modal .warning-text {
            color: #ef4444;
            font-weight: 600;
            margin-top: 1rem;
        }

        .logout-modal .modal-footer {
            padding: 1.5rem;
            display: flex;
            justify-content: center;
            gap: 1rem;
            border-top: 1px solid #e5e7eb;
            background: #f9fafb;
        }

        .logout-modal .modal-footer .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .logout-modal .modal-footer .btn:hover {
            transform: translateY(-2px);
        }

        .logout-modal .modal-footer .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .logout-modal .modal-footer .btn-secondary:hover {
            background: #5a6268;
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
        }

        .logout-modal .modal-footer .btn-danger {
            background: var(--modal-header-bg, linear-gradient(135deg, #ef4444 0%, #dc2626 100%));
            color: white;
        }

        .logout-modal .modal-footer .btn-danger:hover {
            background: var(--modal-header-bg, linear-gradient(135deg, #dc2626 0%, #b91c1c 100%));
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
        }
        
        /* --- Responsive Design --- */
        @media (max-width: 900px) {
            #sidebar {
                width: 85vw;
                max-width: 320px;
                left: -85vw;
                transition: left 0.3s ease;
            }
            #sidebar:not(.hide) { left: 0; }
            #sidebar.hide { left: -85vw; }
            #content { left: 0; width: 100%; }
            #sidebar.hide ~ #content { left: 0; width: 100%; }
        }

        /* --- CSS Tambahan untuk Pesan Error/Status di dalam Modal --- */
        .error-message, .status-message {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 1rem;
            text-align: left;
            border: 1px solid;
            font-weight: 500;
        }
        .error-message {
            background: #fee2e2; border-color: #fca5a5; color: #b91c1c;
        }
        .error-message ul {
            padding-left: 20px;
        }
        .status-message {
            background: #dcfce7; border-color: #86efac; color: #15803d;
        }
        .form-control {
            width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #d1d5db;
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
                {{-- Button to trigger the logout modal --}}
                <button type="button" onclick="showLogoutModal()">
                    <i class='bx bxs-log-out-circle'></i>
                    <span class="text">Logout</span>
                </button>
            </li>
        </ul>
    </section>

    <section id="content">
        <nav>
            <i class='bx bx-menu'></i>
            <a href="#" class="nav-link">@yield('title', 'Page')</a>
            <div style="margin-left: auto;"></div>
            {{-- ✅ PERBAIKAN: Ini sudah menjadi tombol pemicu modal --}}
            <button type="button" onclick="showChangePasswordModal()" class="profile" style="background:none; border:none; cursor:pointer;">
                <img src="{{ asset('img/people.png') }}" alt="Profile">
                <span>{{ auth()->guard('admin')->user()->name ?? 'Admin' }}</span>
            </button>
        </nav>

        <main>
            @yield('content')
        </main>
    </section>

    {{-- Logout Confirmation Modal --}}
    <div id="logoutModal" class="logout-modal">
        <div class="modal-content">
            <div class="modal-header">
                <i class='bx bx-error-circle warning-icon'></i>
                <h3>Konfirmasi Logout</h3>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin mengakhiri sesi ini?</p>
                <p class="warning-text">Anda akan diminta untuk login kembali.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeLogoutModal()">
                    <i class='bx bx-x'></i> Batal
                </button>
                <form id="logoutForm" method="POST" action="{{ route('admin.logout') }}">
                    @csrf 
                    <button type="submit" class="btn btn-danger">
                        <i class='bx bxs-log-out-circle'></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- ✅ KODE HTML MODAL GANTI PASSWORD DITARUH DI SINI --}}
    <div id="changePasswordModal" class="logout-modal" style="--modal-border-color: #3b82f6; --modal-header-bg: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
        <div class="modal-content">
            <form id="changePasswordForm" method="POST" action="{{ route('admin.profile.updatePassword') }}">
                @csrf
                @method('PUT')

                <div class="modal-header" style="background: var(--modal-header-bg);">
                    <i class='bx bxs-key warning-icon'></i>
                    <h3>Ganti Password</h3>
                </div>
                <div class="modal-body" style="text-align: left;">
                    
                    {{-- Menampilkan pesan error validasi jika ada --}}
                    @if ($errors->any())
                        <div class="error-message">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    {{-- Menampilkan pesan sukses --}}
                    @if (session('status'))
                         <div class="status-message">{{ session('status') }}</div>
                    @endif

                    <div class="form-group" style="margin-bottom: 1rem;">
                        <label for="current_password" style="font-weight: 600; color: #4b5563; margin-bottom: 0.5rem; display: block;">Password Saat Ini</label>
                        <input type="password" name="current_password" required class="form-control">
                    </div>

                    <div class="form-group" style="margin-bottom: 1rem;">
                        <label for="new_password" style="font-weight: 600; color: #4b5563; margin-bottom: 0.5rem; display: block;">Password Baru</label>
                        <input type="password" name="new_password" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="new_confirm_password" style="font-weight: 600; color: #4b5563; margin-bottom: 0.5rem; display: block;">Konfirmasi Password Baru</label>
                        <input type="password" name="new_confirm_password" required class="form-control">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeChangePasswordModal()">
                        <i class='bx bx-x'></i> Batal
                    </button>
                    <button type="submit" class="btn btn-danger" style="background: var(--modal-header-bg);">
                        <i class='bx bx-save'></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>


    <script>
        // Sidebar toggle logic
        const sidebar = document.getElementById('sidebar');
        const menuButton = document.querySelector('#content nav .bx-menu');
        
        if(menuButton) {
            menuButton.addEventListener('click', () => {
                sidebar.classList.toggle('hide');
            });
        }

        // Auto-close sidebar on mobile when an item is clicked
        document.querySelectorAll('#sidebar .side-menu a').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 900 && !sidebar.classList.contains('hide')) {
                    sidebar.classList.add('hide');
                }
            });
        });

        // Initialize sidebar state based on screen size
        function initializeSidebar() {
            if (window.innerWidth <= 900) {
                sidebar.classList.add('hide');
            } else {
                sidebar.classList.remove('hide');
            }
        }
        document.addEventListener('DOMContentLoaded', initializeSidebar);
        window.addEventListener('resize', initializeSidebar);

        // --- Logout Confirmation Modal Logic ---
        const logoutModal = document.getElementById('logoutModal');
        function showLogoutModal() { if (logoutModal) logoutModal.classList.add('show'); }
        function closeLogoutModal() { if (logoutModal) logoutModal.classList.remove('show'); }
        if (logoutModal) {
            logoutModal.addEventListener('click', function(e) { if (e.target === logoutModal) closeLogoutModal(); });
            document.addEventListener('keydown', function(e) { if (e.key === 'Escape' && logoutModal.classList.contains('show')) closeLogoutModal(); });
        }

        // ✅ KODE JAVASCRIPT GANTI PASSWORD DITARUH DI SINI
        const passwordModal = document.getElementById('changePasswordModal');
    function showChangePasswordModal() { if (passwordModal) passwordModal.classList.add('show'); }
    function closeChangePasswordModal() { if (passwordModal) passwordModal.classList.remove('show'); }
    if (passwordModal) {
        passwordModal.addEventListener('click', function(e) { if (e.target === passwordModal) closeChangePasswordModal(); });
        document.addEventListener('keydown', function(e) { if (e.key === 'Escape' && passwordModal.classList.contains('show')) closeChangePasswordModal(); });
    }

    // --- Logic to run after the page is fully loaded ---
    document.addEventListener('DOMContentLoaded', function() {
        initializeSidebar(); // Initialize sidebar state on load

        // Check if change password modal should be shown due to errors
        @if ($errors->any())
            showChangePasswordModal();
        @endif
        
        // Check if change password modal should be shown due to a success message
        @if (session('status'))
            showChangePasswordModal();
            setTimeout(function() {
                closeChangePasswordModal();
            }, 3000); // Auto-close after 3 seconds
        @endif
    });

    // Also re-check sidebar state on window resize
    window.addEventListener('resize', initializeSidebar);

        console.log('✅ Admin layout loaded with yellow theme and consistent modals.');
    </script>
    @stack('scripts')
</body>
</html>
