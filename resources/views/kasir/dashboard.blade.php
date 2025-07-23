@extends('layouts.kasir')

@section('title', 'Dashboard POS')

@section('content')
<div class="pos-system">
    {{-- Bagian Kiri: Daftar Produk & Kategori --}}
    <div class="products-section">
        <header class="pos-header">
            <div class="header-info">
                <h1 class="store-name">SISTEM KASIR POS</h1>
                <span class="store-subtitle">Point of Sale - {{ auth('kasir')->user()->name }}</span>
            </div>
            <form method="GET" action="{{ route('kasir.dashboard') }}" class="search-bar">
                <div class="search-input-group">
                    <svg class="search-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
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
                    <a href="{{ route('kasir.dashboard', ['category' => $category->id]) }}" 
                       class="category-item {{ request('category') == $category->id ? 'active' : '' }}">
                        <span>{{ strtoupper($category->name) }}</span>
                    </a>
                @endforeach
            </nav>
        </aside>
        
        <main class="products-panel">
            <div class="products-grid">
                @forelse($foods as $food)
                    <div class="product-card" 
                         data-id="{{ $food->id }}" 
                         data-name="{{ htmlspecialchars($food->name, ENT_QUOTES) }}" 
                         data-price="{{ $food->price }}">
                        <div class="product-image-container">
                            <img src="{{ $food->image ? asset('storage/' . $food->image) : 'https://placehold.co/300x200/e2e8f0/334155?text=No+Image' }}" 
                                 alt="{{ $food->name }}" 
                                 class="product-image">
                        </div>
                        <div class="product-info">
                            <h4 class="product-name">{{ $food->name }}</h4>
                            <div class="product-price">Rp{{ number_format($food->price, 0, ',', '.') }}</div>
                        </div>
                    </div>
                @empty
                    <div class="no-products">
                        <h3>PRODUK TIDAK DITEMUKAN</h3>
                        <p>Coba kata kunci atau kategori lain.</p>
                    </div>
                @endforelse
            </div>
        </main>
    </div>

    {{-- Bagian Kanan: Panel Transaksi --}}
    <aside class="order-panel">
        <div class="panel-header">
            <h3>Detail Transaksi</h3>
        </div>
        
        <div class="customer-info">
            <div class="form-group">
                <label class="mb-1">Jenis Pesanan</label>
                <div class="radio-group">
                    <label class="radio-label">
                        <input type="radio" name="order_type" value="takeaway" id="order_type_takeaway" checked>
                        <span>Takeaway</span>
                    </label>
                    <label class="radio-label">
                        <input type="radio" name="order_type" value="dine_in" id="order_type_dinein">
                        <span>Dine In</span>
                    </label>
                </div>
            </div>
            
            <div class="form-group">
                <label for="customer_name_input">Nama Pelanggan</label>
                <input type="text" id="customer_name_input" placeholder="Wajib diisi" class="form-input" required>
            </div>
            
            <div class="form-group" id="table_number_group" style="display:none;">
                <label for="table_number_input">Nomor Meja</label>
                <select id="table_number_input" class="form-input">
                    <option value="">Pilih Meja</option>
                    @for($i=1; $i<=20; $i++)
                        <option value="{{ $i }}">Meja {{ $i }}</option>
                    @endfor
                </select>
            </div>
        </div>
        
        <div class="order-items-container" id="order-items">
            <div class="empty-order">
                <p>Pilih produk untuk memulai transaksi</p>
            </div>
        </div>
        
        <div class="order-summary">
            <div class="summary-row">
                <span>Subtotal</span>
                <span id="subtotal">Rp 0</span>
            </div>
            <div class="summary-row">
                <span>Biaya Layanan (10%)</span>
                <span id="service-fee">Rp 0</span>
            </div>
            <div class="summary-total">
                <span>TOTAL</span>
                <span id="total">Rp 0</span>
            </div>
        </div>
        
        <div class="order-actions">
            <button class="action-btn secondary" id="clear-order-btn">BERSIHKAN</button>
            <button type="button" class="action-btn primary" id="process-payment-btn" disabled>
                LANJUT BAYAR
            </button>
        </div>
    </aside>
</div>

{{-- Styles --}}
<style>
:root {
    --primary-color: #f59e0b;
    --primary-dark: #d97706;
    --primary-light: #fbbf24;
    --secondary-color: #4b5563;
    --bg-light: #f9fafb;
    --border-color: #e5e7eb;
    --warning-bg: #fffbeb;
    --warning-border: #fed7aa;
    --warning-text: #92400e;
}

