<!DOCTYPE html>
<html>
<head>
    <title>Menu Makanan & Minuman</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.0.1/css/boxicons.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
    @extends('layouts.admin')

    @section('content')
    <main>
        <div class="head-title">
            <div class="left">
                <h1>Menu Makanan & Minuman</h1>
                <ul class="breadcrumb">
                    <li>
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li><i class='bx bx-chevron-right' ></i></li>
                    <li>
                        <a class="active" href="#">Menu</a>
                    </li>
                </ul>
            </div>
            <a href="{{ route('admin.menus.create') }}" class="btn-download" style="background:var(--blue);color:white;">
                <i class='bx bx-plus'></i>
                <span class="text">Tambah Menu</span>
            </a>
        </div>
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Daftar Menu</h3>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Jenis</th>
                            <th>Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($menus as $menu)
                        <tr>
                            <td>{{ $menu->name }}</td>
                            <td>{{ ucfirst($menu->type) }}</td>
                            <td>Rp {{ number_format($menu->price, 0, ',', '.') }}</td>
                            <td>
                                <a href="{{ route('admin.menus.edit', $menu) }}" style="color:var(--blue);margin-right:8px;"><i class='bx bx-edit'></i>Edit</a>
                                <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" onclick="return confirm('Yakin hapus?')" style="background:none;border:none;color:var(--red);cursor:pointer;"><i class='bx bx-trash'></i>Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                        @if($menus->isEmpty())
                        <tr>
                            <td colspan="4" style="text-align:center;">Belum ada menu.</td>
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
