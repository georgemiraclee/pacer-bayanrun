<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Middleware\AdminAuthenticate;

// ════════════════════════════════════════════════════════════
// PUBLIC ROUTES - Form Pendaftaran Kandidat
// ════════════════════════════════════════════════════════════

// Route::get('/', function () {
//     return redirect()->route('candidate.register');
// });

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('daftar')->name('candidate.')->group(function () {
    Route::get('/',       [CandidateController::class, 'create'])->name('register');
    Route::post('/',      [CandidateController::class, 'store'])->name('store');
    Route::get('/sukses', [CandidateController::class, 'success'])->name('success');
});

// ════════════════════════════════════════════════════════════
// ADMIN AUTH ROUTES
// ════════════════════════════════════════════════════════════

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login',  [AdminController::class, 'loginForm'])->name('login');
    Route::post('/login', [AdminController::class, 'login'])->name('login.post');

    // Protected admin routes
    Route::middleware(AdminAuthenticate::class)->group(function () {
        Route::post('/logout',   [AdminController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/export',    [AdminController::class, 'exportCsv'])->name('export');

        Route::prefix('kandidat/{candidate}')->name('candidate.')->group(function () {
            Route::get('/',                [AdminController::class, 'show'])->name('show');
            Route::post('/status',         [AdminController::class, 'updateStatus'])->name('status');
            Route::get('/download/ktp',    [AdminController::class, 'downloadKtp'])->name('download.ktp');
            Route::get('/download/fm-cert',[AdminController::class, 'downloadFmCert'])->name('download.fm');
            Route::get('/download/hm-cert',[AdminController::class, 'downloadHmCert'])->name('download.hm');
        });
    });
});