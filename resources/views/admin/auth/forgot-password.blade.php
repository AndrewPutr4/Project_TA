<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #f59e0b;
            --secondary-color: #d97706;
            --accent-color: #fbbf24;
            --error-color: #ef4444;
            --success-color: #10b981;
            --light-color: #fffbeb;
            --dark-color: #92400e;
            --text-color: #78350f;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        .forgot-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(245, 158, 11, 0.2);
            max-width: 450px;
            width: 100%;
            padding: 0;
            overflow: hidden;
            animation: fadeIn 0.5s ease;
            border: 2px solid #fed7aa;
        }

        .forgot-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 30px 25px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .forgot-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .forgot-container h2 {
            font-size: 2rem;
            margin-bottom: 8px;
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .forgot-container h2::before {
            content: '🔑';
            font-size: 1.8rem;
        }

        .forgot-container p {
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 0;
            position: relative;
            z-index: 2;
            font-size: 0.95rem;
        }

        .forgot-form {
            padding: 35px 30px;
            background: linear-gradient(135deg, #fffbeb 0%, rgba(254, 243, 199, 0.3) 100%);
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 10px;
            display: block;
            font-size: 0.95rem;
        }

        .form-control {
            width: 100%;
            padding: 15px 15px 15px 45px;
            border-radius: 12px;
            border: 2px solid #fed7aa;
            font-size: 1rem;
            background: white;
            color: var(--text-color);
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.2);
            outline: none;
            background: #fffbeb;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 43px;
            color: #d97706;
            font-size: 1.1rem;
        }

        .btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(245, 158, 11, 0.3);
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn:hover {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(245, 158, 11, 0.4);
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            padding: 10px;
            border-radius: 8px;
        }

        .back-link:hover {
            background: rgba(245, 158, 11, 0.1);
            color: var(--secondary-color);
        }

        .status-message {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: var(--success-color);
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            text-align: center;
            border: 2px solid #a7f3d0;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .status-message::before {
            content: '✅';
            font-size: 1.1rem;
        }

        .error-message {
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            color: var(--error-color);
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            text-align: center;
            border: 2px solid #fecaca;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .error-message::before {
            content: '❌';
            font-size: 1.1rem;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 480px) {
            .forgot-container {
                border-radius: 0;
                border: none;
                box-shadow: none;
            }

            body {
                padding: 0;
                background: white;
            }

            .forgot-form {
                padding: 25px 20px;
            }

            .forgot-header {
                padding: 25px 20px;
            }
        }

        /* Add loading animation */
        .btn.loading {
            pointer-events: none;
            opacity: 0.8;
        }

        .btn.loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            margin: auto;
            border: 2px solid transparent;
            border-top-color: white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="forgot-container">
        <div class="forgot-header">
            <h2>Lupa Password</h2>
            <p>Masukkan email admin untuk reset password</p>
        </div>

        <div class="forgot-form">
            @if(session('status'))
                <div class="status-message">{{ session('status') }}</div>
            @endif
            @if(session('error'))
                <div class="error-)</div>
            @endif
            @if(session('error'))
                <div class="error-message">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('admin.password.email') }}" id="forgotForm">
                @csrf
                <div class="form-group">
                    <label for="email">Email Admin</label>
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" class="form-control" id="email" name="email" required autofocus placeholder="Masukkan email admin">
                </div>
                <button type="submit" class="btn" id="forgotBtn">
                    <i class="fas fa-paper-plane"></i> Kirim Link Reset
                </button>
            </form>

            <a href="{{ route('admin.login') }}" class="back-link">
                <i class="fas fa-arrow-left"></i> Kembali ke Login
            </a>
        </div>
    </div>

    <script>
        // Add form submission animation
        document.getElementById('forgotForm').addEventListener('submit', function() {
            const btn = document.getElementById('forgotBtn');
            btn.classList.add('loading');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
        });

        // Add input focus animations
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });
    </script>
</body>
</html>
