@extends('layouts.admin')

@section('content')
<style>
    main {
        padding: 32px 24px;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: calc(100vh - 70px);
    }

    .head-title {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 32px;
        flex-wrap: wrap;
        gap: 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 40px;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(102, 126, 234, 0.3);
        position: relative;
        overflow: hidden;
    }

    .head-title::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: rotate 20s linear infinite;
    }

    @keyframes rotate {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .head-title .left {
        position: relative;
        z-index: 2;
    }

    .head-title .left h1 {
        font-size: 2.8rem;
        font-weight: 800;
        margin: 0 0 15px 0;
        display: flex;
        align-items: center;
        gap: 15px;
        color: white;
    }

    .head-title .left h1::before {
        content: '🍽️';
        font-size: 2.5rem;
    }

    .breadcrumb {
        display: flex;
        align-items: center;
        list-style: none;
        gap: 8px;
        margin: 0;
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

    .table-data {
        display: grid;
        grid-template-columns: 1fr;
        gap: 24px;
    }

    .order {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .order:hover {
        transform: translateY(-5px);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    }

    .form-container {
        padding: 40px;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    }

    .form-header {
        text-align: center;
        margin-bottom: 40px;
        padding-bottom: 20px;
        border-bottom: 2px solid #e9ecef;
        position: relative;
    }

    .form-header::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 2px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .form-header h2 {
        font-size: 2rem;
        font-weight: 700;
        color: #495057;
        margin: 0 0 10px 0;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
    }

    .form-header h2::before {
        content: '📝';
        font-size: 1.8rem;
    }

    .form-header p {
        color: #6c757d;
        font-size: 1rem;
        margin: 0;
    }

    .form-group {
        margin-bottom: 30px;
        position: relative;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        color: #495057;
        margin-bottom: 8px;
        font-size: 1rem;
        position: relative;
        padding-left: 30px;
    }

    .form-group label::before {
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        font-size: 1.2rem;
    }

    .form-group label[for="name"]::before {
        content: '🏷️';
    }

    .form-group label[for="category"]::before {
        content: '📂';
    }

    .form-group label[for="price"]::before {
        content: '💰';
    }

    .form-group label[for="image"]::before {
        content: '📸';
    }

    .form-control {
        width: 100% !important;
        padding: 15px 20px !important;
        border: 2px solid #e9ecef;
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .form-control:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        transform: translateY(-2px);
    }

    .form-control:hover {
        border-color: #667eea;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    select.form-control {
        cursor: pointer;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 12px center;
        background-repeat: no-repeat;
        background-size: 16px;
        padding-right: 50px !important;
        appearance: none;
    }

    input[type="file"].form-control {
        padding: 12px 15px !important;
        cursor: pointer;
        position: relative;
    }

    input[type="file"].form-control::file-selector-button {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 8px;
        margin-right: 15px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    input[type="file"].form-control::file-selector-button:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .price-input-container {
        position: relative;
    }

    .price-input-container::before {
        content: 'Rp';
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
        font-weight: 600;
        z-index: 2;
    }

    .price-input-container .form-control {
        padding-left: 50px !important;
    }

    .form-actions {
        display: flex;
        gap: 15px;
        justify-content: center;
        margin-top: 40px;
        padding-top: 30px;
        border-top: 1px solid #e9ecef;
    }

    .btn-download {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 15px 30px;
        text-decoration: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        border: none;
        cursor: pointer;
        min-width: 140px;
        justify-content: center;
    }

    .btn-download::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }

    .btn-download:hover::before {
        left: 100%;
    }

    .btn-download:hover {
        transform: translateY(-3px);
        text-decoration: none;
    }

    .btn-save {
        background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%) !important;
        color: white !important;
        box-shadow: 0 8px 25px rgba(86, 171, 47, 0.3);
    }

    .btn-save:hover {
        box-shadow: 0 15px 35px rgba(86, 171, 47, 0.4);
        color: white !important;
    }

    .btn-save::after {
        content: '💾';
        margin-left: 8px;
    }

    .btn-back {
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%) !important;
        color: white !important;
        box-shadow: 0 8px 25px rgba(255, 107, 107, 0.3);
    }

    .btn-back:hover {
        box-shadow: 0 15px 35px rgba(255, 107, 107, 0.4);
        color: white !important;
    }

    .btn-back::after {
        content: '↩️';
        margin-left: 8px;
    }

    .form-tips {
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        border-left: 4px solid #2196f3;
        padding: 20px;
        border-radius: 0 12px 12px 0;
        margin-bottom: 30px;
        position: relative;
    }

    .form-tips::before {
        content: '💡';
        position: absolute;
        top: 20px;
        left: -12px;
        background: #2196f3;
        color: white;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
    }

    .form-tips h4 {
        color: #1976d2;
        font-size: 1.1rem;
        font-weight: 600;
        margin: 0 0 10px 0;
    }

    .form-tips ul {
        margin: 0;
        padding-left: 20px;
        color: #1565c0;
    }

    .form-tips li {
        margin-bottom: 5px;
        font-size: 0.9rem;
    }

    /* Input validation styles */
    .form-control:valid {
        border-color: #28a745;
    }

    .form-control:invalid:not(:placeholder-shown) {
        border-color: #dc3545;
    }

    .form-control:valid + .validation-icon::after {
        content: '✅';
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
    }

    .validation-icon {
        position: relative;
    }

    /* Responsive design */
    @media (max-width: 768px) {
        main {
            padding: 20px 15px;
        }

        .head-title {
            padding: 25px 20px;
        }

        .head-title .left h1 {
            font-size: 2rem;
        }

        .form-container {
            padding: 25px 20px;
        }

        .form-header h2 {
            font-size: 1.5rem;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn-download {
            width: 100%;
        }

        .form-group label {
            font-size: 0.9rem;
        }

        .form-control {
            padding: 12px 15px !important;
            font-size: 0.9rem;
        }
    }

    /* Animation for form appearance */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .order {
        animation: fadeInUp 0.6s ease forwards;
    }

    .form-group {
        animation: fadeInUp 0.6s ease forwards;
        animation-delay: calc(var(--delay) * 0.1s);
    }
</style>

<main>
    <div class="head-title">
        <div class="left">
            <h1>Tambah Menu</h1>
            <ul class="breadcrumb">
                <li>
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                </li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li>
                    <a href="{{ route('admin.menus.index') }}">Menu</a>
                </li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li>
                    <a class="active" href="#">Tambah</a>
                </li>
            </ul>
        </div>
    </div>
    
    <div class="table-data">
        <div class="order">
            <div class="form-container">
                <div class="form-header">
                    <h2>Form Tambah Menu Baru</h2>
                    <p>Lengkapi informasi menu yang akan ditambahkan</p>
                </div>

                <div class="form-tips">
                    <h4>Tips Pengisian Form:</h4>
                    <ul>
                        <li>Pastikan nama menu unik dan mudah diingat</li>
                        <li>Pilih kategori yang sesuai dengan jenis menu</li>
                        <li>Format harga: gunakan angka saja (contoh: 25000)</li>
                        <li>Upload gambar dengan format JPG/PNG, maksimal 2MB</li>
                    </ul>
                </div>

                <form method="POST" action="{{ route('admin.menus.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="form-group" style="--delay: 1">
                        <label for="name">Nama Menu</label>
                        <div class="validation-icon">
                            <input type="text" 
                                   id="name"
                                   name="name" 
                                   class="form-control" 
                                   required 
                                   placeholder="Masukkan nama menu (contoh: Nasi Goreng Spesial)"
                                   autocomplete="off">
                        </div>
                    </div>

                    <div class="form-group" style="--delay: 2">
                        <label for="category">Kategori Menu</label>
                        <select id="category" name="category_id" class="form-control" required>
                            <option value="" disabled selected>-- Pilih Kategori Menu --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group" style="--delay: 3">
                        <label for="price">Harga Menu</label>
                        <div class="price-input-container validation-icon">
                            <input type="text" 
                                   id="price"
                                   name="price" 
                                   class="form-control" 
                                   required 
                                   placeholder="25000" 
                                   pattern="[0-9,\.]*" 
                                   inputmode="decimal"
                                   autocomplete="off">
                        </div>
                        <small style="color: #6c757d; font-size: 0.85rem; margin-top: 5px; display: block;">
                            💡 Masukkan harga dalam rupiah (contoh: 25000 untuk Rp 25.000)
                        </small>
                    </div>

                    <div class="form-group" style="--delay: 4">
                        <label for="image">Gambar Menu</label>
                        <input type="file" 
                               id="image"
                               name="image" 
                               class="form-control" 
                               accept="image/*">
                        <small style="color: #6c757d; font-size: 0.85rem; margin-top: 5px; display: block;">
                            📸 Format yang didukung: JPG, PNG, GIF (Maksimal 2MB)
                        </small>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-download btn-save">
                            <i class='bx bx-save'></i>
                            Simpan Menu
                        </button>
                        <a href="{{ route('admin.menus.index') }}" class="btn-download btn-back">
                            <i class='bx bx-arrow-back'></i>
                            Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
// Add loading animation to form submission
document.querySelector('form').addEventListener('submit', function(e) {
    const submitBtn = document.querySelector('.btn-save');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Menyimpan...';
    submitBtn.style.pointerEvents = 'none';
    
    // If validation fails, restore button
    setTimeout(() => {
        if (!this.checkValidity()) {
            submitBtn.innerHTML = originalText;
            submitBtn.style.pointerEvents = 'auto';
        }
    }, 100);
});

// Enhanced form interactions
document.querySelectorAll('.form-control').forEach(input => {
    input.addEventListener('focus', function() {
        this.parentElement.style.transform = 'scale(1.02)';
        this.parentElement.style.transition = 'transform 0.3s ease';
    });
    
    input.addEventListener('blur', function() {
        this.parentElement.style.transform = 'scale(1)';
    });
});

// Price formatting
document.getElementById('price').addEventListener('input', function(e) {
    let value = e.target.value.replace(/[^\d]/g, '');
    if (value) {
        // Add thousand separators for display (optional)
        let formatted = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        // Keep original value for form submission
        e.target.value = value;
        
        // Show formatted preview
        if (!document.querySelector('.price-preview')) {
            const preview = document.createElement('small');
            preview.className = 'price-preview';
            preview.style.cssText = 'color: #28a745; font-weight: 600; margin-top: 5px; display: block;';
            e.target.parentElement.appendChild(preview);
        }
        document.querySelector('.price-preview').textContent = `Preview: Rp ${formatted}`;
    } else {
        const preview = document.querySelector('.price-preview');
        if (preview) preview.remove();
    }
});

// File upload preview
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        // Remove existing preview
        const existingPreview = document.querySelector('.image-preview');
        if (existingPreview) existingPreview.remove();
        
        // Create preview
        const preview = document.createElement('div');
        preview.className = 'image-preview';
        preview.style.cssText = `
            margin-top: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            text-align: center;
            border: 2px dashed #dee2e6;
        `;
        
        const img = document.createElement('img');
        img.style.cssText = `
            max-width: 200px;
            max-height: 150px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        `;
        
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
        
        const fileName = document.createElement('p');
        fileName.style.cssText = 'margin: 10px 0 0 0; color: #6c757d; font-size: 0.9rem;';
        fileName.textContent = `📁 ${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
        
        preview.appendChild(img);
        preview.appendChild(fileName);
        e.target.parentElement.appendChild(preview);
    }
});

// Form validation feedback
document.querySelectorAll('.form-control[required]').forEach(input => {
    input.addEventListener('blur', function() {
        if (this.value.trim() === '') {
            this.style.borderColor = '#dc3545';
            this.style.boxShadow = '0 0 0 3px rgba(220, 53, 69, 0.1)';
        } else {
            this.style.borderColor = '#28a745';
            this.style.boxShadow = '0 0 0 3px rgba(40, 167, 69, 0.1)';
        }
    });
});

// Smooth scroll to form errors
function scrollToError() {
    const firstError = document.querySelector('.form-control:invalid');
    if (firstError) {
        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        firstError.focus();
    }
}

// Auto-focus first input
window.addEventListener('load', () => {
    document.getElementById('name').focus();
});

// Add button press effects
document.querySelectorAll('.btn-download').forEach(btn => {
    btn.addEventListener('click', function() {
        this.style.transform = 'scale(0.95)';
        setTimeout(() => {
            this.style.transform = 'scale(1)';
        }, 150);
    });
});

// Category selection enhancement
document.getElementById('category').addEventListener('change', function() {
    if (this.value) {
        this.style.borderColor = '#28a745';
        this.style.boxShadow = '0 0 0 3px rgba(40, 167, 69, 0.1)';
    }
});
</script>
@endsection