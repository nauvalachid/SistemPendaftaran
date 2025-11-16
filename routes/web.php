<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\LandingPageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminPendaftaranController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminExportController;
use App\Http\Controllers\Admin\AdminKontenController;
use Illuminate\Http\Request;
use App\Models\Pendaftaran;

Route::get('/', [LandingPageController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    // Cek apakah ada data pendaftaran yang tersimpan di session
    if (session()->has('pending_pendaftaran')) {

        $pendingData = session('pending_pendaftaran');
        session()->forget('pending_pendaftaran'); // Langsung hapus session

        // Cek lagi apakah user ini sudah pernah mendaftar
        if (Pendaftaran::where('id_user', Auth::id())->exists()) {
            // Jika ternyata sudah ada, beri info dan jangan buat data baru
            return redirect()->route('dashboard')->with('info', 'Anda sudah pernah mendaftar sebelumnya.');
        }

        // Buat data pendaftaran dari data session
        $pendaftaran = Pendaftaran::create(array_merge(
            ['id_user' => Auth::id()],
            $pendingData
        ));

        // Arahkan ke halaman detail pendaftaran dengan pesan sukses
        return redirect()->route('pendaftaran.show', $pendaftaran->id)
            ->with('success', 'Pendaftaran Anda berhasil diselesaikan!');
    }

    // Jika tidak ada data di session, tampilkan dashboard seperti biasa
    return view('dashboard');

})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Pendaftaran Routes
    Route::get('/pendaftaran/{pendaftaran}', [PendaftaranController::class, 'show'])->name('pendaftaran.show');
    Route::post('/store', [PendaftaranController::class, 'store'])->name('pendaftaran.store');
    Route::get('/create', [PendaftaranController::class, 'create'])->name('pendaftaran.create');
});

require __DIR__ . '/auth.php';
Route::prefix('admin')->name('admin.')->group(function () {

    // Rute Authenticated (Dashboard & Logout) untuk Admin
    Route::middleware('auth:admin')->group(function () {

        // Dashboard Admin
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // =============================
        // PENDAFTARAN
        // =============================
        Route::resource('pendaftaran', AdminPendaftaranController::class)
            ->only(['index', 'show'])
            ->names('pendaftaran');

        Route::get(
            'pendaftaran/{pendaftaran}/download/{field}',
            [AdminPendaftaranController::class, 'download']
        )
            ->name('pendaftaran.download');

        Route::get(
            '/export/pendaftaran',
            [AdminExportController::class, 'export']
        )
            ->name('export.pendaftaran');

        // Note: Penggunaan POST untuk update resource tidak ideal, 
        // namun dipertahankan sesuai kode yang Anda berikan.
        Route::post(
            '/pendaftaran/{id}/status',
            [AdminPendaftaranController::class, 'update']
        )
            ->name('pendaftaran.updateStatus');

        // ===================================
        // KONTEN – CRUD LENGKAP (1 Controller)
        // ===================================
        
        // Rute API untuk mengambil data tunggal (JSON)
        Route::get('/konten/json/{id}', [AdminKontenController::class, 'json'])->name('konten.json');
        
        // Halaman admin konten (Blade)
        Route::get('/konten', [AdminKontenController::class, 'index'])->name('konten.index');

        // Rute Tambahan untuk CRUD Konten Utama (Store, Update, Destroy)
        Route::post('/konten', [AdminKontenController::class, 'store'])->name('konten.store');
        Route::put('/konten/{id}', [AdminKontenController::class, 'update'])->name('konten.update');
        Route::delete('/konten/{id}', [AdminKontenController::class, 'destroy'])->name('konten.destroy');

        // Rute untuk Media Tambahan
        Route::post('/konten_media', [AdminKontenController::class, 'storeMedia'])->name('konten_media.store');
        Route::delete('/konten_media/{id}', [AdminKontenController::class, 'destroyMedia'])->name('konten_media.destroy');
    });
});



// -------------------------------------------------------------------
// --- 3. Rute TATA USAHA (TU) (Multi-Auth)
// -------------------------------------------------------------------
Route::prefix('tu')->name('tu.')->group(function () {

    // Rute Guest (Login) untuk TU
    // Menggunakan middleware 'guest:tata_usaha'
    Route::middleware('guest:tata_usaha')->group(function () {
        Route::get('/login', [App\Http\Controllers\TataUsaha\AuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('/login', [App\Http\Controllers\TataUsaha\AuthenticatedSessionController::class, 'store']);
    });

    // Rute Authenticated (Dashboard & Logout) untuk TU
    // Menggunakan middleware 'auth:tata_usaha'
    Route::middleware('auth:tata_usaha')->group(function () {
        Route::post('/logout', [App\Http\Controllers\TataUsaha\AuthenticatedSessionController::class, 'destroy'])->name('logout');

        // Dashboard TU
        Route::get('/dashboard', function () {
            return view('tu.dashboard');
        })->name('dashboard');


    });
});