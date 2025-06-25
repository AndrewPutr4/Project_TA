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
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(180deg); }
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
        content: '⏰';
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

    .btn-download {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 15px 30px;
        color: white;
        text-decoration: none;
        border-radius: 15px;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        z-index: 2;
    }

    .btn-download[style*="blue"] {
        background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
        box-shadow: 0 8px 25px rgba(86, 171, 47, 0.3);
    }

    .btn-download[style*="green"] {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        box-shadow: 0 8px 25px rgba(240, 147, 251, 0.3);
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
        color: white;
        text-decoration: none;
    }

    .btn-download[style*="blue"]:hover {
        box-shadow: 0 15px 35px rgba(86, 171, 47, 0.4);
    }

    .btn-download[style*="green"]:hover {
        box-shadow: 0 15px 35px rgba(240, 147, 251, 0.4);
    }

    .btn-download i {
        font-size: 1.2rem;
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
        transform: translateY(-8px);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    }

    .order .head {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 25px 30px;
        border-bottom: 1px solid #e9ecef;
        position: relative;
    }

    .order .head h3 {
        margin: 0;
        color: #495057;
        font-size: 1.5rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .order .head h3::before {
        content: '📋';
        font-size: 1.3rem;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
    }

    table thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    table th {
        padding: 20px 25px;
        text-align: left;
        font-weight: 600;
        font-size: 0.95rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    table td {
        padding: 20px 25px;
        border-bottom: 1px solid #f0f0f0;
        vertical-align: middle;
        font-size: 0.95rem;
    }

    table tbody tr {
        transition: all 0.3s ease;
        position: relative;
    }

    table tbody tr:hover {
        background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 100%);
        transform: scale(1.01);
    }

    table tbody tr:nth-child(even) {
        background: rgba(248, 249, 250, 0.5);
    }

    table tbody tr:nth-child(even):hover {
        background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 100%);
    }

    /* Enhanced styling for kasir names */
    table td:first-child {
        font-weight: 600;
        color: #495057;
        position: relative;
    }

    table td:first-child::before {
        content: '👤';
        margin-right: 8px;
        font-size: 1.1rem;
    }

    /* Time styling */
    table td:nth-child(2),
    table td:nth-child(3) {
        font-family: 'Courier New', monospace;
        font-weight: 600;
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        color: #1565c0;
        border-radius: 8px;
        text-align: center;
        position: relative;
    }

    table td:nth-child(2)::before {
        content: '🕐';
        margin-right: 5px;
    }

    table td:nth-child(3)::before {
        content: '🕕';
        margin-right: 5px;
    }

    /* Action buttons styling */
    table td:last-child a {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 16px;
        background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);
        color: white !important;
        text-decoration: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(23, 162, 184, 0.3);
        margin-right: 8px;
    }

    table td:last-child a:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(23, 162, 184, 0.4);
        color: white !important;
        text-decoration: none;
    }

    table td:last-child button {
        display: inline-flex;
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

    table td:last-child button:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(255, 107, 107, 0.4);
    }

    table td:last-child form {
        display: inline;
    }

    /* Empty state styling */
    table td[colspan="4"] {
        text-align: center;
        padding: 80px 20px;
        color: #6c757d;
        font-size: 1.1rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        position: relative;
    }

    table td[colspan="4"]::before {
        content: '📋';
        display: block;
        font-size: 4rem;
        margin-bottom: 20px;
        opacity: 0.3;
    }

    /* Responsive design */
    @media (max-width: 768px) {
        main {
            padding: 20px 15px;
        }

        .head-title {
            padding: 25px 20px;
            flex-direction: column;
            align-items: flex-start;
        }

        .head-title .left h1 {
            font-size: 2rem;
        }

        .btn-download {
            width: 100%;
            justify-content: center;
            margin-left: 0 !important;
            margin-top: 10px;
        }

        table th,
        table td {
            padding: 15px 10px;
            font-size: 0.85rem;
        }

        table td:last-child {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        table td:last-child a,
        table td:last-child button {
            width: 100%;
            justify-content: center;
            margin-right: 0;
        }
    }

    /* Loading animation */
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

<main>
    <div class="head-title">
        <div class="left">
            <h1>Shift Kasir</h1>
            <ul class="breadcrumb">
                <li>
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                </li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li>
                    <a class="active" href="#">Shift Kasir</a>
                </li>
            </ul>
        </div>
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="{{ route('admin.shifts.create') }}" class="btn-download" style="background:var(--blue);color:white;">
                <i class='bx bx-plus'></i>
                <span class="text">Tambah Shift</span>
            </a>
            <a href="{{ route('admin.kasir.register') }}" class="btn-download" style="background:var(--green);color:white;margin-left:8px;">
                <i class='bx bx-user-plus'></i>
                <span class="text">Registrasi Kasir</span>
            </a>
        </div>
    </div>
    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Daftar Shift Kasir</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Nama Kasir</th>
                        <th>Mulai</th>
                        <th>Selesai</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($shifts as $shift)
                    <tr>
                        <td>{{ $shift->kasir_name }}</td>
                        <td>{{ $shift->start_time }}</td>
                        <td>{{ $shift->end_time }}</td>
                        <td>
                            <a href="{{ route('admin.shifts.edit', $shift) }}" style="color:var(--blue);margin-right:8px;"><i class='bx bx-edit'></i>Edit</a>
                            <form action="{{ route('admin.shifts.destroy', $shift) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" onclick="return confirm('Yakin hapus?')" style="background:none;border:none;color:var(--red);cursor:pointer;"><i class='bx bx-trash'></i>Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    @if($shifts->isEmpty())
                    <tr>
                        <td colspan="4" style="text-align:center;">Belum ada shift kasir.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</main>

<script>
// Add loading animation to buttons
document.querySelectorAll('.btn-download, table a, table button').forEach(btn => {
    btn.addEventListener('click', function() {
        this.style.transform = 'scale(0.95)';
        setTimeout(() => {
            this.style.transform = 'scale(1)';
        }, 150);
    });
});

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
            scrollBtn.addEventListener('mouseenter', () => {
                scrollBtn.style.transform = 'scale(1.1)';
            });
            scrollBtn.addEventListener('mouseleave', () => {
                scrollBtn.style.transform = 'scale(1)';
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

// Enhanced table interactions
document.querySelectorAll('table tbody tr').forEach(row => {
    row.addEventListener('mouseenter', function() {
        this.style.boxShadow = '0 5px 15px rgba(0,0,0,0.1)';
    });
    
    row.addEventListener('mouseleave', function() {
        this.style.boxShadow = 'none';
    });
});
</script>
@endsection