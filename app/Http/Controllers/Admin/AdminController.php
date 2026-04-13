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
        $label = match($request->status) { 'verified'=>'Diterima','rejected'=>'Ditolak', default=>'Di-reset ke Pending' };
        return back()->with('success', "Kandidat {$candidate->nama} berhasil {$label}.");
    }

    // ── FILE DOWNLOADS ────────────────────────────────────────

    private function download(Candidate $candidate, ?string $path, string $prefix): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        abort_unless($path && Storage::disk('private')->exists($path), 404, 'File tidak ditemukan.');
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        return Storage::disk('private')->download($path, "{$prefix}_{$candidate->nama}_#{$candidate->id}.{$ext}");
    }

    public function downloadKtp(Candidate $c)         { return $this->download($c, $c->ktp_file,           'KTP'); }
    public function downloadFmCert(Candidate $c)      { return $this->download($c, $c->fm_certificate,     'Sertifikat_FM'); }
    public function downloadHmCert(Candidate $c)      { return $this->download($c, $c->hm_certificate,     'Sertifikat_HM'); }
    public function download10kCert(Candidate $c)     { return $this->download($c, $c->race_10k_certificate,'Sertifikat_10K'); }
    public function download5kCert(Candidate $c)      { return $this->download($c, $c->race_5k_certificate, 'Sertifikat_5K'); }
    public function downloadTrailCert(Candidate $c)   { return $this->download($c, $c->trail_certificate,   'Sertifikat_Trail'); }
    public function downloadMileageDec(Candidate $c)  { return $this->download($c, $c->mileage_dec_graph,   'Mileage_Des2025'); }
    public function downloadMileageJan(Candidate $c)  { return $this->download($c, $c->mileage_jan_graph,   'Mileage_Jan2026'); }
    public function downloadMileageFeb(Candidate $c)  { return $this->download($c, $c->mileage_feb_graph,   'Mileage_Feb2026'); }
    public function downloadMileageMar(Candidate $c)  { return $this->download($c, $c->mileage_mar_graph,   'Mileage_Mar2026'); }
    public function downloadBtFm(Candidate $c)        { return $this->download($c, $c->best_time_fm_file,   'BestTime_FM'); }
    public function downloadBtHm(Candidate $c)        { return $this->download($c, $c->best_time_hm_file,   'BestTime_HM'); }
    public function downloadBt10k(Candidate $c)       { return $this->download($c, $c->best_time_10k_file,  'BestTime_10K'); }
    public function downloadBt5k(Candidate $c)        { return $this->download($c, $c->best_time_5k_file,   'BestTime_5K'); }
    public function downloadWaiver(Candidate $c)      { return $this->download($c, $c->waiver_file,         'Waiver'); }

    // ── EXPORT CSV ───────────────────────────────────────────

    public function exportCsv(Request $request)
    {
        $candidates = Candidate::query()
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->get();

        $fn = 'candidates_bayanrun_'.now()->format('Ymd_His').'.csv';
        $headers = ['Content-Type'=>'text/csv','Content-Disposition'=>"attachment; filename={$fn}"];

        $callback = function() use ($candidates) {
            $f = fopen('php://output','w');
            fputcsv($f, ['ID','Nama','Email','TTL','Domisili','Instagram','Strava',
                'FM','HM','10K','5K','Mileage Total (km)',
                'Best FM','Best HM','Best 10K','Best 5K',
                'Pacer Exp','Komitmen','Izin Keluarga','Status','Daftar']);
            foreach ($candidates as $c) {
                fputcsv($f, [
                    $c->id, $c->nama, $c->email,
                    $c->tanggal_lahir->format('d/m/Y'), $c->domisili,
                    $c->instagram, $c->strava,
                    $c->is_full_marathon?'Ya':'Tidak',
                    $c->is_half_marathon?'Ya':'Tidak',
                    $c->is_10k, $c->is_5k,
                    number_format($c->totalMileage(),2),
                    $c->best_time_fm??'-', $c->best_time_hm??'-',
                    $c->best_time_10k??'-', $c->best_time_5k??'-',
                    $c->is_pacer_experience?'Ya':'Tidak',
                    $c->komitmen??'-', $c->izin_keluarga??'-',
                    $c->status->label(), $c->created_at->format('d/m/Y H:i'),
                ]);
            }
            fclose($f);
        };

        return response()->stream($callback, 200, $headers);
    }
}