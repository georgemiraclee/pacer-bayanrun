@extends('layouts.admin')
@section('title', $candidate->nama)

@section('content')

<style>
    .back-link {
        display:inline-flex; align-items:center; gap:7px;
        font-family:'Syne',sans-serif; font-size:10px; font-weight:700;
        letter-spacing:.1em; text-transform:uppercase; color:#555;
        text-decoration:none; transition:color .18s; margin-bottom:22px;
    }
    .back-link:hover { color:#E8001E; }

    .detail-grid {
        display:grid; grid-template-columns:1fr 300px; gap:20px; align-items:start;
    }
    @media(max-width:860px){ .detail-grid{grid-template-columns:1fr;} }

    .panel {
        background:#161616; border:1px solid rgba(255,255,255,.07);
        border-radius:16px; overflow:hidden; margin-bottom:16px;
    }
    .panel-head {
        padding:13px 22px;
        border-bottom:1px solid rgba(255,255,255,.06);
        display:flex; align-items:center; gap:10px;
    }
    .panel-head-label {
        font-family:'Syne',sans-serif; font-size:10px; font-weight:700;
        letter-spacing:.1em; text-transform:uppercase; color:#555;
    }
    .panel-body { padding:22px; }

    .info-row {
        display:flex; gap:12px; padding:11px 0;
        border-bottom:1px solid rgba(255,255,255,.04);
        align-items:flex-start;
    }
    .info-row:last-child { border-bottom:none; }
    .info-key {
        font-family:'Syne',sans-serif; font-size:10px; font-weight:700;
        letter-spacing:.08em; text-transform:uppercase; color:#444;
        min-width:120px; flex-shrink:0; padding-top:1px;
    }
    .info-val { font-size:14px; color:#CCC; line-height:1.6; }
    .info-val a { color:#E8001E; text-decoration:none; }
    .info-val a:hover { text-decoration:underline; }

    .badge {
        display:inline-flex; align-items:center; gap:5px;
        padding:5px 12px; border-radius:100px;
        font-family:'Syne',sans-serif; font-size:10px; font-weight:700; letter-spacing:.04em;
    }
    .badge-pending  { background:rgba(234,179,8,.12);  color:#EAB308; }
    .badge-verified { background:rgba(22,163,74,.12);  color:#16A34A; }
    .badge-rejected { background:rgba(232,0,30,.12);   color:#E8001E; }

    .hero-panel {
        background:#161616; border:1px solid rgba(255,255,255,.07);
        border-radius:16px; padding:24px 26px;
        margin-bottom:20px;
        display:flex; align-items:center; justify-content:space-between; gap:16px;
        flex-wrap:wrap;
    }
    .hero-panel h1 {
        font-family:'Syne',sans-serif; font-size:22px; font-weight:800; color:#fff;
    }
    .hero-panel p { font-size:13px; color:#555; margin-top:3px; }

    .dl-btn {
        display:inline-flex; align-items:center; gap:7px;
        background:rgba(255,255,255,.06); color:#AAA;
        border:1px solid rgba(255,255,255,.08);
        padding:9px 16px; border-radius:10px;
        font-family:'Syne',sans-serif; font-size:10px; font-weight:700;
        letter-spacing:.08em; text-transform:uppercase;
        text-decoration:none; transition:all .18s;
    }
    .dl-btn:hover { background:rgba(255,255,255,.1); color:#fff; border-color:rgba(255,255,255,.18); }

    /* Status action */
    .action-btn {
        width:100%; padding:11px;
        border-radius:10px; border:2px solid rgba(255,255,255,.07);
        background:rgba(255,255,255,.04); color:#666;
        font-family:'Syne',sans-serif; font-size:11px; font-weight:700;
        letter-spacing:.08em; text-transform:uppercase;
        cursor:pointer; transition:all .18s;
        display:flex; align-items:center; justify-content:center; gap:8px;
    }
    .action-btn.approve:hover,.action-btn.approve.active {
        background:rgba(22,163,74,.15); border-color:#16A34A; color:#16A34A;
    }
    .action-btn.reject:hover,.action-btn.reject.active {
        background:rgba(232,0,30,.12); border-color:#E8001E; color:#E8001E;
    }
    .submit-btn {
        width:100%; padding:12px;
        border-radius:10px; border:none;
        font-family:'Syne',sans-serif; font-size:11px; font-weight:700;
        letter-spacing:.1em; text-transform:uppercase;
        cursor:pointer; transition:all .2s;
        margin-top:12px;
    }
    .submit-approve { background:#16A34A; color:#fff; }
    .submit-approve:hover { background:#15803D; }
    .submit-reject  { background:#E8001E; color:#fff; }
    .submit-reject:hover  { background:#C0001A; }

    textarea.notes-input {
        width:100%; background:#0D0D0D;
        border:1.5px solid rgba(255,255,255,.08);
        border-radius:9px; padding:10px 13px;
        font-family:'DM Sans',sans-serif; font-size:13px; color:#ccc;
        resize:none; outline:none; margin-top:10px;
        transition:border-color .18s;
    }
    textarea.notes-input:focus { border-color:#E8001E; }
    textarea.notes-input::placeholder { color:#444; }

    .reset-btn {
        width:100%; padding:9px;
        background:transparent; border:1px solid rgba(255,255,255,.07);
        color:#444; border-radius:9px;
        font-family:'Syne',sans-serif; font-size:9px; font-weight:700;
        letter-spacing:.08em; text-transform:uppercase;
        cursor:pointer; transition:all .18s; margin-top:8px;
    }
    .reset-btn:hover { border-color:#555; color:#888; }

    .no-experience { font-size:13px; color:#444; font-style:italic; }
</style>

{{-- Back --}}
<a href="{{ route('admin.dashboard') }}" class="back-link">
    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
    </svg>
    Kembali ke Dashboard
</a>

{{-- Hero --}}
<div class="hero-panel">
    <div>
        <h1>{{ $candidate->nama }}</h1>
        <p>{{ $candidate->email }} · Daftar {{ $candidate->created_at->format('d M Y, H:i') }}</p>
    </div>
    <span class="badge badge-{{ $candidate->status->value }}">{{ $candidate->status->label() }}</span>
</div>

<div class="detail-grid">

    {{-- ── LEFT COLUMN ─────────────────────────── --}}
    <div>

        {{-- Data Pribadi --}}
        <div class="panel">
            <div class="panel-head">
                <svg width="14" height="14" fill="none" stroke="#555" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span class="panel-head-label">Data Pribadi</span>
            </div>
            <div class="panel-body" style="padding-top:8px; padding-bottom:8px;">
                @foreach([
                    ['Nama',           $candidate->nama],
                    ['Email',          $candidate->email],
                    ['Tanggal Lahir',  $candidate->tanggal_lahir->format('d F Y').' ('.$candidate->tanggal_lahir->age.' tahun)'],
                    ['Domisili',       $candidate->domisili],
                    ['Alamat',         $candidate->alamat],
                ] as [$k, $v])
                <div class="info-row">
                    <span class="info-key">{{ $k }}</span>
                    <span class="info-val">{{ $v }}</span>
                </div>
                @endforeach
                <div class="info-row">
                    <span class="info-key">Instagram</span>
                    <span class="info-val"><a href="{{ $candidate->instagram }}" target="_blank">{{ $candidate->instagram }}</a></span>
                </div>
                <div class="info-row">
                    <span class="info-key">Strava</span>
                    <span class="info-val"><a href="{{ $candidate->strava }}" target="_blank">{{ $candidate->strava }}</a></span>
                </div>
            </div>
        </div>

        {{-- Full Marathon --}}
        <div class="panel">
            <div class="panel-head">
                <svg width="14" height="14" fill="none" stroke="#555" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                </svg>
                <span class="panel-head-label">Full Marathon</span>
            </div>
            <div class="panel-body" style="padding-top:8px; padding-bottom:8px;">
                @if($candidate->is_full_marathon)
                    <div class="info-row">
                        <span class="info-key">Event</span>
                        <span class="info-val">{{ $candidate->fm_event }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">Tahun</span>
                        <span class="info-val">{{ $candidate->fm_year }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">Sertifikat</span>
                        <span class="info-val">
                            <a href="{{ route('admin.candidate.download.fm', $candidate) }}" class="dl-btn">
                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Download FM
                            </a>
                        </span>
                    </div>
                @else
                    <p class="no-experience">Belum pernah mengikuti Full Marathon.</p>
                @endif
            </div>
        </div>

        {{-- Half Marathon --}}
        <div class="panel">
            <div class="panel-head">
                <svg width="14" height="14" fill="none" stroke="#555" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                <span class="panel-head-label">Half Marathon</span>
            </div>
            <div class="panel-body" style="padding-top:8px; padding-bottom:8px;">
                @if($candidate->is_half_marathon)
                    <div class="info-row">
                        <span class="info-key">Event</span>
                        <span class="info-val">{{ $candidate->hm_event }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">Tahun</span>
                        <span class="info-val">{{ $candidate->hm_year }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">Sertifikat</span>
                        <span class="info-val">
                            <a href="{{ route('admin.candidate.download.hm', $candidate) }}" class="dl-btn">
                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Download HM
                            </a>
                        </span>
                    </div>
                @else
                    <p class="no-experience">Belum pernah mengikuti Half Marathon.</p>
                @endif
            </div>
        </div>

        {{-- Pengalaman Lari --}}
        @if(!$candidate->is_full_marathon && !$candidate->is_half_marathon && $candidate->pengalaman_lari)
        <div class="panel">
            <div class="panel-head">
                <span class="panel-head-label">Pengalaman Lari</span>
            </div>
            <div class="panel-body">
                <p style="font-size:14px; color:#AAA; line-height:1.75;">{{ $candidate->pengalaman_lari }}</p>
            </div>
        </div>
        @endif

    </div>

    {{-- ── RIGHT COLUMN ─────────────────────────── --}}
    <div>

        {{-- Download KTP --}}
        <div class="panel">
            <div class="panel-head">
                <span class="panel-head-label">Dokumen</span>
            </div>
            <div class="panel-body">
                <a href="{{ route('admin.candidate.download.ktp', $candidate) }}" class="dl-btn" style="width:100%; justify-content:center;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0"/>
                    </svg>
                    Download KTP
                </a>
            </div>
        </div>

        {{-- Catatan admin (existing) --}}
        @if($candidate->catatan_admin)
        <div class="panel">
            <div class="panel-head">
                <span class="panel-head-label">Catatan Admin</span>
            </div>
            <div class="panel-body">
                <p style="font-size:13px; color:#888; line-height:1.6;">{{ $candidate->catatan_admin }}</p>
            </div>
        </div>
        @endif

        {{-- Status Action --}}
        <div class="panel" x-data="{ action: '' }">
            <div class="panel-head">
                <span class="panel-head-label">Keputusan Seleksi</span>
            </div>
            <div class="panel-body">
                @if($candidate->isPending())
                <div style="display:flex; flex-direction:column; gap:8px;">
                    <button type="button"
                            @click="action = action === 'verified' ? '' : 'verified'"
                            :class="action === 'verified' ? 'active' : ''"
                            class="action-btn approve">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                        Terima Kandidat
                    </button>
                    <button type="button"
                            @click="action = action === 'rejected' ? '' : 'rejected'"
                            :class="action === 'rejected' ? 'active' : ''"
                            class="action-btn reject">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Tolak Kandidat
                    </button>
                </div>

                <form method="POST" action="{{ route('admin.candidate.status', $candidate) }}"
                      x-show="action !== ''" x-transition>
                    @csrf
                    <input type="hidden" name="status" :value="action">
                    <textarea name="catatan_admin" rows="3" class="notes-input"
                              placeholder="Catatan untuk kandidat (opsional)..."></textarea>
                    <button type="submit"
                            :class="action === 'verified' ? 'submit-approve' : 'submit-reject'"
                            class="submit-btn"
                            x-text="action === 'verified' ? '✓ Konfirmasi Penerimaan' : '✕ Konfirmasi Penolakan'">
                    </button>
                </form>

                @else
                <div style="text-align:center; padding:12px 0;">
                    @if($candidate->isVerified())
                        <div style="width:44px; height:44px; background:rgba(22,163,74,.15); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 10px;">
                            <svg width="22" height="22" fill="none" stroke="#16A34A" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <p style="font-family:'Syne',sans-serif; font-size:12px; font-weight:700; color:#16A34A; text-transform:uppercase; letter-spacing:.05em;">Diterima</p>
                    @else
                        <div style="width:44px; height:44px; background:rgba(232,0,30,.12); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 10px;">
                            <svg width="22" height="22" fill="none" stroke="#E8001E" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </div>
                        <p style="font-family:'Syne',sans-serif; font-size:12px; font-weight:700; color:#E8001E; text-transform:uppercase; letter-spacing:.05em;">Ditolak</p>
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