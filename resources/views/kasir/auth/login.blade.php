<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Kasir</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <style>
        body {
            background: linear-gradient(135deg, #e0f7fa 0%, #f6fbf7 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .auth-container {
            max-width: 380px;
            width: 100%;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px #e0e0e0;
            padding: 36px 28px 28px 28px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .auth-container img {
            width: 84px;
            height: 84px;
            border-radius: 50%;
            margin-bottom: 18px;
            box-shadow: 0 2px 8px #e0e0e0;
        }
        .auth-container h2 {
            text-align: center;
            margin-bottom: 22px;
            font-weight: 700;
            color: #2ecc71;
            letter-spacing: 1px;
        }
        .auth-container label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #222;
        }
        .auth-container input {
            width: 100%;
            padding: 11px 12px;
            margin-bottom: 18px;
            border-radius: 8px;
            border: 1px solid #d1d5db;
            font-size: 15px;
            background: #f9f9f9;
            transition: border 0.2s;
        }
        .auth-container input:focus {
            border: 1.5px solid #2ecc71;
            outline: none;
            background: #fff;
        }
        .auth-container button {
            width: 100%;
            padding: 12px 0;
            background: linear-gradient(90deg, #2ecc71 60%, #27ae60 100%);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 17px;
            margin-top: 8px;
            box-shadow: 0 2px 8px #e0e0e0;
            cursor: pointer;
            transition: background 0.2s;
        }
        .auth-container button:hover {
            background: linear-gradient(90deg, #27ae60 60%, #2ecc71 100%);
        }
        .auth-container .error {
            color: #e74c3c;
            margin-bottom: 12px;
            text-align: center;
            font-size: 15px;
        }
        @media (max-width: 500px) {
            .auth-container {
                padding: 18px 8px;
                border-radius: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <img src="{{ file_exists(public_path('img/kasir.png')) ? asset('img/kasir.png') : 'https://ui-avatars.com/api/?name=Kasir&background=2ecc71&color=fff&size=80' }}" alt="Kasir">
        <h2>Login Kasir</h2>
        <form method="POST" action="{{ route('kasir.login') }}">
            @csrf
            {{-- Pastikan guard kasir digunakan di controller --}}
            <label for="email">Email</label>
            <input id="email" type="email" name="email" required autofocus autocomplete="username">
            <label for="password">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password">
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
