<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Food Delivery') - Pesan Makanan Online</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .app-container {
            max-width: 414px;
            margin: 0 auto;
            background: #ffffff;
            min-height: 100vh;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        /* Header Styles */
        .header {
            background: linear-gradient(135deg, #ff6b6b, #ffa500);
            padding: 20px 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            position: relative;
            border-radius: 0 0 25px 25px;
            box-shadow: 0 4px 20px rgba(255, 107, 107, 0.3);
        }

        .location-info {
            position: relative;
            z-index: 1;
        }

        .location-label {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 2px;
        }

        .location-text {
            font-weight: 700;
            font-size: 16px;
            color: white;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 15px;
            position: relative;
            z-index: 1;
        }

        .avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            border: 3px solid rgba(255, 255, 255, 0.3);
        }

        /* Search Bar */
        .search-bar {
            padding: 15px 20px;
            background: white;
            border-bottom: 1px solid #e2e8f0;
        }

        .search-form {
            display: flex;
            gap: 10px;
        }

        .search-input {
            flex: 1;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 16px;
            outline: none;
        }

        .search-btn {
            background: linear-gradient(135deg, #ff6b6b, #ffa500);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
        }

        /* Category Styles */
        .category-section {
            padding: 25px 20px;
            background: #f8fafc;
        }

        .category-list {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .category-link {
            text-decoration: none;
        }

        .category-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-width: 85px;
            height: 85px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .category-makanan {
            background: linear-gradient(135deg, #ffe5b4, #ffd89b);
            color: #b45f06;
            border: 2px solid #ffb400;
            box-shadow: 0 4px 15px rgba(255, 180, 0, 0.2);
        }

        .category-minuman {
            background: linear-gradient(135deg, #b4eaff, #a8e6cf);
            color: #007b8a;
            border: 2px solid #00b4d8;
            box-shadow: 0 4px 15px rgba(0, 180, 216, 0.2);
        }

        .category-default {
            background: linear-gradient(135deg, #ffffff, #f1f5f9);
            color: #475569;
            border: 2px solid #e2e8f0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .category-active {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .category-icon {
            font-size: 28px;
            margin-bottom: 5px;
        }

        /* Food List Styles */
        .food-section {
            padding: 0 20px 100px;
            background: #ffffff;
        }

        .food-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 20px;
            padding-top: 10px;
        }

        .food-card {
            background: white;
            border-radius: 20px;
            padding: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #f1f5f9;
        }

        .menu-image {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 15px;
            margin-bottom: 12px;
        }

        .food-title {
            font-weight: 700;
            font-size: 16px;
            color: #1e293b;
            margin-bottom: 6px;
            line-height: 1.3;
        }

        .food-desc {
            color: #64748b;
            font-size: 13px;
            line-height: 1.4;
            margin-bottom: 12px;
        }

        .food-price {
            font-weight: 700;
            font-size: 16px;
            color: #dc2626;
            margin-bottom: 12px;
        }

        .add-form {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .qty-input {
            width: 50px;
            padding: 8px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            text-align: center;
        }

        .add-btn {
            flex: 1;
            background: linear-gradient(135deg, #ff6b6b, #ffa500);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
        }

        /* Bottom Navigation */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            max-width: 414px;
            width: 100%;
            background: white;
            display: flex;
            justify-content: space-around;
            padding: 15px 0 25px;
            border-top: 1px solid #e2e8f0;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.1);
            border-radius: 25px 25px 0 0;
        }

        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 8px 12px;
            border-radius: 12px;
            color: #94a3b8;
            text-decoration: none;
            position: relative;
            min-width: 60px;
        }

        .nav-item.active {
            background: linear-gradient(135deg, #ff6b6b, #ffa500);
            color: white;
            box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);
        }

        .nav-icon {
            font-size: 20px;
            margin-bottom: 4px;
        }

        .nav-label {
            font-size: 12px;
            font-weight: 500;
        }

        .cart-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #dc2626;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        /* Alert Styles */
        .alert {
            padding: 12px 20px;
            margin: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #64748b;
        }

        .empty-icon {
            font-size: 80px;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .empty-state h3 {
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
        }

        .empty-state p {
            color: #64748b;
            margin-bottom: 30px;
            font-size: 16px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #ff6b6b, #ffa500);
            color: white;
            padding: 14px 28px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 700;
            font-size: 16px;
            display: inline-block;
            border: none;
            cursor: pointer;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .app-container {
                max-width: 100%;
            }
            
            .food-list {
                grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
                gap: 15px;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="app-container">
        @yield('content')
    </div>
</body>
</html>
