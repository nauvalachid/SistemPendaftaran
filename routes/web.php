<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\LandingPageController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\AdminPendaftaranController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminExportController;
use App\Http\Controllers\Admin\AdminKontenController;
use App\Http\Controllers\Admin\AdminPembayaranController;
use Illuminate\Http\Request;
use App\Models\Pendaftaran;

/*
|--------------------------------------------------------------------------
| Halaman Utama (Landing Page)
|--------------------------------------------------------------------------
*/
Route::get('/', [LandingPageController::class, 'index'])->name('home');
Route::get('/pendaftaran', [PendaftaranController::class, 'index'])->name('pendaftaran.index');


/*
|--------------------------------------------------------------------------
| Dashboard User
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {

    if (session()->has('pending_pendaftaran')) {
        $pendingData = session('pending_pendaftaran');
        session()->forget('pending_pendaftaran');

        if (Pendaftaran::where('id_user', Auth::id())->exists()) {
            return redirect()->route('dashboard')->with('info', 'Anda sudah pernah mendaftar sebelumnya.');
        }

        $pendaftaran = Pendaftaran::create(array_merge(
            ['id_user' => Auth::id()],
            $pendingData
        ));

        return redirect()->route('pendaftaran.show', $pendaftaran->id_pendaftaran)
            ->with('success', 'Pendaftaran Anda berhasil diselesaikan!');
    }

    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


/*
|--------------------------------------------------------------------------
| ROUTE USER (AUTH)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Pendaftaran
    Route::get('/pendaftaran/{pendaftaran}', [PendaftaranController::class, 'show'])
        ->name('pendaftaran.show');

    Route::get('/create', [PendaftaranController::class, 'create'])
        ->name('pendaftaran.create');

    Route::post('/store', [PendaftaranController::class, 'store'])
        ->name('pendaftaran.store');

    Route::get('/pendaftaran/{id}/export-pdf', [PendaftaranController::class, 'exportPdf'])
        ->name('pendaftaran.pdf');

    Route::get('/pendaftaran/{id}/preview', [PendaftaranController::class, 'previewPdf'])
        ->name('pendaftaran.previewPdf');

    Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran.index');
    Route::post('/pembayaran/submit', [PembayaranController::class, 'submit'])->name('pembayaran.submit');

});

require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| ROUTE ADMIN (Multi-Auth)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {

    // Authenticated Admin
    Route::middleware('auth:admin')->group(function () {

        // Dashboard Admin
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | CRUD Pendaftaran oleh Admin
        |--------------------------------------------------------------------------
        */
        Route::resource('pendaftaran', AdminPendaftaranController::class)
            ->only(['index', 'show'])
            ->names('pendaftaran');

        Route::get('pendaftaran/{pendaftaran}/download/{field}', [AdminPendaftaranController::class, 'download'])->name('pendaftaran.download');
        Route::post('pendaftaran/{pendaftaran}/approve', [AdminPendaftaranController::class, 'approve'])->name('pendaftaran.approve');
        Route::post('pendaftaran/{pendaftaran}/reject', [AdminPendaftaranController::class, 'reject'])->name('pendaftaran.reject');
        Route::get('/export/pendaftaran', [AdminExportController::class, 'export'])->name('export.pendaftaran');
        Route::post('/pendaftaran/{id}/status', [AdminPendaftaranController::class, 'update'])->name('pendaftaran.updateStatus');

        Route::get('/pembayaran', [AdminPembayaranController::class, 'index'])->name('pembayaran.index');

        // Rute Detail Tagihan Siswa
        Route::get('/pembayaran/detail/{tagihan}', [AdminPembayaranController::class, 'show'])->name('pembayaran.show');

        // Rute Verifikasi Pembayaran (AJAX - POST/PATCH)
        // Menggunakan ID Pembayaran (bukan ID Tagihan) karena satu tagihan bisa banyak cicilan
        Route::post('/pembayaran/verify/{id}', [AdminPembayaranController::class, 'verify'])->name('pembayaran.verify');

        // Rute Tolak Pembayaran (AJAX - POST)
        Route::post('/pembayaran/reject/{id}', [AdminPembayaranController::class, 'reject'])->name('pembayaran.reject');

        // Rute Lihat Bukti Transfer
        Route::get('/pembayaran/bukti/{pembayaran}', [AdminPembayaranController::class, 'viewBukti'])->name('pembayaran.view-bukti');

        /*
        |--------------------------------------------------------------------------
        | CRUD Konten Website (CMS)
        |--------------------------------------------------------------------------
        */
        Route::get('/konten', [AdminKontenController::class, 'index'])->name('konten.index');

        // >>>>>> INI BARIS PENTING YANG SEBELUMNYA HILANG <<<<<<
        Route::get('/konten/json/{id}', [AdminKontenController::class, 'json'])->name('konten.json');
        // >>>>>> --------------------------------------- <<<<<<

        Route::post('/konten/create', [AdminKontenController::class, 'store'])->name('konten.store');
        Route::put('/konten/{id}', [AdminKontenController::class, 'update'])->name('konten.update');
        Route::delete('/konten/{id}', [AdminKontenController::class, 'destroy'])->name('konten.destroy');

        // Rute untuk Media Tambahan
        Route::post('/konten_media', [AdminKontenController::class, 'storeMedia'])->name('konten_media.store');
        Route::delete('/konten_media/{id}', [AdminKontenController::class, 'destroyMedia'])->name('konten_media.destroy');
    });
});


/*
|--------------------------------------------------------------------------
| ROUTE TATA USAHA (TU)
|--------------------------------------------------------------------------
*/
Route::prefix('tu')->name('tu.')->group(function () {
    Route::middleware('guest:tata_usaha')->group(function () {
        Route::get('/login', [App\Http\Controllers\TataUsaha\AuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('/login', [App\Http\Controllers\TataUsaha\AuthenticatedSessionController::class, 'store']);
    });

    Route::middleware('auth:tata_usaha')->group(function () {
        Route::post('/logout', [App\Http\Controllers\TataUsaha\AuthenticatedSessionController::class, 'destroy'])->name('logout');
        Route::get('/dashboard', function () {
            return view('tu.dashboard');
        })->name('dashboard');
    });
});