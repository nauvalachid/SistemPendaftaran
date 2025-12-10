<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->ensureIsNotRateLimited();

        // Ambil input dari form (field name='username')
        $inputLogin = $request->input('username');
        $inputPassword = $request->input('password');
        $remember = $request->boolean('remember');

        // ---------------------------------------------------------
        // 1. LOGIN ADMIN (Cek kolom 'username')
        // ---------------------------------------------------------
        // Sesuai gambar tabel admin Anda
        if (Auth::guard('admin')->attempt(['username' => $inputLogin, 'password' => $inputPassword], $remember)) {
            
            RateLimiter::clear($request->throttleKey());
            $request->session()->regenerate();
            Log::info('Admin Login Success: ' . $inputLogin);

            return redirect()->route('admin.dashboard'); 
        }

        // ---------------------------------------------------------
        // 2. LOGIN USER BIASA (Cek kolom 'nama')
        // ---------------------------------------------------------
        // PERBAIKAN: Karena tabel users hanya punya 'nama' (tidak ada username/email),
        // kita mapping input ke kolom 'nama'.
        
        if (Auth::guard('web')->attempt(['username' => $inputLogin, 'password' => $inputPassword], $remember)) {
            
            RateLimiter::clear($request->throttleKey());
            $request->session()->regenerate();
            Log::info('User Login Success: ' . $inputLogin);
            
            $request->session()->forget('url.intended');

            return redirect()->intended('/');
        }

        // ---------------------------------------------------------
        // 3. JIKA GAGAL
        // ---------------------------------------------------------
        RateLimiter::hit($request->throttleKey());

        throw ValidationException::withMessages([
            'username' => trans('auth.failed'),
        ]);
    }

    public function destroy(Request $request): RedirectResponse
    {
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        }

        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $request->session()->forget('url.intended');

        return redirect('/');
    }
}