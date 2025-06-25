@extends('layouts.admin')

@section('content')
<style>
    .menu-container {
        padding: 32px 24px;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: calc(100vh - 70px);
    }

    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 40px;
        border-radius: 20px;
        margin-bottom: 30px;
        box-shadow: 0 20px 40px rgba(102, 126, 234, 0.3);
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(180deg); }
    }

    .page-header .header-content {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }

    .page-header h1 {
        font-size: 2.8rem;
        font-weight: 800;
        margin: 0 0 10px 0;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .page-header .subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
        font-weight: 300;
    }

    .breadcrumb {
        display: flex;
        align-items: center;
        list-style: none;
        gap: 8px;
        margin: 15px 0 0 0;
        padding: 0;
    }

    .breadcrumb li a {
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        padding: 5px 10px;
        border-radius: 8px;
    }

    .breadcrumb li a:hover,
    .breadcrumb li a.active {
        color: white;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
    }

    .breadcrumb li i {
        color: rgba(255, 255, 255, 0.6);
        font-size: 0.9rem;
    }

    .btn-add {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 15px 30px;
        background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
        color: white;
        text-decoration: none;
        border-radius: 15px;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 8px 25px rgba(86, 171, 47, 0.3);
        position: relative;
        overflow: hidden;
    }

    .btn-add::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }

    .btn-add:hover::before {
        left: 100%;
    }

    .btn-add:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(86, 171, 47, 0.4);
    }

    .btn-add i {
        font-size: 1.2rem;
    }

    .menu-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .menu-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    }

    .card-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 25px 30px;
        border-bottom: 1px solid #e9ecef;
        position: relative;
    }

    .card-header h3 {
        margin: 0;
        color: #495057;
        font-size: 1.5rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .card-header .menu-count {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-left: auto;
    }

    .table-container {
        overflow-x: auto;
        background: white;
    }

    .menu-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
    }

    .menu-table thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .menu-table th {
        padding: 20px 25px;
        text-align: left;
        font-weight: 600;
        font-size: 0.95rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .menu-table td {
        padding: 20px 25px;
        border-bottom: 1px solid #f0f0f0;
        vertical-align: middle;
        font-size: 0.95rem;
    }

    .menu-table tbody tr {
        transition: all 0.3s ease;
        position: relative;
    }

    .menu-table tbody tr:hover {
        background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 100%);
        transform: scale(1.01);
    }

    .menu-image {
        width: 70px;
        height: 70px;
        object-fit: cover;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
        border: 3px solid white;
    }

    .menu-image:hover {
        transform: scale(1.1);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
    }

    .no-image {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #e9ecef 0%, #f8f9fa 100%);
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #adb5bd;
        font-size: 1.5rem;
        border: 2px dashed #dee2e6;
    }

    .menu-name {
        font-weight: 600;
        color: #495057;
        font-size: 1.1rem;
        margin-bottom: 5px;
    }

    .menu-description {
        color: #6c757d;
        font-size: 0.85rem;
        opacity: 0.8;
    }

    .category-badge {
        background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-block;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .price-tag {
        font-size: 1.2rem;
        font-weight: 700;
        color: #28a745;
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        padding: 10px 15px;
        border-radius: 12px;
        display: inline-block;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .btn-edit {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 10px 16px;
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        text-decoration: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(240, 147, 251, 0.3);
    }

    .btn-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(240, 147, 251, 0.4);
        color: white;
        text-decoration: none;
    }

    .btn-delete {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 10px 16px;
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);
    }

    .btn-delete:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(255, 107, 107, 0.4);
    }

    .empty-state {
        text-align: center;
        padding: 80px 20px;
        color: #6c757d;
    }

    .empty-state-icon {
        font-size: 5rem;
        margin-bottom: 20px;
        opacity: 0.3;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .empty-state h4 {
        font-size: 1.5rem;
        margin-bottom: 10px;
        color: #495057;
    }

    .empty-state p {
        font-size: 1rem;
        margin-bottom: 30px;
    }

    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        padding: 25px;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        gap: 15px;
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }

    .stat-card:nth-child(1) {
        border-left-color: #667eea;
    }

    .stat-card:nth-child(2) {
        border-left-color: #56ab2f;
    }

    .stat-card:nth-child(3) {
        border-left-color: #f093fb;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
    }

    .stat-card:nth-child(1) .stat-icon {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .stat-card:nth-child(2) .stat-icon {
        background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
    }

    .stat-card:nth-child(3) .stat-icon {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .stat-info h3 {
        font-size: 1.8rem;
        font-weight: 700;
        margin: 0 0 5px 0;
        color: #495057;
    }

    .stat-info p {
        margin: 0;
        color: #6c757d;
        font-weight: 500;
    }

    @media (max-width: 768px) {
        .menu-container {
            padding: 20px 15px;
        }

        .page-header {
            padding: 25px 20px;
        }

        .page-header h1 {
            font-size: 2rem;
        }

        .page-header .header-content {
            flex-direction: column;
            align-items: flex-start;
        }

        .menu-table th,
        .menu-table td {
            padding: 15px 10px;
            font-size: 0.85rem;
        }

        .action-buttons {
            flex-direction: column;
            gap: 5px;
        }

        .btn-edit,
        .btn-delete {
            font-size: 0.8rem;
            padding: 8px 12px;
        }
    }

    /* Loading Animation */
    @keyframes shimmer {
        0% { background-position: -200px 0; }
        100% { background-position: calc(200px + 100%) 0; }
    }

    .loading {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200px 100%;
        animation: shimmer 1.5s infinite;
    }
</style>

<div class="menu-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <div class="left">
                <h1>
                    <i class='bx bxs-food-menu'></i>
                    Menu Makanan & Minuman
                </h1>
                <div class="subtitle">Kelola menu makanan dan minuman restoran</div>
                <ul class="breadcrumb">
                    <li>
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li>
                        <a class="active" href="#">Menu</a>
                    </li>
                </ul>
            </div>
            <a href="{{ route('admin.menus.create') }}" class="btn-add">
                <i class='bx bx-plus'></i>
                <span class="text">Tambah Menu Baru</span>
            </a>
        </div>
    </div>

    <!-- Statistics Row -->
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon">
                <i class='bx bxs-food-menu'></i>
            </div>
            <div class="stat-info">
                <h3>{{ $menus->count() }}</h3>
                <p>Total Menu</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class='bx bxs-category'></i>
            </div>
            <div class="stat-info">
                <h3>{{ $menus->pluck('category_id')->unique()->count() }}</h3>
                <p>Kategori</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class='bx bxs-dollar-circle'></i>
            </div>
            <div class="stat-info">
                <h3>Rp {{ number_format($menus->avg('price') ?? 0, 0, ',', '.') }}</h3>
                <p>Harga Rata-rata</p>
            </div>
        </div>
    </div>

    <!-- Menu Table -->
    <div class="menu-card">
        <div class="card-header">
            <h3>
                <i class='bx bxs-grid'></i>
                Daftar Menu
            </h3>
            <div class="menu-count">{{ $menus->count() }} Menu</div>
        </div>
        <div class="table-container">
            <table class="menu-table">
                <thead>
                    <tr>
                        <th style="width: 100px;">Gambar</th>
                        <th>Nama Menu</th>
                        <th style="width: 150px;">Kategori</th>
                        <th style="width: 150px;">Harga</th>
                        <th style="width: 200px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($menus as $menu)
                    <tr>
                        <td>
                            @if($menu->image && file_exists(public_path('storage/'.$menu->image)))
                                <img src="{{ asset('storage/'.$menu->image) }}" alt="Gambar {{ $menu->name }}" class="menu-image">
                            @else
                                <div class="no-image">
                                    <i class='bx bx-image'></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="menu-name">{{ $menu->name }}</div>
                            @if($menu->description)
                                <div class="menu-description">{{ Str::limit($menu->description, 50) }}</div>
                            @endif
                        </td>
                        <td>
                            @if($menu->category)
                                <span class="category-badge">{{ $menu->category->name }}</span>
                            @else
                                <span style="color: #adb5bd; font-style: italic;">Tidak ada kategori</span>
                            @endif
                        </td>
                        <td>
                            <div class="price-tag">
                                Rp {{ number_format($menu->price, 0, ',', '.') }}
                            </div>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.menus.edit', $menu) }}" class="btn-edit">
                                    <i class='bx bx-edit'></i>
                                    Edit
                                </a>
                                <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" style="display: inline;">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus menu {{ $menu->name }}?')">
                                        <i class='bx bx-trash'></i>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <div class="empty-state-icon">🍽️</div>
                                <h4>Belum ada menu</h4>
                                <p>Mulai tambahkan menu makanan dan minuman pertama Anda</p>
                                <a href="{{ route('admin.menus.create') }}" class="btn-add">
                                    <i class='bx bx-plus'></i>
                                    Tambah Menu Pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
// Add loading animation to buttons
document.querySelectorAll('.btn-add, .btn-edit, .btn-delete').forEach(btn => {
    btn.addEventListener('click', function() {
        this.style.transform = 'scale(0.95)';
        setTimeout(() => {
            this.style.transform = 'scale(1)';
        }, 150);
    });
});

// Image error handling
document.querySelectorAll('.menu-image').forEach(img => {
    img.addEventListener('error', function() {
        this.style.display = 'none';
        const noImageDiv = document.createElement('div');
        noImageDiv.className = 'no-image';
        noImageDiv.innerHTML = '<i class="bx bx-image"></i>';
        this.parentNode.appendChild(noImageDiv);
    });
});

// Animate statistics on page load
function animateStats() {
    const statNumbers = document.querySelectorAll('.stat-info h3');
    statNumbers.forEach(stat => {
        const finalNumber = parseInt(stat.textContent.replace(/[^0-9]/g, '')) || 0;
        let currentNumber = 0;
        const increment = finalNumber / 30;
        const timer = setInterval(() => {
            currentNumber += increment;
            if (currentNumber >= finalNumber) {
                currentNumber = finalNumber;
                clearInterval(timer);
            }
            if (stat.textContent.includes('Rp')) {
                stat.textContent = 'Rp ' + Math.floor(currentNumber).toLocaleString('id-ID');
            } else {
                stat.textContent = Math.floor(currentNumber);
            }
        }, 50);
    });
}

// Run animation when page loads
window.addEventListener('load', animateStats);

// Add smooth scroll to top functionality
window.addEventListener('scroll', function() {
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    if (scrollTop > 300) {
        if (!document.querySelector('.scroll-top')) {
            const scrollBtn = document.createElement('button');
            scrollBtn.className = 'scroll-top';
            scrollBtn.innerHTML = '<i class="bx bx-up-arrow-alt"></i>';
            scrollBtn.style.cssText = `
                position: fixed;
                bottom: 30px;
                right: 30px;
                width: 50px;
                height: 50px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                border: none;
                border-radius: 50%;
                cursor: pointer;
                z-index: 1000;
                box-shadow: 0 5px 20px rgba(102, 126, 234, 0.3);
                transition: all 0.3s ease;
            `;
            scrollBtn.addEventListener('click', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
            document.body.appendChild(scrollBtn);
        }
    } else {
        const scrollBtn = document.querySelector('.scroll-top');
        if (scrollBtn) {
            scrollBtn.remove();
        }
    }
});
</script>
@endsection