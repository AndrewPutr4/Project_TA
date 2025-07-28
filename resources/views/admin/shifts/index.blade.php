@extends('layouts.admin')

@section('content')
<style>
    main {
        padding: 20px;
        background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
        min-height: calc(100vh - 70px);
    }

    .page-header {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        padding: 40px;
        border-radius: 20px;
        margin-bottom: 25px;
        box-shadow: 0 8px 25px rgba(245, 158, 11, 0.3);
        position: relative;
    }

    .header-content {
        width: 100%;
    }

    .header-icon {
        font-size: 2.5rem;
        margin-bottom: 15px;
        display: block;
    }

    .page-header h1 {
        font-size: 2.5rem;
        font-weight: 800;
        margin: 0 0 10px 0;
        color: white;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .page-subtitle {
        font-size: 1.1rem;
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 20px;
        font-weight: 400;
    }

    .breadcrumb {
        background: transparent !important;
        padding: 0 !important;
        margin: 0 !important;
        list-style: none;
        display: flex;
        align-items: center;
        gap: 8px;
        border: none !important;
        box-shadow: none !important;
    }

    .breadcrumb li {
        background: transparent !important;
        border: none !important;
    }

    .breadcrumb li a {
        color: rgba(255, 255, 255, 0.8) !important;
        text-decoration: none;
        font-weight: 500;
        padding: 6px 12px;
        border-radius: 8px;
        transition: all 0.3s ease;
        border: none !important;
        font-size: 0.95rem;
        background: transparent !important;
    }

    .breadcrumb li a:hover,
    .breadcrumb li a.active {
        color: white !important;
        background: rgba(255, 255, 255, 0.2) !important;
    }

    .breadcrumb li i {
        color: rgba(255, 255, 255, 0.6);
        font-size: 0.9rem;
        margin: 0 5px;
    }

    .content-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(245, 158, 11, 0.08);
        overflow: hidden;
        border: 2px solid #fed7aa;
    }

    .card-header {
        background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
        padding: 20px 25px;
        border-bottom: 1px solid #fed7aa;
    }

    .card-header h3 {
        margin: 0;
        color: #92400e;
        font-size: 1.3rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .table {
        width: 100%;
        margin: 0;
        border-collapse: collapse;
        border: none;
    }

    .table thead th {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        padding: 15px 20px;
        text-align: left;
        font-weight: 600;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
    }

    .table tbody td {
        padding: 15px 20px;
        border: none;
        border-bottom: 1px solid #fed7aa;
        vertical-align: middle;
        font-size: 0.95rem;
    }

    .table tbody tr {
        transition: background-color 0.2s ease;
        border: none;
    }

    .table tbody tr:hover {
        background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
    }

    .table tbody tr:nth-child(even) {
        background-color: #fffbeb;
    }

    .table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Kasir name styling */
    .kasir-name {
        font-weight: 600;
        color: #92400e;
    }

    .kasir-name::before {
        content: '👤';
        margin-right: 8px;
    }

    /* Time columns styling */
    .time-cell {
        font-family: 'Courier New', monospace;
        font-weight: 600;
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        color: #1e40af;
        border-radius: 6px;
        text-align: center;
        padding: 8px 12px;
        border: none;
        display: inline-block;
        border: 2px solid #bfdbfe;
    }

    .time-start::before {
        content: '🕐';
        margin-right: 5px;
    }

    .time-end::before {
        content: '🕕';
        margin-right: 5px;
    }

    .date-cell {
        font-weight: 600;
        color: #92400e;
        background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
        padding: 6px 12px;
        border-radius: 6px;
        border: 2px solid #fed7aa;
        display: inline-block;
    }

    /* Action buttons */
    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 8px 15px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.85rem;
        text-decoration: none;
        transition: all 0.3s ease;
        margin-right: 8px;
        border: none;
        cursor: pointer;
        border: 2px solid transparent;
    }

    .btn-edit {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
        border-color: #3b82f6;
    }

    .btn-edit:hover {
        background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
        color: white;
        text-decoration: none;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .btn-delete {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        border-color: #ef4444;
    }

    .btn-delete:hover {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #92400e;
        font-size: 1.1rem;
        border: none;
    }

    .empty-state::before {
        content: '📋';
        display: block;
        font-size: 3rem;
        margin-bottom: 15px;
        opacity: 0.5;
    }

    /* Remove any white backgrounds */
    nav, .breadcrumb, .breadcrumb li, .breadcrumb li a {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
    }

    /* Delete Modal Styles */
    .delete-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
        z-index: 1000;
        backdrop-filter: blur(5px);
    }

    .modal-content {
        background: white;
        border-radius: 20px;
        width: 90%;
        max-width: 500px;
        box-shadow: 0 25px 50px rgba(245, 158, 11, 0.3);
        border: 2px solid #fed7aa;
        overflow: hidden;
        animation: modalSlideIn 0.3s ease;
    }

    .modal-header {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        padding: 1.5rem;
        border-bottom: 2px solid #fecaca;
        text-align: center;
    }

    .warning-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        display: block;
    }

    .modal-body {
        padding: 2rem;
        text-align: center;
    }

    .modal-warning {
        color: #ef4444;
        font-weight: 600;
        margin-top: 1rem;
    }

    .modal-footer {
        padding: 1.5rem;
        display: flex;
        justify-content: center;
        gap: 1rem;
        border-top: 1px solid #e5e7eb;
    }

    .btn-cancel {
        background: white;
        border: 2px solid #e5e7eb;
        color: #374151;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-cancel:hover {
        border-color: #d1d5db;
        background: #f9fafb;
    }

    .btn-confirm-delete {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-confirm-delete:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(239, 68, 68, 0.4);
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-60px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        main {
            padding: 15px;
        }

        .page-header {
            padding: 25px 20px;
        }

        .page-header h1 {
            font-size: 2rem;
        }

        .table thead th,
        .table tbody td {
            padding: 12px 15px;
            font-size: 0.85rem;
        }

        .btn-action {
            width: 100%;
            justify-content: center;
            margin-bottom: 5px;
            margin-right: 0;
        }
    }
