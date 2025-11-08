<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PendaftaranController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminPendaftaranController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminExportController;
use Illuminate\Http\Request;
use App\Models\Pendaftaran;

Route::get('/', function () {
    return view('welcome');
});

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

    // Rute Guest (Login) untuk Admin
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [App\Http\Controllers\Admin\AuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('/login', [App\Http\Controllers\Admin\AuthenticatedSessionController::class, 'store']);
    });

    // Rute Authenticated (Dashboard & Logout) untuk Admin
    Route::middleware('auth:admin')->group(function () {
        Route::post('/logout', [App\Http\Controllers\Admin\AuthenticatedSessionController::class, 'destroy'])->name('logout');

        // Dashboard Admin
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');


        // --- Rute MANAJEMEN PENDAFTARAN OLEH ADMIN ---

        // 1. Rute Resource: Index & Show
        Route::resource('pendaftaran', AdminPendaftaranController::class)
            ->only(['index', 'show'])
            ->names('pendaftaran');

        // 2. Rute Download Dokumen
        Route::get('pendaftaran/{pendaftaran}/download/{field}', [AdminPendaftaranController::class, 'download'])
            ->name('pendaftaran.download');

        // 3. Rute Export Data Pendaftar
        Route::get('/export/pendaftaran', [AdminExportController::class, 'export'])->name('export.pendaftaran');


        // ... Tambahkan rute CRUD Admin lainnya di sini
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