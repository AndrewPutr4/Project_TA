@extends('layouts.app')

@section('title', 'Beranda')

@push('styles')
<style>
    /* Sticky Header */
    .header {
        position: sticky;
        top: 0;
        z-index: 100;
        transition: all 0.3s ease;
    }

    .header.scrolled {
        box-shadow: 0 4px 20px rgba(255, 107, 107, 0.4);
        backdrop-filter: blur(10px);
    }

    /* Search Bar Improvements */
    .search-bar {
        position: sticky;
        top: 80px;
        z-index: 99;
        background: white;
        transition: all 0.3s ease;
    }

    .search-bar.active {
        display: block;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    /* Category Section Sticky */
    .category-section {
        position: sticky;
        top: 140px;
        z-index: 98;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }

    /* Image Loading States */
    .menu-image {
        transition: opacity 0.3s ease;
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
    }

    .menu-image.loading {
        opacity: 0.7;
        background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
    }

    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }

    /* Food Card Improvements */
    .food-card {
        transition: all 0.3s ease;
        transform: translateY(0);
    }

    .food-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.15);
    }

    .food-card.loading {
        pointer-events: none;
        opacity: 0.7;
    }

    /* Button States */
    .add-menu-btn {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .add-menu-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 107, 107, 0.4);
    }

    .add-menu-btn.added {
        background: linear-gradient(135deg, #10b981, #059669);
        transform: scale(0.95);
    }

    .add-menu-btn:disabled {
        opacity: 0.8;
        cursor: not-allowed;
        transform: none;
    }

    /* Toast Improvements */
    .toast {
        position: fixed;
        top: 100px;
        right: 20px;
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        padding: 12px 20px;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(16, 185, 129, 0.3);
        transform: translateX(100%);
        transition: transform 0.3s ease;
        z-index: 1000;
        max-width: 300px;
        font-weight: 600;
    }

    .toast.show {
        transform: translateX(0);
    }

    .toast.error {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        box-shadow: 0 4px 20px rgba(220, 38, 38, 0.3);
    }

    /* Loading Overlay */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.9);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 2000;
    }

    .loading-spinner {
        width: 40px;
        height: 40px;
        border: 4px solid #f1f5f9;
        border-top: 4px solid #ff6b6b;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Responsive Improvements */
    @media (max-width: 480px) {
        .header {
            padding: 15px 15px 20px;
        }
        
        .search-bar {
            top: 70px;
        }
        
        .category-section {
            top: 120px;
            padding: 20px 15px;
        }
        
        .food-section {
            padding: 0 15px 100px;
        }
    }

    /* Smooth Scrolling */
    html {
        scroll-behavior: smooth;
    }

    /* Category Scroll Indicator */
    .category-list {
        scrollbar-width: none;
        -ms-overflow-style: none;
        position: relative;
    }

    .category-list::-webkit-scrollbar {
        display: none;
    }

    .category-list::after {
        content: '';
        position: absolute;
        right: 0;
        top: 0;
        bottom: 0;
        width: 20px;
        background: linear-gradient(to left, #f8fafc, transparent);
        pointer-events: none;
    }
</style>
@endpush

@section('content')
<div class="customer-app-container">
    <!-- Header Section -->
    <div class="header" id="mainHeader">
        <div class="location-info">
            <div class="location-label">📍 Lokasi</div>
            <div class="location-text">Bali, Indonesia</div>
        </div>
        <div class="header-actions">
            <img src="https://randomuser.me/api/portraits/men/32.jpg" 
                 alt="User Avatar" 
                 class="avatar"
                 onclick="showProfile()"
                 onerror="this.src='https://ui-avatars.com/api/?name=User&background=ff6b6b&color=fff'">
            <div class="search-icon" onclick="toggleSearch()" title="Cari Menu">🔍</div>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="search-bar" id="searchBar">
        <form method="GET" action="{{ route('home') }}" id="searchForm">
            <input type="text" 
                   class="search-input" 
                   placeholder="Cari bakso atau minuman..." 
                   name="search"
                   value="{{ request('search') }}"
                   id="searchInput"
                   autocomplete="off">
            <input type="hidden" name="category" value="{{ request('category') }}">
            <button type="submit" class="search-btn" title="Cari">🔍</button>
        </form>
    </div>

    <!-- Category Section -->
    <div class="category-section">
        <div class="category-list" id="categoryList">
            <a href="{{ route('home') }}" class="category-link">
                <div class="category-item category-default {{ !request('category') ? 'category-active' : '' }}">
                    <div class="category-icon">🍽️</div>
                    <span>Semua</span>
                </div>
            </a>
            @foreach($categories as $category)
                <a href="{{ route('home', ['category' => $category->id]) }}" class="category-link">
                    <div class="category-item {{ $category->name == 'makanan' ? 'category-makanan' : ($category->name == 'Minuman' ? 'category-minuman' : 'category-default') }} {{ isset($selectedCategory) && $selectedCategory && $selectedCategory->id == $category->id ? 'category-active' : '' }}">
                        <div class="category-icon">{{ $category->icon }}</div>
                        <span>{{ $category->name }}</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Menu List Section -->
    <div class="food-section">
    <div class="food-list" id="foodList">
        @if($foods->count() > 0)
            @foreach($foods as $menu)
                @php
                    // Cek apakah gambar tersedia
                    $imagePath = public_path('storage/' . $menu->image);
                    $imageSrc = file_exists($imagePath) ? asset('storage/' . $menu->image) : asset('storage/menus/no-image.png');
                @endphp

                <div class="food-card">
                    <img src="{{ $imageSrc }}"
                         alt="Gambar {{ $menu->name }}"
                         class="menu-image loading"
                         loading="lazy">
                         
                    <div class="food-title">{{ $menu->name }}</div>
                    <div class="food-desc">{{ $menu->description }}</div>
                    <div class="food-price">{{ $menu->formatted_price }}</div>

                    {{-- ✅ FORM TANPA JAVASCRIPT --}}
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                        <input type="hidden" name="name" value="{{ $menu->name }}">
                        <input type="hidden" name="price" value="{{ $menu->price }}">
                        <input type="hidden" name="image" value="{{ $menu->image }}">
                        <input type="hidden" name="description" value="{{ $menu->description }}">

                        <button type="submit" class="add-menu-btn" title="Tambah {{ $menu->name }} ke keranjang">
                            🛒 Tambah ke Keranjang
                        </button>
                    </form>
                </div>
            @endforeach
        @else
            <div class="empty-state">
                <div class="empty-icon">🍽️</div>
                <h3>Tidak ada menu ditemukan</h3>
                <p>Coba ubah kategori atau kata kunci pencarian</p>
                <a href="{{ route('home') }}" class="btn-primary">Lihat Semua Menu</a>
            </div>
        @endif
    </div>
</div>

    <!-- Order Summary / Checkout Form (letakkan di bagian bawah cart atau checkout) -->
    <form method="POST" action="{{ route('order.checkout') }}">
        @csrf
        <!-- ...field customer_name, customer_phone, customer_address, notes... -->
        <div class="form-group">
            <label for="table_number">Nomor Meja (Opsional, untuk dine-in)</label>
            <select id="table_number" name="table_number" class="form-control">
                <option value="">Pilih Nomor Meja</option>
                @for($i = 1; $i <= 20; $i++)
                    <option value="{{ $i }}" {{ old('table_number') == $i ? 'selected' : '' }}>Meja {{ $i }}</option>
                @endfor
            </select>
        </div>
        <!-- ...tombol submit... -->
    </form>

    <!-- Bottom Navigation -->
    <div class="bottom-nav">
        <a href="{{ route('home') }}" class="nav-item active" title="Beranda">
            <span class="nav-icon">🏠</span>
            <span class="nav-label">Beranda</span>
        </a>
        <a href="{{ url('/cart') }}" class="nav-item" title="Keranjang" id="cartLink">
            <span class="nav-icon">🛒</span>
            <span class="nav-label">Keranjang</span>
            <span class="cart-badge" id="cartBadge" style="display: none;">0</span>
        </a>
        <a href="#" class="nav-item" title="Favorit" onclick="showFavorites()">
            <span class="nav-icon">❤️</span>
            <span class="nav-label">Favorit</span>
        </a>
        <a href="#" class="nav-item" title="Profil" onclick="showProfile()">
            <span class="nav-icon">👤</span>
            <span class="nav-label">Profil</span>
        </a>
    </div>

    <!-- Toast Notification -->
    <div class="toast" id="toast"></div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Global variables
let cart = JSON.parse(localStorage.getItem('cart')) || [];
let isLoading = false;

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateCartBadge();
    initializeScrollEffects();
    initializeCategoryScroll();
    preloadImages();
});

