<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — Bayan Run 2026</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: #F5F4F0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
        }

        /* Subtle pattern background */
        body::before {
            content: '';
            position: fixed; inset: 0;
            background-image: radial-gradient(#E8E8E8 1px, transparent 1px);
            background-size: 28px 28px;
            pointer-events: none;
        }

        .wrap {
            width: 100%; max-width: 400px;
            position: relative; z-index: 1;
            animation: fadeUp .45s ease both;
        }
        @keyframes fadeUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }

        .logo-area {
            text-align: center; margin-bottom: 32px;
        }
        .logo-area img { height: 46px; width: auto; margin-bottom: 12px; }
        .logo-area p {
            font-family: 'Syne', sans-serif;
            font-size: 10px; font-weight: 700;
            letter-spacing: .14em; text-transform: uppercase;
            color: #AAAAAA;
        }

        .card {
            background: #FFFFFF;
            border: 1px solid #E8E8E8;
            border-radius: 20px;
            padding: 36px;
            box-shadow: 0 4px 40px rgba(0,0,0,.06);
        }

        .card-title {
            font-family: 'Syne', sans-serif;
            font-size: 22px; font-weight: 800;
            color: #111; margin-bottom: 4px;
        }
        .card-sub {
            font-size: 13px; color: #999; margin-bottom: 28px;
        }

        .field { margin-bottom: 18px; }
        .label {
            display: block;
            font-family: 'Syne', sans-serif;
            font-size: 10px; font-weight: 700;
            letter-spacing: .1em; text-transform: uppercase;
            color: #666; margin-bottom: 7px;
        }
        input[type=email], input[type=password] {
            width: 100%;
            background: #FAFAFA;
            border: 1.5px solid #E8E8E8;
            border-radius: 11px;
            padding: 12px 16px;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px; color: #111;
            outline: none;
            transition: border-color .18s, box-shadow .18s, background .18s;
        }
        input:focus {
            border-color: #E8001E;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(232,0,30,.08);
        }
        input::placeholder { color: #C0C0C0; }

        .remember {
            display: flex; align-items: center; gap: 8px; margin-bottom: 24px;
        }
        .remember input {
            width: 16px; height: 16px;
            accent-color: #E8001E; cursor: pointer;
        }
        .remember label { font-size: 13px; color: #888; cursor: pointer; }

        .btn {
            width: 100%; padding: 14px;
            background: #E8001E; color: #fff; border: none;
            border-radius: 12px;
            font-family: 'Syne', sans-serif;
            font-size: 12px; font-weight: 700;
            letter-spacing: .1em; text-transform: uppercase;
            cursor: pointer; transition: all .2s;
        }
        .btn:hover {
            background: #C0001A;
            transform: translateY(-1px);
            box-shadow: 0 8px 28px rgba(232,0,30,.25);
        }

        .err-box {
            background: #FFF0F2;
            border: 1px solid #FFCCD2;
            border-radius: 11px;
            padding: 12px 16px;
            font-size: 13px; color: #C00018;
            margin-bottom: 20px;
            display: flex; align-items: center; gap: 10px;
        }

        .back-link {
            display: block; text-align: center;
            margin-top: 20px;
            font-size: 12px; font-family: 'Syne', sans-serif;
            font-weight: 600; letter-spacing: .08em;
            text-transform: uppercase; color: #BBB;
            text-decoration: none; transition: color .2s;
        }
        .back-link:hover { color: #E8001E; }

        .divider {
            height: 1px; background: #F0F0F0;
            margin: 24px 0;
        }
        .secured {
            display: flex; align-items: center; justify-content: center; gap: 6px;
            font-size: 11px; color: #CCC;
        }
    </style>
</head>
<body>

<div class="wrap">
    <div class="logo-area">
        <img src="https://res.cloudinary.com/djs5pi7ev/image/upload/q_auto/f_auto/v1775466723/LOGO_BR2026_vbixvo.png"
             alt="Bayan Run 2026">
        <p>Admin Panel · Akses Terbatas</p>
    </div>

    <div class="card">
        <h1 class="card-title">Selamat Datang 👋</h1>
        <p class="card-sub">Masuk untuk mengelola data seleksi pacer.</p>

        @if($errors->any())
        <div class="err-box">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink:0">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            {{ $errors->first() }}
        </div>
        @endif

        @if(session('error'))
        <div class="err-box">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}">
            @csrf
            <div class="field">
                <label class="label">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       placeholder="admin@bayanrun.com" required autofocus>
            </div>
            <div class="field">
                <label class="label">Password</label>
                <input type="password" name="password" placeholder="••••••••" required>
            </div>
            <div class="remember">
                <input type="checkbox" name="remember" id="rem">
                <label for="rem">Ingat saya selama 30 hari</label>
            </div>
            <button type="submit" class="btn">Masuk ke Dashboard →</button>
        </form>

        <div class="divider"></div>
        <div class="secured">
            <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"/>
            </svg>
            Halaman ini terenkripsi & aman
        </div>
    </div>

    <!--<a href="{{ route('candidate.register') }}" class="back-link">← Kembali ke Form Pendaftaran</a>-->
</div>

</body>
</html>