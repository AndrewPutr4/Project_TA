<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Password Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f7fa; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .reset-container { background: #fff; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); max-width: 400px; width: 100%; padding: 32px 28px; }
        .reset-container h2 { margin-bottom: 18px; color: #4361ee; }
        .reset-container p { color: #555; margin-bottom: 18px; }
        .form-group { margin-bottom: 18px; }
        .form-group label { font-weight: 500; color: #495057; margin-bottom: 6px; display: block; }
        .form-control { width: 100%; padding: 12px 14px; border-radius: 8px; border: 1px solid #ddd; font-size: 1rem; }
        .btn { width: 100%; padding: 12px; background: linear-gradient(to right, #4361ee, #3f37c9); color: #fff; border: none; border-radius: 8px; font-size: 1rem; font-weight: 600; cursor: pointer; }
        .btn:hover { background: linear-gradient(to right, #3f37c9, #4361ee); }
        .back-link { display: block; text-align: center; margin-top: 18px; color: #4361ee; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
        .error-message { background: #ffe5e5; color: #d90429; padding: 10px 15px; border-radius: 8px; margin-bottom: 18px; text-align: center; }
        .status-message { background: #e0f2fe; color: #2563eb; padding: 10px 15px; border-radius: 8px; margin-bottom: 18px; text-align: center; }
    </style>
</head>
<body>
    <div class="reset-container">
        <h2>Ganti Password</h2>
        <p>Masukkan password baru untuk akun admin Anda.</p>
        @if(session('error'))
            <div class="error-message">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="error-message">{{ $errors->first() }}</div>
        @endif
        @if(session('status'))
            <div class="status-message">{{ session('status') }}</div>
        @endif
        <form method="POST" action="{{ route('admin.password.reset') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email Admin</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $email ?? old('email') }}" readonly>
            </div>
            <div class="form-group">
                <label for="password">Password Baru</label>
                <input type="password" class="form-control" id="password" name="password" required minlength="6" placeholder="Minimal 6 karakter">
            </div>
            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required minlength="6" placeholder="Ulangi password baru">
            </div>
            <button type="submit" class="btn"><i class="fas fa-key"></i> Ganti Password</button>
        </form>
        <a href="{{ route('admin.login') }}" class="back-link"><i class="fas fa-arrow-left"></i> Kembali ke Login</a>
    </div>
</body>
</html>
</body>
</html>
