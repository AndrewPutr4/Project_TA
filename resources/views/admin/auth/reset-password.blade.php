<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password Admin</title>
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
        .status-message { background: #e0f2fe; color: #2563eb; padding: 10px 15px; border-radius: 8px; margin-bottom: 18px; text-align: center; }
        .reset-code-box { background: #f3f4f6; color: #222; font-size: 1.3rem; font-weight: bold; letter-spacing: 2px; padding: 12px 0; border-radius: 8px; margin-bottom: 18px; text-align: center; }
        .error-message { background: #ffe5e5; color: #d90429; padding: 10px 15px; border-radius: 8px; margin-bottom: 18px; text-align: center; }
    </style>
</head>
<body>
    <div class="reset-container">
        <h2>Reset Password</h2>
        <p>Masukkan kode reset yang ditampilkan, email, dan password baru Anda.</p>
        @if(session('reset_code'))
            <div class="reset-code-box">Kode Reset Anda: <span>{{ session('reset_code') }}</span></div>
        @endif
        @if($errors->any())
            <div class="error-message">{{ $errors->first() }}</div>
        @endif
        <form method="POST" action="{{ route('admin.password.reset') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email Admin</label>
                <input type="email" class="form-control" id="email" name="email" required autofocus placeholder="Masukkan email admin" value="{{ session('admin_reset_email') }}">
            </div>
            <div class="form-group">
                <label for="reset_code">Kode Reset</label>
                <input type="text" class="form-control" id="reset_code" name="reset_code" required placeholder="Masukkan kode reset">
            </div>
            <div class="form-group">
                <label for="password">Password Baru</label>
                <input type="password" class="form-control" id="password" name="password" required minlength="6" placeholder="Password baru">
            </div>
            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password Baru</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required minlength="6" placeholder="Ulangi password baru">
            </div>
            <button type="submit" class="btn"><i class="fas fa-key"></i> Reset Password</button>
        </form>
        <a href="{{ route('admin.login') }}" class="back-link"><i class="fas fa-arrow-left"></i> Kembali ke Login</a>
    </div>
</body>
</html>
