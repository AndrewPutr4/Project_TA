<!DOCTYPE html>
<html>
<head>
    <title>Edit Menu</title>
</head>
<body>
    @extends('layouts.admin')

    @section('content')
    <main>
        <div class="head-title">
            <div class="left">
                <h1>Edit Menu</h1>
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
                        <a class="active" href="#">Edit</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="table-data">
            <div class="order">
                <form method="POST" action="{{ route('admin.menus.update', $menu) }}">
                    @csrf
                    @method('PUT')
                    <div style="margin-bottom:16px;">
                        <label>Nama Menu</label>
                        <input type="text" name="name" class="form-control" value="{{ $menu->name }}" required style="width:100%;padding:8px;">
                    </div>
                    {{-- Hapus field jenis --}}
                    <div style="margin-bottom:16px;">
                        <label>Kategori</label>
                        <select name="category_id" class="form-control" required style="width:100%;padding:8px;">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $menu->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div style="margin-bottom:16px;">
                        <label>Harga</label>
                        <input type="number" name="price" class="form-control" value="{{ $menu->price }}" required style="width:100%;padding:8px;">
                    </div>
                    <button type="submit" class="btn-download" style="background:var(--blue);color:white;">Update</button>
                    <a href="{{ route('admin.menus.index') }}" class="btn-download" style="background:var(--red);color:white;">Batal</a>
                </form>
            </div>
        </div>
    </main>
    @endsection
</body>
</html>
