<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Bayan Open 2026 — Turnamen Bulutangkis Bergengsi, Balikpapan, Kalimantan Timur. Segera Hadir.">
    <title>Coming Soon — Bayan Open 2026</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
    :root {
        --fire:        #ea6c0a;
        --fire-deep:   #c2500a;
        --fire-light:  #ff8c38;
        --gold:        #f5a623;
        --bg:          #faf7f4;
        --bg-2:        #f2ede7;
        --bg-card:     #ffffff;
        --border:      rgba(0,0,0,0.08);
        --border-warm: rgba(234,108,10,0.22);
        --text-primary:   #1a1208;
        --text-secondary: #6b5c4a;
        --text-muted:     #a89282;
        --font: 'Montserrat', sans-serif;
        --marquee-h: 38px;
        --shadow-card: 0 2px 20px rgba(0,0,0,0.07), 0 0 0 1px rgba(0,0,0,0.05);
        --shadow-fire: 0 8px 32px rgba(234,108,10,0.35);
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    html, body {
        width: 100%; height: 100%;
        font-family: var(--font);
        background: var(--bg);
        color: var(--text-primary);
        overflow: hidden;
    }

    /* ─── LOADER ─── */
    #loader {
        position: fixed; inset: 0; z-index: 9999;
        background: var(--bg);
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
    }
    .loader-logo-wrap {
        position: relative; width: 130px; height: 130px;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 40px; opacity: 0;
    }
    .loader-logo { width: 90px; height: auto; filter: drop-shadow(0 4px 20px rgba(234,108,10,0.35)); position: relative; z-index: 2; }
    .loader-ring { position: absolute; inset: 0; border-radius: 50%; }
    .loader-ring svg { width: 100%; height: 100%; position: absolute; inset: 0; }
    .loader-ring-inner { position: absolute; inset: 10px; border-radius: 50%; border: 1px solid rgba(234,108,10,0.15); }
    .loader-progress-wrap { width: 210px; height: 2px; background: rgba(0,0,0,0.08); border-radius: 99px; overflow: hidden; margin-bottom: 18px; opacity: 0; }
    .loader-bar { height: 100%; width: 0%; background: linear-gradient(90deg, var(--fire-deep), var(--fire), var(--gold)); border-radius: 99px; box-shadow: 0 0 10px rgba(234,108,10,0.55); }
    .loader-counter { font-size: 11px; font-weight: 700; letter-spacing: 0.22em; color: var(--text-muted); opacity: 0; }
    .loader-label { position: absolute; bottom: 44px; font-size: 9px; font-weight: 600; letter-spacing: 0.26em; text-transform: uppercase; color: var(--text-muted); opacity: 0; }
    .loader-particle { position: absolute; border-radius: 50%; pointer-events: none; opacity: 0; }

    /* ─── PAGE ─── */
    #cs-page {
        position: fixed; inset: 0;
        display: flex; flex-direction: column;
        visibility: hidden; overflow: hidden;
        background: var(--bg);
    }

    /* Background decorations */
    .bg-blob { position: absolute; border-radius: 50%; pointer-events: none; z-index: 0; }
    .bg-blob-1 { width: 600px; height: 600px; top: -200px; right: -150px; background: radial-gradient(circle, rgba(234,108,10,0.09) 0%, transparent 65%); animation: blob1 16s ease-in-out infinite alternate; }
    .bg-blob-2 { width: 500px; height: 500px; bottom: 0; left: -150px; background: radial-gradient(circle, rgba(245,166,35,0.07) 0%, transparent 65%); animation: blob2 20s ease-in-out infinite alternate; }
    .bg-blob-3 { width: 300px; height: 300px; top: 40%; left: 55%; background: radial-gradient(circle, rgba(234,108,10,0.055) 0%, transparent 65%); animation: blob2 13s ease-in-out infinite alternate-reverse; }
    @keyframes blob1 { from{transform:translate(0,0) scale(1)} to{transform:translate(-30px,30px) scale(1.12)} }
    @keyframes blob2 { from{transform:translate(0,0) scale(1)} to{transform:translate(20px,-20px) scale(1.08)} }

    .bg-dots { position: absolute; inset: 0; z-index: 0; pointer-events: none; opacity: 0; background-image: radial-gradient(circle, rgba(0,0,0,0.07) 1px, transparent 1px); background-size: 28px 28px; }

    /* Top fire accent */
    #cs-top-bar { position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, transparent 0%, var(--fire-deep) 20%, var(--fire) 50%, var(--gold) 70%, var(--fire) 85%, transparent 100%); z-index: 7; opacity: 0; transform: scaleX(0); transform-origin: left; }

    /* Corner */
    #cs-corner-logo { position: absolute; top: 22px; left: 28px; z-index: 6; opacity: 0; }
    #cs-corner-logo img { height: 40px; width: auto; filter: drop-shadow(0 2px 6px rgba(234,108,10,0.2)); }
    #cs-edition-tag { position: absolute; top: 30px; right: 28px; z-index: 6; opacity: 0; font-size: 9px; font-weight: 700; letter-spacing: 0.22em; text-transform: uppercase; color: var(--text-muted); }

    /* Socials */
    #cs-socials { position: absolute; right: 28px; bottom: calc(var(--marquee-h) + 16px); display: flex; flex-direction: column; gap: 10px; z-index: 6; opacity: 0; }
    .cs-social-btn { width: 36px; height: 36px; border-radius: 10px; background: var(--bg-card); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; text-decoration: none; color: var(--text-muted); transition: all 0.25s ease; box-shadow: var(--shadow-card); }
    .cs-social-btn:hover { background: var(--fire); border-color: var(--fire); color: #fff; transform: scale(1.1); box-shadow: var(--shadow-fire); }

    .shuttle-particle { position: absolute; pointer-events: none; z-index: 4; opacity: 0; }

    /* ─── SCROLL AREA ─── */
    #cs-scroll { position: relative; z-index: 5; flex: 1 1 auto; overflow-y: auto; overflow-x: hidden; scrollbar-width: none; -ms-overflow-style: none; }
    #cs-scroll::-webkit-scrollbar { display: none; }

    #cs-content {
        min-height: 100%;
        display: flex; flex-direction: column;
        align-items: center; text-align: center;
        width: 100%; max-width: 760px;
        padding: 68px 24px 24px;
        margin: 0 auto;
        justify-content: center;
    }

    /* Eyebrow */
    .cs-eyebrow { display: inline-flex; align-items: center; gap: 9px; padding: 5px 16px 5px 7px; border-radius: 99px; background: rgba(234,108,10,0.09); border: 1px solid rgba(234,108,10,0.24); margin-bottom: 22px; opacity: 0; transform: translateY(16px); }
    .eyebrow-dot-wrap { width: 22px; height: 22px; border-radius: 50%; background: rgba(234,108,10,0.16); display: flex; align-items: center; justify-content: center; }
    .eyebrow-dot { width: 7px; height: 7px; border-radius: 50%; background: var(--fire); box-shadow: 0 0 6px rgba(234,108,10,0.75); animation: blink 2.4s ease infinite; }
    @keyframes blink { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:0.35;transform:scale(0.75)} }
    .eyebrow-text { font-size: 10px; font-weight: 700; letter-spacing: 0.18em; text-transform: uppercase; color: var(--fire-deep); }

    /* Logo */
    .cs-logo { height: clamp(64px, 11vw, 96px); width: auto; display: block; margin: 0 auto 16px; filter: drop-shadow(0 4px 20px rgba(234,108,10,0.25)); opacity: 0; transform: scale(0.88) translateY(14px); }

    /* Headline */
    .cs-headline-wrap { margin-bottom: 12px; }
    .cs-headline { font-size: clamp(38px, 9vw, 84px); font-weight: 900; letter-spacing: -0.045em; line-height: 0.96; color: var(--text-primary); display: block; opacity: 0; transform: translateY(40px); }
    .cs-headline em { font-style: normal; color: var(--fire); }

    /* Tagline */
    .cs-tagline { font-size: clamp(12px, 1.8vw, 15px); font-weight: 400; color: var(--text-secondary); line-height: 1.78; max-width: 420px; margin: 0 auto 30px; opacity: 0; transform: translateY(18px); }

    /* Fire line */
    #cs-fire-line { width: 0; height: 2px; background: linear-gradient(90deg, transparent, var(--fire), var(--gold), var(--fire), transparent); margin: 0 auto 30px; border-radius: 99px; }

    /* ─── COUNTDOWN ─── */
    .cs-countdown { display: flex; gap: 8px; align-items: center; justify-content: center; margin-bottom: 32px; opacity: 0; transform: translateY(18px); flex-wrap: nowrap; }
    .cs-count-unit { display: flex; flex-direction: column; align-items: center; }
    .cs-count-box { background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; padding: clamp(10px,2vw,18px) clamp(14px,2.5vw,24px) clamp(8px,1.5vw,14px); min-width: clamp(60px,10vw,78px); position: relative; overflow: hidden; box-shadow: var(--shadow-card); transition: border-color 0.3s, box-shadow 0.3s, transform 0.2s; }
    .cs-count-box::before { content: ''; position: absolute; top: 0; left: 15%; right: 15%; height: 2px; background: linear-gradient(90deg, transparent, rgba(234,108,10,0.3), transparent); border-radius: 0 0 99px 99px; }
    .cs-count-box:hover { border-color: var(--border-warm); box-shadow: 0 4px 24px rgba(234,108,10,0.15), var(--shadow-card); transform: translateY(-2px); }
    .cs-count-num { font-size: clamp(24px, 4.5vw, 40px); font-weight: 800; color: var(--text-primary); line-height: 1; letter-spacing: -0.02em; display: block; text-align: center; transition: color 0.2s; }
    .cs-count-lbl { font-size: clamp(7px,1.2vw,9px); font-weight: 600; color: var(--text-muted); letter-spacing: 0.15em; text-transform: uppercase; margin-top: 7px; }
    .cs-count-sep { font-size: clamp(22px, 4vw, 30px); font-weight: 800; color: var(--fire); opacity: 0.45; align-self: flex-start; margin-top: 10px; line-height: 1; animation: sep-blink 1.2s ease infinite; }
    @keyframes sep-blink { 0%,100%{opacity:0.45} 50%{opacity:0.12} }

    /* ─── BUTTONS ─── */
    .cs-notify { display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; margin-bottom: 28px; opacity: 0; transform: translateY(16px); }
    .btn-fire { display: inline-flex; align-items: center; gap: 9px; font-family: var(--font); font-size: 10.5px; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: #fff; text-decoration: none; background: linear-gradient(135deg, var(--fire-light) 0%, var(--fire-deep) 100%); padding: 13px 28px; border-radius: 14px; border: none; cursor: pointer; box-shadow: var(--shadow-fire), inset 0 1px 0 rgba(255,255,255,0.22); transition: all 0.3s cubic-bezier(0.22,1,0.36,1); position: relative; overflow: hidden; }
    .btn-fire::before { content:''; position:absolute; inset:0; background:linear-gradient(135deg,rgba(255,255,255,0.14),transparent); pointer-events:none; }
    .btn-fire:hover { transform:translateY(-2px); box-shadow:0 12px 40px rgba(234,108,10,0.48), inset 0 1px 0 rgba(255,255,255,0.26); }
    .btn-ghost { display: inline-flex; align-items: center; gap: 8px; font-family: var(--font); font-size: 10.5px; font-weight: 600; letter-spacing: 0.10em; text-transform: uppercase; color: var(--text-secondary); text-decoration: none; background: var(--bg-card); padding: 13px 24px; border-radius: 14px; border: 1px solid var(--border); cursor: pointer; transition: all 0.25s ease; box-shadow: var(--shadow-card); }
    .btn-ghost:hover { background: var(--bg-2); border-color: var(--border-warm); color: var(--fire-deep); transform: translateY(-1px); }

    /* ─── STATS ─── */
    .cs-stats { display: flex; gap: 0; opacity: 0; transform: translateY(16px); background: var(--bg-card); border: 1px solid var(--border); border-radius: 20px; box-shadow: var(--shadow-card); overflow: hidden; flex-wrap: wrap; justify-content: center; }
    .cs-stat-cell { padding: clamp(12px,1.8vw,18px) clamp(18px,3vw,32px); text-align: center; position: relative; flex: 1 0 auto; }
    .cs-stat-cell + .cs-stat-cell::before { content: ''; position: absolute; left: 0; top: 20%; bottom: 20%; width: 1px; background: var(--border); }
    .cs-stat-val { display: block; font-size: clamp(17px,3vw,22px); font-weight: 800; color: var(--fire); line-height: 1; }
    .cs-stat-lbl { display: block; font-size: 9px; font-weight: 600; color: var(--text-muted); letter-spacing: 0.12em; text-transform: uppercase; margin-top: 5px; }

    /* ─── MARQUEE BAR ─── */
    #cs-marquee-bar { position: relative; z-index: 10; flex: 0 0 var(--marquee-h); height: var(--marquee-h); background: var(--bg-2); border-top: 1px solid var(--border); display: flex; align-items: center; overflow: hidden; opacity: 0; mask-image: linear-gradient(90deg, transparent, #000 6%, #000 94%, transparent); -webkit-mask-image: linear-gradient(90deg, transparent, #000 6%, #000 94%, transparent); }
    .cs-marquee-track { display: flex; white-space: nowrap; width: max-content; }
    .cs-marquee-item { display: inline-flex; align-items: center; gap: 10px; font-size: 9px; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase; color: var(--text-muted); padding: 0 22px; }
    .cs-marquee-item .dot { width: 3px; height: 3px; border-radius: 50%; background: var(--fire); opacity: 0.6; flex-shrink: 0; }

    /* ─── RESPONSIVE ─── */
    @media (max-width: 640px) {
        #cs-content { padding: 62px 18px 20px; }
        #cs-corner-logo, #cs-edition-tag, #cs-socials { display: none; }
        .cs-notify { flex-direction: column; align-items: center; }
        .cs-stats { width: 100%; }
        .cs-stat-cell { flex: 1 0 40%; }
        .cs-stat-cell + .cs-stat-cell::before { display: none; }
    }
    @media (max-height: 600px) {
        #cs-content { justify-content: flex-start; padding-top: 50px; }
        .cs-logo { height: 52px; }
        .cs-tagline { display: none; }
        #cs-fire-line { margin-bottom: 18px; }
    }
    </style>
</head>
<body>

<!-- LOADER -->
<div id="loader">
    <div class="loader-logo-wrap" id="loaderLogoWrap">
        <div class="loader-ring" id="loaderRing">
            <svg viewBox="0 0 130 130" fill="none" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="ringGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%"   stop-color="#ea6c0a" stop-opacity="1"/>
                        <stop offset="50%"  stop-color="#f5a623" stop-opacity="1"/>
                        <stop offset="100%" stop-color="#ea6c0a" stop-opacity="0"/>
                    </linearGradient>
                </defs>
                <circle cx="65" cy="65" r="60" stroke="url(#ringGrad)" stroke-width="1.5" stroke-linecap="round" stroke-dasharray="220 160"/>
            </svg>
        </div>
        <div class="loader-ring-inner"></div>
        <img src="https://res.cloudinary.com/djs5pi7ev/image/upload/q_auto/f_auto/v1775466723/LOGO_BR2026_vbixvo.png" alt="Bayan Open 2026" class="loader-logo">
    </div>
    <div class="loader-progress-wrap" id="loaderProgressWrap">
        <div class="loader-bar" id="loaderBar"></div>
    </div>
    <div class="loader-counter" id="loaderCounter">0%</div>
    <div class="loader-label" id="loaderLabel">Bayan Run 2026 &mdash; Balikpapan, Kalimantan Timur</div>
</div>

<!-- PAGE -->
<div id="cs-page">

    <div class="bg-dots" id="bg-dots"></div>
    <div class="bg-blob bg-blob-1"></div>
    <div class="bg-blob bg-blob-2"></div>
    <div class="bg-blob bg-blob-3"></div>

    <div id="cs-top-bar"></div>

    <div id="cs-corner-logo">
        <img src="https://res.cloudinary.com/djs5pi7ev/image/upload/v1774509112/BAYAN_R_-_SAMPING_fgnkyb.png" alt="Bayan Open">
    </div>
    <div id="cs-edition-tag">Edisi 2026</div>
 <!-- CONTENT 
    <div id="cs-socials">
        <a href="https://www.instagram.com/bayan_open/" class="cs-social-btn" title="Instagram">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
        </a>
        <a href="https://wa.me/6281234567890" class="cs-social-btn" title="WhatsApp">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"/></svg>
        </a>
    </div>-->

    <!-- CONTENT -->
    <div id="cs-scroll">
        <div id="cs-content">

            <div class="cs-eyebrow" id="cs-eyebrow">
                <div class="eyebrow-dot-wrap"><div class="eyebrow-dot"></div></div>
                <span class="eyebrow-text">Segera Hadir</span>
            </div>

        <img src="https://res.cloudinary.com/djs5pi7ev/image/upload/q_auto/f_auto/v1775466723/LOGO_BR2026_vbixvo.png" alt="Bayan Open 2026" class="cs-logo" id="cs-logo">

            <div class="cs-headline-wrap">
                <span class="cs-headline" id="cs-headline">COMING<br><em>SOON</em></span>
            </div>

            <p class="cs-tagline" id="cs-tagline">
               Pendaftaran Pacer  Bayan Run 2026<br>
              Keep Moving, Keep Strong<br>
                Pantau terus IG @bayan_open untuk informasi pendaftaran
            </p>

            <div id="cs-fire-line"></div>
                <!-- COUNTDOWN (OFF) -->
                
                <div class="cs-countdown" id="cs-countdown">
                    <div class="cs-count-unit">
                        <div class="cs-count-box"><span class="cs-count-num" id="cd-days">--</span></div>
                        <span class="cs-count-lbl">Hari</span>
                    </div>
                    <span class="cs-count-sep">:</span>
                    <div class="cs-count-unit">
                        <div class="cs-count-box"><span class="cs-count-num" id="cd-hours">--</span></div>
                        <span class="cs-count-lbl">Jam</span>
                    </div>
                    <span class="cs-count-sep">:</span>
                    <div class="cs-count-unit">
                        <div class="cs-count-box"><span class="cs-count-num" id="cd-mins">--</span></div>
                        <span class="cs-count-lbl">Menit</span>
                    </div>
                    <span class="cs-count-sep">:</span>
                    <div class="cs-count-unit">
                        <div class="cs-count-box"><span class="cs-count-num" id="cd-secs">--</span></div>
                        <span class="cs-count-lbl">Detik</span>
                    </div>
                </div>
               

                <!-- TEXT PENGGANTI -->
                <p style="margin-bottom: 32px; color: #a89282; font-size: 13px;">
                    24-29 Agustus 2026 &mdash; BSCC Dome, Balikpapan, Kalimantan Timur
                </p>

            <div class="cs-notify" id="cs-notify">
                <a href="https://www.instagram.com/bayan_open/" target="_blank" class="btn-ghost">
                    Follow Instagram
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
                </a>
            </div>
     <!-- 
            <div class="cs-stats" id="cs-stats">
                <div class="cs-stat-cell"><span class="cs-stat-val">22</span><span class="cs-stat-lbl">Kategori</span></div>
                <div class="cs-stat-cell"><span class="cs-stat-val">500+</span><span class="cs-stat-lbl">Peserta</span></div>
                <div class="cs-stat-cell"><span class="cs-stat-val">PBSI</span><span class="cs-stat-lbl">Sirknas C</span></div>
                <div class="cs-stat-cell"><span class="cs-stat-val">2026</span><span class="cs-stat-lbl">Edisi</span></div>
            </div>-->

        </div>
    </div>

    <!-- MARQUEE -->
    <div id="cs-marquee-bar">
        <div class="cs-marquee-track" id="cs-marquee-track">
            <span class="cs-marquee-item"><span class="dot"></span>Bayan Run 2026</span>
            <span class="cs-marquee-item"><span class="dot"></span>Balikpapan</span>
            <span class="cs-marquee-item"><span class="dot"></span>Kalimantan Timur</span>
            <span class="cs-marquee-item"><span class="dot"></span>PACER</span>
            <span class="cs-marquee-item"><span class="dot"></span>Keep Moving</span>
            <span class="cs-marquee-item"><span class="dot"></span>Keep Strong</span>
            <span class="cs-marquee-item"><span class="dot"></span>Running Event</span>
            <span class="cs-marquee-item"><span class="dot"></span>Segera Hadir</span>
            <span class="cs-marquee-item"><span class="dot"></span>Bayan Run 2026</span>
            <span class="cs-marquee-item"><span class="dot"></span>Balikpapan</span>
            <span class="cs-marquee-item"><span class="dot"></span>Kalimantan Timur</span>
            <span class="cs-marquee-item"><span class="dot"></span>PACER</span>
            <span class="cs-marquee-item"><span class="dot"></span>Keep Moving</span>
            <span class="cs-marquee-item"><span class="dot"></span>Keep Strong</span>
            <span class="cs-marquee-item"><span class="dot"></span>Running Event</span>
            <span class="cs-marquee-item"><span class="dot"></span>Segera Hadir</span>
        </div>
    </div>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script>
/* =========================
   COUNTDOWN (DISABLED)
   Aktifkan kembali dengan
   menghapus comment ini
========================= */

(function() {
    var target = new Date('2026-04-15T08:00:00+08:00');
    function pad(n) { return String(n).padStart(2, '0'); }
    function tick() {
        var diff = Math.max(0, target - new Date());
        document.getElementById('cd-days').textContent  = pad(Math.floor(diff / 864e5));
        document.getElementById('cd-hours').textContent = pad(Math.floor((diff % 864e5) / 36e5));
        document.getElementById('cd-mins').textContent  = pad(Math.floor((diff % 36e5) / 6e4));
        document.getElementById('cd-secs').textContent  = pad(Math.floor((diff % 6e4) / 1e3));
    }
    tick(); setInterval(tick, 1000);
})();


(function() {
    var loader  = document.getElementById('loader');
    var lWrap   = document.getElementById('loaderLogoWrap');
    var lRing   = document.getElementById('loaderRing');
    var progW   = document.getElementById('loaderProgressWrap');
    var bar     = document.getElementById('loaderBar');
    var counter = document.getElementById('loaderCounter');
    var label   = document.getElementById('loaderLabel');
    var page    = document.getElementById('cs-page');

    var colors = ['#ea6c0a','#f5a623','#c2500a','rgba(234,108,10,0.35)'];
    for (var i = 0; i < 20; i++) {
        var p = document.createElement('div');
        p.className = 'loader-particle';
        var sz = (Math.random()*3+2)+'px';
        p.style.cssText = 'left:'+Math.random()*100+'vw;top:'+Math.random()*100+'vh;width:'+sz+';height:'+sz+';background:'+colors[Math.floor(Math.random()*colors.length)];
        loader.appendChild(p);
        gsap.to(p,{opacity:Math.random()*0.4+0.05,y:(Math.random()-0.5)*80,x:(Math.random()-0.5)*60,duration:Math.random()*3.5+2.5,repeat:-1,yoyo:true,ease:'sine.inOut',delay:Math.random()*2});
    }

    gsap.timeline()
        .to(lWrap,  {opacity:1,y:0,duration:0.7,ease:'power3.out'})
        .to(progW,  {opacity:1,duration:0.4},'-=0.3')
        .to(counter,{opacity:1,duration:0.4},'-=0.25')
        .to(label,  {opacity:1,duration:0.5},'-=0.2');

    gsap.to(lRing,{rotation:360,duration:1.5,repeat:-1,ease:'none',transformOrigin:'center center'});
    gsap.to('.loader-logo',{filter:'drop-shadow(0 6px 26px rgba(234,108,10,0.5))',duration:1.2,repeat:-1,yoyo:true,ease:'sine.inOut',delay:0.4});

    var prog={val:0};
    gsap.to(prog,{val:100,duration:1.0,ease:'power1.inOut',delay:0.5,
        onUpdate:function(){var v=Math.round(prog.val);bar.style.width=v+'%';counter.textContent=v+'%';},
        onComplete:exitLoader});

    function exitLoader(){
        gsap.timeline({onComplete:revealPage})
            .to(bar,{boxShadow:'0 0 20px rgba(234,108,10,0.85)',duration:0.15,yoyo:true,repeat:1})
            .to('.loader-logo',{scale:1.08,duration:0.18,ease:'power2.out'})
            .to(loader,{opacity:0,duration:0.45,ease:'power2.inOut'},'+=0.06');
    }

    function revealPage(){
        loader.style.display='none';
        page.style.visibility='visible';

        gsap.timeline()
            .to('#bg-dots',       {opacity:1,duration:0.8},0)
            .to('#cs-top-bar',    {opacity:1,scaleX:1,duration:1.0,ease:'power3.out'},0.05)
            .to(['#cs-corner-logo','#cs-edition-tag'],{opacity:1,stagger:0.1,duration:0.55,ease:'power2.out'},0.3)
            .to('#cs-eyebrow',    {opacity:1,y:0,duration:0.65,ease:'power3.out'},0.4)
            .to('#cs-logo',       {opacity:1,scale:1,y:0,duration:0.85,ease:'back.out(1.4)'},0.55)
            .to('#cs-headline',   {opacity:1,y:0,duration:0.8,ease:'power3.out'},0.7)
            .to('#cs-tagline',    {opacity:1,y:0,duration:0.65,ease:'power2.out'},0.88)
            .to('#cs-fire-line',  {width:'160px',duration:0.9,ease:'power2.out'},0.96)
            .to('#cs-countdown',  {opacity:1,y:0,duration:0.65,ease:'power2.out'},1.06)
            .to('#cs-notify',     {opacity:1,y:0,duration:0.6,ease:'power2.out'},1.2)
            .to('#cs-stats',      {opacity:1,y:0,duration:0.55,ease:'power2.out'},1.33)
            .to('#cs-socials',    {opacity:1,duration:0.5,ease:'power2.out'},1.42)
            .to('#cs-marquee-bar',{opacity:1,duration:0.7,ease:'power2.out'},1.5)
            .to('.cs-count-num',  {scale:1.12,color:'#ea6c0a',stagger:0.06,duration:0.25,yoyo:true,repeat:1,ease:'power2.inOut'},1.1);

        setTimeout(spawnParticles,1700);
        setTimeout(startMarquee,1900);

        document.addEventListener('mousemove',function(e){
            var mx=e.clientX/window.innerWidth-0.5,my=e.clientY/window.innerHeight-0.5;
            gsap.to('.bg-blob-1',{x:mx*28,y:my*22,duration:2.5,ease:'power2.out'});
            gsap.to('.bg-blob-2',{x:mx*-22,y:my*-18,duration:3.0,ease:'power2.out'});
            gsap.to('.bg-blob-3',{x:mx*36,y:my*28,duration:2.0,ease:'power2.out'});
        });
    }

    function spawnParticles(){
        var emojis=['🏸','✦','·','◦','∘'];
        for(var i=0;i<8;i++){
            var el=document.createElement('div');
            el.className='shuttle-particle';
            el.textContent=emojis[i%emojis.length];
            el.style.left=(8+Math.random()*84)+'%';
            el.style.top=(8+Math.random()*84)+'%';
            el.style.fontSize=(Math.random()*10+8)+'px';
            el.style.color=Math.random()>0.5?'rgba(234,108,10,0.15)':'rgba(0,0,0,0.06)';
            page.appendChild(el);
            gsap.to(el,{opacity:Math.random()*0.28+0.04,y:(Math.random()-0.5)*110,x:(Math.random()-0.5)*70,rotation:(Math.random()-0.5)*40,duration:Math.random()*5+4,repeat:-1,yoyo:true,ease:'sine.inOut',delay:Math.random()*2});
        }
    }

    function startMarquee(){
        var track=document.getElementById('cs-marquee-track');
        if(!track)return;
        gsap.fromTo(track,{x:0},{x:-(track.scrollWidth/2),duration:42,ease:'none',repeat:-1});
    }
})();
</script>
</body>
</html>