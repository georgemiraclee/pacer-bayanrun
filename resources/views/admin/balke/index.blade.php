@extends('layouts.admin')
@section('title', 'Blast WA — Balke Test')

@push('admin-styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&display=swap');

*, *::before, *::after { box-sizing: border-box; }

/* ─────────────────────────────────────────
   STAT GRID
───────────────────────────────────────── */
.stat-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 14px;
    margin-bottom: 22px;
}
@media(max-width:1100px){ .stat-grid { grid-template-columns: repeat(3,1fr); } }
@media(max-width:700px)  { .stat-grid { grid-template-columns: repeat(2,1fr); } }

.stat-card {
    background: #fff;
    border: 1px solid #EBEBEB;
    border-radius: 14px;
    padding: 18px 20px;
    display: flex; flex-direction: column; gap: 6px;
    box-shadow: 0 1px 3px rgba(0,0,0,.04);
    transition: box-shadow .2s, transform .2s;
}
.stat-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,.08); transform: translateY(-2px); }
.stat-card .s-label {
    font-family:'Syne',sans-serif; font-size:9px; font-weight:700;
    letter-spacing:.1em; text-transform:uppercase; color:#AAAAAA;
}
.stat-card .s-num { font-family:'Syne',sans-serif; font-size:34px; font-weight:800; line-height:1; }
.stat-card.total    { border-top:3px solid #111; }       .stat-card.total .s-num    { color:#111; }
.stat-card.terkirim { border-top:3px solid #25D366; }    .stat-card.terkirim .s-num { color:#25D366; }
.stat-card.belum    { border-top:3px solid #E8001E; }    .stat-card.belum .s-num    { color:#E8001E; }
.stat-card.no-wa    { border-top:3px solid #D97706; }    .stat-card.no-wa .s-num    { color:#D97706; }
.stat-card.gagal    { border-top:3px solid #6B7280; }    .stat-card.gagal .s-num    { color:#6B7280; }

/* ─────────────────────────────────────────
   CARD BASE
───────────────────────────────────────── */
.card {
    background: #fff;
    border: 1px solid #EBEBEB;
    border-radius: 14px;
    box-shadow: 0 1px 3px rgba(0,0,0,.04);
}

/* ─────────────────────────────────────────
   TOOLBAR
───────────────────────────────────────── */
.toolbar {
    margin-bottom: 18px; padding: 16px 20px;
    display: flex; flex-wrap: wrap; gap: 10px; align-items: flex-end;
}
.f-label {
    font-family:'Syne',sans-serif; font-size:9px; font-weight:700;
    letter-spacing:.1em; text-transform:uppercase; color:#AAAAAA;
    display: block; margin-bottom: 4px;
}
select, input[type=text], input[type=time] {
    font-family:'DM Sans',sans-serif; font-size:13px; color:#111;
    background:#FAFAFA; border:1.5px solid #E8E8E8; border-radius:8px;
    padding:7px 12px; outline:none; transition:border-color .15s;
    -webkit-appearance:none; appearance:none;
}
select:focus, input[type=text]:focus, input[type=time]:focus {
    border-color:#E8001E; background:#fff;
}

/* ─────────────────────────────────────────
   BUTTONS
───────────────────────────────────────── */
.btn {
    display: inline-flex; align-items: center; gap: 6px;
    border: none; border-radius: 8px; padding: 8px 14px;
    font-family:'Syne',sans-serif; font-size:10px; font-weight:700;
    letter-spacing:.07em; text-transform:uppercase;
    cursor: pointer; transition: all .15s;
    text-decoration: none; white-space: nowrap;
}
.btn-red     { background:#E8001E; color:#fff; }
.btn-red:hover     { background:#C0001A; color:#fff; }
.btn-green   { background:#16A34A; color:#fff; }
.btn-green:hover   { background:#15803D; color:#fff; }
.btn-wa      { background:#25D366; color:#fff; }
.btn-wa:hover      { background:#1DA851; color:#fff; }
.btn-gray    { background:#F5F5F5; color:#777; }
.btn-gray:hover    { background:#E8E8E8; color:#444; }
.btn-outline { background:#fff; color:#666; border:1.5px solid #E8E8E8; }
.btn-outline:hover { border-color:#E8001E; color:#E8001E; }
.btn-danger  { background:#FFF0F2; color:#E8001E; border:1.5px solid #FFCCD2; }
.btn-danger:hover  { background:#E8001E; color:#fff; border-color:#E8001E; }

/* ─────────────────────────────────────────
   BLAST INFO BANNER
───────────────────────────────────────── */
.blast-info {
    background: #F0FDF4;
    border: 1.5px solid #BBF7D0;
    border-radius: 12px;
    padding: 16px 20px;
    margin-bottom: 18px;
    display: flex; align-items: flex-start; gap: 14px;
}
.blast-info-icon {
    width: 40px; height: 40px; border-radius: 10px;
    background: #25D366; display: flex; align-items: center;
    justify-content: center; flex-shrink: 0;
}
.blast-info-title {
    font-family:'Syne',sans-serif; font-size:13px; font-weight:700; color:#111;
    margin-bottom: 4px;
}
.blast-info-sub { font-size:12px; color:#555; line-height:1.6; }

/* ─────────────────────────────────────────
   IMPORT CARD
───────────────────────────────────────── */
.import-card { padding: 22px; margin-bottom: 18px; }
.import-card-header {
    display: flex; align-items: flex-start; justify-content: space-between;
    margin-bottom: 16px; flex-wrap: wrap; gap: 12px;
}
.import-card-title {
    font-family:'Syne',sans-serif; font-size:12px; font-weight:700;
    letter-spacing:.08em; text-transform:uppercase; color:#888;
}
.import-card-sub { font-size:12px; color:#AAAAAA; margin-top:3px; }

.import-zone {
    border: 2px dashed #E0E0E0; border-radius: 12px;
    padding: 28px 20px; text-align: center;
    cursor: pointer; transition: all .2s; background: #FAFAFA;
}
.import-zone:hover { border-color: #E8001E; background: #FFF0F2; }
.import-zone.drag  { border-color: #E8001E; background: #FFF0F2; }
.import-zone-icon  {
    width:44px; height:44px; background:#FFF0F2; border-radius:10px;
    display:flex; align-items:center; justify-content:center; margin:0 auto 12px;
}

/* ─────────────────────────────────────────
   RESET ZONE
───────────────────────────────────────── */
.reset-zone {
    background:#FFF0F2; border:1.5px solid #FFCCD2; border-radius:12px;
    padding:16px 20px; display:flex; align-items:center; gap:12px; flex-wrap:wrap;
}
.reset-zone input[type=text] { border-color:#FFCCD2; }

/* ─────────────────────────────────────────
   TABLE
───────────────────────────────────────── */
.table-card { overflow: hidden; }
.table-head-bar {
    padding: 14px 20px; border-bottom: 1px solid #F5F5F5;
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 10px;
}
.table-head-bar h2 {
    font-family:'Syne',sans-serif; font-size:12px; font-weight:700; color:#111;
}
table { width:100%; border-collapse:collapse; }
thead tr { border-bottom:1px solid #F0F0F0; background:#FAFAFA; }
thead th {
    padding: 9px 14px; text-align:left;
    font-family:'Syne',sans-serif; font-size:9px; font-weight:700;
    letter-spacing:.1em; text-transform:uppercase; color:#AAAAAA; white-space:nowrap;
}
tbody tr { border-bottom:1px solid #F8F8F8; transition:background .1s; }
tbody tr:hover { background:#FAFAFA; }
tbody td { padding: 10px 14px; }
.cand-name { font-size:14px; font-weight:500; color:#111; }
.cand-wa   { font-size:11px; color:#AAAAAA; margin-top:1px; }
.time-badge {
    font-family:'Syne',sans-serif; font-size:11px; font-weight:700;
    color:#E8001E; background:#FFF0F2; padding:3px 8px; border-radius:6px;
    display:inline-block;
}
.date-badge {
    font-family:'Syne',sans-serif; font-size:11px; font-weight:700;
    color:#2563EB; background:#EFF6FF; padding:3px 8px; border-radius:6px;
    display:inline-block;
}

/* ─────────────────────────────────────────
   BADGES
───────────────────────────────────────── */
.badge {
    display:inline-flex; align-items:center; gap:4px;
    padding:4px 9px; border-radius:100px;
    font-family:'Syne',sans-serif; font-size:9px; font-weight:700;
}
.badge-sent  { background:#DCFCE7; color:#15803D; }
.badge-notel { background:#FEF3C7; color:#D97706; }
.badge-belum { background:#F3F4F6; color:#6B7280; }
.badge-fail  { background:#FEE2E2; color:#B91C1C; }

/* ─────────────────────────────────────────
   ACTION BUTTONS IN TABLE
───────────────────────────────────────── */
.wa-link {
    display:inline-flex; align-items:center; gap:5px;
    background:#25D366; color:#fff; padding:5px 10px;
    border-radius:7px; font-family:'Syne',sans-serif; font-size:9px;
    font-weight:700; text-decoration:none; transition:background .15s;
    border:none; cursor:pointer; white-space:nowrap;
}
.wa-link:hover  { background:#1DA851; }
.wa-link:disabled { opacity:.4; cursor:not-allowed; }

.del-btn {
    display:inline-flex; align-items:center; gap:5px;
    background:#FFF0F2; color:#E8001E; padding:5px 9px;
    border-radius:7px; font-family:'Syne',sans-serif; font-size:9px;
    font-weight:700; border:1.5px solid #FFCCD2; cursor:pointer;
    transition:all .15s; white-space:nowrap;
}
.del-btn:hover { background:#E8001E; color:#fff; border-color:#E8001E; }

/* ─────────────────────────────────────────
   CHECKBOX
───────────────────────────────────────── */
.cb-all, .cb-row {
    cursor:pointer; accent-color:#25D366; width:15px; height:15px;
}

/* ─────────────────────────────────────────
   BULK BAR
───────────────────────────────────────── */
.bulk-bar {
    display: none; align-items: center; gap: 10px; flex-wrap: wrap;
    padding: 10px 20px; background: #F0FFF4;
    border: 1px solid #BBF7D0; border-radius: 10px;
    margin-bottom: 14px; animation: slideDown .2s ease;
}
.bulk-bar.visible { display: flex; }
.bulk-bar-count {
    font-family:'Syne',sans-serif; font-size:11px; font-weight:700;
    color:#15803D; flex:1;
}
@keyframes slideDown {
    from { opacity:0; transform:translateY(-6px); }
    to   { opacity:1; transform:translateY(0); }
}

/* ─────────────────────────────────────────
   PROGRESS BAR
───────────────────────────────────────── */
.progress-wrap {
    background:#F0F0F0; border-radius:999px; overflow:hidden;
    height:8px; margin-bottom:6px;
}
.progress-bar {
    height:100%; background:#25D366; border-radius:999px;
    transition: width .5s ease;
}

/* ─────────────────────────────────────────
   MODAL BASE
───────────────────────────────────────── */
.modal-overlay {
    display: none; position: fixed; inset:0; z-index:9999;
    background: rgba(0,0,0,.5); backdrop-filter: blur(4px);
    align-items: center; justify-content: center; padding: 16px;
}
.modal-overlay.open { display: flex; }
.modal-box {
    background: #fff; border-radius: 20px; padding: 28px;
    width: 100%; max-width: 460px;
    box-shadow: 0 24px 80px rgba(0,0,0,.3);
    animation: cardIn .3s cubic-bezier(.34,1.1,.64,1) both;
}
.modal-box.modal-sm { max-width: 400px; }
@keyframes cardIn {
    from { opacity:0; transform:scale(.94) translateY(8px); }
    to   { opacity:1; transform:scale(1) translateY(0); }
}
.modal-title {
    font-family:'Syne',sans-serif; font-size:14px; font-weight:800; color:#111;
    margin-bottom:4px;
}
.modal-sub { font-size:12px; color:#AAAAAA; margin-top:3px; margin-bottom:20px; }
.modal-header {
    display:flex; align-items:flex-start; justify-content:space-between;
    margin-bottom:20px; gap:12px;
}
.modal-close {
    width:32px; height:32px; border-radius:50%;
    border:1.5px solid #E8E8E8; background:#F5F5F5;
    cursor:pointer; display:flex; align-items:center; justify-content:center;
    font-size:18px; color:#888; transition:all .15s; line-height:1; flex-shrink:0;
}
.modal-close:hover { background:#E8001E; color:#fff; border-color:#E8001E; }
.modal-footer { display:flex; gap:10px; margin-top:22px; }

/* ─────────────────────────────────────────
   DANGER ICON (modal)
───────────────────────────────────────── */
.danger-icon {
    width:52px; height:52px; background:#FFF0F2; border-radius:50%;
    display:flex; align-items:center; justify-content:center;
    margin:0 auto 16px; font-size:22px;
}

/* ─────────────────────────────────────────
   FORM FIELDS
───────────────────────────────────────── */
.field-group { display:flex; flex-direction:column; gap:14px; }
.field { display:flex; flex-direction:column; gap:4px; }
.field-row { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
.field select, .field input { width:100%; }

/* ─────────────────────────────────────────
   ROW REMOVING ANIMATION
───────────────────────────────────────── */
.row-removing {
    transition: opacity .3s ease, transform .3s ease !important;
    opacity: 0 !important;
    transform: translateX(12px) !important;
}

/* ─────────────────────────────────────────
   RESPONSIVE
───────────────────────────────────────── */
@media(max-width:640px){
    .toolbar { padding:12px 14px; }
    .field-row { grid-template-columns:1fr; }
    table thead { display:none; }
    table, tbody, tr, td { display:block; width:100%; }
    tbody tr {
        border:1px solid #EBEBEB; border-radius:12px;
        margin-bottom:10px; padding:12px 14px; background:#fff;
    }
    tbody td { padding:4px 0; border:none; font-size:13px; }
}
</style>
@endpush

@section('content')

{{-- ════════════════════════════════════════════
     TOAST
════════════════════════════════════════════ --}}
@if(session('success'))
<div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,5000)"
     x-transition:leave="transition duration-300" x-transition:leave-end="opacity-0 -translate-y-2"
     style="position:fixed;top:18px;right:18px;z-index:9999;background:#0D0D0D;color:#fff;padding:12px 18px;border-radius:12px;font-size:13px;max-width:340px;box-shadow:0 8px 40px rgba(0,0,0,.2);border-left:3px solid #16A34A;display:flex;align-items:center;gap:8px;">
    <svg width="16" height="16" fill="none" stroke="#4ADE80" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
    </svg>
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,5000)"
     style="position:fixed;top:18px;right:18px;z-index:9999;background:#0D0D0D;color:#fff;padding:12px 18px;border-radius:12px;font-size:13px;max-width:340px;box-shadow:0 8px 40px rgba(0,0,0,.2);border-left:3px solid #E8001E;display:flex;align-items:center;gap:8px;">
    <svg width="16" height="16" fill="none" stroke="#F87171" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
    </svg>
    {{ session('error') }}
</div>
@endif

{{-- ════════════════════════════════════════════
     STAT CARDS
════════════════════════════════════════════ --}}
<div class="stat-grid">
    <div class="stat-card total">
        <span class="s-label">Total Kandidat</span>
        <span class="s-num">{{ $stats['total'] }}</span>
    </div>
    <div class="stat-card terkirim">
        <span class="s-label">WA Terkirim</span>
        <span class="s-num">{{ $stats['terkirim'] }}</span>
    </div>
    <div class="stat-card belum">
        <span class="s-label">Belum Terkirim</span>
        <span class="s-num">{{ $stats['belum'] }}</span>
    </div>
    <div class="stat-card no-wa">
        <span class="s-label">Tanpa No WA</span>
        <span class="s-num">{{ $stats['no_wa'] }}</span>
    </div>
    <div class="stat-card gagal">
        <span class="s-label">Gagal Kirim</span>
        <span class="s-num">{{ $stats['gagal'] }}</span>
    </div>
</div>

{{-- ════════════════════════════════════════════
     IMPORT CARD
════════════════════════════════════════════ --}}
<div class="card import-card" style="margin-bottom:18px;">
    <div class="import-card-header">
        <div>
            <p class="import-card-title">Import Data Balke Test</p>
            <p class="import-card-sub">Upload Excel dari panitia. Data yang sudah ada tidak akan duplikat.</p>
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
            <a href="{{ route('admin.balke.export') }}{{ request()->getQueryString() ? '?'.request()->getQueryString() : '' }}"
               class="btn btn-green">
                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export CSV
            </a>
            <button type="button" class="btn btn-red" onclick="openModal('modalTambah')">
                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Manual
            </button>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.balke.import') }}" enctype="multipart/form-data" id="importForm">
        @csrf
        <div class="import-zone" id="importZone"
             onclick="document.getElementById('excelFile').click()"
             ondragover="event.preventDefault();this.classList.add('drag')"
             ondragleave="this.classList.remove('drag')"
             ondrop="handleDrop(event)">
            <div class="import-zone-icon">
                <svg width="22" height="22" fill="none" stroke="#E8001E" stroke-width="1.6" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
            </div>
            <p id="importZoneText" style="font-size:13px;font-weight:500;color:#666;">
                Klik atau drag file Excel jadwal Balke Test
            </p>
            <p style="font-size:11px;color:#BBB;margin-top:4px;">
                Balke_Test_Pacer_Bayan_Run_2026.xlsx · Maks 10MB
            </p>
        </div>
        <input type="file" id="excelFile" name="excel_file" accept=".xlsx,.xls"
               style="display:none" onchange="onFileSelect(this)">
        @error('excel_file')
            <span style="font-size:12px;color:#E8001E;display:block;margin-top:6px;">{{ $message }}</span>
        @enderror
        <button type="submit" id="uploadBtn" class="btn btn-red"
                style="display:none;margin-top:12px;width:100%;justify-content:center;padding:11px;">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
            </svg>
            Upload & Import Sekarang
        </button>
    </form>

    {{-- Zona Bahaya --}}
    <details style="margin-top:16px;">
        <summary style="font-family:'Syne',sans-serif;font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#AAAAAA;cursor:pointer;user-select:none;">
            ⚠ Zona Bahaya — Hapus Semua Data Balke Test
        </summary>
        <div class="reset-zone" style="margin-top:10px;">
            <form method="POST" action="{{ route('admin.balke.reset') }}"
                  onsubmit="return confirmReset(event)"
                  style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end;width:100%;">
                @csrf
                <div style="flex:1;min-width:160px;">
                    <label class="f-label" style="color:#E8001E;">Ketik HAPUS untuk konfirmasi</label>
                    <input type="text" name="confirm" placeholder="HAPUS"
                           id="resetConfirmInput" style="border-color:#FFCCD2;width:100%;">
                </div>
                <button type="submit" class="btn btn-red" style="padding:8px 16px;">
                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Hapus Semua
                </button>
            </form>
        </div>
    </details>
</div>

{{-- ════════════════════════════════════════════
     BLAST INFO BANNER
════════════════════════════════════════════ --}}
<div class="blast-info">
    <div class="blast-info-icon">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="white">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413z"/>
        </svg>
    </div>
    <div>
        <p class="blast-info-title">
            Blast WA Balke Test — Template
            <code style="background:#E8F5E9;color:#16A34A;padding:2px 6px;border-radius:4px;font-size:11px;">balke_test</code>
        </p>
        <p class="blast-info-sub">
            Kirim undangan Balke Test via Qiscus ke kandidat Pacer Bayan Run 2026.
            Template WA akan dikirim ke semua kandidat yang memiliki nomor WhatsApp.
            Blast dilakukan per {{ config('qiscus.blast_delay_ms', 1500) }}ms untuk menghindari rate-limit.
        </p>
    </div>
</div>

{{-- ════════════════════════════════════════════
     FILTER TOOLBAR
════════════════════════════════════════════ --}}
<div class="card toolbar" style="margin-bottom:14px;">
    <form method="GET" action="{{ route('admin.balke.index') }}"
          style="display:flex;flex-wrap:wrap;gap:10px;align-items:flex-end;width:100%;">
        <div style="flex:1;min-width:180px;">
            <span class="f-label">Cari Nama / WA</span>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Nama atau nomor WA..." style="width:100%;">
        </div>
        <div>
            <span class="f-label">Status WA</span>
            <select name="status">
                <option value="">Semua</option>
                <option value="terkirim" {{ request('status')==='terkirim' ? 'selected' : '' }}>Terkirim</option>
                <option value="belum"    {{ request('status')==='belum'    ? 'selected' : '' }}>Belum Kirim</option>
                <option value="no_wa"   {{ request('status')==='no_wa'    ? 'selected' : '' }}>Tanpa No WA</option>
            </select>
        </div>
        <div style="display:flex;gap:8px;align-items:center;padding-bottom:1px;flex-wrap:wrap;">
            <button type="submit" class="btn btn-red">
                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                </svg>
                Filter
            </button>
            <a href="{{ route('admin.balke.index') }}" class="btn btn-gray">Reset</a>
            <button type="button" class="btn btn-wa" onclick="blastAllFiltered()">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413z"/>
                </svg>
                Blast Semua (Filter Aktif)
            </button>
        </div>
    </form>
</div>

{{-- ════════════════════════════════════════════
     BULK ACTION BAR
════════════════════════════════════════════ --}}
<div class="bulk-bar" id="bulkBar">
    <span class="bulk-bar-count" id="bulkCount">0 kandidat dipilih</span>
    <button type="button" class="btn btn-wa" onclick="blastSelected()">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413z"/>
        </svg>
        Blast WA Terpilih
    </button>
    <button type="button" class="btn btn-danger" onclick="openDeleteBatchModal()">
        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
        </svg>
        Hapus Terpilih
    </button>
    <button type="button" class="btn btn-gray" onclick="clearSelection()" style="margin-left:auto;">
        Batal
    </button>
</div>

{{-- ════════════════════════════════════════════
     CANDIDATE TABLE
════════════════════════════════════════════ --}}
<div class="card table-card">
    <div class="table-head-bar">
        <h2>Kandidat Balke Test</h2>
        <span style="font-size:12px;color:#AAAAAA;">{{ $candidates->total() }} kandidat</span>
    </div>

    @if($candidates->isEmpty())
    <div style="text-align:center;padding:56px 20px;">
        <div style="font-size:36px;margin-bottom:12px;">🏃</div>
        <p style="font-family:'Syne',sans-serif;font-size:14px;font-weight:700;color:#333;margin-bottom:6px;">
            Belum ada kandidat Balke Test
        </p>
        <p style="font-size:13px;color:#AAAAAA;">
            Tambahkan kandidat manual menggunakan tombol di atas.
        </p>
    </div>
    @else
    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th style="width:36px;">
                        <input type="checkbox" class="cb-all" id="cbAll" onchange="toggleAll(this)">
                    </th>
                    <th>#</th>
                    <th>Kandidat</th>
                    <th>Tanggal Test</th>
                    <th>Jam</th>
                    <th>Status WA</th>
                    <th>Blast WA</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($candidates as $c)
                <tr id="row-{{ $c->id }}">
                    {{-- Checkbox --}}
                    <td>
                        <input type="checkbox" class="cb-row" value="{{ $c->id }}"
                               onchange="onCheckChange()"
                               {{ empty($c->no_wa) ? 'disabled' : '' }}>
                    </td>

                    {{-- No urut --}}
                    <td style="color:#CCC;font-size:12px;">{{ $loop->iteration }}</td>

                    {{-- Nama + WA --}}
                    <td>
                        <div class="cand-name">{{ $c->nama }}</div>
                        <div class="cand-wa">{{ $c->no_wa ?? '— Tanpa No WA' }}</div>
                    </td>

                    {{-- Tanggal --}}
                    <td>
                        <span class="date-badge">{{ $c->tanggal_balke ?? '—' }}</span>
                    </td>

                    {{-- Jam --}}
                    <td>
                        <span class="time-badge">{{ $c->jam_balke ?? '—' }} WITA</span>
                    </td>

                    {{-- Status WA --}}
                    <td>
                        @if(empty($c->no_wa))
                            <span class="badge badge-notel">
                                <svg width="9" height="9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 9v4m0 4h.01"/>
                                </svg>
                                No WA kosong
                            </span>
                        @elseif($c->balke_wa_sent)
                            <span class="badge badge-sent">
                                <svg width="9" height="9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                                Terkirim
                            </span>
                            <div style="font-size:10px;color:#CCCCCC;margin-top:2px;">
                                {{ $c->balke_wa_sent_at?->format('d M, H:i') }}
                            </div>
                        @elseif($c->balke_wa_failed)
                            <span class="badge badge-fail">
                                <svg width="9" height="9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Gagal
                            </span>
                        @else
                            <span class="badge badge-belum">— Belum</span>
                        @endif
                    </td>

                    {{-- Blast WA button --}}
                    <td>
                        <button class="wa-link" id="blast-btn-{{ $c->id }}"
                                onclick="blastSingle({{ $c->id }}, this)"
                                {{ empty($c->no_wa) ? 'disabled' : '' }}
                                style="{{ empty($c->no_wa) ? 'opacity:.4;cursor:not-allowed;' : '' }}">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413z"/>
                            </svg>
                            <span id="blast-label-{{ $c->id }}">
                                {{ $c->balke_wa_sent ? 'Kirim Ulang' : 'Blast WA' }}
                            </span>
                        </button>
                    </td>

                    {{-- Aksi: Hapus --}}
                    <td>
                        <button class="del-btn"
                                onclick="openDeleteModal({{ $c->id }}, '{{ addslashes($c->nama) }}')"
                                title="Hapus kandidat ini">
                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Hapus
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="padding:14px 20px;border-top:1px solid #F5F5F5;">
        {{ $candidates->links() }}
    </div>
    @endif
</div>


{{-- ════════════════════════════════════════════
     MODAL: TAMBAH KANDIDAT MANUAL
════════════════════════════════════════════ --}}
<div class="modal-overlay" id="modalTambah" onclick="handleOverlayClick(event,'modalTambah')">
    <div class="modal-box">
        <div class="modal-header">
            <div>
                <p class="modal-title">Tambah Kandidat Manual</p>
                <p class="modal-sub">Isi data kandidat Balke Test secara manual</p>
            </div>
            <button class="modal-close" onclick="closeModal('modalTambah')">×</button>
        </div>

        <form method="POST" action="{{ route('admin.balke.store-manual') }}">
            @csrf
            <div class="field-group">

                <div class="field">
                    <label class="f-label">Nama Lengkap *</label>
                    <input type="text" name="nama" required placeholder="Nama kandidat">
                </div>

                <div class="field">
                    <label class="f-label">Nomor WhatsApp</label>
                    <input type="text" name="no_wa" placeholder="08xxxxxxxxxx">
                </div>

                <div class="field-row">
                    <div class="field">
                        <label class="f-label">Tanggal Test *</label>
                        <input type="text" name="tanggal_balke" required placeholder="Sabtu, 10 Mei 2026">
                    </div>
                    <div class="field">
                        <label class="f-label">Jam *</label>
                        <input type="time" name="jam_balke" required style="width:100%;">
                    </div>
                </div>

            </div>

            <div style="display:flex;gap:10px;margin-top:22px;">
                <button type="button" onclick="closeModal('modalTambah')"
                        class="btn btn-gray" style="flex:1;justify-content:center;padding:11px;">
                    Batal
                </button>
                <button type="submit" class="btn btn-red"
                        style="flex:2;justify-content:center;padding:11px;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Kandidat
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ════════════════════════════════════════════
     MODAL: BLAST PROGRESS
════════════════════════════════════════════ --}}
<div class="modal-overlay" id="modalProgress">
    <div class="modal-box">
        <button class="modal-close" id="modalCloseBtn"
                onclick="closeProgress()"
                style="margin-bottom:10px;float:right;display:none;">×</button>
        <p class="modal-title">Blast WA Balke Test</p>
        <p class="modal-sub" id="progressSub">Menyiapkan pengiriman...</p>

        <div class="progress-wrap">
            <div class="progress-bar" id="progressBar" style="width:0%"></div>
        </div>
        <div style="display:flex;justify-content:space-between;font-size:11px;color:#AAAAAA;margin-bottom:20px;">
            <span id="progressCount">0 / 0</span>
            <span id="progressPct">0%</span>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:18px;">
            <div style="background:#F0FDF4;border-radius:10px;padding:12px 16px;text-align:center;">
                <div style="font-family:'Syne',sans-serif;font-size:9px;font-weight:700;color:#AAAAAA;text-transform:uppercase;letter-spacing:.1em;margin-bottom:4px;">Berhasil</div>
                <div style="font-family:'Syne',sans-serif;font-size:28px;font-weight:800;color:#16A34A;" id="countBerhasil">0</div>
            </div>
            <div style="background:#FEF2F2;border-radius:10px;padding:12px 16px;text-align:center;">
                <div style="font-family:'Syne',sans-serif;font-size:9px;font-weight:700;color:#AAAAAA;text-transform:uppercase;letter-spacing:.1em;margin-bottom:4px;">Gagal</div>
                <div style="font-family:'Syne',sans-serif;font-size:28px;font-weight:800;color:#DC2626;" id="countGagal">0</div>
            </div>
        </div>

        <div id="blastLog"
             style="background:#F9FAFB;border-radius:8px;padding:10px 12px;max-height:160px;overflow-y:auto;font-size:11px;font-family:monospace;color:#555;line-height:1.7;display:none;">
        </div>

        <button type="button" id="doneBtn" class="btn btn-wa"
                style="width:100%;justify-content:center;padding:11px;margin-top:16px;display:none;"
                onclick="closeProgress()">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
            Selesai — Tutup
        </button>
    </div>
</div>

{{-- ════════════════════════════════════════════
     MODAL: KONFIRMASI HAPUS SINGLE
════════════════════════════════════════════ --}}
<div class="modal-overlay" id="modalHapusSingle" onclick="handleOverlayClick(event,'modalHapusSingle')">
    <div class="modal-box modal-sm">
        <div style="text-align:center;padding-bottom:4px;">
            <div class="danger-icon">🗑️</div>
            <p class="modal-title" style="text-align:center;">Hapus Kandidat?</p>
            <p style="font-size:13px;color:#AAAAAA;margin-top:6px;line-height:1.6;">
                Kamu akan menghapus<br>
                <strong id="hapusNama" style="color:#111;font-size:14px;"></strong><br>
                <span style="color:#E8001E;font-weight:600;font-size:12px;">Tindakan ini tidak bisa dibatalkan!</span>
            </p>
        </div>
        <form id="hapusSingleForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-footer">
                <button type="button" onclick="closeModal('modalHapusSingle')"
                        class="btn btn-gray" style="flex:1;justify-content:center;padding:11px;">
                    Batal
                </button>
                <button type="submit" class="btn btn-red"
                        style="flex:1;justify-content:center;padding:11px;">
                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Ya, Hapus
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ════════════════════════════════════════════
     MODAL: KONFIRMASI HAPUS BATCH
════════════════════════════════════════════ --}}
<div class="modal-overlay" id="modalHapusBatch" onclick="handleOverlayClick(event,'modalHapusBatch')">
    <div class="modal-box modal-sm">
        <div style="text-align:center;padding-bottom:4px;">
            <div class="danger-icon">⚠️</div>
            <p class="modal-title" style="text-align:center;">Hapus Kandidat Terpilih?</p>
            <p style="font-size:13px;color:#AAAAAA;margin-top:6px;line-height:1.6;">
                Kamu akan menghapus
                <strong id="hapusBatchCount" style="color:#E8001E;"></strong>.<br>
                <span style="color:#E8001E;font-weight:600;font-size:12px;">Tindakan ini tidak bisa dibatalkan!</span>
            </p>
        </div>
        <div class="modal-footer">
            <button type="button" onclick="closeModal('modalHapusBatch')"
                    class="btn btn-gray" style="flex:1;justify-content:center;padding:11px;">
                Batal
            </button>
            <button type="button" onclick="confirmHapusBatch()"
                    class="btn btn-red" style="flex:1;justify-content:center;padding:11px;">
                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Ya, Hapus Semua
            </button>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════
     SCRIPTS
════════════════════════════════════════════ --}}
@push('admin-scripts')
<script>
var kandidatData = @json($candidateData);

/* ────────────────────────────────────────────
   MODAL HELPERS
──────────────────────────────────────────── */
function openModal(id)  { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }
function handleOverlayClick(e, id) {
    if (e.target === document.getElementById(id)) closeModal(id);
}

/* ────────────────────────────────────────────
   BLAST PROGRESS MODAL
──────────────────────────────────────────── */
function openProgress() { document.getElementById('modalProgress').classList.add('open'); }
function closeProgress() {
    document.getElementById('modalProgress').classList.remove('open');
    location.reload();
}

/* ────────────────────────────────────────────
   CHECKBOX / SELECT ALL
──────────────────────────────────────────── */
function toggleAll(cb) {
    document.querySelectorAll('.cb-row:not(:disabled)').forEach(c => c.checked = cb.checked);
    updateBulkBar();
}
function onCheckChange() {
    var all     = document.querySelectorAll('.cb-row:not(:disabled)');
    var checked = document.querySelectorAll('.cb-row:checked');
    document.getElementById('cbAll').checked =
        all.length > 0 && checked.length === all.length;
    updateBulkBar();
}
function updateBulkBar() {
    var checked = document.querySelectorAll('.cb-row:checked');
    var bar = document.getElementById('bulkBar');
    var cnt = document.getElementById('bulkCount');
    if (checked.length > 0) {
        bar.classList.add('visible');
        cnt.textContent = checked.length + ' kandidat dipilih';
    } else {
        bar.classList.remove('visible');
    }
}
function clearSelection() {
    document.querySelectorAll('.cb-row').forEach(c => c.checked = false);
    document.getElementById('cbAll').checked = false;
    updateBulkBar();
}

/* ────────────────────────────────────────────
   HAPUS SINGLE
──────────────────────────────────────────── */
function openDeleteModal(id, nama) {
    document.getElementById('hapusNama').textContent = nama;
    document.getElementById('hapusSingleForm').action = '/admin/balke/' + id;
    openModal('modalHapusSingle');
}

/* ────────────────────────────────────────────
   HAPUS BATCH
──────────────────────────────────────────── */
function openDeleteBatchModal() {
    var checked = document.querySelectorAll('.cb-row:checked');
    if (checked.length === 0) {
        showToast('Pilih kandidat dulu.', 'error');
        return;
    }
    document.getElementById('hapusBatchCount').textContent =
        checked.length + ' kandidat';
    openModal('modalHapusBatch');
}

function confirmHapusBatch() {
    var ids = [...document.querySelectorAll('.cb-row:checked')]
        .map(c => parseInt(c.value));

    closeModal('modalHapusBatch');

    fetch('{{ route('admin.balke.destroy-batch') }}', {
        method:  'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ ids: ids })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            ids.forEach(function(id) {
                var row = document.getElementById('row-' + id);
                if (row) {
                    row.classList.add('row-removing');
                    setTimeout(function() { row.remove(); }, 320);
                }
            });
            clearSelection();
            setTimeout(function() { location.reload(); }, 1200);
        } else {
            showToast(data.message || 'Gagal menghapus.', 'error');
        }
    })
    .catch(function() {
        showToast('Terjadi kesalahan jaringan.', 'error');
    });
}

/* ────────────────────────────────────────────
   BLAST — CORE
──────────────────────────────────────────── */
function runBlast(ids) {
    if (ids.length === 0) {
        showToast('Tidak ada kandidat dengan nomor WA.', 'error');
        return;
    }
    if (!confirm('Blast WA Balke Test ke ' + ids.length + ' kandidat via Qiscus?')) return;

    openProgress();
    document.getElementById('progressSub').textContent     = 'Mengirim ke ' + ids.length + ' kandidat...';
    document.getElementById('progressCount').textContent   = '0 / ' + ids.length;
    document.getElementById('progressPct').textContent     = '0%';
    document.getElementById('progressBar').style.width     = '0%';
    document.getElementById('countBerhasil').textContent   = '0';
    document.getElementById('countGagal').textContent      = '0';
    document.getElementById('blastLog').style.display      = 'none';
    document.getElementById('blastLog').innerHTML          = '';
    document.getElementById('doneBtn').style.display       = 'none';
    document.getElementById('modalCloseBtn').style.display = 'none';

    fetch('{{ route('admin.balke.blast-batch') }}', {
        method:  'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ ids: ids })
    })
    .then(r => r.json())
    .then(function(data) {
        var b = data.berhasil || 0;
        var g = data.gagal    || 0;
        var t = b + g;

        document.getElementById('progressBar').style.width   = '100%';
        document.getElementById('progressCount').textContent = t + ' / ' + t;
        document.getElementById('progressPct').textContent   = '100%';
        document.getElementById('countBerhasil').textContent = b;
        document.getElementById('countGagal').textContent    = g;
        document.getElementById('progressSub').textContent   = data.message || 'Selesai';

        if (data.errors && data.errors.length > 0) {
            var log = document.getElementById('blastLog');
            log.style.display = 'block';
            data.errors.forEach(function(e) {
                log.innerHTML += '<span style="color:#DC2626;">✗ ' + e + '</span>\n';
            });
        }

        document.getElementById('doneBtn').style.display       = 'flex';
        document.getElementById('modalCloseBtn').style.display = 'flex';
    })
    .catch(function() {
        document.getElementById('progressSub').textContent     = 'Terjadi kesalahan jaringan.';
        document.getElementById('doneBtn').style.display       = 'flex';
        document.getElementById('modalCloseBtn').style.display = 'flex';
        showToast('Terjadi kesalahan jaringan.', 'error');
    });
}

function blastAllFiltered() {
    var ids = kandidatData
        .filter(function(k) { return k.no_wa; })
        .map(function(k)    { return k.id; });
    runBlast(ids);
}

function blastSelected() {
    var ids = [...document.querySelectorAll('.cb-row:checked')]
        .map(c => parseInt(c.value));
    clearSelection();
    runBlast(ids);
}

/* ────────────────────────────────────────────
   BLAST SINGLE
──────────────────────────────────────────── */
function blastSingle(id, btn) {
    var label = document.getElementById('blast-label-' + id);
    var orig  = label.textContent;
    btn.disabled = true;
    label.textContent    = 'Mengirim...';
    btn.style.background = '#9CA3AF';

    fetch('/admin/balke/blast-single/' + id, {
        method:  'POST',
        headers: {
            'X-CSRF-TOKEN':  '{{ csrf_token() }}',
            'Accept':        'application/json',
            'Content-Type':  'application/json'
        }
    })
    .then(r => r.json())
    .then(function(data) {
        if (data.success) {
            btn.style.background = '#16A34A';
            label.textContent    = '✓ Terkirim';
            showToast(data.message, 'success');
        } else {
            btn.disabled         = false;
            btn.style.background = '#E8001E';
            label.textContent    = '✗ Gagal';
            showToast(data.message, 'error');
            setTimeout(function() {
                label.textContent    = orig;
                btn.style.background = '';
            }, 3000);
        }
    })
    .catch(function() {
        btn.disabled         = false;
        btn.style.background = '#E8001E';
        label.textContent    = '✗ Error';
        setTimeout(function() {
            label.textContent    = orig;
            btn.style.background = '';
        }, 3000);
    });
}

/* ────────────────────────────────────────────
   TOAST
──────────────────────────────────────────── */
function showToast(msg, type) {
    var toast = document.createElement('div');
    var color = type === 'success' ? '#16A34A' : '#E8001E';
    var icon  = type === 'success' ? '✓' : '⚠';
    toast.style.cssText =
        'position:fixed;bottom:24px;right:24px;z-index:9999;' +
        'background:#0D0D0D;color:#fff;padding:12px 18px;border-radius:12px;' +
        'font-size:13px;max-width:340px;box-shadow:0 8px 40px rgba(0,0,0,.2);' +
        'border-left:3px solid ' + color + ';' +
        'display:flex;align-items:center;gap:8px;font-family:DM Sans,sans-serif;';
    toast.innerHTML =
        '<span style="color:' + color + ';font-weight:700;">' + icon + '</span> ' + msg;
    document.body.appendChild(toast);
    setTimeout(function() {
        toast.style.transition = 'opacity .3s, transform .3s';
        toast.style.opacity    = '0';
        toast.style.transform  = 'translateY(6px)';
        setTimeout(function() { toast.remove(); }, 300);
    }, 3700);
}

/* ────────────────────────────────────────────
   DRAG & DROP IMPORT
──────────────────────────────────────────── */
function handleDrop(e) {
    e.preventDefault();
    document.getElementById('importZone').classList.remove('drag');
    var file = e.dataTransfer && e.dataTransfer.files[0];
    if (!file) return;
    var input = document.getElementById('excelFile');
    var dt    = new DataTransfer();
    dt.items.add(file);
    input.files = dt.files;
    onFileSelect(input);
}

function onFileSelect(input) {
    if (!input.files[0]) return;
    document.getElementById('importZoneText').textContent =
        '✓ ' + input.files[0].name + ' — Klik Upload untuk lanjut';
    var zone = document.getElementById('importZone');
    zone.style.borderColor = '#16A34A';
    zone.style.background  = '#F0FDF4';
    document.getElementById('uploadBtn').style.display = 'flex';
}

/* ────────────────────────────────────────────
   RESET CONFIRM
──────────────────────────────────────────── */
function confirmReset(e) {
    var val = document.getElementById('resetConfirmInput').value;
    if (val !== 'HAPUS') {
        e.preventDefault();
        alert('Ketik "HAPUS" untuk konfirmasi.');
        return false;
    }
    return confirm('Yakin hapus SEMUA data Balke Test? Ini tidak bisa dibatalkan!');
}
</script>
@endpush

@endsection