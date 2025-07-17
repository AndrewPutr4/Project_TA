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
        content: '✏️';
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
        content: '🔄';
        font-size: 1.8rem;
    }

    .form-header p {
        color: #6c757d;
        font-size: 1rem;
        margin: 0;
    }

    .menu-info-card {
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        border-left: 4px solid #2196f3;
        padding: 20px;
        border-radius: 0 12px 12px 0;
        margin-bottom: 30px;
        position: relative;
    }

    .menu-info-card::before {
        content: '📋';
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

    .menu-info-card h4 {
        color: #1976d2;
        font-size: 1.1rem;
        font-weight: 600;
        margin: 0 0 10px 0;
    }

    .menu-info-card p {
        margin: 0;
        color: #1565c0;
        font-size: 0.9rem;
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

    .current-image-container {
        margin-bottom: 20px;
        text-align: center;
        padding: 20px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 12px;
        border: 2px dashed #dee2e6;
        position: relative;
    }

    .current-image-container::before {
        content: '🖼️ Gambar Saat Ini';
        position: absolute;
        top: -12px;
        left: 20px;
        background: white;
        color: #6c757d;
        padding: 0 10px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .current-image {
        width: 120px !important;
        height: 120px !important;
        object-fit: cover;
        border-radius: 15px !important;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
        border: 3px solid white;
    }

    .current-image:hover {
        transform: scale(1.05);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
    }

    .image-info {
        margin-top: 15px;
        color: #6c757d;
        font-size: 0.9rem;
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

    .file-upload-note {
        background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        border-left: 4px solid #ffc107;
        padding: 15px;
        border-radius: 0 8px 8px 0;
        margin-top: 10px;
        position: relative;
    }

    .file-upload-note::before {
        content: '⚠️';
        position: absolute;
        top: 15px;
        left: -12px;
        background: #ffc107;
        color: white;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
    }

    .file-upload-note small {
        color: #856404 !important;
        font-weight: 500;
        font-size: 0.85rem !important;
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

    .btn-update {
        background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%) !important;
        color: white !important;
        box-shadow: 0 8px 25px rgba(23, 162, 184, 0.3);
    }

    .btn-update:hover {
        box-shadow: 0 15px 35px rgba(23, 162, 184, 0.4);
        color: white !important;
    }

    .btn-update::after {
        content: '🔄';
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

    /* Input validation styles */
    .form-control:valid {
        border-color: #28a745;
    }

    .form-control:invalid:not(:placeholder-shown) {
        border-color: #dc3545;
    }

    .validation-icon {
        position: relative;
    }

    .form-control:valid + .validation-feedback::after {
        content: '✅';
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
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

        .current-image {
            width: 100px !important;
            height: 100px !important;
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

    /* Image zoom modal */
    .image-modal {
        display: none;
        position: fixed;
        z-index: 10000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.8);
        backdrop-filter: blur(5px);
    }

    .image-modal-content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        max-width: 90%;
        max-height: 90%;
        border-radius: 15px;
        box-shadow: 0 25px 50px rgba(0,0,0,0.5);
    }

    .image-modal-close {
        position: absolute;
        top: -15px;
        right: -15px;
        background: #ff6b6b;
        color: white;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 5px 15px rgba(255, 107, 107, 0.4);
    }

    /* Modal Konfirmasi Kembali */
    #backConfirmModal {
        display:none;
        position:fixed;
        z-index:9999;
        left:0;
        top:0;
        width:100vw;
        height:100vh;
        background:rgba(0,0,0,0.35);
        align-items:center;
        justify-content:center;
    }

    #backConfirmModal .modal-content {
        background:#fff;
        border-radius:18px;
        max-width:350px;
        margin:auto;
        padding:2rem 1.5rem;
        box-shadow:0 8px 32px rgba(0,0,0,0.18);
        text-align:center;
        position:relative;
    }

    #backConfirmModal .modal-content .modal-icon {
        font-size:2.5rem;
        color:#ff9800;
        margin-bottom:0.7rem;
    }

    #backConfirmModal .modal-content h5 {
        font-weight:700;
        color:#333;
    }

    #backConfirmModal .modal-content p {
        color:#666;
        margin-bottom:1.5rem;
    }

    #backConfirmModal .modal-content .btn {
        min-width:90px;
    }

    /* Modal Konfirmasi Update */
    #updateConfirmModal {
        display:none;
        position:fixed;
        z-index:9999;
        left:0;
        top:0;
        width:100vw;
        height:100vh;
        background:rgba(0,0,0,0.35);
        align-items:center;
        justify-content:center;
    }

    #updateConfirmModal .modal-content {
        background:#fff;
        border-radius:18px;
        max-width:350px;
        margin:auto;
        padding:2rem 1.5rem;
        box-shadow:0 8px 32px rgba(0,0,0,0.18);
        text-align:center;
        position:relative;
    }

    #updateConfirmModal .modal-content .modal-icon {
        font-size:2.5rem;
        color:#17a2b8;
        margin-bottom:0.7rem;
    }

    #updateConfirmModal .modal-content h5 {
        font-weight:700;
        color:#333;
    }

    #updateConfirmModal .modal-content p {
        color:#666;
        margin-bottom:1.5rem;
    }

    #updateConfirmModal .modal-content .btn {
        min-width:90px;
    }
