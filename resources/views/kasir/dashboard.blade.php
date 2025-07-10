@extends('layouts.kasir')

{{-- PENTING: Pastikan layout 'layouts.kasir' Anda memiliki meta tag CSRF di dalam <head> --}}
{{-- Contoh: <meta name="csrf-token" content="{{ csrf_token() }}"> --}}

@section('content')
<div class="pos-system">
    {{-- ================================================= --}}
    {{-- Bagian Kiri: Daftar Produk & Kategori --}}
    {{-- ================================================= --}}
    <div class="products-section">
        <header class="pos-header">
            <div class="header-info">
                <h1 class="store-name">SISTEM KASIR</h1>
                <span class="store-subtitle">Point of Sale - Kasir Menu</span>
            </div>
            <form method="GET" action="{{ route('kasir.dashboard') }}" class="search-bar">
                <div class="search-input-group">
                    <svg class="search-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.35-4.35"></path></svg>
                    <input type="text" name="search" placeholder="Cari produk..." class="search-input" value="{{ request('search') }}">
                </div>
            </form>
        </header>
        
        <aside class="categories-panel">
            <nav class="categories-list">
                <a href="{{ route('kasir.dashboard') }}" class="category-item {{ !request('category') ? 'active' : '' }}">
                    <span>SEMUA</span>
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('kasir.dashboard', ['category' => $category->id]) }}" class="category-item {{ request('category') == $category->id ? 'active' : '' }}">
                        <span>{{ strtoupper($category->name) }}</span>
                    </a>
                @endforeach
            </nav>
        </aside>
        
        <main class="products-panel">
             <div class="products-grid">
                @forelse($foods as $food)
                    {{-- PERBAIKAN: Menghapus 'onclick' dan menggunakan data attributes untuk menyimpan info produk --}}
                    <div class="product-card" data-id="{{ $food->id }}" data-name="{{ htmlspecialchars($food->name, ENT_QUOTES) }}" data-price="{{ $food->price }}">
                        <div class="product-image-container">
                            <img src="{{ $food->image ? asset('storage/' . $food->image) : 'https://placehold.co/300x200/e2e8f0/334155?text=No+Image' }}" alt="{{ $food->name }}" class="product-image">
                        </div>
                        <div class="product-info">
                            <h4 class="product-name">{{ $food->name }}</h4>
                            <div class="product-price">Rp{{ number_format($food->price, 0, ',', '.') }}</div>
                        </div>
                    </div>
                @empty
                    <div class="no-products"><h3>PRODUK TIDAK DITEMUKAN</h3><p>Coba kata kunci atau kategori lain.</p></div>
                @endforelse
            </div>
        </main>
    </div>

    {{-- ================================================= --}}
    {{-- Bagian Kanan: Panel Transaksi (INTERAKTIF) --}}
    {{-- ================================================= --}}
    <aside class="order-panel">
        <div class="panel-header"><h3>Detail Transaksi</h3></div>
        
        <div class="customer-info">
            <div class="form-group">
                <label class="mb-1">Jenis Pesanan</label>
                <div>
                    <label style="margin-right: 1.5em;">
                        <input type="radio" name="order_type" value="takeaway" id="order_type_takeaway" checked>
                        Takeaway
                    </label>
                    <label>
                        <input type="radio" name="order_type" value="dine_in" id="order_type_dinein">
                        Dine In
                    </label>
                </div>
            </div>
            <div class="form-group">
                <label for="customer_name_input">Nama Pelanggan</label>
                <input type="text" id="customer_name_input" placeholder="Wajib diisi" class="form-input">
            </div>
            <div class="form-group" id="table_number_group" style="display:none;">
                <label for="table_number_input">Nomor Meja</label>
                <select id="table_number_input" class="form-input">
                    <option value="">Pilih Meja</option>
                    @for($i=1; $i<=10; $i++)
                        <option value="{{ $i }}">Meja {{ $i }}</option>
                    @endfor
                </select>
            </div>
        </div>

        <div class="order-items-container" id="order-items">
            <div class="empty-order"><p>Pilih produk untuk memulai transaksi</p></div>
        </div>

        <div class="order-summary">
            <div class="summary-row"><span>Subtotal</span><span id="subtotal">Rp 0</span></div>
            <div class="summary-row"><span>Pajak (10%)</span><span id="tax">Rp 0</span></div>
            <div class="summary-total"><span>TOTAL</span><span id="total">Rp 0</span></div>
        </div>
        
        <div class="order-actions">
            <button class="action-btn secondary" id="clear-order-btn">BERSIHKAN</button>
            <button class="action-btn primary" id="process-payment-btn" disabled>LANJUT BAYAR</button>
        </div>
    </aside>
