<?php

namespace App\Http\Controllers\Admin;

use App\Models\Candidate;
use App\Enums\CandidateStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str; // ← tambahkan ini di bagian use
class AdminController extends Controller
{
    // ── AUTH ──────────────────────────────────────────────────

    public function loginForm()
    {
        if (Auth::guard('admin')->check()) return redirect()->route('admin.dashboard');
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $creds = $request->validate([
            'email'    => ['required','email'],
            'password' => ['required'],
        ]);
        if (Auth::guard('admin')->attempt($creds, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }
        return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    // ── DASHBOARD ────────────────────────────────────────────

    public function dashboard(Request $request)
    {
        $query = Candidate::query()->latest();

        if ($request->filled('status'))   $query->where('status', $request->status);
        if ($request->filled('domisili')) $query->where('domisili', 'like', '%'.$request->domisili.'%');
        if ($request->filled('race')) {
            match($request->race) {
                'fm'   => $query->where('is_full_marathon', true),
                'hm'   => $query->where('is_half_marathon', true),
                '10k'  => $query->where('is_10k', 'pernah'),
                '5k'   => $query->where('is_5k', 'pernah'),
                'none' => $query->where('is_full_marathon', false)->where('is_half_marathon', false),
                default => null,
            };
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('nama','like',"%{$s}%")->orWhere('email','like',"%{$s}%"));
        }

        $candidates = $query->paginate(15)->withQueryString();
        $stats = [
            'total'    => Candidate::count(),
            'pending'  => Candidate::where('status','pending')->count(),
            'verified' => Candidate::where('status','verified')->count(),
            'rejected' => Candidate::where('status','rejected')->count(),
        ];

        return view('admin.dashboard', compact('candidates','stats'));
    }

    public function show(Candidate $candidate)
    {
        return view('admin.detail', compact('candidate'));
    }

    public function updateStatus(Request $request, Candidate $candidate)
    {
        $request->validate([
            'status'        => ['required','in:verified,rejected,pending'],
            'catatan_admin' => ['nullable','string','max:1000'],
        ]);
        $candidate->update(['status' => $request->status, 'catatan_admin' => $request->catatan_admin]);
        $label = match($request->status) {
            'verified' => 'Diterima', 'rejected' => 'Ditolak', default => 'Di-reset ke Pending'
        };
        return back()->with('success', "Kandidat {$candidate->nama} berhasil {$label}.");
    }

    private function preview(Candidate $candidate, ?string $storedPath): \Symfony\Component\HttpFoundation\Response
    {
        // 1. Cek apakah path ada di database
        if (empty($storedPath)) {
            abort(404, 'Path tidak ditemukan di database');
        }

        // 2. Cek apakah file ada di disk 'private'
        if (!Storage::disk('private')->exists($storedPath)) {
            // Debug: Jika error, cek log untuk melihat path mana yang dicari
            Log::error("File tidak ditemukan di disk private: " . $storedPath);
            abort(404, 'File fisik tidak ditemukan');
        }

        // 3. Ambil path absolut untuk dikirim sebagai response
        $absolutePath = Storage::disk('private')->path($storedPath);
        
        $ext = strtolower(pathinfo($storedPath, PATHINFO_EXTENSION));
        $mimeType = $this->getMimeType($ext);

        return response()->file($absolutePath, [
            'Content-Type'        => $mimeType,
            'Content-Disposition' => 'inline',
        ]);
    }

    private function getMimeType(string $ext): string
    {
        return match(strtolower($ext)) {
            'pdf'  => 'application/pdf',
            'jpg', 'jpeg' => 'image/jpeg',
            'png'  => 'image/png',
            default => 'application/octet-stream',
        };
    }

    // Ganti semua $c menjadi $candidate
    public function previewKtp(Candidate $candidate)        { return $this->preview($candidate, $candidate->ktp_file); }
    public function previewFmCert(Candidate $candidate)     { return $this->preview($candidate, $candidate->fm_certificate); }
    public function previewHmCert(Candidate $candidate)     { return $this->preview($candidate, $candidate->hm_certificate); }
    public function preview10kCert(Candidate $candidate)    { return $this->preview($candidate, $candidate->race_10k_certificate); }
    public function preview5kCert(Candidate $candidate)     { return $this->preview($candidate, $candidate->race_5k_certificate); }
    public function previewTrailCert(Candidate $candidate)  { return $this->preview($candidate, $candidate->trail_certificate); }
    public function previewMileageDec(Candidate $candidate) { return $this->preview($candidate, $candidate->mileage_dec_graph); }
    public function previewMileageJan(Candidate $candidate) { return $this->preview($candidate, $candidate->mileage_jan_graph); }
    public function previewMileageFeb(Candidate $candidate) { return $this->preview($candidate, $candidate->mileage_feb_graph); }
    public function previewMileageMar(Candidate $candidate) { return $this->preview($candidate, $candidate->mileage_mar_graph); }
    public function previewBtFm(Candidate $candidate)       { return $this->preview($candidate, $candidate->best_time_fm_file); }
    public function previewBtHm(Candidate $candidate)       { return $this->preview($candidate, $candidate->best_time_hm_file); }
    public function previewBt10k(Candidate $candidate)      { return $this->preview($candidate, $candidate->best_time_10k_file); }
    public function previewBt5k(Candidate $candidate)       { return $this->preview($candidate, $candidate->best_time_5k_file); }
    public function previewWaiver(Candidate $candidate)     { return $this->preview($candidate, $candidate->waiver_file); }

    // ── EXPORT CSV ───────────────────────────────────────────

    public function exportCsv(Request $request) 
    {
        $candidates = Candidate::query()
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->get();

        $fn      = 'candidates_bayanrun_'.now()->format('Ymd_His').'.csv';
        $headers = ['Content-Type'=>'text/csv','Content-Disposition'=>"attachment; filename={$fn}"];

        $callback = function() use ($candidates) {
            $f = fopen('php://output','w');
            fputcsv($f, ['ID','NIK','Nama','WhatsApp',' Tgl Lahir','Domisili','Instagram','Strava',
                'FM','HM','10K','5K','Mileage Total (km)',
                'Best FM','Best HM','Best 10K','Best 5K',
                'Pacer Exp','Komitmen','Izin Keluarga','Status','Daftar']);
            foreach ($candidates as $c) {
                fputcsv($f, [
                    $c->id, $c->nik ?? '', $c->nama, $c->email,
                    $c->no_hp ?? '',
                    $c->tanggal_lahir ?? '', $c->domisili,
                    $c->instagram, $c->strava,
                    $c->is_full_marathon ? 'Ya' : 'Tidak',
                    $c->is_half_marathon ? 'Ya' : 'Tidak',
                    $c->is_10k, $c->is_5k,
                    number_format($c->totalMileage(), 2),
                    $c->best_time_fm  ?? '-', $c->best_time_hm  ?? '-',
                    $c->best_time_10k ?? '-', $c->best_time_5k  ?? '-',
                    $c->is_pacer_experience ? 'Ya' : 'Tidak',
                    $c->komitmen      ?? '-',
                    $c->izin_keluarga ?? '-',
                    $c->status->label(),
                    $c->created_at->format('d/m/Y H:i'),
                ]);
            }
            fclose($f);
        };

        return response()->stream($callback, 200, $headers);
    }
}