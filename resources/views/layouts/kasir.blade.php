<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Kasir System')</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            line-height: 1.5;
            min-height: 100vh;
        }

        /* Clean Navigation Bar */
        .navbar {
            background: white;
            border-bottom: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .navbar-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 70px;
            flex-wrap: wrap;
        }

        /* Brand Section */
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: #1e293b;
            flex-shrink: 0;
        }
        .brand-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            box-shadow: 0 2px 4px rgba(245, 158, 11, 0.3);
        }
        .brand-text {
            font-size: 1.125rem;
            font-weight: 700;
            color: #1e293b;
        }

        /* Navigation Right Section */
        .navbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        /* User Info */
        .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            background: #fffbeb;
            border: 1px solid #fed7aa;
            border-radius: 8px;
        }
        .user-avatar {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.875rem;
        }
        .user-details {
            display: flex;
            flex-direction: column;
        }
        .user-name {
            font-size: 0.875rem;
            font-weight: 600;
            color: #1e293b;
            line-height: 1.2;
        }
        .user-role {
            font-size: 0.75rem;
            color: #92400e;
            line-height: 1.2;
        }

        /* Status Indicator */
        .status-badge {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.375rem 0.75rem;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 6px;
        }
        .status-dot {
            width: 6px;
            height: 6px;
            background: #10b981;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .status-text {
            font-size: 0.75rem;
            font-weight: 600;
            color: #059669;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }

        /* Navigation Links */
        .nav-links {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            text-decoration: none;
            color: #64748b;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 6px;
            transition: all 0.2s ease;
            border: 1px solid transparent;
            position: relative;
            white-space: nowrap;
        }
        .nav-link:hover {
            color: #f59e0b;
            background: #fffbeb;
            border-color: #fed7aa;
        }
        .nav-link.active {
            color: #f59e0b;
            background: #fffbeb;
            border-color: #fbbf24;
            box-shadow: 0 1px 3px rgba(245, 158, 11, 0.2);
        }

        /* Order notification badge */
        .nav-link .notification-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            background: #ef4444;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.625rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid white;
        }

        /* Logout Button */
        .logout-form {
            display: inline-block;
        }
        .logout-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(239, 68, 68, 0.3);
        }
        .logout-btn:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(239, 68, 68, 0.4);
        }
        .logout-btn:active {
            transform: translateY(0);
        }

        /* Main Content */
        .main-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 1rem;
            min-height: calc(100vh - 70px);
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        .modal-content {
            background: white;
            border-radius: 16px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            max-width: 400px;
            width: 90%;
            margin: 1rem;
            transform: scale(0.9) translateY(20px);
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
        }
        .modal-overlay.active .modal-content {
            transform: scale(1) translateY(0);
        }
        .modal-header {
            padding: 1.5rem 1.5rem 1rem 1.5rem;
            text-align: center;
        }
        .modal-icon {
            width: 64px;
            height: 64px;
            background: #fef2f2;
            border: 2px solid #fecaca;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem auto;
            color: #ef4444;
        }
        .modal-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }
        .modal-message {
            font-size: 0.875rem;
            color: #64748b;
            line-height: 1.5;
        }
        .modal-body {
            padding: 0 1.5rem 1.5rem 1.5rem;
        }
        .modal-actions {
            display: flex;
            gap: 0.75rem;
            justify-content: center;
        }
        .modal-btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            min-width: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        .modal-btn-cancel {
            background: #f1f5f9;
            color: #64748b;
            border: 1px solid #e2e8f0;
        }
        .modal-btn-cancel:hover {
            background: #e2e8f0;
            color: #475569;
        }
        .modal-btn-confirm {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }
        .modal-btn-confirm:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        }
        .modal-btn-confirm:active,
        .modal-btn-cancel:active {
            transform: translateY(1px);
        }
        .modal-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        /* Loading State */
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .navbar-container {
                padding: 0 1rem;
            }
            .main-content {
                padding: 1rem;
            }
        }

        @media (max-width: 1024px) {
            .navbar-container {
                height: auto;
                min-height: 70px;
                padding: 0.75rem 1rem;
            }
            .navbar-right {
                gap: 0.75rem;
            }
            .nav-links {
                gap: 0.25rem;
            }
            .nav-link {
                padding: 0.375rem 0.75rem;
                font-size: 0.8125rem;
            }
            .main-content {
                padding: 1rem 0.75rem;
            }
        }

        @media (max-width: 768px) {
            .navbar-container {
                flex-direction: column;
                gap: 1rem;
                padding: 1rem;
                height: auto;
            }
            .navbar-brand {
                align-self: flex-start;
            }
            .navbar-right {
                width: 100%;
                justify-content: space-between;
                flex-wrap: wrap;
                gap: 0.75rem;
            }
            .user-info {
                padding: 0.375rem 0.75rem;
                order: 1;
            }
            .user-details {
                display: none;
            }
            .status-badge {
                padding: 0.25rem 0.5rem;
                order: 2;
            }
            .status-text {
                display: none;
            }
            .nav-links {
                order: 3;
                flex: 1;
                justify-content: center;
            }
            .nav-link span {
                display: none;
            }
            .logout-form {
                order: 4;
            }
            .logout-btn {
                padding: 0.375rem 0.75rem;
                font-size: 0.8125rem;
            }
            .logout-btn span {
                display: none;
            }
            .main-content {
                padding: 0.75rem 0.5rem;
            }
            .modal-content {
                margin: 0.5rem;
                width: calc(100% - 1rem);
            }
            .modal-header {
                padding: 1.25rem 1.25rem 0.75rem 1.25rem;
            }
            .modal-body {
                padding: 0 1.25rem 1.25rem 1.25rem;
            }
            .modal-icon {
                width: 56px;
                height: 56px;
            }
            .modal-title {
                font-size: 1.125rem;
            }
            .modal-actions {
                flex-direction: column;
            }
            .modal-btn {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .navbar-container {
                padding: 0.75rem 0.5rem;
            }
            .brand-text {
                font-size: 1rem;
            }
            .brand-icon {
                width: 32px;
                height: 32px;
            }
            .navbar-right {
                gap: 0.5rem;
            }
            .nav-links {
                gap: 0.125rem;
            }
            .nav-link {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }
            .user-info {
                padding: 0.25rem 0.5rem;
            }
            .user-avatar {
                width: 28px;
                height: 28px;
                font-size: 0.75rem;
            }
            .status-badge {
                padding: 0.125rem 0.375rem;
            }
            .logout-btn {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }
            .main-content {
                padding: 0.5rem 0.25rem;
            }
        }

        /* Smooth Transitions */
        * {
            transition: color 0.15s ease, background-color 0.15s ease, border-color 0.15s ease;
        }

        /* Animations */
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .modal-content {
            animation: fadeIn 0.3s ease-out;
        }

        /* Mobile-first approach for better performance */
        @media (min-width: 769px) {
            .navbar-container {
                flex-direction: row;
                height: 70px;
                padding: 0 2rem;
            }
            .navbar-right {
                width: auto;
                gap: 1.5rem;
            }
            .user-details {
                display: flex !important;
            }
            .status-text {
                display: inline !important;
            }
            .nav-link span {
                display: inline !important;
            }
            .logout-btn span {
                display: inline !important;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container">
            <a href="{{ route('kasir.dashboard') }}" class="navbar-brand">
                <div class="brand-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 3h18l-1 13H4L3 3z"></path>
                        <path d="M16 16a2 2 0 1 1-4 0 2 2 0 0 1 4 0z"></path>
                        <path d="M23 16a2 2 0 1 1-4 0 2 2 0 0 1 4 0z"></path>
                    </svg>
                </div>
                <span class="brand-text">KASIR SYSTEM</span>
            </a>
            <div class="navbar-right">
                <div class="user-info">
                    <div class="user-avatar">A</div>
                    <div class="user-details">
                        <span class="user-name">Admin</span>
                        <span class="user-role">Kasir</span>
                    </div>
                </div>
                <div class="status-badge">
                    <div class="status-dot"></div>
                    <span class="status-text">Online</span>
                </div>
                <div class="nav-links">
                    <a href="{{ route('kasir.shift') }}" class="nav-link {{ request()->routeIs('kasir.shift') ? 'active' : '' }}">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12,6 12,12 16,14"></polyline>
                        </svg>
                        <span>Shift</span>
                    </a>
                    <a href="{{ route('kasir.dashboard') }}" class="nav-link {{ request()->routeIs('kasir.dashboard') ? 'active' : '' }}">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                            <rect x="7" y="7" width="3" height="3"></rect>
                            <rect x="14" y="7" width="3" height="3"></rect>
                            <rect x="7" y="14" width="3" height="3"></rect>
                            <rect x="14" y="14" width="3" height="3"></rect>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('kasir.orders.index') }}" class="nav-link {{ request()->routeIs('kasir.orders.*') ? 'active' : '' }}">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                            <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                        </svg>
                        <span>Orders</span>
                        <span class="notification-badge" id="pendingOrdersBadge" style="display: none;">0</span>
                    </a>
                    <a href="{{ route('kasir.transactions.index') }}" class="nav-link {{ request()->routeIs('kasir.transactions.*') ? 'active' : '' }}">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                            <line x1="1" y1="10" x2="23" y2="10"></line>
                        </svg>
                        <span>Transaksi</span>
                    </a>
                </div>
                <form method="POST" action="{{ route('kasir.logout') }}" class="logout-form">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16,17 21,12 16,7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Logout Confirmation Modal -->
    <div class="modal-overlay" id="logoutModal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16,17 21,12 16,7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                </div>
                <h3 class="modal-title">Konfirmasi Logout</h3>
                <p class="modal-message">Apakah Anda yakin ingin keluar dari sistem kasir? Semua data yang belum disimpan akan hilang.</p>
            </div>
            <div class="modal-body">
                <div class="modal-actions">
                    <button type="button" class="modal-btn modal-btn-cancel" id="cancelLogout">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                        Batal
                    </button>
                    <button type="button" class="modal-btn modal-btn-confirm" id="confirmLogout">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20,6 9,17 4,12"></polyline>
                        </svg>
                        Ya, Logout
                    </button>
                </div>
            </div>
        </div>
    </div>

    <main class="main-content">
        @yield('content')
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const logoutBtn = document.getElementById('logoutBtn');
            const logoutModal = document.getElementById('logoutModal');
            const cancelLogout = document.getElementById('cancelLogout');
            const confirmLogout = document.getElementById('confirmLogout');
            const logoutForm = document.getElementById('logoutForm');

            // Show modal when logout button is clicked
            if (logoutBtn) {
                logoutBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    logoutModal.classList.add('active');
                    document.body.style.overflow = 'hidden';
                });
            }

            // Hide modal when cancel button is clicked
            if (cancelLogout) {
                cancelLogout.addEventListener('click', function() {
                    hideModal();
                });
            }

            // Hide modal when clicking outside
            logoutModal.addEventListener('click', function(e) {
                if (e.target === logoutModal) {
                    hideModal();
                }
            });

            // Hide modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && logoutModal.classList.contains('active')) {
                    hideModal();
                }
            });

            // Confirm logout
            if (confirmLogout) {
                confirmLogout.addEventListener('click', function() {
                    confirmLogout.disabled = true;
                    confirmLogout.innerHTML = `
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="animation: spin 1s linear infinite;">
                            <path d="M21 12a9 9 0 11-6.219-8.56"/>
                        </svg>
                        Logging out...
                    `;
                    
                    cancelLogout.disabled = true;
                    
                    setTimeout(() => {
                        if (logoutForm) {
                            logoutForm.submit();
                        }
                    }, 500);
                });
            }

            function hideModal() {
                logoutModal.classList.remove('active');
                document.body.style.overflow = '';
                
                if (confirmLogout) {
                    confirmLogout.disabled = false;
                    confirmLogout.innerHTML = `
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20,6 9,17 4,12"></polyline>
                        </svg>
                        Ya, Logout
                    `;
                }
                if (cancelLogout) {
                    cancelLogout.disabled = false;
                }
            }

            // Add loading state to navigation links
            document.querySelectorAll('.nav-link').forEach(link => {
                link.addEventListener('click', function() {
                    if (this.href && !this.href.includes('#')) {
                        this.classList.add('loading');
                    }
                });
            });

            // Load pending orders count
            loadPendingOrdersCount();
            console.log('✅ Kasir System loaded');
        });

        function loadPendingOrdersCount() {
            if (typeof window.kasirOrdersStatsUrl !== 'undefined') {
                fetch(window.kasirOrdersStatsUrl)
                    .then(response => response.json())
                    .then(data => {
                        const badge = document.getElementById('pendingOrdersBadge');
                        if (badge && data.pending_orders > 0) {
                            badge.textContent = data.pending_orders;
                            badge.style.display = 'flex';
                        } else if (badge) {
                            badge.style.display = 'none';
                        }
                    })
                    .catch(error => {
                        console.log('Could not load orders stats:', error);
                    });
            }
        }

        setInterval(loadPendingOrdersCount, 30000);
    </script>
    @stack('scripts')
</body>
</html>