.pos-system {
    display: grid;
    grid-template-columns: 1fr 420px;
    height: calc(100vh - 70px);
    background-color: var(--bg-light);
    font-family: 'Inter', sans-serif;
    gap: 0;
}

.products-section {
    display: flex;
    flex-direction: column;
    overflow: hidden;
    background-color: white;
    min-width: 0;
}

.pos-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid var(--border-color);
    flex-shrink: 0;
    background: linear-gradient(135deg, var(--warning-bg) 0%, #fef3c7 100%);
}

.store-name {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--warning-text);
    margin: 0;
}

.store-subtitle {
    font-size: 0.875rem;
    color: var(--warning-text);
    opacity: 0.8;
}

.search-bar {
    width: 40%;
    min-width: 200px;
}

.search-input-group {
    position: relative;
}

.search-icon {
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--primary-color);
}

.search-input {
    width: 100%;
    padding: 0.6rem 0.6rem 0.6rem 2.5rem;
    border-radius: 8px;
    border: 2px solid var(--warning-border);
    background-color: white;
    transition: all 0.2s ease;
}

.search-input:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
}

.categories-panel {
    padding: 0.75rem 1.5rem;
    border-bottom: 1px solid var(--border-color);
    flex-shrink: 0;
    background: var(--warning-bg);
}

.categories-list {
    display: flex;
    gap: 0.75rem;
    overflow-x: auto;
    padding-bottom: 8px;
}

.category-item {
    padding: 0.5rem 1.25rem;
    border-radius: 99px;
    background: white;
    color: var(--warning-text);
    font-weight: 500;
    cursor: pointer;
    text-decoration: none;
    white-space: nowrap;
    transition: all 0.2s;
    border: 2px solid var(--warning-border);
}

.category-item.active, .category-item:hover {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    color: white;
    border-color: var(--primary-color);
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
}

.products-panel {
    flex-grow: 1;
    overflow-y: auto;
    padding: 1.5rem;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 1.25rem;
}

.product-card {
    border-radius: 12px;
    cursor: pointer;
    background-color: white;
    box-shadow: 0 2px 8px rgba(245, 158, 11, 0.1);
    transition: all 0.2s ease;
    border: 2px solid transparent;
}

.product-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(245, 158, 11, 0.2);
    border-color: var(--primary-light);
}

.product-image-container {
    width: 100%;
    height: 120px;
    pointer-events: none;
    position: relative;
    overflow: hidden;
    border-radius: 10px 10px 0 0;
}

.product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
}

.product-info {
    padding: 0.75rem;
    pointer-events: none;
}

.product-name {
    font-weight: 600;
    font-size: 0.9rem;
    margin-bottom: 0.25rem;
    color: #1f2937;
}

.product-price {
    font-weight: 700;
    color: var(--primary-color);
    font-size: 1rem;
}

.no-products {
    grid-column: 1 / -1;
    text-align: center;
    padding: 3rem;
    color: var(--secondary-color);
}

.order-panel {
    display: flex;
    flex-direction: column;
    background-color: white;
    border-left: 1px solid var(--border-color);
    min-width: 0;
}

.panel-header {
    padding: 1.25rem 1.5rem;
    font-size: 1.25rem;
    font-weight: 700;
    border-bottom: 1px solid var(--border-color);
    flex-shrink: 0;
    background: linear-gradient(135deg, var(--warning-bg) 0%, #fef3c7 100%);
    color: var(--warning-text);
}

.customer-info {
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-color);
    flex-shrink: 0;
}

.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    display: block;
    font-size: 0.875rem;
    font-weight: 500;
    margin-bottom: 0.25rem;
    color: #374151;
}

.radio-group {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.radio-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    font-size: 0.875rem;
    padding: 0.5rem 1rem;
    border: 2px solid var(--warning-border);
    border-radius: 8px;
    transition: all 0.2s ease;
    background: white;
}

.radio-label:has(input:checked) {
    background: var(--warning-bg);
    border-color: var(--primary-color);
    color: var(--warning-text);
}

.form-input {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid var(--warning-border);
    border-radius: 8px;
    transition: all 0.2s ease;
    background: white;
}

.form-input:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
}

.order-items-container {
    flex-grow: 1;
    padding: 1rem 1.5rem;
    overflow-y: auto;
}

.empty-order {
    text-align: center;
    padding: 4rem 1rem;
    color: var(--secondary-color);
}

.order-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 0;
    border-bottom: 1px solid var(--border-color);
}

.order-item:last-child {
    border-bottom: none;
}

.item-name {
    font-weight: 600;
}

