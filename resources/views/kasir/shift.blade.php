@extends('layouts.kasir')

@section('content')
<div class="shift-container">
    <div class="shift-card">
        <div class="shift-header">
            <div class="shift-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12,6 12,12 16,14"></polyline>
                </svg>
            </div>
            <div class="shift-title">
                <h1>Shift Kasir</h1>
                <p class="shift-subtitle">Kelola sesi kerja kasir Anda</p>
            </div>
        </div>

        <div class="shift-content">
            @if(session('shift_active'))
                <div class="shift-status active">
                    <div class="status-indicator">
                        <div class="status-dot"></div>
                        <span class="status-text">Shift Sedang Berjalan</span>
                    </div>
                    <div class="shift-info">
                        <div class="info-item">
                            <span class="info-label">Waktu Mulai</span>
                            <span class="info-value">{{ date('H:i') }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Kasir</span>
                            <span class="info-value">Admin</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Status</span>
                            <span class="info-value status-active">Aktif</span>
                        </div>
                    </div>
                </div>

                <div class="shift-actions">
                    <form method="POST" action="{{ route('kasir.shift.end') }}" class="action-form">
                        @csrf
                        <button type="submit" class="btn btn-danger">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="15" y1="9" x2="9" y2="15"></line>
                                <line x1="9" y1="9" x2="15" y2="15"></line>
                            </svg>
                            Akhiri Shift
                        </button>
                    </form>
                    
                    <a href="{{ route('kasir.dashboard') }}" class="btn btn-primary">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 3h18l-1 13H4L3 3z"></path>
                            <path d="M16 16a2 2 0 1 1-4 0 2 2 0 0 1 4 0z"></path>
                            <path d="M23 16a2 2 0 1 1-4 0 2 2 0 0 1 4 0z"></path>
                        </svg>
                        Masuk Ke Dashboard
                    </a>
                </div>
            @else
                <div class="shift-status inactive">
                    <div class="status-indicator">
                        <div class="status-dot inactive"></div>
                        <span class="status-text">Shift Belum Dimulai</span>
                    </div>
                    <div class="shift-info">
                        <div class="info-item">
                            <span class="info-label">Waktu Saat Ini</span>
                            <span class="info-value" id="current-time">{{ date('H:i') }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Kasir</span>
                            <span class="info-value">Admin</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Status</span>
                            <span class="info-value status-inactive">Tidak Aktif</span>
                        </div>
                    </div>
                </div>

                <div class="shift-actions">
                    <form method="POST" action="{{ route('kasir.shift.start') }}" class="action-form">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-large">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polygon points="5,3 19,12 5,21"></polygon>
                            </svg>
                            Mulai Shift
                        </button>
                    </form>
                </div>

                <div class="shift-note">
                    <div class="note-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="16" x2="12" y2="12"></line>
                            <line x1="12" y1="8" x2="12.01" y2="8"></line>
                        </svg>
                    </div>
                    <p>Mulai shift untuk mengakses dashboard kasir dan melakukan transaksi penjualan.</p>
                </div>
            @endif

            @if(session('message'))
                <div class="alert alert-info">
                    <div class="alert-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="16" x2="12" y2="12"></line>
                            <line x1="12" y1="8" x2="12.01" y2="8"></line>
                        </svg>
                    </div>
                    <span>{{ session('message') }}</span>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    :root {
        --primary-color: #f59e0b;
        --primary-dark: #d97706;
        --primary-light: #fbbf24;
        --warning-bg: #fffbeb;
        --warning-border: #fed7aa;
        --warning-text: #92400e;
        --success-color: #10b981;
        --danger-color: #ef4444;
    }

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

    .shift-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        background: linear-gradient(135deg, var(--warning-bg) 0%, #fef3c7 50%, var(--warning-border) 100%);
    }

    .shift-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(245, 158, 11, 0.15), 0 4px 6px rgba(245, 158, 11, 0.1);
        overflow: hidden;
        width: 100%;
        max-width: 500px;
        border: 2px solid var(--warning-border);
        transition: all 0.3s ease;
    }

    .shift-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 35px rgba(245, 158, 11, 0.2), 0 10px 15px rgba(245, 158, 11, 0.15);
    }

    .shift-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        padding: 2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .shift-icon {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .shift-title h1 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }

    .shift-subtitle {
        font-size: 0.875rem;
        opacity: 0.9;
        font-weight: 400;
    }

    .shift-content {
        padding: 2rem;
    }

    .shift-status {
        background: var(--warning-bg);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border: 2px solid var(--warning-border);
        transition: all 0.2s ease;
    }

    .shift-status.active {
        background: #f0fdf4;
        border-color: #bbf7d0;
    }

    .shift-status.inactive {
        background: #fef2f2;
        border-color: #fecaca;
    }

    .status-indicator {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .status-dot {
        width: 12px;
        height: 12px;
        background: var(--success-color);
        border-radius: 50%;
        animation: pulse 2s infinite;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.2);
    }

    .status-dot.inactive {
        background: var(--danger-color);
        animation: none;
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.2);
    }

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
            transform: scale(1);
        }
        50% {
            opacity: 0.7;
            transform: scale(1.1);
        }
    }

    .status-text {
        font-size: 1rem;
        font-weight: 600;
        color: #1e293b;
    }

    .shift-info {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
        border-bottom: 1px solid rgba(245, 158, 11, 0.1);
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-label {
        font-size: 0.875rem;
        color: #64748b;
        font-weight: 500;
    }

    .info-value {
        font-size: 0.875rem;
        font-weight: 600;
        color: #1e293b;
    }

    .status-active {
        color: var(--success-color) !important;
        font-weight: 700;
    }

    .status-inactive {
        color: var(--danger-color) !important;
        font-weight: 700;
    }

    .shift-actions {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .action-form {
        width: 100%;
    }

    .btn {
        width: 100%;
        padding: 0.875rem 1.5rem;
        border: none;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        text-decoration: none;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border: 2px solid transparent;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        border-color: var(--primary-color);
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, var(--primary-dark) 0%, #b45309 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }

    .btn-danger {
        background: linear-gradient(135deg, var(--danger-color) 0%, #dc2626 100%);
        color: white;
        border-color: var(--danger-color);
    }

    .btn-danger:hover {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .btn-large {
        padding: 1.25rem 2rem;
        font-size: 1rem;
        font-weight: 700;
    }

    .shift-note {
        background: var(--warning-bg);
        border: 2px solid var(--warning-border);
        border-radius: 12px;
        padding: 1.25rem;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .note-icon {
        color: var(--primary-color);
        flex-shrink: 0;
        margin-top: 0.125rem;
    }

    .shift-note p {
        font-size: 0.875rem;
        color: var(--warning-text);
        line-height: 1.5;
        font-weight: 500;
    }

    .alert {
        padding: 1rem;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.875rem;
        font-weight: 500;
        border: 2px solid;
    }

    .alert-info {
        background: var(--warning-bg);
        border-color: var(--warning-border);
        color: var(--warning-text);
    }

    .alert-icon {
        flex-shrink: 0;
        color: var(--primary-color);
    }

    /* Responsive Design */
    @media (max-width: 640px) {
        .shift-container {
            padding: 1rem;
        }
        
        .shift-header {
            padding: 1.5rem;
            flex-direction: column;
            text-align: center;
            gap: 1rem;
        }
        
        .shift-icon {
            width: 50px;
            height: 50px;
        }
        
        .shift-title h1 {
            font-size: 1.25rem;
        }
        
        .shift-content {
            padding: 1.5rem;
        }
        
        .shift-status {
            padding: 1rem;
        }
        
        .info-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.25rem;
            padding: 0.75rem 0;
        }
        
        .shift-note {
            flex-direction: column;
            gap: 0.5rem;
            padding: 1rem;
        }
    }

    @media (max-width: 480px) {
        .shift-container {
            padding: 0.5rem;
        }
        
        .shift-header {
            padding: 1rem;
        }
        
        .shift-content {
            padding: 1rem;
        }
        
        .btn {
            padding: 1rem;
            font-size: 0.9rem;
        }
        
        .btn-large {
            padding: 1.125rem 1.5rem;
            font-size: 0.95rem;
        }
    }

    /* Loading Animation */
    .btn:active {
        transform: translateY(0);
    }

    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
        background: #9ca3af !important;
        border-color: #9ca3af !important;
    }

    /* Custom Scrollbar */
    ::-webkit-scrollbar {
        width: 6px;
    }

    ::-webkit-scrollbar-track {
        background: var(--warning-bg);
    }

    ::-webkit-scrollbar-thumb {
        background: var(--primary-color);
        border-radius: 3px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: var(--primary-dark);
    }

    /* Enhanced animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .shift-card {
        animation: fadeInUp 0.5s ease-out;
    }

    /* Focus states for accessibility */
    .btn:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.3);
    }

    /* Touch-friendly improvements */
    @media (hover: none) and (pointer: coarse) {
        .btn:hover {
            transform: none;
        }
        
        .btn:active {
            transform: scale(0.98);
        }
        
        .shift-card:hover {
            transform: none;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update time every minute
    function updateTime() {
        const timeEl = document.getElementById('current-time');
        if (timeEl) {
            const now = new Date();
            timeEl.textContent = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit'
            });
        }
    }
    
    // Update time immediately and then every minute
    updateTime();
    setInterval(updateTime, 60000);
    
    // Add loading state to buttons
    const forms = document.querySelectorAll('.action-form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const button = this.querySelector('.btn');
            const originalText = button.innerHTML;
            
            button.disabled = true;
            button.innerHTML = `
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="animation: spin 1s linear infinite;">
                    <path d="M21 12a9 9 0 11-6.219-8.56"/>
                </svg>
                Memproses...
            `;
            
            // Reset after 3 seconds if form doesn't submit
            setTimeout(() => {
                if (button.disabled) {
                    button.disabled = false;
                    button.innerHTML = originalText;
                }
            }, 3000);
        });
    });
    
    console.log('✅ Shift page loaded with yellow theme');
});

// Add CSS for spin animation
const style = document.createElement('style');
style.textContent = `
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
`;
document.head.appendChild(style);
</script>
@endsection
