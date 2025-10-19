<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\User; // Tambahkan ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // Tambahkan ini
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PendaftaranController extends Controller
{
    protected array $documentFields = ['kk', 'akte', 'foto', 'ijazah_sk', 'bukti_bayar'];

    /**
     * Tampilkan formulir pendaftaran untuk publik.
     */
    public function create()
    {
        // Hapus semua pengecekan login di sini.
        // Langsung tampilkan formulir untuk semua pengunjung.
        return view('pendaftaran.create');
    }

    /**
     * Simpan pendaftaran baru DAN buat akun user baru.
     */
    public function store(Request $request)
    {
        // --- 1. Validasi Data ---
        // Kita gabungkan validasi data pendaftaran dan data user (nama, email, password)
        $validatedData = $request->validate([
            // Data Akun User Baru
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',

            // Data Siswa
            'nama_siswa' => 'required|string|max:255',
            'tempat_tgl_lahir' => 'required|string|max:255',
            'jenis_kelamin' => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
            'agama' => 'required|string|max:100',
            'asal_sekolah' => 'required|string|max:255',
            'alamat' => 'required|string',

            // Data Orang Tua (opsional)
            'nama_ayah' => 'nullable|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'pendidikan_terakhir_ayah' => 'nullable|string|max:100',
            'pendidikan_terakhir_ibu' => 'nullable|string|max:100',
            'pekerjaan_ayah' => 'nullable|string|max:100',
            'pekerjaan_ibu' => 'nullable|string|max:100',

            // Dokumen Wajib
            'kk' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'akte' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'ijazah_sk' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'bukti_bayar' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // --- 2. Buat User Baru ---
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);
        
        // --- 3. Upload Dokumen ---
        $filePaths = $this->uploadDocuments($request);

        // --- 4. Simpan Data Pendaftaran ke Database ---
        $pendaftaran = Pendaftaran::create(array_merge(
            ['id_user' => $user->id], // Gunakan ID dari user yang baru dibuat
            $this->extractDataFields($validatedData),
            $filePaths
        ));

        // --- 5. Login User yang baru dibuat ---
        Auth::login($user);

        // Arahkan ke halaman dashboard dengan pesan sukses
        return redirect()->route('dashboard')
            ->with('success', 'Pendaftaran berhasil dan akun Anda telah dibuat! Silahkan cek status pendaftaran Anda.');
    }
    
    // Fungsi show dan helper lainnya tetap sama...
    
    protected function extractDataFields(array $validatedData): array
    {
        $userFields = ['name', 'email', 'password', 'password_confirmation'];
        return collect($validatedData)->except(array_merge($this->documentFields, $userFields))->toArray();
    }

    protected function uploadDocuments(Request $request): array
    {
        $filePaths = [];
        foreach ($this->documentFields as $field) {
            if ($request->hasFile($field)) {
                $path = $request->file($field)->store('documents', 'public');
                $filePaths[$field] = $path;
            }
        }
        return $filePaths;
    }
}