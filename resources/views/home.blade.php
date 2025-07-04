@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
<!-- Header Section -->
<div class="header">
    <div class="location-info">
        <div class="location-label">📍 Lokasi</div>
        <div class="location-text">Bali, Indonesia</div>
    </div>
    <div class="header-actions">
        <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="User Avatar" class="avatar">
    </div>
</div>

<!-- Search Bar -->
<div class="search-bar">
    <form method="GET" action="{{ route('home') }}" class="search-form">
        <input type="text" class="search-input" placeholder="Cari makanan atau minuman..." name="search" value="{{ request('search') }}">
        <input type="hidden" name="category" value="{{ request('category') }}">
        <button type="submit" class="search-btn">🔍</button>
    </form>
</div>

<!-- Alerts -->
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
@endif

<!-- Category Section -->
<div class="category-section">
    <div class="category-list">
        <a href="{{ route('home') }}" class="category-link">
            <div class="category-item category-default {{ !request('category') ? 'category-active' : '' }}">
                <div class="category-icon">🍽️</div>
                <span>Semua</span>
            </div>
        </a>
        @foreach($categories as $category)
            <a href="{{ route('home', ['category' => $category->id]) }}" class="category-link">
                <div class="category-item {{ $category->name == 'Makanan' ? 'category-makanan' : 'category-minuman' }} {{ isset($selectedCategory) && $selectedCategory && $selectedCategory->id == $category->id ? 'category-active' : '' }}">
                    <div class="category-icon">{{ $category->icon }}</div>
                    <span>{{ $category->name }}</span>
                </div>
            </a>
        @endforeach
    </div>
</div>

<!-- Menu List Section -->
<div class="food-section">
    <div class="food-list">
        @if($menus->count() > 0)
            @foreach($menus as $menu)
                <div class="food-card">
                    <img src="{{ $menu->image_url }}" alt="Gambar {{ $menu->name }}" class="menu-image">
                    <div class="food-title">{{ $menu->name }}</div>
                    <div class="food-desc">{{ $menu->description }}</div>
                    <div class="food-price">{{ $menu->formatted_price }}</div>
                    <form method="POST" action="{{ route('cart.add') }}" class="add-form">
                        @csrf
                        <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                        <input type="number" name="quantity" value="1" min="1" max="10" class="qty-input">
                        <button type="submit" class="add-btn">🛒 Tambah</button>
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

<!-- Bottom Navigation -->
<div class="bottom-nav">
    <a href="{{ route('home') }}" class="nav-item active">
        <span class="nav-icon">🏠</span>
        <span class="nav-label">Beranda</span>
    </a>
    <a href="{{ route('cart.index') }}" class="nav-item">
        <span class="nav-icon">🛒</span>
        <span class="nav-label">Keranjang</span>
        @if($cartCount > 0)
            <span class="cart-badge">{{ $cartCount }}</span>
        @endif
    </a>
    <a href="#" class="nav-item">
        <span class="nav-icon">❤️</span>
        <span class="nav-label">Favorit</span>
    </a>
    <a href="#" class="nav-item">
        <span class="nav-icon">👤</span>
        <span class="nav-label">Profil</span>
    </a>
</div>
@endsection