// Scroll Effects
function initializeScrollEffects() {
    const header = document.getElementById('mainHeader');
    let lastScrollTop = 0;

    window.addEventListener('scroll', function() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        // Add scrolled class for styling
        if (scrollTop > 10) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
        
        lastScrollTop = scrollTop;
    }, { passive: true });
}

// Category Scroll
function initializeCategoryScroll() {
    const categoryList = document.getElementById('categoryList');
    if (!categoryList) return;

    // Touch scroll for mobile
    let isDown = false;
    let startX;
    let scrollLeft;

    categoryList.addEventListener('mousedown', (e) => {
        isDown = true;
        startX = e.pageX - categoryList.offsetLeft;
        scrollLeft = categoryList.scrollLeft;
        categoryList.style.cursor = 'grabbing';
    });

    categoryList.addEventListener('mouseleave', () => {
        isDown = false;
        categoryList.style.cursor = 'grab';
    });

    categoryList.addEventListener('mouseup', () => {
        isDown = false;
        categoryList.style.cursor = 'grab';
    });

    categoryList.addEventListener('mousemove', (e) => {
        if (!isDown) return;
        e.preventDefault();
        const x = e.pageX - categoryList.offsetLeft;
        const walk = (x - startX) * 2;
        categoryList.scrollLeft = scrollLeft - walk;
    });
}

