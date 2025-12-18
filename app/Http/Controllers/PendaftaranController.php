<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;


class PendaftaranController extends Controller
{
    /**
     * Array nama kolom file dokumen.
     * @var array
     */
    protected array $documentFields = ['kk', 'akte', 'foto', 'ijazah_sk', 'bukti_bayar'];

    // app/Http/Controllers/PendaftaranController.php

    public function index(Request $request)
    {
        $pendaftaran = null; // Gunakan satu variabel konsisten untuk data yang akan dikirim ke view
        $searchTerm = $request->input('search');

        // 1. Cek apakah ada pengguna yang sedang login
        if (Auth::check()) {
            // Coba ambil data pendaftaran berdasarkan ID pengguna yang login
            $pendaftaran_user = Pendaftaran::where('id_user', Auth::id())->first();

            // Jika pengguna login dan sudah mendaftar, set data ini sebagai data default
            if ($pendaftaran_user) {
                $pendaftaran = $pendaftaran_user;
            } else {
                // Jika pengguna login tapi belum mendaftar DAN tidak sedang mencari, arahkan ke formulir
                if (!$searchTerm) {
                    return redirect()->route('pendaftaran.create');
                }
            }
        }

        // 2. Handle Pencarian (Jika ada search term, ini akan menimpa/menggantikan data yang dilihat)
        if ($searchTerm) {
            // Cari berdasarkan nama_siswa menggunakan LIKE
            $pendaftaran_search = Pendaftaran::where('nama_siswa', 'like', '%' . $searchTerm . '%')->first();

            // Timpa $pendaftaran dengan hasil pencarian jika ditemukan (untuk display hasil search)
            $pendaftaran = $pendaftaran_search;
        }

        // 3. Kirim view dengan data yang sudah dikonsolidasikan
        // Variabel yang dikirim adalah $pendaftaran, sesuai dengan yang diharapkan oleh resources/views/ppdb/status.blade.php
        return view('pendaftaran.status', [
            'pendaftaran' => $pendaftaran,
            'searchTerm' => $searchTerm
        ]);
    }

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
        // Langkah 1: Validasi semua input terlebih dahulu.
        $validatedData = $this->validatePendaftaran($request);

        // --- 1.5. Proses Penggabungan Tempat & Tanggal Lahir ---
        $tempat_lahir = $validatedData['tempat_lahir'];
        $tanggal_lahir_raw = $validatedData['tanggal_lahir'];

        // Mengubah format tanggal (dari YYYY-MM-DD menjadi DD-MM-YYYY)
        $tanggal_lahir_formatted = Carbon::parse($tanggal_lahir_raw)->format('d-m-Y');

        // Gabungkan menjadi format yang disimpan di database: "Kota, DD-MM-YYYY"
        $tempat_tgl_lahir_final = $tempat_lahir . ', ' . $tanggal_lahir_formatted;

        // Inject nilai gabungan ke $validatedData
        $validatedData['tempat_tgl_lahir'] = $tempat_tgl_lahir_final;

        // Hapus field input mentah
        unset($validatedData['tempat_lahir']);
        unset($validatedData['tanggal_lahir']);
        // --------------------------------------------------------

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
            return redirect()->route('pendaftaran.index')
                ->with('success', 'Pendaftaran berhasil dikirim! Silahkan cek status pendaftaran Anda.');
        }

        // KONDISI B: Jika pengguna adalah PENGUNJUNG (BELUM LOGIN)
        else {
            // Langkah 2: Upload file terlebih dahulu dan dapatkan path-nya.
            // Catatan: Pastikan logika uploadDocuments Anda menyimpan file secara sementara atau permanen.
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
        // Memodifikasi helper untuk memvalidasi tempat_lahir dan tanggal_lahir secara terpisah.
        return $request->validate([
            // Data Siswa
            'nama_siswa' => 'required|string|max:255',
            'nisn' => 'nullable|string|max:10', // <--- SUDAH DITAMBAH DI SINI
            'no_telp' => 'required|string|max:15', // <--- DIUBAH MENJADI 'required' DAN DITAMBAH DI SINI

            // Kolom input terpisah
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',

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
    }

    /**
     * Helper untuk mengunggah dokumen menggunakan nama file asli + timestamp.
     * @param Request $request
     * @return array
     */
    protected function uploadDocuments(Request $request): array
    {
        $filePaths = [];
        foreach ($this->documentFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);

                // Mengambil nama asli file dari client
                $originalName = $file->getClientOriginalName();

                // Membuat nama file baru: timestamp_nama_asli.ekstensi
                // Ini membantu mencegah bentrokan nama file, tapi tetap mempertahankan nama asli.
                $fileNameToStore = time() . '_' . $originalName;

                // Menyimpan file menggunakan storeAs
                $path = $file->storeAs('documents', $fileNameToStore, 'public');
                $filePaths[$field] = $path;
            }
        }
        return $filePaths;
    }

    /**
     * Helper untuk mengambil hanya field yang relevan untuk model Pendaftaran.
     * @param array $data
     * @return array
     */
    protected function extractDataFields(array $data): array
    {
        // Daftar kolom yang ada di tabel Pendaftaran (kecuali id_user dan timestamps)
        $pendaftaranFields = [
            'nama_siswa',
            'nisn', // <--- SUDAH DITAMBAH DI SINI
            'no_telp', // <--- SUDAH DITAMBAH DI SINI
            'tempat_tgl_lahir',
            'jenis_kelamin',
            'agama',
            'asal_sekolah',
            'alamat',
            'nama_ayah',
            'nama_ibu',
            'pendidikan_terakhir_ayah',
            'pendidikan_terakhir_ibu',
            'pekerjaan_ayah',
            'pekerjaan_ibu'
        ];

        // Filter data yang divalidasi
        return array_filter(
            $data,
            fn($key) => in_array($key, $pendaftaranFields),
            ARRAY_FILTER_USE_KEY
        );
    }

    public function exportPdf($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);

        // Keamanan: hanya pemilik data yang boleh mengunduh PDF
        if (Auth::id() !== $pendaftaran->id_user) {
            return redirect('/dashboard')->with('error', 'Anda tidak memiliki akses ke data ini.');
        }

        $pdf = PDF::loadView('pendaftaran.bukti-pdf', compact('pendaftaran'))
            ->setPaper('A4', 'portrait');

        return $pdf->download('bukti-pendaftaran-' . $pendaftaran->nama_siswa . '.pdf');
    }

    public function previewPdf($id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);

        return view('pendaftaran.bukti-pdf', compact('pendaftaran'));
    }

}