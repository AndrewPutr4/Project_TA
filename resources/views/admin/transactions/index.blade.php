<!DOCTYPE html>
<html>
<head>
    <title>Transaksi Pelanggan</title>
</head>
<body>
    @extends('layouts.admin')

    @section('content')
    <main>
        <div class="head-title">
            <div class="left">
                <h1>Transaksi Pelanggan</h1>
                <ul class="breadcrumb">
                    <li>
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li><i class='bx bx-chevron-right' ></i></li>
                    <li>
                        <a class="active" href="#">Transaksi</a>
                    </li>
                </ul>
            </div>
            <a href="{{ route('admin.transactions.create') }}" class="btn-download" style="background:var(--blue);color:white;">
                <i class='bx bx-plus'></i>
                <span class="text">Tambah Transaksi</span>
            </a>
        </div>
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Daftar Transaksi</h3>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Nama Pelanggan</th>
                            <th>Total</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->customer_name }}</td>
                            <td>Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                            <td>{{ $transaction->created_at->format('d-m-Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.transactions.edit', $transaction) }}" style="color:var(--blue);margin-right:8px;"><i class='bx bx-edit'></i>Edit</a>
                                <form action="{{ route('admin.transactions.destroy', $transaction) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" onclick="return confirm('Yakin hapus?')" style="background:none;border:none;color:var(--red);cursor:pointer;"><i class='bx bx-trash'></i>Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                        @if($transactions->isEmpty())
                        <tr>
                            <td colspan="4" style="text-align:center;">Belum ada transaksi.</td>
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
