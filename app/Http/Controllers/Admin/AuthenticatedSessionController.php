<?php

namespace App\Http\Controllers\Admin; // PENTING: Namespace Admin

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest; // Gunakan Request default Laravel
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class AuthenticatedSessionController extends Controller
{
    /**
     * Menampilkan view login Admin.
     */
    public function create(): View
    {
        // View login admin (pastikan view ini ada)
        return view('auth.login'); 
    }

    /**
     * Menangani proses otentikasi Admin.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Default Guard: 'web'. Kita perlu menggunakan Guard 'admin'.
        $credentials = $request->only('email', 'password');

        // --- DEBUGGING LOG BARU ---
        Log::info('Admin Login Attempt:', [
            'guard' => 'admin',
            'email' => $credentials['email'],
            // JANGAN log password mentah di produksi!
            'status' => 'Trying...', 
        ]);
        // --- END DEBUGGING ---

        // PENTING: Ganti Auth::attempt() default dengan Auth::guard('admin')->attempt()
        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            
            Log::info('Admin Login Success for email: ' . $credentials['email']);

            // Otentikasi sukses
            $request->session()->regenerate();

            // Arahkan ke dashboard admin
            return redirect()->intended(route('admin.dashboard')); 
        }

        Log::warning('Admin Login Failed for email: ' . $credentials['email'] . ' (Credentials Mismatch)');
        
        // Otentikasi gagal
        return back()->withInput($request->only('email', 'remember'))
                    ->withErrors(['email' => 'These credentials do not match our records.']);
    }

    /**
     * Menangani proses logout Admin.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // PENTING: Gunakan Guard 'admin' saat logout
        Auth::guard('admin')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('auth.login');
    }
}