</div>

{{-- CSS untuk Halaman POS --}}
<style>
    :root { --primary-color: #2563eb; --secondary-color: #4b5563; --bg-light: #f9fafb; --border-color: #e5e7eb; }
    .pos-system { display: grid; grid-template-columns: 1fr 420px; height: 100vh; background-color: var(--bg-light); font-family: 'Inter', sans-serif; }
    .products-section { display: flex; flex-direction: column; overflow: hidden; background-color: white; }
    .pos-header { display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.5rem; border-bottom: 1px solid var(--border-color); }
    .store-name { font-size: 1.5rem; font-weight: 700; color: #111827; }
    .store-subtitle { font-size: 0.875rem; color: var(--secondary-color); }
    .search-bar { width: 40%; }
    .search-input-group { position: relative; }
    .search-icon { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: var(--secondary-color); }
    .search-input { width: 100%; padding: 0.6rem 0.6rem 0.6rem 2.5rem; border-radius: 8px; border: 1px solid var(--border-color); background-color: var(--bg-light); }
    .categories-panel { padding: 0.75rem 1.5rem; border-bottom: 1px solid var(--border-color); }
    .categories-list { display: flex; gap: 0.75rem; overflow-x: auto; padding-bottom: 8px; }
    .category-item { padding: 0.5rem 1.25rem; border-radius: 99px; background: #f3f4f6; color: var(--secondary-color); font-weight: 500; cursor: pointer; text-decoration: none; white-space: nowrap; transition: all 0.2s; }
    .category-item.active, .category-item:hover { background: var(--primary-color); color: white; }
    .products-panel { flex-grow: 1; overflow-y: auto; padding: 1.5rem; }
    .products-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 1.25rem; }
    .product-card { border-radius: 12px; cursor: pointer; background-color: white; box-shadow: 0 1px 3px rgba(0,0,0,0.05); transition: all 0.2s ease; }
    .product-card:hover { transform: translateY(-3px); box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
    .product-image-container { width: 100%; height: 120px; pointer-events: none; }
    .product-image { width: 100%; height: 100%; object-fit: cover; border-top-left-radius: 12px; border-top-right-radius: 12px; }
    .product-info { padding: 0.75rem; pointer-events: none; }
    .product-name { font-weight: 600; font-size: 0.9rem; margin-bottom: 0.25rem; color: #1f2937; }
    .product-price { font-weight: 700; color: var(--primary-color); }
    .no-products { grid-column: 1 / -1; text-align: center; padding: 3rem; color: var(--secondary-color); }
    .order-panel { display: flex; flex-direction: column; background-color: white; border-left: 1px solid var(--border-color); }
    .panel-header { padding: 1.25rem 1.5rem; font-size: 1.25rem; font-weight: 700; border-bottom: 1px solid var(--border-color); }
    .customer-info { padding: 1.5rem; border-bottom: 1px solid var(--border-color); }
    .form-group { margin-bottom: 1rem; }
    .form-group label { display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem; color: #374151; }
    .form-input { width: 100%; padding: 0.6rem; border: 1px solid var(--border-color); border-radius: 8px; }
    .order-items-container { flex-grow: 1; padding: 1rem 1.5rem; overflow-y: auto; }
    .empty-order { text-align: center; padding: 4rem 1rem; color: var(--secondary-color); }
    .order-item { display: flex; justify-content: space-between; align-items: center; padding: 1rem 0; border-bottom: 1px solid var(--border-color); }
    .order-item:last-child { border-bottom: none; }
    .item-name { font-weight: 600; }
    .item-price { font-size: 0.875rem; color: var(--secondary-color); }
    .quantity-controls { display: flex; align-items: center; gap: 0.75rem; }
    .quantity-controls button { width: 28px; height: 28px; border-radius: 50%; border: 1px solid var(--border-color); background-color: var(--bg-light); cursor: pointer; }
    .item-total { font-weight: 700; }
    .order-summary { padding: 1.5rem; border-top: 8px solid #f3f4f6; }
    .summary-row, .summary-total { display: flex; justify-content: space-between; margin-bottom: 0.75rem; }
    .summary-total { font-weight: 700; font-size: 1.25rem; color: #111827; margin-top: 1rem; }
    .order-actions { padding: 1.5rem; display: flex; gap: 1rem; }
    .action-btn { flex-grow: 1; padding: 1rem; border-radius: 8px; border: none; font-weight: bold; font-size: 1rem; cursor: pointer; transition: all 0.2s; }
    .action-btn.secondary { background: #e5e7eb; color: #374151; }
    .action-btn.primary { background: var(--primary-color); color: white; }
    .action-btn:disabled { background-color: #9ca3af; color: #e5e7eb; cursor: not-allowed; }

    @media (max-width: 900px) {
        .pos-system {
            grid-template-columns: 1fr;
            height: auto;
        }
        .order-panel {
            position: static;
            border-left: none;
            border-top: 1px solid var(--border-color);
            width: 100%;
            min-width: 0;
            max-width: 100vw;
        }
        .products-section {
            min-width: 0;
        }
        .products-panel {
            max-height: none;
            padding: 1rem;
        }
    }

    @media (max-width: 600px) {
        .pos-header, .panel-header, .customer-info, .order-summary, .order-actions {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }
        .products-panel, .order-items-container {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }
        .products-grid {
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 0.75rem;
        }
        .order-panel {
            font-size: 0.95rem;
        }
        .action-btn {
            padding: 0.7rem;
            font-size: 0.95rem;
        }
    }
</style>

{{-- JavaScript untuk Logika POS --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Variabel untuk menyimpan item-item pesanan
    let orderItems = [];
    const processBtn = document.getElementById('process-payment-btn');
    const productsGrid = document.querySelector('.products-grid');

    // Fungsi untuk format mata uang Rupiah
    const formatCurrency = (number) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);

    // PERBAIKAN: Fungsi internal untuk menambah item, tidak lagi di 'window'
    const addToOrder = (id, name, price) => {
        const existingItem = orderItems.find(item => item.id === id);
        if (existingItem) {
            existingItem.quantity++;
        } else {
            orderItems.push({ id, name, price, quantity: 1 });
        }
        renderOrder();
    };

    // PERBAIKAN: Menggunakan event delegation pada container grid
    if(productsGrid) {
        productsGrid.addEventListener('click', (event) => {
            const card = event.target.closest('.product-card');
            if (card) {
                const id = parseInt(card.dataset.id, 10);
                const name = card.dataset.name;
                const price = parseFloat(card.dataset.price);
                
                if(!isNaN(id) && name && !isNaN(price)) {
                    addToOrder(id, name, price);
                }
            }
        });
    }

    // Fungsi untuk mengubah kuantitas item
    window.updateQuantity = (id, change) => {
        const item = orderItems.find(item => item.id === id);
        if (item) {
            item.quantity += change;
            if (item.quantity <= 0) {
                orderItems = orderItems.filter(i => i.id !== id);
            }
        }
        renderOrder();
    };

    // Fungsi untuk merender ulang daftar pesanan di panel kanan
    function renderOrder() {
        const container = document.getElementById('order-items');
        if (orderItems.length === 0) {
            container.innerHTML = '<div class="empty-order"><p>Pilih produk untuk memulai transaksi</p></div>';
        } else {
            container.innerHTML = orderItems.map(item => `
                <div class="order-item">
                    <div>
                        <div class="item-name">${item.name}</div>
                        <small class="item-price">${formatCurrency(item.price)}</small>
                    </div>
                    <div class="quantity-controls">
                        <button onclick="updateQuantity(${item.id}, -1)">-</button>
                        <span>${item.quantity}</span>
                        <button onclick="updateQuantity(${item.id}, 1)">+</button>
                    </div>
                    <b class="item-total">${formatCurrency(item.price * item.quantity)}</b>
                </div>
            `).join('');
        }
        calculateTotals();
    }
    
    // Fungsi untuk menghitung total, subtotal, dan pajak
    function calculateTotals() {
        const subtotal = orderItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        const tax = subtotal * 0.10; // Pajak 10%
        const total = subtotal + tax;
        
        document.getElementById('subtotal').textContent = formatCurrency(subtotal);
        document.getElementById('tax').textContent = formatCurrency(tax);
        document.getElementById('total').textContent = formatCurrency(total);

        processBtn.disabled = orderItems.length === 0;
    }
    
    document.getElementById('clear-order-btn').addEventListener('click', () => {
        if (confirm('Apakah Anda yakin ingin membersihkan semua item?')) {
            orderItems = [];
            document.getElementById('customer_name_input').value = '';
            renderOrder();
        }
    });

    // === Tambahan untuk order type dan nomor meja ===
    const orderTypeTakeaway = document.getElementById('order_type_takeaway');
    const orderTypeDinein = document.getElementById('order_type_dinein');
    const tableNumberGroup = document.getElementById('table_number_group');
    const tableNumberInput = document.getElementById('table_number_input');

    function updateOrderTypeUI() {
        if (orderTypeDinein.checked) {
            tableNumberGroup.style.display = '';
        } else {
            tableNumberGroup.style.display = 'none';
            tableNumberInput.value = '';
        }
    }
    orderTypeTakeaway.addEventListener('change', updateOrderTypeUI);
    orderTypeDinein.addEventListener('change', updateOrderTypeUI);
    updateOrderTypeUI();

    processBtn.addEventListener('click', async () => {
        const customerName = document.getElementById('customer_name_input').value.trim();
        const orderType = orderTypeDinein.checked ? 'dine_in' : 'takeaway';
        const tableNumber = tableNumberInput.value;

        if (!customerName) {
            alert('Nama pelanggan wajib diisi.');
            return;
        }
        if (orderType === 'dine_in' && !tableNumber) {
            alert('Nomor meja wajib dipilih untuk Dine In.');
            return;
        }
        if (orderItems.length === 0) {
            alert('Keranjang masih kosong.');
            return;
        }

        processBtn.disabled = true;
        processBtn.textContent = 'MEMPROSES...';

        const payload = {
            customer_name: customerName,
            order_type: orderType,
            items: orderItems.map(item => ({ id: item.id, quantity: item.quantity })),
        };
        if (orderType === 'dine_in') {
            payload.table_number = tableNumber;
        }

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const response = await fetch('{{ route("kasir.orders.storeTakeaway") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            const result = await response.json();

            if (response.ok && result.success) {
                window.location.href = result.redirect_url;
            } else {
                alert('Error: ' + (result.message || 'Terjadi kesalahan yang tidak diketahui.'));
                processBtn.disabled = false;
                processBtn.textContent = 'LANJUT BAYAR';
            }
        } catch (error) {
            console.error('Fetch error:', error);
            alert('Tidak dapat terhubung ke server. Periksa koneksi Anda.');
            processBtn.disabled = false;
            processBtn.textContent = 'LANJUT BAYAR';
        }
    });

    renderOrder();
});
</script>
@endsection