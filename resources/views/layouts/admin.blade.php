<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — Bayan Run 2026</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        :root {
            --red:     #E8001E;
            --red-lt:  #FFF0F2;
            --red-mid: #FFCCD2;
            --gray-50: #F8F8F8;
            --gray-100:#F0F0F0;
            --gray-200:#E4E4E4;
            --gray-400:#A0A0A0;
            --gray-600:#5A5A5A;
            --gray-800:#1A1A1A;
            --white:   #FFFFFF;
            --shadow:  0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
            --shadow-md: 0 4px 20px rgba(0,0,0,.07);
            --sidebar-w: 240px;
            --topbar-h: 58px;
            --transition: .25s cubic-bezier(.4,0,.2,1);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--gray-50);
            color: var(--gray-800);
            min-height: 100vh;
            display: flex;
        }

        h1,h2,h3,.syne { font-family: 'Syne', sans-serif; }

        /* ══════════════════════════════════════
           SIDEBAR
        ══════════════════════════════════════ */
        .sidebar {
            width: var(--sidebar-w);
            flex-shrink: 0;
            background: var(--white);
            border-right: 1px solid var(--gray-200);
            display: flex;
            flex-direction: column;
            position: fixed;
            left: 0; top: 0; bottom: 0;
            z-index: 200;
            box-shadow: var(--shadow-md);
            transition: transform var(--transition);
        }

        .sidebar-logo {
            padding: 22px 20px 18px;
            border-bottom: 1px solid var(--gray-100);
            display: flex; align-items: center; gap: 12px;
        }
        .sidebar-logo img { height: 36px; width: auto; object-fit: contain; }

        .sidebar-admin-info {
            padding: 14px 20px;
            border-bottom: 1px solid var(--gray-100);
            background: var(--gray-50);
        }
        .sidebar-admin-info .role {
            font-family: 'Syne', sans-serif;
            font-size: 9px; font-weight: 700;
            letter-spacing: .12em; text-transform: uppercase;
            color: var(--red); margin-bottom: 4px;
        }
        .sidebar-admin-info .name {
            font-size: 13px; font-weight: 600; color: var(--gray-800);
        }
        .sidebar-admin-info .email {
            font-size: 11px; color: var(--gray-400);
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
            max-width: 195px; display: block; margin-top: 1px;
        }

        .sidebar-nav {
            flex: 1;
            padding: 12px 10px;
            display: flex; flex-direction: column; gap: 2px;
            overflow-y: auto;
        }

        .nav-section-label {
            font-family: 'Syne', sans-serif;
            font-size: 9px; font-weight: 700;
            letter-spacing: .12em; text-transform: uppercase;
            color: var(--gray-400);
            padding: 10px 10px 4px;
        }

        .nav-link {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 12px; border-radius: 10px;
            font-size: 13px; font-weight: 500;
            color: var(--gray-600);
            text-decoration: none;
            transition: all .15s;
            cursor: pointer; border: none; background: none;
            width: 100%; text-align: left;
        }
        .nav-link:hover {
            background: var(--gray-100);
            color: var(--gray-800);
        }
        .nav-link.active {
            background: var(--red-lt);
            color: var(--red);
            font-weight: 600;
        }
        .nav-link.active svg { color: var(--red); }
        .nav-link svg { flex-shrink: 0; color: var(--gray-400); }

        .sidebar-footer {
            padding: 10px;
            border-top: 1px solid var(--gray-100);
        }
        .nav-link.danger:hover {
            background: #FFF0F2;
            color: var(--red);
        }
        .nav-link.danger:hover svg { color: var(--red); }

        /* ══════════════════════════════════════
           SIDEBAR OVERLAY (mobile backdrop)
        ══════════════════════════════════════ */
        .sidebar-overlay {
            display: none;
            position: fixed; inset: 0; z-index: 199;
            background: rgba(0,0,0,.45);
            backdrop-filter: blur(3px);
            -webkit-backdrop-filter: blur(3px);
            animation: overlayFadeIn .2s ease forwards;
        }
        .sidebar-overlay.open { display: block; }
        @keyframes overlayFadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }

        /* ══════════════════════════════════════
           MAIN CONTENT
        ══════════════════════════════════════ */
        .main-wrap {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: margin-left var(--transition);
        }

        .topbar {
            background: var(--white);
            border-bottom: 1px solid var(--gray-200);
            padding: 0 28px;
            height: var(--topbar-h);
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 50;
            box-shadow: var(--shadow);
            gap: 12px;
        }
        .topbar-left {
            display: flex; align-items: center; gap: 12px;
        }
        .topbar-title {
            font-family: 'Syne', sans-serif;
            font-size: 14px; font-weight: 700;
            color: var(--gray-800);
        }
        .topbar-date {
            font-size: 12px; color: var(--gray-400);
            white-space: nowrap;
        }

        /* ── Hamburger Button ── */
        .hamburger-btn {
            display: none;
            align-items: center; justify-content: center;
            width: 36px; height: 36px;
            border: 1.5px solid var(--gray-200);
            border-radius: 9px; background: none;
            cursor: pointer; color: var(--gray-600);
            transition: all .15s;
            flex-shrink: 0;
        }
        .hamburger-btn:hover {
            background: var(--gray-100);
            color: var(--gray-800);
            border-color: var(--gray-400);
        }
        .hamburger-btn svg { display: block; }

        .page-content {
            padding: 28px;
            flex: 1;
        }

        /* ══════════════════════════════════════
           TOAST
        ══════════════════════════════════════ */
        .admin-toast {
            position: fixed; top: 18px; right: 18px; z-index: 9999;
            background: var(--gray-800); color: #fff;
            padding: 12px 18px; border-radius: 12px;
            font-size: 13px; font-weight: 500;
            display: flex; align-items: center; gap: 10px;
            box-shadow: 0 8px 40px rgba(0,0,0,.15);
            max-width: 340px;
            animation: toastPop .3s cubic-bezier(.34,1.4,.64,1) both;
        }
        @keyframes toastPop {
            from { opacity:0; transform:translateY(-10px) scale(.95); }
            to   { opacity:1; transform:translateY(0) scale(1); }
        }

        /* ══════════════════════════════════════
           RESPONSIVE — TABLET (≤ 1024px)
        ══════════════════════════════════════ */
        @media (max-width: 1024px) {
            :root { --sidebar-w: 220px; }
        }

        /* ══════════════════════════════════════
           RESPONSIVE — MOBILE (≤ 768px)
        ══════════════════════════════════════ */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: 260px;
                box-shadow: none;
            }
            .sidebar.open {
                transform: translateX(0);
                box-shadow: 4px 0 40px rgba(0,0,0,.18);
            }

            .main-wrap {
                margin-left: 0;
            }

            .hamburger-btn {
                display: flex;
            }

            .topbar {
                padding: 0 16px;
            }

            .topbar-date {
                display: none;
            }

            .page-content {
                padding: 16px;
            }

            .admin-toast {
                top: auto;
                bottom: 18px;
                right: 16px;
                left: 16px;
                max-width: none;
            }
        }

        /* ══════════════════════════════════════
           RESPONSIVE — SMALL MOBILE (≤ 480px)
        ══════════════════════════════════════ */
        @media (max-width: 480px) {
            .sidebar { width: 80vw; max-width: 300px; }
            .page-content { padding: 12px; }
        }
    </style>

    @stack('admin-styles')
