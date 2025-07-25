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

        /* Enhanced Navigation Bar */
        .navbar {
            background: white;
            border-bottom: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
            transition: box-shadow 0.3s ease, background-color 0.3s ease;
        }

        .navbar.scrolled {
            box-shadow: 0 4px 20px 0 rgba(0, 0, 0, 0.15);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }

        .navbar-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 70px;
            position: relative;
        }

        /* Brand Section */
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: #1e293b;
            flex-shrink: 0;
            z-index: 101;
        }

        .brand-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            box-shadow: 0 4px 8px rgba(245, 158, 11, 0.3);
        }

        .brand-text {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
        }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            flex-direction: column;
            gap: 4px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .mobile-menu-toggle:hover {
            background: #f1f5f9;
        }

        .mobile-menu-toggle span {
            width: 24px;
            height: 3px;
            background: #64748b;
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        .mobile-menu-toggle.active span:nth-child(1) {
            transform: rotate(45deg) translate(6px, 6px);
        }

        .mobile-menu-toggle.active span:nth-child(2) {
            opacity: 0;
        }

        .mobile-menu-toggle.active span:nth-child(3) {
            transform: rotate(-45deg) translate(6px, -6px);
        }

        /* Navigation Right Section */
        .navbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        /* Mobile Navigation Overlay */
        .mobile-nav-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            z-index: 99;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .mobile-nav-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* Mobile Navigation Menu */
        .mobile-nav-menu {
            position: fixed;
            top: 0;
            right: -100%;
            width: 320px;
            height: 100vh;
            background: white;
            box-shadow: -4px 0 20px rgba(0, 0, 0, 0.1);
            z-index: 100;
            transition: right 0.3s ease;
            overflow-y: auto;
        }

        .mobile-nav-menu.active {
            right: 0;
        }

        .mobile-nav-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }

        .mobile-nav-content {
            padding: 1.5rem;
        }

        .mobile-user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: #fffbeb;
            border: 1px solid #fed7aa;
            border-radius: 12px;
            margin-bottom: 1.5rem;
        }

        .mobile-user-avatar {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1.125rem;
        }

        .mobile-user-details h3 {
            font-size: 1rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .mobile-user-details p {
            font-size: 0.875rem;
            color: #92400e;
        }

        .mobile-status-badge {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .mobile-nav-links {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .mobile-nav-link {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            text-decoration: none;
            color: #64748b;
            font-size: 1rem;
            font-weight: 500;
            border-radius: 10px;
            transition: all 0.2s ease;
            border: 1px solid transparent;
            position: relative;
        }

        .mobile-nav-link:hover {
            color: #f59e0b;
            background: #fffbeb;
            border-color: #fed7aa;
        }

        .mobile-nav-link.active {
            color: #f59e0b;
            background: #fffbeb;
            border-color: #fbbf24;
            box-shadow: 0 2px 4px rgba(245, 158, 11, 0.2);
        }

        .mobile-logout-btn {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            padding: 1rem;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 8px rgba(239, 68, 68, 0.3);
        }

        .mobile-logout-btn:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            transform: translateY(-1px);
            box-shadow: 0 6px 12px rgba(239, 68, 68, 0.4);
        }

        /* User Info */
        .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            background: #fffbeb;
            border: 1px solid #fed7aa;
            border-radius: 10px;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
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
            padding: 0.5rem 0.75rem;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
        }

        .status-dot {
            width: 8px;
            height: 8px;
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
            padding: 0.625rem 1rem;
            text-decoration: none;
            color: #64748b;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.2s ease;
            border: 1px solid transparent;
            position: relative;
            white-space: nowrap;
        }

        .nav-link:hover {
            color: #f59e0b;
            background: #fffbeb;
            border-color: #fed7aa;
            transform: translateY(-1px);
        }

        .nav-link.active {
            color: #f59e0b;
            background: #fffbeb;
            border-color: #fbbf24;
            box-shadow: 0 2px 4px rgba(245, 158, 11, 0.2);
        }

        /* Order notification badge */
        .nav-link .notification-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background: #ef4444;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.625rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid white;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 20%, 53%, 80%, 100% {
                transform: translate3d(0,0,0);
            }
            40%, 43% {
                transform: translate3d(0, -8px, 0);
            }
            70% {
                transform: translate3d(0, -4px, 0);
            }
            90% {
                transform: translate3d(0, -2px, 0);
            }
        }

        /* Logout Button */
        .logout-form {
            display: inline-block;
        }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1rem;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 2px 4px rgba(239, 68, 68, 0.3);
        }

        .logout-btn:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(239, 68, 68, 0.4);
        }

        .logout-btn:active {
            transform: translateY(0);
        }

        /* Main Content */
        .main-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 1.5rem;
            min-height: calc(100vh - 70px);
        }

        /* Enhanced Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(8px);
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
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            max-width: 420px;
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
            padding: 2rem 2rem 1rem 2rem;
            text-align: center;
        }

        .modal-icon {
            width: 72px;
            height: 72px;
            background: #fef2f2;
            border: 2px solid #fecaca;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem auto;
            color: #ef4444;
        }

        .modal-title {
            font-size: 1.375rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.75rem;
        }

        .modal-message {
            font-size: 0.9375rem;
            color: #64748b;
            line-height: 1.6;
        }

        .modal-body {
            padding: 0 2rem 2rem 2rem;
        }

        .modal-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .modal-btn {
            padding: 0.875rem 1.75rem;
            border: none;
            border-radius: 10px;
            font-size: 0.9375rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            min-width: 120px;
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
            transform: translateY(-1px);
        }

        .modal-btn-confirm {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            box-shadow: 0 2px 4px rgba(239, 68, 68, 0.3);
        }

        .modal-btn-confirm:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(239, 68, 68, 0.4);
        }

        .modal-btn-confirm:active,
        .modal-btn-cancel:active {
            transform: translateY(0);
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

        /* Enhanced Responsive Design */
        @media (max-width: 1200px) {
            .navbar-container {
                padding: 0 1rem;
            }
            
            .main-content {
                padding: 1rem;
            }
        }

        /* Tablet Landscape (1024px and below) */
        @media (max-width: 1024px) {
            .navbar-container {
                padding: 0 1rem;
                gap: 1rem;
            }
            
            .navbar-right {
                gap: 0.75rem;
            }
            
            .user-info {
                padding: 0.375rem 0.75rem;
            }
            
            .user-details {
                display: none;
            }
            
            .status-badge {
                padding: 0.375rem 0.5rem;
            }
            
            .status-text {
                font-size: 0.625rem;
            }
            
            .nav-links {
                gap: 0.25rem;
            }
            
            .nav-link {
                padding: 0.5rem 0.75rem;
                font-size: 0.8125rem;
            }
            
            /* Keep span text visible on tablets */
            .nav-link span {
                display: inline;
            }
            
            .logout-btn {
                padding: 0.5rem 0.75rem;
                font-size: 0.8125rem;
            }
            
            /* Keep logout text visible on tablets */
            .logout-btn span {
                display: inline;
            }
            
            .main-content {
                padding: 1rem 0.75rem;
            }
        }

        /* Tablet Portrait (768px to 900px) */
        @media (max-width: 900px) and (min-width: 769px) {
            .navbar-container {
                height: auto;
                min-height: 70px;
                padding: 0.75rem 1rem;
                flex-wrap: wrap;
                justify-content: space-between;
            }
            
            .navbar-brand {
                order: 1;
                flex-shrink: 0;
            }
            
            .navbar-right {
                order: 2;
                width: auto;
                flex-wrap: wrap;
                gap: 0.5rem;
            }
            
            .user-info {
                order: 1;
                padding: 0.375rem 0.75rem;
            }
            
            .user-details {
                display: none;
            }
            
            .status-badge {
                order: 2;
                padding: 0.375rem 0.5rem;
            }
            
            .status-text {
                font-size: 0.625rem;
            }
            
            .nav-links {
                order: 3;
                gap: 0.25rem;
                flex-wrap: nowrap;
            }
            
            .nav-link {
                padding: 0.375rem 0.5rem;
                font-size: 0.75rem;
                min-width: auto;
            }
            
            /* Keep span text visible on tablet portrait */
            .nav-link span {
                display: inline;
            }
            
            .logout-form {
                order: 4;
            }
            
            .logout-btn {
                padding: 0.375rem 0.5rem;
                font-size: 0.75rem;
            }
            
            /* Keep logout text visible on tablet portrait */
            .logout-btn span {
                display: inline;
            }
        }

        /* Mobile (768px and below) */
        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: flex;
            }
            
            .navbar-right {
                display: none;
            }
            
            .navbar-container {
                height: 70px;
                padding: 0 1rem;
                flex-wrap: nowrap;
            }
            
            .brand-text {
                font-size: 1.125rem;
            }
            
            .brand-icon {
                width: 36px;
                height: 36px;
            }
            
            .main-content {
                padding: 1rem 0.75rem;
            }
            
            .modal-content {
                margin: 1rem;
                width: calc(100% - 2rem);
            }
            
            .modal-header {
                padding: 1.5rem 1.5rem 1rem 1.5rem;
            }
            
            .modal-body {
                padding: 0 1.5rem 1.5rem 1.5rem;
            }
            
            .modal-icon {
                width: 64px;
                height: 64px;
            }
            
            .modal-title {
                font-size: 1.25rem;
            }
            
            .modal-actions {
                flex-direction: column;
            }
            
            .modal-btn {
                width: 100%;
            }
        }

        /* Small Mobile (480px and below) */
        @media (max-width: 480px) {
            .navbar-container {
                padding: 0 0.75rem;
            }
            
            .brand-text {
                font-size: 1rem;
            }
            
            .brand-icon {
                width: 32px;
                height: 32px;
            }
            
            .main-content {
                padding: 0.75rem 0.5rem;
            }
            
            .mobile-nav-menu {
                width: 100%;
                right: -100%;
            }
        }

        /* Large screens - restore full functionality */
        @media (min-width: 1025px) {
            .navbar-container {
                flex-direction: row;
                height: 70px;
                padding: 0 2rem;
                flex-wrap: nowrap;
            }
            
            .navbar-right {
                width: auto;
                gap: 1.5rem;
                flex-wrap: nowrap;
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

        /* Specific tablet landscape optimization */
        @media (min-width: 769px) and (max-width: 1024px) and (orientation: landscape) {
            .navbar-container {
                height: 70px;
                padding: 0 1.5rem;
                flex-wrap: nowrap;
            }
            
            .navbar-right {
                gap: 0.75rem;
                flex-wrap: nowrap;
            }
            
            .user-info {
                min-width: 60px;
            }
            
            .status-badge {
                min-width: 80px;
            }
            
            .nav-links {
                gap: 0.375rem;
            }
            
            .nav-link {
                padding: 0.5rem 0.625rem;
                font-size: 0.8125rem;
                white-space: nowrap;
            }
            
            /* Show text on tablet landscape */
            .nav-link span {
                display: inline;
            }
            
            .logout-btn {
                padding: 0.5rem 0.625rem;
                font-size: 0.8125rem;
            }
            
            /* Show logout text on tablet landscape */
            .logout-btn span {
                display: inline;
            }
        }

        /* Tablet portrait optimization */
        @media (min-width: 769px) and (max-width: 1024px) and (orientation: portrait) {
            .navbar-container {
                height: auto;
                min-height: 70px;
                padding: 0.75rem 1rem;
                flex-wrap: wrap;
                gap: 0.75rem;
            }
            
            .navbar-brand {
                flex: 0 0 auto;
            }
            
            .navbar-right {
                flex: 1;
                justify-content: flex-end;
                gap: 0.5rem;
                flex-wrap: wrap;
            }
            
            .user-info,
            .status-badge {
                flex: 0 0 auto;
            }
            
            .nav-links {
                flex: 1;
                justify-content: center;
                gap: 0.25rem;
                min-width: 100%;
                order: 3;
                margin-top: 0.5rem;
            }
            
            .logout-form {
                flex: 0 0 auto;
                order: 2;
            }
            
            .logout-btn {
                padding: 0.375rem 0.5rem;
                font-size: 0.75rem;
            }
            
            /* Show logout text on tablet portrait */
            .logout-btn span {
                display: inline;
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

        /* Enhanced hover effects */
        .nav-link svg,
        .mobile-nav-link svg {
            transition: transform 0.2s ease;
        }

        .nav-link:hover svg,
        .mobile-nav-link:hover svg {
            transform: scale(1.1);
        }

        /* Focus states for accessibility */
        .nav-link:focus,
        .mobile-nav-link:focus,
        .logout-btn:focus,
        .modal-btn:focus {
            outline: 2px solid #f59e0b;
            outline-offset: 2px;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container">
            <a href="{{ route('kasir.dashboard') }}" class="navbar-brand">
                <div class="brand-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 3h18l-1 13H4L3 3z"></path>
                        <path d="M16 16a2 2 0 1 1-4 0 2 2 0 0 1 4 0z"></path>
                        <path d="M23 16a2 2 0 1 1-4 0 2 2 0 0 1 4 0z"></path>
                    </svg>
                </div>
                <span class="brand-text">KASIR SYSTEM</span>
            </a>

            <!-- Desktop Navigation -->
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
                
                <form method="POST" action="{{ route('kasir.logout') }}" class="logout-form" id="logoutForm">
                    @csrf
                    <button type="button" class="logout-btn" id="logoutBtn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16,17 21,12 16,7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                        <span>Logout</span>
                    </button>
                </form>
            </div>

            <!-- Mobile Menu Toggle -->
            <button class="mobile-menu-toggle" id="mobileMenuToggle">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </nav>

    <!-- Mobile Navigation Overlay -->
    <div class="mobile-nav-overlay" id="mobileNavOverlay"></div>

    <!-- Mobile Navigation Menu -->
    <div class="mobile-nav-menu" id="mobileNavMenu">
        <div class="mobile-nav-header">
            <h2 style="font-size: 1.25rem; font-weight: 700; margin: 0;">Menu Navigasi</h2>
        </div>
        <div class="mobile-nav-content">
            <div class="mobile-user-info">
                <div class="mobile-user-avatar">A</div>
                <div class="mobile-user-details">
                    <h3>Admin</h3>
                    <p>Kasir</p>
                </div>
            </div>

            <div class="mobile-status-badge">
                <div class="status-dot"></div>
                <span class="status-text">Online</span>
            </div>

            <div class="mobile-nav-links">
                <a href="{{ route('kasir.shift') }}" class="mobile-nav-link {{ request()->routeIs('kasir.shift') ? 'active' : '' }}">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12,6 12,12 16,14"></polyline>
                    </svg>
                    <span>Shift</span>
                </a>
                <a href="{{ route('kasir.dashboard') }}" class="mobile-nav-link {{ request()->routeIs('kasir.dashboard') ? 'active' : '' }}">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <rect x="7" y="7" width="3" height="3"></rect>
                        <rect x="14" y="7" width="3" height="3"></rect>
                        <rect x="7" y="14" width="3" height="3"></rect>
                        <rect x="14" y="14" width="3" height="3"></rect>
                    </svg>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('kasir.orders.index') }}" class="mobile-nav-link {{ request()->routeIs('kasir.orders.*') ? 'active' : '' }}">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                        <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                    </svg>
                    <span>Orders</span>
                    <span class="notification-badge" style="display: none;">0</span>
                </a>
                <a href="{{ route('kasir.transactions.index') }}" class="mobile-nav-link {{ request()->routeIs('kasir.transactions.*') ? 'active' : '' }}">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                        <line x1="1" y1="10" x2="23" y2="10"></line>
                    </svg>
                    <span>Transaksi</span>
                </a>
            </div>

            <form method="POST" action="{{ route('kasir.logout') }}" class="logout-form">
                @csrf
                <button type="button" class="mobile-logout-btn" id="mobileLogoutBtn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16,17 21,12 16,7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Logout Confirmation Modal -->
    <div class="modal-overlay" id="logoutModal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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
            // Mobile menu elements
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const mobileNavOverlay = document.getElementById('mobileNavOverlay');
            const mobileNavMenu = document.getElementById('mobileNavMenu');
            
            // Logout elements
            const logoutBtn = document.getElementById('logoutBtn');
            const mobileLogoutBtn = document.getElementById('mobileLogoutBtn');
            const logoutModal = document.getElementById('logoutModal');
            const cancelLogout = document.getElementById('cancelLogout');
            const confirmLogout = document.getElementById('confirmLogout');
            const logoutForm = document.getElementById('logoutForm');

            // Mobile menu functionality
            function toggleMobileMenu() {
                const isActive = mobileNavMenu.classList.contains('active');
                
                if (isActive) {
                    closeMobileMenu();
                } else {
                    openMobileMenu();
                }
            }

            function openMobileMenu() {
                mobileNavOverlay.classList.add('active');
                mobileNavMenu.classList.add('active');
                mobileMenuToggle.classList.add('active');
                document.body.style.overflow = 'hidden';
            }

            function closeMobileMenu() {
                mobileNavOverlay.classList.remove('active');
                mobileNavMenu.classList.remove('active');
                mobileMenuToggle.classList.remove('active');
                document.body.style.overflow = '';
            }

            // Mobile menu event listeners
            if (mobileMenuToggle) {
                mobileMenuToggle.addEventListener('click', toggleMobileMenu);
            }

            if (mobileNavOverlay) {
                mobileNavOverlay.addEventListener('click', closeMobileMenu);
            }

            // Close mobile menu when clicking on nav links
            document.querySelectorAll('.mobile-nav-link').forEach(link => {
                link.addEventListener('click', closeMobileMenu);
            });

            // Logout modal functionality
            function showLogoutModal() {
                logoutModal.classList.add('active');
                document.body.style.overflow = 'hidden';
                closeMobileMenu(); // Close mobile menu if open
            }

            function hideLogoutModal() {
                logoutModal.classList.remove('active');
                document.body.style.overflow = '';
                
                // Reset confirm button
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

            // Logout event listeners
            if (logoutBtn) {
                logoutBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    showLogoutModal();
                });
            }

            if (mobileLogoutBtn) {
                mobileLogoutBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    showLogoutModal();
                });
            }

            if (cancelLogout) {
                cancelLogout.addEventListener('click', hideLogoutModal);
            }

            // Hide modal when clicking outside
            if (logoutModal) {
                logoutModal.addEventListener('click', function(e) {
                    if (e.target === logoutModal) {
                        hideLogoutModal();
                    }
                });
            }

            // Hide modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    if (logoutModal.classList.contains('active')) {
                        hideLogoutModal();
                    }
                    if (mobileNavMenu.classList.contains('active')) {
                        closeMobileMenu();
                    }
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

            // Add loading state to navigation links
            document.querySelectorAll('.nav-link, .mobile-nav-link').forEach(link => {
                link.addEventListener('click', function() {
                    if (this.href && !this.href.includes('#')) {
                        this.classList.add('loading');
                    }
                });
            });

            // Load pending orders count
            loadPendingOrdersCount();
            
            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    closeMobileMenu();
                }
            });

            console.log('✅ Enhanced Kasir System loaded');
        });

        function loadPendingOrdersCount() {
            if (typeof window.kasirOrdersStatsUrl !== 'undefined') {
                fetch(window.kasirOrdersStatsUrl)
                    .then(response => response.json())
                    .then(data => {
                        const badges = document.querySelectorAll('#pendingOrdersBadge, .notification-badge');
                        badges.forEach(badge => {
                            if (data.pending_orders > 0) {
                                badge.textContent = data.pending_orders;
                                badge.style.display = 'flex';
                            } else {
                                badge.style.display = 'none';
                            }
                        });
                    })
                    .catch(error => {
                        console.log('Could not load orders stats:', error);
                    });
            }
        }

        // Refresh orders count every 30 seconds
        setInterval(loadPendingOrdersCount, 30000);

// Add this to the existing JavaScript section, after the DOMContentLoaded event listener

// Handle tablet-specific interactions
function handleTabletView() {
    const isTablet = window.innerWidth >= 769 && window.innerWidth <= 1024;
    const navbar = document.querySelector('.navbar-container');
    
    if (isTablet) {
        // Add tablet-specific class for additional styling if needed
        navbar.classList.add('tablet-view');
        
        // Ensure mobile menu is closed on tablet
        closeMobileMenu();
    } else {
        navbar.classList.remove('tablet-view');
    }
}

// Run on load and resize
handleTabletView();
window.addEventListener('resize', handleTabletView);

// Optimize touch interactions for tablets
if ('ontouchstart' in window) {
    document.querySelectorAll('.nav-link, .logout-btn').forEach(element => {
        element.addEventListener('touchstart', function() {
            this.style.transform = 'scale(0.95)';
        });
        
        element.addEventListener('touchend', function() {
            this.style.transform = '';
        });
    });
}

window.addEventListener('scroll', function() {
    const navbar = document.querySelector('.navbar');
    if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
});
    </script>
    @stack('scripts')
</body>
</html>