</style>

<main>
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <span class="header-icon">⏰</span>
            <h1>Shift Kasir</h1>
            <p class="page-subtitle">Kelola jadwal shift dan waktu kerja kasir</p>
            <nav>
                <ul class="breadcrumb">
                    <li>
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li>
                        <a class="active" href="#">Shift Kasir</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    <!-- Content Card -->
    <div class="content-card">
        <div class="card-header">
            <h3>
                <i class='bx bx-time-five'></i>
                Daftar Shift Kasir
            </h3>
        </div>
        
        <table class="table">
            <thead>
                <tr>
                    <th>Nama Kasir</th>
                    <th>Tanggal</th>
                    <th>Mulai</th>
                    <th>Selesai</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($shifts as $shift)
                <tr>
                    <td>
                        <span class="kasir-name">{{ $shift->kasir_name }}</span>
                    </td>
                    <td>
                        <span class="date-cell">{{ \Carbon\Carbon::parse($shift->date)->format('d/m/Y') }}</span>
                    </td>
                    <td>
                        <span class="time-cell time-start">{{ $shift->start_time }}</span>
                    </td>
                    <td>
                        <span class="time-cell time-end">{{ $shift->end_time ?? '-' }}</span>
                    </td>
                    <td>
                        <button type="button" class="btn-action btn-delete" onclick="showDeleteModal('{{ $shift->id }}', '{{ $shift->kasir_name }}')">
                            <i class='bx bx-trash'></i>
                            Hapus
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="empty-state">
                        Belum ada data shift kasir.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="delete-modal">
        <div class="modal-content">
            <div class="modal-header">
                <i class='bx bx-error-circle warning-icon'></i>
                <h3>Konfirmasi Hapus Shift</h3>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus shift untuk kasir "<span id="kasirToDelete"></span>"?</p>
                <p class="modal-warning">Tindakan ini tidak dapat dibatalkan!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeDeleteModal()">
                    <i class='bx bx-x'></i> Batal
                </button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf 
                    @method('DELETE')
                    <button type="submit" class="btn-confirm-delete">
                        <i class='bx bx-trash'></i> Hapus Shift
                    </button>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Smooth hover effects for table rows
    const tableRows = document.querySelectorAll('.table tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.01)';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });

    // Button click animation
    const actionButtons = document.querySelectorAll('.btn-action');
    actionButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
        });
    });
    
    console.log('✅ Shifts page loaded with yellow theme');
});

function showDeleteModal(shiftId, kasirName) {
    const modal = document.getElementById('deleteModal');
    const kasirToDelete = document.getElementById('kasirToDelete');
    const deleteForm = document.getElementById('deleteForm');
    
    kasirToDelete.textContent = kasirName;
    deleteForm.action = `/admin/shifts/${shiftId}`;
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.style.display = 'none';
    document.body.style.overflow = '';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('deleteModal');
    if (event.target == modal) {
        closeDeleteModal();
    }
}

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});
</script>
@endsection
