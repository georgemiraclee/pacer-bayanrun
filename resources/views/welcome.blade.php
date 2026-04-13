<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Bayan Run 2026 — Pendaftaran Pacer Segera Hadir. Balikpapan, Kalimantan Timur.">
    <title>Coming Soon — Bayan Run 2026</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
    :root {
        --red:        #E8001E;
        --red-deep:   #B50018;
        --red-light:  #FF2E47;
        --blue:       #003FB5;
        --blue-light: #1A5CDB;
        --white:      #ffffff;
        --bg:         #ffffff;
        --bg-2:       #f5f7fc;
        --bg-3:       #fdf5f6;
        --text-1:     #0f0b1a;
        --text-2:     #4a4a5a;
        --text-3:     #9a96a8;
        --border:     rgba(0,0,0,0.08);
        --font:       'Montserrat', sans-serif;
        --marquee-h:  38px;
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    html, body {
        width: 100%; height: 100%;
        font-family: var(--font);
        background: var(--bg);
        color: var(--text-1);
        overflow: hidden;
    }

    /* ─── LOADER ─── */
    #loader {
        position: fixed; inset: 0; z-index: 9999;
        background: var(--bg);
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        gap: 0;
    }
    .loader-logo-wrap {
        width: 120px; height: 120px;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 36px; opacity: 0;
        position: relative;
    }
    .loader-logo { width: 80px; height: auto; position: relative; z-index: 2; }
    .loader-ring {
        position: absolute; inset: 0;
        border-radius: 50%;
        animation: spin 1.4s linear infinite;
    }
    .loader-ring svg { width: 100%; height: 100%; }
    @keyframes spin { to { transform: rotate(360deg); } }

    .loader-bar-wrap {
        width: 200px; height: 2px;
        background: rgba(0,0,0,0.08);
        border-radius: 99px; overflow: hidden;
        margin-bottom: 14px; opacity: 0;
    }
    .loader-bar {
        height: 100%; width: 0%;
        background: linear-gradient(90deg, var(--red-deep), var(--red), var(--blue));
        border-radius: 99px;
        transition: width 0.05s linear;
    }
    .loader-pct {
        font-size: 10px; font-weight: 700; letter-spacing: 0.22em;
        color: var(--text-3); opacity: 0;
    }
    .loader-sub {
        position: absolute; bottom: 36px;
        font-size: 9px; font-weight: 600; letter-spacing: 0.22em;
        text-transform: uppercase; color: var(--text-3); opacity: 0;
    }

    /* ─── PAGE ─── */
    #page {
        position: fixed; inset: 0;
        display: flex; flex-direction: column;
        visibility: hidden; overflow: hidden;
        background: var(--bg);
    }

    /* Flag stripe top */
    #top-stripe {
        flex: 0 0 4px;
        background: linear-gradient(90deg,
            var(--red) 0%, var(--red) 33.33%,
            var(--white) 33.33%, var(--white) 66.66%,
            var(--blue) 66.66%, var(--blue) 100%);
        opacity: 0;
        transform: scaleX(0);
        transform-origin: left;
    }

    /* Background deco */
    .bg-blob { position: absolute; border-radius: 50%; pointer-events: none; z-index: 0; }
    .b1 { width: 560px; height: 560px; top: -180px; right: -120px; background: radial-gradient(circle, rgba(232,0,30,0.07) 0%, transparent 65%); animation: b1 18s ease-in-out infinite alternate; }
    .b2 { width: 480px; height: 480px; bottom: -100px; left: -140px; background: radial-gradient(circle, rgba(0,63,181,0.07) 0%, transparent 65%); animation: b2 22s ease-in-out infinite alternate; }
    .b3 { width: 280px; height: 280px; top: 42%; left: 52%; background: radial-gradient(circle, rgba(232,0,30,0.04) 0%, transparent 65%); animation: b2 14s ease-in-out infinite alternate-reverse; }
    @keyframes b1 { from{transform:translate(0,0) scale(1)} to{transform:translate(-25px,20px) scale(1.1)} }
    @keyframes b2 { from{transform:translate(0,0) scale(1)} to{transform:translate(18px,-18px) scale(1.08)} }

    .bg-dots {
        position: absolute; inset: 0; z-index: 0; pointer-events: none; opacity: 0;
        background-image: radial-gradient(circle, rgba(0,0,0,0.055) 1px, transparent 1px);
        background-size: 28px 28px;
    }

    /* Corner badge */
    #corner-logo { position: absolute; top: 20px; left: 24px; z-index: 6; opacity: 0; }
    #corner-logo img { height: 38px; width: auto; }
    #edition-tag {
        position: absolute; top: 28px; right: 24px; z-index: 6; opacity: 0;
        font-size: 9px; font-weight: 700; letter-spacing: 0.2em;
        text-transform: uppercase; color: var(--text-3);
    }

    /* Social */
    #socials {
        position: absolute; right: 24px;
        bottom: calc(var(--marquee-h) + 14px);
        display: flex; flex-direction: column; gap: 9px; z-index: 6; opacity: 0;
    }
    .soc-btn {
        width: 36px; height: 36px; border-radius: 10px;
        background: var(--bg); border: 1px solid var(--border);
        display: flex; align-items: center; justify-content: center;
        text-decoration: none; color: var(--text-3);
        transition: all 0.22s ease;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
    }
    .soc-btn:hover { background: var(--red); border-color: var(--red); color: #fff; transform: scale(1.1); }

    /* ─── SCROLL ─── */
    #scroll { position: relative; z-index: 5; flex: 1 1 auto; overflow-y: auto; overflow-x: hidden; scrollbar-width: none; -ms-overflow-style: none; }
    #scroll::-webkit-scrollbar { display: none; }

    #content {
        min-height: 100%;
        display: flex; flex-direction: column;
        align-items: center; text-align: center;
        width: 100%; max-width: 720px;
        padding: 64px 24px 24px;
        margin: 0 auto;
        justify-content: center;
    }

    /* Flag mini */
    .flag-mini { display: inline-flex; gap: 2px; margin-bottom: 16px; opacity: 0; transform: translateY(10px); }
    .flag-r { width: 20px; height: 13px; background: var(--red); border-radius: 2px 0 0 2px; }
    .flag-w { width: 20px; height: 13px; background: #fff; border: 1px solid #ddd; }
    .flag-b { width: 20px; height: 13px; background: var(--blue); border-radius: 0 2px 2px 0; }

    /* Eyebrow */
    .eyebrow {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 5px 16px 5px 7px; border-radius: 99px;
        background: rgba(232,0,30,0.08); border: 1px solid rgba(232,0,30,0.22);
        margin-bottom: 20px; opacity: 0; transform: translateY(14px);
    }
    .eyebrow-dot-wrap { width: 20px; height: 20px; border-radius: 50%; background: rgba(232,0,30,0.14); display: flex; align-items: center; justify-content: center; }
    .eyebrow-dot { width: 7px; height: 7px; border-radius: 50%; background: var(--red); animation: blink 2.4s ease infinite; }
    @keyframes blink { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:0.25;transform:scale(0.65)} }
    .eyebrow-text { font-size: 10px; font-weight: 700; letter-spacing: 0.18em; text-transform: uppercase; color: var(--red-deep); }

    /* Logo */
    .cs-logo { height: clamp(60px,10vw,90px); width: auto; display: block; margin: 0 auto 14px; opacity: 0; transform: scale(0.88) translateY(12px); }

    /* Headline */
    .headline { font-size: clamp(40px,10vw,86px); font-weight: 900; letter-spacing: -0.045em; line-height: 0.93; margin-bottom: 12px; opacity: 0; transform: translateY(36px); display: block; color: var(--text-1); }
    .headline em { font-style: normal; color: var(--red); }

    /* Accent line */
    #accent-line {
        width: 0; height: 3px; margin: 0 auto 10px; border-radius: 99px;
        background: linear-gradient(90deg, var(--red-deep), var(--red) 40%, var(--blue-light) 70%, var(--blue));
    }

    /* Tagline */
    .tagline { font-size: clamp(12px, 1.7vw, 14px); font-weight: 400; color: var(--text-2); line-height: 1.8; max-width: 400px; margin: 0 auto 26px; opacity: 0; transform: translateY(16px); }
    .tagline strong { color: var(--red); font-weight: 700; }

    /* ─── COUNTDOWN ─── */
    .countdown { display: flex; gap: 8px; align-items: center; justify-content: center; margin-bottom: 20px; opacity: 0; transform: translateY(16px); flex-wrap: nowrap; }
    .cd-unit { display: flex; flex-direction: column; align-items: center; }
    .cd-box {
        background: var(--bg); border: 1.5px solid #eaecf0; border-radius: 14px;
        padding: clamp(10px,1.8vw,16px) clamp(14px,2.5vw,22px) clamp(8px,1.4vw,12px);
        min-width: clamp(58px,9vw,74px); position: relative; overflow: hidden;
        box-shadow: 0 2px 14px rgba(0,0,0,0.05);
        transition: border-color 0.3s, transform 0.2s;
    }
    .cd-box::before { content: ''; position: absolute; top: 0; left: 15%; right: 15%; height: 2px; background: linear-gradient(90deg, transparent, rgba(232,0,30,0.35), transparent); }
    .cd-box:hover { border-color: rgba(232,0,30,0.3); transform: translateY(-2px); }
    .cd-num { font-size: clamp(22px, 4vw, 38px); font-weight: 900; color: var(--text-1); line-height: 1; letter-spacing: -0.02em; display: block; text-align: center; }
    .cd-lbl { font-size: clamp(7px,1.1vw,8.5px); font-weight: 700; color: var(--text-3); letter-spacing: 0.14em; text-transform: uppercase; margin-top: 6px; }
    .cd-sep { font-size: clamp(20px,3.5vw,28px); font-weight: 900; color: var(--red); opacity: 0.4; align-self: flex-start; margin-top: 12px; animation: sep 1.2s ease infinite; }
    @keyframes sep { 0%,100%{opacity:0.4} 50%{opacity:0.1} }

    /* Buttons */
    .btn-row { display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; margin-bottom: 26px; opacity: 0; transform: translateY(14px); }
    .btn-red {
        display: inline-flex; align-items: center; gap: 8px;
        font-family: var(--font); font-size: 10px; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase;
        color: #fff; text-decoration: none;
        background: var(--red); padding: 13px 26px; border-radius: 12px; border: none; cursor: pointer;
        box-shadow: 0 6px 24px rgba(232,0,30,0.3); transition: all 0.25s;
    }
    .btn-red:hover { background: var(--red-deep); transform: translateY(-2px); box-shadow: 0 10px 32px rgba(232,0,30,0.4); }
    .btn-blue {
        display: inline-flex; align-items: center; gap: 8px;
        font-family: var(--font); font-size: 10px; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase;
        color: var(--blue); text-decoration: none;
        background: var(--bg); padding: 13px 24px; border-radius: 12px;
        border: 1.5px solid rgba(0,63,181,0.28); cursor: pointer;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05); transition: all 0.22s;
    }
    .btn-blue:hover { background: rgba(0,63,181,0.06); transform: translateY(-1px); }

    /* Stats */
    .stats {
        display: flex; gap: 0; opacity: 0; transform: translateY(14px);
        background: var(--bg); border: 1.5px solid #eaecf0; border-radius: 18px;
        box-shadow: 0 2px 16px rgba(0,0,0,0.05); overflow: hidden;
        flex-wrap: wrap; justify-content: center; max-width: 420px; width: 100%;
    }
    .stat { padding: clamp(12px,1.8vw,16px) clamp(16px,3vw,30px); text-align: center; position: relative; flex: 1 0 auto; }
    .stat + .stat::before { content: ''; position: absolute; left: 0; top: 20%; bottom: 20%; width: 1px; background: #eaecf0; }
    .stat-val { display: block; font-size: clamp(15px,2.5vw,19px); font-weight: 900; color: var(--red); line-height: 1; }
    .stat-val.blue { color: var(--blue); }
    .stat-lbl { display: block; font-size: 8px; font-weight: 700; color: var(--text-3); letter-spacing: 0.12em; text-transform: uppercase; margin-top: 5px; }

    /* ─── MARQUEE ─── */
    #marquee-bar {
        position: relative; z-index: 10; flex: 0 0 var(--marquee-h); height: var(--marquee-h);
        background: #f7f8fc; border-top: 1px solid #eaecf0;
        display: flex; align-items: center; overflow: hidden; opacity: 0;
        mask-image: linear-gradient(90deg, transparent, #000 6%, #000 94%, transparent);
        -webkit-mask-image: linear-gradient(90deg, transparent, #000 6%, #000 94%, transparent);
    }
    .marquee-track { display: flex; white-space: nowrap; width: max-content; }
    .m-item { display: inline-flex; align-items: center; gap: 9px; font-size: 8.5px; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase; color: var(--text-3); padding: 0 20px; }
    .m-dot { width: 3px; height: 3px; border-radius: 50%; flex-shrink: 0; }
    .m-dot.r { background: var(--red); }
    .m-dot.b { background: var(--blue); }

    /* ─── RESPONSIVE ─── */
    @media (max-width: 600px) {
        #content { padding: 58px 16px 20px; }
        #corner-logo, #edition-tag, #socials { display: none; }
        .btn-row { flex-direction: column; align-items: center; }
        .stats { width: 100%; }
        .stat { flex: 1 0 40%; }
        .stat + .stat::before { display: none; }
    }
    @media (max-height: 600px) {
        #content { justify-content: flex-start; padding-top: 46px; }
        .tagline { display: none; }
    }
    </style>
</head>
<body>

<!-- LOADER -->
<div id="loader">
    <div class="loader-logo-wrap" id="llw">
        <div class="loader-ring" id="lring">
            <svg viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="rg" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%"   stop-color="#E8001E" stop-opacity="1"/>
                        <stop offset="50%"  stop-color="#003FB5" stop-opacity="1"/>
                        <stop offset="100%" stop-color="#E8001E" stop-opacity="0"/>
                    </linearGradient>
                </defs>
                <circle cx="60" cy="60" r="55" stroke="url(#rg)" stroke-width="1.5" stroke-linecap="round" stroke-dasharray="200 145"/>
            </svg>
        </div>
        <img src="https://res.cloudinary.com/djs5pi7ev/image/upload/q_auto/f_auto/v1775466723/LOGO_BR2026_vbixvo.png" alt="Bayan Run 2026" class="loader-logo">
    </div>
    <div class="loader-bar-wrap" id="lbw"><div class="loader-bar" id="lbar"></div></div>
    <div class="loader-pct" id="lpct">0%</div>
    <div class="loader-sub" id="lsub">Bayan Run 2026 &mdash; Balikpapan, Kalimantan Timur</div>
</div>

<!-- PAGE -->
<div id="page">
    <div class="bg-dots" id="bgdots"></div>
    <div class="bg-blob b1"></div>
    <div class="bg-blob b2"></div>
    <div class="bg-blob b3"></div>

    <div id="top-stripe"></div>

    <div id="corner-logo">
        <img src="https://res.cloudinary.com/djs5pi7ev/image/upload/v1774509112/BAYAN_R_-_SAMPING_fgnkyb.png" alt="Bayan Open">
    </div>
    <div id="edition-tag">Edisi 2026</div>

    <div id="socials">
        <a href="https://www.instagram.com/bayan_open/" class="soc-btn" title="Instagram" target="_blank">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
        </a>
    </div>

    <div id="scroll">
        <div id="content">

            <div class="eyebrow" id="eyebrow">
                <div class="eyebrow-dot-wrap"><div class="eyebrow-dot"></div></div>
                <span class="eyebrow-text">Segera Hadir</span>
            </div>

            <img src="https://res.cloudinary.com/djs5pi7ev/image/upload/q_auto/f_auto/v1775466723/LOGO_BR2026_vbixvo.png" alt="Bayan Run 2026" class="cs-logo" id="cslogo">

            <span class="headline" id="headline">COMING<br><em>SOON</em></span>

            <div id="accent-line"></div>

            <p class="tagline" id="tagline">
                Pendaftaran Pacer <strong>Bayan Run 2026</strong><br>
                Keep Moving, Keep Strong<br>
                Pantau terus IG <strong>@bayan_open</strong> untuk informasi pendaftaran
            </p>

            <div class="countdown" id="countdown">
                <div class="cd-unit"><div class="cd-box"><span class="cd-num" id="cd-d">--</span></div><span class="cd-lbl">Hari</span></div>
                <span class="cd-sep">:</span>
                <div class="cd-unit"><div class="cd-box"><span class="cd-num" id="cd-h">--</span></div><span class="cd-lbl">Jam</span></div>
                <span class="cd-sep">:</span>
                <div class="cd-unit"><div class="cd-box"><span class="cd-num" id="cd-m">--</span></div><span class="cd-lbl">Menit</span></div>
                <span class="cd-sep">:</span>
                <div class="cd-unit"><div class="cd-box"><span class="cd-num" id="cd-s">--</span></div><span class="cd-lbl">Detik</span></div>
            </div>



            <div class="btn-row" id="btnrow">
                <a href="https://www.instagram.com/bayan_open/" target="_blank" class="btn-red">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="white" stroke="none"/></svg>
                    Follow Instagram
                </a>
            </div>

            <div class="stats" id="stats">
                <div class="stat"><span class="stat-val">21K</span><span class="stat-lbl">Half Marathon</span></div>
                <div class="stat"><span class="stat-val">10K</span><span class="stat-lbl">Fun Run</span></div>
                <div class="stat"><span class="stat-val blue">5K</span><span class="stat-lbl">Fun Run</span></div>
                <div class="stat"><span class="stat-val blue">2.5K</span><span class="stat-lbl">Kid Dash</span></div>
            </div>

        </div>
    </div>

    <!-- MARQUEE -->
    <div id="marquee-bar">
        <div class="marquee-track" id="mtrack">
            <span class="m-item"><span class="m-dot r"></span>Bayan Run 2026</span>
            <span class="m-item"><span class="m-dot b"></span>Balikpapan</span>
            <span class="m-item"><span class="m-dot r"></span>Kalimantan Timur</span>
            <span class="m-item"><span class="m-dot b"></span>Pacer</span>
            <span class="m-item"><span class="m-dot r"></span>Keep Moving</span>
            <span class="m-item"><span class="m-dot b"></span>Keep Strong</span>
            <span class="m-item"><span class="m-dot r"></span>Running Event</span>
            <span class="m-item"><span class="m-dot b"></span>Segera Hadir</span>
            <span class="m-item"><span class="m-dot r"></span>Bayan Run 2026</span>
            <span class="m-item"><span class="m-dot b"></span>Balikpapan</span>
            <span class="m-item"><span class="m-dot r"></span>Kalimantan Timur</span>
            <span class="m-item"><span class="m-dot b"></span>Pacer</span>
            <span class="m-item"><span class="m-dot r"></span>Keep Moving</span>
            <span class="m-item"><span class="m-dot b"></span>Keep Strong</span>
            <span class="m-item"><span class="m-dot r"></span>Running Event</span>
            <span class="m-item"><span class="m-dot b"></span>Segera Hadir</span>
        </div>
    </div>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script>
/* COUNTDOWN */
(function(){
    var target = new Date('2026-04-15T08:00:00+08:00');
    function pad(n){ return String(n).padStart(2,'0'); }
    function tick(){
        var diff = Math.max(0, target - new Date());
        document.getElementById('cd-d').textContent = pad(Math.floor(diff/864e5));
        document.getElementById('cd-h').textContent = pad(Math.floor((diff%864e5)/36e5));
        document.getElementById('cd-m').textContent = pad(Math.floor((diff%36e5)/6e4));
        document.getElementById('cd-s').textContent = pad(Math.floor((diff%6e4)/1e3));
    }
    tick(); setInterval(tick, 1000);
})();

/* LOADER + REVEAL */
(function(){
    var loader = document.getElementById('loader');
    var llw    = document.getElementById('llw');
    var lbw    = document.getElementById('lbw');
    var lbar   = document.getElementById('lbar');
    var lpct   = document.getElementById('lpct');
    var lsub   = document.getElementById('lsub');
    var page   = document.getElementById('page');

    gsap.timeline()
        .to(llw,  {opacity:1, y:0, duration:0.65, ease:'power3.out'})
        .to(lbw,  {opacity:1, duration:0.4}, '-=0.25')
        .to(lpct, {opacity:1, duration:0.35}, '-=0.2')
        .to(lsub, {opacity:1, duration:0.45}, '-=0.15');

    var prog = {val:0};
    gsap.to(prog, {
        val: 100, duration: 1.05, ease: 'power1.inOut', delay: 0.45,
        onUpdate: function(){
            var v = Math.round(prog.val);
            lbar.style.width = v + '%';
            lpct.textContent = v + '%';
        },
        onComplete: function(){
            gsap.timeline({onComplete: revealPage})
                .to(loader, {opacity:0, duration:0.42, ease:'power2.inOut', delay:0.08});
        }
    });

    function revealPage(){
        loader.style.display = 'none';
        page.style.visibility = 'visible';

        gsap.timeline()
            .to('#bgdots',        {opacity:1, duration:0.8}, 0)
            .to('#top-stripe',    {opacity:1, scaleX:1, duration:1.1, ease:'power3.out'}, 0.05)
            .to(['#corner-logo','#edition-tag'], {opacity:1, stagger:0.1, duration:0.5, ease:'power2.out'}, 0.3)
            .to('#flagmini',      {opacity:1, y:0, duration:0.55, ease:'power3.out'}, 0.38)
            .to('#eyebrow',       {opacity:1, y:0, duration:0.6, ease:'power3.out'}, 0.46)
            .to('#cslogo',        {opacity:1, scale:1, y:0, duration:0.8, ease:'back.out(1.5)'}, 0.58)
            .to('#headline',      {opacity:1, y:0, duration:0.75, ease:'power3.out'}, 0.72)
            .to('#accent-line',   {width:'140px', duration:0.9, ease:'power2.out'}, 0.9)
            .to('#tagline',       {opacity:1, y:0, duration:0.6, ease:'power2.out'}, 0.98)
            .to('#countdown',     {opacity:1, y:0, duration:0.6, ease:'power2.out'}, 1.08)
            .to('#btnrow',        {opacity:1, y:0, duration:0.55, ease:'power2.out'}, 1.2)
            .to('#stats',         {opacity:1, y:0, duration:0.5, ease:'power2.out'}, 1.44)
            .to('#socials',       {opacity:1, duration:0.45, ease:'power2.out'}, 1.5)
            .to('#marquee-bar',   {opacity:1, duration:0.65, ease:'power2.out'}, 1.55)
            .to('.cd-num',        {scale:1.1, color:'#E8001E', stagger:0.06, duration:0.22, yoyo:true, repeat:1, ease:'power2.inOut'}, 1.1);

        setTimeout(startMarquee, 1800);

        document.addEventListener('mousemove', function(e){
            var mx = e.clientX/window.innerWidth - 0.5;
            var my = e.clientY/window.innerHeight - 0.5;
            gsap.to('.b1', {x:mx*24, y:my*20, duration:2.5, ease:'power2.out'});
            gsap.to('.b2', {x:mx*-20, y:my*-16, duration:3.0, ease:'power2.out'});
            gsap.to('.b3', {x:mx*32, y:my*24, duration:2.0, ease:'power2.out'});
        });
    }

    function startMarquee(){
        var track = document.getElementById('mtrack');
        if(!track) return;
        gsap.fromTo(track, {x:0}, {x:-(track.scrollWidth/2), duration:40, ease:'none', repeat:-1});
    }
})();
</script>
</body>
</html>