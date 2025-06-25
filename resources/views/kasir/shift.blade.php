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
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
}

.shift-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    width: 100%;
    max-width: 500px;
    border: 1px solid #e2e8f0;
}

.shift-header {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
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
    background: #f8fafc;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    border: 1px solid #e2e8f0;
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
    background: #10b981;
    border-radius: 50%;
    animation: pulse 2s infinite;
}

.status-dot.inactive {
    background: #ef4444;
    animation: none;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
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
    color: #10b981 !important;
}

.status-inactive {
    color: #ef4444 !important;
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
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
}

.btn-primary {
    background: #3b82f6;
    color: white;
}

.btn-primary:hover {
    background: #2563eb;
    transform: translateY(-1px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.btn-danger {
    background: #ef4444;
    color: white;
}

.btn-danger:hover {
    background: #dc2626;
    transform: translateY(-1px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.btn-large {
    padding: 1.25rem 2rem;
    font-size: 1rem;
}

.shift-note {
    background: #fffbeb;
    border: 1px solid #fed7aa;
    border-radius: 8px;
    padding: 1rem;
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.note-icon {
    color: #f59e0b;
    flex-shrink: 0;
    margin-top: 0.125rem;
}

.shift-note p {
    font-size: 0.875rem;
    color: #92400e;
    line-height: 1.5;
}

.alert {
    padding: 1rem;
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 0.875rem;
    font-weight: 500;
}

.alert-info {
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    color: #1e40af;
}

.alert-icon {
    flex-shrink: 0;
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
    }
    
    .shift-note {
        flex-direction: column;
        gap: 0.5rem;
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
}

/* Smooth Transitions */
.shift-card {
    transition: all 0.3s ease;
}

.shift-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

/* Custom Scrollbar */
::-webkit-scrollbar {
    width: 6px;
}

::-webkit-scrollbar-track {
    background: #f1f5f9;
}

::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
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
    
    // Add smooth entrance animation
    const shiftCard = document.querySelector('.shift-card');
    shiftCard.style.opacity = '0';
    shiftCard.style.transform = 'translateY(20px)';
    
    setTimeout(() => {
        shiftCard.style.transition = 'all 0.5s ease-out';
        shiftCard.style.opacity = '1';
        shiftCard.style.transform = 'translateY(0)';
    }, 100);
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