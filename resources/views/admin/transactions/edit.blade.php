<!DOCTYPE html>
<html>
<head>
    <title>Edit Transaksi</title>
</head>
<body>
    @extends('layouts.admin')

    @section('content')
    <main>
        <div class="head-title">
            <div class="left">
                <h1>Edit Transaksi</h1>
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
                        <a class="active" href="#">Edit</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="table-data">
            <div class="order">
                <form method="POST" action="{{ route('admin.transactions.update', $transaction) }}">
                    @csrf
                    @method('PUT')
                    <div style="margin-bottom:16px;">
                        <label>Nama Pelanggan</label>
                        <input type="text" name="customer_name" class="form-control" value="{{ $transaction->customer_name }}" required style="width:100%;padding:8px;">
                    </div>
                    <div style="margin-bottom:16px;">
                        <label>Total</label>
                        <input type="number" name="total" class="form-control" value="{{ $transaction->total }}" required style="width:100%;padding:8px;">
                    </div>
                    <button type="submit" class="btn-download" style="background:var(--blue);color:white;">Update</button>
                    <a href="{{ route('admin.transactions.index') }}" class="btn-download" style="background:var(--red);color:white;">Batal</a>
                </form>
            </div>
        </div>
    </main>
    @endsection
</body>
</html>
