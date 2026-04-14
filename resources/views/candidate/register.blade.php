@extends('layouts.app')
@section('title', 'Pendaftaran Pacer — Bayan Run 2026')

@push('styles')
<style>
/* ════════════════════════════════════════════════
   BASE VARIABLES — ALL BLUE, NO RED
════════════════════════════════════════════════ */
:root {
    --primary:        #2563EB;
    --primary-dark:   #1D4ED8;
    --primary-xdark:  #1E3A8A;
    --primary-light:  rgba(37,99,235,.08);
    --primary-border: rgba(37,99,235,.35);
    --primary-glow:   rgba(37,99,235,.15);
    --green:          #16A34A;
    --yellow:         #D97706;
    --radius-card:    16px;
    --radius-input:   10px;
}

/* ════════════════════════════════════════════════
   RESPONSIVE GRID
════════════════════════════════════════════════ */
.grid-2col {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    align-items: start;
}
@media (max-width: 600px) {
    .grid-2col { grid-template-columns: 1fr; gap: 12px; }
    .field-row  { flex-direction: column !important; }
}

/* ════════════════════════════════════════════════
   ANIMATIONS
════════════════════════════════════════════════ */
@keyframes fadeSlideUp {
    from { opacity:0; transform:translateY(14px); }
    to   { opacity:1; transform:translateY(0); }
}
@keyframes shimmerScan {
    0%   { background-position:-200% center; }
    100% { background-position:200% center; }
}
@keyframes shake {
    0%,100% { transform:translateX(0); }
    20%     { transform:translateX(-6px); }
    40%     { transform:translateX(6px); }
    60%     { transform:translateX(-4px); }
    80%     { transform:translateX(4px); }
}
@keyframes sheetIn  { from{transform:translateY(100%);opacity:0} to{transform:translateY(0);opacity:1} }
@keyframes sheetOut { from{transform:translateY(0);opacity:1}    to{transform:translateY(100%);opacity:0} }
@keyframes bdIn     { from{opacity:0} to{opacity:1} }
@keyframes bdOut    { from{opacity:1} to{opacity:0} }
@keyframes toastIn  { from{opacity:0;transform:translateY(-10px) scale(.96)} to{opacity:1;transform:translateY(0) scale(1)} }
@keyframes popIn    { from{opacity:0;transform:scale(.88)} to{opacity:1;transform:scale(1)} }
@keyframes pulseGreen {
    0%,100% { box-shadow: 0 0 0 0 rgba(22,163,74,.3); }
    50%     { box-shadow: 0 0 0 6px rgba(22,163,74,.0); }
}

/* ════════════════════════════════════════════════
   KTP OCR CARD
════════════════════════════════════════════════ */
.ktp-ocr-card {
    background: #fff;
    border: 2px solid #E8E8E8;
    border-radius: var(--radius-card);
    padding: 22px;
    transition: border-color .25s, box-shadow .25s, background .25s;
}
.ktp-ocr-card.scanned {
    border-color: var(--green);
    background: #F0FDF4;
    box-shadow: 0 0 0 3px rgba(22,163,74,.08);
}
.ktp-ocr-card.scan-error {
    border-color: var(--primary);
    background: var(--primary-light);
    animation: shake .4s ease;
}
@media (max-width: 600px) { .ktp-ocr-card { padding: 14px; } }

