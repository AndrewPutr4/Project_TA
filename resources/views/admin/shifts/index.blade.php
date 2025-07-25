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
                        <a href="{{ route('admin.shifts.edit', $shift) }}" class="btn-action btn-edit">
                            <i class='bx bx-edit'></i>
                            Edit
                        </a>
                        <form action="{{ route('admin.shifts.destroy', $shift) }}" method="POST" style="display:inline;">
                            @csrf 
                            @method('DELETE')
                            <button type="submit" class="btn-action btn-delete" onclick="return confirm('Yakin ingin menghapus shift ini?')">
                                <i class='bx bx-trash'></i>
                                Hapus
                            </button>
                        </form>
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
</script>
@endsection
