{{-- ...existing code... --}}
<head>
    {{-- ...existing code... --}}
    <link rel="stylesheet" href="{{ asset('css/customer-menu.css') }}">
    {{-- ...existing code... --}}
</head>
{{-- ...existing code... --}}
<div class="customer-app-container">
    <div class="header">
        <div>
            <div style="font-size:12px;color:#bbb;">Location</div>
            <div style="font-weight:600;font-size:15px;">Sylhet, Bangladesh</div>
        </div>
        <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="avatar" style="width:38px;height:38px;border-radius:50%;">
        <span style="font-size:22px;color:#bbb;cursor:pointer;">&#128269;</span>
    </div>
    <div class="banner">
        <div class="banner-content">
            <div class="banner-title">Free Home <span style="color:#ffb400;">Delivery</span> Service</div>
            <div class="banner-desc">Nikmati layanan antar makanan gratis ke rumah Anda!</div>
            <button class="banner-btn">Order Now</button>
        </div>
        <img src="https://cdn-icons-png.flaticon.com/512/1046/1046784.png" alt="delivery" style="width:70px;">
    </div>
    <div class="category-list">
        @if(isset($categories) && $categories->count())
            @foreach($categories as $category)
                <a href="{{ url('/?category=' . $category->id) }}" style="text-decoration:none;">
                    <div class="category-item{{ (isset($selectedCategory) && $selectedCategory && $selectedCategory->id == $category->id) ? ' active' : '' }}">
                        <span style="font-size:20px;">{{ $category->icon ?? '🍽️' }}</span><br>
                        {{ $category->name ?? '-' }}
                    </div>
                </a>
            @endforeach
        @else
            <div class="category-item active">
                <span style="font-size:20px;">🍽️</span><br>
                Kategori Kosong
            </div>
        @endif
    </div>
    <div class="food-list">
        @if(isset($foods) && $foods->count())
            @foreach($foods as $food)
                <div class="food-card">
                    <img src="{{ $food->image_url ?? 'https://via.placeholder.com/180x120?text=No+Image' }}" alt="{{ $food->name ?? 'Makanan' }}">
                    <div class="food-rating">&#11088; {{ number_format($food->rating ?? 4.5, 1) }}</div>
                    <div class="food-title">{{ $food->name ?? '-' }}</div>
                    <div class="food-desc">{{ $food->description ?? '-' }}</div>
                    <div class="food-price">Rp{{ isset($food->price) ? number_format($food->price, 0, ',', '.') : '0' }}</div>
                </div>
            @endforeach
        @else
            <div class="food-card">
                <img src="https://via.placeholder.com/180x120?text=No+Image" alt="Makanan">
                <div class="food-rating">&#11088; 0.0</div>
                <div class="food-title">Menu Kosong</div>
                <div class="food-desc">Belum ada makanan tersedia</div>
                <div class="food-price">Rp0</div>
            </div>
        @endif
    </div>
    <div class="bottom-nav">
        <a href="#" class="active">&#8962;</a>
        <a href="#">&#128179;</a>
        <a href="#">&#10084;</a>
        <a href="#">&#128100;</a>
    </div>
</div>
{{-- ...existing code... --}}