<!DOCTYPE html>
<html>
<head>
    <title>Sistem Shift Kasir</title>
</head>
<body>
    @extends('layouts.admin')

    @section('content')
    <main>
        <div class="head-title">
            <div class="left">
                <h1>Shift Kasir</h1>
                <ul class="breadcrumb">
                    <li>
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li><i class='bx bx-chevron-right' ></i></li>
                    <li>
                        <a class="active" href="#">Shift Kasir</a>
                    </li>
                </ul>
            </div>
            <a href="{{ route('admin.shifts.create') }}" class="btn-download" style="background:var(--blue);color:white;">
                <i class='bx bx-plus'></i>
                <span class="text">Tambah Shift</span>
            </a>
            <a href="{{ route('admin.kasir.register') }}" class="btn-download" style="background:var(--green);color:white;margin-left:8px;">
                <i class='bx bx-user-plus'></i>
                <span class="text">Registrasi Kasir</span>
            </a>
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
    @endsection
</body>
</html>
