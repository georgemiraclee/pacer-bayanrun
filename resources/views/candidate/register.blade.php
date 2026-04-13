@extends('layouts.app')
@section('title', 'Pendaftaran Pacer — Bayan Run 2026')

@section('content')
<div class="form-shell" x-data="regForm()">

    {{-- ── Hero ─────────────────────────────────────── --}}
    <div class="page-hero">
        <h1>Daftar Jadi <span>Pacer</span></h1>
        <p>Lengkapi data di bawah dengan benar untuk mendaftar sebagai<br>kandidat Pacer Bayan Run 2026.</p>
        <div class="steps">
            <div class="step-chip"><span class="num">1</span>Data Pribadi</div>
            <div class="step-chip"><span class="num">2</span>Full Marathon</div>
            <div class="step-chip"><span class="num">3</span>Half Marathon</div>
            <div class="step-chip"><span class="num">4</span>Submit</div>
        </div>
    </div>

    {{-- ── Error Summary ───────────────────────────── --}}
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

        {{-- ══════════════════════════════════════════
             SECTION 1 — DATA PRIBADI
        ══════════════════════════════════════════ --}}
        <div class="card" style="animation-delay:.05s">
            <div class="card-head">
                <div class="card-num">1</div>
                <div class="card-title">Data Pribadi</div>
            </div>
            <div class="card-body">

                {{-- Email --}}
                <div class="field">
                    <label class="label">Email <span class="req">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           placeholder="nama@email.com"
                           class="{{ $errors->has('email') ? 'err' : '' }}">
                    @error('email') <span class="err-msg">{{ $message }}</span> @enderror
                </div>

                {{-- Nama + Tanggal Lahir --}}
                <div class="field-row">
                    <div class="field">
                        <label class="label">Nama Lengkap <span class="req">*</span><span class="hint">(sesuai KTP)</span></label>
                        <input type="text" name="nama" value="{{ old('nama') }}"
                               placeholder="Nama Lengkap Anda"
                               class="{{ $errors->has('nama') ? 'err' : '' }}">
                        @error('nama') <span class="err-msg">{{ $message }}</span> @enderror
                    </div>
                    <div class="field">
                        <label class="label">Tanggal Lahir <span class="req">*</span></label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                               max="{{ now()->format('Y-m-d') }}"
                               class="{{ $errors->has('tanggal_lahir') ? 'err' : '' }}">
                        @error('tanggal_lahir') <span class="err-msg">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Domisili --}}
                <div class="field">
                    <label class="label">Domisili <span class="req">*</span><span class="hint">(kota/kabupaten)</span></label>
                    <input type="text" name="domisili" value="{{ old('domisili') }}"
                           placeholder="contoh: Samarinda, Kalimantan Timur"
                           class="{{ $errors->has('domisili') ? 'err' : '' }}">
                    @error('domisili') <span class="err-msg">{{ $message }}</span> @enderror
                </div>

                {{-- Alamat --}}
                <div class="field">
                    <label class="label">Alamat Domisili Lengkap <span class="req">*</span></label>
                    <textarea name="alamat" rows="3"
                              placeholder="Jl. Contoh No. 1, RT/RW, Kelurahan, Kecamatan..."
                              class="{{ $errors->has('alamat') ? 'err' : '' }}">{{ old('alamat') }}</textarea>
                    @error('alamat') <span class="err-msg">{{ $message }}</span> @enderror
                </div>

                {{-- KTP Upload --}}
                <div class="field">
                    <label class="label">Upload KTP <span class="req">*</span><span class="hint">PDF / JPG / PNG · maks 10 MB</span></label>
                    <label for="ktp_file"
                           class="upload {{ $errors->has('ktp_file') ? 'error' : '' }}"
                           :class="ktpName ? 'done' : ''"
                           style="display:block;">
                        <input type="file" name="ktp_file" id="ktp_file"
                               accept=".pdf,.jpg,.jpeg,.png"
                               style="display:none"
                               @change="ktpName = $event.target.files[0]?.name">
                        <svg class="upload-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <p x-text="ktpName ? ktpName : 'Klik untuk upload KTP'"></p>
                        <p style="font-size:11px; margin-top:4px; color:#BBB" x-show="!ktpName">atau drag & drop file di sini</p>
                    </label>
                    @error('ktp_file') <span class="err-msg">{{ $message }}</span> @enderror
                </div>

                {{-- Instagram + Strava --}}
                <div class="field-row">
                    <div class="field">
                        <label class="label">Instagram <span class="req">*</span><span class="hint">(link profil)</span></label>
                        <input type="url" name="instagram" value="{{ old('instagram') }}"
                               placeholder="https://instagram.com/username"
                               class="{{ $errors->has('instagram') ? 'err' : '' }}">
                        @error('instagram') <span class="err-msg">{{ $message }}</span> @enderror
                    </div>
                    <div class="field">
                        <label class="label">Strava <span class="req">*</span><span class="hint">(link profil)</span></label>
                        <input type="url" name="strava" value="{{ old('strava') }}"
                               placeholder="https://strava.com/athletes/..."
                               class="{{ $errors->has('strava') ? 'err' : '' }}">
                        @error('strava') <span class="err-msg">{{ $message }}</span> @enderror
                    </div>
                </div>

            </div>
        </div>

        {{-- ══════════════════════════════════════════
             SECTION 2 — FULL MARATHON
        ══════════════════════════════════════════ --}}
        <div class="card" style="animation-delay:.12s">
            <div class="card-head">
                <div class="card-num">2</div>
                <div class="card-title">Pengalaman Full Marathon</div>
            </div>
            <div class="card-body">
                <div class="field">
                    <label class="label">Pernah mengikuti Full Marathon? <span class="req">*</span></label>
                    <div class="radio-group">
                        <label class="radio-opt">
                            <input type="radio" name="is_full_marathon" value="pernah"
                                   x-model="fm"
                                   {{ old('is_full_marathon') === 'pernah' ? 'checked' : '' }}>
                            <div class="radio-pip"></div>
                            <div>
                                <div class="radio-label">Pernah</div>
                                <div class="radio-sub">Sertakan bukti sertifikat</div>
                            </div>
                        </label>
                        <label class="radio-opt">
                            <input type="radio" name="is_full_marathon" value="tidak"
                                   x-model="fm"
                                   {{ old('is_full_marathon') === 'tidak' ? 'checked' : '' }}>
                            <div class="radio-pip"></div>
                            <div>
                                <div class="radio-label">Belum Pernah</div>
                                <div class="radio-sub">Lewati bagian ini</div>
                            </div>
                        </label>
                    </div>
                    @error('is_full_marathon') <span class="err-msg">{{ $message }}</span> @enderror
                </div>

                <div class="cond-block" x-show="fm === 'pernah'" x-transition>
                    <div class="field-row">
                        <div class="field">
                            <label class="label">Nama Event <span class="req">*</span></label>
                            <input type="text" name="fm_event" value="{{ old('fm_event') }}"
                                   placeholder="Jakarta Marathon 2023"
                                   class="{{ $errors->has('fm_event') ? 'err' : '' }}">
                            @error('fm_event') <span class="err-msg">{{ $message }}</span> @enderror
                        </div>
                        <div class="field">
                            <label class="label">Tahun <span class="req">*</span></label>
                            <input type="number" name="fm_year" value="{{ old('fm_year') }}"
                                   placeholder="{{ date('Y') }}" min="2000" max="{{ date('Y') }}"
                                   class="{{ $errors->has('fm_year') ? 'err' : '' }}">
                            @error('fm_year') <span class="err-msg">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Upload Sertifikat FM <span class="req">*</span><span class="hint">JPG / PNG</span></label>
                        <label for="fm_cert" class="upload" :class="fmCert ? 'done' : ''" style="display:block;">
                            <input type="file" name="fm_certificate" id="fm_cert"
                                   accept=".jpg,.jpeg,.png" style="display:none"
                                   @change="fmCert = $event.target.files[0]?.name">
                            <svg class="upload-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p x-text="fmCert ? fmCert : 'Upload sertifikat Full Marathon'"></p>
                        </label>
                        @error('fm_certificate') <span class="err-msg">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════
             SECTION 3 — HALF MARATHON
        ══════════════════════════════════════════ --}}
        <div class="card" style="animation-delay:.18s">
            <div class="card-head">
                <div class="card-num">3</div>
                <div class="card-title">Pengalaman Half Marathon</div>
            </div>
            <div class="card-body">
                <div class="field">
                    <label class="label">Pernah mengikuti Half Marathon? <span class="req">*</span></label>
                    <div class="radio-group">
                        <label class="radio-opt">
                            <input type="radio" name="is_half_marathon" value="pernah"
                                   x-model="hm"
                                   {{ old('is_half_marathon') === 'pernah' ? 'checked' : '' }}>
                            <div class="radio-pip"></div>
                            <div>
                                <div class="radio-label">Pernah</div>
                                <div class="radio-sub">Sertakan bukti sertifikat</div>
                            </div>
                        </label>
                        <label class="radio-opt">
                            <input type="radio" name="is_half_marathon" value="tidak"
                                   x-model="hm"
                                   {{ old('is_half_marathon') === 'tidak' ? 'checked' : '' }}>
                            <div class="radio-pip"></div>
                            <div>
                                <div class="radio-label">Belum Pernah</div>
                                <div class="radio-sub">Lewati bagian ini</div>
                            </div>
                        </label>
                    </div>
                    @error('is_half_marathon') <span class="err-msg">{{ $message }}</span> @enderror
                </div>

                <div class="cond-block" x-show="hm === 'pernah'" x-transition>
                    <div class="field-row">
                        <div class="field">
                            <label class="label">Nama Event <span class="req">*</span></label>
                            <input type="text" name="hm_event" value="{{ old('hm_event') }}"
                                   placeholder="Bali Marathon 2023 (HM)"
                                   class="{{ $errors->has('hm_event') ? 'err' : '' }}">
                            @error('hm_event') <span class="err-msg">{{ $message }}</span> @enderror
                        </div>
                        <div class="field">
                            <label class="label">Tahun <span class="req">*</span></label>
                            <input type="number" name="hm_year" value="{{ old('hm_year') }}"
                                   placeholder="{{ date('Y') }}" min="2000" max="{{ date('Y') }}"
                                   class="{{ $errors->has('hm_year') ? 'err' : '' }}">
                            @error('hm_year') <span class="err-msg">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Upload Sertifikat HM <span class="req">*</span><span class="hint">JPG / PNG</span></label>
                        <label for="hm_cert" class="upload" :class="hmCert ? 'done' : ''" style="display:block;">
                            <input type="file" name="hm_certificate" id="hm_cert"
                                   accept=".jpg,.jpeg,.png" style="display:none"
                                   @change="hmCert = $event.target.files[0]?.name">
                            <svg class="upload-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p x-text="hmCert ? hmCert : 'Upload sertifikat Half Marathon'"></p>
                        </label>
                        @error('hm_certificate') <span class="err-msg">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════
             SECTION 4 — PENGALAMAN LARI (jika belum FM/HM)
        ══════════════════════════════════════════ --}}
        <div class="card" style="animation-delay:.22s" x-show="fm === 'tidak' && hm === 'tidak'" x-transition>
            <div class="card-head" style="background:#1A1A2E;">
                <div class="card-num" style="background:#3B4FE0;">4</div>
                <div class="card-title">Pengalaman Lari Lainnya</div>
            </div>
            <div class="card-body">
                <div class="info-box">
                    💡 Karena belum pernah mengikuti FM/HM, ceritakan pengalaman lari Anda
                    (10K, 5K, trail run, training rutin, dll) untuk membantu panitia menilai kelayakan Anda sebagai pacer.
                </div>
                <div class="field">
                    <label class="label">Ceritakan Pengalaman Lari Anda <span class="req">*</span></label>
                    <textarea name="pengalaman_lari" rows="5"
                              placeholder="Ceritakan event yang pernah diikuti, jarak biasa berlari, pace rata-rata, rutinitas training, dll..."
                              class="{{ $errors->has('pengalaman_lari') ? 'err' : '' }}">{{ old('pengalaman_lari') }}</textarea>
                    @error('pengalaman_lari') <span class="err-msg">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        {{-- ── Submit ──────────────────────────────── --}}
        <div style="animation-delay:.28s; animation: fadeUp .5s ease both .28s;">
            <div class="disclaimer" style="margin-bottom:16px;">
                <span>✓</span> Dengan mengirimkan formulir ini, saya menyatakan bahwa seluruh data yang diisi adalah benar dan dapat dipertanggungjawabkan.<br>
                <span>✓</span> Data akan digunakan untuk keperluan seleksi dan asuransi jika lolos sebagai Pacer Bayan Run 2026.
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
        fm: '{{ old('is_full_marathon', '') }}',
        hm: '{{ old('is_half_marathon', '') }}',
        ktpName: '',
        fmCert: '',
        hmCert: '',
    }
}
</script>
@endpush
@endsection