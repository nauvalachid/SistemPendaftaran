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
     * Handle an incoming authentication request (User).
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        // ===== DETEKSI ROLE OTOMATIS =====

        // 1. Cek Admin
        if (\App\Models\Admin::where('email', $credentials['email'])->exists()) {
            if (Auth::guard('admin')->attempt($credentials, $remember)) {
                Log::info('Admin Login Success: ' . $credentials['email']);
                $request->session()->regenerate();
                return redirect()->intended(route('admin.dashboard'));
            }
        }

        // // 2. Cek Tata Usaha
        // elseif (\App\Models\TataUsaha::where('email', $credentials['email'])->exists()) {
        //     if (Auth::guard('tata_usaha')->attempt($credentials, $remember)) {
        //         Log::info('Tata Usaha Login Success: ' . $credentials['email']);
        //         $request->session()->regenerate();
        //         return redirect()->intended(route('tu.dashboard'));
        //     }
        // }
        else {
            if (Auth::guard('web')->attempt($credentials, $remember)) {
                Log::info('User Login Success: ' . $credentials['email']);
                $request->session()->regenerate();
                return redirect()->intended('/');
            }
        }

        // Login gagal
        Log::warning('Login Failed for: ' . $credentials['email']);

        return back()->withInput($request->only('email', 'remember'))
            ->withErrors(['email' => 'The provided credentials do not match our records.']);
    }


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
            Log::info('Admin Logged Out');
        } else {
            Auth::guard('web')->logout();
            Log::info('User Logged Out');
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
