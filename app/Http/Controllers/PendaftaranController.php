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
     * Tampilkan formulir pendaftaran baru (create).
     */
    public function create()
    {
        // Pastikan hanya user yang sudah login (siswa) yang bisa mengakses
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Silahkan login untuk mendaftar.');
        }

        // Cek apakah user sudah pernah mendaftar
        $existingPendaftaran = Pendaftaran::where('id_user', Auth::id())->first();

        if ($existingPendaftaran) {
             // Jika sudah ada, arahkan ke halaman detail/status pendaftaran
             return redirect()->route('pendaftaran.show', $existingPendaftaran->id)->with('info', 'Anda sudah menyelesaikan pendaftaran. Silahkan cek status Anda.');
        }

        return view('pendaftaran.create');
    }
    
    /**
     * Tampilkan detail pendaftaran (show) dan status pendaftaran.
     */
    // public function show(Pendaftaran $pendaftaran)
    // {
    //     // Kebijakan: Hanya pemilik yang boleh melihat data pendaftaran mereka sendiri
        
    //     // **LOGIKA PENTING**: Memastikan Pendaftaran yang diakses ($pendaftaran) 
    //     // dimiliki oleh user yang sedang login (Auth::id()).
    //     if (Auth::id() !== $pendaftaran->id_user) {
    //         // Jika ID user tidak cocok, user diarahkan kembali
    //         return redirect('/dashboard')->with('error', 'Anda tidak memiliki akses ke data ini.');
    //     }

    //     // Jika ID cocok, tampilkan view dengan data pendaftaran
    //     return view('pendaftaran.show', compact('pendaftaran'));
    // }

    /**
     * Tangani permintaan penyimpanan pendaftaran baru (store).
     */
    public function store(Request $request)
    {
        // Cek duplikasi pendaftaran
        if (Pendaftaran::where('id_user', Auth::id())->exists()) {
            return back()->with('error', 'Anda sudah melakukan pendaftaran sebelumnya.');
        }

        // --- 1. Validasi Data ---
        $validatedData = $this->validatePendaftaran($request, true); // true = required files

        // --- 2. Upload Dokumen ---
        $filePaths = $this->uploadDocuments($request);

        // --- 3. Simpan Data ke Database ---
        Pendaftaran::create(array_merge(
            ['id_user' => Auth::id()],
            $this->extractDataFields($validatedData),
            $filePaths
        ));

        // Setelah berhasil, arahkan ke halaman detail/status pendaftaran yang baru dibuat
        $newPendaftaran = Pendaftaran::where('id_user', Auth::id())->first();

        return redirect()->route('pendaftaran.show', $newPendaftaran->id)
            ->with('success', 'Pendaftaran berhasil dikirim! Silahkan cek status pendaftaran Anda.');
    }


    // =========================================================
    // HELPER FUNCTIONS (Hanya yang relevan untuk POST/SHOW)
    // =========================================================

    /**
     * Melakukan validasi data pendaftaran.
     */
    protected function validatePendaftaran(Request $request, bool $filesRequired = true): array
    {
        $fileRules = [
            'kk' => 'mimes:jpg,jpeg,png,pdf|max:2048',
            'akte' => 'mimes:jpg,jpeg,png,pdf|max:2048',
            'foto' => 'mimes:jpg,jpeg,png|max:2048',
            'ijazah_sk' => 'mimes:jpg,jpeg,png,pdf|max:2048',
            'bukti_bayar' => 'nullable|mimes:jpg,jpeg,png,pdf|max:2048',
        ];

        // Atur status 'required' untuk file
        foreach (['kk', 'akte', 'foto', 'ijazah_sk'] as $field) {
            if ($filesRequired) {
                $fileRules[$field] = 'required|file|' . $fileRules[$field];
            } else {
                $fileRules[$field] = 'nullable|file|' . $fileRules[$field];
            }
        }


        return $request->validate(array_merge([
            // Data Siswa
            'nama_siswa' => 'required|string|max:255',
            'tempat_tgl_lahir' => 'required|string|max:255',
            'jenis_kelamin' => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
            'agama' => 'required|string|max:100',
            'asal_sekolah' => 'required|string|max:255',
            'alamat' => 'required|string',

            // Data Orang Tua
            'nama_ayah' => 'nullable|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'pendidikan_terakhir_ayah' => 'nullable|string|max:100',
            'pendidikan_terakhir_ibu' => 'nullable|string|max:100',
            'pekerjaan_ayah' => 'nullable|string|max:100',
            'pekerjaan_ibu' => 'nullable|string|max:100',
        ], $fileRules));
    }

    /**
     * Mengambil data non-file dari hasil validasi.
     */
    protected function extractDataFields(array $validatedData): array
    {
        return collect($validatedData)->except($this->documentFields)->toArray();
    }

    /**
     * Mengupload dokumen ke storage.
     */
    protected function uploadDocuments(Request $request): array
    {
        $filePaths = [];
        foreach ($this->documentFields as $field) {
            if ($request->hasFile($field)) {
                // Simpan file ke storage 'public/documents'
                $path = $request->file($field)->store('documents', 'public');
                $filePaths[$field] = $path;
            }
        }
        return $filePaths;
    }
}
