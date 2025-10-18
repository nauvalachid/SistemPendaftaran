<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PendaftaranController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminPendaftaranController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/store', [PendaftaranController::class, 'store'])->name('pendaftaran.store');
    Route::get('/create', [PendaftaranController::class, 'create'])->name('pendaftaran.create');
    Route::get('/pendaftaran/{pendaftaran}', [PendaftaranController::class, 'show'])->name('pendaftaran.show');
});

require __DIR__.'/auth.php';
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
        Route::get('/dashboard', function () {
            // Sebaiknya arahkan ke Controller: [App\Http\Controllers\Admin\DashboardController::class, 'index']
            return view('admin.dashboard');
        })->name('dashboard');
        
        // --- Rute MANAJEMEN PENDAFTARAN OLEH ADMIN ---
        
        // 1. Rute Resource: Index & Show
        Route::resource('pendaftaran', AdminPendaftaranController::class)
            ->only(['index', 'show'])
            ->names('pendaftaran');
            
        // 2. Rute Download Dokumen
        Route::get('pendaftaran/{pendaftaran}/download/{field}', [AdminPendaftaranController::class, 'download'])
            ->name('pendaftaran.download');
        
        // ----------------------------------------------------------------------
        
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