// Image Handling
function handleImageError(img, menuName) {
    // Create a placeholder with menu name
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    canvas.width = 180;
    canvas.height = 120;
    
    // Background
    ctx.fillStyle = '#f1f5f9';
    ctx.fillRect(0, 0, 180, 120);
    
    // Text
    ctx.fillStyle = '#64748b';
    ctx.font = '14px Inter, sans-serif';
    ctx.textAlign = 'center';
    ctx.fillText('🍽️', 90, 50);
    ctx.font = '12px Inter, sans-serif';
    ctx.fillText(menuName.substring(0, 15), 90, 80);
    
    img.src = canvas.toDataURL();
    img.classList.remove('loading');
}

function preloadImages() {
    const images = document.querySelectorAll('.menu-image');
    images.forEach(img => {
        if (img.complete) {
            img.classList.remove('loading');
        }
    });
}

// Cart functionality
function updateCartBadge() {
    const badge = document.getElementById('cartBadge');
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    
    if (totalItems > 0) {
        badge.textContent = totalItems;
        badge.style.display = 'flex';
        
        // Animate badge
        badge.style.transform = 'scale(1.2)';
        setTimeout(() => {
            badge.style.transform = 'scale(1)';
        }, 200);
    } else {
        badge.style.display = 'none';
    }
}

function addToCart(menuId, menuName, menuPrice, menuImage, menuDescription) {
    if (isLoading) return;
    
    const existingItem = cart.find(item => item.id === menuId);
    
    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({
            id: menuId,
            name: menuName,
            price: menuPrice,
            image: menuImage,
            description: menuDescription,
            quantity: 1
        });
    }
    
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartBadge();
    
    // Show feedback
    const button = document.querySelector(`[data-food-id="${menuId}"]`);
    if (button) {
        const originalText = button.innerHTML;
        button.innerHTML = '✅ Ditambahkan!';
        button.classList.add('added');
        button.disabled = true;
        
        setTimeout(() => {
            button.innerHTML = originalText;
            button.classList.remove('added');
            button.disabled = false;
        }, 1500);
    }
    
    showToast(`${menuName} ditambahkan ke keranjang!`, 'success');
}

// Toast functionality
function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    if (!toast) return;

    toast.textContent = message;
    toast.className = `toast ${type}`;
    toast.classList.add('show');
    
    setTimeout(() => {
        toast.classList.remove('show');
    }, 3000);
}

// Search functionality
function toggleSearch() {
    const searchBar = document.getElementById('searchBar');
    const searchInput = document.getElementById('searchInput');
    
    if (searchBar.classList.contains('active')) {
        searchBar.classList.remove('active');
        searchInput.value = '';
        // Clear search and reload
        const currentCategory = new URLSearchParams(window.location.search).get('category');
        window.location.href = '{{ route("home") }}' + (currentCategory ? '?category=' + currentCategory : '');
    } else {
        searchBar.classList.add('active');
        setTimeout(() => searchInput.focus(), 100);
    }
}

// Auto submit search form with debounce
let searchTimeout;
document.getElementById('searchInput').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        if (this.value.length >= 2 || this.value.length === 0) {
            showLoading();
            document.getElementById('searchForm').submit();
        }
    }, 500);
});

// Loading functions
function showLoading() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) {
        overlay.style.display = 'flex';
        isLoading = true;
    }
}

function hideLoading() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) {
        overlay.style.display = 'none';
        isLoading = false;
    }
}

// Profile functionality
function showProfile() {
    showToast('Fitur profil akan segera hadir!', 'info');
}

function showFavorites() {
    showToast('Fitur favorit akan segera hadir!', 'info');
}

// Smooth scroll to top when clicking logo
function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

// Handle page visibility change
document.addEventListener('visibilitychange', function() {
    if (!document.hidden) {
        updateCartBadge();
    }
});

// Handle online/offline status
window.addEventListener('online', function() {
    showToast('Koneksi internet tersambung kembali!', 'success');
});

window.addEventListener('offline', function() {
    showToast('Koneksi internet terputus!', 'error');
});

// Prevent form submission when loading
document.addEventListener('submit', function(e) {
    if (isLoading) {
        e.preventDefault();
        return false;
    }
});

// Hide loading on page load
window.addEventListener('load', function() {
    hideLoading();
});
</script>
@endpush
