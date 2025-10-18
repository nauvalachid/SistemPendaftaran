<?php

namespace App\Http\Controllers\TataUsaha; // <-- Namespace baru

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest; // Anda mungkin perlu membuat LoginRequest khusus jika Admin menggunakan 'username'
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    // Tampilkan view login TU
    public function create(): View
    {
        return view('auth.login'); // <-- Arahkan ke view login TU
    }

    // Tangani permintaan otentikasi
    public function store(LoginRequest $request): RedirectResponse
    {
        // PENTING: Lakukan Authentikasi menggunakan Guard 'tata_usaha' secara manual
        $credentials = $request->only('username', 'password'); // Sesuaikan dengan field login (misalnya 'username')
        
        if (Auth::guard('tata_usaha')->attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            
            // <-- Arahkan ke dashboard TU
            return redirect()->intended(route('tu.dashboard')); 
        }

        return back()->withErrors(['username' => 'Kredensial Tata Usaha tidak cocok.']);
    }

    // Logout TU
    public function destroy(Request $request): RedirectResponse
    {
        // PENTING: Logout menggunakan Guard 'tata_usaha'
        Auth::guard('tata_usaha')->logout(); 

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route('auth.login')); // <-- Arahkan kembali ke halaman login TU
    }
}