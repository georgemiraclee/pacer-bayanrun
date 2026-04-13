<?php

namespace App\Http\Controllers\Admin;

use App\Models\Candidate;
use App\Enums\CandidateStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    // ────────────────────────────────────────────────────────
    // AUTH
    // ────────────────────────────────────────────────────────

    /**
     * Tampilkan halaman login admin
     */
    public function loginForm()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    /**
     * Proses login admin
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    /**
     * Logout admin
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    // ────────────────────────────────────────────────────────
    // DASHBOARD
    // ────────────────────────────────────────────────────────

    /**
     * Dashboard utama - list semua kandidat dengan filter
     */
    public function dashboard(Request $request)
    {
        $query = Candidate::query()->latest();

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter domisili
        if ($request->filled('domisili')) {
            $query->where('domisili', 'like', '%' . $request->domisili . '%');
        }

        // Filter pengalaman race
        if ($request->filled('race')) {
            match ($request->race) {
                'fm'   => $query->where('is_full_marathon', true),
                'hm'   => $query->where('is_half_marathon', true),
                'none' => $query->where('is_full_marathon', false)->where('is_half_marathon', false),
                default => null,
            };
        }

        // Search by nama/email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $candidates = $query->paginate(15)->withQueryString();

        // Statistik ringkasan
        $stats = [
            'total'    => Candidate::count(),
            'pending'  => Candidate::where('status', 'pending')->count(),
            'verified' => Candidate::where('status', 'verified')->count(),
            'rejected' => Candidate::where('status', 'rejected')->count(),
        ];

        return view('admin.dashboard', compact('candidates', 'stats'));
    }

    /**
     * Detail kandidat
     */
    public function show(Candidate $candidate)
    {
        return view('admin.detail', compact('candidate'));
    }

    /**
     * Update status kandidat (approve/reject)
     */
    public function updateStatus(Request $request, Candidate $candidate)
    {
        $request->validate([
            'status'        => ['required', 'in:verified,rejected'],
            'catatan_admin' => ['nullable', 'string', 'max:1000'],
        ]);

        $candidate->update([
            'status'        => $request->status,
            'catatan_admin' => $request->catatan_admin,
        ]);

        $label = $request->status === 'verified' ? 'Diverifikasi' : 'Ditolak';

        return back()->with('success', "Kandidat {$candidate->nama} berhasil {$label}.");
    }

    /**
     * Download file KTP (hanya admin)
     */
    public function downloadKtp(Candidate $candidate)
    {
        abort_unless(
            Storage::disk('private')->exists($candidate->ktp_file),
            404,
            'File KTP tidak ditemukan.'
        );

        return Storage::disk('private')->download(
            $candidate->ktp_file,
            'KTP_' . $candidate->nama . '_' . $candidate->id . '.jpg'
        );
    }

    /**
     * Download sertifikat FM
     */
    public function downloadFmCert(Candidate $candidate)
    {
        abort_unless(
            $candidate->fm_certificate &&
            Storage::disk('private')->exists($candidate->fm_certificate),
            404,
            'File sertifikat tidak ditemukan.'
        );

        return Storage::disk('private')->download(
            $candidate->fm_certificate,
            'FM_Certificate_' . $candidate->nama . '.jpg'
        );
    }

    /**
     * Download sertifikat HM
     */
    public function downloadHmCert(Candidate $candidate)
    {
        abort_unless(
            $candidate->hm_certificate &&
            Storage::disk('private')->exists($candidate->hm_certificate),
            404,
            'File sertifikat tidak ditemukan.'
        );

        return Storage::disk('private')->download(
            $candidate->hm_certificate,
            'HM_Certificate_' . $candidate->nama . '.jpg'
        );
    }

    /**
     * Export kandidat ke CSV
     */
    public function exportCsv(Request $request)
    {
        $candidates = Candidate::query()
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->get();

        $filename = 'candidates_bayan_run_' . now()->format('Ymd_His') . '.csv';
        $headers  = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () use ($candidates) {
            $file = fopen('php://output', 'w');

            // Header CSV
            fputcsv($file, [
                'ID', 'Nama', 'Email', 'Tanggal Lahir', 'Domisili',
                'Instagram', 'Strava', 'Full Marathon', 'Half Marathon',
                'Status', 'Tanggal Daftar',
            ]);

            foreach ($candidates as $c) {
                fputcsv($file, [
                    $c->id,
                    $c->nama,
                    $c->email,
                    $c->tanggal_lahir->format('d/m/Y'),
                    $c->domisili,
                    $c->instagram,
                    $c->strava,
                    $c->is_full_marathon ? 'Ya' : 'Tidak',
                    $c->is_half_marathon ? 'Ya' : 'Tidak',
                    $c->status->label(),
                    $c->created_at->format('d/m/Y H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
