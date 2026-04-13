<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — Bayan Run 2026</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500&display=swap" rel="stylesheet">

    <style>
        :root { --red:#E8001E; }
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}

        body {
            font-family:'DM Sans',sans-serif;
            background:#0D0D0D;
            min-height:100vh;
            display:grid;
            place-items:center;
            padding:24px;
        }

        /* noise */
        body::before {
            content:'';
            position:fixed; inset:0;
            background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='300'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='300' height='300' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
            pointer-events:none;
        }

        .login-wrap {
            width:100%; max-width:420px;
            animation:up .5s ease both;
        }
        @keyframes up { from{opacity:0;transform:translateY(24px)} to{opacity:1;transform:translateY(0)} }

        .logo-area { text-align:center; margin-bottom:36px; }
        .logo-area img { height:44px; width:auto; object-fit:contain; margin-bottom:16px; }
        .logo-area p {
            font-family:'Syne',sans-serif; font-size:10px; font-weight:700;
            letter-spacing:.14em; text-transform:uppercase; color:#444;
        }

        .card {
            background:#161616;
            border:1px solid rgba(255,255,255,.07);
            border-radius:20px;
            padding:32px;
        }

        .card-title {
            font-family:'Syne',sans-serif;
            font-size:20px; font-weight:800;
            color:#fff; margin-bottom:6px;
        }
        .card-sub { font-size:13px; color:#555; margin-bottom:28px; }

        .field { margin-bottom:18px; }
        .label {
            display:block;
            font-family:'Syne',sans-serif; font-size:10px; font-weight:700;
            letter-spacing:.1em; text-transform:uppercase; color:#555;
            margin-bottom:7px;
        }
        input[type=email],input[type=password] {
            width:100%;
            background:#0D0D0D;
            border:1.5px solid rgba(255,255,255,.1);
            border-radius:10px;
            padding:13px 16px;
            font-family:'DM Sans',sans-serif;
            font-size:14px; color:#fff;
            outline:none;
            transition:border-color .18s, box-shadow .18s;
        }
        input[type=email]:focus,input[type=password]:focus {
            border-color:var(--red);
            box-shadow:0 0 0 3px rgba(232,0,30,.12);
        }
        input::placeholder { color:#444; }

        .remember {
            display:flex; align-items:center; gap:8px;
            margin-bottom:24px;
        }
        .remember input { width:16px; height:16px; accent-color:var(--red); cursor:pointer; }
        .remember label { font-size:13px; color:#555; cursor:pointer; }

        .btn {
            width:100%; padding:14px;
            background:var(--red); color:#fff; border:none;
            border-radius:12px;
            font-family:'Syne',sans-serif; font-size:12px; font-weight:700;
            letter-spacing:.1em; text-transform:uppercase;
            cursor:pointer; transition:all .2s;
        }
        .btn:hover { background:#C0001A; box-shadow:0 8px 30px rgba(232,0,30,.3); }

        .err-banner {
            background:rgba(232,0,30,.12);
            border:1px solid rgba(232,0,30,.25);
            border-radius:10px; padding:12px 16px;
            font-size:13px; color:#FF6B6B;
            margin-bottom:20px;
            display:flex; align-items:center; gap:10px;
        }

        .back {
            display:block; text-align:center;
            margin-top:20px; font-size:11px;
            font-family:'Syne',sans-serif; font-weight:600;
            letter-spacing:.08em; text-transform:uppercase;
            color:#333; text-decoration:none;
            transition:color .2s;
        }
        .back:hover { color:var(--red); }
    </style>
</head>
<body>

<div class="login-wrap">
    <div class="logo-area">
        <img src="https://res.cloudinary.com/djs5pi7ev/image/upload/q_auto/f_auto/v1775466723/LOGO_BR2026_vbixvo.png"
             alt="Bayan Run 2026">
        <p>Admin Panel · Restricted Access</p>
    </div>

    <div class="card">
        <h1 class="card-title">Selamat Datang</h1>
        <p class="card-sub">Masuk untuk mengakses dashboard seleksi pacer.</p>

        @if($errors->any())
        <div class="err-banner">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            {{ $errors->first() }}
        </div>
        @endif

        @if(session('error'))
        <div class="err-banner">
            {{ session('error') }}
        </div>
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
                <input type="checkbox" name="remember" id="remember">
                <label for="remember">Ingat saya selama 30 hari</label>
            </div>
            <button type="submit" class="btn">Masuk ke Dashboard</button>
        </form>
    </div>

    <a href="{{ route('candidate.register') }}" class="back">← Kembali ke Halaman Pendaftaran</a>
</div>

</body>
</html>