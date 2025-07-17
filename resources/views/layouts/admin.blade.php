<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdminHub - @yield('title', 'Dashboard')</title>
    
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        /* CSS LENGKAP ANDA DITEMPATKAN DI SINI */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }
        :root {
            --primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --white: #ffffff;
            --light: #f8f9fa;
            --gray-100: #f1f3f4;
            --gray-200: #e9ecef;
            --gray-800: #343a40;
            --shadow: 0 4px 6px rgba(0,0,0,0.1);
            --border-radius: 12px;
            --border-radius-lg: 16px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 80px;
            --navbar-height: 70px;
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            z-index: 2000;
            transition: var(--transition);
            display: flex;
            flex-direction: column;
            box-shadow: 2px 0 16px rgba(102,126,234,0.08);
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
            border-bottom: 1px solid rgba(255,255,255,0.08);
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
            transition: background 0.18s, color 0.18s;
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
            background: rgba(255, 255, 255, 0.18);
            color: #fff;
            box-shadow: 0 2px 12px rgba(102,126,234,0.08);
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
            scrollbar-color: #b3b3e6 #f8f9fa;
        }
        #sidebar .side-menu::-webkit-scrollbar {
            width: 6px;
        }
        #sidebar .side-menu::-webkit-scrollbar-thumb {
            background: #b3b3e6;
            border-radius: 8px;
        }
        #sidebar .side-menu::-webkit-scrollbar-track {
            background: transparent;
        }
        @media (max-width: 1200px) {
            #sidebar {
                width: 200px;
            }
            #content {
                width: calc(100% - 200px);
                left: 200px;
            }
        }
        @media (max-width: 900px) {
            #sidebar {
                width: 90vw;
                min-width: 160px;
                max-width: 320px;
                left: 0;
                top: 0;
                height: 100vh;
                z-index: 3000;
                box-shadow: 2px 0 16px rgba(102,126,234,0.12);
            }
            #sidebar.hide {
                left: -90vw;
            }
            #content {
                left: 0;
                width: 100vw;
            }
        }
        @media (max-width: 600px) {
            #content, main {
                width: 100vw !important;
                max-width: 100vw !important;
                min-width: 0 !important;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
                border-radius: 0 !important;
            }
            body {
                background: #f8f9fa !important;
            }
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
        }
        #content nav .bx-menu {
            font-size: 1.8rem;
            cursor: pointer;
        }
        #content nav .profile {
            margin-left: auto;
        }
        #content nav .profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
        main {
            padding: 32px 24px;
        }

        /* --- STYLING KONTEN (DASHBOARD) --- */
        .head-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 32px;
        }
        .head-title .left h1 {
            font-size: 2.5rem;
            font-weight: 800;
            background: var(--primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .btn-download {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: var(--primary);
            color: var(--white);
            border-radius: var(--border-radius);
            font-weight: 600;
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
        }
        .box-info li i {
            font-size: 3rem;
            padding: 20px;
            border-radius: 50%;
            color: var(--white);
        }
        .box-info li:nth-child(1) i { background: var(--primary); }
        .box-info li:nth-child(2) i { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
        .box-info li:nth-child(3) i { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .box-info li .text h3 { font-size: 2rem; font-weight: 700; }
        .box-info li .text p { font-size: 1rem; }

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
        }
        .table-data .order { flex-basis: 60%; }
        .table-data .head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 24px;
            border-bottom: 1px solid var(--gray-200);
        }
        .table-data table { width: 100%; border-collapse: collapse; }
        .table-data th, .table-data td { padding: 16px 24px; text-align: left; }
        .status.process {
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            background: #cce5ff;
            color: #004085;
        }
    </style>
</head>
<body>

    <section id="sidebar">
        <a href="{{ route('admin.dashboard') }}" class="brand">
            <i class='bx bxs-smile'></i>
            <span class="text">AdminHub</span>
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
                    <span class="text">Transaksi</span>
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
                <span style="font-weight:600; color:#444; margin-left:8px;">{{ auth()->user()->name ?? 'Admin' }}</span>
            </div>
        </nav>
        <main>
            @yield('content')
        </main>
    </section>

    <!-- Modal Profile (Nama & Ganti Password) -->
    <div id="profileModal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.18); align-items:center; justify-content:center;">
        <div style="background:#fff; border-radius:18px; max-width:340px; margin:auto; padding:2.2rem 1.5rem 1.5rem 1.5rem; box-shadow:0 8px 32px rgba(0,0,0,0.18); text-align:center; position:relative;">
            <button onclick="closeProfileModal()" style="position:absolute;top:12px;right:18px;background:none;border:none;font-size:1.5rem;color:#888;cursor:pointer;">&times;</button>
            <!-- Hapus gambar profile di sini -->
            <h4 style="margin:0 0 0.5rem 0; font-size:1.2rem; font-weight:700; color:#333;">{{ auth()->user()->name ?? 'Admin' }}</h4>
            <form method="POST" action="{{ route('admin.profile.password') }}" style="margin-top:1.5rem;">
                @csrf
                <div style="margin-bottom:1.2rem; text-align:left;">
                    <label for="new_password" style="font-weight:600; color:#555; font-size:0.98rem;">Password Baru</label>
                    <input type="password" id="new_password" name="new_password" required minlength="6" placeholder="Minimal 6 karakter" style="width:100%;padding:10px 12px;border-radius:8px;border:1.5px solid #e9ecef;margin-top:6px;">
                </div>
                <div style="margin-bottom:1.5rem; text-align:left;">
                    <label for="new_password_confirmation" style="font-weight:600; color:#555; font-size:0.98rem;">Konfirmasi Password</label>
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" required minlength="6" placeholder="Ulangi password baru" style="width:100%;padding:10px 12px;border-radius:8px;border:1.5px solid #e9ecef;margin-top:6px;">
                </div>
                <button type="submit" style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:#fff;border:none;border-radius:10px;padding:0.7rem 1.5rem;font-weight:600;font-size:1rem;cursor:pointer;">Update Password</button>
            </form>
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
        // Optional: close sidebar on mobile when clicking outside
        document.addEventListener('click', function(e) {
            if(window.innerWidth <= 900) {
                const sidebar = document.getElementById('sidebar');
                const menuButton = document.querySelector('#content nav .bx-menu');
                if (!sidebar.contains(e.target) && !menuButton.contains(e.target)) {
                    sidebar.classList.add('hide');
                }
            }
        });
    </script>
    @stack('scripts')
</body>
</html>