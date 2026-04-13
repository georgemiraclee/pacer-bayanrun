<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Http\Requests\CandidateRegistrationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CandidateController extends Controller
{
    /**
     * Tampilkan form pendaftaran
     */
    public function create()
    {
        return view('candidate.register');
    }

    /**
     * Proses submit form pendaftaran
     */
    public function store(CandidateRegistrationRequest $request)
    {
        $data = $request->validated();

        // ── Upload KTP ───────────────────────────────────────
        if ($request->hasFile('ktp_file')) {
            $data['ktp_file'] = $request->file('ktp_file')
                ->store('ktp', 'private'); // simpan di storage/app/private/ktp/
        }

        // ── Upload Sertifikat Full Marathon ──────────────────
        if ($request->hasFile('fm_certificate')) {
            $data['fm_certificate'] = $request->file('fm_certificate')
                ->store('certificates/fm', 'private');
        }

        // ── Upload Sertifikat Half Marathon ──────────────────
        if ($request->hasFile('hm_certificate')) {
            $data['hm_certificate'] = $request->file('hm_certificate')
                ->store('certificates/hm', 'private');
        }

        // ── Normalize boolean fields ─────────────────────────
        $data['is_full_marathon'] = $data['is_full_marathon'] === 'pernah';
        $data['is_half_marathon'] = $data['is_half_marathon'] === 'pernah';

        // ── Bersihkan data yang tidak relevan ─────────────────
        if (!$data['is_full_marathon']) {
            $data['fm_event']       = null;
            $data['fm_year']        = null;
            $data['fm_certificate'] = $data['fm_certificate'] ?? null;
        }

        if (!$data['is_half_marathon']) {
            $data['hm_event']       = null;
            $data['hm_year']        = null;
            $data['hm_certificate'] = $data['hm_certificate'] ?? null;
        }

        // ── Simpan ke database ───────────────────────────────
        Candidate::create($data);

        return redirect()->route('candidate.success')
            ->with('success', 'Pendaftaran berhasil! Data Anda sedang dalam proses verifikasi.');
    }

    /**
     * Halaman sukses setelah submit
     */
    public function success()
    {
        if (!session('success')) {
            return redirect()->route('candidate.register');
        }

        return view('candidate.success');
    }
}
