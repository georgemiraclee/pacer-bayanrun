@extends('layouts.admin')
@section('title', $candidate->nama)

@section('content')

<style>
    .back-link { display:inline-flex;align-items:center;gap:7px;font-family:'Syne',sans-serif;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#555;text-decoration:none;transition:color .18s;margin-bottom:22px; }
    .back-link:hover { color:#E8001E; }
    .detail-grid { display:grid;grid-template-columns:1fr 280px;gap:18px;align-items:start; }
    @media(max-width:860px){ .detail-grid{grid-template-columns:1fr;} }
    .panel { background:#161616;border:1px solid rgba(255,255,255,.07);border-radius:14px;overflow:hidden;margin-bottom:14px; }
    .panel-head { padding:12px 20px;border-bottom:1px solid rgba(255,255,255,.06);display:flex;align-items:center;gap:10px; }
    .ph-label { font-family:'Syne',sans-serif;font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#555; }
    .panel-body { padding:16px 20px; }
    .info-row { display:flex;gap:10px;padding:9px 0;border-bottom:1px solid rgba(255,255,255,.04);align-items:flex-start; }
    .info-row:last-child { border-bottom:none; }
    .ik { font-family:'Syne',sans-serif;font-size:9px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#444;min-width:110px;flex-shrink:0;padding-top:2px; }
    .iv { font-size:13px;color:#CCC;line-height:1.6; }
    .iv a { color:#E8001E;text-decoration:none; }
    .iv a:hover { text-decoration:underline; }
    .badge { display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:100px;font-family:'Syne',sans-serif;font-size:10px;font-weight:700; }
    .badge-pending  { background:rgba(234,179,8,.12);color:#EAB308; }
    .badge-verified { background:rgba(22,163,74,.12);color:#16A34A; }
    .badge-rejected { background:rgba(232,0,30,.12);color:#E8001E; }
    .hero-panel { background:#161616;border:1px solid rgba(255,255,255,.07);border-radius:14px;padding:22px 24px;margin-bottom:18px;display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap; }
    .hero-panel h1 { font-family:'Syne',sans-serif;font-size:20px;font-weight:800;color:#fff; }
    .hero-panel p { font-size:12px;color:#555;margin-top:3px; }
    .dl-btn { display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,.05);color:#888;border:1px solid rgba(255,255,255,.07);padding:7px 13px;border-radius:8px;font-family:'Syne',sans-serif;font-size:9px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;text-decoration:none;transition:all .18s;margin-top:6px; }
    .dl-btn:hover { background:rgba(255,255,255,.1);color:#fff; }
    .mileage-grid { display:grid;grid-template-columns:1fr 1fr;gap:10px; }
    .mileage-card { background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);border-radius:10px;padding:14px; }
    .mileage-card .period { font-family:'Syne',sans-serif;font-size:9px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#555;margin-bottom:6px; }
    .mileage-card .km { font-family:'Syne',sans-serif;font-size:24px;font-weight:800;color:#fff; }
    .mileage-card .unit { font-size:12px;color:#555;margin-left:2px; }
    .action-btn { width:100%;padding:11px;border-radius:10px;border:2px solid rgba(255,255,255,.07);background:rgba(255,255,255,.04);color:#666;font-family:'Syne',sans-serif;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;cursor:pointer;transition:all .18s;display:flex;align-items:center;justify-content:center;gap:8px; }
    .action-btn.approve:hover,.action-btn.approve.active { background:rgba(22,163,74,.15);border-color:#16A34A;color:#16A34A; }
    .action-btn.reject:hover,.action-btn.reject.active  { background:rgba(232,0,30,.12);border-color:#E8001E;color:#E8001E; }
    .submit-btn { width:100%;padding:12px;border-radius:10px;border:none;font-family:'Syne',sans-serif;font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;cursor:pointer;transition:all .2s;margin-top:12px; }
    .sub-approve { background:#16A34A;color:#fff; } .sub-approve:hover{background:#15803D;}
    .sub-reject  { background:#E8001E;color:#fff; } .sub-reject:hover{background:#C0001A;}
    textarea.n-in { width:100%;background:#0D0D0D;border:1.5px solid rgba(255,255,255,.08);border-radius:9px;padding:10px 13px;font-family:'DM Sans',sans-serif;font-size:13px;color:#ccc;resize:none;outline:none;margin-top:10px;transition:border-color .18s; }
    textarea.n-in:focus { border-color:#E8001E; }
    .reset-btn { width:100%;padding:9px;background:transparent;border:1px solid rgba(255,255,255,.07);color:#444;border-radius:9px;font-family:'Syne',sans-serif;font-size:9px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;cursor:pointer;transition:all .18s;margin-top:8px; }
    .reset-btn:hover { border-color:#555;color:#888; }
    .no-exp { font-size:13px;color:#444;font-style:italic; }
    .preferred-tag { display:inline-flex;background:rgba(232,0,30,.1);color:#E8001E;padding:4px 10px;border-radius:100px;font-family:'Syne',sans-serif;font-size:10px;font-weight:700;margin:2px; }
</style>

<a href="{{ route('admin.dashboard') }}" class="back-link">
    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
    Kembali ke Dashboard
</a>

<div class="hero-panel">
    <div>
        <h1>{{ $candidate->nama }}</h1>
        <p>{{ $candidate->email }} · Daftar {{ $candidate->created_at->format('d M Y, H:i') }}</p>
    </div>
    <span class="badge badge-{{ $candidate->status->value }}">{{ $candidate->status->label() }}</span>
</div>

<div class="detail-grid">
<div>

    {{-- Data Pribadi --}}
    <div class="panel">
        <div class="panel-head"><svg width="13" height="13" fill="none" stroke="#555" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg><span class="ph-label">Data Pribadi</span></div>
        <div class="panel-body" style="padding-top:6px;padding-bottom:6px;">
            @foreach([['Nama',$candidate->nama],['Email',$candidate->email],['TTL',$candidate->tanggal_lahir->format('d F Y').' ('.$candidate->tanggal_lahir->age.' thn)'],['Domisili',$candidate->domisili],['Alamat',$candidate->alamat]] as [$k,$v])
            <div class="info-row"><span class="ik">{{ $k }}</span><span class="iv">{{ $v }}</span></div>
            @endforeach
            <div class="info-row"><span class="ik">Instagram</span><span class="iv"><a href="{{ $candidate->instagram }}" target="_blank">{{ $candidate->instagram }}</a></span></div>
            <div class="info-row"><span class="ik">Strava</span><span class="iv"><a href="{{ $candidate->strava }}" target="_blank">{{ $candidate->strava }}</a></span></div>
        </div>
    </div>

    {{-- Race Experience --}}
    <div class="panel">
        <div class="panel-head"><span class="ph-label">Pengalaman Race</span></div>
        <div class="panel-body" style="padding-top:6px;padding-bottom:6px;">
            @foreach([
                ['Full Marathon', $candidate->is_full_marathon, $candidate->fm_event, $candidate->fm_year, $candidate->fm_certificate, 'download.fm'],
                ['Half Marathon', $candidate->is_half_marathon, $candidate->hm_event, $candidate->hm_year, $candidate->hm_certificate, 'download.hm'],
            ] as [$label, $pernah, $event, $year, $cert, $route])
            <div class="info-row">
                <span class="ik">{{ $label }}</span>
                <span class="iv">
                    @if($pernah)
                        <strong style="color:#fff">{{ $event }}</strong> ({{ $year }})
                        @if($cert)<br><a href="{{ route('admin.candidate.'.$route, $candidate) }}" class="dl-btn" style="margin-top:4px;">↓ Sertifikat</a>@endif
                    @else <span style="color:#444">Tidak Pernah</span> @endif
                </span>
            </div>
            @endforeach

            @foreach([
                ['10K', $candidate->is_10k, $candidate->race_10k_event, $candidate->race_10k_year, $candidate->race_10k_certificate, 'download.10k'],
                ['5K',  $candidate->is_5k,  $candidate->race_5k_event,  $candidate->race_5k_year,  $candidate->race_5k_certificate,  'download.5k'],
            ] as [$label, $status, $event, $year, $cert, $route])
            <div class="info-row">
                <span class="ik">{{ $label }}</span>
                <span class="iv">
                    @if($status === 'pernah')
                        <strong style="color:#fff">{{ $event }}</strong> ({{ $year }})
                        @if($cert)<br><a href="{{ route('admin.candidate.'.$route, $candidate) }}" class="dl-btn" style="margin-top:4px;">↓ Sertifikat</a>@endif
                    @elseif($status === 'skip') <span style="color:#555">Dilewati</span>
                    @else <span style="color:#444">Tidak Pernah</span> @endif
                </span>
            </div>
            @endforeach

            @if($candidate->trail_status === 'trail')
            <div class="info-row">
                <span class="ik">Trail / Lainnya</span>
                <span class="iv">
                    <strong style="color:#fff">{{ $candidate->trail_event }}</strong> ({{ $candidate->trail_year }})
                    @if($candidate->trail_certificate)<br><a href="{{ route('admin.candidate.download.trail', $candidate) }}" class="dl-btn" style="margin-top:4px;">↓ Sertifikat</a>@endif
                </span>
            </div>
            @endif
        </div>
    </div>

    {{-- Mileage --}}
    <div class="panel">
        <div class="panel-head"><span class="ph-label">Mileage</span><span style="font-size:11px;color:#E8001E;margin-left:auto;font-family:'Syne',sans-serif;font-weight:700;">Total: {{ number_format($candidate->totalMileage(),2) }} km</span></div>
        <div class="panel-body">
            <div class="mileage-grid">
                @foreach([
                    ['Des 2025', $candidate->mileage_dec_2025, $candidate->mileage_dec_graph, 'download.mileage.dec'],
                    ['Jan 2026', $candidate->mileage_jan_2026, $candidate->mileage_jan_graph, 'download.mileage.jan'],
                    ['Feb 2026', $candidate->mileage_feb_2026, $candidate->mileage_feb_graph, 'download.mileage.feb'],
                    ['Mar 2026', $candidate->mileage_mar_2026, $candidate->mileage_mar_graph, 'download.mileage.mar'],
                ] as [$period, $km, $graph, $route])
                <div class="mileage-card">
                    <div class="period">{{ $period }}</div>
                    <div><span class="km">{{ number_format($km ?? 0, 2) }}</span><span class="unit">km</span></div>
                    @if($graph)<a href="{{ route('admin.candidate.'.$route, $candidate) }}" class="dl-btn">↓ Grafik</a>@endif
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Best Time --}}
    <div class="panel">
        <div class="panel-head"><span class="ph-label">Catatan Waktu Terbaik</span></div>
        <div class="panel-body" style="padding-top:6px;padding-bottom:6px;">
            @foreach([
                ['FM (42K)', $candidate->best_time_fm, $candidate->best_time_fm_file, 'download.bt.fm'],
                ['HM (21K)', $candidate->best_time_hm, $candidate->best_time_hm_file, 'download.bt.hm'],
                ['10K',      $candidate->best_time_10k,$candidate->best_time_10k_file,'download.bt.10k'],
                ['5K',       $candidate->best_time_5k, $candidate->best_time_5k_file, 'download.bt.5k'],
            ] as [$label, $time, $file, $route])
            <div class="info-row">
                <span class="ik">{{ $label }}</span>
                <span class="iv">
                    @if($time) <strong style="color:#E8001E;font-size:15px;font-family:'Syne',sans-serif;">{{ $time }}</strong>
                        @if($file)<a href="{{ route('admin.candidate.'.$route, $candidate) }}" class="dl-btn" style="margin-left:8px;">↓ Bukti</a>@endif
                    @else <span style="color:#444">—</span> @endif
                </span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Pengalaman Pacer --}}
    <div class="panel">
        <div class="panel-head"><span class="ph-label">Pengalaman Pacer / Running Buddies</span></div>
        <div class="panel-body" style="padding-top:6px;padding-bottom:6px;">
            @if($candidate->is_pacer_experience)
                <div class="info-row"><span class="ik">Event</span><span class="iv" style="white-space:pre-line">{{ $candidate->pacer_event_list }}</span></div>
                <div class="info-row"><span class="ik">Jarak & Pace</span><span class="iv" style="white-space:pre-line">{{ $candidate->pacer_distance_pace }}</span></div>
            @else <p class="no-exp">Belum pernah menjadi pacer.</p>
            @endif
        </div>
    </div>

    {{-- Essay --}}
    <div class="panel">
        <div class="panel-head"><span class="ph-label">Essay & Pemahaman</span></div>
        <div class="panel-body">
            <p style="font-family:'Syne',sans-serif;font-size:9px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#444;margin-bottom:6px;">Pandangan Dunia Lari</p>
            <p style="font-size:13px;color:#AAA;line-height:1.75;margin-bottom:16px;">{{ $candidate->essay_running_world }}</p>
            <p style="font-family:'Syne',sans-serif;font-size:9px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#444;margin-bottom:6px;">Definisi Pacer</p>
            <p style="font-size:13px;color:#AAA;line-height:1.75;">{{ $candidate->essay_pacer_definition }}</p>
        </div>
    </div>

    {{-- Komitmen --}}
    <div class="panel">
        <div class="panel-head"><span class="ph-label">Komitmen & Preferensi</span></div>
        <div class="panel-body" style="padding-top:6px;padding-bottom:6px;">
            <div class="info-row">
                <span class="ik">Alasan Pantas</span>
                <span class="iv" style="white-space:pre-line">{{ $candidate->alasan_pantas }}</span>
            </div>
            <div class="info-row">
                <span class="ik">Jarak Favorit</span>
                <span class="iv">
                    @foreach($candidate->preferred_distance ?? [] as $d)
                        <span class="preferred-tag">{{ strtoupper($d) }}</span>
                    @endforeach
                </span>
            </div>
            <div class="info-row"><span class="ik">Komitmen</span><span class="iv">{{ $candidate->komitmen ?? '—' }}</span></div>
            <div class="info-row"><span class="ik">Izin Keluarga</span><span class="iv">{{ $candidate->izin_keluarga === 'ya' ? '✓ Sudah Dapat Izin' : '⏳ Masih Diskusi' }}</span></div>
        </div>
    </div>

</div>

{{-- RIGHT COLUMN --}}
<div>

    {{-- Dokumen --}}
    <div class="panel">
        <div class="panel-head"><span class="ph-label">Dokumen</span></div>
        <div class="panel-body" style="display:flex;flex-direction:column;gap:8px;">
            <a href="{{ route('admin.candidate.download.ktp', $candidate) }}" class="dl-btn" style="justify-content:center;margin-top:0;">↓ KTP</a>
            @if($candidate->waiver_file)
            <a href="{{ route('admin.candidate.download.waiver', $candidate) }}" class="dl-btn" style="justify-content:center;margin-top:0;">↓ Waiver Bermaterai</a>
            @endif
        </div>
    </div>

    {{-- Pernyataan --}}
    <div class="panel">
        <div class="panel-head"><span class="ph-label">Pernyataan Keabsahan</span></div>
        <div class="panel-body" style="text-align:center;padding:14px;">
            @if($candidate->pernyataan_keabsahan)
                <div style="width:36px;height:36px;background:rgba(22,163,74,.15);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 8px;">
                    <svg width="18" height="18" fill="none" stroke="#16A34A" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </div>
                <p style="font-family:'Syne',sans-serif;font-size:10px;font-weight:700;color:#16A34A;text-transform:uppercase;">Sudah Bersumpah</p>
            @else
                <p style="font-size:12px;color:#555;">Belum melengkapi pernyataan.</p>
            @endif
        </div>
    </div>

    {{-- Catatan Admin --}}
    @if($candidate->catatan_admin)
    <div class="panel">
        <div class="panel-head"><span class="ph-label">Catatan Admin</span></div>
        <div class="panel-body"><p style="font-size:13px;color:#888;line-height:1.6;">{{ $candidate->catatan_admin }}</p></div>
    </div>
    @endif

    {{-- Status Action --}}
    <div class="panel" x-data="{action:''}">
        <div class="panel-head"><span class="ph-label">Keputusan Seleksi</span></div>
        <div class="panel-body">
            @if($candidate->isPending())
            <div style="display:flex;flex-direction:column;gap:8px;">
                <button type="button" @click="action=action==='verified'?'':'verified'" :class="action==='verified'?'active':''" class="action-btn approve">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>Terima Kandidat
                </button>
                <button type="button" @click="action=action==='rejected'?'':'rejected'" :class="action==='rejected'?'active':''" class="action-btn reject">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>Tolak Kandidat
                </button>
            </div>
            <form method="POST" action="{{ route('admin.candidate.status', $candidate) }}" x-show="action!==''" x-transition>
                @csrf
                <input type="hidden" name="status" :value="action">
                <textarea name="catatan_admin" rows="3" class="n-in" placeholder="Catatan untuk kandidat (opsional)..."></textarea>
                <button type="submit" :class="action==='verified'?'sub-approve':'sub-reject'" class="submit-btn"
                        x-text="action==='verified'?'✓ Konfirmasi Penerimaan':'✕ Konfirmasi Penolakan'"></button>
            </form>
            @else
            <div style="text-align:center;padding:12px 0;">
                @if($candidate->isVerified())
                    <div style="width:44px;height:44px;background:rgba(22,163,74,.15);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 8px;">
                        <svg width="22" height="22" fill="none" stroke="#16A34A" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <p style="font-family:'Syne',sans-serif;font-size:11px;font-weight:700;color:#16A34A;text-transform:uppercase;">Diterima</p>
                @else
                    <div style="width:44px;height:44px;background:rgba(232,0,30,.12);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 8px;">
                        <svg width="22" height="22" fill="none" stroke="#E8001E" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </div>
                    <p style="font-family:'Syne',sans-serif;font-size:11px;font-weight:700;color:#E8001E;text-transform:uppercase;">Ditolak</p>
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