@extends('layouts.admin')
@section('title', 'Dashboard')

@push('admin-styles')
<style>
    /* ── Stat Cards ── */
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    @media(max-width:1100px){ .stat-grid{grid-template-columns:repeat(3,1fr);} }
    @media(max-width:900px){  .stat-grid{grid-template-columns:repeat(2,1fr);} }

    .stat-card {
        background: #fff;
        border: 1px solid #EBEBEB;
        border-radius: 16px;
        padding: 20px 22px;
        display: flex; flex-direction: column; gap: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,.04);
        transition: box-shadow .2s, transform .2s;
    }
    .stat-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,.08); transform: translateY(-1px); }

    .stat-card .s-label {
        font-family: 'Syne', sans-serif;
        font-size: 10px; font-weight: 700;
        letter-spacing: .1em; text-transform: uppercase;
        color: #AAAAAA;
    }
    .stat-card .s-num {
        font-family: 'Syne', sans-serif;
        font-size: 38px; font-weight: 800;
        line-height: 1;
    }
    .stat-card .s-sub {
        font-size: 12px; color: #BBBBBB;
    }
    .stat-card.total    .s-num { color: #111; }
    .stat-card.pending  .s-num { color: #D97706; }
    .stat-card.verified .s-num { color: #16A34A; }
    .stat-card.rejected .s-num { color: #E8001E; }
    .stat-card.lolos    .s-num { color: #7C3AED; }

    .stat-card.total    { border-top: 3px solid #111; }
    .stat-card.pending  { border-top: 3px solid #D97706; }
    .stat-card.verified { border-top: 3px solid #16A34A; }
    .stat-card.rejected { border-top: 3px solid #E8001E; }
    .stat-card.lolos    { border-top: 3px solid #7C3AED; }

    /* ── Filter Bar ── */
    .filter-bar {
        background: #fff;
        border: 1px solid #EBEBEB;
        border-radius: 16px;
        padding: 18px 22px;
        margin-bottom: 20px;
        display: flex; flex-wrap: wrap; gap: 12px; align-items: flex-end;
        box-shadow: 0 1px 3px rgba(0,0,0,.04);
    }
    .filter-field { display: flex; flex-direction: column; gap: 5px; }
    .f-label {
        font-family: 'Syne', sans-serif;
        font-size: 9px; font-weight: 700;
        letter-spacing: .1em; text-transform: uppercase;
        color: #AAAAAA;
    }
    input[type=text], select {
        font-family: 'DM Sans', sans-serif;
        font-size: 13px; color: #111;
        background: #FAFAFA;
        border: 1.5px solid #E8E8E8;
        border-radius: 9px;
        padding: 8px 13px;
        outline: none;
        transition: border-color .15s, background .15s;
        -webkit-appearance: none; appearance: none;
    }
    input[type=text]:focus, select:focus {
        border-color: #E8001E;
        background: #fff;
    }
    input[type=text]::placeholder { color: #C0C0C0; }

    .btn-filter {
        background: #E8001E; color: #fff; border: none;
        padding: 9px 20px; border-radius: 9px;
        font-family: 'Syne', sans-serif;
        font-size: 11px; font-weight: 700;
        letter-spacing: .08em; text-transform: uppercase;
        cursor: pointer; transition: background .15s;
    }
    .btn-filter:hover { background: #C0001A; }

    .btn-reset {
        background: #F5F5F5; color: #777; border: none;
        padding: 9px 16px; border-radius: 9px;
        font-family: 'Syne', sans-serif;
        font-size: 11px; font-weight: 700;
        letter-spacing: .08em; text-transform: uppercase;
        cursor: pointer; text-decoration: none;
        display: inline-flex; align-items: center;
        transition: background .15s;
    }
    .btn-reset:hover { background: #EBEBEB; color: #444; }

    .btn-csv {
        background: #E6F9EE; color: #15803D; border: none;
        padding: 9px 16px; border-radius: 9px;
        font-family: 'Syne', sans-serif;
        font-size: 11px; font-weight: 700;
        letter-spacing: .08em; text-transform: uppercase;
        cursor: pointer; text-decoration: none;
        display: inline-flex; align-items: center; gap: 6px;
        transition: background .15s;
    }
    .btn-csv:hover { background: #BBFDD0; }

    /* ── Table ── */
    .table-card {
        background: #fff;
        border: 1px solid #EBEBEB;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,.04);
    }
    .table-head-bar {
        padding: 16px 22px;
        border-bottom: 1px solid #F5F5F5;
        display: flex; align-items: center; justify-content: space-between;
    }
    .table-head-bar h2 {
        font-family: 'Syne', sans-serif;
        font-size: 13px; font-weight: 700; color: #111;
    }
    .table-head-bar span { font-size: 12px; color: #AAAAAA; }

    table { width: 100%; border-collapse: collapse; }

    thead tr { border-bottom: 1px solid #F0F0F0; background: #FAFAFA; }
    thead th {
        padding: 10px 18px; text-align: left;
        font-family: 'Syne', sans-serif;
        font-size: 9px; font-weight: 700;
        letter-spacing: .12em; text-transform: uppercase;
        color: #AAAAAA;
    }

    tbody tr {
        border-bottom: 1px solid #F8F8F8;
        transition: background .12s;
    }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: #FAFAFA; }
    tbody td { padding: 13px 18px; }

    .cand-name  { font-size: 14px; font-weight: 500; color: #111; }
    .cand-email { font-size: 12px; color: #AAAAAA; margin-top: 1px; }

    /* ── Badges ── */
    .badge {
        display: inline-flex; align-items: center;
        padding: 4px 10px; border-radius: 100px;
        font-family: 'Syne', sans-serif;
        font-size: 10px; font-weight: 700;
    }
    .badge-pending      { background: #FEF3C7; color: #D97706; }
    .badge-verified     { background: #DCFCE7; color: #15803D; }
    .badge-rejected     { background: #FFE4E7; color: #E8001E; }
    .badge-fm           { background: #EDE9FE; color: #7C3AED; }
    .badge-hm           { background: #DBEAFE; color: #2563EB; }
    .badge-lolos        { background: #EDE9FE; color: #7C3AED; }
    .badge-tidak-lolos  { background: #FFE4E7; color: #E8001E; }

    .btn-detail {
        display: inline-flex; align-items: center; gap: 5px;
        background: #F5F5F5; color: #666;
        border: none; padding: 7px 14px;
        border-radius: 8px;
        font-family: 'Syne', sans-serif;
        font-size: 10px; font-weight: 700;
        letter-spacing: .06em; text-transform: uppercase;
        text-decoration: none; cursor: pointer;
        transition: all .15s;
    }
    .btn-detail:hover { background: #E8001E; color: #fff; }

    /* ── Empty State ── */
    .empty-state {
        text-align: center; padding: 60px 20px;
    }
    .empty-state .emoji { font-size: 40px; margin-bottom: 14px; }
    .empty-state h3 { font-family:'Syne',sans-serif; font-size:15px; font-weight:700; color:#333; margin-bottom:6px; }
    .empty-state p  { font-size:13px; color:#AAA; }

    /* ── Pagination override ── */
    .pagination-wrap {
        padding: 14px 20px;
        border-top: 1px solid #F5F5F5;
    }

    /* ── Seleksi summary bar ── */
    .seleksi-summary {
        background: #FAF7FF;
        border: 1px solid #DDD6FE;
        border-radius: 12px;
        padding: 12px 20px;
        margin-bottom: 20px;
        display: flex; flex-wrap: wrap; gap: 20px; align-items: center;
    }
    .seleksi-summary .ss-label {
        font-family: 'Syne', sans-serif;
        font-size: 9px; font-weight: 700;
        letter-spacing: .1em; text-transform: uppercase;
        color: #7C3AED;
    }
    .seleksi-summary .ss-group {
        display: flex; align-items: center; gap: 8px;
    }
    .seleksi-summary .ss-num {
        font-family: 'Syne', sans-serif;
        font-size: 18px; font-weight: 800;
    }
    .seleksi-summary .ss-num.lolos     { color: #7C3AED; }
    .seleksi-summary .ss-num.tidak     { color: #E8001E; }
    .seleksi-summary .ss-num.belum     { color: #AAAAAA; }
    .seleksi-summary .ss-divider       { width: 1px; height: 24px; background: #DDD6FE; }
    .seleksi-summary .ss-pct {
        font-size: 11px; color: #9C6DE0;
        font-family: 'Syne', sans-serif; font-weight: 700;
    }

    /* ── MOBILE RESPONSIVE ── */
    @media(max-width:640px){
        .stat-grid { grid-template-columns: repeat(2,1fr); gap:10px; }
        .stat-card { padding: 14px 16px; }
        .stat-card .s-num { font-size: 28px; }

        .filter-bar { padding: 14px 16px; }
        .filter-field { width: 100%; }
        .filter-field input[type=text],
        .filter-field select { width: 100%; box-sizing: border-box; }
        .filter-bar > form > div:last-child { width: 100%; flex-wrap: wrap; }
        .btn-filter, .btn-reset, .btn-csv { flex: 1; justify-content: center; text-align: center; }

        .seleksi-summary { padding: 12px 14px; gap: 12px; }

        table thead { display: none; }
        table, tbody, tr, td { display: block; width: 100%; }
        tbody tr {
            border: 1px solid #EBEBEB;
            border-radius: 12px;
            margin-bottom: 10px;
            padding: 14px 16px;
            background: #fff;
            box-shadow: 0 1px 4px rgba(0,0,0,.04);
        }
        tbody td {
            padding: 4px 0;
            border: none;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }
        tbody td:first-child { display: none; }
        tbody td:nth-child(2) {
            padding-bottom: 8px;
            margin-bottom: 4px;
            border-bottom: 1px solid #F5F5F5;
        }
        tbody td:nth-child(3)::before { content: "Domisili"; font-family:'Syne',sans-serif; font-size:9px; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:#C0C0C0; min-width:72px; padding-top:2px; }
        tbody td:nth-child(4)::before { content: "Race";     font-family:'Syne',sans-serif; font-size:9px; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:#C0C0C0; min-width:72px; padding-top:2px; }
        tbody td:nth-child(5)::before { content: "Status";   font-family:'Syne',sans-serif; font-size:9px; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:#C0C0C0; min-width:72px; padding-top:2px; }
        tbody td:nth-child(6)::before { content: "Seleksi";  font-family:'Syne',sans-serif; font-size:9px; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:#C0C0C0; min-width:72px; padding-top:2px; }
        tbody td:nth-child(7)::before { content: "Daftar";   font-family:'Syne',sans-serif; font-size:9px; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:#C0C0C0; min-width:72px; padding-top:2px; }
        tbody td:last-child { margin-top: 10px; }
        .btn-detail { width: 100%; justify-content: center; }

        .pagination-wrap { padding: 12px 16px; }
        .pagination-wrap nav { overflow-x: auto; }
        .table-head-bar { padding: 12px 16px; }
    }
</style>
@endpush

@section('content')

{{-- ── Stat Cards ── --}}
<div class="stat-grid">
    <div class="stat-card total">
        <span class="s-label">Total Kandidat</span>
        <span class="s-num">{{ $stats['total'] }}</span>
        <span class="s-sub">Semua pendaftar</span>
    </div>
    <div class="stat-card pending">
        <span class="s-label">Pending</span>
        <span class="s-num">{{ $stats['pending'] }}</span>
        <span class="s-sub">Menunggu review</span>
    </div>
    <div class="stat-card verified">
        <span class="s-label">Terverifikasi</span>
        <span class="s-num">{{ $stats['verified'] }}</span>
        <span class="s-sub">Diterima panitia</span>
    </div>
    <div class="stat-card rejected">
        <span class="s-label">Ditolak</span>
        <span class="s-num">{{ $stats['rejected'] }}</span>
        <span class="s-sub">Tidak lolos seleksi</span>
    </div>
    <div class="stat-card lolos">
        <span class="s-label">Lolos Pacer</span>
        <span class="s-num">{{ $stats['lolos'] }}</span>
        <span class="s-sub">Lolos seleksi akhir</span>
    </div>
</div>

{{-- ── Seleksi Summary Bar (hanya tampil jika ada yang sudah diseleksi) ── --}}
@if($stats['lolos'] > 0 || $stats['tidak_lolos'] > 0)
@php
    $totalSeleksi = $stats['lolos'] + $stats['tidak_lolos'];
    $pctLolos = $totalSeleksi > 0 ? round($stats['lolos'] / $totalSeleksi * 100) : 0;
@endphp
<div class="seleksi-summary">
    <span class="ss-label">Rekap Seleksi Pacer</span>
    <div class="ss-divider"></div>
    <div class="ss-group">
        <span class="ss-num lolos">{{ $stats['lolos'] }}</span>
        <div>
            <div style="font-family:'Syne',sans-serif; font-size:9px; font-weight:700; text-transform:uppercase; color:#7C3AED;">Lolos</div>
            <div class="ss-pct">{{ $pctLolos }}% dari yang diseleksi</div>
        </div>
    </div>
    <div class="ss-divider"></div>
    <div class="ss-group">
        <span class="ss-num tidak">{{ $stats['tidak_lolos'] }}</span>
        <div>
            <div style="font-family:'Syne',sans-serif; font-size:9px; font-weight:700; text-transform:uppercase; color:#E8001E;">Tidak Lolos</div>
            <div style="font-size:11px; color:#E8A0A0; font-family:'Syne',sans-serif; font-weight:700;">{{ 100 - $pctLolos }}% dari yang diseleksi</div>
        </div>
    </div>
    <div class="ss-divider"></div>
    <div class="ss-group">
        <span class="ss-num belum">{{ $stats['belum_seleksi'] }}</span>
        <div>
            <div style="font-family:'Syne',sans-serif; font-size:9px; font-weight:700; text-transform:uppercase; color:#AAAAAA;">Belum Diseleksi</div>
            <div style="font-size:11px; color:#CCCCCC; font-family:'Syne',sans-serif; font-weight:700;">Dari yang terverifikasi</div>
        </div>
    </div>
</div>
@endif

{{-- ── Filter Bar ── --}}
<div class="filter-bar">
    <form method="GET" action="{{ route('admin.dashboard') }}"
          style="display:flex; flex-wrap:wrap; gap:12px; align-items:flex-end; width:100%;">

        <div class="filter-field" style="flex:1; min-width:180px;">
            <span class="f-label">Cari Nama / Email</span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama atau email...">
        </div>

        <div class="filter-field">
            <span class="f-label">Status</span>
            <select name="status">
                <option value="">Semua Status</option>
                <option value="pending"  {{ request('status')==='pending'  ?'selected':'' }}>Pending</option>
                <option value="verified" {{ request('status')==='verified' ?'selected':'' }}>Terverifikasi</option>
                <option value="rejected" {{ request('status')==='rejected' ?'selected':'' }}>Ditolak</option>
            </select>
        </div>

        <div class="filter-field">
            <span class="f-label">Pengalaman Race</span>
            <select name="race">
                <option value="">Semua</option>
                <option value="fm"   {{ request('race')==='fm'   ?'selected':'' }}>Full Marathon</option>
                <option value="hm"   {{ request('race')==='hm'   ?'selected':'' }}>Half Marathon</option>
                <option value="10k"  {{ request('race')==='10k'  ?'selected':'' }}>10K</option>
                <option value="5k"   {{ request('race')==='5k'   ?'selected':'' }}>5K</option>
                <option value="none" {{ request('race')==='none' ?'selected':'' }}>Belum FM/HM</option>
            </select>
        </div>

        <div class="filter-field">
            <span class="f-label">Seleksi Pacer</span>
            <select name="seleksi">
                <option value="">Semua</option>
                <option value="lolos"       {{ request('seleksi')==='lolos'       ?'selected':'' }}>Lolos</option>
                <option value="tidak_lolos" {{ request('seleksi')==='tidak_lolos' ?'selected':'' }}>Tidak Lolos</option>
                <option value="belum"       {{ request('seleksi')==='belum'       ?'selected':'' }}>Belum Diseleksi</option>
            </select>
        </div>

        <div class="filter-field">
            <span class="f-label">Domisili</span>
            <input type="text" name="domisili" value="{{ request('domisili') }}" placeholder="Samarinda...">
        </div>

        <div style="display:flex; gap:8px; align-items:center; padding-bottom:1px;">
            <button type="submit" class="btn-filter">Filter</button>
            <a href="{{ route('admin.dashboard') }}" class="btn-reset">Reset</a>
            <a href="{{ route('admin.export') }}{{ request()->getQueryString() ? '?'.request()->getQueryString() : '' }}"
               class="btn-csv">
                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export CSV
            </a>
        </div>
    </form>
</div>

{{-- ── Table ── --}}
<div class="table-card">
    <div class="table-head-bar">
        <h2>Daftar Kandidat</h2>
        <span>{{ $candidates->total() }} data ditemukan</span>
    </div>

    @if($candidates->isEmpty())
    <div class="empty-state">
        <div class="emoji">🔍</div>
        <h3>Tidak ada kandidat</h3>
        <p>Coba ubah parameter filter atau tunggu pendaftar baru.</p>
    </div>
    @else
    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kandidat</th>
                    <th>Domisili</th>
                    <th>Race</th>
                    <th>Status</th>
                    <th>Seleksi</th>
                    <th>Tgl Daftar</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($candidates as $c)
                <tr>
                    <td style="color:#CCC; font-size:12px;">{{ $c->id }}</td>
                    <td>
                        <div class="cand-name">{{ $c->nama }}</div>
                        <div class="cand-email">{{ $c->email }}</div>
                    </td>
                    <td style="font-size:13px; color:#666;">{{ $c->domisili }}</td>
                    <td>
                        <div style="display:flex; gap:4px; flex-wrap:wrap;">
                            @if($c->is_full_marathon)
                                <span class="badge badge-fm">FM</span>
                            @endif
                            @if($c->is_half_marathon)
                                <span class="badge badge-hm">HM</span>
                            @endif
                            @if($c->is_10k === 'pernah')
                                <span class="badge" style="background:#FEF9C3;color:#854D0E;">10K</span>
                            @endif
                            @if(!$c->is_full_marathon && !$c->is_half_marathon && $c->is_10k !== 'pernah')
                                <span style="color:#CCC; font-size:12px;">—</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <span class="badge badge-{{ $c->status->value }}">
                            {{ $c->status->label() }}
                        </span>
                    </td>
                    <td>
                        @if($c->hasil_seleksi === 'lolos')
                            <span class="badge badge-lolos">
                                <svg width="9" height="9" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right:3px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                                Lolos
                            </span>
                        @elseif($c->hasil_seleksi === 'tidak_lolos')
                            <span class="badge badge-tidak-lolos">
                                <svg width="9" height="9" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right:3px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Tidak Lolos
                            </span>
                        @else
                            <span style="color:#CCC; font-size:12px;">—</span>
                        @endif
                    </td>
                    <td style="font-size:12px; color:#AAAAAA;">
                        {{ $c->created_at->format('d M Y') }}
                    </td>
                    <td>
                        <a href="{{ route('admin.candidate.show', $c) }}" class="btn-detail">
                            Detail →
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pagination-wrap">
        {{ $candidates->links() }}
    </div>
    @endif
</div>

@endsection