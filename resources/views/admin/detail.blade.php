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

    /* ── Download button ── */
    .dl-btn {
        display: inline-flex; align-items: center; gap: 6px;
        background: #F5F5F5; color: #555;
        border: 1px solid #E8E8E8;
        padding: 7px 12px; border-radius: 8px;
        font-family: 'Syne', sans-serif;
        font-size: 9px; font-weight: 700;
        letter-spacing: .08em; text-transform: uppercase;
        text-decoration: none; transition: all .15s; margin-top: 6px;
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
</style>
@endpush

@section('content')

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
                        <br><a href="{{ route('admin.candidate.download.fm', $candidate) }}" class="dl-btn">↓ Sertifikat FM</a>
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
                        <br><a href="{{ route('admin.candidate.download.hm', $candidate) }}" class="dl-btn">↓ Sertifikat HM</a>
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
                        <br><a href="{{ route('admin.candidate.download.10k', $candidate) }}" class="dl-btn">↓ Sertifikat 10K</a>
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
                        <br><a href="{{ route('admin.candidate.download.5k', $candidate) }}" class="dl-btn">↓ Sertifikat 5K</a>
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
                    <br><a href="{{ route('admin.candidate.download.trail', $candidate) }}" class="dl-btn">↓ Sertifikat Trail</a>
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
                    ['Des 2025', $candidate->mileage_dec_2025, $candidate->mileage_dec_graph, 'download.mileage.dec'],
                    ['Jan 2026', $candidate->mileage_jan_2026, $candidate->mileage_jan_graph, 'download.mileage.jan'],
                    ['Feb 2026', $candidate->mileage_feb_2026, $candidate->mileage_feb_graph, 'download.mileage.feb'],
                    ['Mar 2026', $candidate->mileage_mar_2026, $candidate->mileage_mar_graph, 'download.mileage.mar'],
                ] as [$period, $km, $graph, $route])
                <div class="m-card">
                    <div class="period">{{ $period }}</div>
                    <div>
                        <span class="km-val">{{ number_format($km ?? 0, 2) }}</span>
                        <span class="km-unit">km</span>
                    </div>
                    @if($graph)
                    <a href="{{ route('admin.candidate.'.$route, $candidate) }}" class="dl-btn">↓ Grafik</a>
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
                ['FM (42K)', $candidate->best_time_fm,  $candidate->best_time_fm_file,  'download.bt.fm'],
                ['HM (21K)', $candidate->best_time_hm,  $candidate->best_time_hm_file,  'download.bt.hm'],
                ['10K',      $candidate->best_time_10k, $candidate->best_time_10k_file, 'download.bt.10k'],
                ['5K',       $candidate->best_time_5k,  $candidate->best_time_5k_file,  'download.bt.5k'],
            ] as [$label, $time, $file, $route])
            <div class="ir">
                <span class="ik">{{ $label }}</span>
                <span class="iv">
                    @if($time)
                        <strong style="color:#E8001E; font-size:15px; font-family:'Syne',sans-serif;">{{ $time }}</strong>
                        @if($file)
                        <a href="{{ route('admin.candidate.'.$route, $candidate) }}" class="dl-btn" style="margin-left:6px;">↓ Bukti</a>
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

        {{-- KTP --}}
        @if($candidate->ktp_file)
        <a href="{{ route('admin.candidate.download.ktp', $candidate) }}" class="dl-btn" style="justify-content:center; margin-top:0;" target="_blank">
            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            Lihat KTP
        </a>
        @endif

        @if($candidate->waiver_file)
        <a href="{{ route('admin.candidate.download.waiver', $candidate) }}" class="dl-btn" style="justify-content:center; margin-top:0;" target="_blank">
            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            Lihat Waiver
        </a>
        @endif

        @if($candidate->fm_certificate)
        <a href="{{ route('admin.candidate.download.fm', $candidate) }}" class="dl-btn" style="justify-content:center; margin-top:0;" target="_blank">↗ Sertifikat FM</a>
        @endif

        @if($candidate->hm_certificate)
        <a href="{{ route('admin.candidate.download.hm', $candidate) }}" class="dl-btn" style="justify-content:center; margin-top:0;" target="_blank">↗ Sertifikat HM</a>
        @endif

        @if($candidate->race_10k_certificate)
        <a href="{{ route('admin.candidate.download.10k', $candidate) }}" class="dl-btn" style="justify-content:center; margin-top:0;" target="_blank">↗ Sertifikat 10K</a>
        @endif

        @if($candidate->race_5k_certificate)
        <a href="{{ route('admin.candidate.download.5k', $candidate) }}" class="dl-btn" style="justify-content:center; margin-top:0;" target="_blank">↗ Sertifikat 5K</a>
        @endif

        @if($candidate->trail_certificate)
        <a href="{{ route('admin.candidate.download.trail', $candidate) }}" class="dl-btn" style="justify-content:center; margin-top:0;" target="_blank">↗ Sertifikat Trail</a>
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

@endsection