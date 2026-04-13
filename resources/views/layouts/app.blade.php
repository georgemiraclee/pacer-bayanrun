<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Bayan Run 2026')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        :root {
            --red:   #E8001E;
            --dark:  #0D0D0D;
            --gray:  #F3F2EE;
            --mid:   #8A8A8A;
            --white: #FFFFFF;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--gray);
            color: var(--dark);
            min-height: 100vh;
        }

        /* Noise grain */
        body::after {
            content:'';
            position:fixed; inset:0;
            background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='300'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='300' height='300' filter='url(%23n)' opacity='0.035'/%3E%3C/svg%3E");
            pointer-events:none; z-index:9999;
        }

        h1,h2,h3,.syne { font-family:'Syne',sans-serif; }

        /* ── Ticker ── */
        .ticker { background:var(--red); overflow:hidden; padding:7px 0; }
        .ticker-inner { display:flex; gap:48px; animation:tick 22s linear infinite; white-space:nowrap; }
        @keyframes tick { to { transform:translateX(-50%); } }
        .ticker-text {
            font-family:'Syne',sans-serif; font-size:10px; font-weight:700;
            letter-spacing:.14em; text-transform:uppercase; color:#fff;
        }

        /* ── Nav ── */
        .nav {
            background:rgba(255, 255, 255, 0.96);
            backdrop-filter:blur(24px);
            -webkit-backdrop-filter:blur(24px);
            position:sticky; top:0; z-index:200;
            border-bottom:1px solid rgba(255,255,255,.05);
        }
        .nav-inner {
            max-width:820px; margin:0 auto;
            padding:0 28px; height:68px;
            display:flex; align-items:center; justify-content:space-between;
        }
        .nav-badge {
            display:flex; align-items:center; gap:8px;
            background:rgba(255,255,255,.06);
            border:1px solid rgba(255,255,255,.1);
            padding:6px 14px; border-radius:100px;
            font-family:'Syne',sans-serif; font-size:10px;
            font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:#999;
        }
        .live-dot {
            width:6px; height:6px; border-radius:50%; background:var(--red);
            animation:blink 1.8s ease-in-out infinite;
        }
        @keyframes blink { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.4;transform:scale(1.5)} }

        /* ── Form wrapper ── */
        .form-shell {
            max-width:780px; margin:0 auto;
            padding:48px 24px 80px;
        }

        /* ── Page hero ── */
        .page-hero {
            text-align:center;
            margin-bottom:44px;
            animation:fadeUp .55s ease both;
        }
        @keyframes fadeUp { from{opacity:0;transform:translateY(22px)} to{opacity:1;transform:translateY(0)} }
        .page-hero h1 {
            font-size:clamp(32px,5vw,54px);
            font-weight:800;
            line-height:1.05;
            letter-spacing:-.02em;
            color:var(--dark);
        }
        .page-hero h1 span { color:var(--red); }
        .page-hero p {
            margin-top:12px; color:var(--mid);
            font-size:15px; font-weight:300; line-height:1.7;
        }
        .page-hero .steps {
            display:flex; justify-content:center; gap:6px;
            margin-top:24px; flex-wrap:wrap;
        }
        .step-chip {
            display:flex; align-items:center; gap:6px;
            background:#fff; border:1px solid #E5E5E5;
            padding:6px 14px; border-radius:100px;
            font-size:11px; font-weight:500; color:#666;
            font-family:'Syne',sans-serif;
        }
        .step-chip .num {
            width:18px; height:18px; background:var(--red);
            border-radius:50%; color:#fff; font-size:9px;
            font-weight:800; display:flex; align-items:center; justify-content:center;
        }

        /* ── Card ── */
        .card {
            background:#fff;
            border:1px solid rgba(0,0,0,.07);
            border-radius:20px;
            overflow:hidden;
            box-shadow:0 2px 40px rgba(0,0,0,.05);
            margin-bottom:20px;
            animation:fadeUp .5s ease both;
        }
        .card-head {
            background:var(--dark);
            padding:16px 28px;
            display:flex; align-items:center; gap:14px;
        }
        .card-num {
            width:30px; height:30px; background:var(--red);
            border-radius:50%; display:flex; align-items:center; justify-content:center;
            font-family:'Syne',sans-serif; font-size:11px; font-weight:800; color:#fff;
            flex-shrink:0;
        }
        .card-title {
            font-family:'Syne',sans-serif; font-size:11px; font-weight:700;
            letter-spacing:.1em; text-transform:uppercase; color:#fff;
        }
        .card-body { padding:28px; display:flex; flex-direction:column; gap:22px; }

        /* ── Fields ── */
        .field { display:flex; flex-direction:column; gap:6px; }
        .field-row { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
        @media(max-width:580px){ .field-row{grid-template-columns:1fr;} }

        .label {
            font-family:'Syne',sans-serif; font-size:10px; font-weight:700;
            letter-spacing:.1em; text-transform:uppercase; color:#444;
        }
        .label .req { color:var(--red); margin-left:2px; }
        .label .hint { font-family:'DM Sans',sans-serif; text-transform:none; letter-spacing:0; font-weight:400; color:#AAA; font-size:10px; }

        input[type=text],input[type=email],input[type=url],input[type=date],input[type=number],textarea,select {
            font-family:'DM Sans',sans-serif;
            font-size:14px;
            color:var(--dark);
            background:#FAFAFA;
            border:1.5px solid #E8E8E8;
            border-radius:10px;
            padding:12px 16px;
            width:100%;
            outline:none;
            transition:border-color .18s, box-shadow .18s, background .18s;
            appearance:none;
            -webkit-appearance:none;
        }
        input:focus,textarea:focus,select:focus {
            border-color:var(--red);
            background:#fff;
            box-shadow:0 0 0 3px rgba(232,0,30,.08);
        }
        input.err,textarea.err,select.err { border-color:var(--red); background:#fff8f8; }
        .err-msg { font-size:11px; color:var(--red); margin-top:2px; }
        textarea { resize:none; }

        /* ── Upload ── */
        .upload {
            border:2px dashed #DDD; border-radius:12px;
            padding:24px; text-align:center; cursor:pointer;
            transition:all .2s; background:#FAFAFA;
        }
        .upload:hover { border-color:var(--red); background:#fff8f8; }
        .upload.done  { border-color:#16A34A; background:#F0FDF4; border-style:solid; }
        .upload-icon { width:36px; height:36px; margin:0 auto 8px; color:#BBB; }
        .upload p { font-size:13px; color:#888; }
        .upload p strong { color:var(--dark); }

        /* ── Radio card ── */
        .radio-group { display:flex; gap:12px; }
        .radio-opt {
            flex:1; border:2px solid #E8E8E8; border-radius:12px;
            padding:14px 18px; cursor:pointer;
            display:flex; align-items:center; gap:10px;
            transition:all .18s; user-select:none;
        }
        .radio-opt:has(input:checked) { border-color:var(--red); background:#fff8f8; }
        .radio-opt input { display:none; }
        .radio-pip {
            width:20px; height:20px; border:2px solid #CCC;
            border-radius:50%; position:relative; flex-shrink:0;
            transition:all .18s;
        }
        .radio-opt:has(input:checked) .radio-pip {
            border-color:var(--red); background:var(--red);
        }
        .radio-opt:has(input:checked) .radio-pip::after {
            content:''; position:absolute; inset:4px;
            background:#fff; border-radius:50%;
        }
        .radio-label { font-family:'Syne',sans-serif; font-size:13px; font-weight:700; }
        .radio-sub   { font-size:11px; color:var(--mid); margin-top:1px; }

        /* ── Conditional ── */
        .cond-block {
            border-left:3px solid var(--red);
            padding:16px 0 4px 20px;
            display:flex; flex-direction:column; gap:18px;
        }

        /* ── Info box ── */
        .info-box {
            background:#F0F9FF; border:1px solid #BAE6FD;
            border-radius:10px; padding:14px 16px;
            font-size:13px; color:#0369A1; line-height:1.6;
        }
        .info-box.warning { background:#FFFBEB; border-color:#FDE68A; color:#92400E; }

        /* ── Error summary ── */
        .error-summary {
            background:#FFF5F5; border:1px solid #FECACA;
            border-radius:14px; padding:18px 20px;
            margin-bottom:24px;
            animation:fadeUp .3s ease both;
        }
        .error-summary h3 {
            font-family:'Syne',sans-serif; font-size:12px;
            font-weight:700; letter-spacing:.05em;
            text-transform:uppercase; color:var(--red); margin-bottom:10px;
        }
        .error-summary ul { list-style:none; display:flex; flex-direction:column; gap:5px; }
        .error-summary li { font-size:13px; color:#7F1D1D; display:flex; gap:8px; align-items:flex-start; }
        .error-summary li::before { content:'→'; color:var(--red); font-weight:700; flex-shrink:0; }

        /* ── Submit ── */
        .btn-submit {
            width:100%; padding:18px;
            background:var(--red); color:#fff; border:none; border-radius:14px;
            font-family:'Syne',sans-serif; font-size:13px; font-weight:700;
            letter-spacing:.1em; text-transform:uppercase;
            cursor:pointer; transition:all .2s;
            display:flex; align-items:center; justify-content:center; gap:10px;
        }
        .btn-submit:hover { background:#C40019; transform:translateY(-2px); box-shadow:0 12px 40px rgba(232,0,30,.28); }
        .btn-submit:active { transform:translateY(0); }

        /* ── Disclaimer ── */
        .disclaimer {
            background:var(--dark); color:#666;
            border-radius:14px; padding:18px 22px;
            font-size:12px; line-height:1.8;
            margin-top:4px;
        }
        .disclaimer span { color:#AAA; }

        /* ── Toast ── */
        .toast {
            position:fixed; top:20px; right:20px; z-index:9998;
            background:var(--dark); color:#fff;
            padding:14px 20px; border-radius:14px;
            font-size:13px; display:flex; align-items:center; gap:10px;
            box-shadow:0 8px 50px rgba(0,0,0,.25); max-width:340px;
        }
        .toast.err { background:var(--red); }

        /* ── Footer ── */
        .site-footer {
            background:var(--dark); text-align:center;
            padding:36px 24px;
            font-size:12px; color:#444;
        }
        .site-footer img { height:30px; opacity:.4; margin-bottom:12px; display:block; margin-inline:auto; }
    </style>

    @stack('styles')
</head>
<body>

{{-- Ticker --}}
<div class="ticker">
    <div class="ticker-inner">
        @foreach(range(1,12) as $i)
            <span class="ticker-text">Bayan Run 2026</span>
            <span class="ticker-text" style="opacity:.4">✦</span>
            <span class="ticker-text">Pendaftaran Pacer</span>
            <span class="ticker-text" style="opacity:.4">✦</span>
            <span class="ticker-text">Open Registration</span>
            <span class="ticker-text" style="opacity:.4">✦</span>
        @endforeach
    </div>
</div>

{{-- Nav --}}
<nav class="nav">
    <div class="nav-inner">
        <a href="{{ route('candidate.register') }}" style="display:block;">
            <img src="https://res.cloudinary.com/djs5pi7ev/image/upload/q_auto/f_auto/v1775466723/LOGO_BR2026_vbixvo.png"
                 alt="Bayan Run 2026" style="height:50px; width:auto; object-fit:contain; display:block;">
        </a>
        <div class="nav-badge">
            <div class="live-dot"></div>
            <span>Pendaftaran Dibuka</span>
        </div>
    </div>
</nav>

{{-- Toasts --}}
@if(session('success'))
<div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,4500)"
     x-transition:leave="transition duration-300 ease-in"
     x-transition:leave-end="opacity-0 translate-x-full"
     class="toast">
    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink:0">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
    </svg>
    <span>{{ session('success') }}</span>
</div>
@endif

@if(session('error'))
<div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,4500)"
     x-transition:leave="transition duration-300 ease-in"
     x-transition:leave-end="opacity-0 translate-x-full"
     class="toast err">
    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink:0">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
    </svg>
    <span>{{ session('error') }}</span>
</div>
@endif

<main style="position:relative; z-index:1;">
    @yield('content')
</main>

<footer class="site-footer">
    <img src="https://res.cloudinary.com/djs5pi7ev/image/upload/q_auto/f_auto/v1775466723/LOGO_BR2026_vbixvo.png" alt="Bayan Run 2026">
    <p>© 2026 Bayan Run · Sistem Pendaftaran Pacer Resmi</p>
</footer>

@stack('scripts')
</body>
</html>