</style>

<main>
    <div class="head-title">
        <div class="left">
            <h1>Edit Menu</h1>
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
                    <a class="active" href="#">Edit</a>
                </li>
            </ul>
        </div>
    </div>
    
    <div class="table-data">
        <div class="order">
            <div class="form-container">
                <div class="form-header">
                    <h2>Form Edit Menu</h2>
                    <p>Perbarui informasi menu yang sudah ada</p>
                </div>

                {{-- Notifikasi sukses/error --}}
                @if(session('success'))
                    <div class="alert alert-success" id="notif-success">
                        <strong>Berhasil!</strong> {{ session('success') }}
                        <button type="button" class="btn btn-sm btn-outline-primary ms-3" onclick="window.location.reload()">Edit Lagi</button>
                    </div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger" id="notif-error">
                        <strong>Error:</strong> {{ $errors->first() }}
                        <button type="button" class="btn btn-sm btn-outline-primary ms-3" onclick="window.location.reload()">Kembali Mengedit</button>
                    </div>
                @endif

                <div class="menu-info-card">
                    <h4>Menu yang sedang diedit:</h4>
                    <p><strong>{{ $menu->name }}</strong> - Terakhir diupdate: {{ $menu->updated_at->format('d M Y, H:i') }}</p>
                </div>

                <form method="POST" action="{{ route('admin.menus.update', $menu) }}" enctype="multipart/form-data" id="editMenuForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group" style="--delay: 1">
                        <label for="name">Nama Menu</label>
                        <div class="validation-icon">
                            <input type="text" 
                                   id="name"
                                   name="name" 
                                   class="form-control" 
                                   value="{{ $menu->name }}" 
                                   required 
                                   placeholder="Masukkan nama menu"
                                   autocomplete="off">
                        </div>
                    </div>

                    <div class="form-group" style="--delay: 1.5">
                        <label for="description">Deskripsi Menu</label>
                        <textarea id="description"
                                  name="description"
                                  class="form-control"
                                  rows="3"
                                  placeholder="Deskripsi singkat menu">{{ $menu->description }}</textarea>
                    </div>

                    <div class="form-group" style="--delay: 2">
                        <label for="category">Kategori Menu</label>
                        <select id="category" name="category_id" class="form-control" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $menu->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group" style="--delay: 3">
                        <label for="price">Harga Menu</label>
                        <div class="price-input-container validation-icon">
                            <input type="number"
                                   id="price"
                                   name="price"
                                   class="form-control"
                                   value="{{ old('price', $menu->price) }}"
                                   required
                                   placeholder="15000"
                                   min="0"
                                   step="1"
                                   autocomplete="off">
                        </div>
                        <small style="color: #6c757d; font-size: 0.85rem; margin-top: 5px; display: block;">
                            💡 Masukkan harga dalam rupiah tanpa titik/koma (contoh: 25000 untuk Rp 25.000)
                        </small>
                    </div>

                    <div class="form-group" style="--delay: 4">
                        <label for="image">Gambar Menu</label>
                        
                        @if($menu->image)
                            <div class="current-image-container">
                                <img src="{{ asset('storage/'.$menu->image) }}" 
                                     alt="Gambar Menu {{ $menu->name }}" 
                                     class="current-image"
                                     onclick="openImageModal(this.src)">
                                <div class="image-info">
                                    <strong>📁 Gambar saat ini</strong><br>
                                    <small>Klik gambar untuk memperbesar</small>
                                </div>
                            </div>
                        @endif
                        
                        <input type="file" 
                               id="image"
                               name="image" 
                               class="form-control" 
                               accept="image/*">
                        
                        <div class="file-upload-note">
                            <small>Biarkan kosong jika tidak ingin mengubah gambar. Format yang didukung: JPG, PNG, GIF (Maksimal 2MB)</small>
                        </div>
                    </div>

                    <div class="form-group" style="--delay: 4.5">
                        <label for="is_available">
                            <input type="checkbox" id="is_available" name="is_available" value="1" {{ $menu->is_available ? 'checked' : '' }}>
                            Tersedia untuk dipesan
                        </label>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-download btn-update" id="btnUpdateMenu">
                            <i class='bx bx-save'></i>
                            <span id="btnUpdateText">Update Menu</span>
                            <span id="btnUpdateLoading" style="display:none;"><i class="bx bx-loader-alt bx-spin"></i> Mengupdate...</span>
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

