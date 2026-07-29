<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — Culture Closet</title>
    <link href="{{ asset('admin.css') }}" rel="stylesheet">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: #f0ece2;
        }
        .login-wrap {
            width: 340px;
        }
        .login-logo {
            text-align: center;
            margin-bottom: 28px;
        }
        .login-logo-name {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            color: #003F5F;
        }
        .login-logo-tag {
            font-size: 10px;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: rgba(0,63,95,0.38);
            margin-top: 3px;
        }
        .login-card {
            background: #fff;
            border-radius: 12px;
            border: 0.5px solid rgba(0,63,95,0.1);
            padding: 28px 28px 24px;
        }
        .login-title {
            font-family: 'Playfair Display', serif;
            font-size: 17px;
            color: #003F5F;
            margin-bottom: 4px;
        }
        .login-sub {
            font-size: 11px;
            color: rgba(0,63,95,0.4);
            margin-bottom: 22px;
        }
        .form-group {
            margin-bottom: 14px;
        }
        .form-label {
            display: block;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: rgba(0,63,95,0.5);
            margin-bottom: 5px;
            font-weight: 500;
        }
        .form-input {
            width: 100%;
            padding: 9px 12px;
            border: 0.5px solid rgba(0,63,95,0.18);
            border-radius: 8px;
            font-size: 13px;
            font-family: 'DM Sans', sans-serif;
            color: #003F5F;
            background: #fff;
            outline: none;
            transition: border-color 0.15s;
        }
        .form-input:focus {
            border-color: #003F5F;
        }
        .form-input.error {
            border-color: #c0392b;
        }
        .form-error {
            font-size: 11px;
            color: #c0392b;
            margin-top: 4px;
        }
        .remember-row {
            display: flex;
            align-items: center;
            gap: 7px;
            margin-bottom: 18px;
        }
        .remember-row input[type="checkbox"] {
            accent-color: #003F5F;
        }
        .remember-row label {
            font-size: 11px;
            color: rgba(0,63,95,0.55);
        }
        .login-btn {
            width: 100%;
            padding: 10px;
            background: #003F5F;
            color: #FFC857;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-family: 'Playfair Display', serif;
            cursor: pointer;
            letter-spacing: 0.02em;
            transition: background 0.15s;
        }
        .login-btn:hover {
            background: #005580;
        }
        .login-footer {
            text-align: center;
            font-size: 10px;
            color: rgba(0,63,95,0.3);
            margin-top: 18px;
        }
    </style>
</head>
<body>
    <div class="login-wrap">
        <div class="login-logo">
            <div class="login-logo-name">Culture Closet</div>
            <div class="login-logo-tag">Admin Panel</div>
        </div>

        <div class="login-card">
            <div class="login-title">Welcome back</div>
            <div class="login-sub">Sign in with your admin credentials</div>

            <form method="POST" action="{{ route('admin.login.post') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="form-input {{ $errors->has('email') ? 'error' : '' }}"
                        placeholder="admin@culturecloset.site"
                        autofocus
                        required
                    >
                    @error('email')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="form-input {{ $errors->has('password') ? 'error' : '' }}"
                        placeholder="••••••••"
                        required
                    >
                    @error('password')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="remember-row">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Keep me signed in</label>
                </div>

                <button type="submit" class="login-btn">Sign In</button>
            </form>
        </div>

        <div class="login-footer">Culture Closet © {{ date('Y') }} · Admin access only</div>
    </div>
</body>
</html>
