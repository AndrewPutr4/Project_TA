@extends('layouts.admin')

@section('content')
<style>
    .kasir-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
        min-height: calc(100vh - 70px);
    }

    .page-header {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        padding: 30px;
        border-radius: 15px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(245, 158, 11, 0.3);
    }

    .page-header h2 {
        margin: 0;
        font-size: 2.5rem;
        font-weight: 300;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .page-header .subtitle {
        opacity: 0.9;
        margin-top: 8px;
        font-size: 1.1rem;
    }

    .card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 25px rgba(245, 158, 11, 0.08);
        margin-bottom: 30px;
        overflow: hidden;
        border: 2px solid #fed7aa;
    }

    .card-header {
        background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
        padding: 25px 30px;
        border-bottom: 1px solid #fed7aa;
    }

    .card-header h4 {
        margin: 0;
        color: #92400e;
        font-size: 1.4rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-body {
        padding: 30px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr auto;
        gap: 20px;
        align-items: end;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        font-weight: 600;
        color: #92400e;
        margin-bottom: 8px;
        font-size: 0.95rem;
    }

    .form-control {
        padding: 12px 16px;
        border: 2px solid #fed7aa;
        border-radius: 10px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: #fffbeb;
    }

    .form-control:focus {
        outline: none;
        border-color: #f59e0b;
        background: white;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
    }

    .btn {
        padding: 12px 24px;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.95rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        border: 2px solid transparent;
    }

    .btn-primary {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        border-color: #f59e0b;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(245, 158, 11, 0.3);
        background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
    }

    .btn-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border-color: #10b981;
    }

    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
    }

    .btn-warning {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
        border-color: #3b82f6;
    }

    .btn-warning:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
        background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
    }

    .btn-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        border-color: #ef4444;
    }

    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(239, 68, 68, 0.3);
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    }

    .table-container {
        overflow-x: auto;
        border-radius: 10px;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        background: white;
    }

    .table thead {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }

    .table th {
        padding: 18px 20px;
        text-align: left;
        font-weight: 600;
        font-size: 0.95rem;
        letter-spacing: 0.5px;
    }

    .table td {
        padding: 16px 20px;
        border-bottom: 1px solid #fed7aa;
        vertical-align: middle;
    }

    .table tbody tr {
        transition: all 0.3s ease;
    }

    .table tbody tr:hover {
        background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
        transform: scale(1.01);
    }

    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .btn-sm {
        padding: 8px 16px;
        font-size: 0.85rem;
    }

    .alert {
        padding: 15px 20px;
        border-radius: 10px;
        margin-top: 20px;
        font-weight: 500;
        border: 2px solid;
    }

    .alert-success {
        background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
        color: #166534;
        border-color: #bbf7d0;
    }

    .alert-danger {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
        border-color: #fecaca;
    }

    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0,0,0,0.5);
        align-items: center;
        justify-content: center;
        z-index: 1000;
        backdrop-filter: blur(5px);
    }

    .modal-content {
        background: white;
        padding: 0;
        border-radius: 15px;
        min-width: 500px;
        max-width: 90vw;
        box-shadow: 0 20px 60px rgba(245, 158, 11, 0.3);
        animation: modalSlideIn 0.3s ease;
        border: 2px solid #fed7aa;
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-50px) scale(0.9);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .modal-header {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        padding: 25px 30px;
        border-radius: 15px 15px 0 0;
    }

    .modal-header h4 {
        margin: 0;
        font-size: 1.3rem;
        font-weight: 600;
    }

    .modal-body {
        padding: 30px;
    }

    .modal-form-group {
        margin-bottom: 20px;
    }

    .modal-actions {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        margin-top: 25px;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #92400e;
    }

    .empty-state-icon {
        font-size: 4rem;
        margin-bottom: 20px;
        opacity: 0.3;
    }

    .icon {
        width: 20px;
        height: 20px;
        display: inline-block;
    }

    @media (max-width: 768px) {
        .kasir-container {
            padding: 15px;
        }
        
        .form-row {
            grid-template-columns: 1fr;
            gap: 15px;
        }
        
        .page-header h2 {
            font-size: 2rem;
        }
        
        .modal-content {
            min-width: auto;
            margin: 20px;
        }
        
        .action-buttons {
            flex-direction: column;
        }
    }
