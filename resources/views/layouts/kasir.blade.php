<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir Area</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <style>
        body {
            background: #f6fbf7;
        }
        nav {
            background: #fff;
            border-radius: 12px;
            margin: 18px 32px 24px 32px;
            padding: 12px 24px;
            box-shadow: 0 2px 8px #eee;
            display: flex;
            align-items: center;
            gap: 18px;
        }
        nav a, nav button {
            font-weight: 600;
            color: #222;
            text-decoration: none;
            font-size: 15px;
        }
        nav button:hover, nav a:hover {
            color: #2ecc71;
        }
        main {
            margin: 0 32px;
        }
    </style>
</head>
<body>
    <nav>
        <a href="{{ route('kasir.dashboard') }}">Dashboard</a>
        <a href="{{ route('kasir.shift') }}">Shift</a>
        <form method="POST" action="{{ route('kasir.logout') }}" style="display:inline;">
            @csrf
            <button type="submit" style="background:none;border:none;color:inherit;">Logout</button>
        </form>
    </nav>
    <main>
        @yield('content')
    </main>
</body>
</html>
