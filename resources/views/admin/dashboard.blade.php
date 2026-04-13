@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')

<style>
    .stat-card {
        background:#161616; border:1px solid rgba(255,255,255,.07);
        border-radius:16px; padding:22px 24px;
    }
    .stat-label {
        font-family:'Syne',sans-serif; font-size:9px; font-weight:700;
        letter-spacing:.12em; text-transform:uppercase; color:#555;
        margin-bottom:8px;
    }
    .stat-num { font-family:'Syne',sans-serif; font-size:36px; font-weight:800; line-height:1; }

    .filter-bar {
        background:#161616; border:1px solid rgba(255,255,255,.07);
        border-radius:16px; padding:20px 22px;
        display:flex; flex-wrap:wrap; gap:12px; align-items:flex-end;
        margin-bottom:20px;
    }
    .filter-field { display:flex; flex-direction:column; gap:5px; }
    .filter-label {
        font-family:'Syne',sans-serif; font-size:9px; font-weight:700;
        letter-spacing:.1em; text-transform:uppercase; color:#555;
    }
    input[type=text],select {
        font-family:'DM Sans',sans-serif; font-size:13px;
        background:#0D0D0D; border:1.5px solid rgba(255,255,255,.08);
        border-radius:9px; padding:9px 13px; color:#fff;
        outline:none; transition:border-color .18s;
        -webkit-appearance:none; appearance:none;
    }
    input[type=text]:focus,select:focus { border-color:#E8001E; }
    input[type=text]::placeholder { color:#444; }

    .btn-filter {
        background:#E8001E; color:#fff; border:none; padding:9px 18px;
        border-radius:9px; font-family:'Syne',sans-serif; font-size:11px;
        font-weight:700; letter-spacing:.08em; text-transform:uppercase;
        cursor:pointer; transition:background .18s;
    }
    .btn-filter:hover { background:#C0001A; }
    .btn-reset {
        background:rgba(255,255,255,.06); color:#888; border:none;
        padding:9px 16px; border-radius:9px;
        font-family:'Syne',sans-serif; font-size:11px;
        font-weight:700; letter-spacing:.08em; text-transform:uppercase;
        cursor:pointer; text-decoration:none; display:inline-flex; align-items:center;
    }
    .btn-reset:hover { background:rgba(255,255,255,.1); color:#fff; }
    .btn-csv {
        background:#16A34A; color:#fff; border:none; padding:9px 16px;
        border-radius:9px; font-family:'Syne',sans-serif; font-size:11px;
        font-weight:700; letter-spacing:.08em; text-transform:uppercase;
        cursor:pointer; text-decoration:none; display:inline-flex;
        align-items:center; gap:6px; transition:background .18s;
    }
    .btn-csv:hover { background:#15803D; }

    .table-wrap {
        background:#161616; border:1px solid rgba(255,255,255,.07);
        border-radius:16px; overflow:hidden;
    }
    .table-head {
        padding:16px 22px;
        border-bottom:1px solid rgba(255,255,255,.06);
        display:flex; align-items:center; justify-content:space-between;
    }
    .table-head h2 {
        font-family:'Syne',sans-serif; font-size:13px; font-weight:700; color:#fff;
    }
    .table-head span { font-size:12px; color:#555; }
    table { width:100%; border-collapse:collapse; }
    thead tr { border-bottom:1px solid rgba(255,255,255,.05); }
    thead th {
        padding:11px 18px; text-align:left;
        font-family:'Syne',sans-serif; font-size:9px; font-weight:700;
        letter-spacing:.12em; text-transform:uppercase; color:#444;
    }
    tbody tr { border-bottom:1px solid rgba(255,255,255,.04); transition:background .15s; }
    tbody tr:last-child { border-bottom:none; }
    tbody tr:hover { background:rgba(255,255,255,.02); }
    tbody td { padding:14px 18px; }

    .badge {
        display:inline-flex; align-items:center; gap:5px;
        padding:4px 10px; border-radius:100px;
        font-family:'Syne',sans-serif; font-size:10px; font-weight:700; letter-spacing:.04em;
    }
    .badge-pending  { background:rgba(234,179,8,.12);  color:#EAB308; }
    .badge-verified { background:rgba(22,163,74,.12);  color:#16A34A; }
    .badge-rejected { background:rgba(232,0,30,.12);   color:#E8001E; }
    .badge-fm       { background:rgba(168,85,247,.12); color:#A855F7; }
    .badge-hm       { background:rgba(59,130,246,.12); color:#3B82F6; }

    .name-cell p:first-child { font-size:14px; color:#fff; font-weight:500; }
    .name-cell p:last-child  { font-size:12px; color:#555; margin-top:2px; }

    .btn-detail {
        font-family:'Syne',sans-serif; font-size:10px; font-weight:700;
        letter-spacing:.06em; text-transform:uppercase;
        background:rgba(255,255,255,.06); color:#AAA;
        padding:6px 14px; border-radius:8px;
        text-decoration:none; transition:all .18s; display:inline-block;
    }
    .btn-detail:hover { background:var(--red,#E8001E); color:#fff; }

    .empty-state {
        text-align:center; padding:60px 20px;
        color:#444;
    }
    .empty-state p:first-child { font-size:32px; margin-bottom:12px; }
    .empty-state p { font-size:13px; }

    .pagination-wrap {
        padding:14px 18px;
        border-top:1px solid rgba(255,255,255,.05);
        display:flex; align-items:center; justify-content:flex-end;
    }
    .pagination-wrap nav { display:flex; gap:4px; }
    .pagination-wrap nav * { color:#555 !important; }
    .pagination-wrap nav .page-link { color:#555; }
</style>

{{-- ── Stats ─────────────────────────────────────────── --}}
<div style="display:grid; grid-template-columns:repeat(4,1fr); gap:14px; margin-bottom:20px;">
    <div class="stat-card">
        <div class="stat-label">Total Kandidat</div>
        <div class="stat-num" style="color:#fff;">{{ $stats['total'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Pending</div>
        <div class="stat-num" style="color:#EAB308;">{{ $stats['pending'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Terverifikasi</div>
        <div class="stat-num" style="color:#16A34A;">{{ $stats['verified'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Ditolak</div>
        <div class="stat-num" style="color:#E8001E;">{{ $stats['rejected'] }}</div>
    </div>
</div>

{{-- ── Filter ───────────────────────────────────────── --}}
<div class="filter-bar">
    <form method="GET" action="{{ route('admin.dashboard') }}"
          style="display:flex; flex-wrap:wrap; gap:12px; align-items:flex-end; width:100%;">

        <div class="filter-field" style="flex:1; min-width:180px;">
            <span class="filter-label">Cari Nama / Email</span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama atau email...">
        </div>

        <div class="filter-field">
            <span class="filter-label">Status</span>
            <select name="status">
                <option value="">Semua</option>
                <option value="pending"  {{ request('status')==='pending'  ?'selected':'' }}>Pending</option>
                <option value="verified" {{ request('status')==='verified' ?'selected':'' }}>Verified</option>
                <option value="rejected" {{ request('status')==='rejected' ?'selected':'' }}>Rejected</option>
            </select>
        </div>

        <div class="filter-field">
            <span class="filter-label">Race</span>
            <select name="race">
                <option value="">Semua</option>
                <option value="fm"   {{ request('race')==='fm'   ?'selected':'' }}>Full Marathon</option>
                <option value="hm"   {{ request('race')==='hm'   ?'selected':'' }}>Half Marathon</option>
                <option value="none" {{ request('race')==='none' ?'selected':'' }}>Belum FM/HM</option>
            </select>
        </div>

        <div class="filter-field">
            <span class="filter-label">Domisili</span>
            <input type="text" name="domisili" value="{{ request('domisili') }}" placeholder="Samarinda...">
        </div>

        <div style="display:flex; gap:8px; align-items:center;">
            <button type="submit" class="btn-filter">Filter</button>
            <a href="{{ route('admin.dashboard') }}" class="btn-reset">Reset</a>
            <a href="{{ route('admin.export') }}{{ request()->getQueryString() ? '?'.request()->getQueryString() : '' }}"
               class="btn-csv">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                CSV
            </a>
        </div>
    </form>
</div>

{{-- ── Table ────────────────────────────────────────── --}}
<div class="table-wrap">
    <div class="table-head">
        <h2>Daftar Kandidat</h2>
        <span>{{ $candidates->total() }} data ditemukan</span>
    </div>

    @if($candidates->isEmpty())
    <div class="empty-state">
        <p>🔍</p>
        <p style="color:#fff; font-weight:500; margin-bottom:4px;">Tidak ada kandidat</p>
        <p>Coba ubah parameter filter pencarian.</p>
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
                    <th>Daftar</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($candidates as $c)
                <tr>
                    <td style="color:#444; font-size:12px;">{{ $c->id }}</td>
                    <td>
                        <div class="name-cell">
                            <p>{{ $c->nama }}</p>
                            <p>{{ $c->email }}</p>
                        </div>
                    </td>
                    <td style="font-size:13px; color:#888;">{{ $c->domisili }}</td>
                    <td>
                        <div style="display:flex; gap:5px; flex-wrap:wrap;">
                            @if($c->is_full_marathon)
                                <span class="badge badge-fm">FM</span>
                            @endif
                            @if($c->is_half_marathon)
                                <span class="badge badge-hm">HM</span>
                            @endif
                            @if(!$c->is_full_marathon && !$c->is_half_marathon)
                                <span style="color:#444; font-size:12px;">—</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <span class="badge badge-{{ $c->status->value }}">
                            {{ $c->status->label() }}
                        </span>
                    </td>
                    <td style="font-size:12px; color:#444;">
                        {{ $c->created_at->format('d M Y') }}
                    </td>
                    <td>
                        <a href="{{ route('admin.candidate.show', $c) }}" class="btn-detail">Detail →</a>
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