/* Dropzone */
.ktp-dropzone {
    border: 2px dashed #DDD;
    border-radius: 12px;
    padding: 28px 20px;
    text-align: center;
    cursor: pointer;
    transition: all .2s;
    background: #FAFAFA;
    position: relative;
}
.ktp-dropzone:hover,
.ktp-dropzone.drag-over { border-color: var(--primary); background: var(--primary-light); }
.ktp-dropzone.has-file  { border-color: var(--green); border-style: solid; background: #F0FDF4; }
.ktp-dropzone .dz-icon {
    width:44px; height:44px; margin:0 auto 10px;
    background: var(--primary-light); border-radius:12px;
    display:flex; align-items:center; justify-content:center;
}
.ktp-dropzone .dz-text    { font-size:13px; font-weight:500; color:#666; }
.ktp-dropzone .dz-subtext { font-size:11px; color:#BBB; margin-top:4px; }

/* Preview */
.ktp-preview-wrap { display:none; position:relative; text-align:center; }
.ktp-preview-wrap.show { display:block; animation:fadeSlideUp .25s ease both; }
.ktp-preview-img {
    max-height:140px; max-width:100%; border-radius:10px; object-fit:contain;
    box-shadow:0 4px 20px rgba(0,0,0,.12); margin:0 auto; display:block;
}
.ktp-remove-btn {
    position:absolute; top:-8px; right:calc(50% - 70px - 8px);
    width:24px; height:24px; border-radius:50%;
    background: var(--primary); border:none; cursor:pointer;
    display:flex; align-items:center; justify-content:center; transition:background .15s;
}
.ktp-remove-btn:hover { background: var(--primary-dark); }

/* Scan button */
.btn-scan {
    display:none; width:100%; margin-top:12px; padding:11px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color:#fff; border:none; border-radius:10px;
    font-family:'Syne',sans-serif; font-size:12px; font-weight:700;
    letter-spacing:.08em; text-transform:uppercase; cursor:pointer; transition:all .2s;
    align-items:center; justify-content:center; gap:8px;
    box-shadow: 0 4px 18px rgba(37,99,235,.25);
}
.btn-scan:hover { background: linear-gradient(135deg, var(--primary-dark), var(--primary-xdark)); transform:translateY(-1px); }
.btn-scan.show  { display:flex; animation:fadeSlideUp .2s ease both; }

/* Loading bar */
.scan-loading { display:none; margin-top:12px; text-align:center; padding:8px 0; }
.scan-loading.show { display:block; }
.scan-loading p { font-size:11px; color:var(--primary); font-weight:600; margin-bottom:6px; }
.scan-bar { height:3px; border-radius:99px; overflow:hidden; background:var(--primary-light); }
.scan-bar-inner {
    height:100%; width:40%;
    background: linear-gradient(90deg,transparent,var(--primary),transparent);
    background-size:200% 100%; animation:shimmerScan 1.2s ease infinite;
}

/* Scan badge */
.scan-badge {
    display:none; align-items:center; gap:5px; padding:3px 10px; border-radius:100px;
    font-family:'Syne',sans-serif; font-size:10px; font-weight:700;
    background:rgba(22,163,74,.1); border:1px solid rgba(22,163,74,.3); color:var(--green);
}
.scan-badge.show { display:inline-flex; animation:popIn .2s ease both; }

/* KTP Data panel */
.ktp-data-panel {
    display:none; margin-top:16px; background:#fff;
    border:1.5px solid #E8E8E8; border-radius:12px; overflow:hidden;
    animation:fadeSlideUp .3s ease both;
}
.ktp-data-panel.show  { display:block; }
.ktp-data-panel.valid { border-color:rgba(22,163,74,.4); background:#F9FFFB; }
.ktp-data-header {
    background:#F5F5F5; border-bottom:1px solid #EBEBEB; padding:9px 16px;
    display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:4px;
}
.ktp-data-header span { font-family:'Syne',sans-serif; font-size:9px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:#888; }
.ktp-edit-hint { font-size:10px; color:var(--primary); font-style:italic; }
.ktp-row { display:flex; align-items:center; gap:10px; padding:9px 16px; border-bottom:1px solid #F0F0F0; min-height:38px; flex-wrap:wrap; }
.ktp-row:last-child { border-bottom:none; }
.ktp-lbl { font-family:'Syne',sans-serif; font-size:9px; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:#AAA; min-width:86px; flex-shrink:0; }
.ktp-val { flex:1; font-size:13px; color:#222; font-weight:500; padding:3px 8px; border-radius:7px; border:1px solid transparent; cursor:pointer; transition:all .15s; word-break:break-word; }
.ktp-val:hover { background:var(--primary-light); border-color:var(--primary-border); }
.ktp-val:hover::after { content:' ✏'; font-size:9px; color:var(--primary); opacity:.6; margin-left:3px; }
.ktp-val.empty  { color:#CCC; font-style:italic; font-weight:400; font-size:12px; }
.ktp-val.edited { color:var(--yellow); border-color:rgba(217,119,6,.3); background:rgba(217,119,6,.04); }
.ktp-inp {
    display:none; flex:1; background:var(--primary-light);
    border:1.5px solid var(--primary-border); border-radius:7px;
    color:#111; font-size:13px; font-weight:600; padding:3px 10px; outline:none; min-width:0;
    transition:border-color .15s, box-shadow .15s;
}
.ktp-inp:focus { border-color:var(--primary); box-shadow:0 0 0 2px rgba(37,99,235,.12); }
.gender-badge { flex:1; font-size:12px; font-weight:700; padding:3px 10px; border-radius:7px; border:1px solid; display:inline-block; }
.gender-l { color:#2563EB; background:rgba(37,99,235,.07); border-color:rgba(37,99,235,.25); }
.gender-p { color:#7C3AED; background:rgba(124,58,237,.07); border-color:rgba(124,58,237,.25); }
.gender-u { color:#AAA; background:transparent; border-color:#EEE; font-style:italic; font-weight:400; }

/* ════════════════════════════════════════════════
   UNIFIED UPLOAD ZONE
   FIX: Hapus onclick dari label — for= saja sudah cukup
════════════════════════════════════════════════ */
.upload-zone {
    border: 2px dashed #D0D0D0;
    border-radius: 12px;
    padding: 16px 12px;
    text-align: center;
    cursor: pointer;
    transition: all .2s;
    background: #fff;
    display: block;
    user-select: none;
    -webkit-user-select: none;
    box-shadow: 0 1px 4px rgba(0,0,0,.05);
}
.upload-zone:hover {
    border-color: var(--primary);
    background: var(--primary-light);
    box-shadow: 0 0 0 3px var(--primary-glow);
}
.upload-zone.done {
    border-color: var(--green);
    border-style: solid;
    background: #F0FDF4;
    animation: pulseGreen .6s ease;
}
.upload-zone.err {
    border-color: var(--primary);
    background: var(--primary-light);
}
.upload-zone-icon {
    width: 36px; height: 36px; margin: 0 auto 8px;
    background: var(--primary-light); border-radius: 10px;
    display: flex; align-items: center; justify-content: center; transition: background .2s;
}
.upload-zone.done .upload-zone-icon { background: rgba(22,163,74,.12); }
.upload-zone-name {
    font-size: 11px; font-weight: 600; color: #888;
    margin-top: 4px; word-break: break-all; max-width: 100%; overflow: hidden;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
}
.upload-zone.done .upload-zone-name { color: var(--green); }
.upload-zone.err  .upload-zone-name { color: var(--primary); }

/* Thumbnail */
.upload-thumb-wrap { display:none; position:relative; margin-top:10px; }
.upload-thumb-wrap.show { display:block; }
.upload-thumb { width:100%; max-height:100px; object-fit:cover; border-radius:8px; display:block; border:1px solid rgba(22,163,74,.2); }
.upload-thumb-remove {
    position:absolute; top:-6px; right:-6px; width:20px; height:20px; border-radius:50%;
    background: var(--primary); border:none; cursor:pointer;
    display:flex; align-items:center; justify-content:center; box-shadow:0 2px 6px rgba(0,0,0,.15);
}
.upload-thumb-remove:hover { background: var(--primary-dark); }

/* Persisted badge */
.persisted-badge {
    display:inline-flex; align-items:center; gap:5px; padding:4px 10px; border-radius:100px; margin-top:6px;
    font-size:10px; font-weight:700; font-family:'Syne',sans-serif;
    background:rgba(22,163,74,.1); border:1px solid rgba(22,163,74,.3); color:var(--green);
}

/* Mileage block */
.mileage-block {
    background: #FAFAFA; border: 1px solid #EEE; border-radius: 14px;
    padding: 18px; margin-bottom: 12px; transition: border-color .2s, background .2s;
}
.mileage-block.uploaded { border-color: rgba(22,163,74,.4); background: #F9FFFB; }
@media (max-width: 600px) { .mileage-block { padding: 14px; } }

/* ════════════════════════════════════════════════
   JARAK FAVORIT CARDS — clear bordered checkbox cards
════════════════════════════════════════════════ */
.dist-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}
@media (max-width: 480px) { .dist-grid { grid-template-columns: 1fr; } }

.dist-card {
    display: flex;
    align-items: center;
    gap: 12px;
    background: #FAFAFA;
    border: 2px solid #D0D0D0;
    border-radius: 12px;
    padding: 14px 16px;
    cursor: pointer;
    transition: all .2s;
    box-shadow: 0 1px 4px rgba(0,0,0,.06);
    -webkit-tap-highlight-color: transparent;
    user-select: none;
    position: relative;
}
.dist-card:hover {
    border-color: var(--primary);
    background: var(--primary-light);
    box-shadow: 0 0 0 3px var(--primary-glow);
    transform: translateY(-1px);
}
.dist-card.selected {
    border-color: var(--primary);
    background: var(--primary-light);
    box-shadow: 0 0 0 3px var(--primary-glow);
}
.dist-check {
    width: 22px; height: 22px; flex-shrink: 0;
    border: 2px solid #CCC; border-radius: 5px;
    display: flex; align-items: center; justify-content: center;
    transition: all .18s; background: #fff;
}
.dist-card.selected .dist-check { background: var(--primary); border-color: var(--primary); }
.dist-check svg { opacity:0; transform:scale(.6); transition:opacity .15s,transform .15s; }
.dist-card.selected .dist-check svg { opacity:1; transform:scale(1); }
.dist-label { font-family:'Syne',sans-serif; font-size:13px; font-weight:700; color:#333; transition: color .2s; }
.dist-card.selected .dist-label { color: var(--primary); }

/* ════════════════════════════════════════════════
   BOTTOM SHEET
════════════════════════════════════════════════ */
.sheet-backdrop {
    display:none; position:fixed; inset:0; z-index:1000;
    background:rgba(0,0,0,.5); backdrop-filter:blur(3px);
}
.sheet-backdrop.show   { display:block; animation:bdIn .2s ease both; }
.sheet-backdrop.hiding { animation:bdOut .2s ease both; }
.upload-sheet {
    display:none; position:fixed; bottom:0; left:0; right:0; z-index:1001;
    background:#0D0D0D; border-top:2px solid rgba(37,99,235,.2);
    border-radius:22px 22px 0 0; padding:0 0 28px;
    max-width:540px; margin:0 auto; box-shadow:0 -20px 60px rgba(0,0,0,.5);
}
.upload-sheet.show   { display:block; animation:sheetIn .28s cubic-bezier(.34,1.3,.64,1) both; }
.upload-sheet.hiding { animation:sheetOut .2s ease both; }
.sheet-handle { width:40px; height:4px; border-radius:99px; background:rgba(255,255,255,.15); margin:14px auto 20px; }
.sheet-title { font-family:'Syne',sans-serif; font-size:10px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:rgba(255,255,255,.3); text-align:center; margin-bottom:18px; }
.sheet-opts { display:grid; grid-template-columns:repeat(3,1fr); gap:10px; padding:0 16px; }
.sheet-opt {
    display:flex; flex-direction:column; align-items:center; gap:8px; padding:16px 8px;
    border-radius:16px; border:1.5px solid; cursor:pointer; font-family:'Syne',sans-serif;
    font-size:11px; font-weight:700; text-align:center; transition:all .15s; background:none;
    -webkit-tap-highlight-color:transparent;
}
.sheet-opt:active { transform:scale(.95); }
.sheet-opt-icon { width:44px; height:44px; border-radius:14px; display:flex; align-items:center; justify-content:center; }
.opt-camera { color:#3B82F6; border-color:rgba(59,130,246,.35); background:rgba(59,130,246,.08); }
.opt-camera .sheet-opt-icon { background:rgba(59,130,246,.15); }
.opt-foto   { color:#60A5FA; border-color:rgba(96,165,250,.28); background:rgba(96,165,250,.06); }
.opt-foto   .sheet-opt-icon { background:rgba(96,165,250,.12); }
.opt-file   { color:#93C5FD; border-color:rgba(147,197,253,.2); background:rgba(147,197,253,.04); }
.opt-file   .sheet-opt-icon { background:rgba(147,197,253,.1); }
.sheet-cancel {
    display:block; width:calc(100% - 32px); margin:14px 16px 0; padding:13px; border-radius:14px;
    border:1px solid rgba(255,255,255,.1); background:rgba(255,255,255,.04);
    color:rgba(255,255,255,.4); font-size:13px; font-weight:700; text-align:center; cursor:pointer; transition:all .15s;
}
.sheet-cancel:hover { background:rgba(255,255,255,.08); color:rgba(255,255,255,.7); }

/* ════════════════════════════════════════════════
   TOAST — auto dismiss 4s
════════════════════════════════════════════════ */
#pacerToast {
    position:fixed; top:80px; right:20px; z-index:9999;
    max-width:340px; padding:12px 18px; border-radius:12px;
    font-size:12px; font-weight:600; line-height:1.5; display:none;
    box-shadow:0 8px 40px rgba(0,0,0,.2); pointer-events:none;
}
.toast-success { background:#0D0D0D; border:1px solid rgba(22,163,74,.4); color:#22C55E; }
.toast-warn    { background:#0D0D0D; border:1px solid rgba(234,179,8,.4); color:#EAB308; }
.toast-info    { background:#0D0D0D; border:1px solid rgba(37,99,235,.4); color:#60A5FA; }
.toast-error   { background:#0D0D0D; border:1px solid rgba(37,99,235,.5); color:#93C5FD; }
@media (max-width: 600px) {
    #pacerToast { left:16px; right:16px; max-width:100%; top:auto; bottom:20px; }
}

/* ════════════════════════════════════════════════
   PERNYATAAN — ALL BLUE
════════════════════════════════════════════════ */
.pernyataan-box {
    background: var(--primary-light); border: 1px solid var(--primary-border);
    border-radius: 12px; padding: 18px 20px;
}
.pernyataan-text { font-size:13px; font-weight:600; color:#1E3A8A; line-height:1.7; margin-bottom:14px; }
.pernyataan-label { display:flex; align-items:flex-start; gap:12px; cursor:pointer; -webkit-tap-highlight-color:transparent; }
.pernyataan-check-box {
    width:24px; height:24px; flex-shrink:0; margin-top:1px;
    border:2.5px solid var(--primary); border-radius:6px; background:#fff;
    display:flex; align-items:center; justify-content:center;
    transition:background .18s,border-color .18s;
    box-shadow:0 0 0 3px rgba(37,99,235,.1);
}
.pernyataan-check-box.checked { background:var(--primary); border-color:var(--primary); box-shadow:0 0 0 3px rgba(37,99,235,.18); }
.pernyataan-check-box svg { opacity:0; transform:scale(.6); transition:opacity .15s,transform .15s; }
.pernyataan-check-box.checked svg { opacity:1; transform:scale(1); }
.pernyataan-check-text { font-size:14px; font-weight:700; color:#1E3A8A; line-height:1.5; }

/* ════════════════════════════════════════════════
   RESPONSIVE
════════════════════════════════════════════════ */
@media (max-width: 480px) {
    .sheet-opts { gap:8px; padding:0 10px; }
    .sheet-opt  { padding:12px 4px; font-size:10px; }
    .sheet-opt-icon { width:36px; height:36px; border-radius:10px; }
    .ktp-lbl { min-width:72px; font-size:8px; }
}
</style>
@endpush

@section('content')
<div class="form-shell" x-data="regForm()">

    {{-- Hero --}}
    <div class="page-hero">
        <h1>Daftar Jadi <span>Pacer</span></h1>
        <p>Isi seluruh data dengan benar dan jujur.<br>Ketidakjujuran data berakibat diskualifikasi permanen.</p>
        <div class="steps" style="margin-top:20px;flex-wrap:wrap;">
            @foreach(['Data Pribadi','Full Marathon','Half Marathon','10K & 5K','Mileage','Best Time','Pengalaman Pacer','Komitmen','Dokumen'] as $i => $s)
            <div class="step-chip"><span class="num">{{ $i+1 }}</span>{{ $s }}</div>
            @endforeach
        </div>
    </div>

    {{-- Error Summary --}}
    @if($errors->any())
    <div class="error-summary">
        <h3>{{ $errors->count() }} kesalahan perlu diperbaiki</h3>
        <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form method="POST" action="{{ route('candidate.store') }}" enctype="multipart/form-data" id="pacerForm">
        @csrf

        {{-- ════ SECTION 1 — DATA PRIBADI ════ --}}
        <div class="card">
            <div class="card-head"><div class="card-num">1</div><div class="card-title">Data Pribadi</div></div>
            <div class="card-body">

                <div class="field">
                    <label class="label">Email <span class="req">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" class="{{ $errors->has('email')?'err':'' }}">
                    @error('email')<span class="err-msg">{{ $message }}</span>@enderror
                </div>
                <div class="field">
                    <label class="label">Nomor WhatsApp / Telepon <span class="req">*</span></label>
                    <input type="email" 
                        name="no_hp" 
                        value="{{ old('no_hp') }}" 
                        placeholder="0812-3456-7890"
                        class="{{ $errors->has('no_hp')?'err':'' }}">
                    
                    <p style="font-size:11px; color:#AAAAAA; margin-top:4px;">
                        Nomor aktif yang dapat dihubungi via WhatsApp
                    </p>

                    @error('no_hp')
                        <span class="err-msg">{{ $message }}</span>
                    @enderror
                </div>

                {{-- KTP OCR CARD --}}
                <div id="ktpOcrCard" class="ktp-ocr-card">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;flex-wrap:wrap;gap:8px;">
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:32px;height:32px;background:var(--primary-light);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <svg width="16" height="16" fill="none" stroke="var(--primary)" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M7 9h10M7 13h6"/></svg>
                            </div>
                            <div>
                                <p style="font-family:'Syne',sans-serif;font-size:12px;font-weight:700;color:#111;">Upload &amp; Scan KTP <span style="color:var(--primary)">*</span></p>
                                <p style="font-size:11px;color:#AAA;margin-top:1px;">Upload → klik <strong style="color:var(--primary)">SCAN KTP</strong> → data terisi otomatis</p>
                            </div>
                        </div>
                        <span id="scanBadge" class="scan-badge">
                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            Ter-scan
                        </span>
                    </div>

                    <div id="ktpDropzone" class="ktp-dropzone"
                         onclick="KTP.openSheet()"
                         ondragover="event.preventDefault();this.classList.add('drag-over')"
                         ondragleave="this.classList.remove('drag-over')"
                         ondrop="KTP.handleDrop(event)">
                        <div id="dzDefault">
                            <div class="dz-icon">
                                <svg width="22" height="22" fill="none" stroke="var(--primary)" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                            </div>
                            <p class="dz-text">Ketuk untuk upload foto KTP</p>
                            <p class="dz-subtext">Kamera · Galeri · File Manager · JPG/PNG · Maks 10MB</p>
                        </div>
                        <div id="dzPreview" class="ktp-preview-wrap">
                            <div style="position:relative;display:inline-block;">
                                <img id="ktpPreviewImg" src="" alt="Preview KTP" class="ktp-preview-img">
                                <button type="button" class="ktp-remove-btn" onclick="KTP.reset(event)" title="Ganti foto">
                                    <svg width="12" height="12" fill="none" stroke="#fff" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                            <p style="font-size:11px;color:#AAA;margin-top:8px;">Ketuk untuk ganti foto</p>
                        </div>
                    </div>

                    <input type="file" id="ktpInputCamera" accept="image/*" capture="environment" style="display:none" onchange="KTP.fileChosen(this)">
                    <input type="file" id="ktpInputFoto"   accept="image/*" style="display:none" onchange="KTP.fileChosen(this)">
                    <input type="file" id="ktpInputFile"   accept="image/*,.heic,.heif" style="display:none" onchange="KTP.fileChosen(this)">
                    <input type="file" id="ktpFileForServer" name="ktp_file" style="display:none" accept="image/*">

                    <button type="button" id="btnScan" class="btn-scan" onclick="KTP.scan()">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/></svg>
                        SCAN KTP — Isi Data Otomatis
                    </button>

                    <div id="scanLoading" class="scan-loading">
                        <p>Membaca KTP dengan AI, harap tunggu...</p>
                        <div class="scan-bar"><div class="scan-bar-inner"></div></div>
                    </div>

                    @error('ktp_file')<span class="err-msg" style="display:block;margin-top:8px;">{{ $message }}</span>@enderror

                    <div id="ktpDataPanel" class="ktp-data-panel">
                        <div class="ktp-data-header">
                            <span>✦ Data dari KTP — klik untuk edit</span>
                            <span class="ktp-edit-hint">✏ NIK / Nama / Tgl Lahir bisa diedit</span>
                        </div>
                        <div id="ktpDataRows"></div>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Nama Lengkap <span class="req">*</span><span class="hint">(sesuai KTP, otomatis dari scan)</span></label>
                    <input type="text" name="nama" id="fieldNama" value="{{ old('nama') }}" placeholder="Terisi otomatis setelah scan KTP" class="{{ $errors->has('nama')?'err':'' }}">
                    @error('nama')<span class="err-msg">{{ $message }}</span>@enderror
                </div>

                <div class="grid-2col field-row">
                    <div class="field">
                        <label class="label">Tanggal Lahir <span class="req">*</span><span class="hint">(dari KTP)</span></label>
                        <input type="text" name="tanggal_lahir" id="fieldTglLahir" value="{{ old('tanggal_lahir') }}" placeholder="DD-MM-YYYY" class="{{ $errors->has('tanggal_lahir')?'err':'' }}">
                        @error('tanggal_lahir')<span class="err-msg">{{ $message }}</span>@enderror
                    </div>
                    <div class="field">
                        <label class="label">NIK <span class="req">*</span><span class="hint">(16 digit)</span></label>
                        <input type="text" name="nik" id="fieldNik" value="{{ old('nik') }}" placeholder="Terisi otomatis dari scan" maxlength="16" class="{{ $errors->has('nik')?'err':'' }}">
                        @error('nik')<span class="err-msg">{{ $message }}</span>@enderror
                    </div>
                </div>

                {{-- Input Domisili yang telah diubah menjadi Radio Button --}}
                <div class="field">
                    <label class="label">Apakah anda berdomisili di Balikpapan? <span class="req">*</span></label>
                    <div class="radio-group">
                        {{-- Pilihan Ya --}}
                        <label class="dist-card" :class="domisili === 'Balikpapan' ? 'selected' : ''" @click="domisili = 'Balikpapan'">
                            <input type="radio" name="domisili" value="Balikpapan" x-model="domisili" style="display:none">
                            <div class="dist-check">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="4"><path d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="dist-label">Ya</span>
                        </label>

                        {{-- Pilihan Tidak --}}
                        <label class="dist-card" :class="domisili === 'Luar Balikpapan' ? 'selected' : ''" @click="domisili = 'Luar Balikpapan'">
                            <input type="radio" name="domisili" value="Luar Balikpapan" x-model="domisili" style="display:none">
                            <div class="dist-check">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="4"><path d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="dist-label">Tidak</span>
                        </label>
                    </div>
                    @error('domisili')<span class="err-msg">{{ $message }}</span>@enderror
                </div>

                <div class="field">
                    <label class="label">Alamat Domisili Sesuai KTP <span class="req">*</span></label>
                    <textarea name="alamat" rows="3" placeholder="Jl. Contoh No. 1, RT/RW, Kelurahan, Kecamatan..." class="{{ $errors->has('alamat')?'err':'' }}">{{ old('alamat') }}</textarea>
                    @error('alamat')<span class="err-msg">{{ $message }}</span>@enderror
                </div>

                <div class="grid-2col field-row">
                    <div class="field">
                        <label class="label">Instagram <span class="req">*</span><span class="hint">(link profil)</span></label>
                        <input type="url" name="instagram" value="{{ old('instagram') }}" placeholder="https://instagram.com/username" class="{{ $errors->has('instagram')?'err':'' }}">
                        @error('instagram')<span class="err-msg">{{ $message }}</span>@enderror
                    </div>
                    <div class="field">
                        <label class="label">Strava <span class="req">*</span><span class="hint">(link profil)</span></label>
                        <input type="url" name="strava" value="{{ old('strava') }}" placeholder="https://strava.com/athletes/..." class="{{ $errors->has('strava')?'err':'' }}">
                        @error('strava')<span class="err-msg">{{ $message }}</span>@enderror
                    </div>
                </div>

            </div>
        </div>

        {{-- ════ SECTION 2 — FULL MARATHON ════ --}}
        <div class="card">
            <div class="card-head"><div class="card-num">2</div><div class="card-title">Pengalaman Full Marathon</div></div>
            <div class="card-body">
                <div class="field">
                    <label class="label">Pernah mengikuti Full Marathon? <span class="req">*</span></label>
                    <div class="radio-group">
                        @include('candidate._radio', ['name'=>'is_full_marathon','value'=>'pernah','model'=>'fm','label'=>'Pernah','sub'=>'Sertakan sertifikat'])
                        @include('candidate._radio', ['name'=>'is_full_marathon','value'=>'tidak','model'=>'fm','label'=>'Tidak Pernah','sub'=>'Lewati bagian ini'])
                    </div>
                    @error('is_full_marathon')<span class="err-msg">{{ $message }}</span>@enderror
                </div>
                <div class="cond-block" x-show="fm==='pernah'" x-transition>
                    <div class="grid-2col field-row">
                        <div class="field">
                            <label class="label">Nama Event FM <span class="req">*</span></label>
                            <input type="text" name="fm_event" value="{{ old('fm_event') }}" placeholder="Jakarta Marathon 2024" class="{{ $errors->has('fm_event')?'err':'' }}">
                            @error('fm_event')<span class="err-msg">{{ $message }}</span>@enderror
                        </div>
                        <div class="field">
                            <label class="label">Tahun <span class="req">*</span></label>
                            <input type="number" name="fm_year" value="{{ old('fm_year') }}" placeholder="{{ date('Y') }}" min="2000" max="{{ date('Y') }}" class="{{ $errors->has('fm_year')?'err':'' }}">
                            @error('fm_year')<span class="err-msg">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Upload Sertifikat FM <span class="req">*</span><span class="hint">JPG/PNG</span></label>
                        {{-- FIX: Tidak ada onclick — for= saja --}}
                        <label for="fm_cert" class="upload-zone {{ $errors->has('fm_certificate')?'err':'' }}">
                            <input type="file" id="fm_cert" name="fm_certificate" accept=".jpg,.jpeg,.png" style="display:none" onchange="UPLOAD.fileChosen('fm', this)">
                            <div class="upload-zone-icon">
                                <svg id="uz-up-fm" width="18" height="18" fill="none" stroke="var(--primary)" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                <svg id="uz-ok-fm" width="18" height="18" fill="none" stroke="#16A34A" stroke-width="2.5" viewBox="0 0 24 24" style="display:none"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <p class="upload-zone-name" id="uz-name-fm">Upload Sertifikat Full Marathon</p>
                        </label>
                        <div class="upload-thumb-wrap" id="uz-thumb-wrap-fm">
                            <img id="uz-thumb-fm" src="" alt="Preview" class="upload-thumb">
                            <button type="button" class="upload-thumb-remove" onclick="UPLOAD.remove('fm')">
                                <svg width="10" height="10" fill="none" stroke="#fff" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                        @error('fm_certificate')<span class="err-msg">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- ════ SECTION 3 — HALF MARATHON ════ --}}
        <div class="card">
            <div class="card-head"><div class="card-num">3</div><div class="card-title">Pengalaman Half Marathon</div></div>
            <div class="card-body">
                <div class="field">
                    <label class="label">Pernah mengikuti Half Marathon? <span class="req">*</span></label>
                    <div class="radio-group">
                        @include('candidate._radio', ['name'=>'is_half_marathon','value'=>'pernah','model'=>'hm','label'=>'Pernah','sub'=>'Sertakan sertifikat'])
                        @include('candidate._radio', ['name'=>'is_half_marathon','value'=>'tidak','model'=>'hm','label'=>'Tidak Pernah','sub'=>'Lewati bagian ini'])
                    </div>
                    @error('is_half_marathon')<span class="err-msg">{{ $message }}</span>@enderror
                </div>
                <div class="cond-block" x-show="hm==='pernah'" x-transition>
                    <div class="grid-2col field-row">
                        <div class="field">
                            <label class="label">Nama Event HM <span class="req">*</span></label>
                            <input type="text" name="hm_event" value="{{ old('hm_event') }}" placeholder="Bali Marathon 2024 (HM)" class="{{ $errors->has('hm_event')?'err':'' }}">
                            @error('hm_event')<span class="err-msg">{{ $message }}</span>@enderror
                        </div>
                        <div class="field">
                            <label class="label">Tahun <span class="req">*</span></label>
                            <input type="number" name="hm_year" value="{{ old('hm_year') }}" placeholder="{{ date('Y') }}" min="2000" max="{{ date('Y') }}" class="{{ $errors->has('hm_year')?'err':'' }}">
                            @error('hm_year')<span class="err-msg">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Upload Sertifikat HM <span class="req">*</span><span class="hint">JPG/PNG</span></label>
                        <label for="hm_cert" class="upload-zone {{ $errors->has('hm_certificate')?'err':'' }}">
                            <input type="file" id="hm_cert" name="hm_certificate" accept=".jpg,.jpeg,.png" style="display:none" onchange="UPLOAD.fileChosen('hm', this)">
                            <div class="upload-zone-icon">
                                <svg id="uz-up-hm" width="18" height="18" fill="none" stroke="var(--primary)" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                <svg id="uz-ok-hm" width="18" height="18" fill="none" stroke="#16A34A" stroke-width="2.5" viewBox="0 0 24 24" style="display:none"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <p class="upload-zone-name" id="uz-name-hm">Upload Sertifikat Half Marathon</p>
                        </label>
                        <div class="upload-thumb-wrap" id="uz-thumb-wrap-hm">
                            <img id="uz-thumb-hm" src="" alt="Preview" class="upload-thumb">
                            <button type="button" class="upload-thumb-remove" onclick="UPLOAD.remove('hm')">
                                <svg width="10" height="10" fill="none" stroke="#fff" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                        @error('hm_certificate')<span class="err-msg">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>
        </div>

{{-- ════ SECTION 4 — 10K & 5K (Hanya muncul jika FM & HM 'tidak') ════ --}}
<div class="card" x-transition>
    <div class="card-head">
        <div class="card-num">4</div>
        <div class="card-title">Pengalaman 10K & 5K</div>
    </div>
    <div class="card-body">

        {{-- Kategori 10K --}}
        <div style="border-bottom:1px solid #F0F0F0; padding-bottom:20px; margin-bottom:20px;">
            <div class="field">
                <label class="label">Pernah Race 10K? <span class="req">*</span></label>
                <div class="radio-group">
                    {{-- Input radio standar agar mudah divalidasi 'required' oleh browser/laravel --}}
                    <label class="radio-opt">
                        <input type="radio" name="is_10k" value="pernah" x-model="r10k" :required="fm === 'tidak' && hm === 'tidak'">
                        <div class="radio-pip"></div>
                        <div class="radio-label">Pernah</div>
                    </label>
                    <label class="radio-opt">
                        <input type="radio" name="is_10k" value="tidak" x-model="r10k" :required="fm === 'tidak' && hm === 'tidak'">
                        <div class="radio-pip"></div>
                        <div class="radio-label">Tidak Pernah</div>
                    </label>
                </div>
                @error('is_10k')<span class="err-msg">{{ $message }}</span>@enderror
            </div>

            <div class="cond-block" x-show="r10k === 'pernah'" x-transition>
                <div class="grid-2col field-row">
                    <div class="field">
                        <label class="label">Nama Event 10K</label>
                        <input type="text" name="race_10k_event" value="{{ old('race_10k_event') }}" placeholder="Run For Life 10K">
                    </div>
                    <div class="field">
                        <label class="label">Tahun</label>
                        <input type="number" name="race_10k_year" value="{{ old('race_10k_year') }}" placeholder="2024">
                    </div>
                </div>
            </div>
        </div>

        {{-- Kategori 5K --}}
        <div>
            <div class="field">
                <label class="label">Pernah Race 5K? <span class="req">*</span></label>
                <div class="radio-group">
                    <label class="radio-opt">
                        <input type="radio" name="is_5k" value="pernah" x-model="r5k" :required="fm === 'tidak' && hm === 'tidak'">
                        <div class="radio-pip"></div>
                        <div class="radio-label">Pernah</div>
                    </label>
                    <label class="radio-opt">
                        <input type="radio" name="is_5k" value="tidak" x-model="r5k" :required="fm === 'tidak' && hm === 'tidak'">
                        <div class="radio-pip"></div>
                        <div class="radio-label">Tidak Pernah</div>
                    </label>
                </div>
                @error('is_5k')<span class="err-msg">{{ $message }}</span>@enderror
            </div>

            <div class="cond-block" x-show="r5k === 'pernah'" x-transition>
                <div class="grid-2col field-row">
                    <div class="field">
                        <label class="label">Nama Event 5K</label>
                        <input type="text" name="race_5k_event" value="{{ old('race_5k_event') }}" placeholder="Fun Run 5K">
                    </div>
                    <div class="field">
                        <label class="label">Tahun</label>
                        <input type="number" name="race_5k_year" value="{{ old('race_5k_year') }}" placeholder="2024">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

        {{-- ════ SECTION 4B — EVENT LARI LAINNYA ════ --}}
        {{-- x-show dihapus agar section ini selalu tampil --}}
        <div class="card" x-transition>
            <div class="card-head" style="background:#1A1A2E;">
                <div class="card-num" style="background:#4F46E5;">4B</div>
                <div class="card-title" style="color:#fff;">Event Lari Lainnya (Trail / Ultra / Dll)</div>
            </div>
            <div class="card-body">
                <div class="field">
                    <label class="label">Apakah pernah ikut event lain selain Road Race? <span class="req">*</span></label>
                    <div style="display:flex; flex-direction:column; gap:8px;">
                        @foreach(['trail'=>'Pernah','none'=>'Tidak Pernah Event Apapun'] as $val=>$lbl)
                        <label class="radio-opt">
                            <input type="radio" name="trail_status" value="{{ $val }}" x-model="trailStatus" {{ old('trail_status')===$val?'checked':'' }}>
                            <div class="radio-pip"></div>
                            <div>
                                <div class="radio-label">{{ $lbl }}</div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Blok ini hanya muncul jika user memilih 'Pernah' --}}
                <div class="cond-block" x-show="trailStatus==='trail'" x-transition>
                    <div class="grid-2col field-row">
                        <div class="field">
                            <label class="label">Nama Event <span class="req">*</span></label>
                            <input type="text" name="trail_event" value="{{ old('trail_event') }}" placeholder="Contoh: Trail Run Borneo 2024">
                        </div>
                        <div class="field">
                            <label class="label">Tahun <span class="req">*</span></label>
                            <input type="number" name="trail_year" value="{{ old('trail_year') }}" placeholder="{{ date('Y') }}" min="2000" max="{{ date('Y') }}">
                        </div>
                    </div>
                    
                    <div class="field">
                        <label class="label">Upload Bukti <span class="req">*</span></label>
                        <label for="trail_cert" class="upload-zone">
                            <input type="file" id="trail_cert" name="trail_certificate" accept=".jpg,.jpeg,.png" style="display:none" onchange="UPLOAD.fileChosen('trail', this)">
                            <div class="upload-zone-icon">
                                <svg id="uz-up-trail" width="18" height="18" fill="none" stroke="var(--primary)" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                <svg id="uz-ok-trail" width="18" height="18" fill="none" stroke="#16A34A" stroke-width="2.5" viewBox="0 0 24 24" style="display:none"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <p class="upload-zone-name" id="uz-name-trail">Upload Bukti Event</p>
                        </label>
                        
                        <div class="upload-thumb-wrap" id="uz-thumb-wrap-trail">
                            <img id="uz-thumb-trail" src="" alt="Preview" class="upload-thumb">
                            <button type="button" class="upload-thumb-remove" onclick="UPLOAD.remove('trail')">
                                <svg width="10" height="10" fill="none" stroke="#fff" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ════ SECTION 5 — MILEAGE ════ --}}
        <div class="card">
            <div class="card-head"><div class="card-num">5</div><div class="card-title">Data Mileage (Desember 2025 — Maret 2026)</div></div>
            <div class="card-body">
                <div class="mileage-block">
                <div style="font-size:12px; color:#666; gap 15px; display:flex; align-items:center; margin-bottom:15px;">
                        Contoh Screenshot Grafik Mileage Strava atau Smart Watch:
                    </div>
                <div style="display:flex; gap:15px; margin-bottom:15px; align-items:center;">
                    <div style="text-align:center;">
                        <img src="https://lh7-rt.googleusercontent.com/formsz/AN7BsVCqtKQF2aT7V7ZnDaECAjDY_RXgGe9QTa2Y2L7wHjbo6wMtZXnCKJjWXC_PFFPI-pVhLGhLE8eXjmRSEtcLtdjMsmYi3oKNLgq-jR0KC6ZNQZussyTD3g5XEAH2J5vKVSaYr77xx5DND_ws35l0kHQtPiozXebsV-EC=s2048?key=Z1pP6rwltK_1EvnFUHo5QA" alt="Strava" style="width:200px; height:200px; border-radius:8px;">
                        <p style="font-size:10px; font-weight:700;">STRAVA</p>
                    </div>
                    <div style="text-align:center;">
                        <img src="https://lh7-rt.googleusercontent.com/formsz/AN7BsVCAErtFM6hs7Koy0krMVnfrpgdrIfUJBXKTQTsFSK8NViQpPZMKphnTyUxMvyMHvL80uj57NigMnwk0aetL0JVcQhXOht24apgSVunS6Kbfl7RFr40Xh8VSvt_CuCBvVIUvhf1qf-7zL2b36ka41C0HkDbvJ-U5AW54=s2048?key=Z1pP6rwltK_1EvnFUHo5QA" alt="Smart Watch" style="width:200px; height:200px; border-radius:8px;">
                        <p style="font-size:10px; font-weight:700;">SMART WATCH</p>
                    </div>

                </div>
                </div>
                <div class="info-box">📊 Upload screenshot grafik mileage dari <strong>Strava</strong> atau smartwatch. Isi total jarak dalam <strong>km</strong>. File tidak akan hilang meski ada error validasi.</div>

                @foreach([
                    ['dec_2025','Desember 2025'],
                    ['jan_2026','Januari 2026'],
                    ['feb_2026','Februari 2026'],
                    ['mar_2026','Maret 2026']
                ] as [$key,$label])
                <div class="mileage-block" id="mileage-block-{{ $key }}">
                    <div class="grid-2col">
                        <div class="field" style="margin:0">
                            <label class="label" for="mileage_{{ $key }}_input">
                                {{ $label }} <span class="req">*</span><span class="hint">km</span>
                            </label>
                            <div style="position:relative;">
                                <input type="number" id="mileage_{{ $key }}_input" name="mileage_{{ $key }}"
                                       value="{{ old('mileage_'.$key) }}" placeholder="0" min="0" step="0.01"
                                       style="padding-right:44px;" class="{{ $errors->has('mileage_'.$key)?'err':'' }}">
                                <span style="position:absolute;right:14px;top:50%;transform:translateY(-50%);font-size:12px;color:#AAA;font-family:'Syne',sans-serif;font-weight:700;">km</span>
                            </div>
                            @error('mileage_'.$key)<span class="err-msg">{{ $message }}</span>@enderror
                        </div>

                        <div class="field" style="margin:0">
                            <label class="label">Grafik {{ $label }} <span class="req">*</span></label>
                            {{-- FIX: Tidak ada onclick di label --}}
                            <label for="mg_{{ $key }}" id="mg-label-{{ $key }}"
                                   class="upload-zone {{ $errors->has('mileage_'.$key.'_graph') ? 'err' : '' }}">
                                <input type="file" id="mg_{{ $key }}" name="mileage_{{ $key }}_graph"
                                       accept=".jpg,.jpeg,.png,.webp" style="display:none"
                                       onchange="MILEAGE.fileChosen('{{ $key }}', this)">
                                <div class="upload-zone-icon">
                                    <svg id="uz-up-mg-{{ $key }}" width="18" height="18" fill="none" stroke="var(--primary)" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                    <svg id="uz-ok-mg-{{ $key }}" width="18" height="18" fill="none" stroke="#16A34A" stroke-width="2.5" viewBox="0 0 24 24" style="display:none"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <p class="upload-zone-name" id="mg-name-{{ $key }}">
                                    @if($errors->has('mileage_'.$key.'_graph')) ⚠ Wajib diupload
                                    @else Ketuk untuk upload grafik
                                    @endif
                                </p>
                            </label>
                            <div class="upload-thumb-wrap" id="mg-thumb-wrap-{{ $key }}">
                                <img id="mg-thumb-{{ $key }}" src="" alt="Preview" class="upload-thumb">
                                <button type="button" class="upload-thumb-remove" onclick="MILEAGE.remove('{{ $key }}')">
                                    <svg width="10" height="10" fill="none" stroke="#fff" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                            <div id="mg-persisted-{{ $key }}" style="display:none;">
                                <span class="persisted-badge">
                                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    File tersimpan — siap dikirim ulang
                                </span>
                            </div>
                            @error('mileage_'.$key.'_graph')<span class="err-msg">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- ════ SECTION 6 — BEST TIME ════ --}}
        <div class="card">
            <div class="card-head"><div class="card-num">6</div><div class="card-title">Catatan Waktu Terbaik</div></div>
            <div class="card-body">
                <div class="info-box warning">⏱️ Format: <strong>H:MM:SS</strong> (contoh: 4:30:00). Kosongkan jika belum pernah.</div>
                @foreach([
                    ['fm','Full Marathon (42K)','best_time_fm','best_time_fm_file'],
                    ['hm','Half Marathon (21K)','best_time_hm','best_time_hm_file'],
                    ['10k','10 Kilometer','best_time_10k','best_time_10k_file'],
                    ['5k','5 Kilometer','best_time_5k','best_time_5k_file']
                ] as [$key,$label,$timeField,$fileField])
                <div class="grid-2col" style="background:#FAFAFA;border:1px solid #EEE;border-radius:14px;padding:18px;margin-bottom:12px;">
                    <div class="field" style="margin:0">
                        <label class="label">{{ $label }}<span class="hint">(Opsional)</span></label>
                        <input type="text" name="{{ $timeField }}" value="{{ old($timeField) }}" placeholder="H:MM:SS">
                    </div>
                    <div class="field" style="margin:0">
                        <label class="label">Upload Bukti</label>
                        <label for="bt_{{ $key }}" class="upload-zone" style="padding:14px;">
                            <input type="file" id="bt_{{ $key }}" name="{{ $fileField }}" accept=".jpg,.jpeg,.png" style="display:none" onchange="UPLOAD.fileChosen('bt_{{$key}}', this)">
                            <div class="upload-zone-icon">
                                <svg id="uz-up-bt-{{ $key }}" width="18" height="18" fill="none" stroke="var(--primary)" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <svg id="uz-ok-bt-{{ $key }}" width="18" height="18" fill="none" stroke="#16A34A" stroke-width="2.5" viewBox="0 0 24 24" style="display:none"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <p class="upload-zone-name" id="uz-name-bt-{{ $key }}">Upload bukti (Opsional)</p>
                        </label>
                        <div class="upload-thumb-wrap" id="uz-thumb-wrap-bt-{{ $key }}">
                            <img id="uz-thumb-bt-{{ $key }}" src="" alt="Preview" class="upload-thumb">
                            <button type="button" class="upload-thumb-remove" onclick="UPLOAD.remove('bt_{{ $key }}')">
                                <svg width="10" height="10" fill="none" stroke="#fff" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- ════ SECTION 7 — PENGALAMAN PACER ════ --}}
        <div class="card">
            <div class="card-head"><div class="card-num">7</div><div class="card-title">Pengalaman Menjadi Pacer / Running Buddies</div></div>
            <div class="card-body">
                <div class="field">
                    <label class="label">Pernah menjadi Pace Setter / Running Buddies? <span class="req">*</span></label>
                    <div class="radio-group">
                        @include('candidate._radio', ['name'=>'is_pacer_experience','value'=>'pernah','model'=>'pacerExp','label'=>'Pernah','sub'=>'Isi detail'])
                        @include('candidate._radio', ['name'=>'is_pacer_experience','value'=>'tidak','model'=>'pacerExp','label'=>'Belum Pernah','sub'=>''])
                    </div>
                    @error('is_pacer_experience')<span class="err-msg">{{ $message }}</span>@enderror
                </div>
                <div class="cond-block" x-show="pacerExp==='pernah'" x-transition>
                    <div class="field">
                        <label class="label">Nama Event + Tahun <span class="req">*</span><span class="hint">(boleh lebih dari satu)</span></label>
                        <textarea name="pacer_event_list" rows="3" placeholder="- Balikpapan Marathon 2023&#10;- Bayan Run 2024">{{ old('pacer_event_list') }}</textarea>
                        @error('pacer_event_list')<span class="err-msg">{{ $message }}</span>@enderror
                    </div>
                    <div class="field">
                        <label class="label">Jarak & Pace Saat Bertugas <span class="req">*</span></label>
                        <textarea name="pacer_distance_pace" rows="3" placeholder="- HM (21K) @ 6:00/km&#10;- 10K @ 5:30/km">{{ old('pacer_distance_pace') }}</textarea>
                        @error('pacer_distance_pace')<span class="err-msg">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- ════ SECTION 8 — ESSAY ════ --}}
        <div class="card">
            <div class="card-head"><div class="card-num">8</div><div class="card-title">Pemahaman & Essay</div></div>
            <div class="card-body">
                <div class="field">
                    <label class="label">Pandangan Anda tentang Dunia Lari Jarak Jauh <span class="req">*</span></label>
                    <p style="font-size:12px;color:#AAA;margin-bottom:6px;line-height:1.6;">Jelaskan dari sudut pandang Anda tentang perkembangan olahraga lari yang sedang digemari.</p>
                    <textarea name="essay_running_world" rows="5" placeholder="Tulis pandangan Anda..." class="{{ $errors->has('essay_running_world')?'err':'' }}">{{ old('essay_running_world') }}</textarea>
                    @error('essay_running_world')<span class="err-msg">{{ $message }}</span>@enderror
                </div>
                <div class="field">
                    <label class="label">Apa itu Pacer / Running Buddies? <span class="req">*</span></label>
                    <textarea name="essay_pacer_definition" rows="4" placeholder="Jelaskan menurut pemahaman Anda..." class="{{ $errors->has('essay_pacer_definition')?'err':'' }}">{{ old('essay_pacer_definition') }}</textarea>
                    @error('essay_pacer_definition')<span class="err-msg">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        {{-- ════ SECTION 9 — KOMITMEN ════ --}}
        <div class="card">
            <div class="card-head"><div class="card-num">9</div><div class="card-title">Komitmen & Preferensi</div></div>
            <div class="card-body">
                <div class="field">
                    <label class="label">Mengapa Anda Pantas Menjadi Pace Setter Bayan Run 2026? <span class="req">*</span></label>
                    <textarea name="alasan_pantas" rows="4" placeholder="Ceritakan alasan Anda..." class="{{ $errors->has('alasan_pantas')?'err':'' }}">{{ old('alasan_pantas') }}</textarea>
                    @error('alasan_pantas')<span class="err-msg">{{ $message }}</span>@enderror
                </div>

                {{-- JARAK FAVORIT — bordered clickable cards --}}
                <div class="field">
                    <label class="label">Jarak Favorit Saat Bertugas <span class="req">*</span><span class="hint">(boleh lebih dari satu)</span></label>
                    <div class="dist-grid">
                        @foreach(['hm'=>'Half Marathon (21K)','10k'=>'10 Kilometer','5k'=>'5 Kilometer','any'=>'Siap di Jarak Berapapun'] as $val=>$lbl)
                        <label class="dist-card" :class="preferDist.includes('{{ $val }}') ? 'selected' : ''">
                            <input type="checkbox" name="preferred_distance[]" value="{{ $val }}"
                                   x-model="preferDist"
                                   {{ in_array($val, old('preferred_distance',[]))?'checked':'' }}
                                   style="position:absolute;opacity:0;width:0;height:0;">
                            <div class="dist-check">
                                <svg width="12" height="12" fill="none" stroke="white" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <span class="dist-label">{{ $lbl }}</span>
                        </label>
                        @endforeach
                    </div>
                    @error('preferred_distance')<span class="err-msg">{{ $message }}</span>@enderror
                </div>

                <div class="field">
                    <label class="label">Komitmen Agenda 4 Bulan <span class="req">*</span></label>
                    <div style="display:flex;flex-direction:column;gap:8px;">
                        @foreach(['ya_siap'=>'Ya, Saya Siap Menjalani Peran Ini','tidak_siap'=>'Tidak, Saya Tidak Siap','mencoba_menyesuaikan'=>'Saya Akan Mencoba Menyesuaikan'] as $val=>$lbl)
                        <label class="radio-opt" style="max-width:100%"><input type="radio" name="komitmen" value="{{ $val }}" x-model="komitmen" {{ old('komitmen')===$val?'checked':'' }}><div class="radio-pip"></div><div><div class="radio-label">{{ $lbl }}</div></div></label>
                        @endforeach
                    </div>
                    @error('komitmen')<span class="err-msg">{{ $message }}</span>@enderror
                </div>

                <div class="field">
                    <label class="label">Izin & Dukungan Keluarga <span class="req">*</span></label>
                    <div class="radio-group">
                        <label class="radio-opt"><input type="radio" name="izin_keluarga" value="ya" x-model="izinKeluarga" {{ old('izin_keluarga')==='ya'?'checked':'' }}><div class="radio-pip"></div><div><div class="radio-label">Ya, Sudah Mendapat Izin</div></div></label>
                        <label class="radio-opt"><input type="radio" name="izin_keluarga" value="belum" x-model="izinKeluarga" {{ old('izin_keluarga')==='belum'?'checked':'' }}><div class="radio-pip"></div><div><div class="radio-label">Belum, Masih Diskusi</div></div></label>
                    </div>
                    @error('izin_keluarga')<span class="err-msg">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        {{-- ════ SECTION 10 — DOKUMEN FINAL ════ --}}
        <div class="card">
            <div class="card-head" style="background:#1E3A8A;"><div class="card-num" style="background:var(--primary);">10</div><div class="card-title">Dokumen Final & Pernyataan</div></div>
            <div class="card-body">
                <div class="info-box warning">📄 <strong>Wajib:</strong> Unduh, cetak, tanda tangani di atas <strong>materai fisik</strong>, lalu upload.</div>
                <a href="https://drive.google.com/uc?export=download&id=14hrQEWmcKavgqWj4_9o3Svqe5f0httgm"
                   target="_blank"
                   style="display:inline-flex;align-items:center;gap:6px;margin-bottom:14px;color:var(--primary);font-weight:700;font-size:13px;text-decoration:none;padding:8px 14px;border:1.5px solid var(--primary-border);border-radius:8px;background:var(--primary-light);transition:all .2s;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Download Surat Waiver
                </a>

                <div class="field">
                    <label class="label">Upload Waiver Surat Persetujuan <span class="req">*</span><span class="hint">PDF/JPG/PNG</span></label>
                    <label for="waiver_file" class="upload-zone {{ $errors->has('waiver_file')?'err':'' }}">
                        <input type="file" id="waiver_file" name="waiver_file" accept=".pdf,.jpg,.jpeg,.png" style="display:none" onchange="UPLOAD.fileChosen('waiver', this)">
                        <div class="upload-zone-icon">
                            <svg id="uz-up-waiver" width="18" height="18" fill="none" stroke="var(--primary)" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                            <svg id="uz-ok-waiver" width="18" height="18" fill="none" stroke="#16A34A" stroke-width="2.5" viewBox="0 0 24 24" style="display:none"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <p class="upload-zone-name" id="uz-name-waiver">Upload Surat Waiver Bermaterai</p>
                    </label>
                    <div class="upload-thumb-wrap" id="uz-thumb-wrap-waiver">
                        <img id="uz-thumb-waiver" src="" alt="Preview" class="upload-thumb">
                        <button type="button" class="upload-thumb-remove" onclick="UPLOAD.remove('waiver')">
                            <svg width="10" height="10" fill="none" stroke="#fff" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    @error('waiver_file')<span class="err-msg">{{ $message }}</span>@enderror
                </div>

                <div class="pernyataan-box">
                    <p class="pernyataan-text">
                        Saya menyatakan bahwa seluruh data lari, catatan waktu, dan informasi pribadi yang saya lampirkan adalah <strong>benar adanya tanpa manipulasi</strong>. Ketidakjujuran data berakibat <strong>diskualifikasi permanen</strong>.
                    </p>
                    <label class="pernyataan-label" onclick="togglePernyataan()">
                        <input type="checkbox" name="pernyataan_keabsahan" id="pernyataanCheckbox" value="1"
                               {{ old('pernyataan_keabsahan') ? 'checked' : '' }}
                               style="position:absolute;opacity:0;width:0;height:0;pointer-events:none;">
                        <div class="pernyataan-check-box {{ old('pernyataan_keabsahan') ? 'checked' : '' }}"
                             id="pernyataanVisual" role="checkbox"
                             aria-checked="{{ old('pernyataan_keabsahan') ? 'true' : 'false' }}">
                            <svg width="14" height="14" fill="none" stroke="white" stroke-width="3" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <span class="pernyataan-check-text">Ya, saya menjamin keabsahan seluruh data yang saya lampirkan.</span>
                    </label>
                    @error('pernyataan_keabsahan')<span class="err-msg" style="display:block;margin-top:8px;">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <div style="margin-top:8px;">
            <div class="disclaimer" style="margin-bottom:16px;font-size:12px;line-height:1.9;">
                <span>✓</span> Seluruh data wajib diisi dengan benar dan dapat dipertanggungjawabkan.<br>
                <span>✓</span> Data digunakan untuk keperluan seleksi dan asuransi jika lolos sebagai Pacer.<br>
                <span>✓</span> Ketidakjujuran data berakibat diskualifikasi permanen.
            </div>
            <button type="submit" class="btn-submit" id="btnSubmit">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                Kirim Pendaftaran
            </button>
        </div>

    </form>
</div>

{{-- Bottom Sheet --}}
<div id="sheetBackdrop" class="sheet-backdrop" onclick="SHEET.close()"></div>
<div id="uploadSheet" class="upload-sheet" role="dialog">
    <div class="sheet-handle"></div>
    <p class="sheet-title">Pilih Sumber Foto KTP</p>
    <div class="sheet-opts">
        <button type="button" class="sheet-opt opt-camera" onclick="SHEET.pick('camera')">
            <div class="sheet-opt-icon"><svg width="22" height="22" fill="none" stroke="#3B82F6" stroke-width="1.8" viewBox="0 0 24 24"><path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/><circle cx="12" cy="13" r="4"/></svg></div>
            <span>Foto<br>Kamera</span>
        </button>
        <button type="button" class="sheet-opt opt-foto" onclick="SHEET.pick('foto')">
            <div class="sheet-opt-icon"><svg width="22" height="22" fill="none" stroke="#60A5FA" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg></div>
            <span>Upload<br>Foto</span>
        </button>
        <button type="button" class="sheet-opt opt-file" onclick="SHEET.pick('file')">
            <div class="sheet-opt-icon"><svg width="22" height="22" fill="none" stroke="#93C5FD" stroke-width="1.8" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/></svg></div>
            <span>Upload<br>File</span>
        </button>
    </div>
    <button type="button" class="sheet-cancel" onclick="SHEET.close()">Batal</button>
</div>

@push('scripts')
<script>

document.addEventListener('DOMContentLoaded', function() {
    const citySelect = document.getElementById('fieldDomisili');
    const provId = '64'; // ID untuk Kalimantan Timur

    fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provId}.json`)
        .then(response => response.json())
        .then(regencies => {
            // Kosongkan loading text
            citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
            
            regencies.forEach(regency => {
                let option = document.createElement('option');
                // value disimpan dalam format Title Case (Contoh: KOTA SAMARINDA)
                option.value = regency.name; 
                option.text = regency.name;
                
                // Jika ada data lama (old value) dari Laravel, otomatis pilih
                if(option.value == "{{ old('domisili') }}") {
                    option.selected = true;
                }
                
                citySelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error fetching cities:', error);
            citySelect.innerHTML = '<option value="">Gagal memuat data</option>';
        });
});
/* ═══════════════════════════════════════════════════════════════
   SHEET
═══════════════════════════════════════════════════════════════ */
var SHEET=(function(){
    var _a=false;
    function open(){if(_a)return;var b=document.getElementById('sheetBackdrop'),s=document.getElementById('uploadSheet');b.classList.remove('hiding');s.classList.remove('hiding');b.classList.add('show');s.classList.add('show');document.body.style.overflow='hidden';}
    function close(){if(_a)return;_a=true;var b=document.getElementById('sheetBackdrop'),s=document.getElementById('uploadSheet');b.classList.add('hiding');s.classList.add('hiding');setTimeout(function(){b.classList.remove('show','hiding');s.classList.remove('show','hiding');document.body.style.overflow='';_a=false;},220);}
    function pick(t){close();setTimeout(function(){var m={camera:'ktpInputCamera',foto:'ktpInputFoto',file:'ktpInputFile'};var el=document.getElementById(m[t]);if(el)el.click();},240);}
    var _y=0;
    document.addEventListener('touchstart',function(e){_y=e.touches[0].clientY;},{passive:true});
    document.addEventListener('touchend',function(e){if(document.getElementById('uploadSheet').classList.contains('show')&&e.changedTouches[0].clientY-_y>70)close();},{passive:true});
    document.addEventListener('keydown',function(e){if(e.key==='Escape')close();});
    return{open,close,pick};
})();

/* ═══════════════════════════════════════════════════════════════
   TOAST — auto dismiss 4s dengan fade-out proper
═══════════════════════════════════════════════════════════════ */
var _toastTimer=null;
function showToast(msg,type){
    type=type||'success';
    var el=document.getElementById('pacerToast');
    if(!el){el=document.createElement('div');el.id='pacerToast';document.body.appendChild(el);}
    // Reset transisi sebelumnya
    el.style.transition='none';
    el.style.opacity='1';
    el.style.transform='translateY(0) scale(1)';
    el.className='toast-'+type;
    el.textContent=msg;
    el.style.display='block';
    if(_toastTimer)clearTimeout(_toastTimer);
    // Fade out setelah 4 detik
    _toastTimer=setTimeout(function(){
        el.style.transition='opacity .4s ease, transform .4s ease';
        el.style.opacity='0';
        el.style.transform='translateY(-10px) scale(0.95)';
        setTimeout(function(){el.style.display='none';},420);
    },4000);
}

/* ═══════════════════════════════════════════════════════════════
   PERNYATAAN
═══════════════════════════════════════════════════════════════ */
function togglePernyataan(){
    var cb=document.getElementById('pernyataanCheckbox');
    var v=document.getElementById('pernyataanVisual');
    cb.checked=!cb.checked;
    v.classList.toggle('checked',cb.checked);
    v.setAttribute('aria-checked',cb.checked?'true':'false');
}

/* ═══════════════════════════════════════════════════════════════
   UPLOAD — unified module, FIX: tidak ada programmatic open
═══════════════════════════════════════════════════════════════ */
var UPLOAD=(function(){
    'use strict';
    var MAX=450*1024;
    var _m={
        fm:      {input:'fm_cert',   name:'uz-name-fm',     up:'uz-up-fm',     ok:'uz-ok-fm',     tw:'uz-thumb-wrap-fm',     th:'uz-thumb-fm'},
        hm:      {input:'hm_cert',   name:'uz-name-hm',     up:'uz-up-hm',     ok:'uz-ok-hm',     tw:'uz-thumb-wrap-hm',     th:'uz-thumb-hm'},
        '10k':   {input:'cert_10k',  name:'uz-name-10k',    up:'uz-up-10k',    ok:'uz-ok-10k',    tw:'uz-thumb-wrap-10k',    th:'uz-thumb-10k'},
        '5k':    {input:'cert_5k',   name:'uz-name-5k',     up:'uz-up-5k',     ok:'uz-ok-5k',     tw:'uz-thumb-wrap-5k',     th:'uz-thumb-5k'},
        trail:   {input:'trail_cert',name:'uz-name-trail',  up:'uz-up-trail',  ok:'uz-ok-trail',  tw:'uz-thumb-wrap-trail',  th:'uz-thumb-trail'},
        waiver:  {input:'waiver_file',name:'uz-name-waiver',up:'uz-up-waiver', ok:'uz-ok-waiver', tw:'uz-thumb-wrap-waiver', th:'uz-thumb-waiver'},
        bt_fm:   {input:'bt_fm',     name:'uz-name-bt-fm',  up:'uz-up-bt-fm',  ok:'uz-ok-bt-fm',  tw:'uz-thumb-wrap-bt-fm',  th:'uz-thumb-bt-fm'},
        bt_hm:   {input:'bt_hm',     name:'uz-name-bt-hm',  up:'uz-up-bt-hm',  ok:'uz-ok-bt-hm',  tw:'uz-thumb-wrap-bt-hm',  th:'uz-thumb-bt-hm'},
        bt_10k:  {input:'bt_10k',    name:'uz-name-bt-10k', up:'uz-up-bt-10k', ok:'uz-ok-bt-10k', tw:'uz-thumb-wrap-bt-10k', th:'uz-thumb-bt-10k'},
        bt_5k:   {input:'bt_5k',     name:'uz-name-bt-5k',  up:'uz-up-bt-5k',  ok:'uz-ok-bt-5k',  tw:'uz-thumb-wrap-bt-5k',  th:'uz-thumb-bt-5k'},
    };
    function _g(id){return document.getElementById(id);}
    function markDone(key,name){
        var m=_m[key];if(!m)return;
        var inp=_g(m.input),lbl=inp?inp.closest('label.upload-zone'):null;
        if(lbl){lbl.classList.add('done');lbl.classList.remove('err');}
        var n=_g(m.name);if(n)n.textContent='✓ '+name;
        var up=_g(m.up);if(up)up.style.display='none';
        var ok=_g(m.ok);if(ok)ok.style.display='';
    }
    function markEmpty(key){
        var m=_m[key];if(!m)return;
        var inp=_g(m.input),lbl=inp?inp.closest('label.upload-zone'):null;
        if(lbl){lbl.classList.remove('done','err');}
        var n=_g(m.name);if(n){n.textContent='Ketuk untuk upload';n.style.color='';}
        var up=_g(m.up);if(up)up.style.display='';
        var ok=_g(m.ok);if(ok)ok.style.display='none';
        var tw=_g(m.tw);if(tw)tw.classList.remove('show');
    }
    function showThumb(key,src){
        var m=_m[key];if(!m)return;
        var tw=_g(m.tw),th=_g(m.th);
        if(!tw||!th)return;
        if(src&&!src.startsWith('data:application')){th.src=src;tw.classList.add('show');}
    }
    function saveSession(key,file,dataURL){
        try{
            if(dataURL.length>MAX*1.37)return;
            sessionStorage.setItem('pacer_uz_'+key+'_meta',JSON.stringify({name:file.name,type:file.type||'image/jpeg',saved:Date.now()}));
            sessionStorage.setItem('pacer_uz_'+key+'_data',dataURL);
        }catch(e){}
    }
    function clearSession(key){
        try{sessionStorage.removeItem('pacer_uz_'+key+'_meta');sessionStorage.removeItem('pacer_uz_'+key+'_data');}catch(e){}
    }
    function fileChosen(key,input){
        if(!input.files||!input.files[0])return;
        var file=input.files[0];
        if(file.size>10*1024*1024){showToast('File terlalu besar. Maks 10MB.','error');input.value='';return;}
        if(file.type==='application/pdf'){
            markDone(key,file.name);
            saveSession(key,file,'data:application/pdf;placeholder');
            showToast('File "'+file.name+'" berhasil dipilih.','success');
            return;
        }
        var r=new FileReader();
        r.onload=function(e){
            var d=e.target.result;
            markDone(key,file.name);showThumb(key,d);saveSession(key,file,d);
            showToast('File "'+file.name+'" berhasil dipilih.','success');
        };
        r.onerror=function(){showToast('Gagal membaca file.','error');};
        r.readAsDataURL(file);
    }
    function remove(key){
        var m=_m[key];if(!m)return;
        var inp=_g(m.input);
        if(inp){try{inp.value='';}catch(e){}try{var dt=new DataTransfer();inp.files=dt.files;}catch(e){}}
        markEmpty(key);clearSession(key);showToast('File dihapus.','warn');
    }
    function restoreAll(){
        Object.keys(_m).forEach(function(key){
            try{
                var ms=sessionStorage.getItem('pacer_uz_'+key+'_meta');
                var d=sessionStorage.getItem('pacer_uz_'+key+'_data');
                if(!ms||!d)return;
                var meta=JSON.parse(ms);
                if(Date.now()-meta.saved>30*60*1000){clearSession(key);return;}
                fetch(d).then(function(r){return r.blob();}).then(function(b){
                    var f=new File([b],meta.name,{type:meta.type,lastModified:Date.now()});
                    var m=_m[key];if(!m)return;
                    var inp=_g(m.input);if(!inp)return;
                    try{var dt=new DataTransfer();dt.items.add(f);inp.files=dt.files;}catch(e){}
                    markDone(key,meta.name);showThumb(key,d);
                }).catch(function(){clearSession(key);});
            }catch(e){}
        });
    }
    function clearAll(){Object.keys(_m).forEach(function(key){clearSession(key);});}
    return{fileChosen,remove,restoreAll,clearAll};
})();

/* ═══════════════════════════════════════════════════════════════
   MILEAGE
═══════════════════════════════════════════════════════════════ */
var MILEAGE=(function(){
    'use strict';
    var KEYS=['dec_2025','jan_2026','feb_2026','mar_2026'];
    var MAX=450*1024;
    function _g(id){return document.getElementById(id);}
    function assignToInput(key,file){
        var inp=_g('mg_'+key);if(!inp)return false;
        try{var dt=new DataTransfer();dt.items.add(file);inp.files=dt.files;return true;}catch(e){return false;}
    }
    function markDone(key,name){
        var lbl=_g('mg-label-'+key),nm=_g('mg-name-'+key),blk=_g('mileage-block-'+key),up=_g('uz-up-mg-'+key),ok=_g('uz-ok-mg-'+key);
        if(lbl){lbl.classList.add('done');lbl.classList.remove('err');}
        if(nm)nm.textContent='✓ '+name;
        if(blk)blk.classList.add('uploaded');
        if(up)up.style.display='none';
        if(ok)ok.style.display='';
    }
    function markEmpty(key){
        var lbl=_g('mg-label-'+key),nm=_g('mg-name-'+key),blk=_g('mileage-block-'+key),up=_g('uz-up-mg-'+key),ok=_g('uz-ok-mg-'+key),tw=_g('mg-thumb-wrap-'+key),p=_g('mg-persisted-'+key);
        if(lbl){lbl.classList.remove('done','err');}
        if(nm){nm.textContent='Ketuk untuk upload grafik';nm.style.color='';}
        if(blk)blk.classList.remove('uploaded');
        if(up)up.style.display='';
        if(ok)ok.style.display='none';
        if(tw)tw.classList.remove('show');
        if(p)p.style.display='none';
    }
    function showThumb(key,src){
        var tw=_g('mg-thumb-wrap-'+key),th=_g('mg-thumb-'+key);
        if(!tw||!th)return;
        th.src=src;tw.classList.add('show');
    }
    function saveSession(key,file,dataURL){
        try{
            if(dataURL.length>MAX*1.37)return;
            sessionStorage.setItem('pacer_mg_'+key+'_meta',JSON.stringify({name:file.name,type:file.type||'image/jpeg',saved:Date.now()}));
            sessionStorage.setItem('pacer_mg_'+key+'_data',dataURL);
        }catch(e){}
    }
    function clearSession(key){
        try{sessionStorage.removeItem('pacer_mg_'+key+'_meta');sessionStorage.removeItem('pacer_mg_'+key+'_data');}catch(e){}
    }
    function fileChosen(key,input){
        if(!input.files||!input.files[0])return;
        var file=input.files[0];
        if(file.size>8*1024*1024){showToast('File terlalu besar. Maks 8MB.','error');input.value='';return;}
        var r=new FileReader();
        r.onload=function(e){
            var d=e.target.result;
            markDone(key,file.name);showThumb(key,d);saveSession(key,file,d);
            var p=_g('mg-persisted-'+key);if(p)p.style.display='none';
            showToast('Grafik '+key.replace(/_/g,' ')+' berhasil dipilih!','success');
        };
        r.onerror=function(){showToast('Gagal membaca file.','error');};
        r.readAsDataURL(file);
    }
    function remove(key){
        var inp=_g('mg_'+key);
        if(inp){try{inp.value='';}catch(e){}try{var dt=new DataTransfer();inp.files=dt.files;}catch(e){}}
        markEmpty(key);clearSession(key);showToast('File grafik dihapus.','warn');
    }
    function restoreFromSession(){
        KEYS.forEach(function(key){
            try{
                var ms=sessionStorage.getItem('pacer_mg_'+key+'_meta');
                var d=sessionStorage.getItem('pacer_mg_'+key+'_data');
                if(!ms||!d)return;
                var meta=JSON.parse(ms);
                if(Date.now()-meta.saved>30*60*1000){clearSession(key);return;}
                fetch(d).then(function(r){return r.blob();}).then(function(b){
                    var f=new File([b],meta.name,{type:meta.type,lastModified:Date.now()});
                    var ok=assignToInput(key,f);
                    if(ok){markDone(key,meta.name);showThumb(key,d);var p=_g('mg-persisted-'+key);if(p)p.style.display='block';}
                }).catch(function(){clearSession(key);});
            }catch(e){}
        });
    }
    function clearAllSession(){KEYS.forEach(function(key){clearSession(key);});}
    return{fileChosen,remove,restoreFromSession,clearAllSession};
})();

/* ═══════════════════════════════════════════════════════════════
   KTP OCR
═══════════════════════════════════════════════════════════════ */
var KTP=(function(){
    'use strict';
    var _file=null;
    function compress(file,cb){
        var MP=1600,MK=300,MQ=0.35;
        var r=new FileReader();
        r.onload=function(e){
            var img=new Image();
            img.onload=function(){
                var w=img.naturalWidth,h=img.naturalHeight;
                if(w>MP||h>MP){if(w>h){h=Math.round(h*MP/w);w=MP;}else{w=Math.round(w*MP/h);h=MP;}}
                var c=document.createElement('canvas');c.width=w;c.height=h;c.getContext('2d').drawImage(img,0,0,w,h);
                function tryQ(q){
                    c.toBlob(function(b){
                        if(!b){cb(file,e.target.result);return;}
                        if(b.size/1024>MK&&q-0.1>=MQ){tryQ(+(q-.1).toFixed(2));return;}
                        if(b.size/1024>MK){w=Math.round(w*.7);h=Math.round(h*.7);c.width=w;c.height=h;c.getContext('2d').drawImage(img,0,0,w,h);tryQ(0.55);return;}
                        var cv=new File([b],file.name.replace(/\.[^.]+$/,'')+'.jpg',{type:'image/jpeg',lastModified:Date.now()});
                        cb(cv,URL.createObjectURL(b));
                    },'image/jpeg',q);
                }
                tryQ(0.82);
            };
            img.onerror=function(){showToast('Format tidak didukung. Coba JPG.','warn');cb(null,null);};
            img.src=e.target.result;
        };
        r.onerror=function(){showToast('Gagal membaca file.','error');cb(null,null);};
        r.readAsDataURL(file);
    }
    function assignServer(file){
        var si=document.getElementById('ktpFileForServer');if(!si)return;
        try{var dt=new DataTransfer();dt.items.add(file);si.files=dt.files;}catch(e){}
    }
    function processFile(file){
        if(!file)return;
        if(file.size>10*1024*1024){showToast('File terlalu besar. Maks 10MB.','error');return;}
        _file=null;
        compress(file,function(cv,url){
            if(!cv)return;
            _file=cv;assignServer(cv);
            document.getElementById('ktpPreviewImg').src=url;
            document.getElementById('dzDefault').style.display='none';
            document.getElementById('dzPreview').classList.add('show');
            document.getElementById('btnScan').classList.add('show');
            document.getElementById('ktpDropzone').classList.add('has-file');
            resetData();showToast('Foto KTP siap. Klik SCAN KTP.','success');
        });
    }
    function fileChosen(input){if(input.files&&input.files[0])processFile(input.files[0]);input.value='';}
    function handleDrop(e){
        e.preventDefault();document.getElementById('ktpDropzone').classList.remove('drag-over');
        var f=e.dataTransfer&&e.dataTransfer.files[0];
        if(f&&f.type.startsWith('image/'))processFile(f);
        else showToast('Hanya file gambar yang diterima.','warn');
    }
    function reset(e){
        if(e)e.stopPropagation();_file=null;
        var si=document.getElementById('ktpFileForServer');
        if(si){try{si.value='';}catch(ee){}try{var dt=new DataTransfer();si.files=dt.files;}catch(ee){}}
        document.getElementById('dzDefault').style.display='';
        document.getElementById('dzPreview').classList.remove('show');
        document.getElementById('ktpPreviewImg').src='';
        document.getElementById('ktpDropzone').classList.remove('has-file');
        document.getElementById('btnScan').classList.remove('show');
        document.getElementById('scanLoading').classList.remove('show');
        document.getElementById('ktpOcrCard').classList.remove('scanned','scan-error');
        document.getElementById('scanBadge').classList.remove('show');
        resetData();
        ['fieldNama','fieldTglLahir','fieldNik'].forEach(function(id){var el=document.getElementById(id);if(el)el.value='';});
    }
    function resetData(){document.getElementById('ktpDataPanel').classList.remove('show','valid');document.getElementById('ktpDataRows').innerHTML='';}
    function scan(){
        if(!_file){showToast('Upload foto KTP terlebih dahulu.','warn');return;}
        document.getElementById('btnScan').classList.remove('show');document.getElementById('scanLoading').classList.add('show');resetData();
        var fd=new FormData();fd.append('image',_file,_file.name||'ktp.jpg');
        var csrf=(document.querySelector('meta[name="csrf-token"]')||{}).content||'';
        fetch('{{ route('ocr.ktp') }}',{method:'POST',headers:{'X-CSRF-TOKEN':csrf,'Accept':'application/json'},body:fd})
        .then(function(res){
            document.getElementById('scanLoading').classList.remove('show');document.getElementById('btnScan').classList.add('show');
            if(!res.ok)return res.json().catch(function(){return{};}).then(function(err){showToast(err.message||'HTTP '+res.status,'error');flashErr();});
            return res.json().then(function(result){
                if(!result.success){showToast(result.message||'Gagal baca KTP. Foto ulang lebih jelas.','error');flashErr();return;}
                renderData(result.data);
                document.getElementById('ktpOcrCard').classList.add('scanned');document.getElementById('scanBadge').classList.add('show');
                setField('fieldNama',result.data.nama||'');setField('fieldTglLahir',result.data.tanggal_lahir||'');setField('fieldNik',result.data.nik||'');
                showToast('KTP berhasil dibaca! Periksa data.','success');
            });
        })
        .catch(function(err){document.getElementById('scanLoading').classList.remove('show');document.getElementById('btnScan').classList.add('show');showToast('Tidak bisa konek ke OCR.','error');console.error('[OCR]',err);});
    }
    function flashErr(){var c=document.getElementById('ktpOcrCard');c.classList.add('scan-error');setTimeout(function(){c.classList.remove('scan-error');},600);}
    function renderData(data){
        var rows=document.getElementById('ktpDataRows');rows.innerHTML='';
        var FIELDS=[{label:'NIK',key:'nik',val:data.nik||''},{label:'Nama',key:'nama',val:data.nama||''},{label:'Tgl Lahir',key:'tanggal_lahir',val:data.tanggal_lahir||''},{label:'Tempat',key:'tempat_lahir',val:data.tempat_lahir||'',ro:true}];
        FIELDS.forEach(function(f){
            var ve='<span id="kv_'+f.key+'" class="ktp-val'+(f.val?'':' empty')+'"'+(f.ro?' style="cursor:default"':' onclick="KTP.editField(\''+f.key+'\')" title="Klik untuk edit"')+'>'+(f.val||(f.ro?'—':'(ketuk untuk isi)'))+'</span>';
            var ie=f.ro?'':'<input type="text" id="ki_'+f.key+'" class="ktp-inp" value="'+esc(f.val)+'" placeholder="'+esc(f.label)+'" onkeydown="KTP.inpKey(event,\''+f.key+'\')" onblur="KTP.saveField(\''+f.key+'\')">';
            rows.innerHTML+='<div class="ktp-row"><span class="ktp-lbl">'+f.label+'</span>'+ve+ie+'</div>';
        });
        var gn=data.jenis_kelamin||'',gc=gn==='L'?'gender-l':gn==='P'?'gender-p':'gender-u',gt=gn==='L'?'♂ Laki-laki':gn==='P'?'♀ Perempuan':'— tidak terbaca';
        rows.innerHTML+='<div class="ktp-row"><span class="ktp-lbl">Kelamin</span><span class="gender-badge '+gc+'">'+gt+'</span></div>';
        rows.innerHTML+='<div class="ktp-row"><span class="ktp-lbl">Kota KTP</span>'+(data.kota?'<span class="ktp-val" style="cursor:default;">'+esc(data.kota)+'</span>':'<span class="ktp-val empty" style="cursor:default;">—</span>')+'</div>';
        document.getElementById('ktpDataPanel').classList.add('show','valid');
    }
    function editField(key){var v=document.getElementById('kv_'+key),i=document.getElementById('ki_'+key);if(!v||!i)return;v.style.display='none';i.style.display='';i.focus();i.select&&i.select();}
    function saveField(key){var v=document.getElementById('kv_'+key),i=document.getElementById('ki_'+key);if(!v||!i)return;var nv=i.value.trim();v.textContent=nv||'(ketuk untuk isi)';v.className='ktp-val'+(nv?' edited':' empty');v.style.display='';i.style.display='none';if(key==='nama')setField('fieldNama',nv);if(key==='tanggal_lahir')setField('fieldTglLahir',nv);if(key==='nik')setField('fieldNik',nv);}
    function inpKey(e,key){if(e.key==='Enter'){e.preventDefault();saveField(key);}if(e.key==='Escape'){var v=document.getElementById('kv_'+key),i=document.getElementById('ki_'+key);if(i)i.style.display='none';if(v)v.style.display='';}}
    function setField(id,val){var el=document.getElementById(id);if(el)el.value=val;}
    function esc(s){return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');}
    function openSheet(){SHEET.open();}
    return{fileChosen,handleDrop,reset,scan,editField,saveField,inpKey,openSheet};
})();

/* ═══════════════════════════════════════════════════════════════
   INIT
═══════════════════════════════════════════════════════════════ */
document.addEventListener('DOMContentLoaded',function(){
    MILEAGE.restoreFromSession();
    UPLOAD.restoreAll();
    var form=document.getElementById('pacerForm');
    if(form){form.addEventListener('submit',function(){setTimeout(function(){MILEAGE.clearAllSession();UPLOAD.clearAll();},3000);});}
});
</script>

<script>
function regForm(){
    return{
        fm:'{{ old('is_full_marathon','') }}',
        hm:'{{ old('is_half_marathon','') }}',
        r10k:'{{ old('is_10k','') }}',
        r5k:'{{ old('is_5k','') }}',
        domisili: '{{ old('domisili', '') }}', // Tambahkan atau pastikan baris ini ada
        trailStatus:'{{ old('trail_status','') }}',
        pacerExp:'{{ old('is_pacer_experience','') }}',
        komitmen:'{{ old('komitmen','') }}',
        izinKeluarga:'{{ old('izin_keluarga','') }}',
        preferDist:@json(old('preferred_distance',[])),
        pernyataan:{{ old('pernyataan_keabsahan') ? 'true' : 'false' }},
    }
}
</script>
@endpush
@endsection