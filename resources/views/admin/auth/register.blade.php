<!DOCTYPE html>
<html>
<head>
    <title>Admin Register</title>
</head>
<body>
    <h2>Admin Register</h2>
    <form method="POST" action="{{ route('admin.register') }}">
        @csrf
        <div>
            <label>Name</label>
            <input type="text" name="name" required autofocus>
        </div>
        <div>
            <label>Email</label>
            <input type="email" name="email" required>
        </div>
        <div>
            <label>Password</label>
            <input type="password" name="password" required>
        </div>
        <div>
            <label>Confirm Password</label>
            <input type="password" name="password_confirmation" required>
        </div>
        <button type="submit">Register</button>
    </form>
    <a href="{{ route('admin.login') }}">Login</a>
    @if($errors->any())
        <div>
            <strong>{{ $errors->first() }}</strong>
        </div>
    @endif
</body>
</html>
