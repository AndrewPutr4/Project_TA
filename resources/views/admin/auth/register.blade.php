<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Admin</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <style>
        .auth-container { max-width: 400px; margin: 60px auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 8px #eee; padding: 32px; }
        .auth-container h2 { text-align: center; margin-bottom: 24px; }
        .auth-container label { display: block; margin-bottom: 6px; }
        .auth-container input { width: 100%; padding: 10px; margin-bottom: 16px; border-radius: 6px; border: 1px solid #ccc; }
        .auth-container button { width: 100%; padding: 10px; background: var(--blue); color: #fff; border: none; border-radius: 6px; font-weight: bold; }
        .auth-container .link { text-align: center; margin-top: 12px; }
        .auth-container .error { color: var(--red); margin-bottom: 12px; text-align: center; }
    </style>
</head>
<body>
    <div class="auth-container">
        <h2>Register Admin</h2>
        <form method="POST" action="{{ route('admin.register') }}">
            @csrf
            <label>Nama</label>
            <input type="text" name="name" required autofocus>
            <label>Email</label>
            <input type="email" name="email" required>
            <label>Password</label>
            <input type="password" name="password" required>
            <label>Konfirmasi Password</label>
            <input type="password" name="password_confirmation" required>
            <button type="submit">Register</button>
        </form>
        <div class="link">
            Sudah punya akun? <a href="{{ route('admin.login') }}">Login</a>
        </div>
        @if($errors->any())
            <div class="error">
                {{ $errors->first() }}
            </div>
        @endif
    </div>
</body>
</html>
