@extends('layouts.customer')

@section('content')
    <h1>Daftar Menu</h1>
    <div style="display:flex;flex-wrap:wrap;gap:24px;">
        @foreach($menus as $menu)
            <div style="width:220px;border:1px solid #eee;border-radius:8px;padding:16px;text-align:center;background:#fff;">
                @if($menu->image)
                    <img src="{{ asset('storage/'.$menu->image) }}" alt="Gambar Menu" style="width:120px;height:120px;object-fit:cover;border-radius:6px;margin-bottom:12px;">
                @else
                    <div style="width:120px;height:120px;background:#f3f3f3;border-radius:6px;display:flex;align-items:center;justify-content:center;margin:0 auto 12px auto;color:#aaa;">Tidak ada gambar</div>
                @endif
                <div style="font-weight:bold;font-size:18px;">{{ $menu->name }}</div>
                <div style="color:#888;margin:6px 0 10px 0;">{{ $menu->category ? $menu->category->name : '-' }}</div>
                <div style="font-size:16px;color:var(--blue);margin-bottom:10px;">Rp {{ number_format($menu->price, 0, ',', '.') }}</div>
                {{-- Tambahkan tombol beli/pesan di sini jika diperlukan --}}
            </div>
        @endforeach
        @if($menus->isEmpty())
            <div style="width:100%;text-align:center;color:#888;">Belum ada menu tersedia.</div>
        @endif
    </div>
@endsection
