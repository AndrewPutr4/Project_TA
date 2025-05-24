<!DOCTYPE html>
<html>
<head>
    <title>Tambah Shift</title>
</head>
<body>
    @extends('layouts.admin')

    @section('content')
    <main>
        <div class="head-title">
            <div class="left">
                <h1>Tambah Shift Kasir</h1>
                <ul class="breadcrumb">
                    <li>
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li><i class='bx bx-chevron-right' ></i></li>
                    <li>
                        <a href="{{ route('admin.shifts.index') }}">Shift Kasir</a>
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
                <form method="POST" action="{{ route('admin.shifts.store') }}">
                    @csrf
                    <div style="margin-bottom:16px;">
                        <label>Nama Kasir</label>
                        <input type="text" name="kasir_name" class="form-control" required style="width:100%;padding:8px;">
                    </div>
                    <div style="margin-bottom:16px;">
                        <label>Mulai</label>
                        <input type="time" name="start_time" class="form-control" required style="width:100%;padding:8px;">
                    </div>
                    <div style="margin-bottom:16px;">
                        <label>Selesai</label>
                        <input type="time" name="end_time" class="form-control" required style="width:100%;padding:8px;">
                    </div>
                    <button type="submit" class="btn-download" style="background:var(--blue);color:white;">Simpan</button>
                    <a href="{{ route('admin.shifts.index') }}" class="btn-download" style="background:var(--red);color:white;">Batal</a>
                </form>
            </div>
        </div>
    </main>
    @endsection
</body>
</html>
