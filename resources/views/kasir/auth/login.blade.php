<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Kasir</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 50%, #fed7aa 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            padding: 1rem;
        }
        
        .auth-container {
            max-width: 380px;
            width: 100%;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(245, 158, 11, 0.2);
            padding: 36px 28px 28px 28px;
            display: flex;
            flex-direction: column;
            align-items: center;
            border: 2px solid #fed7aa;
        }
        
        .auth-container img {
            width: 84px;
            height: 84px;
            border-radius: 50%;
            margin-bottom: 18px;
            box-shadow: 0 4px 16px rgba(245, 158, 11, 0.3);
            border: 3px solid #f59e0b;
        }
        
        .auth-container h2 {
            text-align: center;
            margin-bottom: 22px;
            font-weight: 700;
            color: #f59e0b;
            letter-spacing: 1px;
            font-size: 1.5rem;
        }
        
        .auth-container label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #92400e;
            font-size: 0.875rem;
        }
        
        .auth-container input {
            width: 100%;
            padding: 11px 12px;
            margin-bottom: 18px;
            border-radius: 8px;
            border: 2px solid #fed7aa;
            font-size: 15px;
            background: #fffbeb;
            transition: all 0.2s;
        }
        
        .auth-container input:focus {
            border: 2px solid #f59e0b;
            outline: none;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
        }
        
        .auth-container button {
            width: 100%;
            padding: 12px 0;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 17px;
            margin-top: 8px;
            box-shadow: 0 4px 16px rgba(245, 158, 11, 0.4);
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .auth-container button:hover {
            background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(245, 158, 11, 0.5);
        }
        
        .auth-container button:active {
            transform: translateY(0);
        }
        
        .auth-container .error {
            color: #dc2626;
            margin-bottom: 12px;
            text-align: center;
            font-size: 15px;
            padding: 8px;
            background: #fef2f2;
            border-radius: 6px;
            border: 1px solid #fecaca;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            body {
                padding: 0.5rem;
            }
            
            .auth-container {
                max-width: 100%;
                padding: 24px 20px 20px 20px;
                border-radius: 12px;
            }
            
            .auth-container img {
                width: 72px;
                height: 72px;
                margin-bottom: 16px;
            }
            
            .auth-container h2 {
                font-size: 1.375rem;
                margin-bottom: 20px;
            }
            
            .auth-container input {
                padding: 10px 11px;
                font-size: 14px;
                margin-bottom: 16px;
            }
            
            .auth-container button {
                padding: 11px 0;
                font-size: 16px;
            }
        }
        
        @media (max-width: 480px) {
            .auth-container {
                padding: 20px 16px 16px 16px;
                margin: 0.5rem;
            }
            
            .auth-container img {
                width: 64px;
                height: 64px;
                margin-bottom: 14px;
            }
            
            .auth-container h2 {
                font-size: 1.25rem;
                margin-bottom: 18px;
            }
            
            .auth-container label {
                font-size: 0.8125rem;
                margin-bottom: 5px;
            }
            
            .auth-container input {
                padding: 9px 10px;
                font-size: 13px;
                margin-bottom: 14px;
            }
            
            .auth-container button {
                padding: 10px 0;
                font-size: 15px;
                margin-top: 6px;
            }
        }
        
        /* Loading state */
        .auth-container button:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }
        
        /* Focus improvements for accessibility */
        .auth-container input:focus {
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
        }
        
        /* Better touch targets for mobile */
        @media (hover: none) and (pointer: coarse) {
            .auth-container input {
                padding: 12px;
                font-size: 16px; /* Prevents zoom on iOS */
            }
            
            .auth-container button {
                padding: 14px 0;
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <img src="{{ file_exists(public_path('img/kasir.png')) ? asset('img/kasir.png') : 'https://ui-avatars.com/api/?name=Kasir&background=f59e0b&color=fff&size=80' }}" alt="Kasir">
        <h2>Login Kasir</h2>
        <form method="POST" action="{{ route('kasir.login') }}">
            @csrf
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
