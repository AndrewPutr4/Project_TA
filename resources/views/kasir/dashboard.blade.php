@extends('layouts.kasir')

@section('content')
<div class="kasir-container" style="display:flex;gap:24px;background:#f6fbf7;padding:32px 0;min-height:100vh;">
    {{-- Sidebar Kategori --}}
    <aside style="width:180px;background:#fff;border-radius:16px;padding:24px 12px;box-shadow:0 2px 8px #eee;">
        <h4 style="margin-bottom:18px;font-weight:700;">Category</h4>
        <div>
            @foreach($categories as $category)
                <a href="{{ url('/kasir/dashboard?category=' . $category->id) }}" style="display:flex;align-items:center;gap:8px;padding:10px 8px;border-radius:8px;margin-bottom:6px;text-decoration:none;color:#222;{{ (isset($selectedCategory) && $selectedCategory->id == $category->id) ? 'background:#f5f5f5;font-weight:600;' : '' }}">
                    <span style="font-size:20px;">{{ $category->icon ?? '🍽️' }}</span>
                    <span>{{ $category->name }}</span>
                </a>
            @endforeach
        </div>
    </aside>

    {{-- Daftar Menu --}}
    <main style="flex:1;background:#fff;border-radius:16px;padding:24px;box-shadow:0 2px 8px #eee;">
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <h4 style="margin-bottom:18px;font-weight:700;">Special Menu for you</h4>
            <form method="GET" action="{{ url('/kasir/dashboard') }}">
                <input type="text" name="search" placeholder="Search menu..." style="padding:6px 12px;border-radius:8px;border:1px solid #eee;">
            </form>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:18px;">
            @foreach($foods as $food)
                <div style="background:#fafafa;border-radius:12px;padding:14px;text-align:center;box-shadow:0 1px 4px #f0f0f0;">
                    <img src="{{ $food->image ? asset('storage/'.$food->image) : 'https://via.placeholder.com/120x80?text=No+Image' }}" alt="{{ $food->name }}" style="width:80px;height:80px;object-fit:cover;border-radius:8px;">
                    <div style="font-weight:600;margin:8px 0 4px 0;">{{ $food->name }}</div>
                    <div style="font-size:13px;color:#888;">Rp{{ number_format($food->price,0,',','.') }}</div>
                    <button style="margin-top:10px;background:#2ecc71;color:#fff;border:none;padding:6px 18px;border-radius:6px;cursor:pointer;">Add</button>
                </div>
            @endforeach
        </div>
    </main>

    {{-- Order Details --}}
    <aside style="width:320px;background:#fff;border-radius:16px;padding:24px;box-shadow:0 2px 8px #eee;">
        <h4 style="margin-bottom:18px;font-weight:700;">Order Details</h4>
        <div style="margin-bottom:18px;">
            <div style="font-weight:600;">Customer: <span style="font-weight:400;">Umum</span></div>
            <div style="font-size:13px;color:#888;">Table: <span style="color:#222;">A1</span></div>
        </div>
        <div>
            {{-- Loop pesanan di sini --}}
            <div style="display:flex;justify-content:space-between;margin-bottom:8px;">
                <span>Super Delicious Pizza</span>
                <span>Rp45.000</span>
            </div>
            <div style="display:flex;justify-content:space-between;margin-bottom:8px;">
                <span>French Fries</span>
                <span>Rp15.000</span>
            </div>
            {{-- ... --}}
        </div>
        <hr style="margin:16px 0;">
        <div style="display:flex;justify-content:space-between;font-weight:600;">
            <span>Total</span>
            <span>Rp60.000</span>
        </div>
        <button style="margin-top:18px;width:100%;background:#ffb400;color:#fff;border:none;padding:10px 0;border-radius:8px;font-weight:700;font-size:16px;cursor:pointer;">Charge and Sell</button>
    </aside>
</div>
@endsection
