<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PendaftaranController extends Controller
{
    /**
     * Array nama kolom file dokumen.
     * @var array
     */
    protected array $documentFields = ['kk', 'akte', 'foto', 'ijazah_sk', 'bukti_bayar'];

    /**
     * Menampilkan formulir pendaftaran untuk publik.
     */
    public function create()
    {
        // Langsung tampilkan formulir untuk semua pengunjung.
        return view('pendaftaran.create');
    }

    /**
     * Menangani permintaan penyimpanan pendaftaran.
     * Logika ini akan memeriksa status login pengguna.
     */
    public function store(Request $request)
    {
        // Langkah 1: Validasi semua input terlebih dahulu, siapa pun yang mengirim.
        $validatedData = $this->validatePendaftaran($request);

        // KONDISI A: Jika pengguna SUDAH LOGIN
        if (Auth::check()) {
            // Cek apakah user yang login ini sudah pernah mendaftar.
            if (Pendaftaran::where('id_user', Auth::id())->exists()) {
                return back()->with('error', 'Anda sudah melakukan pendaftaran sebelumnya.');
            }

            // Langsung proses upload file dan simpan data ke database.
            $filePaths = $this->uploadDocuments($request);
            $pendaftaran = Pendaftaran::create(array_merge(
                ['id_user' => Auth::id()], // Hubungkan dengan user yang login
                $this->extractDataFields($validatedData),
                $filePaths
            ));
            
            // Arahkan ke halaman detail pendaftaran dengan pesan sukses.
            return redirect()->route('pendaftaran.show', $pendaftaran->id)
                ->with('success', 'Pendaftaran berhasil dikirim!');
        } 
        
        // KONDISI B: Jika pengguna adalah PENGUNJUNG (BELUM LOGIN)
        else {
            // Langkah 2: Upload file terlebih dahulu dan dapatkan path-nya.
            $filePaths = $this->uploadDocuments($request);
            
            // Langkah 3: Gabungkan data teks dan path file yang sudah di-upload.
            $allData = array_merge($this->extractDataFields($validatedData), $filePaths);
            
            // Langkah 4: Simpan semua data yang sudah matang ini ke dalam session.
            session(['pending_pendaftaran' => $allData]);

            // Langkah 5: Arahkan pengunjung ke halaman login.
            return redirect()->route('login')
                ->with('info', 'Silakan login atau buat akun untuk menyelesaikan pendaftaran.');
        }
    }

    /**
     * Menampilkan detail pendaftaran yang dimiliki oleh user yang login.
     */
    public function show(Pendaftaran $pendaftaran)
    {
        // Keamanan: Hanya pemilik yang boleh melihat data pendaftaran mereka.
        if (Auth::id() !== $pendaftaran->id_user) {
            return redirect('/dashboard')->with('error', 'Anda tidak memiliki akses ke data ini.');
        }

        return view('pendaftaran.show', compact('pendaftaran'));
    }

    // =========================================================
    // HELPER FUNCTIONS
    // =========================================================

    protected function validatePendaftaran(Request $request): array
    {
        return $request->validate([
            'nama_siswa' => 'required|string|max:255',
            'tempat_tgl_lahir' => 'required|string|max:255',
            'jenis_kelamin' => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
            'agama' => 'required|string|max:100',
            'asal_sekolah' => 'required|string|max:255',
            'alamat' => 'required|string',
            'nama_ayah' => 'nullable|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'pendidikan_terakhir_ayah' => 'nullable|string|max:100',
            'pendidikan_terakhir_ibu' => 'nullable|string|max:100',
            'pekerjaan_ayah' => 'nullable|string|max:100',
            'pekerjaan_ibu' => 'nullable|string|max:100',
            'kk' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'akte' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'ijazah_sk' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'bukti_bayar' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);
    }

    protected function extractDataFields(array $validatedData): array
    {
        return collect($validatedData)->except($this->documentFields)->toArray();
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