<!-- Image Modal -->
<div id="imageModal" class="image-modal" onclick="closeImageModal()">
    <img class="image-modal-content" id="modalImage">
    <button class="image-modal-close" onclick="closeImageModal()">&times;</button>
</div>

<!-- Modal Konfirmasi Kembali -->
<div id="backConfirmModal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.35); align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:18px; max-width:350px; margin:auto; padding:2rem 1.5rem; box-shadow:0 8px 32px rgba(0,0,0,0.18); text-align:center; position:relative;">
        <div style="font-size:2.5rem; color:#ff9800; margin-bottom:0.7rem;">&#9888;</div>
        <h5 style="font-weight:700; color:#333;">Perubahan Belum Disimpan</h5>
        <p style="color:#666; margin-bottom:1.5rem;">Anda memiliki perubahan pada form ini.<br>Yakin ingin kembali tanpa menyimpan?</p>
        <div style="display:flex; gap:1rem; justify-content:center;">
            <button id="backConfirmYes" class="btn btn-danger" style="min-width:90px;">Ya, Kembali</button>
            <button id="backConfirmNo" class="btn btn-primary" style="min-width:90px;">Batal</button>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Update -->
<div id="updateConfirmModal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.35); align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:18px; max-width:350px; margin:auto; padding:2rem 1.5rem; box-shadow:0 8px 32px rgba(0,0,0,0.18); text-align:center; position:relative;">
        <div style="font-size:2.5rem; color:#17a2b8; margin-bottom:0.7rem;">&#9888;</div>
        <h5 style="font-weight:700; color:#333;">Konfirmasi Simpan Perubahan</h5>
        <p style="color:#666; margin-bottom:1.5rem;">Anda yakin ingin menyimpan perubahan pada menu ini?</p>
        <div style="display:flex; gap:1rem; justify-content:center;">
            <button id="updateConfirmYes" class="btn btn-success" style="min-width:90px;">Ya, Simpan</button>
            <button id="updateConfirmNo" class="btn btn-primary" style="min-width:90px;">Batal</button>
        </div>
    </div>
</div>

<script>
// Image modal functions
function openImageModal(src) {
    document.getElementById('imageModal').style.display = 'block';
    document.getElementById('modalImage').src = src;
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    document.getElementById('imageModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});

// Tombol loading UX saat submit
document.getElementById('editMenuForm').addEventListener('submit', function(e) {
    const btn = document.getElementById('btnUpdateMenu');
    document.getElementById('btnUpdateText').style.display = 'none';
    document.getElementById('btnUpdateLoading').style.display = '';
    btn.disabled = true;
});

// Jika ada notifikasi sukses/error, scroll ke atas otomatis
window.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('notif-success') || document.getElementById('notif-error')) {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
});

