@extends('layouts.app')
@section('title', 'Pendaftaran Pacer — Bayan Run 2026')

@section('content')
<div class="form-shell" x-data="regForm()">

    {{-- ── Hero ──────────────────────────────────────────── --}}
    <div class="page-hero">
        <h1>Daftar Jadi <span>Pacer</span></h1>
        <p>Isi seluruh data dengan benar dan jujur.<br>Ketidakjujuran data berakibat diskualifikasi permanen.</p>
        <div class="steps" style="margin-top:20px; flex-wrap:wrap;">
            @foreach(['Data Pribadi','Full Marathon','Half Marathon','10K & 5K','Mileage','Best Time','Pengalaman Pacer','Komitmen','Dokumen'] as $i => $s)
            <div class="step-chip"><span class="num">{{ $i+1 }}</span>{{ $s }}</div>
            @endforeach
        </div>
    </div>

    {{-- ── Error Summary ──────────────────────────────────── --}}
    @if($errors->any())
    <div class="error-summary">
        <h3>{{ $errors->count() }} kesalahan perlu diperbaiki</h3>
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('candidate.store') }}" enctype="multipart/form-data">
        @csrf

        {{-- ════════════════════════════════════════════════
             SECTION 1 — DATA PRIBADI
        ════════════════════════════════════════════════ --}}
        <div class="card">
            <div class="card-head"><div class="card-num">1</div><div class="card-title">Data Pribadi</div></div>
            <div class="card-body">

                <div class="field">
                    <label class="label">Email <span class="req">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" class="{{ $errors->has('email')?'err':'' }}">
                    @error('email')<span class="err-msg">{{ $message }}</span>@enderror
                </div>

                <div class="field-row">
                    <div class="field">
                        <label class="label">Nama Lengkap <span class="req">*</span><span class="hint">(sesuai KTP)</span></label>
                        <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Nama Lengkap" class="{{ $errors->has('nama')?'err':'' }}">
                        @error('nama')<span class="err-msg">{{ $message }}</span>@enderror
                    </div>
                    <div class="field">
                        <label class="label">Tanggal Lahir <span class="req">*</span></label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" max="{{ now()->format('Y-m-d') }}" class="{{ $errors->has('tanggal_lahir')?'err':'' }}">
                        @error('tanggal_lahir')<span class="err-msg">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="field">
                    <label class="label">Domisili <span class="req">*</span><span class="hint">(kota/kabupaten)</span></label>
                    <input type="text" name="domisili" value="{{ old('domisili') }}" placeholder="Samarinda, Kalimantan Timur" class="{{ $errors->has('domisili')?'err':'' }}">
                    @error('domisili')<span class="err-msg">{{ $message }}</span>@enderror
                </div>

                <div class="field">
                    <label class="label">Alamat Domisili Sesuai KTP <span class="req">*</span></label>
                    <textarea name="alamat" rows="3" placeholder="Jl. Contoh No. 1, RT/RW, Kelurahan, Kecamatan..." class="{{ $errors->has('alamat')?'err':'' }}">{{ old('alamat') }}</textarea>
                    @error('alamat')<span class="err-msg">{{ $message }}</span>@enderror
                </div>

                <div class="field">
                    <label class="label">Upload KTP <span class="req">*</span><span class="hint">PDF/JPG/PNG · maks 10MB</span></label>
                    <label for="ktp_file" class="upload" :class="files.ktp?'done':''" style="display:block">
                        <input type="file" id="ktp_file" name="ktp_file" accept=".pdf,.jpg,.jpeg,.png" style="display:none" @change="files.ktp=$event.target.files[0]?.name">
                        @include('candidate._upload_inner', ['field'=>'ktp', 'text'=>'Upload KTP'])
                    </label>
                    @error('ktp_file')<span class="err-msg">{{ $message }}</span>@enderror
                </div>

                <div class="field-row">
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

        {{-- ════════════════════════════════════════════════
             SECTION 2 — FULL MARATHON
        ════════════════════════════════════════════════ --}}
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
                    <div class="field-row">
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
                        <label for="fm_cert" class="upload" :class="files.fm?'done':''" style="display:block">
                            <input type="file" id="fm_cert" name="fm_certificate" accept=".jpg,.jpeg,.png" style="display:none" @change="files.fm=$event.target.files[0]?.name">
                            @include('candidate._upload_inner', ['field'=>'fm', 'text'=>'Upload Sertifikat Full Marathon'])
                        </label>
                        @error('fm_certificate')<span class="err-msg">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- ════════════════════════════════════════════════
             SECTION 3 — HALF MARATHON
        ════════════════════════════════════════════════ --}}
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
                    <div class="field-row">
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
                        <label for="hm_cert" class="upload" :class="files.hm?'done':''" style="display:block">
                            <input type="file" id="hm_cert" name="hm_certificate" accept=".jpg,.jpeg,.png" style="display:none" @change="files.hm=$event.target.files[0]?.name">
                            @include('candidate._upload_inner', ['field'=>'hm', 'text'=>'Upload Sertifikat Half Marathon'])
                        </label>
                        @error('hm_certificate')<span class="err-msg">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- ════════════════════════════════════════════════
             SECTION 4 — 10K & 5K
        ════════════════════════════════════════════════ --}}
        <div class="card" x-show="fm!=='pernah' || hm!=='pernah'" x-transition>
            <div class="card-head"><div class="card-num">4</div><div class="card-title">Pengalaman 10K & 5K</div></div>
            <div class="card-body">
                <div class="info-box">
                    💡 Bagian ini untuk kandidat yang belum pernah FM atau HM. Kandidat yang sudah pernah FM & HM bisa melewati dengan memilih <strong>"Saya Melewati"</strong>.
                </div>

                {{-- 10K --}}
                <div style="border-bottom:1px solid #F0F0F0; padding-bottom:20px;">
                    <div class="field">
                        <label class="label">Apakah pernah mengikuti Race 10K? <span class="req">*</span></label>
                        <div class="radio-group">
                            @include('candidate._radio', ['name'=>'is_10k','value'=>'pernah','model'=>'r10k','label'=>'Pernah','sub'=>''])
                            @include('candidate._radio', ['name'=>'is_10k','value'=>'tidak','model'=>'r10k','label'=>'Tidak Pernah','sub'=>''])
                            @include('candidate._radio', ['name'=>'is_10k','value'=>'skip','model'=>'r10k','label'=>'Saya Melewati','sub'=>''])
                        </div>
                        @error('is_10k')<span class="err-msg">{{ $message }}</span>@enderror
                    </div>
                    <div class="cond-block" x-show="r10k==='pernah'" x-transition>
                        <div class="field-row">
                            <div class="field">
                                <label class="label">Nama Event 10K <span class="req">*</span></label>
                                <input type="text" name="race_10k_event" value="{{ old('race_10k_event') }}" placeholder="Run For Life 10K 2024">
                                @error('race_10k_event')<span class="err-msg">{{ $message }}</span>@enderror
                            </div>
                            <div class="field">
                                <label class="label">Tahun <span class="req">*</span></label>
                                <input type="number" name="race_10k_year" value="{{ old('race_10k_year') }}" placeholder="{{ date('Y') }}" min="2000" max="{{ date('Y') }}">
                                @error('race_10k_year')<span class="err-msg">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Upload Sertifikat 10K <span class="hint">(jika ada)</span></label>
                            <label for="cert_10k" class="upload" :class="files.r10k?'done':''" style="display:block">
                                <input type="file" id="cert_10k" name="race_10k_certificate" accept=".jpg,.jpeg,.png" style="display:none" @change="files.r10k=$event.target.files[0]?.name">
                                @include('candidate._upload_inner', ['field'=>'r10k', 'text'=>'Upload Sertifikat 10K (Opsional)'])
                            </label>
                        </div>
                    </div>
                </div>

                {{-- 5K --}}
                <div style="padding-top:4px;">
                    <div class="field">
                        <label class="label">Apakah pernah mengikuti Race 5K? <span class="req">*</span></label>
                        <div class="radio-group">
                            @include('candidate._radio', ['name'=>'is_5k','value'=>'pernah','model'=>'r5k','label'=>'Pernah','sub'=>''])
                            @include('candidate._radio', ['name'=>'is_5k','value'=>'tidak','model'=>'r5k','label'=>'Tidak Pernah','sub'=>''])
                            @include('candidate._radio', ['name'=>'is_5k','value'=>'skip','model'=>'r5k','label'=>'Saya Melewati','sub'=>''])
                        </div>
                        @error('is_5k')<span class="err-msg">{{ $message }}</span>@enderror
                    </div>
                    <div class="cond-block" x-show="r5k==='pernah'" x-transition>
                        <div class="field-row">
                            <div class="field">
                                <label class="label">Nama Event 5K <span class="req">*</span></label>
                                <input type="text" name="race_5k_event" value="{{ old('race_5k_event') }}" placeholder="Fun Run 5K 2024">
                                @error('race_5k_event')<span class="err-msg">{{ $message }}</span>@enderror
                            </div>
                            <div class="field">
                                <label class="label">Tahun <span class="req">*</span></label>
                                <input type="number" name="race_5k_year" value="{{ old('race_5k_year') }}" placeholder="{{ date('Y') }}" min="2000" max="{{ date('Y') }}">
                                @error('race_5k_year')<span class="err-msg">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Upload Sertifikat 5K <span class="hint">(jika ada)</span></label>
                            <label for="cert_5k" class="upload" :class="files.r5k?'done':''" style="display:block">
                                <input type="file" id="cert_5k" name="race_5k_certificate" accept=".jpg,.jpeg,.png" style="display:none" @change="files.r5k=$event.target.files[0]?.name">
                                @include('candidate._upload_inner', ['field'=>'r5k', 'text'=>'Upload Sertifikat 5K (Opsional)'])
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ════════════════════════════════════════════════
             SECTION 4B — TRAIL / EVENT NON-ROAD RACE
             (hanya tampil jika belum ada pengalaman road race sama sekali)
        ════════════════════════════════════════════════ --}}
        <div class="card" x-show="fm==='tidak' && hm==='tidak' && r10k!=='pernah' && r5k!=='pernah'" x-transition>
            <div class="card-head" style="background:#1A1A2E;"><div class="card-num" style="background:#4F46E5;">4B</div><div class="card-title">Event Lainnya (Trail / Non-Road Race)</div></div>
            <div class="card-body">
                <div class="info-box">
                    📋 Bagian ini untuk kandidat yang belum pernah mengikuti road race manapun. Jika pernah mengikuti trail event atau event resmi lain, silakan isi di sini.
                </div>
                <div class="field">
                    <label class="label">Status <span class="req">*</span></label>
                    <div style="display:flex; flex-direction:column; gap:8px;">
                        <label class="radio-opt">
                            <input type="radio" name="trail_status" value="trail" x-model="trailStatus" {{ old('trail_status')==='trail'?'checked':'' }}><div class="radio-pip"></div>
                            <div><div class="radio-label">Pernah Trail Event</div></div>
                        </label>
                        <label class="radio-opt">
                            <input type="radio" name="trail_status" value="none" x-model="trailStatus" {{ old('trail_status')==='none'?'checked':'' }}><div class="radio-pip"></div>
                            <div><div class="radio-label">Saya Tidak Pernah Mengikuti Event Apapun</div></div>
                        </label>
                        <label class="radio-opt">
                            <input type="radio" name="trail_status" value="skip" x-model="trailStatus" {{ old('trail_status')==='skip'?'checked':'' }}><div class="radio-pip"></div>
                            <div><div class="radio-label">Saya Ingin Melewati Pertanyaan Ini</div></div>
                        </label>
                    </div>
                </div>
                <div class="cond-block" x-show="trailStatus==='trail'" x-transition>
                    <div class="field-row">
                        <div class="field">
                            <label class="label">Nama Event <span class="req">*</span></label>
                            <input type="text" name="trail_event" value="{{ old('trail_event') }}" placeholder="Trail Run Borneo 2024">
                        </div>
                        <div class="field">
                            <label class="label">Tahun <span class="req">*</span></label>
                            <input type="number" name="trail_year" value="{{ old('trail_year') }}" placeholder="{{ date('Y') }}" min="2000" max="{{ date('Y') }}">
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Upload Bukti / Sertifikat <span class="req">*</span></label>
                        <label for="trail_cert" class="upload" :class="files.trail?'done':''" style="display:block">
                            <input type="file" id="trail_cert" name="trail_certificate" accept=".jpg,.jpeg,.png" style="display:none" @change="files.trail=$event.target.files[0]?.name">
                            @include('candidate._upload_inner', ['field'=>'trail', 'text'=>'Upload Bukti Event Trail'])
                        </label>
                    </div>
                </div>
            </div>
        </div>

        {{-- ════════════════════════════════════════════════
             SECTION 5 — MILEAGE
        ════════════════════════════════════════════════ --}}
        <div class="card">
            <div class="card-head"><div class="card-num">5</div><div class="card-title">Data Mileage (Desember 2025 — Maret 2026)</div></div>
            <div class="card-body">
                <div class="info-box">
                    📊 Upload screenshot grafik mileage dari <strong>Strava</strong> atau aplikasi smartwatch Anda (Garmin, Apple Watch, dll). Isi angka total jarak lari dalam satuan <strong>kilometer (km)</strong>.
                </div>

                @foreach([
                    ['dec_2025', 'Desember 2025'],
                    ['jan_2026', 'Januari 2026'],
                    ['feb_2026', 'Februari 2026'],
                    ['mar_2026', 'Maret 2026'],
                ] as [$key, $label])
                <div style="background:#FAFAFA; border:1px solid #EEEEEE; border-radius:14px; padding:18px; display:grid; grid-template-columns:1fr 1fr; gap:16px; align-items:start;">
                    <div class="field" style="margin:0">
                        <label class="label">{{ $label }} — Total Jarak <span class="req">*</span><span class="hint">angka saja (km)</span></label>
                        <div style="position:relative;">
                            <input type="number" name="mileage_{{ $key }}" value="{{ old('mileage_'.$key) }}"
                                   placeholder="0" min="0" step="0.01"
                                   style="padding-right:44px;"
                                   class="{{ $errors->has('mileage_'.$key)?'err':'' }}">
                            <span style="position:absolute; right:14px; top:50%; transform:translateY(-50%); font-size:12px; color:#AAA; font-family:'Syne',sans-serif; font-weight:700;">km</span>
                        </div>
                        @error('mileage_'.$key)<span class="err-msg">{{ $message }}</span>@enderror
                    </div>
                    <div class="field" style="margin:0">
                        <label class="label">Upload Grafik {{ $label }} <span class="req">*</span></label>
                        <label for="mg_{{ $key }}" class="upload" :class="files.mg_{{ $key }}?'done':''" style="display:block; padding:14px;">
                            <input type="file" id="mg_{{ $key }}" name="mileage_{{ $key }}_graph" accept=".jpg,.jpeg,.png" style="display:none" @change="files.mg_{{ $key }}=$event.target.files[0]?.name">
                            <svg style="width:24px;height:24px;margin:0 auto 6px;color:#BBB" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            <p style="font-size:12px;color:#888" x-text="files.mg_{{ $key }} || 'Upload grafik {{ $label }}'"></p>
                        </label>
                        @error('mileage_'.$key.'_graph')<span class="err-msg">{{ $message }}</span>@enderror
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- ════════════════════════════════════════════════
             SECTION 6 — BEST TIME / CATATAN WAKTU TERBAIK
        ════════════════════════════════════════════════ --}}
        <div class="card">
            <div class="card-head"><div class="card-num">6</div><div class="card-title">Catatan Waktu Terbaik</div></div>
            <div class="card-body">
                <div class="info-box warning">
                    ⏱️ Isi catatan waktu terbaik dari Strava atau device lain yang menunjukkan keabsahannya. Kosongkan jika belum pernah menempuh jarak tersebut. <strong>Format: H:MM:SS</strong> (contoh: 4:30:00 untuk FM 4 jam 30 menit).
                </div>

                @foreach([
                    ['fm',  'Full Marathon (42K)',  'best_time_fm',  'best_time_fm_file'],
                    ['hm',  'Half Marathon (21K)',  'best_time_hm',  'best_time_hm_file'],
                    ['10k', '10 Kilometer',          'best_time_10k', 'best_time_10k_file'],
                    ['5k',  '5 Kilometer',           'best_time_5k',  'best_time_5k_file'],
                ] as [$key, $label, $timeField, $fileField])
                <div style="background:#FAFAFA; border:1px solid #EEE; border-radius:14px; padding:18px; display:grid; grid-template-columns:1fr 1fr; gap:16px; align-items:start;">
                    <div class="field" style="margin:0">
                        <label class="label">{{ $label }}<span class="hint">(Opsional jika belum pernah)</span></label>
                        <input type="text" name="{{ $timeField }}" value="{{ old($timeField) }}"
                               placeholder="H:MM:SS"
                               class="{{ $errors->has($timeField)?'err':'' }}">
                        @error($timeField)<span class="err-msg">{{ $message }}</span>@enderror
                    </div>
                    <div class="field" style="margin:0">
                        <label class="label">Upload Bukti Waktu {{ $label }}</label>
                        <label for="bt_{{ $key }}" class="upload" :class="files.bt_{{ $key }}?'done':''" style="display:block; padding:14px;">
                            <input type="file" id="bt_{{ $key }}" name="{{ $fileField }}" accept=".jpg,.jpeg,.png" style="display:none" @change="files.bt_{{ $key }}=$event.target.files[0]?.name">
                            <svg style="width:24px;height:24px;margin:0 auto 6px;color:#BBB" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p style="font-size:12px;color:#888" x-text="files.bt_{{ $key }} || 'Upload bukti (Opsional)'"></p>
                        </label>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- ════════════════════════════════════════════════
             SECTION 7 — PENGALAMAN PACER
        ════════════════════════════════════════════════ --}}
        <div class="card">
            <div class="card-head"><div class="card-num">7</div><div class="card-title">Pengalaman Menjadi Pacer / Running Buddies</div></div>
            <div class="card-body">
                <div class="field">
                    <label class="label">Apakah pernah menjadi Pace Setter / Running Buddies dalam event? <span class="req">*</span></label>
                    <div class="radio-group">
                        @include('candidate._radio', ['name'=>'is_pacer_experience','value'=>'pernah','model'=>'pacerExp','label'=>'Pernah','sub'=>'Isi detail event'])
                        @include('candidate._radio', ['name'=>'is_pacer_experience','value'=>'tidak','model'=>'pacerExp','label'=>'Belum Pernah','sub'=>''])
                    </div>
                    @error('is_pacer_experience')<span class="err-msg">{{ $message }}</span>@enderror
                </div>
                <div class="cond-block" x-show="pacerExp==='pernah'" x-transition>
                    <div class="field">
                        <label class="label">Nama Event + Tahun <span class="req">*</span><span class="hint">(boleh lebih dari satu)</span></label>
                        <textarea name="pacer_event_list" rows="3" placeholder="contoh:&#10;- Balikpapan Marathon 2023&#10;- Bayan Run 2024">{{ old('pacer_event_list') }}</textarea>
                        @error('pacer_event_list')<span class="err-msg">{{ $message }}</span>@enderror
                    </div>
                    <div class="field">
                        <label class="label">Jarak & Pace Saat Bertugas <span class="req">*</span><span class="hint">(boleh lebih dari satu)</span></label>
                        <textarea name="pacer_distance_pace" rows="3" placeholder="contoh:&#10;- HM (21K) @ 6:00/km&#10;- 10K @ 5:30/km">{{ old('pacer_distance_pace') }}</textarea>
                        @error('pacer_distance_pace')<span class="err-msg">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- ════════════════════════════════════════════════
             SECTION 8 — ESSAY / PEMAHAMAN
        ════════════════════════════════════════════════ --}}
        <div class="card">
            <div class="card-head"><div class="card-num">8</div><div class="card-title">Pemahaman & Essay</div></div>
            <div class="card-body">
                <div class="field">
                    <label class="label">Pandangan Anda tentang Dunia Olahraga Lari Jarak Jauh <span class="req">*</span></label>
                    <p style="font-size:12px; color:#AAA; margin-bottom:6px; line-height:1.6;">Jelaskan secara singkat dari sudut pandang Anda dalam mengikuti perkembangan dunia olahraga, terutama lari jarak jauh yang sedang digemari banyak orang.</p>
                    <textarea name="essay_running_world" rows="5" placeholder="Tulis pandangan Anda di sini..." class="{{ $errors->has('essay_running_world')?'err':'' }}">{{ old('essay_running_world') }}</textarea>
                    @error('essay_running_world')<span class="err-msg">{{ $message }}</span>@enderror
                </div>
                <div class="field">
                    <label class="label">Apa itu Pacer / Running Buddies? <span class="req">*</span></label>
                    <p style="font-size:12px; color:#AAA; margin-bottom:6px; line-height:1.6;">Jelaskan secara singkat menurut pemahaman Anda, apa itu Pacer / Running Buddies dalam sebuah event running.</p>
                    <textarea name="essay_pacer_definition" rows="4" placeholder="Tulis penjelasan Anda di sini..." class="{{ $errors->has('essay_pacer_definition')?'err':'' }}">{{ old('essay_pacer_definition') }}</textarea>
                    @error('essay_pacer_definition')<span class="err-msg">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        {{-- ════════════════════════════════════════════════
             SECTION 9 — KOMITMEN & PREFERENSI
        ════════════════════════════════════════════════ --}}
        <div class="card">
            <div class="card-head"><div class="card-num">9</div><div class="card-title">Komitmen & Preferensi</div></div>
            <div class="card-body">

                <div class="field">
                    <label class="label">Mengapa Anda Pantas Menjadi Pace Setter di Bayan Run 2026? <span class="req">*</span></label>
                    <textarea name="alasan_pantas" rows="4" placeholder="Ceritakan alasan Anda..." class="{{ $errors->has('alasan_pantas')?'err':'' }}">{{ old('alasan_pantas') }}</textarea>
                    @error('alasan_pantas')<span class="err-msg">{{ $message }}</span>@enderror
                </div>

                <div class="field">
                    <label class="label">Jarak Favorit Saat Bertugas Jadi Pacer <span class="req">*</span><span class="hint">(boleh pilih lebih dari satu)</span></label>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                        @foreach(['hm'=>'Half Marathon (21K)','10k'=>'10 Kilometer','5k'=>'5 Kilometer','any'=>'Siap di jarak berapapun'] as $val => $lbl)
                        <label style="display:flex; align-items:center; gap:10px; background:#FAFAFA; border:1.5px solid #E8E8E8; border-radius:10px; padding:12px 14px; cursor:pointer; transition:all .18s;"
                               :style="preferDist.includes('{{ $val }}') ? 'border-color:var(--red);background:#FFF8F8;' : ''">
                            <input type="checkbox" name="preferred_distance[]" value="{{ $val }}"
                                   x-model="preferDist"
                                   {{ in_array($val, old('preferred_distance',[]))?'checked':'' }}
                                   style="display:none">
                            <div style="width:20px;height:20px;border:2px solid #DDD;border-radius:4px;flex-shrink:0;display:flex;align-items:center;justify-content:center;transition:all .18s;"
                                 :style="preferDist.includes('{{ $val }}') ? 'background:var(--red);border-color:var(--red);' : ''">
                                <svg x-show="preferDist.includes('{{ $val }}')" width="12" height="12" fill="none" stroke="white" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <span style="font-family:'Syne',sans-serif;font-size:12px;font-weight:700;">{{ $lbl }}</span>
                        </label>
                        @endforeach
                    </div>
                    @error('preferred_distance')<span class="err-msg">{{ $message }}</span>@enderror
                </div>

                <div class="field">
                    <label class="label">Komitmen Mengikuti Agenda 4 Bulan Road to Bayan Run 2026 <span class="req">*</span></label>
                    <div style="display:flex; flex-direction:column; gap:8px;">
                        @foreach(['ya_siap'=>'Ya, Saya Siap Menjalani Peran Ini','tidak_siap'=>'Tidak, Saya Tidak Siap (Ada Banyak Ketidakhadiran)','mencoba_menyesuaikan'=>'Saya Akan Mencoba Menyesuaikan dengan Agenda'] as $val => $lbl)
                        <label class="radio-opt" style="max-width:100%">
                            <input type="radio" name="komitmen" value="{{ $val }}" x-model="komitmen" {{ old('komitmen')===$val?'checked':'' }}>
                            <div class="radio-pip"></div>
                            <div><div class="radio-label">{{ $lbl }}</div></div>
                        </label>
                        @endforeach
                    </div>
                    @error('komitmen')<span class="err-msg">{{ $message }}</span>@enderror
                </div>

                <div class="field">
                    <label class="label">Izin & Dukungan Keluarga / Pasangan <span class="req">*</span></label>
                    <p style="font-size:12px;color:#AAA;margin-bottom:8px;line-height:1.6;">Saya menyatakan bahwa saya telah mendiskusikan seluruh rangkaian jadwal Road to Bayan Run 2026 (termasuk komitmen waktu di akhir pekan) dengan keluarga/pasangan/pihak terkait.</p>
                    <div class="radio-group">
                        <label class="radio-opt">
                            <input type="radio" name="izin_keluarga" value="ya" x-model="izinKeluarga" {{ old('izin_keluarga')==='ya'?'checked':'' }}>
                            <div class="radio-pip"></div>
                            <div><div class="radio-label">Ya, Sudah Mendapat Izin & Dukungan Penuh</div></div>
                        </label>
                        <label class="radio-opt">
                            <input type="radio" name="izin_keluarga" value="belum" x-model="izinKeluarga" {{ old('izin_keluarga')==='belum'?'checked':'' }}>
                            <div class="radio-pip"></div>
                            <div><div class="radio-label">Belum, Masih Dalam Tahap Diskusi</div></div>
                        </label>
                    </div>
                    @error('izin_keluarga')<span class="err-msg">{{ $message }}</span>@enderror
                </div>

            </div>
        </div>

        {{-- ════════════════════════════════════════════════
             SECTION 10 — DOKUMEN FINAL & PERNYATAAN
        ════════════════════════════════════════════════ --}}
        <div class="card">
            <div class="card-head" style="background:#7C1D1D;"><div class="card-num" style="background:#DC2626;">10</div><div class="card-title">Dokumen Final & Pernyataan</div></div>
            <div class="card-body">
                <div class="info-box warning">
                    📄 <strong>Wajib:</strong> Unduh dokumen Waiver Persetujuan Keluarga, cetak, tanda tangani di atas <strong>materai fisik</strong> (bukan e-materai), lalu upload kembali. File harus terbaca jelas.
                </div>

                <div class="field">
                    <label class="label">Upload Waiver Surat Persetujuan <span class="req">*</span><span class="hint">PDF/JPG/PNG</span></label>
                    <label for="waiver_file" class="upload" :class="files.waiver?'done':''" style="display:block">
                        <input type="file" id="waiver_file" name="waiver_file" accept=".pdf,.jpg,.jpeg,.png" style="display:none" @change="files.waiver=$event.target.files[0]?.name">
                        @include('candidate._upload_inner', ['field'=>'waiver', 'text'=>'Upload Surat Waiver Bermaterai'])
                    </label>
                    @error('waiver_file')<span class="err-msg">{{ $message }}</span>@enderror
                </div>

                {{-- Pernyataan Keabsahan --}}
                <div style="background:#FFF5F5; border:1px solid #FECACA; border-radius:12px; padding:18px 20px;">
                    <p style="font-size:13px; font-weight:600; color:#7F1D1D; line-height:1.7; margin-bottom:14px;">
                        Saya menyatakan bahwa seluruh data lari, catatan waktu (Strava/Sertifikat), dan informasi pribadi yang saya lampirkan adalah <strong>benar adanya tanpa manipulasi</strong>. Saya memahami bahwa ketidakjujuran data berakibat pada <strong>diskualifikasi permanen</strong> dari tim Pacer Bayan Run.
                    </p>
                    <label style="display:flex; align-items:flex-start; gap:12px; cursor:pointer;">
                        <div style="position:relative; flex-shrink:0; margin-top:2px;">
                            <input type="checkbox" name="pernyataan_keabsahan" value="1"
                                   x-model="pernyataan"
                                   {{ old('pernyataan_keabsahan')?'checked':'' }}
                                   style="display:none">
                            <div style="width:22px;height:22px;border:2px solid #FECACA;border-radius:6px;display:flex;align-items:center;justify-content:center;transition:all .18s;"
                                 :style="pernyataan ? 'background:#E8001E;border-color:#E8001E;' : ''">
                                <svg x-show="pernyataan" width="13" height="13" fill="none" stroke="white" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                        </div>
                        <span style="font-size:14px; font-weight:600; color:#7F1D1D; line-height:1.5;">Ya, saya menjamin keabsahan seluruh data yang saya lampirkan.</span>
                    </label>
                    @error('pernyataan_keabsahan')<span class="err-msg" style="display:block;margin-top:8px;">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        {{-- ── Submit ───────────────────────────────── --}}
        <div style="margin-top:8px;">
            <div class="disclaimer" style="margin-bottom:16px; font-size:12px; line-height:1.9;">
                <span>✓</span> Seluruh data wajib diisi dengan benar dan dapat dipertanggungjawabkan.<br>
                <span>✓</span> Data digunakan untuk keperluan seleksi dan asuransi jika lolos sebagai Pacer.<br>
                <span>✓</span> Ketidakjujuran data berakibat diskualifikasi permanen.
            </div>
            <button type="submit" class="btn-submit">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
                Kirim Pendaftaran
            </button>
        </div>

    </form>
</div>

@push('scripts')
<script>
function regForm() {
    return {
        fm:          '{{ old('is_full_marathon', '') }}',
        hm:          '{{ old('is_half_marathon', '') }}',
        r10k:        '{{ old('is_10k', '') }}',
        r5k:         '{{ old('is_5k', '') }}',
        trailStatus: '{{ old('trail_status', '') }}',
        pacerExp:    '{{ old('is_pacer_experience', '') }}',
        komitmen:    '{{ old('komitmen', '') }}',
        izinKeluarga:'{{ old('izin_keluarga', '') }}',
        preferDist:  @json(old('preferred_distance', [])),
        pernyataan:  {{ old('pernyataan_keabsahan') ? 'true' : 'false' }},
        files: {
            ktp: '', fm: '', hm: '',
            r10k: '', r5k: '', trail: '',
            mg_dec_2025:'', mg_jan_2026:'', mg_feb_2026:'', mg_mar_2026:'',
            bt_fm:'', bt_hm:'', bt_10k:'', bt_5k:'',
            waiver:'',
        }
    }
}
</script>
@endpush
@endsection