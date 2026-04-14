<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\KtpOcrController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Middleware\AdminAuthenticate;

// ── Public ──────────────────────────────────────────────────
// Route::get('/', fn() => redirect()->route('candidate.register'));

Route::get('/', function () {
    return view('welcome');
});

// ── OCR KTP (AJAX endpoint) ──────────────────────────────────
Route::post('/ocr/ktp', [KtpOcrController::class, 'scan'])->name('ocr.ktp');

// ── DIAGNOSTIC: cek storage (hapus setelah production) ───────
Route::get('/admin/debug-storage/{id}', function($id) {
    if (!app()->isLocal()) abort(403);
    $c = \App\Models\Candidate::findOrFail($id);
    $checks = [];
    $fields = [
        'ktp_file', 'fm_certificate', 'hm_certificate',
        'race_10k_certificate', 'race_5k_certificate', 'trail_certificate',
        'mileage_dec_graph', 'mileage_jan_graph', 'mileage_feb_graph', 'mileage_mar_graph',
        'best_time_fm_file', 'best_time_hm_file', 'best_time_10k_file', 'best_time_5k_file',
        'waiver_file',
    ];
    foreach ($fields as $f) {
        $path = $c->$f;
        if (!$path) { $checks[$f] = 'NULL (tidak ada)'; continue; }
        $diskExists = \Illuminate\Support\Facades\Storage::disk('private')->exists($path);
        $absPath    = storage_path('app/private/' . $path);
        $fileExists = file_exists($absPath);
        $checks[$f] = [
            'db_path'     => $path,
            'disk_exists' => $diskExists ? 'YA ✓' : 'TIDAK ✗',
            'file_exists' => $fileExists ? 'YA ✓' : 'TIDAK ✗',
            'abs_path'    => $absPath,
            'size'        => $fileExists ? round(filesize($absPath)/1024,1).'KB' : '—',
        ];
    }
    return response()->json([
        'kandidat'     => $c->nama,
        'storage_root' => storage_path('app/private'),
        'checks'       => $checks,
    ], 200, ['Content-Type'=>'application/json'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
})->middleware(\App\Http\Middleware\AdminAuthenticate::class);

Route::prefix('daftar')->name('candidate.')->group(function () {
    Route::get('/',       [CandidateController::class, 'create'])->name('register');
    Route::post('/',      [CandidateController::class, 'store'])->name('store');
    Route::get('/sukses', [CandidateController::class, 'success'])->name('success');
});

// ── Admin ────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login',  [AdminController::class, 'loginForm'])->name('login');
    Route::post('/login', [AdminController::class, 'login'])->name('login.post');

    Route::middleware(AdminAuthenticate::class)->group(function () {
        Route::post('/logout',   [AdminController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/export',    [AdminController::class, 'exportCsv'])->name('export');

        Route::prefix('kandidat/{candidate}')->name('candidate.')->group(function () {
            Route::get('/',        [AdminController::class, 'show'])->name('show');
            Route::post('/status', [AdminController::class, 'updateStatus'])->name('status');

            // Downloads - langsung download, tidak ada halaman preview
            Route::get('/dl/ktp',         [AdminController::class, 'downloadKtp'])->name('download.ktp');
            Route::get('/dl/fm-cert',     [AdminController::class, 'downloadFmCert'])->name('download.fm');
            Route::get('/dl/hm-cert',     [AdminController::class, 'downloadHmCert'])->name('download.hm');
            Route::get('/dl/10k-cert',    [AdminController::class, 'download10kCert'])->name('download.10k');
            Route::get('/dl/5k-cert',     [AdminController::class, 'download5kCert'])->name('download.5k');
            Route::get('/dl/trail-cert',  [AdminController::class, 'downloadTrailCert'])->name('download.trail');
            Route::get('/dl/mileage-dec', [AdminController::class, 'downloadMileageDec'])->name('download.mileage.dec');
            Route::get('/dl/mileage-jan', [AdminController::class, 'downloadMileageJan'])->name('download.mileage.jan');
            Route::get('/dl/mileage-feb', [AdminController::class, 'downloadMileageFeb'])->name('download.mileage.feb');
            Route::get('/dl/mileage-mar', [AdminController::class, 'downloadMileageMar'])->name('download.mileage.mar');
            Route::get('/dl/bt-fm',       [AdminController::class, 'downloadBtFm'])->name('download.bt.fm');
            Route::get('/dl/bt-hm',       [AdminController::class, 'downloadBtHm'])->name('download.bt.hm');
            Route::get('/dl/bt-10k',      [AdminController::class, 'downloadBt10k'])->name('download.bt.10k');
            Route::get('/dl/bt-5k',       [AdminController::class, 'downloadBt5k'])->name('download.bt.5k');
            Route::get('/dl/waiver',      [AdminController::class, 'downloadWaiver'])->name('download.waiver');
        });
    });
});