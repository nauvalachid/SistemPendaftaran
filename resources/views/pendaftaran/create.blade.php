<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Formulir Pendaftaran Siswa Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 md:p-10 max-w-4xl mx-auto">

                <h1 class="text-2xl font-bold text-indigo-700 mb-2">Lengkapi Data Pendaftaran</h1>
                <p class="text-gray-600 mb-8">Isi formulir ini dengan data yang sebenar-benarnya dan lampirkan dokumen yang dibutuhkan.</p>

                @if(session('success') || session('error') || session('info') || $errors->any())
                    <div class="
                        @if(session('success')) bg-green-100 border-green-400 text-green-700
                        @elseif(session('error') || $errors->any()) bg-red-100 border-red-400 text-red-700
                        @else bg-blue-100 border-blue-400 text-blue-700
                        @endif
                        border px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">
                            @if(session('success')) Berhasil!
                            @elseif(session('error') || $errors->any()) Error!
                            @else Perhatian!
                            @endif
                        </strong>
                        <span class="block sm:inline">
                            @if(session('success')) {{ session('success') }}
                            @elseif(session('error')) {{ session('error') }}
                            @elseif(session('info')) {{ session('info') }}
                            @else Terdapat kesalahan validasi. Silakan periksa kembali formulir di langkah yang relevan.
                            @endif
                        </span>
                        @if($errors->any())
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endif

                <div class="flex justify-between items-center mb-8">
                    <div id="step-indicator-1" class="flex-1 text-center border-b-2 py-2 text-indigo-600 border-indigo-600 transition duration-300">
                        <span class="font-semibold">1. Data Siswa</span>
                    </div>
                    <div id="step-indicator-2" class="flex-1 text-center border-b-2 py-2 text-gray-400 border-gray-300 transition duration-300">
                        <span class="font-semibold">2. Data Orang Tua</span>
                    </div>
                    <div id="step-indicator-3" class="flex-1 text-center border-b-2 py-2 text-gray-400 border-gray-300 transition duration-300">
                        <span class="font-semibold">3. Dokumen</span>
                    </div>
                </div>


                <form action="{{ route('pendaftaran.store') }}" method="POST" enctype="multipart/form-data" id="registration-form">
                    @csrf

                    <div id="step-1" class="step-content">
                        <div class="mb-8 pb-4">
                            <h2 class="text-xl font-semibold text-indigo-600 mb-4">1. Data Calon Siswa</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                <div>
                                    <label for="nama_siswa" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap Siswa <span class="text-red-500">*</span></label>
                                    <input type="text" name="nama_siswa" id="nama_siswa" value="{{ old('nama_siswa') }}" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('nama_siswa') border-red-500 @enderror">
                                    @error('nama_siswa') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="tempat_tgl_lahir" class="block text-sm font-medium text-gray-700 mb-1">Tempat,Tanggal Lahir <span class="text-red-500">*</span></label>
                                    <input type="text" name="tempat_tgl_lahir" id="tempat_tgl_lahir" value="{{ old('tempat_tgl_lahir') }}" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('tempat_tgl_lahir') border-red-500 @enderror">
                                    @error('tempat_tgl_lahir') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>


                                <div>
                                    <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                                    <select name="jenis_kelamin" id="jenis_kelamin" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('jenis_kelamin') border-red-500 @enderror">
                                        <option value="">Pilih</option>
                                        <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                    @error('jenis_kelamin') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="agama" class="block text-sm font-medium text-gray-700 mb-1">Agama <span class="text-red-500">*</span></label>
                                    <input type="text" name="agama" id="agama" value="{{ old('agama') }}" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('agama') border-red-500 @enderror">
                                    @error('agama') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="asal_sekolah" class="block text-sm font-medium text-gray-700 mb-1">Asal Sekolah <span class="text-red-500">*</span></label>
                                    <input type="text" name="asal_sekolah" id="asal_sekolah" value="{{ old('asal_sekolah') }}" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('asal_sekolah') border-red-500 @enderror">
                                    @error('asal_sekolah') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap <span class="text-red-500">*</span></label>
                                    <textarea name="alamat" id="alamat" rtows="3" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('alamat') border-red-500 @enderror">{{ old('alamat') }}</textarea>
                                    @error('alamat') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="button" onclick="nextStep(2)"
                                class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500 focus:ring-opacity-50 transition duration-150 ease-in-out">
                                Lanjut ke Data Orang Tua &rarr;
                            </button>
                        </div>
                    </div>

                    <div id="step-2" class="step-content hidden">
                        <div class="mb-8 pb-4">
                            <h2 class="text-xl font-semibold text-indigo-600 mb-4">2. Data Orang Tua / Wali</h2>
                            <p class="text-sm text-gray-500 italic mb-4">*) Kosongkan jika tidak ada/tidak tahu.</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                <div>
                                    <label for="nama_ayah" class="block text-sm font-medium text-gray-700 mb-1">Nama Ayah</label>
                                    <input type="text" name="nama_ayah" id="nama_ayah" value="{{ old('nama_ayah') }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('nama_ayah') border-red-500 @enderror">
                                    @error('nama_ayah') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="nama_ibu" class="block text-sm font-medium text-gray-700 mb-1">Nama Ibu</label>
                                    <input type="text" name="nama_ibu" id="nama_ibu" value="{{ old('nama_ibu') }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('nama_ibu') border-red-500 @enderror">
                                    @error('nama_ibu') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="pendidikan_terakhir_ayah" class="block text-sm font-medium text-gray-700 mb-1">Pendidikan Terakhir Ayah</label>
                                    <input type="text" name="pendidikan_terakhir_ayah" id="pendidikan_terakhir_ayah" value="{{ old('pendidikan_terakhir_ayah') }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('pendidikan_terakhir_ayah') border-red-500 @enderror">
                                    @error('pendidikan_terakhir_ayah') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="pendidikan_terakhir_ibu" class="block text-sm font-medium text-gray-700 mb-1">Pendidikan Terakhir Ibu</label>
                                    <input type="text" name="pendidikan_terakhir_ibu" id="pendidikan_terakhir_ibu" value="{{ old('pendidikan_terakhir_ibu') }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('pendidikan_terakhir_ibu') border-red-500 @enderror">
                                    @error('pendidikan_terakhir_ibu') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="pekerjaan_ayah" class="block text-sm font-medium text-gray-700 mb-1">Pekerjaan Ayah</label>
                                    <input type="text" name="pekerjaan_ayah" id="pekerjaan_ayah" value="{{ old('pekerjaan_ayah') }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('pekerjaan_ayah') border-red-500 @enderror">
                                    @error('pekerjaan_ayah') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="pekerjaan_ibu" class="block text-sm font-medium text-gray-700 mb-1">Pekerjaan Ibu</label>
                                    <input type="text" name="pekerjaan_ibu" id="pekerjaan_ibu" value="{{ old('pekerjaan_ibu') }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('pekerjaan_ibu') border-red-500 @enderror">
                                    @error('pekerjaan_ibu') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between">
                            <button type="button" onclick="prevStep(1)"
                                class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg shadow-md hover:bg-gray-100 focus:outline-none transition duration-150 ease-in-out">
                                &larr; Sebelumnya
                            </button>
                            <button type="button" onclick="nextStep(3)"
                                class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500 focus:ring-opacity-50 transition duration-150 ease-in-out">
                                Lanjut ke Dokumen &rarr;
                            </button>
                        </div>
                    </div>

                    <div id="step-3" class="step-content hidden">
                        <div class="mb-10">
                            <h2 class="text-xl font-semibold text-indigo-600 mb-4">3. Upload Dokumen Pendukung</h2>
                            <p class="text-sm text-gray-500 italic mb-4">Jenis file yang diizinkan: JPG, JPEG, PNG, PDF. Ukuran maksimal 2MB per file. Tanda <span class="text-red-500">*</span> wajib diisi.</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                <div>
                                    <label for="kk" class="block text-sm font-medium text-gray-700 mb-1">Kartu Keluarga (KK) <span class="text-red-500">*</span></label>
                                    <input type="file" name="kk" id="kk" required
                                        class="w-full block text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer @error('kk') border-red-500 @enderror">
                                    @error('kk') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="akte" class="block text-sm font-medium text-gray-700 mb-1">Akte Kelahiran <span class="text-red-500">*</span></label>
                                    <input type="file" name="akte" id="akte" required
                                        class="w-full block text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer @error('akte') border-red-500 @enderror">
                                    @error('akte') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="foto" class="block text-sm font-medium text-gray-700 mb-1">Pas Foto (3x4 atau sejenisnya) <span class="text-red-500">*</span></label>
                                    <input type="file" name="foto" id="foto" required
                                        class="w-full block text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer @error('foto') border-red-500 @enderror">
                                    @error('foto') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="ijazah_sk" class="block text-sm font-medium text-gray-700 mb-1">Ijazah Terakhir / Surat Keterangan Lulus (SKL) <span class="text-red-500">*</span></label>
                                    <input type="file" name="ijazah_sk" id="ijazah_sk" required
                                        class="w-full block text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer @error('ijazah_sk') border-red-500 @enderror">
                                    @error('ijazah_sk') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="bukti_bayar" class="block text-sm font-medium text-gray-700 mb-1">Bukti Pembayaran Pendaftaran (Opsional)</label>
                                    <input type="file" name="bukti_bayar" id="bukti_bayar"
                                        class="w-full block text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer @error('bukti_bayar') border-red-500 @enderror">
                                    @error('bukti_bayar') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                            </div>
                        </div>

                        <div class="flex justify-between">
                            <button type="button" onclick="prevStep(2)"
                                class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg shadow-md hover:bg-gray-100 focus:outline-none transition duration-150 ease-in-out">
                                &larr; Sebelumnya
                            </button>
                            <button type="submit"
                                class="w-full md:w-auto px-6 py-3 bg-green-600 text-white font-semibold rounded-lg shadow-md hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-500 focus:ring-opacity-50 transition duration-150 ease-in-out">
                                Kirim Formulir Pendaftaran
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let currentStep = 1;
            
            // Logika untuk menampilkan langkah yang mengandung error validasi dari server
            const errorFields = document.querySelectorAll('.text-red-500.text-xs.mt-1');
            if (errorFields.length > 0) {
                // Cari elemen input yang memiliki error dan tentukan langkahnya.
                for (let i = 0; i < errorFields.length; i++) {
                    let parent = errorFields[i].closest('.step-content');
                    if (parent) {
                        if (parent.id === 'step-1') {
                            currentStep = 1;
                            break;
                        } else if (parent.id === 'step-2') {
                            currentStep = 2;
                            break;
                        } else if (parent.id === 'step-3') {
                            currentStep = 3;
                            break;
                        }
                    }
                }
            }
            
            showStep(currentStep);
        });

        /**
         * Menampilkan langkah (step) tertentu.
         * @param {number} stepNumber - Nomor langkah yang akan ditampilkan (1, 2, atau 3).
         */
        function showStep(stepNumber) {
            const steps = document.querySelectorAll('.step-content');
            steps.forEach(step => {
                step.classList.add('hidden');
            });
            
            const currentStepElement = document.getElementById('step-' + stepNumber);
            if (currentStepElement) {
                currentStepElement.classList.remove('hidden');
            }

            updateStepIndicator(stepNumber);
        }

        /**
         * Memperbarui tampilan indikator langkah.
         * @param {number} stepNumber - Nomor langkah saat ini.
         */
        function updateStepIndicator(stepNumber) {
            for (let i = 1; i <= 3; i++) {
                const indicator = document.getElementById('step-indicator-' + i);
                if (i <= stepNumber) {
                    // Langkah sudah dilalui atau langkah saat ini
                    indicator.classList.remove('text-gray-400', 'border-gray-300');
                    indicator.classList.add('text-indigo-600', 'border-indigo-600');
                } else {
                    // Langkah yang akan datang
                    indicator.classList.remove('text-indigo-600', 'border-indigo-600');
                    indicator.classList.add('text-gray-400', 'border-gray-300');
                }
            }
        }

        /**
         * Pindah ke langkah berikutnya.
         * @param {number} nextStepNumber - Nomor langkah tujuan.
         */
        function nextStep(nextStepNumber) {
            // Pindah step.
            showStep(nextStepNumber);
            // Gulir ke atas ke awal form
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        /**
         * Pindah ke langkah sebelumnya.
         * @param {number} prevStepNumber - Nomor langkah tujuan.
         */
        function prevStep(prevStepNumber) {
            showStep(prevStepNumber);
            // Gulir ke atas ke awal form
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    </script>
</x-app-layout>