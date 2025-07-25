<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detail Pesanan</title>
    
    <link href="{{ asset('assets/img/favicon.png') }}" rel="icon">
    <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">
    
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Amatic+SC:400;700&display=swap" rel="stylesheet">
    
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">
    
    <style>
        /* Base page styles */
        body {
            margin: 0; /* Remove default body margin */
            padding: 0; /* Remove default body padding */
            border-top: 4px solid #f97316; /* Add the orange line at the top */
        }
        .order-detail-page * { box-sizing: border-box !important; }
        .order-detail-page { 
            font-family: 'Inter', sans-serif !important; 
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%) !important; 
            color: #1e293b !important; 
            line-height: 1.6 !important; 
            min-height: calc(100vh - 4px); /* Adjusted min-height to account for 4px top border */
            padding: 1rem 0 !important; /* Reduced vertical padding for overall page */
        }
        .order-detail-page .container { 
            max-width: 1000px !important; 
            margin: 0 auto !important; 
            padding: 0 1rem !important; /* Reduced horizontal padding for container */
        }
        
        /* Main Header for the page */
        #main-page-header {
            background: white !important; 
            padding: 0.6rem 1.25rem !important; /* Further reduced padding for smaller height */
            border-bottom: 1px solid #e2e8f0 !important; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.05) !important; 
            position: sticky !important;
            top: 0 !important;
            z-index: 1000 !important; 
            display: flex !important; 
            justify-content: flex-end !important; /* Align button to the right */
            align-items: center !important; 
            flex-wrap: wrap !important; 
            gap: 1rem !important; 
            border-radius: 0 0 16px 16px !important; 
        }
        #main-page-header .back-button { 
            display: inline-flex !important; 
            align-items: center !important; 
            gap: 0.6rem !important; /* Reduced gap */
            padding: 0.6rem 1rem !important; /* Reduced padding */
            background: linear-gradient(135deg, #f97316, #ea580c) !important; 
            color: white !important; 
            text-decoration: none !important; 
            border-radius: 10px !important; /* Slightly smaller border-radius */
            font-weight: 600 !important; 
            font-size: 0.85rem !important; /* Slightly reduced font size */
            box-shadow: 0 3px 12px rgba(249,115,22,0.3) !important; /* Slightly smaller shadow */
            transition: all 0.3s ease !important; 
        }
        #main-page-header .back-button:hover { 
            transform: translateY(-1px) !important; /* Smaller hover effect */
            box-shadow: 0 6px 18px rgba(249,115,22,0.4) !important; 
            color: white !important; 
            text-decoration: none !important; 
        }
        
        /* NEW: Single Main Order Details Card */
        .order-detail-page .main-order-details-card {
            background: white !important; 
            border-radius: 16px !important; /* Slightly smaller border-radius */
            box-shadow: 0 6px 25px rgba(0,0,0,0.07) !important; /* Slightly smaller shadow */
            border: 1px solid #f1f5f9 !important; 
            overflow: hidden !important; 
            position: relative !important; 
            padding: 0 !important; 
            margin-top: 1rem !important; /* Reduced margin-top */
        }
        .order-detail-page .main-order-details-card::before { 
            content: '' !important; 
            position: absolute !important; 
            top: 0 !important; 
            left: 0 !important; 
            right: 0 !important; 
            height: 3px !important; /* Reduced height */
            background: linear-gradient(90deg, #f97316, #ef4444) !important; 
        }

        /* Internal Grid for the Main Order Details Card */
        .order-detail-page .main-order-details-grid {
            display: grid !important;
            gap: 1rem !important; /* Reduced gap between major sections within the card */
            padding: 1.25rem !important; /* Reduced overall padding for the card content */
        }

        /* Order Title (Pesanan #ORD-...) */
        .order-detail-page .main-order-details-grid .order-title { 
            font-size: 1.75rem !important; /* Reduced font size */
            font-weight: 800 !important; 
            color: #1e293b !important; 
            margin: 0 !important; 
            padding-bottom: 0.75rem !important; /* Reduced padding */
            border-bottom: 1px solid #e2e8f0 !important;
        }

        /* Section for Order Meta and Status */
        .order-detail-page .order-meta-and-status-section {
            display: grid !important;
            gap: 1rem !important; /* Reduced gap */
            background: linear-gradient(135deg, #fef3c7, #fde68a) !important; 
            padding: 1.25rem !important; /* Reduced padding */
            border-radius: 12px !important; /* Slightly smaller border-radius */
            box-shadow: inset 0 1px 6px rgba(0,0,0,0.04) !important; /* Slightly smaller shadow */
            border: 1px solid #fcd34d !important;
        }
        .order-detail-page .order-meta-grid { 
            display: grid !important; 
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)) !important; /* Adjusted min-width */
            gap: 0.75rem !important; /* Reduced gap */
        }
        .order-detail-page .meta-item { 
            background: rgba(255,255,255,0.7) !important; 
            padding: 0.8rem !important; /* Reduced padding */
            border-radius: 10px !important; /* Slightly smaller border-radius */
            backdrop-filter: blur(5px) !important; 
            border: 1px solid rgba(255,255,255,0.8) !important; 
            color: #1e293b !important;
        }
        .order-detail-page .meta-label { 
            font-size: 0.75rem !important; /* Reduced font size */
            color: #64748b !important; 
            margin-bottom: 0.2rem !important; /* Reduced margin */
            text-transform: uppercase !important; 
            letter-spacing: 0.5px !important; 
        }
        .order-detail-page .meta-value { 
            font-size: 1rem !important; /* Reduced font size */
            font-weight: 600 !important; 
            word-break: break-word !important; 
        }
        .order-detail-page .status-content {
            text-align: center !important;
            background: rgba(255,255,255,0.7) !important;
            padding: 1.25rem !important; /* Reduced padding */
            border-radius: 12px !important; /* Slightly smaller border-radius */
            backdrop-filter: blur(5px) !important;
            border: 1px solid rgba(255,255,255,0.8) !important;
        }
        .order-detail-page .status-icon { 
            font-size: 3.5rem !important; /* Reduced font size */
            margin-bottom: 0.75rem !important; /* Reduced margin */
            display: block !important; 
            animation: bounce 2s infinite !important; 
            color: #f97316 !important; 
        }
        .order-detail-page .status-text { 
            font-size: 1.5rem !important; /* Reduced font size */
            font-weight: 800 !important; 
            color: #ea580c !important; 
            margin-bottom: 0.4rem !important; /* Reduced margin */
            text-transform: uppercase !important; 
            letter-spacing: 1px !important; 
        }
        .order-detail-page .status-desc { 
            color: #64748b !important; 
            font-size: 1rem !important; /* Reduced font size */
            font-weight: 500 !important; 
        }

        /* Sections for Customer Info, Payment Summary, and Order Items */
        .order-detail-page .combined-section {
            background: white !important; 
            border-radius: 12px !important; /* Slightly smaller border-radius */
            box-shadow: 0 3px 12px rgba(0,0,0,0.04) !important; /* Slightly smaller shadow */
            border: 1px solid #e2e8f0 !important;
            overflow: hidden !important;
        }
        .order-detail-page .combined-section-header {
            display: flex !important;
            align-items: center !important;
            gap: 0.8rem !important; /* Reduced gap */
            padding: 1rem 1.25rem !important; /* Reduced padding */
            border-bottom: 1px solid #e2e8f0 !important;
            background: #f8fafc !important;
            flex-wrap: wrap !important;
        }
        .order-detail-page .combined-section-header .card-icon {
            width: 40px !important; /* Reduced size */
            height: 40px !important; /* Reduced size */
            font-size: 1.3rem !important; /* Reduced font size */
            border-radius: 14px !important; /* Adjusted for squircle effect */
            color: white !important;
            display: flex !important; 
            align-items: center !important; 
            justify-content: center !important; 
            box-shadow: 0 5px 15px rgba(0,0,0,0.09) !important; /* Adjusted shadow */
        }
        .order-detail-page .combined-section-header .card-title {
            font-size: 1.2rem !important; /* Reduced font size */
            font-weight: 700 !important;
            color: #1e293b !important;
            margin: 0 !important;
        }
        .order-detail-page .combined-section-content {
            padding: 1.25rem !important; /* Reduced padding */
        }

        /* Info Grid (inside combined section) */
        .order-detail-page .info-grid { display: grid !important; gap: 0.6rem !important; } /* Reduced gap */
        .order-detail-page .info-row { 
            display: flex !important; 
            align-items: center !important; 
            padding: 0.6rem 0.8rem !important; /* Reduced padding */
            background: #f8fafc !important; 
            border-radius: 6px !important; /* Reduced border-radius */
            border-left: 3px solid #3b82f6 !important; /* Reduced border thickness */
            flex-wrap: wrap !important; 
            gap: 0.4rem !important; /* Reduced gap */
        }
        .order-detail-page .info-label { 
            font-weight: 600 !important; 
            color: #64748b !important; 
            width: 100px !important; /* Reduced width */
            flex-shrink: 0 !important; 
            min-width: 80px !important; /* Reduced min-width */
            font-size: 0.85rem !important; /* Reduced font size */
        }
        .order-detail-page .info-value { 
            font-weight: 600 !important; 
            color: #1e293b !important; 
            font-size: 0.95rem !important; /* Reduced font size */
            flex: 1 !important; 
            word-break: break-word !important; 
        }
        
        /* Items List (inside combined section) */
        .order-detail-page .items-list { display: grid !important; gap: 0.6rem !important; } /* Reduced gap */
        .order-detail-page .order-item { 
            display: flex !important; 
            justify-content: space-between !important; 
            align-items: center !important; 
            padding: 0.8rem !important; /* Reduced padding */
            background: #f8fafc !important; 
            border-radius: 10px !important; /* Reduced border-radius */
            border: 1px solid #e2e8f0 !important; 
            transition: all 0.3s ease !important; 
            flex-wrap: wrap !important; 
            gap: 0.6rem !important; /* Reduced gap */
        }
        .order-detail-page .order-item:hover { 
            transform: translateY(-1px) !important; /* Smaller hover effect */
            box-shadow: 0 6px 18px rgba(0,0,0,0.09) !important; 
            background: white !important; 
        }
        .order-detail-page .item-details { flex: 1 !important; min-width: 160px !important; } /* Adjusted min-width */
        .order-detail-page .item-name { 
            font-weight: 700 !important; 
            color: #1e293b !important; 
            margin-bottom: 0.2rem !important; /* Reduced margin */
            font-size: 1.05rem !important; /* Reduced font size */
            word-break: break-word !important; 
        }
        .order-detail-page .item-qty-price { 
            color: #64748b !important; 
            font-size: 0.85rem !important; /* Reduced font size */
            font-weight: 500 !important; 
        }
        .order-detail-page .item-total { 
            font-weight: 800 !important; 
            color: #ef4444 !important; 
            font-size: 1.15rem !important; /* Reduced font size */
            background: linear-gradient(135deg, #fef2f2, #fee2e2) !important; 
            padding: 0.5rem 0.8rem !important; /* Reduced padding */
            border-radius: 8px !important; /* Reduced border-radius */
            border: 1px solid #fecaca !important; 
            white-space: nowrap !important; 
            flex-shrink: 0 !important; 
        }
        
        /* Summary List (inside combined section) */
        .order-detail-page .summary-list { display: grid !important; gap: 0.6rem !important; } /* Reduced gap */
        .order-detail-page .summary-row { 
            display: flex !important; 
            justify-content: space-between !important; 
            align-items: center !important; 
            padding: 0.6rem 0.8rem !important; /* Reduced padding */
            background: #f8fafc !important; 
            border-radius: 6px !important; /* Reduced border-radius */
            font-size: 0.95rem !important; /* Reduced font size */
            flex-wrap: wrap !important; 
            gap: 0.4rem !important; /* Reduced gap */
        }
        .order-detail-page .summary-label { color: #64748b !important; font-weight: 500 !important; }
        .order-detail-page .summary-value { font-weight: 600 !important; color: #1e293b !important; white-space: nowrap !important; }
        .order-detail-page .summary-total { 
            background: linear-gradient(135deg, #fef2f2, #fee2e2) !important; 
            border: 2px solid #ef4444 !important; 
            font-size: 1.25rem !important; /* Reduced font size */
            font-weight: 800 !important; 
            padding: 0.8rem !important; /* Reduced padding */
        }
        .order-detail-page .summary-total .summary-label, .order-detail-page .summary-total .summary-value { 
            color: #ef4444 !important; 
            font-weight: 800 !important; 
        }
        
        /* Responsive Layouts */
        @media (min-width: 769px) {
            .order-detail-page .main-order-details-grid {
                grid-template-columns: 1fr; 
            }
            .order-detail-page .order-meta-and-status-section {
                grid-template-columns: 2fr 1fr !important; 
            }
            .order-detail-page .customer-payment-items-grid {
                display: grid !important;
                grid-template-columns: repeat(2, 1fr) !important; 
                gap: 1rem !important; /* Reduced gap */
            }
            .order-detail-page .info-section { grid-column: 1 / 2 !important; }
            .order-detail-page .summary-section { grid-column: 2 / 3 !important; }
            .order-detail-page .items-section { grid-column: 1 / -1 !important; } 
        }

        @media (max-width: 768px) {
            #main-page-header {
                padding: 0.8rem 1rem !important; /* Adjusted for mobile */
                gap: 0.6rem !important; /* Reduced gap for mobile */
            }
            #main-page-header .back-button { padding: 0.5rem 0.9rem !important; font-size: 0.8rem !important; }

            .order-detail-page .container { padding: 0 0.8rem !important; } /* Adjusted for mobile */
            .order-detail-page .main-order-details-card { margin-top: 0.8rem !important; border-radius: 14px !important; } /* Adjusted for mobile */
            .order-detail-page .main-order-details-grid {
                padding: 0.8rem !important; /* Adjusted for mobile */
                gap: 0.8rem !important; /* Adjusted for mobile */
            }
            .order-detail-page .main-order-details-grid .order-title { font-size: 1.6rem !important; padding-bottom: 0.6rem !important; } /* Adjusted for mobile */

            .order-detail-page .order-meta-and-status-section {
                padding: 0.8rem !important; /* Adjusted for mobile */
                gap: 0.8rem !important; /* Adjusted for mobile */
                border-radius: 10px !important; /* Adjusted for mobile */
            }
            .order-detail-page .order-meta-grid { gap: 0.6rem !important; } /* Adjusted for mobile */
            .order-detail-page .meta-item { padding: 0.7rem !important; border-radius: 8px !important; } /* Adjusted for mobile */
            .order-detail-page .meta-label { font-size: 0.7rem !important; } /* Adjusted for mobile */
            .order-detail-page .meta-value { font-size: 0.9rem !important; } /* Adjusted for mobile */
            .order-detail-page .status-content { padding: 0.8rem !important; border-radius: 10px !important; } /* Adjusted for mobile */
            .order-detail-page .status-text { font-size: 1.4rem !important; } /* Adjusted for mobile */
            .order-detail-page .status-desc { font-size: 0.9rem !important; } /* Adjusted for mobile */
            .order-detail-page .status-icon { font-size: 3rem !important; } /* Adjusted for mobile */

            .order-detail-page .combined-section { border-radius: 10px !important; } /* Adjusted for mobile */
            .order-detail-page .combined-section-header { padding: 0.8rem 1rem !important; gap: 0.4rem !important; } /* Adjusted for mobile */
            .order-detail-page .combined-section-header .card-icon { width: 36px !important; height: 36px !important; font-size: 1.1rem !important; border-radius: 12px !important; } /* Adjusted for mobile */
            .order-detail-page .combined-section-header .card-title { font-size: 1.1rem !important; } /* Adjusted for mobile */
            .order-detail-page .combined-section-content { padding: 1rem !important; } /* Adjusted for mobile */

            .order-detail-page .info-row { padding: 0.5rem 0.7rem !important; border-radius: 5px !important; border-left: 2px solid #3b82f6 !important; } /* Adjusted for mobile */
            .order-detail-page .info-label { font-size: 0.75rem !important; } /* Adjusted for mobile */
            .order-detail-page .info-value { font-size: 0.85rem !important; } /* Adjusted for mobile */
            .order-detail-page .order-item { padding: 0.7rem !important; border-radius: 8px !important; } /* Adjusted for mobile */
            .order-detail-page .item-name { font-size: 1rem !important; } /* Adjusted for mobile */
            .order-detail-page .item-qty-price { font-size: 0.8rem !important; } /* Adjusted for mobile */
            .order-detail-page .item-total { font-size: 1.1rem !important; padding: 0.4rem 0.7rem !important; border-radius: 7px !important; } /* Adjusted for mobile */
            .order-detail-page .summary-row { padding: 0.5rem 0.7rem !important; border-radius: 5px !important; font-size: 0.85rem !important; } /* Adjusted for mobile */
            .order-detail-page .summary-total { font-size: 1.1rem !important; padding: 0.7rem !important; } /* Adjusted for mobile */
        }
        
        @media (max-width: 480px) {
            #main-page-header { padding: 0.6rem 0.8rem !important; }
            #main-page-header .back-button { padding: 0.4rem 0.7rem !important; font-size: 0.7rem !important; }

            .order-detail-page .container { padding: 0 0.4rem !important; }
            .order-detail-page .main-order-details-card { border-radius: 12px !important; }
            .order-detail-page .main-order-details-grid { padding: 0.6rem !important; gap: 0.6rem !important; }
            .order-detail-page .main-order-details-grid .order-title { font-size: 1.4rem !important; padding-bottom: 0.4rem !important; }

            .order-detail-page .order-meta-and-status-section { padding: 0.6rem !important; border-radius: 8px !important; }
            .order-detail-page .order-meta-grid { gap: 0.4rem !important; }
            .order-detail-page .meta-item { padding: 0.6rem !important; border-radius: 6px !important; }
            .order-detail-page .meta-label { font-size: 0.65rem !important; }
            .order-detail-page .meta-value { font-size: 0.85rem !important; }
            .order-detail-page .status-content { padding: 0.6rem !important; border-radius: 8px !important; }
            .order-detail-page .status-text { font-size: 1.2rem !important; }
            .order-detail-page .status-desc { font-size: 0.8rem !important; }
            .order-detail-page .status-icon { font-size: 2.5rem !important; }

            .order-detail-page .combined-section { border-radius: 8px !important; }
            .order-detail-page .combined-section-header { padding: 0.6rem 0.8rem !important; gap: 0.3rem !important; }
            .order-detail-page .combined-section-header .card-icon { width: 32px !important; height: 32px !important; font-size: 1rem !important; border-radius: 10px !important; }
            .order-detail-page .combined-section-header .card-title { font-size: 1rem !important; }
            .order-detail-page .combined-section-content { padding: 0.8rem !important; }

            .order-detail-page .info-row { padding: 0.4rem 0.6rem !important; border-radius: 4px !important; }
            .order-detail-page .info-label { font-size: 0.7rem !important; }
            .order-detail-page .info-value { font-size: 0.8rem !important; }
            .order-detail-page .order-item { padding: 0.6rem !important; border-radius: 6px !important; }
            .order-detail-page .item-name { font-size: 0.9rem !important; }
            .order-detail-page .item-qty-price { font-size: 0.75rem !important; }
            .order-detail-page .item-total { font-size: 1rem !important; padding: 0.3rem 0.6rem !important; border-radius: 6px !important; }
            .order-detail-page .summary-row { padding: 0.4rem 0.6rem !important; border-radius: 4px !important; font-size: 0.8rem !important; }
            .order-detail-page .summary-total { font-size: 1rem !important; padding: 0.6rem !important; }
        }
    </style>
</head>
<body>
    <header id="main-page-header">
        <a href="{{ route('orders.history') }}" class="back-button">
            <i class="bi bi-receipt"></i> Riwayat Pesanan
        </a>
    </header>

    <div class="order-detail-page">
        <div class="container">
            <!-- NEW: Single Main Order Details Card - Contains ALL order information -->
            <div class="main-order-details-card">
                <div class="main-order-details-grid">
                    <h2 class="order-title">Pesanan #{{ $order->order_number }}</h2>

                    <!-- Section for Order Meta and Status -->
                    <div class="order-meta-and-status-section">
                        <div class="order-meta-grid">
                            <div class="meta-item">
                                <div class="meta-label">Tanggal Pesanan</div>
                                <div class="meta-value">{{ $order->created_at->format('d M Y, H:i') }}</div>
                            </div>
                            <div class="meta-item">
                                <div class="meta-label">Status Pesanan</div>
                                <div class="meta-value">{{ ucfirst($order->status ?? 'Pending') }}</div>
                            </div>
                            <div class="meta-item">
                                <div class="meta-label">Status Pembayaran</div>
                                <div class="meta-value">{{ ucfirst($order->payment_status ?? 'Pending') }}</div>
                            </div>
                            @if($order->table_number)
                            <div class="meta-item">
                                <div class="meta-label">Nomor Meja</div>
                                <div class="meta-value">Meja {{ $order->table_number }}</div>
                            </div>
                            @endif
                        </div>
                        <div class="status-content">
                            <div class="status-icon">
                                @if($order->status == 'completed') 🍜 
                                @elseif($order->status == 'preparing') 👨‍🍳 
                                @elseif($order->status == 'ready') 🔔 
                                @elseif($order->status == 'cancelled') ❌ 
                                @else ⏳ @endif
                            </div>
                            <div class="status-text">{{ ucfirst($order->status ?? 'Pending') }}</div>
                            <div class="status-desc">
                                @if($order->status == 'pending')
                                    Pesanan Anda menunggu konfirmasi dari dapur.
                                @elseif($order->status == 'confirmed')
                                    Pesanan Anda telah dikonfirmasi dan akan segera disiapkan.
                                @elseif($order->status == 'preparing')
                                    Pesanan Anda sedang dimasak dengan penuh cinta di dapur kami.
                                @elseif($order->status == 'ready')
                                    Pesanan Anda sudah siap! Silakan ambil atau tunggu diantar.
                                @elseif($order->status == 'completed')
                                    Pesanan telah selesai. Terima kasih telah mempercayai kami!
                                @elseif($order->status == 'cancelled')
                                    Pesanan telah dibatalkan. Hubungi kami jika ada pertanyaan.
                                @else
                                    Status pesanan sedang diproses.
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Section for Customer Info, Payment Summary, and Order Items -->
                    <div class="customer-payment-items-grid">
                        <!-- Section: Informasi Pelanggan -->
                        <div class="combined-section info-section">
                            <div class="combined-section-header">
                                <div class="card-icon"><i class="bi bi-person-circle"></i></div>
                                <h2 class="card-title">Informasi Pelanggan</h2>
                            </div>
                            <div class="combined-section-content">
                                <div class="info-grid">
                                    <div class="info-row">
                                        <span class="info-label">Nama Pelanggan:</span>
                                        <span class="info-value">{{ $order->customer_name ?? 'Tidak ada nama' }}</span>
                                    </div>
                                    @if($order->table_number)
                                    <div class="info-row">
                                        <span class="info-label">Nomor Meja:</span>
                                        <span class="info-value">Meja {{ $order->table_number }}</span>
                                    </div>
                                    @endif
                                    <div class="info-row">
                                        <span class="info-label">Waktu Pemesanan:</span>
                                        <span class="info-value">{{ $order->created_at->format('d M Y, H:i:s') }}</span>
                                    </div>
                                    @if($order->updated_at != $order->created_at)
                                    <div class="info-row">
                                        <span class="info-label">Terakhir Diupdate:</span>
                                        <span class="info-value">{{ $order->updated_at->format('d M Y, H:i:s') }}</span>
                                    </div>
                                    @endif
                                    @if($order->notes)
                                    <div class="info-row">
                                        <span class="info-label">Catatan Khusus:</span>
                                        <span class="info-value">{{ $order->notes }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Section: Ringkasan Pembayaran -->
                        <div class="combined-section summary-section">
                            <div class="combined-section-header">
                                <div class="card-icon"><i class="bi bi-receipt-cutoff"></i></div>
                                <h2 class="card-title">Ringkasan Pembayaran</h2>
                            </div>
                            <div class="combined-section-content">
                                <div class="summary-list">
                                    <div class="summary-row">
                                        <span class="summary-label">Subtotal ({{ $order->items->count() }} item)</span>
                                        <span class="summary-value">Rp{{ number_format($order->subtotal ?? 0, 0, ',', '.') }}</span>
                                    </div>
                                    @if($order->service_fee > 0)
                                    <div class="summary-row">
                                        <span class="summary-label">Biaya Layanan</span>
                                        <span class="summary-value">Rp{{ number_format($order->service_fee, 0, ',', '.') }}</span>
                                    </div>
                                    @endif
                                    @if($order->tax ?? 0 > 0)
                                    <div class="summary-row">
                                        <span class="summary-label">Pajak</span>
                                        <span class="summary-value">Rp{{ number_format($order->tax, 0, ',', '.') }}</span>
                                    </div>
                                    @endif
                                    @if($order->discount ?? 0 > 0)
                                    <div class="summary-row">
                                        <span class="summary-label">Diskon</span>
                                        <span class="summary-value">Rp{{ number_format($order->discount, 0, ',', '.') }}</span>
                                    </div>
                                    @endif
                                    <div class="summary-row summary-total">
                                        <span class="summary-label">Total Pembayaran</span>
                                        <span class="summary-value">Rp{{ number_format($order->total, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section: Item Pesanan -->
                        <div class="combined-section items-section">
                            <div class="combined-section-header">
                                <div class="card-icon"><i class="bi bi-basket3-fill"></i></div>
                                <h2 class="card-title">Item Pesanan ({{ $order->items->count() }} item)</h2>
                            </div>
                            <div class="combined-section-content">
                                <div class="items-list">
                                    @forelse($order->items as $item)
                                    <div class="order-item">
                                        <div class="item-details">
                                            <div class="item-name">{{ $item->menu_name }}</div>
                                            <div class="item-qty-price">
                                                {{ $item->quantity }} porsi × Rp{{ number_format($item->price, 0, ',', '.') }}
                                            </div>
                                        </div>
                                        <div class="item-total">
                                            Rp{{ number_format($item->subtotal, 0, ',', '.') }}
                                        </div>
                                    </div>
                                    @empty
                                    <div class="order-item">
                                        <div class="item-details">
                                            <div class="item-name">Tidak ada item dalam pesanan ini.</div>
                                        </div>
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
