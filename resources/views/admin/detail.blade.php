@extends('layouts.admin')
@section('title', $candidate->nama)

@push('admin-styles')
<style>
    .back-link {
        display: inline-flex; align-items: center; gap: 7px;
        font-family: 'Syne', sans-serif; font-size: 10px; font-weight: 700;
        letter-spacing: .1em; text-transform: uppercase;
        color: #AAAAAA; text-decoration: none;
        transition: color .15s; margin-bottom: 22px;
    }
    .back-link:hover { color: #E8001E; }

    /* ── Hero ── */
    .hero-panel {
        background: #fff;
        border: 1px solid #EBEBEB;
        border-radius: 16px;
        padding: 22px 26px;
        margin-bottom: 20px;
        display: flex; align-items: center;
        justify-content: space-between; gap: 16px; flex-wrap: wrap;
        box-shadow: 0 1px 3px rgba(0,0,0,.04);
    }
    .hero-panel h1 { font-family:'Syne',sans-serif; font-size:20px; font-weight:800; color:#111; }
    .hero-panel p  { font-size:12px; color:#AAAAAA; margin-top:3px; }

    /* ── 2-col layout ── */
    .detail-grid { display:grid; grid-template-columns:1fr 290px; gap:18px; align-items:start; }
    @media(max-width:860px){ .detail-grid{grid-template-columns:1fr;} }

    /* ── Panel ── */
    .panel {
        background: #fff;
        border: 1px solid #EBEBEB;
        border-radius: 14px;
        overflow: hidden;
        margin-bottom: 14px;
        box-shadow: 0 1px 3px rgba(0,0,0,.04);
    }
    .panel-head {
        background: #FAFAFA;
        border-bottom: 1px solid #F0F0F0;
        padding: 11px 20px;
        display: flex; align-items: center; gap: 8px;
    }
    .panel-head-label {
        font-family: 'Syne', sans-serif;
        font-size: 10px; font-weight: 700;
        letter-spacing: .1em; text-transform: uppercase; color: #888;
    }
    .panel-body { padding: 14px 20px; }

    /* ── Info rows ── */
    .ir {
        display: flex; gap: 12px; align-items: flex-start;
        padding: 9px 0; border-bottom: 1px solid #F8F8F8;
    }
    .ir:last-child { border-bottom: none; }
    .ik {
        font-family: 'Syne', sans-serif;
        font-size: 9px; font-weight: 700;
        letter-spacing: .08em; text-transform: uppercase;
        color: #C0C0C0; min-width: 100px; flex-shrink: 0; padding-top: 1px;
    }
    .iv { font-size: 13px; color: #333; line-height: 1.6; }
    .iv a { color: #E8001E; text-decoration: none; }
    .iv a:hover { text-decoration: underline; }
    .iv-empty { color: #CCCCCC; font-style: italic; font-size: 12px; }

    /* ── Badges ── */
    .badge {
        display: inline-flex; align-items: center;
        padding: 4px 10px; border-radius: 100px;
        font-family: 'Syne', sans-serif;
        font-size: 10px; font-weight: 700;
    }
    .badge-pending  { background:#FEF3C7; color:#D97706; }
    .badge-verified { background:#DCFCE7; color:#15803D; }
    .badge-rejected { background:#FFE4E7; color:#E8001E; }

    /* ── Preview button ── */
    .dl-btn {
        display: inline-flex; align-items: center; gap: 6px;
        background: #F5F5F5; color: #555;
        border: 1px solid #E8E8E8;
        padding: 7px 12px; border-radius: 8px;
        font-family: 'Syne', sans-serif;
        font-size: 9px; font-weight: 700;
        letter-spacing: .08em; text-transform: uppercase;
        text-decoration: none; transition: all .15s; margin-top: 6px;
        cursor: pointer;
    }
    .dl-btn:hover { background: #E8001E; color: #fff; border-color: #E8001E; }

    /* ── Mileage cards ── */
    .mileage-grid { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
    .m-card {
        background: #FAFAFA; border: 1px solid #EBEBEB;
        border-radius: 10px; padding: 14px;
    }
    .m-card .period {
        font-family: 'Syne', sans-serif; font-size: 9px; font-weight: 700;
        letter-spacing: .1em; text-transform: uppercase; color: #AAAAAA; margin-bottom: 6px;
    }
    .m-card .km-val { font-family:'Syne',sans-serif; font-size:22px; font-weight:800; color:#111; }
    .m-card .km-unit { font-size:12px; color:#AAA; margin-left:2px; }

    /* ── Action buttons ── */
    .action-btn {
        width: 100%; padding: 11px; border-radius: 10px;
        border: 1.5px solid #E8E8E8;
        background: #FAFAFA; color: #888;
        font-family: 'Syne', sans-serif; font-size: 11px; font-weight: 700;
        letter-spacing: .08em; text-transform: uppercase;
        cursor: pointer; transition: all .15s;
        display: flex; align-items: center; justify-content: center; gap: 8px;
    }
    .action-btn.approve:hover, .action-btn.approve.active {
        background: #DCFCE7; border-color: #16A34A; color: #15803D;
    }
    .action-btn.reject:hover, .action-btn.reject.active {
        background: #FFE4E7; border-color: #E8001E; color: #E8001E;
    }
    .submit-btn {
        width: 100%; padding: 12px; border-radius: 10px; border: none;
        font-family: 'Syne', sans-serif; font-size: 11px; font-weight: 700;
        letter-spacing: .1em; text-transform: uppercase;
        cursor: pointer; transition: all .2s; margin-top: 12px;
    }
    .sub-approve { background: #16A34A; color: #fff; }
    .sub-approve:hover { background: #15803D; }
    .sub-reject  { background: #E8001E; color: #fff; }
    .sub-reject:hover  { background: #C0001A; }

    textarea.n-inp {
        width: 100%; background: #FAFAFA;
        border: 1.5px solid #E8E8E8; border-radius: 9px;
        padding: 10px 13px;
        font-family: 'DM Sans', sans-serif; font-size: 13px; color: #333;
        resize: none; outline: none; margin-top: 10px;
        transition: border-color .15s;
    }
    textarea.n-inp:focus { border-color: #E8001E; }
    textarea.n-inp::placeholder { color: #CCC; }

    .reset-btn {
        width: 100%; padding: 9px; margin-top: 8px;
        background: transparent; border: 1px solid #E8E8E8;
        border-radius: 9px; color: #AAAAAA;
        font-family: 'Syne', sans-serif; font-size: 9px; font-weight: 700;
        letter-spacing: .08em; text-transform: uppercase;
        cursor: pointer; transition: all .15s;
    }
    .reset-btn:hover { border-color: #E8001E; color: #E8001E; }

    .pref-tag {
        display: inline-flex;
        background: #FFE4E7; color: #E8001E;
        padding: 3px 9px; border-radius: 100px;
        font-family: 'Syne', sans-serif; font-size: 10px; font-weight: 700;
        margin: 2px;
    }
    .no-exp { font-size: 13px; color: #CCCCCC; font-style: italic; padding: 6px 0; }

    /* ══ PREVIEW MODAL ══ */
    #preview-modal {
        display: none;
        position: fixed; inset: 0; z-index: 9999;
        background: rgba(0,0,0,.75);
        backdrop-filter: blur(6px);
        align-items: center; justify-content: center;
        padding: 20px;
        animation: fadeIn .15s ease;
    }
    #preview-modal.open { display: flex; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

    .modal-box {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        width: 100%; max-width: 860px;
        max-height: 92vh;
        display: flex; flex-direction: column;
        box-shadow: 0 24px 80px rgba(0,0,0,.35);
        animation: slideUp .18s ease;
    }
    @keyframes slideUp { from { transform: translateY(14px); opacity:.6; } to { transform: translateY(0); opacity:1; } }

    .modal-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 14px 20px;
        border-bottom: 1px solid #F0F0F0;
        background: #FAFAFA; flex-shrink: 0;
    }
    .modal-title {
        font-family: 'Syne', sans-serif; font-size: 11px;
        font-weight: 700; letter-spacing: .1em; text-transform: uppercase; color: #555;
    }
    .modal-close {
        width: 30px; height: 30px; border-radius: 8px;
        border: 1px solid #E8E8E8; background: #fff;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; color: #888; transition: all .15s;
    }
    .modal-close:hover { background: #E8001E; color: #fff; border-color: #E8001E; }

    .modal-body {
        flex: 1; overflow: auto;
        display: flex; align-items: center; justify-content: center;
        background: #F8F8F8; min-height: 200px; position: relative;
    }

    /* Image preview */
    #modal-img {
        max-width: 100%; max-height: calc(92vh - 80px);
        object-fit: contain; display: block;
        border-radius: 0;
    }

    /* PDF preview */
    #modal-iframe {
        width: 100%; height: calc(92vh - 80px);
        border: none; display: block;
    }

    /* Loading spinner */
    .modal-spinner {
        position: absolute; inset: 0;
        display: flex; flex-direction: column;
        align-items: center; justify-content: center; gap: 12px;
        background: #F8F8F8;
    }
    .spinner-ring {
        width: 36px; height: 36px;
        border: 3px solid #E8E8E8;
        border-top-color: #E8001E;
        border-radius: 50%;
        animation: spin .7s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    .spinner-text {
        font-family: 'Syne', sans-serif; font-size: 10px;
        font-weight: 700; letter-spacing: .1em; text-transform: uppercase; color: #AAAAAA;
    }

    /* Modal footer with download option */
    .modal-footer {
        padding: 10px 20px;
        border-top: 1px solid #F0F0F0;
        background: #FAFAFA;
        display: flex; align-items: center; justify-content: space-between; flex-shrink: 0;
    }
    .modal-footer-label {
        font-size: 11px; color: #AAAAAA;
    }
    .modal-dl-btn {
        display: inline-flex; align-items: center; gap: 6px;
        background: #111; color: #fff;
        border: none; padding: 7px 14px; border-radius: 8px;
        font-family: 'Syne', sans-serif; font-size: 9px; font-weight: 700;
        letter-spacing: .08em; text-transform: uppercase;
        text-decoration: none; cursor: pointer; transition: background .15s;
    }
    .modal-dl-btn:hover { background: #E8001E; }

    /* thumbnail preview in inline panels */
    .thumb-wrap {
        margin-top: 8px;
        border: 1px solid #EBEBEB; border-radius: 10px;
        overflow: hidden; cursor: pointer;
        position: relative; max-width: 180px;
        transition: box-shadow .15s;
    }
    .thumb-wrap:hover { box-shadow: 0 4px 18px rgba(232,0,30,.18); }
    .thumb-wrap img { width: 100%; height: 100px; object-fit: cover; display: block; }
    .thumb-wrap .thumb-overlay {
        position: absolute; inset: 0;
        background: rgba(0,0,0,0); transition: background .15s;
        display: flex; align-items: center; justify-content: center;
    }
    .thumb-wrap:hover .thumb-overlay { background: rgba(0,0,0,.45); }
    .thumb-overlay span {
        color: #fff; font-family:'Syne',sans-serif; font-size:9px; font-weight:700;
        letter-spacing:.1em; text-transform:uppercase; opacity:0; transition: opacity .15s;
    }
    .thumb-wrap:hover .thumb-overlay span { opacity:1; }

    .pdf-thumb {
        background: #FAFAFA;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        height: 80px; gap: 6px; cursor: pointer;
        border: 1px solid #EBEBEB; border-radius: 10px;
        max-width: 180px; margin-top: 8px; transition: all .15s;
    }
    .pdf-thumb:hover { background: #FFE4E7; border-color: #E8001E; }
    .pdf-thumb svg { color: #E8001E; }
    .pdf-thumb span {
        font-family:'Syne',sans-serif; font-size:9px; font-weight:700;
        letter-spacing:.08em; text-transform:uppercase; color:#888;
    }
</style>
@endpush

@section('content')

{{-- ══ PREVIEW MODAL ══ --}}
<div id="preview-modal" role="dialog" aria-modal="true">
    <div class="modal-box">
        <div class="modal-header">
            <span class="modal-title" id="modal-title">Preview Dokumen</span>
            <button class="modal-close" onclick="closePreview()" aria-label="Tutup">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="modal-body" id="modal-body">
            <div class="modal-spinner" id="modal-spinner">
                <div class="spinner-ring"></div>
                <span class="spinner-text">Memuat dokumen…</span>
            </div>
            {{-- diisi JS --}}
        </div>
        <div class="modal-footer">
            <span class="modal-footer-label" id="modal-file-label">—</span>
            <a href="#" id="modal-dl-link" class="modal-dl-btn" download target="_blank">
                <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Download
            </a>
        </div>
    </div>
</div>

<a href="{{ route('admin.dashboard') }}" class="back-link">
    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
    </svg>
    Kembali ke Dashboard
</a>

{{-- ── Hero ── --}}
<div class="hero-panel">
    <div>
        <h1>{{ $candidate->nama }}</h1>
        <p>{{ $candidate->email }} · Daftar {{ $candidate->created_at->format('d M Y, H:i') }}</p>
    </div>
    <span class="badge badge-{{ $candidate->status->value }}">{{ $candidate->status->label() }}</span>
</div>

<div class="detail-grid">

{{-- ══ LEFT COLUMN ══ --}}
<div>

    {{-- Data Pribadi --}}
    <div class="panel">
        <div class="panel-head">
            <svg width="13" height="13" fill="none" stroke="#AAAAAA" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <span class="panel-head-label">Data Pribadi</span>
        </div>
        <div class="panel-body" style="padding-top:6px; padding-bottom:6px;">
            @foreach([
                ['NIK',       $candidate->nik ?? '—'],
                ['Nama',      $candidate->nama],
                ['Email',     $candidate->email],
                ['WhatsApp',  $candidate->no_hp ?? '—'],
                ['Tgl Lahir', $candidate->tanggal_lahir_formatted . ($candidate->usia !== null ? ' ('.$candidate->usia.' thn)' : '')],
                ['Domisili',  $candidate->domisili],
                ['Alamat',    $candidate->alamat],
            ] as [$k, $v])
            <div class="ir"><span class="ik">{{ $k }}</span><span class="iv">{{ $v ?: '—' }}</span></div>
            @endforeach
            <div class="ir">
                <span class="ik">Instagram</span>
                <span class="iv"><a href="{{ $candidate->instagram }}" target="_blank">{{ $candidate->instagram }}</a></span>
            </div>
            <div class="ir">
                <span class="ik">Strava</span>
                <span class="iv"><a href="{{ $candidate->strava }}" target="_blank">{{ $candidate->strava }}</a></span>
            </div>
            {{-- KTP inline --}}
            @if($candidate->ktp_file)
            <div class="ir">
                <span class="ik">KTP</span>
                <span class="iv">
                    <div class="thumb-wrap"
                         onclick="openPreview('{{ route('admin.candidate.preview.ktp', $candidate) }}', 'KTP – {{ $candidate->nama }}', 'image')">
                        <img src="{{ route('admin.candidate.preview.ktp', $candidate) }}" alt="KTP">
                        <div class="thumb-overlay"><span>Perbesar</span></div>
                    </div>
                </span>
            </div>
            @endif
        </div>
    </div>

    {{-- Race Experience --}}
    <div class="panel">
        <div class="panel-head">
            <svg width="13" height="13" fill="none" stroke="#AAAAAA" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
            </svg>
            <span class="panel-head-label">Pengalaman Race</span>
        </div>
        <div class="panel-body" style="padding-top:6px; padding-bottom:6px;">
            {{-- FM --}}
            <div class="ir">
                <span class="ik">Full Marathon</span>
                <span class="iv">
                    @if($candidate->is_full_marathon)
                        <strong>{{ $candidate->fm_event }}</strong> ({{ $candidate->fm_year }})
                        @if($candidate->fm_certificate)
                        <br>
                        <button class="dl-btn" onclick="openPreview('{{ route('admin.candidate.preview.fm', $candidate) }}', 'Sertifikat FM', 'image')">
                            ↗ Sertifikat FM
                        </button>
                        @endif
                    @else<span class="iv-empty">Tidak Pernah</span>@endif
                </span>
            </div>
            {{-- HM --}}
            <div class="ir">
                <span class="ik">Half Marathon</span>
                <span class="iv">
                    @if($candidate->is_half_marathon)
                        <strong>{{ $candidate->hm_event }}</strong> ({{ $candidate->hm_year }})
                        @if($candidate->hm_certificate)
                        <br>
                        <button class="dl-btn" onclick="openPreview('{{ route('admin.candidate.preview.hm', $candidate) }}', 'Sertifikat HM', 'image')">
                            ↗ Sertifikat HM
                        </button>
                        @endif
                    @else<span class="iv-empty">Tidak Pernah</span>@endif
                </span>
            </div>
            {{-- 10K --}}
            <div class="ir">
                <span class="ik">10K</span>
                <span class="iv">
                    @if($candidate->is_10k === 'pernah')
                        <strong>{{ $candidate->race_10k_event }}</strong> ({{ $candidate->race_10k_year }})
                        @if($candidate->race_10k_certificate)
                        <br>
                        <button class="dl-btn" onclick="openPreview('{{ route('admin.candidate.preview.10k', $candidate) }}', 'Sertifikat 10K', 'image')">
                            ↗ Sertifikat 10K
                        </button>
                        @endif
                    @elseif($candidate->is_10k === 'skip')<span class="iv-empty">Dilewati</span>
                    @else<span class="iv-empty">Tidak Pernah</span>@endif
                </span>
            </div>
            {{-- 5K --}}
            <div class="ir">
                <span class="ik">5K</span>
                <span class="iv">
                    @if($candidate->is_5k === 'pernah')
                        <strong>{{ $candidate->race_5k_event }}</strong> ({{ $candidate->race_5k_year }})
                        @if($candidate->race_5k_certificate)
                        <br>
                        <button class="dl-btn" onclick="openPreview('{{ route('admin.candidate.preview.5k', $candidate) }}', 'Sertifikat 5K', 'image')">
                            ↗ Sertifikat 5K
                        </button>
                        @endif
                    @elseif($candidate->is_5k === 'skip')<span class="iv-empty">Dilewati</span>
                    @else<span class="iv-empty">Tidak Pernah</span>@endif
                </span>
            </div>
            {{-- Trail --}}
            @if($candidate->trail_status === 'trail')
            <div class="ir">
                <span class="ik">Trail</span>
                <span class="iv">
                    <strong>{{ $candidate->trail_event }}</strong> ({{ $candidate->trail_year }})
                    @if($candidate->trail_certificate)
                    <br>
                    <button class="dl-btn" onclick="openPreview('{{ route('admin.candidate.preview.trail', $candidate) }}', 'Sertifikat Trail', 'image')">
                        ↗ Sertifikat Trail
                    </button>
                    @endif
                </span>
            </div>
            @endif
        </div>
    </div>

    {{-- Mileage --}}
    <div class="panel">
        <div class="panel-head">
            <span class="panel-head-label">Mileage</span>
            <span style="margin-left:auto; font-family:'Syne',sans-serif; font-size:11px; font-weight:700; color:#E8001E;">
                Total: {{ number_format($candidate->totalMileage(), 2) }} km
            </span>
        </div>
        <div class="panel-body">
            <div class="mileage-grid">
                @foreach([
                    ['Des 2025', $candidate->mileage_dec_2025, $candidate->mileage_dec_graph, 'preview.mileage.dec', 'Grafik Mileage Des 2025'],
                    ['Jan 2026', $candidate->mileage_jan_2026, $candidate->mileage_jan_graph, 'preview.mileage.jan', 'Grafik Mileage Jan 2026'],
                    ['Feb 2026', $candidate->mileage_feb_2026, $candidate->mileage_feb_graph, 'preview.mileage.feb', 'Grafik Mileage Feb 2026'],
                    ['Mar 2026', $candidate->mileage_mar_2026, $candidate->mileage_mar_graph, 'preview.mileage.mar', 'Grafik Mileage Mar 2026'],
                ] as [$period, $km, $graph, $route, $label])
                <div class="m-card">
                    <div class="period">{{ $period }}</div>
                    <div>
                        <span class="km-val">{{ number_format($km ?? 0, 2) }}</span>
                        <span class="km-unit">km</span>
                    </div>
                    @if($graph)
                    <button class="dl-btn" style="margin-top:8px;"
                            onclick="openPreview('{{ route('admin.candidate.'.$route, $candidate) }}', '{{ $label }}', 'image')">
                        ↗ Grafik
                    </button>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Best Time --}}
    <div class="panel">
        <div class="panel-head"><span class="panel-head-label">Catatan Waktu Terbaik</span></div>
        <div class="panel-body" style="padding-top:6px; padding-bottom:6px;">
            @foreach([
                ['FM (42K)', $candidate->best_time_fm,  $candidate->best_time_fm_file,  'preview.bt.fm',  'Bukti Best Time FM'],
                ['HM (21K)', $candidate->best_time_hm,  $candidate->best_time_hm_file,  'preview.bt.hm',  'Bukti Best Time HM'],
                ['10K',      $candidate->best_time_10k, $candidate->best_time_10k_file, 'preview.bt.10k', 'Bukti Best Time 10K'],
                ['5K',       $candidate->best_time_5k,  $candidate->best_time_5k_file,  'preview.bt.5k',  'Bukti Best Time 5K'],
            ] as [$label, $time, $file, $route, $title])
            <div class="ir">
                <span class="ik">{{ $label }}</span>
                <span class="iv">
                    @if($time)
                        <strong style="color:#E8001E; font-size:15px; font-family:'Syne',sans-serif;">{{ $time }}</strong>
                        @if($file)
                        <button class="dl-btn" style="margin-left:6px; vertical-align:middle;"
                                onclick="openPreview('{{ route('admin.candidate.'.$route, $candidate) }}', '{{ $title }}', 'image')">
                            ↗ Bukti
                        </button>
                        @endif
                    @else<span class="iv-empty">—</span>@endif
                </span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Pengalaman Pacer --}}
    <div class="panel">
        <div class="panel-head"><span class="panel-head-label">Pengalaman Pacer / Running Buddies</span></div>
        <div class="panel-body" style="padding-top:6px; padding-bottom:6px;">
            @if($candidate->is_pacer_experience)
                <div class="ir"><span class="ik">Event</span><span class="iv" style="white-space:pre-line">{{ $candidate->pacer_event_list }}</span></div>
                <div class="ir"><span class="ik">Jarak & Pace</span><span class="iv" style="white-space:pre-line">{{ $candidate->pacer_distance_pace }}</span></div>
            @else<p class="no-exp">Belum pernah menjadi pacer.</p>@endif
        </div>
    </div>

    {{-- Essay --}}
    <div class="panel">
        <div class="panel-head"><span class="panel-head-label">Essay & Pemahaman</span></div>
        <div class="panel-body">
            <p style="font-family:'Syne',sans-serif; font-size:9px; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:#CCCCCC; margin-bottom:6px;">Pandangan Dunia Lari</p>
            <p style="font-size:13px; color:#555; line-height:1.75; margin-bottom:16px;">{{ $candidate->essay_running_world }}</p>
            <p style="font-family:'Syne',sans-serif; font-size:9px; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:#CCCCCC; margin-bottom:6px;">Definisi Pacer</p>
            <p style="font-size:13px; color:#555; line-height:1.75;">{{ $candidate->essay_pacer_definition }}</p>
        </div>
    </div>

    {{-- Komitmen --}}
    <div class="panel">
        <div class="panel-head"><span class="panel-head-label">Komitmen & Preferensi</span></div>
        <div class="panel-body" style="padding-top:6px; padding-bottom:6px;">
            <div class="ir"><span class="ik">Alasan</span><span class="iv" style="white-space:pre-line">{{ $candidate->alasan_pantas }}</span></div>
            <div class="ir">
                <span class="ik">Jarak Favorit</span>
                <span class="iv">
                    @foreach($candidate->preferred_distance ?? [] as $d)
                    <span class="pref-tag">{{ strtoupper($d) }}</span>
                    @endforeach
                </span>
            </div>
            <div class="ir"><span class="ik">Komitmen</span><span class="iv">{{ $candidate->komitmen ?? '—' }}</span></div>
            <div class="ir">
                <span class="ik">Izin Keluarga</span>
                <span class="iv">
                    @if($candidate->izin_keluarga === 'ya')
                        <span style="color:#15803D; font-weight:600;">✓ Sudah Dapat Izin</span>
                    @else
                        <span style="color:#D97706;">⏳ Masih Diskusi</span>
                    @endif
                </span>
            </div>
        </div>
    </div>

</div>

{{-- ══ RIGHT COLUMN ══ --}}
<div>

{{-- Dokumen --}}
<div class="panel">
    <div class="panel-head">
        <svg width="13" height="13" fill="none" stroke="#AAAAAA" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <span class="panel-head-label">Dokumen</span>
    </div>
    <div class="panel-body" style="display:flex; flex-direction:column; gap:8px;">

        @if($candidate->ktp_file)
        <button class="dl-btn" style="justify-content:center; margin-top:0;"
                onclick="openPreview('{{ route('admin.candidate.preview.ktp', $candidate) }}', 'KTP – {{ $candidate->nama }}', 'image')">
            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            Lihat KTP
        </button>
        @endif

        @if($candidate->waiver_file)
        <button class="dl-btn" style="justify-content:center; margin-top:0;"
                onclick="openPreview('{{ route('admin.candidate.preview.waiver', $candidate) }}', 'Waiver – {{ $candidate->nama }}', 'pdf')">
            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            Lihat Waiver
        </button>
        @endif

        @if($candidate->fm_certificate)
        <button class="dl-btn" style="justify-content:center; margin-top:0;"
                onclick="openPreview('{{ route('admin.candidate.preview.fm', $candidate) }}', 'Sertifikat FM', 'image')">
            ↗ Sertifikat FM
        </button>
        @endif

        @if($candidate->hm_certificate)
        <button class="dl-btn" style="justify-content:center; margin-top:0;"
                onclick="openPreview('{{ route('admin.candidate.preview.hm', $candidate) }}', 'Sertifikat HM', 'image')">
            ↗ Sertifikat HM
        </button>
        @endif

        @if($candidate->race_10k_certificate)
        <button class="dl-btn" style="justify-content:center; margin-top:0;"
                onclick="openPreview('{{ route('admin.candidate.preview.10k', $candidate) }}', 'Sertifikat 10K', 'image')">
            ↗ Sertifikat 10K
        </button>
        @endif

        @if($candidate->race_5k_certificate)
        <button class="dl-btn" style="justify-content:center; margin-top:0;"
                onclick="openPreview('{{ route('admin.candidate.preview.5k', $candidate) }}', 'Sertifikat 5K', 'image')">
            ↗ Sertifikat 5K
        </button>
        @endif

        @if($candidate->trail_certificate)
        <button class="dl-btn" style="justify-content:center; margin-top:0;"
                onclick="openPreview('{{ route('admin.candidate.preview.trail', $candidate) }}', 'Sertifikat Trail', 'image')">
            ↗ Sertifikat Trail
        </button>
        @endif

    </div>
</div>

    {{-- Pernyataan Keabsahan --}}
    <div class="panel">
        <div class="panel-head"><span class="panel-head-label">Pernyataan Keabsahan</span></div>
        <div class="panel-body" style="text-align:center; padding:16px;">
            @if($candidate->pernyataan_keabsahan)
            <div style="width:40px; height:40px; background:#DCFCE7; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 10px;">
                <svg width="20" height="20" fill="none" stroke="#15803D" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <p style="font-family:'Syne',sans-serif; font-size:11px; font-weight:700; color:#15803D; text-transform:uppercase;">Data Dijamin Sah</p>
            @else
            <p style="font-size:12px; color:#CCC;">Belum menandatangani pernyataan.</p>
            @endif
        </div>
    </div>

    {{-- Catatan Admin --}}
    @if($candidate->catatan_admin)
    <div class="panel">
        <div class="panel-head"><span class="panel-head-label">Catatan Admin</span></div>
        <div class="panel-body">
            <p style="font-size:13px; color:#666; line-height:1.6; background:#FAFAFA; padding:12px; border-radius:8px; border:1px solid #F0F0F0;">
                {{ $candidate->catatan_admin }}
            </p>
        </div>
    </div>
    @endif

    {{-- Status Action --}}
    <div class="panel" x-data="{action:''}">
        <div class="panel-head">
            <svg width="13" height="13" fill="none" stroke="#AAAAAA" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <span class="panel-head-label">Keputusan Seleksi</span>
        </div>
        <div class="panel-body">

            @if($candidate->isPending())
            <p style="font-size:11px; color:#AAAAAA; margin-bottom:12px; line-height:1.5;">
                Pilih keputusan untuk kandidat ini. Tambahkan catatan jika diperlukan.
            </p>
            <div style="display:flex; flex-direction:column; gap:8px;">
                <button type="button"
                        @click="action = action==='verified' ? '' : 'verified'"
                        :class="action==='verified' ? 'active' : ''"
                        class="action-btn approve">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                    Terima Kandidat
                </button>
                <button type="button"
                        @click="action = action==='rejected' ? '' : 'rejected'"
                        :class="action==='rejected' ? 'active' : ''"
                        class="action-btn reject">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Tolak Kandidat
                </button>
            </div>

            <form method="POST" action="{{ route('admin.candidate.status', $candidate) }}"
                  x-show="action !== ''" x-transition style="margin-top:4px;">
                @csrf
                <input type="hidden" name="status" :value="action">
                <textarea name="catatan_admin" rows="3" class="n-inp"
                          placeholder="Catatan untuk kandidat (opsional)..."></textarea>
                <button type="submit"
                        :class="action==='verified' ? 'sub-approve' : 'sub-reject'"
                        class="submit-btn"
                        x-text="action==='verified' ? '✓ Konfirmasi Penerimaan' : '✕ Konfirmasi Penolakan'">
                </button>
            </form>

            @else
            <div style="text-align:center; padding:14px 0;">
                @if($candidate->isVerified())
                <div style="width:48px; height:48px; background:#DCFCE7; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 10px;">
                    <svg width="24" height="24" fill="none" stroke="#15803D" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <p style="font-family:'Syne',sans-serif; font-size:12px; font-weight:700; color:#15803D; text-transform:uppercase;">Diterima</p>
                @else
                <div style="width:48px; height:48px; background:#FFE4E7; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 10px;">
                    <svg width="24" height="24" fill="none" stroke="#E8001E" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <p style="font-family:'Syne',sans-serif; font-size:12px; font-weight:700; color:#E8001E; text-transform:uppercase;">Ditolak</p>
                @endif

                <form method="POST" action="{{ route('admin.candidate.status', $candidate) }}">
                    @csrf
                    <input type="hidden" name="status" value="pending">
                    <button type="submit" class="reset-btn">Reset ke Pending</button>
                </form>
            </div>
            @endif

        </div>
    </div>

</div>
</div>

@push('admin-scripts')
<script>
function openPreview(url, title, type) {
    const modal   = document.getElementById('preview-modal');
    const body    = document.getElementById('modal-body');
    const spinner = document.getElementById('modal-spinner');
    const titleEl = document.getElementById('modal-title');
    const label   = document.getElementById('modal-file-label');
    const dlLink  = document.getElementById('modal-dl-link');

    // Reset
    titleEl.textContent = title;
    label.textContent   = title;
    dlLink.href         = url;
    body.querySelectorAll('img, iframe').forEach(el => el.remove());
    spinner.style.display = 'flex';
    modal.classList.add('open');
    document.body.style.overflow = 'hidden';

    if (type === 'pdf') {
        const iframe = document.createElement('iframe');
        iframe.id  = 'modal-iframe';
        iframe.src = url;
        iframe.onload = () => { spinner.style.display = 'none'; };
        body.appendChild(iframe);
    } else {
        const img = document.createElement('img');
        img.id  = 'modal-img';
        img.src = url;
        img.alt = title;
        img.onload  = () => { spinner.style.display = 'none'; };
        img.onerror = () => {
            spinner.innerHTML = '<span style="font-family:\'Syne\',sans-serif;font-size:11px;color:#E8001E;font-weight:700;">Gagal memuat file</span>';
        };
        body.appendChild(img);
    }
}

function closePreview() {
    const modal = document.getElementById('preview-modal');
    const body  = document.getElementById('modal-body');
    modal.classList.remove('open');
    document.body.style.overflow = '';
    body.querySelectorAll('img, iframe').forEach(el => el.remove());
}

// Tutup dengan klik backdrop atau Escape
document.getElementById('preview-modal').addEventListener('click', function(e) {
    if (e.target === this) closePreview();
});
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closePreview();
});
</script>
@endpush

@endsection