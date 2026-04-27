@extends('layouts.admin')
@section('title', 'Broadcast Interview')

@push('admin-styles')
<style>
/* ── Base ── */
*, *::before, *::after { box-sizing: border-box; }

/* ── Stat Cards ── */
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
.stat-card .s-label { font-family:'Syne',sans-serif; font-size:9px; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:#AAAAAA; }
.stat-card .s-num   { font-family:'Syne',sans-serif; font-size:34px; font-weight:800; line-height:1; }
.stat-card.total { border-top:3px solid #111; }       .stat-card.total .s-num  { color:#111; }
.stat-card.hadir { border-top:3px solid #16A34A; }    .stat-card.hadir .s-num  { color:#16A34A; }
.stat-card.ganti { border-top:3px solid #D97706; }    .stat-card.ganti .s-num  { color:#D97706; }
.stat-card.belum { border-top:3px solid #E8001E; }    .stat-card.belum .s-num  { color:#E8001E; }
.stat-card.wa    { border-top:3px solid #25D366; }    .stat-card.wa .s-num     { color:#25D366; }

/* ── Cards ── */
.card {
    background: #fff;
    border: 1px solid #EBEBEB;
    border-radius: 14px;
    box-shadow: 0 1px 3px rgba(0,0,0,.04);
}

/* ── Import Card ── */
.import-card { padding: 22px; margin-bottom: 18px; }
.import-card-header {
    display: flex; align-items: flex-start; justify-content: space-between;
    margin-bottom: 16px; flex-wrap: wrap; gap: 12px;
}
.import-card-title { font-family:'Syne',sans-serif; font-size:12px; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:#888; }
.import-card-sub   { font-size:12px; color:#AAAAAA; margin-top:3px; }
.import-card-actions { display:flex; gap:8px; flex-wrap:wrap; align-items:center; }

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

/* ── Toolbar ── */
.toolbar {
    margin-bottom: 18px; padding: 16px 20px;
    display: flex; flex-wrap: wrap; gap: 10px; align-items: flex-end;
}
.f-label {
    font-family:'Syne',sans-serif; font-size:9px; font-weight:700;
    letter-spacing:.1em; text-transform:uppercase; color:#AAAAAA;
    display: block; margin-bottom: 4px;
}
select, input[type=text], input[type=email], input[type=time] {
    font-family:'DM Sans',sans-serif; font-size:13px; color:#111;
    background:#FAFAFA; border:1.5px solid #E8E8E8; border-radius:8px;
    padding:7px 12px; outline:none; transition:border-color .15s;
    -webkit-appearance:none; appearance:none;
}
select:focus, input[type=text]:focus,
input[type=email]:focus, input[type=time]:focus { border-color:#E8001E; background:#fff; }

/* ── Buttons ── */
.btn {
    display: inline-flex; align-items: center; gap: 6px;
    border: none; border-radius: 8px; padding: 8px 14px;
    font-family:'Syne',sans-serif; font-size:10px; font-weight:700;
    letter-spacing:.07em; text-transform:uppercase;
    cursor: pointer; transition: all .15s;
    text-decoration: none; white-space: nowrap;
}
.btn-red     { background:#E8001E; color:#fff; }    .btn-red:hover     { background:#C0001A; color:#fff; }
.btn-green   { background:#16A34A; color:#fff; }    .btn-green:hover   { background:#15803D; color:#fff; }
.btn-wa      { background:#25D366; color:#fff; }    .btn-wa:hover      { background:#1DA851; color:#fff; }
.btn-gray    { background:#F5F5F5; color:#777; }    .btn-gray:hover    { background:#E8E8E8; color:#444; }
.btn-outline { background:#fff; color:#666; border:1.5px solid #E8E8E8; }
.btn-outline:hover { border-color:#E8001E; color:#E8001E; }
.btn-danger  { background:#FFF0F2; color:#E8001E; border:1.5px solid #FFCCD2; }
.btn-danger:hover  { background:#E8001E; color:#fff; border-color:#E8001E; }

/* ── Table ── */
.table-card { overflow: hidden; }
.table-head-bar {
    padding: 14px 20px; border-bottom: 1px solid #F5F5F5;
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 10px;
}
.table-head-bar h2 { font-family:'Syne',sans-serif; font-size:12px; font-weight:700; color:#111; }
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

/* ── Status badges ── */
.badge {
    display:inline-flex; align-items:center; gap:4px;
    padding:4px 9px; border-radius:100px;
    font-family:'Syne',sans-serif; font-size:9px; font-weight:700;
}
.badge-hadir   { background:#DCFCE7; color:#15803D; }
.badge-ganti   { background:#FEF3C7; color:#D97706; }
.badge-belum   { background:#F3F4F6; color:#6B7280; }
.badge-wa-sent { background:#DCFCE7; color:#15803D; }
.badge-wa-no   { background:#F3F4F6; color:#6B7280; }

/* ── Action buttons in table ── */
.wa-link {
    display:inline-flex; align-items:center; gap:5px;
    background:#25D366; color:#fff; padding:5px 10px;
    border-radius:7px; font-family:'Syne',sans-serif; font-size:9px;
    font-weight:700; text-decoration:none; transition:background .15s;
    border:none; cursor:pointer; white-space:nowrap;
}
.wa-link:hover { background:#1DA851; }
.copy-link {
    display:inline-flex; align-items:center; gap:5px;
    background:#F5F5F5; color:#666; padding:5px 9px;
    border-radius:7px; font-family:'Syne',sans-serif; font-size:9px;
    font-weight:700; border:none; cursor:pointer; transition:all .15s;
    white-space:nowrap;
}
.copy-link:hover { background:#E8001E; color:#fff; }
.del-btn {
    display:inline-flex; align-items:center; gap:5px;
    background:#FFF0F2; color:#E8001E; padding:5px 9px;
    border-radius:7px; font-family:'Syne',sans-serif; font-size:9px;
    font-weight:700; border:1.5px solid #FFCCD2; cursor:pointer;
    transition:all .15s; white-space:nowrap;
}
.del-btn:hover { background:#E8001E; color:#fff; border-color:#E8001E; }

/* ── Reset zone ── */
.reset-zone {
    background:#FFF0F2; border:1.5px solid #FFCCD2; border-radius:12px;
    padding:16px 20px; display:flex; align-items:center; gap:12px; flex-wrap:wrap;
}
.reset-zone input[type=text] { border-color:#FFCCD2; }

/* ── Checkbox ── */
.cb-all, .cb-row { cursor:pointer; accent-color:#E8001E; width:15px; height:15px; }

/* ── Bulk action bar (appears when rows selected) ── */
.bulk-bar {
    display: none;
    align-items: center; gap: 10px; flex-wrap: wrap;
    padding: 10px 20px;
    background: #FFF7ED;
    border: 1px solid #FED7AA;
    border-radius: 10px;
    margin-bottom: 14px;
    animation: slideDown .2s ease;
}
.bulk-bar.visible { display: flex; }
.bulk-bar-count {
    font-family:'Syne',sans-serif; font-size:11px; font-weight:700;
    color:#C2410C; flex:1;
}
@keyframes slideDown { from { opacity:0; transform:translateY(-6px); } to { opacity:1; transform:translateY(0); } }

/* ── Modal ── */
.modal-overlay {
    display: none; position: fixed; inset:0; z-index:9999;
    background: rgba(0,0,0,.5); backdrop-filter: blur(4px);
    align-items: center; justify-content: center; padding: 16px;
}
.modal-overlay.open { display: flex; }
.modal-box {
    background: #fff; border-radius: 20px; padding: 28px;
    width: 100%; max-width: 480px;
    box-shadow: 0 24px 80px rgba(0,0,0,.3);
    animation: cardIn .3s cubic-bezier(.34,1.1,.64,1) both;
}
.modal-box.modal-sm { max-width: 400px; }
@keyframes cardIn { from { opacity:0; transform:scale(.94) translateY(8px); } to { opacity:1; transform:scale(1) translateY(0); } }
.modal-header {
    display:flex; align-items:flex-start; justify-content:space-between;
    margin-bottom:20px; gap:12px;
}
.modal-title { font-family:'Syne',sans-serif; font-size:14px; font-weight:800; color:#111; }
.modal-sub   { font-size:12px; color:#AAAAAA; margin-top:3px; }
.modal-close {
    width:32px; height:32px; border-radius:50%; border:1.5px solid #E8E8E8;
    background:#F5F5F5; cursor:pointer; display:flex; align-items:center;
    justify-content:center; font-size:18px; color:#888; line-height:1;
    flex-shrink:0; transition:all .15s;
}
.modal-close:hover { background:#E8001E; color:#fff; border-color:#E8001E; }
.modal-footer { display:flex; gap:10px; margin-top:22px; }

/* ── Form fields ── */
.field-group { display:flex; flex-direction:column; gap:14px; }
.field { display:flex; flex-direction:column; gap:4px; }
.field-row { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
.field select, .field input { width:100%; }

/* ── Danger zone confirm modal ── */
.danger-icon {
    width:52px; height:52px; background:#FFF0F2; border-radius:50%;
    display:flex; align-items:center; justify-content:center;
    margin:0 auto 16px; font-size:22px;
}

/* ── Responsive ── */
@media(max-width:640px){
    .toolbar { padding:12px 14px; }
    .import-card { padding:14px; }
    .field-row { grid-template-columns:1fr; }
    table thead { display:none; }
    table, tbody, tr, td { display:block; width:100%; }
    tbody tr { border:1px solid #EBEBEB; border-radius:12px; margin-bottom:10px; padding:12px 14px; background:#fff; }
    tbody td { padding:4px 0; border:none; font-size:13px; }
}
</style>
@endpush

@section('content')

{{-- ── TOAST ── --}}
@if(session('success'))
<div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,5000)"
     x-transition:leave="transition duration-300" x-transition:leave-end="opacity-0 -translate-y-2"
     style="position:fixed;top:18px;right:18px;z-index:9999;background:#0D0D0D;color:#fff;padding:12px 18px;border-radius:12px;font-size:13px;max-width:340px;box-shadow:0 8px 40px rgba(0,0,0,.2);border-left:3px solid #16A34A;display:flex;align-items:center;gap:8px;">
    <svg width="16" height="16" fill="none" stroke="#4ADE80" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,5000)"
     style="position:fixed;top:18px;right:18px;z-index:9999;background:#0D0D0D;color:#fff;padding:12px 18px;border-radius:12px;font-size:13px;max-width:340px;box-shadow:0 8px 40px rgba(0,0,0,.2);border-left:3px solid #E8001E;display:flex;align-items:center;gap:8px;">
    <svg width="16" height="16" fill="none" stroke="#F87171" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
    {{ session('error') }}
</div>
@endif

{{-- ── STAT CARDS ── --}}
<div class="stat-grid">
    <div class="stat-card total">
        <span class="s-label">Total Kandidat</span>
        <span class="s-num">{{ $stats['total'] }}</span>
    </div>
    <div class="stat-card hadir">
        <span class="s-label">Siap Hadir</span>
        <span class="s-num">{{ $stats['hadir'] }}</span>
    </div>
    <div class="stat-card ganti">
        <span class="s-label">Request Ganti</span>
        <span class="s-num">{{ $stats['ganti'] }}</span>
    </div>
    <div class="stat-card belum">
        <span class="s-label">Belum Konfirmasi</span>
        <span class="s-num">{{ $stats['belum'] }}</span>
    </div>
    <div class="stat-card wa">
        <span class="s-label">WA Terkirim</span>
        <span class="s-num">{{ $stats['wa_sent'] }}</span>
    </div>
</div>

{{-- ── IMPORT EXCEL ── --}}
<div class="card import-card" style="margin-bottom:18px;">
    <div class="import-card-header">
        <div>
            <p class="import-card-title">Import Data Jadwal Interview</p>
            <p class="import-card-sub">Upload Excel dari panitia. Data yang sudah ada tidak akan duplikat.</p>
        </div>
        <div class="import-card-actions">
            <a href="{{ route('admin.interview.export') }}{{ request()->getQueryString() ? '?'.request()->getQueryString() : '' }}"
               class="btn btn-green">
                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Export CSV
            </a>
            <button type="button" class="btn btn-red" onclick="openModal('modalTambah')">
                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Tambah Manual
            </button>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.interview.import') }}" enctype="multipart/form-data" id="importForm">
        @csrf
        <div class="import-zone" id="importZone"
             onclick="document.getElementById('excelFile').click()"
             ondragover="event.preventDefault();this.classList.add('drag')"
             ondragleave="this.classList.remove('drag')"
             ondrop="handleDrop(event)">
            <div class="import-zone-icon">
                <svg width="22" height="22" fill="none" stroke="#E8001E" stroke-width="1.6" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
            </div>
            <p id="importZoneText" style="font-size:13px;font-weight:500;color:#666;">Klik atau drag file Excel jadwal interview</p>
            <p style="font-size:11px;color:#BBB;margin-top:4px;">Interview_Session_Pacer_Bayan_Run_2026.xlsx · Maks 10MB</p>
        </div>
        <input type="file" id="excelFile" name="excel_file" accept=".xlsx,.xls" style="display:none"
               onchange="onFileSelect(this)">
        @error('excel_file')
            <span style="font-size:12px;color:#E8001E;display:block;margin-top:6px;">{{ $message }}</span>
        @enderror
        <button type="submit" id="uploadBtn" class="btn btn-red"
                style="display:none;margin-top:12px;width:100%;justify-content:center;padding:11px;">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
            Upload & Import Sekarang
        </button>
    </form>

    {{-- Reset Data --}}
    <details style="margin-top:16px;">
        <summary style="font-family:'Syne',sans-serif;font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#AAAAAA;cursor:pointer;user-select:none;">
            ⚠ Zona Bahaya — Hapus Semua Data Interview
        </summary>
        <div class="reset-zone" style="margin-top:10px;">
            <form method="POST" action="{{ route('admin.interview.reset') }}"
                  onsubmit="return confirmReset(event)" style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end;width:100%;">
                @csrf
                <div style="flex:1;min-width:160px;">
                    <label class="f-label" style="color:#E8001E;">Ketik HAPUS untuk konfirmasi</label>
                    <input type="text" name="confirm" placeholder="HAPUS" id="resetConfirmInput" style="border-color:#FFCCD2;width:100%;">
                </div>
                <button type="submit" class="btn btn-red" style="padding:8px 16px;">
                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Hapus Semua
                </button>
            </form>
        </div>
    </details>
</div>

{{-- ── FILTER + TOOLBAR ── --}}
<div class="card toolbar" style="margin-bottom:14px;">
    <form method="GET" action="{{ route('admin.interview.index') }}"
          style="display:flex;flex-wrap:wrap;gap:10px;align-items:flex-end;width:100%;">
        <div style="flex:1;min-width:160px;">
            <span class="f-label">Filter Hari</span>
            <select name="jadwal" style="width:100%;">
                <option value="">Semua Hari</option>
                @foreach($jadwalList as $j)
                    <option value="{{ $j }}" {{ request('jadwal') === $j ? 'selected' : '' }}>{{ $j }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <span class="f-label">Status Konfirmasi</span>
            <select name="status">
                <option value="">Semua</option>
                <option value="hadir"      {{ request('status')==='hadir'      ? 'selected' : '' }}>Siap Hadir</option>
                <option value="ganti_hari" {{ request('status')==='ganti_hari' ? 'selected' : '' }}>Request Ganti Hari</option>
                <option value="belum"      {{ request('status')==='belum'      ? 'selected' : '' }}>Belum Konfirmasi</option>
            </select>
        </div>
        <div style="display:flex;gap:8px;align-items:center;padding-bottom:1px;flex-wrap:wrap;">
            <button type="submit" class="btn btn-red">
                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/></svg>
                Filter
            </button>
            <a href="{{ route('admin.interview.index') }}" class="btn btn-gray">Reset</a>
            <button type="button" class="btn btn-wa" onclick="blastAllFiltered()">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413z"/></svg>
                Blast WA (Filter Aktif)
            </button>
        </div>
    </form>
</div>

{{-- ── BULK ACTION BAR (muncul saat checkbox dipilih) ── --}}
<div class="bulk-bar" id="bulkBar">
    <span class="bulk-bar-count" id="bulkCount">0 kandidat dipilih</span>
    <button type="button" class="btn btn-wa" onclick="blastSelected()">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413z"/></svg>
        Blast WA Terpilih
    </button>
    <button type="button" class="btn btn-danger" onclick="openDeleteBatchModal()">
        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
        Hapus Terpilih
    </button>
    <button type="button" class="btn btn-gray" onclick="clearSelection()" style="margin-left:auto;">
        Batal
    </button>
</div>

{{-- ── CANDIDATE TABLE ── --}}
<div class="card table-card">
    <div class="table-head-bar">
        <h2>Daftar Kandidat Interview</h2>
        <span style="font-size:12px;color:#AAAAAA;">{{ $sessions->total() }} kandidat</span>
    </div>

    @if($sessions->isEmpty())
    <div style="text-align:center;padding:56px 20px;">
        <div style="font-size:36px;margin-bottom:12px;">📋</div>
        <p style="font-family:'Syne',sans-serif;font-size:14px;font-weight:700;color:#333;margin-bottom:6px;">Belum ada data</p>
        <p style="font-size:13px;color:#AAAAAA;">Import file Excel jadwal interview terlebih dahulu.</p>
    </div>
    @else
    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th style="width:36px;"><input type="checkbox" class="cb-all" id="cbAll" onchange="toggleAll(this)"></th>
                    <th>#</th>
                    <th>Kandidat</th>
                    <th>Jadwal</th>
                    <th>Jam</th>
                    <th>Konfirmasi</th>
                    <th>WA Status</th>
                    <th>Link</th>
                    <th>Blast WA</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sessions as $s)
                @php $conf = $s->confirmation; @endphp
                <tr id="row-{{ $s->id }}">
                    <td><input type="checkbox" class="cb-row" value="{{ $s->id }}" onchange="onCheckChange()"></td>
                    <td style="color:#CCC;font-size:12px;">{{ $s->id }}</td>
                    <td>
                        <div class="cand-name">{{ $s->nama }}</div>
                        <div class="cand-wa">{{ $s->no_wa ?? '—' }}</div>
                    </td>
                    <td style="font-size:13px;color:#555;">{{ $s->jadwal }}</td>
                    <td><span class="time-badge">{{ $s->waktu }} WITA</span></td>
                    <td>
                        @if($conf)
                            @if($conf->status === 'hadir')
                                <span class="badge badge-hadir">
                                    <svg width="9" height="9" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    Siap Hadir
                                </span>
                            @else
                                <span class="badge badge-ganti">↺ Ganti: {{ $conf->request_hari }}</span>
                                @if($conf->alasan)
                                    <div style="font-size:11px;color:#AAAAAA;margin-top:3px;max-width:160px;">{{ Str::limit($conf->alasan, 40) }}</div>
                                @endif
                            @endif
                            <div style="font-size:10px;color:#CCCCCC;margin-top:2px;">{{ $conf->created_at->format('d M, H:i') }}</div>
                        @else
                            <span class="badge badge-belum">— Belum</span>
                        @endif
                    </td>
                    <td>
                        @if($s->wa_sent)
                            <span class="badge badge-wa-sent">
                                <svg width="9" height="9" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                Terkirim
                            </span>
                            <div style="font-size:10px;color:#CCCCCC;margin-top:2px;">{{ $s->wa_sent_at?->format('d M, H:i') }}</div>
                        @else
                            <span class="badge badge-wa-no">Belum</span>
                        @endif
                    </td>
                    <td>
                        <button class="copy-link"
                                onclick="copyToClipboard('{{ route('interview.confirm', $s->token) }}', this)"
                                title="Copy link konfirmasi">
                            <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            Copy
                        </button>
                    </td>
                    <td>
                        <button class="wa-link" id="blast-btn-{{ $s->id }}"
                                onclick="blastSingle({{ $s->id }}, this)"
                                {{ empty($s->no_wa) ? 'disabled' : '' }}
                                style="{{ empty($s->no_wa) ? 'opacity:.4;cursor:not-allowed;' : '' }}">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413z"/></svg>
                            <span id="blast-label-{{ $s->id }}">{{ $s->wa_sent ? 'Kirim Ulang' : 'Blast WA' }}</span>
                        </button>
                    </td>
                    <td>
                        <button class="del-btn" onclick="openDeleteModal({{ $s->id }}, '{{ addslashes($s->nama) }}')" title="Hapus kandidat ini">
                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Hapus
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="padding:14px 20px;border-top:1px solid #F5F5F5;">
        {{ $sessions->links() }}
    </div>
    @endif
</div>

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- ── MODAL: TAMBAH KANDIDAT MANUAL ── --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<div class="modal-overlay" id="modalTambah" onclick="handleOverlayClick(event,'modalTambah')">
    <div class="modal-box">
        <div class="modal-header">
            <div>
                <p class="modal-title">Tambah Kandidat Manual</p>
                <p class="modal-sub">Isi data kandidat secara manual</p>
            </div>
            <button class="modal-close" onclick="closeModal('modalTambah')">×</button>
        </div>

        <form method="POST" action="{{ route('admin.interview.store-manual') }}">
            @csrf
            <div class="field-group">

                <div class="field">
                    <label class="f-label">Nama Lengkap *</label>
                    <input type="text" name="nama" required placeholder="Nama kandidat">
                </div>

                <div class="field-row">
                    <div class="field">
                        <label class="f-label">Nomor WhatsApp *</label>
                        <input type="text" name="no_wa" required placeholder="08xxxxxxxxxx">
                    </div>
                    <div class="field">
                        <label class="f-label">Email</label>
                        <input type="email" name="email" placeholder="email@contoh.com">
                    </div>
                </div>

                <div class="field-row">
                <div class="field">
                    <label class="f-label">Jadwal (Tanggal) *</label>
                    <input type="date" name="jadwal" required id="jadwalInput" style="width:100%;">
                </div>
                <div class="field">
                    <label class="f-label">Jam *</label>
                    <input type="time" name="waktu" required>
                </div>
            </div>

                {{-- Input custom jadwal --}}
                <div class="field" id="customJadwalWrap" style="display:none;">
                    <label class="f-label">Nama Hari Baru *</label>
                    <input type="text" name="jadwal_custom" id="jadwalCustomInput"
                           placeholder="Contoh: Senin, 4 Mei 2026">
                </div>

                <div class="field">
                    <label class="f-label">Durasi</label>
                    <select name="durasi">
                        <option value="15 Menit">15 Menit</option>
                        <option value="30 Menit">30 Menit</option>
                        <option value="45 Menit">45 Menit</option>
                        <option value="60 Menit">60 Menit</option>
                    </select>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" onclick="closeModal('modalTambah')"
                        class="btn btn-gray" style="flex:1;justify-content:center;padding:11px;">
                    Batal
                </button>
                <button type="submit" class="btn btn-red" style="flex:2;justify-content:center;padding:11px;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Tambah Kandidat
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- ── MODAL: KONFIRMASI HAPUS SINGLE ── --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<div class="modal-overlay" id="modalHapusSingle" onclick="handleOverlayClick(event,'modalHapusSingle')">
    <div class="modal-box modal-sm">
        <div style="text-align:center;padding-bottom:4px;">
            <div class="danger-icon">🗑️</div>
            <p class="modal-title" style="text-align:center;">Hapus Kandidat?</p>
            <p style="font-size:13px;color:#AAAAAA;margin-top:6px;line-height:1.5;">
                Kamu akan menghapus <strong id="hapusNama" style="color:#111;"></strong>.<br>
                Data konfirmasi juga akan ikut terhapus.
            </p>
        </div>
        <form id="hapusSingleForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-footer" style="margin-top:18px;">
                <button type="button" onclick="closeModal('modalHapusSingle')"
                        class="btn btn-gray" style="flex:1;justify-content:center;padding:11px;">
                    Batal
                </button>
                <button type="submit" class="btn btn-red" style="flex:1;justify-content:center;padding:11px;">
                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Ya, Hapus
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- ── MODAL: KONFIRMASI HAPUS BATCH ── --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<div class="modal-overlay" id="modalHapusBatch" onclick="handleOverlayClick(event,'modalHapusBatch')">
    <div class="modal-box modal-sm">
        <div style="text-align:center;padding-bottom:4px;">
            <div class="danger-icon">⚠️</div>
            <p class="modal-title" style="text-align:center;">Hapus Kandidat Terpilih?</p>
            <p style="font-size:13px;color:#AAAAAA;margin-top:6px;line-height:1.5;">
                Kamu akan menghapus <strong id="hapusBatchCount" style="color:#E8001E;"></strong> kandidat.<br>
                Semua data konfirmasi mereka juga akan terhapus.<br>
                <span style="color:#E8001E;font-weight:600;">Tindakan ini tidak bisa dibatalkan!</span>
            </p>
        </div>
        <div class="modal-footer" style="margin-top:18px;">
            <button type="button" onclick="closeModal('modalHapusBatch')"
                    class="btn btn-gray" style="flex:1;justify-content:center;padding:11px;">
                Batal
            </button>
            <button type="button" onclick="confirmHapusBatch()"
                    class="btn btn-red" style="flex:1;justify-content:center;padding:11px;">
                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Ya, Hapus Semua
            </button>
        </div>
    </div>
</div>

@push('admin-scripts')
<script>
/* ════════════════════════════════════════════
   MODAL HELPERS
════════════════════════════════════════════ */
function openModal(id)  { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }
function handleOverlayClick(e, id) {
    if (e.target === document.getElementById(id)) closeModal(id);
}

/* ════════════════════════════════════════════
   JADWAL SELECT — custom hari
════════════════════════════════════════════ */


/* ════════════════════════════════════════════
   RESET CONFIRM
════════════════════════════════════════════ */
function confirmReset(e) {
    var val = document.getElementById('resetConfirmInput').value;
    if (val !== 'HAPUS') {
        e.preventDefault();
        alert('Ketik "HAPUS" untuk konfirmasi.');
        return false;
    }
    return confirm('Yakin hapus SEMUA data interview? Ini tidak bisa dibatalkan!');
}

/* ════════════════════════════════════════════
   HAPUS SINGLE
════════════════════════════════════════════ */
function openDeleteModal(id, nama) {
    document.getElementById('hapusNama').textContent = nama;
    document.getElementById('hapusSingleForm').action = '/admin/interview/' + id;
    openModal('modalHapusSingle');
}

/* ════════════════════════════════════════════
   CHECKBOX + BULK BAR
════════════════════════════════════════════ */
function toggleAll(cb) {
    document.querySelectorAll('.cb-row').forEach(c => c.checked = cb.checked);
    updateBulkBar();
}

function onCheckChange() {
    var all  = document.querySelectorAll('.cb-row');
    var checked = document.querySelectorAll('.cb-row:checked');
    document.getElementById('cbAll').checked = all.length > 0 && checked.length === all.length;
    updateBulkBar();
}

function updateBulkBar() {
    var checked = document.querySelectorAll('.cb-row:checked');
    var bar  = document.getElementById('bulkBar');
    var cnt  = document.getElementById('bulkCount');
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

/* ════════════════════════════════════════════
   HAPUS BATCH
════════════════════════════════════════════ */
function openDeleteBatchModal() {
    var checked = document.querySelectorAll('.cb-row:checked');
    if (checked.length === 0) { showToast('Pilih kandidat dulu.', 'error'); return; }
    document.getElementById('hapusBatchCount').textContent = checked.length;
    openModal('modalHapusBatch');
}

function confirmHapusBatch() {
    var ids = [...document.querySelectorAll('.cb-row:checked')].map(c => parseInt(c.value));
    closeModal('modalHapusBatch');

    fetch('{{ route('admin.interview.destroy-batch') }}', {
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
            // Hapus baris dari DOM
            ids.forEach(id => {
                var row = document.getElementById('row-' + id);
                if (row) {
                    row.style.transition = 'opacity .3s, transform .3s';
                    row.style.opacity = '0';
                    row.style.transform = 'translateX(10px)';
                    setTimeout(() => row.remove(), 300);
                }
            });
            updateBulkBar();
            // Reload setelah animasi
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(data.message || 'Gagal menghapus.', 'error');
        }
    })
    .catch(() => showToast('Terjadi kesalahan jaringan.', 'error'));
}

/* ════════════════════════════════════════════
   BLAST WA
════════════════════════════════════════════ */
var allSessions = @json($sessionData);

function blastAllFiltered() {
    var ids = allSessions.map(s => s.id);
    if (ids.length === 0) { showToast('Tidak ada kandidat.', 'error'); return; }
    if (!confirm('Blast WA via Qiscus ke ' + ids.length + ' kandidat?')) return;

    showToast('Mengirim ' + ids.length + ' WA...', 'success');

    fetch('{{ route('admin.interview.blast-batch') }}', {
        method:  'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body:    JSON.stringify({ ids: ids })
    })
    .then(r => r.json())
    .then(data => showToast(data.message, data.success ? 'success' : 'error'))
    .catch(() => showToast('Terjadi kesalahan jaringan.', 'error'));
}

function blastSelected() {
    var ids = [...document.querySelectorAll('.cb-row:checked')].map(c => parseInt(c.value));
    if (ids.length === 0) { showToast('Pilih kandidat dulu.', 'error'); return; }
    if (!confirm('Blast WA via Qiscus ke ' + ids.length + ' kandidat terpilih?')) return;

    showToast('Mengirim ' + ids.length + ' WA...', 'success');

    fetch('{{ route('admin.interview.blast-batch') }}', {
        method:  'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body:    JSON.stringify({ ids: ids })
    })
    .then(r => r.json())
    .then(data => showToast(data.message, data.success ? 'success' : 'error'))
    .catch(() => showToast('Terjadi kesalahan jaringan.', 'error'));
}

function blastSingle(id, btn) {
    var label = document.getElementById('blast-label-' + id);
    var orig  = label.textContent;
    btn.disabled = true;
    label.textContent = 'Mengirim...';
    btn.style.background = '#AAAAAA';

    fetch('/admin/interview/blast-single/' + id, {
        method:  'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            btn.style.background = '#16A34A';
            label.textContent = '✓ Terkirim';
            showToast(data.message, 'success');
        } else {
            btn.style.background = '#E8001E';
            btn.disabled = false;
            label.textContent = '✗ Gagal';
            showToast(data.message, 'error');
            setTimeout(() => { label.textContent = orig; btn.style.background = ''; }, 3000);
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.style.background = '#E8001E';
        label.textContent = '✗ Error';
        setTimeout(() => { label.textContent = orig; btn.style.background = ''; }, 3000);
    });
}

/* ════════════════════════════════════════════
   COPY LINK
════════════════════════════════════════════ */
function copyToClipboard(text, btn) {
    navigator.clipboard.writeText(text).then(function () {
        var orig = btn.innerHTML;
        btn.innerHTML = '✓ Disalin';
        btn.style.background = '#16A34A';
        btn.style.color = '#fff';
        setTimeout(function () { btn.innerHTML = orig; btn.style.background = ''; btn.style.color = ''; }, 2000);
    });
}

/* ════════════════════════════════════════════
   TOAST
════════════════════════════════════════════ */
function showToast(msg, type) {
    var toast = document.createElement('div');
    var color = type === 'success' ? '#16A34A' : '#E8001E';
    var icon  = type === 'success' ? '✓' : '⚠';
    toast.style.cssText = 'position:fixed;bottom:24px;right:24px;z-index:9999;background:#0D0D0D;color:#fff;padding:12px 18px;border-radius:12px;font-size:13px;max-width:340px;box-shadow:0 8px 40px rgba(0,0,0,.2);border-left:3px solid ' + color + ';display:flex;align-items:center;gap:8px;font-family:DM Sans,sans-serif;';
    toast.innerHTML = '<span style="color:' + color + ';font-weight:700;">' + icon + '</span> ' + msg;
    document.body.appendChild(toast);
    setTimeout(() => {
        toast.style.transition = 'opacity .3s, transform .3s';
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(6px)';
        setTimeout(() => toast.remove(), 300);
    }, 3700);
}

/* ════════════════════════════════════════════
   DRAG DROP IMPORT
════════════════════════════════════════════ */
function handleDrop(e) {
    e.preventDefault();
    document.getElementById('importZone').classList.remove('drag');
    var file = e.dataTransfer && e.dataTransfer.files[0];
    if (!file) return;
    var input = document.getElementById('excelFile');
    var dt = new DataTransfer();
    dt.items.add(file);
    input.files = dt.files;
    onFileSelect(input);
}

function onFileSelect(input) {
    if (!input.files[0]) return;
    document.getElementById('importZoneText').textContent = '✓ ' + input.files[0].name + ' — Klik Upload untuk lanjut';
    var zone = document.getElementById('importZone');
    zone.style.borderColor = '#16A34A';
    zone.style.background  = '#F0FDF4';
    document.getElementById('uploadBtn').style.display = 'flex';
}
</script>
@endpush

@endsection