.item-price {
    font-size: 0.875rem;
    color: var(--secondary-color);
}

.quantity-controls {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.quantity-controls button {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    border: 2px solid var(--primary-color);
    background-color: white;
    cursor: pointer;
    color: var(--primary-color);
    font-weight: bold;
    transition: all 0.2s ease;
}

.quantity-controls button:hover {
    background-color: var(--primary-color);
    color: white;
    transform: scale(1.1);
}

.item-total {
    font-weight: 700;
    color: var(--primary-color);
}

.order-summary {
    padding: 1.5rem;
    border-top: 8px solid var(--warning-bg);
    flex-shrink: 0;
}

.summary-row, .summary-total {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.75rem;
}

.summary-total {
    font-weight: 700;
    font-size: 1.25rem;
    color: var(--primary-color);
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 2px solid var(--warning-border);
}

.order-actions {
    padding: 1.5rem;
    display: flex;
    gap: 1rem;
    flex-shrink: 0;
}

.action-btn {
    flex-grow: 1;
    padding: 1rem;
    border-radius: 8px;
    border: none;
    font-weight: bold;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.2s;
}

.action-btn.secondary {
    background: #f1f5f9;
    color: #374151;
    border: 2px solid #e2e8f0;
}

.action-btn.secondary:hover {
    background: #e2e8f0;
    transform: translateY(-1px);
}

.action-btn.primary {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
}

.action-btn.primary:hover {
    background: linear-gradient(135deg, var(--primary-dark) 0%, #b45309 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(245, 158, 11, 0.4);
}

.action-btn:disabled {
    background-color: #9ca3af;
    color: #e5e7eb;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .pos-system {
        grid-template-columns: 1fr 350px;
    }
    
    .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    }
}

@media (max-width: 768px) {
    .pos-system {
        grid-template-columns: 1fr;
        height: auto;
    }
    
    .order-panel {
        border-left: none;
        border-top: 1px solid var(--border-color);
    }
    
    .pos-header {
        flex-direction: column;
        gap: 1rem;
    }
    
    .search-bar {
        width: 100%;
    }
}
</style>

{{-- JavaScript --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    let orderItems = [];
    const processBtn = document.getElementById('process-payment-btn');
    const productsGrid = document.querySelector('.products-grid');
    
    // Format currency
    const formatCurrency = (number) => new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(number);
    
    // Add item to order
    const addToOrder = (id, name, price) => {
        const existingItem = orderItems.find(item => item.id === id);
        if (existingItem) {
            existingItem.quantity++;
        } else {
            orderItems.push({ id, name, price, quantity: 1 });
        }
        renderOrder();
    };
    
    // Product click handler
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
    
    // Update quantity
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
    
    // Render order items
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
    
    // Calculate totals
    function calculateTotals() {
        const subtotal = orderItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        const serviceFee = subtotal * 0.10;
        const total = subtotal + serviceFee;
        
        document.getElementById('subtotal').textContent = formatCurrency(subtotal);
        document.getElementById('service-fee').textContent = formatCurrency(serviceFee);
        document.getElementById('total').textContent = formatCurrency(total);
        processBtn.disabled = orderItems.length === 0;
    }
    
    // Clear order
    document.getElementById('clear-order-btn').addEventListener('click', () => {
        if (confirm('Apakah Anda yakin ingin membersihkan semua item?')) {
            orderItems = [];
            document.getElementById('customer_name_input').value = '';
            renderOrder();
        }
    });
    
    // Order type toggle
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
    
    // Process payment
    document.getElementById('process-payment-btn').addEventListener('click', async () => {
        const customerName = document.getElementById('customer_name_input').value.trim();
        const orderType = document.getElementById('order_type_dinein').checked ? 'dine_in' : 'takeaway';
        const tableNumber = document.getElementById('table_number_input').value;
        
        // Validation
        if (!customerName) {
            alert('Nama pelanggan harus diisi!');
            return;
        }
        
        if (orderType === 'dine_in' && !tableNumber) {
            alert('Nomor meja harus dipilih untuk Dine In!');
            return;
        }
        
        if (orderItems.length === 0) {
            alert('Pilih minimal satu menu!');
            return;
        }
        
        processBtn.disabled = true;
        processBtn.textContent = 'MEMPROSES...';
        
        const payload = {
            customer_name: customerName,
            order_type: orderType,
            items: orderItems.map(item => ({ id: item.id, quantity: item.quantity })),
            table_number: orderType === 'dine_in' ? tableNumber : null
        };
        
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
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
                alert('Error: ' + (result.message || 'Terjadi kesalahan.'));
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
