<!DOCTYPE html>
<html>
<head>
    <title>Tambah Menu</title>
</head>
<body>
    @extends('layouts.admin')

    @section('content')
    <main>
        <div class="head-title">
            <div class="left">
                <h1>Tambah Menu</h1>
                <ul class="breadcrumb">
                    <li>
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li><i class='bx bx-chevron-right' ></i></li>
                    <li>
                        <a href="{{ route('admin.menus.index') }}">Menu</a>
                    </li>
                    <li><i class='bx bx-chevron-right' ></i></li>
                    <li>
                        <a class="active" href="#">Tambah</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="table-data">
            <div class="order">
                <form method="POST" action="{{ route('admin.menus.store') }}">
                    @csrf
                    <div style="margin-bottom:16px;">
                        <label>Nama Menu</label>
                        <input type="text" name="name" class="form-control" required style="width:100%;padding:8px;">
                    </div>
                    <div style="margin-bottom:16px;">
                        <label>Jenis</label>
                        <select name="type" class="form-control" required style="width:100%;padding:8px;">
                            <option value="makanan">Makanan</option>
                            <option value="minuman">Minuman</option>
                        </select>
                    </div>
                    <div style="margin-bottom:16px;">
                        <label>Harga</label>
                        <input type="text" name="price" class="form-control" required style="width:100%;padding:8px;" placeholder="Contoh: 100000 atau 100.000" pattern="[0-9,\.]*" inputmode="decimal">
                    </div>
                    <button type="submit" class="btn-download" style="background:var(--blue);color:white;">Simpan</button>
                    <a href="{{ route('admin.menus.index') }}" class="btn-download" style="background:var(--red);color:white;">Batal</a>
                </form>
            </div>
        </div>
    </main>
    @endsection
</body>
</html>
