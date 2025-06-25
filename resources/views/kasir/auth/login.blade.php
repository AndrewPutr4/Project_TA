<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Kasir</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <style>
        body { background: #f6fbf7; }
        .auth-container { max-width: 400px; margin: 60px auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 8px #eee; padding: 32px; }
        .auth-container h2 { text-align: center; margin-bottom: 24px; }
        .auth-container label { display: block; margin-bottom: 6px; font-weight: 600; }
        .auth-container input { width: 100%; padding: 10px; margin-bottom: 16px; border-radius: 6px; border: 1px solid #ccc; }
        .auth-container button { width: 100%; padding: 10px; background: #2ecc71; color: #fff; border: none; border-radius: 6px; font-weight: bold; font-size: 16px; }
        .auth-container .link { text-align: center; margin-top: 12px; }
        .auth-container .error { color: #e74c3c; margin-bottom: 12px; text-align: center; }
        .auth-container img { display:block;margin:0 auto 20px auto;width:80px;height:80px; }
        @media (max-width: 500px) {
            .auth-container { padding: 18px; }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <img src="{{ file_exists(public_path('img/kasir.png')) ? asset('img/kasir.png') : 'https://ui-avatars.com/api/?name=Kasir&background=2ecc71&color=fff&size=80' }}" alt="Kasir">
        <h2>Login Kasir</h2>
        <form method="POST" action="{{ route('kasir.login') }}">
            @csrf
            <label for="email">Email</label>
            <input id="email" type="email" name="email" required autofocus>
            <label for="password">Password</label>
            <input id="password" type="password" name="password" required>
            <button type="submit">Login</button>
        </form>
        @if($errors->any())
            <div class="error">
                {{ $errors->first() }}
            </div>
        @endif
    </div>
</body>
</html>
