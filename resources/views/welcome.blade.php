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
                <div class="food-card" 
                    data-id="{{ $food->id }}" 
                    data-name="{{ $food->name }}" 
                    data-price="{{ $food->price }}" 
                    data-image="{{ asset('storage/'.$food->image) }}">
                    <img src="{{ asset('storage/'.$food->image) }}" alt="Gambar {{ $food->name }}" class="menu-image">
                    <div class="food-rating">&#11088; {{ number_format($food->rating ?? 4.5, 1) }}</div>
                    <div class="food-title">{{ $food->name ?? '-' }}</div>
                    <div class="food-desc">{{ $food->description ?? '-' }}</div>
                    <div class="food-price">Rp{{ isset($food->price) ? number_format($food->price, 0, ',', '.') : '0' }}</div>
                    <button class="add-to-transaction-btn" style="margin-top:8px;">Tambah ke Transaksi</button>
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
        <a href="#" id="transaction-btn">&#128179;</a>
        <a href="#">&#10084;</a>
        <a href="#">&#128100;</a>
    </div>
</div>

<!-- Modal Transaksi -->
<div id="transaction-modal" style="display:none;position:fixed;z-index:9999;left:0;top:0;width:100vw;height:100vh;background:rgba(0,0,0,0.3);align-items:center;justify-content:center;">
    <div style="background:#fff;padding:24px 16px 16px 16px;border-radius:12px;max-width:350px;width:90%;margin:auto;position:relative;">
        <span id="close-transaction-modal" style="position:absolute;top:8px;right:12px;font-size:22px;cursor:pointer;">&times;</span>
        <h3 style="margin-top:0;">Transaksi Anda</h3>
        <div id="transaction-list">
            <div style="color:#888;">Belum ada item.</div>
        </div>
        <div style="margin-top:16px;font-weight:bold;">
            Total: Rp<span id="transaction-total">0</span>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Transaction logic
    let transactionItems = [];

    function renderTransaction() {
        const list = document.getElementById('transaction-list');
        const total = document.getElementById('transaction-total');
        if (transactionItems.length === 0) {
            list.innerHTML = '<div style="color:#888;">Belum ada item.</div>';
            total.textContent = '0';
            return;
        }
        let html = '<ul style="padding-left:18px;">';
        let sum = 0;
        transactionItems.forEach(item => {
            html += `<li>
                <img src="${item.image}" alt="" style="width:32px;height:22px;object-fit:cover;border-radius:4px;vertical-align:middle;margin-right:6px;">
                ${item.name} - Rp${item.price.toLocaleString('id-ID')}
                <button onclick="removeTransactionItem(${item.id})" style="margin-left:6px;font-size:12px;">Hapus</button>
            </li>`;
            sum += item.price;
        });
        html += '</ul>';
        list.innerHTML = html;
        total.textContent = sum.toLocaleString('id-ID');
    }

    function removeTransactionItem(id) {
        transactionItems = transactionItems.filter(item => item.id !== id);
        renderTransaction();
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.add-to-transaction-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                const card = e.target.closest('.food-card');
                const id = parseInt(card.getAttribute('data-id'));
                const name = card.getAttribute('data-name');
                const price = parseInt(card.getAttribute('data-price')) || 0;
                const image = card.getAttribute('data-image');
                // Cek jika sudah ada, tidak ditambah dua kali
                if (!transactionItems.find(item => item.id === id)) {
                    transactionItems.push({id, name, price, image});
                    renderTransaction();
                }
            });
        });

        document.getElementById('transaction-btn').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('transaction-modal').style.display = 'flex';
            renderTransaction();
        });

        document.getElementById('close-transaction-modal').addEventListener('click', function() {
            document.getElementById('transaction-modal').style.display = 'none';
        });
    });
</script>
@endpush

{{-- ...existing code... --}}

{{-- Pastikan sebelum </body> ada: --}}
@stack('scripts')