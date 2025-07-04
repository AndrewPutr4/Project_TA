@extends('layouts.app')

@section('title', 'Beranda - Pesan Makanan')

@push('styles')
<style>
    :root {
        --brand-primary: #ff6b6b;
        --brand-secondary: #ffa500;
        --text-dark: #1e293b;
        --text-light: #64748b;
        --bg-main: #f8fafc;
        --bg-white: #ffffff;
        --border-color: #e2e8f0;
        --success: #10b981;
        --error: #ef4444;
        --shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
    }

    html {
        scroll-behavior: smooth;
    }
    body {
        background-color: var(--bg-main);
    }
    .customer-app-container {
        padding-bottom: 80px;
    }

    .food-section {
        padding: 15px;
    }
    .food-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 15px;
    }
    .food-card {
        background: var(--bg-white);
        border-radius: 12px;
        box-shadow: var(--shadow);
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }
    .menu-image {
        width: 100%;
        height: 120px;
        object-fit: cover;
        background-color: #f1f5f9;
    }
    .food-title {
        font-weight: 600;
        padding: 8px 12px 0;
        color: var(--text-dark);
    }
    .food-desc {
        font-size: 12px;
        color: var(--text-light);
        padding: 4px 12px;
        flex-grow: 1;
    }
    .food-price {
        font-weight: 700;
        color: var(--brand-primary);
        padding: 4px 12px;
    }

    .add-menu-btn {
        background: var(--brand-primary);
        color: white;
        border: none;
        padding: 10px;
        margin: 12px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: background-color 0.2s ease-in-out, transform 0.2s ease;
    }
    .add-menu-btn:disabled {
        background-color: #d1d5db;
        cursor: not-allowed;
    }
    .add-menu-btn.added {
        background: var(--success);
    }

    .toast-notification {
        position: fixed;
        bottom: 90px;
        left: 50%;
        transform: translate(-50%, 120%);
        background-color: rgba(20, 20, 20, 0.95);
        color: white;
        padding: 12px 20px;
        border-radius: 999px;
        font-size: 14px;
        font-weight: 500;
        z-index: 9999;
        transition: transform 0.3s ease, opacity 0.3s ease;
        opacity: 0;
        pointer-events: none;
    }
    .toast-notification.show {
        transform: translate(-50%, 0);
        opacity: 1;
    }
    .toast-notification.error {
        background-color: var(--error);
    }

    .nav-item {
        position: relative;
    }
    .cart-badge {
        position: absolute;
        top: 2px;
        right: 15px;
        background-color: var(--brand-primary);
        color: white;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 700;
        border: 2px solid var(--bg-white);
    }
</style>
@endpush

@section('content')
<div class="customer-app-container">
    <div class="header">...</div>
    <div class="search-bar">...</div>
    <div class="category-section">...</div>

    <div class="food-section">
        <div class="food-list" id="foodList">
            @forelse($foods as $menu)
                <div class="food-card">
                    <img src="{{ $menu->image_url }}" alt="{{ $menu->name }}" class="menu-image" onerror="this.src='https://placehold.co/300x200/f1f5f9/64748b?text=Gambar+Rusak'">
                    <div class="food-title">{{ $menu->name }}</div>
                    <div class="food-desc">{{ Str::limit($menu->description, 50) }}</div>
                    <div class="food-price">{{ $menu->formatted_price }}</div>
                    <button type="button" class="add-menu-btn" data-menu-id="{{ $menu->id }}" title="Tambah {{ $menu->name }}">
                        🛒 Tambah
                    </button>
                </div>
            @empty
                <div class="empty-state">
                    <h3>Menu tidak ditemukan</h3>
                    <p>Coba kata kunci atau kategori lain.</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="bottom-nav">
        <a href="{{ route('home') }}" class="nav-item active">
            <span class="nav-icon">🏠</span><span class="nav-label">Beranda</span>
        </a>
        <a href="{{ route('cart.index') }}" class="nav-item">
            <span class="nav-icon">🛒</span>
            <span class="nav-label">Keranjang</span>
            @if($cartCount > 0)
                <span class="cart-badge" id="cartBadge">{{ $cartCount }}</span>
            @endif
        </a>
        <a href="#" class="nav-item">
            <span class="nav-icon">❤️</span><span class="nav-label">Favorit</span>
        </a>
        <a href="#" class="nav-item">
            <span class="nav-icon">👤</span><span class="nav-label">Profil</span>
        </a>
    </div>

    <div id="toast-notification" class="toast-notification"></div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.add-menu-btn').forEach(button => {
        button.addEventListener('click', function () {
            const menuId = this.dataset.menuId;
            addItemToCart(menuId, this);
        });
    });
});

async function addItemToCart(menuId, buttonElement) {
    const originalButtonText = buttonElement.innerHTML;
    buttonElement.disabled = true;
    buttonElement.innerHTML = '...';

    try {
        const response = await fetch("{{ route('api.cart.add') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ menu_id: menuId })
        });

        const result = await response.json();

        if (!response.ok || !result.success) {
            throw new Error(result.message || 'Gagal menambahkan item.');
        }

        updateCartBadge(result.cart_count);
        showToast(result.message);
        buttonElement.innerHTML = '✅ Ditambahkan';
        buttonElement.classList.add('added');

    } catch (error) {
        console.error('Error:', error);
        showToast('Terjadi kesalahan.', 'error');
        buttonElement.innerHTML = originalButtonText;

    } finally {
        setTimeout(() => {
            buttonElement.disabled = false;
            buttonElement.innerHTML = originalButtonText;
            buttonElement.classList.remove('added');
        }, 2000);
    }
}

function updateCartBadge(count) {
    const badge = document.getElementById('cartBadge');
    if (!badge) return;

    badge.textContent = count;
    badge.style.transform = 'scale(1.2)';
    badge.style.display = count > 0 ? 'flex' : 'none';
    setTimeout(() => { badge.style.transform = 'scale(1)'; }, 150);
}

function showToast(message, type = 'success') {
    const toast = document.getElementById('toast-notification');
    if (!toast) return;

    toast.className = 'toast-notification';
    if (type === 'error') {
        toast.classList.add('error');
    }
    toast.textContent = message;
    toast.classList.add('show');

    setTimeout(() => {
        toast.classList.remove('show');
    }, 2500);
}
</script>
@endpush