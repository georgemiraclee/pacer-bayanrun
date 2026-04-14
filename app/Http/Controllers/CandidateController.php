<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Http\Requests\CandidateRegistrationRequest;
use Illuminate\Support\Facades\Storage;

class CandidateController extends Controller
{
    public function create()
    {
        return view('candidate.register');
    }

    public function store(CandidateRegistrationRequest $request)
    {
        $data = $request->validated();

        // ── Helper: simpan file ke disk private ──────────────────
    $upload = fn(string $field, string $folder) =>
        $request->hasFile($field)
            ? $request->file($field)->store($folder, 'private')
            : null;

        // ── Section 1: KTP ───────────────────────────────────────
        $data['ktp_file'] = $upload('ktp_file', 'ktp');
     // ── TAMBAHKAN INI: Konversi tanggal DD-MM-YYYY → YYYY-MM-DD ──
    if (!empty($data['tanggal_lahir'])) {
        if (preg_match('/^(\d{2})-(\d{2})-(\d{4})$/', $data['tanggal_lahir'], $m)) {
            $data['tanggal_lahir'] = "{$m[3]}-{$m[2]}-{$m[1]}";
        }
    }

        // ── Section 2 & 3: FM / HM ───────────────────────────────
        $data['is_full_marathon'] = $data['is_full_marathon'] === 'pernah';
        $data['is_half_marathon'] = $data['is_half_marathon'] === 'pernah';

        if ($data['is_full_marathon']) {
            $data['fm_certificate'] = $upload('fm_certificate', 'certs/fm');
        } else {
            $data['fm_event'] = $data['fm_year'] = $data['fm_certificate'] = null;
        }

        if ($data['is_half_marathon']) {
            $data['hm_certificate'] = $upload('hm_certificate', 'certs/hm');
        } else {
            $data['hm_event'] = $data['hm_year'] = $data['hm_certificate'] = null;
        }

        // ── Section 4: 10K ────────────────────────────────────────
        if ($data['is_10k'] === 'pernah') {
            $data['race_10k_certificate'] = $upload('race_10k_certificate', 'certs/10k');
        } else {
            $data['race_10k_event'] = $data['race_10k_year'] = $data['race_10k_certificate'] = null;
        }

        // ── Section 5: 5K ─────────────────────────────────────────
        if ($data['is_5k'] === 'pernah') {
            $data['race_5k_certificate'] = $upload('race_5k_certificate', 'certs/5k');
        } else {
            $data['race_5k_event'] = $data['race_5k_year'] = $data['race_5k_certificate'] = null;
        }

        // ── Section 6: Trail ──────────────────────────────────────
        if (($data['trail_status'] ?? '') === 'trail') {
            $data['trail_certificate'] = $upload('trail_certificate', 'certs/trail');
        } else {
            $data['trail_event'] = $data['trail_year'] = $data['trail_certificate'] = null;
        }

        // ── Section 7: Mileage Graphs ─────────────────────────────
        $data['mileage_dec_graph'] = $upload('mileage_dec_graph', 'mileage');
        $data['mileage_jan_graph'] = $upload('mileage_jan_graph', 'mileage');
        $data['mileage_feb_graph'] = $upload('mileage_feb_graph', 'mileage');
        $data['mileage_mar_graph'] = $upload('mileage_mar_graph', 'mileage');

        // ── Section 8: Best Time Files ────────────────────────────
        $data['best_time_fm_file']  = $upload('best_time_fm_file',  'besttime');
        $data['best_time_hm_file']  = $upload('best_time_hm_file',  'besttime');
        $data['best_time_10k_file'] = $upload('best_time_10k_file', 'besttime');
        $data['best_time_5k_file']  = $upload('best_time_5k_file',  'besttime');

        // ── Section 9: Pacer Experience ───────────────────────────
        $data['is_pacer_experience'] = ($data['is_pacer_experience'] ?? 'tidak') === 'pernah';
        if (!$data['is_pacer_experience']) {
            $data['pacer_event_list']    = null;
            $data['pacer_distance_pace'] = null;
        }

        // ── Section 12: Waiver ────────────────────────────────────
        $data['waiver_file']           = $upload('waiver_file', 'waiver');
        $data['pernyataan_keabsahan']  = (bool) ($data['pernyataan_keabsahan'] ?? false);

        Candidate::create($data);

        return redirect()->route('candidate.success')
            ->with('success', 'Pendaftaran berhasil! Data Anda sedang diverifikasi.');
    }

    public function success()
    {
        if (!session('success')) {
            return redirect()->route('candidate.register');
        }
        return view('candidate.success');
    }
}