// Add loading animation to form submission
document.querySelector('form').addEventListener('submit', function(e) {
    const submitBtn = document.querySelector('.btn-update');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Mengupdate...';
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

// Price formatting (hanya preview, tidak mengubah value input)
document.getElementById('price').addEventListener('input', function(e) {
    let value = e.target.value.replace(/[^\d]/g, '');
    // Jangan ubah value input, hanya update preview
    if (!document.querySelector('.price-preview')) {
        const preview = document.createElement('small');
        preview.className = 'price-preview';
        preview.style.cssText = 'color: #28a745; font-weight: 600; margin-top: 5px; display: block;';
        e.target.parentElement.appendChild(preview);
    }
    let formatted = value ? value.replace(/\B(?=(\d{3})+(?!\d))/g, '.') : '0';
    document.querySelector('.price-preview').textContent = `Preview: Rp ${formatted}`;
});

// Inisialisasi preview saat load jika ada value
window.addEventListener('load', () => {
    document.getElementById('name').focus();
    
    // Initialize price preview if there's existing value
    const priceInput = document.getElementById('price');
    if (priceInput.value) {
        // Trigger preview tanpa mengubah value
        let value = priceInput.value.replace(/[^\d]/g, '');
        let formatted = value ? value.replace(/\B(?=(\d{3})+(?!\d))/g, '.') : '0';
        if (!document.querySelector('.price-preview')) {
            const preview = document.createElement('small');
            preview.className = 'price-preview';
            preview.style.cssText = 'color: #28a745; font-weight: 600; margin-top: 5px; display: block;';
            priceInput.parentElement.appendChild(preview);
        }
        document.querySelector('.price-preview').textContent = `Preview: Rp ${formatted}`;
    }
});

// File upload preview
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        // Remove existing preview
        const existingPreview = document.querySelector('.new-image-preview');
        if (existingPreview) existingPreview.remove();
        
        // Create preview
        const preview = document.createElement('div');
        preview.className = 'new-image-preview';
        preview.style.cssText = `
            margin-top: 15px;
            padding: 20px;
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border-radius: 12px;
            text-align: center;
            border: 2px solid #28a745;
            position: relative;
        `;
        
        preview.innerHTML = '<div style="color: #155724; font-weight: 600; margin-bottom: 15px;">🆕 Gambar Baru (Preview)</div>';
        
        const img = document.createElement('img');
        img.style.cssText = `
            max-width: 150px;
            max-height: 120px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            border: 3px solid white;
        `;
        
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
        
        const fileName = document.createElement('p');
        fileName.style.cssText = 'margin: 15px 0 0 0; color: #155724; font-size: 0.9rem; font-weight: 500;';
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

// Auto-focus first input
window.addEventListener('load', () => {
    document.getElementById('name').focus();
    
    // Initialize price preview if there's existing value
    const priceInput = document.getElementById('price');
    if (priceInput.value) {
        // Trigger preview tanpa mengubah value
        let value = priceInput.value.replace(/[^\d]/g, '');
        let formatted = value ? value.replace(/\B(?=(\d{3})+(?!\d))/g, '.') : '0';
        if (!document.querySelector('.price-preview')) {
            const preview = document.createElement('small');
            preview.className = 'price-preview';
            preview.style.cssText = 'color: #28a745; font-weight: 600; margin-top: 5px; display: block;';
            priceInput.parentElement.appendChild(preview);
        }
        document.querySelector('.price-preview').textContent = `Preview: Rp ${formatted}`;
    }
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

let hasChanges = false;

// Deteksi perubahan pada form
document.querySelectorAll('.form-control, input[type="checkbox"]').forEach(input => {
    input.addEventListener('input', function() {
        hasChanges = true;
        // Add visual indicator for changes
        if (!document.querySelector('.changes-indicator')) {
            const indicator = document.createElement('div');
            indicator.className = 'changes-indicator';
            indicator.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: linear-gradient(135deg, #ffc107 0%, #ff8f00 100%);
                color: white;
                padding: 10px 20px;
                border-radius: 25px;
                font-weight: 600;
                font-size: 0.9rem;
                box-shadow: 0 5px 20px rgba(255, 193, 7, 0.3);
                z-index: 1000;
                animation: slideInRight 0.3s ease;
            `;
            indicator.innerHTML = '⚠️ Ada perubahan yang belum disimpan';
            document.body.appendChild(indicator);
        }
    });
});

// Tombol kembali dengan validasi perubahan
document.querySelector('.btn-back').addEventListener('click', function(e) {
    if (hasChanges) {
        e.preventDefault();
        document.getElementById('backConfirmModal').style.display = 'flex';
    }
    // Jika tidak ada perubahan, langsung kembali
});

// Modal konfirmasi kembali
document.getElementById('backConfirmYes').onclick = function() {
    hasChanges = false;
    window.location.href = "{{ route('admin.menus.index') }}";
};
document.getElementById('backConfirmNo').onclick = function() {
    document.getElementById('backConfirmModal').style.display = 'none';
};

// Modal konfirmasi update
const editMenuForm = document.getElementById('editMenuForm');
const btnUpdateMenu = document.getElementById('btnUpdateMenu');
let allowSubmit = false;

btnUpdateMenu.addEventListener('click', function(e) {
    if (hasChanges && !allowSubmit) {
        e.preventDefault();
        document.getElementById('updateConfirmModal').style.display = 'flex';
    }
    // Jika tidak ada perubahan, submit langsung
});

// Modal update: Ya, Simpan
document.getElementById('updateConfirmYes').onclick = function() {
    allowSubmit = true;
    document.getElementById('updateConfirmModal').style.display = 'none';
    // Hapus event beforeunload agar browser tidak menampilkan native dialog
    window.onbeforeunload = null;
    hasChanges = false;
    editMenuForm.submit();
};
// Modal update: Batal
document.getElementById('updateConfirmNo').onclick = function() {
    document.getElementById('updateConfirmModal').style.display = 'none';
};

// Hanya tampilkan warning native browser jika user benar-benar meninggalkan halaman (bukan submit/kembali dengan konfirmasi)
window.addEventListener('beforeunload', function(e) {
    if (hasChanges) {
        e.preventDefault();
        e.returnValue = '';
    }
});
editMenuForm.addEventListener('submit', function() {
    hasChanges = false;
    window.onbeforeunload = null;
});
</script>
@endsection