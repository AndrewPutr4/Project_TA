<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Area</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>
<body>
    <nav>
        <a href="{{ route('customer.dashboard') }}">Dashboard</a>
        <a href="{{ route('customer.menu') }}">Menu</a>
        <a href="{{ route('customer.transactions') }}">Transaksi</a>
    </nav>
    <main>
        @yield('content')
    </main>
</body>
</html>