</head>
<body>

{{-- ══ Sidebar Overlay Backdrop (mobile) ══ --}}
<div class="sidebar-overlay" id="sidebar-overlay"></div>

{{-- ══════════════════════════════════════
     SIDEBAR
══════════════════════════════════════ --}}
<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <img src="https://res.cloudinary.com/djs5pi7ev/image/upload/q_auto/f_auto/v1775466723/LOGO_BR2026_vbixvo.png"
             alt="Bayan Run 2026">
    </div>

    <div class="sidebar-admin-info">
        <p class="role">Admin Panel</p>
        <p class="name">{{ Auth::guard('admin')->user()->name }}</p>
        <span class="email">{{ Auth::guard('admin')->user()->email }}</span>
    </div>

    <nav class="sidebar-nav">
        <p class="nav-section-label">Menu</p>

        <a href="{{ route('admin.dashboard') }}"
           class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/>
            </svg>
            Dashboard
        </a>

        <a href="{{ route('admin.export') }}"
           class="nav-link">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Export CSV
        </a>

        <a href="{{ route('admin.interview.index') }}"
        class="nav-link {{ request()->routeIs('admin.interview.*') ? 'active' : '' }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
            </svg>
            Broadcast Interview
        </a>
    </nav>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="nav-link danger" style="color:#A0A0A0;">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Logout
            </button>
        </form>
    </div>
</aside>

{{-- ══════════════════════════════════════
     MAIN
══════════════════════════════════════ --}}
<div class="main-wrap">
    <header class="topbar">
        <div class="topbar-left">
            {{-- Hamburger — hanya muncul di mobile --}}
            <button class="hamburger-btn" id="sidebar-toggle" aria-label="Buka menu navigasi" aria-expanded="false" aria-controls="sidebar">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <span class="topbar-title">@yield('title', 'Dashboard')</span>
        </div>
        <span class="topbar-date">{{ now()->isoFormat('dddd, D MMMM Y') }}</span>
    </header>

    {{-- Toast --}}
    @if(session('success'))
    <div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,4000)"
         x-transition:leave="transition duration-300 ease-in"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="admin-toast">
        <svg width="16" height="16" fill="none" stroke="#4ADE80" viewBox="0 0 24 24" style="flex-shrink:0">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    <main class="page-content">
        @yield('content')
    </main>
</div>

@stack('admin-scripts')

<script>
(function () {
    const toggle  = document.getElementById('sidebar-toggle');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');

    function openSidebar() {
        sidebar.classList.add('open');
        overlay.classList.add('open');
        toggle.setAttribute('aria-expanded', 'true');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        sidebar.classList.remove('open');
        overlay.classList.remove('open');
        toggle.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
    }

    function toggleSidebar() {
        sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
    }

    toggle.addEventListener('click', toggleSidebar);
    overlay.addEventListener('click', closeSidebar);

    // Tutup otomatis saat klik nav link di mobile
    sidebar.querySelectorAll('a.nav-link').forEach(function (link) {
        link.addEventListener('click', function () {
            if (window.innerWidth <= 768) closeSidebar();
        });
    });

    // Tutup saat tekan Escape
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeSidebar();
    });

    // Reset state saat resize ke desktop
    window.addEventListener('resize', function () {
        if (window.innerWidth > 768) closeSidebar();
    });
})();
</script>
</body>
</html>