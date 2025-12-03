<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view (User).
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Display the admin login view.
     */
    public function createAdmin(): View
    {
        return view('auth.login'); // Atau buat view terpisah: 'auth.admin-login'
    }

   /**
     * Handle an incoming authentication request (Final Version).
     */
    public function store(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        // 1. Cek Admin
        if (\App\Models\Admin::where('email', $credentials['email'])->exists()) {
            if (Auth::guard('admin')->attempt($credentials, $remember)) {
                Log::info('Admin Login Success: ' . $credentials['email']);
                $request->session()->regenerate();
                
                // Redirect Admin ke dashboard Admin
                return redirect()->intended(route('admin.dashboard')); 
            }
        }
        
        // 2. Cek User Biasa/Web
        if (Auth::guard('web')->attempt($credentials, $remember)) {
            Log::info('User Login Success: ' . $credentials['email']);
            $request->session()->regenerate();
            
            // **TINDAKAN PENTING:** Hapus intended URL yang mungkin tersisa dari Admin
            $request->session()->forget('url.intended'); 
            
            // Redirect User Biasa ke halaman utama
            return redirect()->intended('/');
        }
    }

   /**
     * Destroy an authenticated session (Multi-Guard aware & Session Cleaner).
     */
    public function destroy(Request $request): RedirectResponse
    {
        $loggedOut = false;

        // 1. Cek dan logout dari guard 'admin'
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
            Log::info('Admin Logged Out');
            $loggedOut = true;
        } 
        
        // 2. Cek dan logout dari guard 'web'
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
            Log::info('User Logged Out');
            $loggedOut = true;
        }

        if ($loggedOut) {
            // **Hapus Sisa Data Sesi Penting:**
            // Hapus kunci sesi yang menyimpan intended URL (rute tujuan setelah login)
            $request->session()->forget('url.intended'); 
            
            // Hapus kunci otentikasi untuk semua guard yang mungkin
            $request->session()->forget(Auth::guard('admin')->getName());
            $request->session()->forget(Auth::guard('web')->getName());
            
            // Invalidate dan regenerate token (seperti sebelumnya)
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return redirect('/');
    }
}