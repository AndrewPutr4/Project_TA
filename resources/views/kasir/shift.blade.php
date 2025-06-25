@extends('layouts.kasir')

@section('content')
    <h1>Shift Kasir</h1>
    @if(session('shift_active'))
        <div style="margin-bottom:20px;">
            <strong>Shift sedang berjalan.</strong>
            <form method="POST" action="{{ route('kasir.shift.end') }}">
                @csrf
                <button type="submit" style="background:var(--red);color:#fff;padding:8px 16px;border:none;border-radius:4px;">Akhiri Shift</button>
            </form>
        </div>
        <a href="{{ route('kasir.dashboard') }}" style="background:var(--blue);color:#fff;padding:8px 16px;border-radius:4px;text-decoration:none;">Masuk Ke Dashboard</a>
    @else
        <form method="POST" action="{{ route('kasir.shift.start') }}">
            @csrf
            <button type="submit" style="background:var(--blue);color:#fff;padding:8px 16px;border:none;border-radius:4px;">Mulai Shift</button>
        </form>
        {{-- Jika ingin login kasir, tambahkan link berikut --}}
        {{-- <a href="{{ route('kasir.login') }}" style="margin-top:16px;display:inline-block;">Login Kasir</a> --}}
    @endif
    @if(session('message'))
        <div style="margin-top:20px;color:var(--red);">{{ session('message') }}</div>
    @endif
@endsection
