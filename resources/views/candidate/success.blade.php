@extends('layouts.app')
@section('title', 'Pendaftaran Berhasil — Bayan Run 2026')

@section('content')
<div style="max-width:600px; margin:0 auto; padding:80px 24px; text-align:center;">

    {{-- Check Icon --}}
    <div style="width:80px; height:80px; background:#0D0D0D; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 32px; animation:pop .5s cubic-bezier(.175,.885,.32,1.275) both;">
        <svg width="36" height="36" fill="none" stroke="#fff" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
        </svg>
    </div>

    <style>
        @keyframes pop { from{opacity:0;transform:scale(.6)} to{opacity:1;transform:scale(1)} }
        @keyframes fadeUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
    </style>

    <h1 style="font-family:'Syne',sans-serif; font-size:clamp(28px,5vw,44px); font-weight:800; letter-spacing:-.02em; color:#0D0D0D; animation:fadeUp .5s ease .15s both;">
        Pendaftaran<br>Berhasil <span style="color:#E8001E;">🎉</span>
    </h1>
    <p style="color:#888; font-size:15px; font-weight:300; margin-top:14px; line-height:1.7; animation:fadeUp .5s ease .25s both;">
        Terima kasih telah mendaftar sebagai kandidat Pacer Bayan Run 2026.<br>
        Data Anda sedang dalam proses verifikasi oleh panitia.
    </p>

    {{-- Steps --}}
    <div style="background:#fff; border:1px solid #EBEBEB; border-radius:20px; padding:28px 32px; margin-top:36px; text-align:left; animation:fadeUp .5s ease .35s both;">
        <p style="font-family:'Syne',sans-serif; font-size:10px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:#888; margin-bottom:18px;">Langkah Selanjutnya</p>
        <div style="display:flex; flex-direction:column; gap:16px;">
            @foreach([
                ['Verifikasi Data', 'Tim panitia akan memverifikasi data dan dokumen yang Anda upload.'],
                ['Proses Review', 'Proses verifikasi memakan waktu 3–7 hari kerja setelah pendaftaran ditutup.'],
                ['Pengumuman', 'Hasil seleksi akan diinformasikan melalui email yang Anda daftarkan.'],
            ] as $i => [$title, $desc])
            <div style="display:flex; gap:14px; align-items:flex-start;">
                <div style="width:28px; height:28px; background:#E8001E; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:1px;">
                    <span style="font-family:'Syne',sans-serif; font-size:10px; font-weight:800; color:#fff;">{{ $i+1 }}</span>
                </div>
                <div>
                    <p style="font-family:'Syne',sans-serif; font-size:13px; font-weight:700; color:#0D0D0D;">{{ $title }}</p>
                    <p style="font-size:13px; color:#888; margin-top:3px; line-height:1.6;">{{ $desc }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Contact --}}
    <p style="font-size:12px; color:#BBB; margin-top:28px; animation:fadeUp .5s ease .45s both;">
        Pertanyaan? DM Instagram resmi kami
        <a href="https://instagram.com/bayanrun" style="color:#E8001E; text-decoration:none; font-weight:500;">@bayanrun</a>
    </p>

    <a href="{{ route('candidate.register') }}"
       style="display:inline-flex; align-items:center; gap:6px; margin-top:20px; font-size:12px; font-family:'Syne',sans-serif; font-weight:600; text-transform:uppercase; letter-spacing:.08em; color:#999; text-decoration:none; transition:color .2s; animation:fadeUp .5s ease .5s both;">
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali ke Halaman Pendaftaran
    </a>

</div>
@endsection