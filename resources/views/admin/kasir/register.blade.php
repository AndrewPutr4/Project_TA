@extends('layouts.admin')

@section('content')
<div style="max-width:900px;margin:32px auto;">
    <h2 style="margin-bottom:24px;">Manajemen Kasir</h2>
    {{-- Tambah Kasir --}}
    <div style="background:#fff;padding:24px;border-radius:10px;box-shadow:0 2px 8px #eee;margin-bottom:32px;">
        <h4 style="margin-bottom:16px;">Tambah Kasir Baru</h4>
        <form method="POST" action="{{ route('admin.kasir.store') }}">
            @csrf
            <div style="display:flex;gap:16px;">
                <div style="flex:1;">
                    <label>Nama</label>
                    <input type="text" name="name" required style="width:100%;padding:8px;border-radius:6px;border:1px solid #ccc;">
                </div>
                <div style="flex:1;">
                    <label>Email</label>
                    <input type="email" name="email" required style="width:100%;padding:8px;border-radius:6px;border:1px solid #ccc;">
                </div>
                <div style="flex:1;">
                    <label>Password</label>
                    <input type="password" name="password" required style="width:100%;padding:8px;border-radius:6px;border:1px solid #ccc;">
                </div>
                <div style="align-self:flex-end;">
                    <button type="submit" style="padding:8px 18px;background:#2ecc71;color:#fff;border:none;border-radius:6px;font-weight:600;">Tambah</button>
                </div>
            </div>
        </form>
        @if($errors->any())
            <div style="color:#e74c3c;margin-top:12px;">
                {{ $errors->first() }}
            </div>
        @endif
        @if(session('success'))
            <div style="color:#27ae60;margin-top:12px;">
                {{ session('success') }}
            </div>
        @endif
    </div>

    {{-- Daftar Kasir --}}
    <div style="background:#fff;padding:24px;border-radius:10px;box-shadow:0 2px 8px #eee;overflow-x:auto;">
        <h4 style="margin-bottom:16px;">Daftar Kasir</h4>
        <table style="width:100%;border-collapse:collapse;min-width:600px;">
            <thead>
                <tr style="background:#f5f5f5;">
                    <th style="padding:12px 10px;text-align:left;">#</th>
                    <th style="padding:12px 10px;text-align:left;">Nama</th>
                    <th style="padding:12px 10px;text-align:left;">Email</th>
                    <th style="padding:12px 10px;text-align:left;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kasirs as $kasir)
                <tr style="transition:background 0.2s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='transparent'">
                    <td style="padding:10px 10px;">{{ $loop->iteration }}</td>
                    <td style="padding:10px 10px;">{{ $kasir->name }}</td>
                    <td style="padding:10px 10px;">{{ $kasir->email }}</td>
                    <td style="padding:10px 10px;">
                        <div style="display:flex;gap:8px;">
                            <form method="POST" action="{{ route('admin.kasir.destroy', $kasir->id) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Yakin hapus kasir ini?')" style="background:#e74c3c;color:#fff;border:none;padding:6px 16px;border-radius:4px;cursor:pointer;">Hapus</button>
                            </form>
                            <button onclick="showEdit({{ $kasir->id }}, '{{ addslashes($kasir->name) }}', '{{ addslashes($kasir->email) }}')" style="background:#f1c40f;color:#fff;border:none;padding:6px 16px;border-radius:4px;cursor:pointer;">Edit</button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="padding:16px;text-align:center;color:#888;">Belum ada kasir.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Edit Kasir --}}
<div id="editModal" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.2);align-items:center;justify-content:center;z-index:99;">
    <div style="background:#fff;padding:32px;border-radius:10px;min-width:340px;box-shadow:0 2px 8px #eee;position:relative;">
        <h4 style="margin-bottom:18px;">Edit Kasir</h4>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div style="margin-bottom:12px;">
                <label>Nama</label>
                <input type="text" id="editName" name="name" required style="width:100%;padding:8px;border-radius:6px;border:1px solid #ccc;">
            </div>
            <div style="margin-bottom:12px;">
                <label>Email</label>
                <input type="email" id="editEmail" name="email" required style="width:100%;padding:8px;border-radius:6px;border:1px solid #ccc;">
            </div>
            <div style="margin-bottom:12px;">
                <label>Password (isi jika ingin ganti)</label>
                <input type="password" name="password" style="width:100%;padding:8px;border-radius:6px;border:1px solid #ccc;">
            </div>
            <div style="display:flex;gap:12px;">
                <button type="submit" style="background:#2ecc71;color:#fff;border:none;padding:8px 18px;border-radius:6px;font-weight:600;">Simpan</button>
                <button type="button" onclick="hideEdit()" style="background:#e74c3c;color:#fff;border:none;padding:8px 18px;border-radius:6px;">Batal</button>
            </div>
        </form>
    </div>
</div>
<script>
function showEdit(id, name, email) {
    document.getElementById('editModal').style.display = 'flex';
    document.getElementById('editName').value = name;
    document.getElementById('editEmail').value = email;
    document.getElementById('editForm').action = '/admin/kasir/' + id;
}
function hideEdit() {
    document.getElementById('editModal').style.display = 'none';
}
</script>
@endsection
