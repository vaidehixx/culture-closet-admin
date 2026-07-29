<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Culture Closet — Ethnic Wear Rental Singapore</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: #FFFCF7;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1.25rem;
        }

        .card {
            max-width: 560px;
            width: 100%;
            text-align: center;
        }

        .logo {
            font-size: 1rem;
            font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #003F5F;
            margin-bottom: 2.5rem;
        }

        h1 {
            font-size: clamp(1.75rem, 5vw, 2.5rem);
            font-weight: 700;
            color: #003F5F;
            line-height: 1.2;
            margin-bottom: 1rem;
        }

        p {
            font-size: 1.0625rem;
            color: #5a6a74;
            line-height: 1.65;
            margin-bottom: 2.25rem;
        }

        .btn {
            display: inline-block;
            background: #FFC857;
            color: #003F5F;
            font-weight: 700;
            font-size: 1rem;
            padding: 0.85rem 2.25rem;
            border-radius: 9999px;
            text-decoration: none;
            transition: opacity 0.15s;
        }

        .btn:hover { opacity: 0.85; }

        .footer {
            margin-top: 3rem;
            font-size: 0.8125rem;
            color: #a0b0bb;
        }

        @media (max-width: 400px) {
            .btn { width: 100%; text-align: center; }
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">Culture Closet</div>
        <h1>Rent stunning ethnic wear from real people in Singapore.</h1>
        <p>Coming soon to the App Store.</p>
        <a href="mailto:hello@culturecloset.site" class="btn">Join the waitlist</a>
        <div class="footer">&copy; {{ date('Y') }} Culture Closet &middot; Singapore</div>
    </div>
</body>
</html>
