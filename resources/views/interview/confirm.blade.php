@extends('layouts.app')
@section('title', 'Konfirmasi Interview — Bayan Run 2026')

@push('styles')
<style>
/* ── Kandidat konfirmasi page — full dark background ── */
body { background: #0D0D0D !important; }

.conf-wrap {
    min-height: 100vh;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    padding: 28px 16px;
}

.conf-card {
    background: #fff;
    border-radius: 24px;
    padding: 36px 32px;
    max-width: 500px; width: 100%;
    box-shadow: 0 24px 80px rgba(0,0,0,.5);
    animation: cardIn .4s cubic-bezier(.34,1.1,.64,1) both;
}
@keyframes cardIn { from{opacity:0;transform:translateY(24px)} to{opacity:1;transform:translateY(0)} }

/* Logo */
.conf-logo-wrap { text-align:center; margin-bottom:24px; }
.conf-logo-wrap img { height:42px; object-fit:contain; }

/* Greeting */
.conf-greeting {
    font-family: 'Syne', sans-serif;
    font-size: 22px; font-weight: 800;
    color: #0D0D0D; line-height: 1.2;
    margin-bottom: 6px; text-align: center;
}
.conf-greeting span { color: #E8001E; }
.conf-sub { font-size: 13px; color: #AAAAAA; line-height: 1.65; text-align: center; margin-bottom: 22px; }

/* Info box */
.info-box {
    background: #F8F8F8; border: 1px solid #EBEBEB;
    border-radius: 14px; padding: 16px 18px; margin-bottom: 22px;
}
.info-row {
    display: flex; align-items: flex-start; gap: 10px;
    padding: 8px 0; border-bottom: 1px solid #F0F0F0;
}
.info-row:last-child { border-bottom: none; padding-bottom: 0; }
.info-key {
    font-family: 'Syne', sans-serif; font-size: 9px; font-weight: 700;
    letter-spacing: .09em; text-transform: uppercase; color: #AAAAAA;
    min-width: 72px; flex-shrink: 0; padding-top: 2px;
}
.info-val { font-size: 13px; font-weight: 500; color: #333; line-height: 1.5; }
.info-val.red { color: #E8001E; font-family: 'Syne', sans-serif; font-size: 15px; font-weight: 700; }

/* Section label */
.sec-label {
    font-family: 'Syne', sans-serif; font-size: 9px; font-weight: 700;
    letter-spacing: .1em; text-transform: uppercase; color: #AAAAAA;
    margin-bottom: 10px; display: block;
}

/* Radio options */
.radio-opt {
    display: flex; align-items: flex-start; gap: 12px;
    border: 2px solid #E4E4E4; border-radius: 14px;
    padding: 14px 16px; cursor: pointer;
    transition: all .2s; margin-bottom: 8px;
    background: #fff; width: 100%; text-align: left;
    -webkit-tap-highlight-color: transparent;
}
.radio-opt:hover { border-color: #E8001E; background: #FFF0F2; }
.radio-opt.selected { border-color: #E8001E; background: #FFF0F2; }
.radio-pip {
    width: 20px; height: 20px; border-radius: 50%;
    border: 2px solid #D0D0D0; display: flex;
    align-items: center; justify-content: center;
    flex-shrink: 0; margin-top: 1px; transition: all .15s;
}
.radio-opt.selected .radio-pip { border-color: #E8001E; background: #E8001E; }
.radio-pip-dot { width: 8px; height: 8px; border-radius: 50%; background: #fff; opacity: 0; transition: opacity .15s; }
.radio-opt.selected .radio-pip-dot { opacity: 1; }
.radio-label { font-size: 14px; font-weight: 600; color: #0D0D0D; }
.radio-sub   { font-size: 12px; color: #AAAAAA; margin-top: 2px; }

/* Ganti hari section */
.ganti-section {
    background: #F8F8F8; border: 1px solid #EBEBEB;
    border-radius: 12px; padding: 14px 16px;
    margin-top: 4px; margin-bottom: 8px;
    display: none;
}
.ganti-section.show { display: block; animation: fadeSlide .2s ease both; }
@keyframes fadeSlide { from{opacity:0;transform:translateY(-8px)} to{opacity:1;transform:translateY(0)} }

.day-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 12px; }
@media(max-width:400px){ .day-grid { grid-template-columns: 1fr; } }

.day-opt {
    border: 1.5px solid #E4E4E4; border-radius: 10px; padding: 10px 12px;
    text-align: center; cursor: pointer; transition: all .15s; background: #fff;
    font-family: 'Syne', sans-serif; font-size: 11px; font-weight: 700;
    color: #666; -webkit-tap-highlight-color: transparent;
}
.day-opt:hover  { border-color: #E8001E; color: #E8001E; background: #FFF0F2; }
.day-opt.active { border-color: #E8001E; color: #E8001E; background: #FFF0F2; }

textarea.alasan-inp {
    width: 100%; border: 1.5px solid #E4E4E4; border-radius: 10px;
    padding: 10px 13px; font-family: 'DM Sans', sans-serif;
    font-size: 13px; color: #333; resize: none; outline: none;
    transition: border-color .15s; background: #fff;
}
textarea.alasan-inp:focus { border-color: #E8001E; }
textarea.alasan-inp::placeholder { color: #CCCCCC; }

/* Send button */
.send-btn {
    width: 100%; margin-top: 18px; padding: 14px;
    background: #E8001E; color: #fff; border: none; border-radius: 12px;
    font-family: 'Syne', sans-serif; font-size: 12px; font-weight: 700;
    letter-spacing: .1em; text-transform: uppercase; cursor: pointer;
    transition: background .15s, transform .1s;
    display: flex; align-items: center; justify-content: center; gap: 8px;
}
.send-btn:hover:not(:disabled) { background: #C0001A; transform: translateY(-1px); }
.send-btn:disabled { background: #E0E0E0; cursor: not-allowed; color: #AAAAAA; }
.send-btn:active:not(:disabled) { transform: scale(.98); }

/* Already responded notice */
.resp-notice {
    background: #F0FDF4; border: 1px solid #BBF7D0;
    border-radius: 10px; padding: 12px 14px;
    font-size: 13px; color: #15803D; font-weight: 500;
    margin-bottom: 14px; display: flex; align-items: center; gap: 8px;
}

/* Success state */
.success-state { text-align: center; padding: 12px 0; }
.success-icon {
    width: 64px; height: 64px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 16px;
}
.success-icon.green { background: #DCFCE7; }
.success-icon.amber { background: #FEF3C7; }
.success-title { font-family: 'Syne', sans-serif; font-size: 18px; font-weight: 800; margin-bottom: 8px; }
.success-title.green { color: #16A34A; }
.success-title.amber { color: #D97706; }
.success-sub  { font-size: 13px; color: #AAAAAA; line-height: 1.7; }
.summary-box {
    background: #F8F8F8; border-radius: 10px; padding: 14px 16px;
    margin-top: 16px; text-align: left;
}
.summary-box .s-title { font-family:'Syne',sans-serif; font-size:9px; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:#AAAAAA; margin-bottom:6px; }
.summary-box .s-val   { font-size:13px; color:#555; line-height:1.7; }

/* Not found */
.notfound-state { text-align: center; padding: 20px 0; }

/* Validation error */
.err-msg { font-size:11px; color:#E8001E; display:block; margin-top:4px; }

/* Hidden radio */
input[type=radio].hidden-radio { position:absolute; opacity:0; width:0; height:0; }

@media(max-width:540px){
    .conf-card { padding:24px 18px; border-radius:18px; }
    .conf-greeting { font-size:19px; }
}
</style>
@endpush

@section('content')
<div class="conf-wrap">

    {{-- Logo atas --}}
    <div style="text-align:center;margin-bottom:20px;">
        <img src="https://res.cloudinary.com/djs5pi7ev/image/upload/q_auto/f_auto/v1775466723/LOGO_BR2026_vbixvo.png"
             alt="Bayan Run 2026" style="height:40px;object-fit:contain;">
    </div>

    <div class="conf-card">

        {{-- ══ SUCCESS STATE (setelah submit) ══ --}}
        @if(session('success'))
        <div class="success-state">
            @if($session->confirmation?->status === 'hadir')
            <div class="success-icon green">
                <svg width="30" height="30" fill="none" stroke="#16A34A" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="success-title green">Konfirmasi Diterima! 🎉</p>
            <p class="success-sub">Terima kasih Kak <strong>{{ $session->nama }}</strong>!<br>Kami tunggu kehadiran Anda tepat waktu ya.</p>
            <div class="summary-box">
                <div class="s-title">Ringkasan Jadwal</div>
                <div class="s-val">
                    📅 {{ $session->jadwal }}<br>
                    🕐 {{ $session->waktu }} WITA<br>
                    📍 Kantor Bayan, Jl. M.T. Haryono Komp. Balikpapan Baru D4 No.8-10
                </div>
            </div>
            @else
            <div class="success-icon amber">
                <svg width="30" height="30" fill="none" stroke="#D97706" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <p class="success-title amber">Request Diterima</p>
            <p class="success-sub">Panitia akan segera menghubungi Anda<br>untuk konfirmasi jadwal pengganti.</p>
            <div class="summary-box">
                <div class="s-title">Detail Request</div>
                <div class="s-val">
                    Ganti ke: <strong>{{ $session->confirmation?->request_hari ?? '-' }}</strong><br>
                    @if($session->confirmation?->alasan)
                    Alasan: {{ $session->confirmation->alasan }}
                    @endif
                </div>
            </div>
            @endif

            <div style="margin-top:20px;padding-top:16px;border-top:1px solid #F0F0F0;">
                <p style="font-size:11px;color:#AAAAAA;line-height:1.7;">
                    Pertanyaan? DM Instagram resmi<br>
                    <a href="https://instagram.com/bayan_open" target="_blank"
                       style="color:#E8001E;font-weight:700;text-decoration:none;">@bayan_open</a>
                </p>
            </div>
        </div>

        {{-- ══ FORM STATE ══ --}}
        @else
        {{-- Header --}}
        <div style="width:52px;height:52px;background:#FFF0F2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 18px;">
            <svg width="24" height="24" fill="none" stroke="#E8001E" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>

        <p class="conf-greeting">Halo, Kak <span>{{ $session->nama }}</span>!</p>
        <p class="conf-sub">
            Tim Rekrutmen Bayan Run 2026 mengundang Anda ke tahap <strong>Test Interview</strong>.<br>
            Mohon konfirmasi kehadiran Anda di bawah ini.
        </p>

        {{-- Info jadwal --}}
        <div class="info-box">
            <div class="info-row">
                <span class="info-key">Hari</span>
                <span class="info-val">{{ $session->jadwal }}</span>
            </div>
            <div class="info-row">
                <span class="info-key">Jam</span>
                <span class="info-val red">{{ $session->waktu }} WITA</span>
            </div>
            <div class="info-row">
                <span class="info-key">Tempat</span>
                <span class="info-val">
                    Kantor Bayan Balikpapan<br>
                    <span style="color:#AAAAAA;font-size:12px;font-weight:400;">
                        Jl. M.T. Haryono Komplek Balikpapan Baru<br>
                        Blok D4 No.8-10 (Sebrang Boyolali BB)
                    </span>
                </span>
            </div>
            <div class="info-row">
                <span class="info-key">Durasi</span>
                <span class="info-val">±{{ $session->durasi }}</span>
            </div>
        </div>

        {{-- Sudah pernah konfirmasi --}}
        @if($session->confirmation)
        <div class="resp-notice">
            <svg width="16" height="16" fill="none" stroke="#16A34A" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            Anda sudah konfirmasi sebelumnya. Isi ulang form di bawah untuk mengubah jawaban.
        </div>
        @endif

        {{-- Validation errors --}}
        @if($errors->any())
        <div style="background:#FFF0F2;border:1px solid #FFCCD2;border-radius:10px;padding:12px 14px;margin-bottom:14px;">
            @foreach($errors->all() as $e)
            <span class="err-msg" style="margin-top:0">⚠ {{ $e }}</span>
            @endforeach
        </div>
        @endif

        {{-- FORM --}}
        <form method="POST" action="{{ route('interview.confirm.store', $session->token) }}" id="confForm">
            @csrf

            <span class="sec-label">Konfirmasi Kehadiran</span>

            {{-- Opsi Hadir --}}
            <label class="radio-opt {{ old('status', $session->confirmation?->status) === 'hadir' ? 'selected' : '' }}"
                   id="optHadir" onclick="selectOpt('hadir')">
                <input type="radio" name="status" value="hadir"
                       class="hidden-radio" id="radioHadir"
                       {{ old('status', $session->confirmation?->status) === 'hadir' ? 'checked' : '' }}>
                <div class="radio-pip">
                    <div class="radio-pip-dot"></div>
                </div>
                <div>
                    <div class="radio-label">✅ Siap Hadir</div>
                    <div class="radio-sub">Saya akan hadir sesuai jadwal yang ditetapkan</div>
                </div>
            </label>

            {{-- Opsi Ganti Hari --}}
            <label class="radio-opt {{ old('status', $session->confirmation?->status) === 'ganti_hari' ? 'selected' : '' }}"
                   id="optGanti" onclick="selectOpt('ganti_hari')">
                <input type="radio" name="status" value="ganti_hari"
                       class="hidden-radio" id="radioGanti"
                       {{ old('status', $session->confirmation?->status) === 'ganti_hari' ? 'checked' : '' }}>
                <div class="radio-pip">
                    <div class="radio-pip-dot"></div>
                </div>
                <div>
                    <div class="radio-label">🔄 Request Ganti Hari</div>
                    <div class="radio-sub">Saya tidak bisa hadir di jadwal ini</div>
                </div>
            </label>

            {{-- Ganti Hari Section --}}
            <div class="ganti-section {{ old('status', $session->confirmation?->status) === 'ganti_hari' ? 'show' : '' }}" id="gantiSection">
                <p style="font-size:12px;font-weight:600;color:#666;margin-bottom:8px;">Pilih hari yang tersedia:</p>

                @if($hariTersedia->isEmpty())
                <p style="font-size:12px;color:#AAAAAA;font-style:italic;">Tidak ada hari lain yang tersedia.</p>
                @else
                <div class="day-grid">
                    @foreach($hariTersedia as $h)
                    <div class="day-opt {{ old('request_hari', $session->confirmation?->request_hari) === $h ? 'active' : '' }}"
                         onclick="selectDay('{{ $h }}', this)">
                        {{ $h }}
                    </div>
                    @endforeach
                </div>
                @endif

                <input type="hidden" name="request_hari" id="requestHariInput"
                       value="{{ old('request_hari', $session->confirmation?->request_hari) }}">
                @error('request_hari')<span class="err-msg">{{ $message }}</span>@enderror

                <textarea name="alasan" class="alasan-inp" rows="3"
                          placeholder="Alasan tidak bisa hadir di jadwal awal... (opsional)">{{ old('alasan', $session->confirmation?->alasan) }}</textarea>
                @error('alasan')<span class="err-msg">{{ $message }}</span>@enderror
            </div>

            <button type="submit" class="send-btn" id="sendBtn"
                    @if(!old('status', $session->confirmation?->status)) disabled @endif>
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
                Kirim Konfirmasi
            </button>
        </form>

        <p style="font-size:11px;color:#CCCCCC;margin-top:16px;text-align:center;line-height:1.7;">
            Pertanyaan? DM
            <a href="https://instagram.com/bayan_open" target="_blank"
               style="color:#E8001E;font-weight:600;text-decoration:none;">@bayan_open</a>
        </p>
        @endif

    </div>
</div>

@push('scripts')
<script>
var currentStatus = '{{ old('status', $session->confirmation?->status ?? '') }}';

function selectOpt(val) {
    currentStatus = val;
    document.getElementById('radioHadir').checked  = (val === 'hadir');
    document.getElementById('radioGanti').checked  = (val === 'ganti_hari');
    document.getElementById('optHadir').classList.toggle('selected', val === 'hadir');
    document.getElementById('optGanti').classList.toggle('selected', val === 'ganti_hari');
    document.getElementById('gantiSection').classList.toggle('show', val === 'ganti_hari');
    checkReady();
}

function selectDay(hari, el) {
    document.querySelectorAll('.day-opt').forEach(d => d.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('requestHariInput').value = hari;
    checkReady();
}

function checkReady() {
    var isGanti = currentStatus === 'ganti_hari';
    var dayOk   = !isGanti || document.getElementById('requestHariInput').value !== '';
    document.getElementById('sendBtn').disabled = !(currentStatus && dayOk);
}

// Init
if (currentStatus) selectOpt(currentStatus);
</script>
@endpush

@endsection
