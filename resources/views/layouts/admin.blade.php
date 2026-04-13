<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin — Bayan Run 2026')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        :root {
            --red:    #E8001E;
            --dark:   #0D0D0D;
            --panel:  #111111;
            --border: rgba(255,255,255,.07);
            --text:   #CCCCCC;
            --muted:  #666;
            --white:  #FFFFFF;
        }
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}

        body {
            font-family:'DM Sans',sans-serif;
            background:#181818;
            color:var(--white);
            min-height:100vh;
            display:flex;
        }
        h1,h2,h3,.syne{font-family:'Syne',sans-serif;}

        /* ── Sidebar ── */
        .sidebar {
            width:220px; flex-shrink:0;
            background:var(--panel);
            border-right:1px solid var(--border);
            display:flex; flex-direction:column;
            position:fixed; left:0; top:0; bottom:0;
            z-index:100; overflow-y:auto;
        }
        .sidebar-logo {
            padding:22px 20px 18px;
            border-bottom:1px solid var(--border);
        }
        .sidebar-admin {
            padding:14px 20px;
            border-bottom:1px solid var(--border);
        }
        .sidebar-admin .name {
            font-family:'Syne',sans-serif; font-size:13px; font-weight:700; color:#fff;
        }
        .sidebar-admin .email {
            font-size:11px; color:var(--muted);
            white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
            max-width:175px; display:block;
        }
        .sidebar-nav {
            flex:1; padding:12px 10px;
            display:flex; flex-direction:column; gap:2px;
        }
        .nav-link {
            display:flex; align-items:center; gap:10px;
            padding:10px 12px; border-radius:10px;
            font-size:13px; font-family:'Syne',sans-serif; font-weight:600;
            color:var(--muted); text-decoration:none;
            transition:all .18s; cursor:pointer; border:none; background:none; width:100%; text-align:left;
        }
        .nav-link:hover { background:rgba(255,255,255,.05); color:#fff; }
        .nav-link.active { background:var(--red); color:#fff; }
        .nav-link svg { flex-shrink:0; }
        .sidebar-footer {
            padding:12px 10px;
            border-top:1px solid var(--border);
        }

        /* ── Main ── */
        .main { margin-left:220px; flex:1; display:flex; flex-direction:column; min-height:100vh; }

        .topbar {
            background:var(--panel);
            border-bottom:1px solid var(--border);
            padding:0 28px;
            height:60px;
            display:flex; align-items:center; justify-content:space-between;
            position:sticky; top:0; z-index:50;
        }
        .topbar-title {
            font-family:'Syne',sans-serif; font-size:14px; font-weight:700;
            color:#fff; letter-spacing:.02em;
        }
        .topbar-date { font-size:12px; color:var(--muted); }

        .page-content { padding:28px; flex:1; }

        /* ── Toast ── */
        .toast {
            position:fixed; top:18px; right:18px; z-index:9999;
            background:#fff; color:#0D0D0D;
            padding:13px 18px; border-radius:12px;
            font-size:13px; display:flex; align-items:center; gap:10px;
            box-shadow:0 8px 50px rgba(0,0,0,.4); max-width:320px;
        }
        .toast.err { background:var(--red); color:#fff; }
    </style>
</head>
<body>

{{-- ── Sidebar ─────────────────────────────────────── --}}
<aside class="sidebar">
    <div class="sidebar-logo">
        <img src="https://res.cloudinary.com/djs5pi7ev/image/upload/q_auto/f_auto/v1775466723/LOGO_BR2026_vbixvo.png"
             alt="Bayan Run 2026" style="height:50px; width:auto; object-fit:contain; display:block;">
    </div>

    <div class="sidebar-admin">
        <p style="font-size:9px; font-family:'Syne',sans-serif; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:var(--muted); margin-bottom:6px;">Logged in as</p>
        <p class="name">{{ Auth::guard('admin')->user()->name }}</p>
        <span class="email">{{ Auth::guard('admin')->user()->email }}</span>
    </div>

    <nav class="sidebar-nav">
        <a href="{{ route('admin.dashboard') }}"
           class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>

        <a href="{{ route('admin.export') }}"
           class="nav-link">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            Export CSV
        </a>

        <a href="{{ route('candidate.register') }}" target="_blank"
           class="nav-link">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
            Form Publik
        </a>
    </nav>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="nav-link" style="color:#E8001E;">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Logout
            </button>
        </form>
    </div>
</aside>

{{-- ── Main ──────────────────────────────────────────── --}}
<div class="main">
    <header class="topbar">
        <span class="topbar-title">@yield('title', 'Dashboard')</span>
        <span class="topbar-date">{{ now()->isoFormat('dddd, D MMMM Y') }}</span>
    </header>

    {{-- Toast --}}
    @if(session('success'))
    <div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,4000)"
         x-transition:leave="transition duration-300" x-transition:leave-end="opacity-0 translate-x-4"
         class="toast">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    <main class="page-content">
        @yield('content')
    </main>
</div>

</body>
</html>