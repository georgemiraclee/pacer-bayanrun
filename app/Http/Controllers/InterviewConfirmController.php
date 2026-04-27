<?php

namespace App\Http\Controllers;

use App\Models\InterviewSession;
use App\Models\InterviewConfirmation;
use Illuminate\Http\Request;

class InterviewConfirmController extends Controller
{
    // ── Tampilkan halaman konfirmasi untuk kandidat ───────────────
    public function show(string $token)
    {
        $session = InterviewSession::with('confirmation')
            ->where('token', $token)
            ->firstOrFail();

        // Daftar hari yang tersedia untuk request ganti
    $hariTersedia = InterviewSession::select('jadwal')
        ->distinct()
        ->where('jadwal', '!=', $session->jadwal)
        ->pluck('jadwal')
        ->sortBy(function($jadwal) {
            $months = [
                'Januari'=>1,'Februari'=>2,'Maret'=>3,'April'=>4,
                'Mei'=>5,'Juni'=>6,'Juli'=>7,'Agustus'=>8,
                'September'=>9,'Oktober'=>10,'November'=>11,'Desember'=>12,
            ];
            // Handle "Senin, 4 Mei 2026" dan "4 Mei 2026"
            $clean = preg_replace('/^[^,]+,\s*/', '', $jadwal); // hapus "Senin, "
            $parts = explode(' ', trim($clean));                 // ["4","Mei","2026"]
            if (count($parts) < 3) return 0;
            return mktime(0,0,0, $months[$parts[1]] ?? 0, (int)$parts[0], (int)$parts[2]);
        })
        ->values();

        return view('interview.confirm', compact('session', 'hariTersedia'));
    }

    // ── Simpan konfirmasi ─────────────────────────────────────────
    public function store(Request $request, string $token)
    {
        $session = InterviewSession::where('token', $token)->firstOrFail();

        $validated = $request->validate([
            'status'       => ['required', 'in:hadir,ganti_hari'],
            'request_hari' => ['required_if:status,ganti_hari', 'nullable', 'string', 'max:100'],
            'alasan'       => ['nullable', 'required_if:status,ganti_hari', 'string', 'max:500'],
        ], [
            'status.required'          => 'Pilih status konfirmasi.',
            'request_hari.required_if' => 'Pilih hari pengganti.',
            'alasan.required_if'       => 'Alasan wajib diisi jika request ganti hari.',
        ]);

        // Upsert — kandidat bisa ubah jawaban
        InterviewConfirmation::updateOrCreate(
            ['interview_session_id' => $session->id],
            [
                'status'       => $validated['status'],
                'request_hari' => $validated['status'] === 'ganti_hari' ? ($validated['request_hari'] ?? null) : null,
                'alasan'       => $validated['alasan'] ?? null,
                'ip_address'   => $request->ip(),
            ]
        );

        return redirect()
            ->route('interview.confirm', $token)
            ->with('success', $validated['status'] === 'hadir'
                ? 'Konfirmasi kehadiran berhasil dikirim! Kami tunggu ya Kak. 🎉'
                : 'Request ganti hari berhasil dikirim. Panitia akan segera menghubungi Anda.');
    }
}