</style>

<div class="kasir-container">
    <!-- Page Header -->
    <div class="page-header">
        <h2>
            <svg class="icon" fill="currentColor" viewBox="0 0 20 20">
                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
            </svg>
            Manajemen Kasir
        </h2>
        <div class="subtitle">Kelola data kasir dan akses sistem</div>
    </div>

    <!-- Add Cashier Form -->
    <div class="card">
        <div class="card-header">
            <h4>
                <svg class="icon" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                </svg>
                Tambah Kasir Baru
            </h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.kasir.store') }}">
                @csrf
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Nama Lengkap</label>
                        <input type="text" id="name" name="name" class="form-control" required placeholder="Masukkan nama lengkap">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control" required placeholder="contoh@email.com">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required placeholder="Minimal 8 karakter">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success">
                            <svg class="icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                            </svg>
                            Tambah Kasir
                        </button>
                    </div>
                </div>
            </form>

            @if($errors->any())
                <div class="alert alert-danger">
                    <strong>Error:</strong> {{ $errors->first() }}
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    <strong>Berhasil:</strong> {{ session('success') }}
                </div>
            @endif
        </div>
    </div>

    <!-- Cashier List -->
    <div class="card">
        <div class="card-header">
            <h4>
                <svg class="icon" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Daftar Kasir ({{ $kasirs->count() }} orang)
            </h4>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 80px;">No</th>
                            <th>Nama Lengkap</th>
                            <th>Email</th>
                            <th style="width: 200px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kasirs as $kasir)
                        <tr>
                            <td><strong>{{ $loop->iteration }}</strong></td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                                        {{ strtoupper(substr($kasir->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight: 600; color: #92400e;">{{ $kasir->name }}</div>
                                        <div style="font-size: 0.85rem; color: #92400e; opacity: 0.7;">Kasir</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span style="color: #92400e;">{{ $kasir->email }}</span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button onclick="showEdit('{{ $kasir->id }}', '{{ addslashes($kasir->name) }}', '{{ addslashes($kasir->email) }}')" class="btn btn-warning btn-sm">
                                        <svg class="icon" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                        </svg>
                                        Edit
                                    </button>
                                    <form method="POST" action="{{ route('admin.kasir.destroy', $kasir->id) }}" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus kasir {{ $kasir->name }}?')" class="btn btn-danger btn-sm">
                                            <svg class="icon" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4">
                                <div class="empty-state">
                                    <div class="empty-state-icon">👥</div>
                                    <h5>Belum ada kasir</h5>
                                    <p>Tambahkan kasir pertama menggunakan form di atas</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h4>
                <svg class="icon" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                </svg>
                Edit Data Kasir
            </h4>
        </div>
        <div class="modal-body">
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-form-group">
                    <label for="editName">Nama Lengkap</label>
                    <input type="text" id="editName" name="name" class="form-control" required>
                </div>
                <div class="modal-form-group">
                    <label for="editEmail">Email</label>
                    <input type="email" id="editEmail" name="email" class="form-control" required>
                </div>
                <div class="modal-form-group">
                    <label for="editPassword">Password Baru</label>
                    <input type="password" id="editPassword" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah password">
                    <small style="color: #92400e; font-size: 0.85rem; margin-top: 5px; display: block;">Kosongkan jika tidak ingin mengubah password</small>
                </div>
                <div class="modal-actions">
                    <button type="button" onclick="hideEdit()" class="btn btn-danger">
                        <svg class="icon" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <svg class="icon" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showEdit(id, name, email) {
    document.getElementById('editModal').style.display = 'flex';
    document.getElementById('editName').value = name;
    document.getElementById('editEmail').value = email;
    document.getElementById('editPassword').value = '';
    document.getElementById('editForm').action = '/admin/kasir/' + id;
}

function hideEdit() {
    document.getElementById('editModal').style.display = 'none';
}

// Close modal when clicking outside
document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideEdit();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        hideEdit();
    }
});

console.log('✅ Kasir register page loaded with yellow theme');
</script>
@endsection
