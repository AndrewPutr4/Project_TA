<!DOCTYPE html>
<html>
<head>
    <title>Tambah Transaksi</title>
    <link rel="stylesheet" href="{{ asset('path/to/your/css/file.css') }}">
</head>
<body>
    @extends('layouts.admin')

    @section('content')
    <main>
        <div class="head-title">
            <div class="left">
                <h1>Tambah Transaksi</h1>
                <ul class="breadcrumb">
                    <li>
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li><i class='bx bx-chevron-right' ></i></li>
                    <li>
                        <a href="{{ route('admin.transactions.index') }}">Transaksi</a>
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
                <form method="POST" action="{{ route('admin.transactions.store') }}">
                    @csrf
                    <div style="margin-bottom:16px;">
                        <label>Nama Pelanggan</label>
                        <input type="text" name="customer_name" class="form-control" required style="width:100%;padding:8px;">
                    </div>
                    <div style="margin-bottom:16px;">
                        <label>Total</label>
                        <input type="number" name="total" class="form-control" required style="width:100%;padding:8px;">
                    </div>
                    <button type="submit" class="btn-download" style="background:var(--blue);color:white;">Simpan</button>
                    <a href="{{ route('admin.transactions.index') }}" class="btn-download" style="background:var(--red);color:white;">Batal</a>
                </form>
            </div>
        </div>
    </main>
    @endsection
</body>
</html>
