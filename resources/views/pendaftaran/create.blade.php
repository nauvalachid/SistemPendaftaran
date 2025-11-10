<x-guest-layout>
    {{-- Container Utama Halaman --}}
    <div class="py-12 md:py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Judul Utama --}}
            <div class="text-center mb-10">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-800">Formulir Pendaftaran Online</h1>
                <p class="mt-2 text-md text-gray-500">SD Muhammadiyah 2 Ambarketawang - Tahun Pelajaran 2025/2026</p>
            </div>

            {{-- Notifikasi --}}
            @if(session('success') || session('error') || session('info') || $errors->any())
                <div class="border px-4 py-3 rounded-lg relative mb-8 max-w-2xl mx-auto text-sm @if(session('success')) bg-green-100 border-green-400 text-green-700 @elseif(session('error') || $errors->any()) bg-red-100 border-red-400 text-red-700 @else bg-blue-100 border-blue-400 text-blue-700 @endif"
                    role="alert">
                    <strong class="font-bold">@if(session('success')) Berhasil! @elseif(session('error') || $errors->any())
                    Error! @else Perhatian! @endif</strong>
                    <span class="block sm:inline ml-2">@if(session('success')) {{ session('success') }}
                    @elseif(session('error')) {{ session('error') }} @elseif(session('info')) {{ session('info') }}
                        @else Terdapat kesalahan validasi. @endif</span>
                    @if($errors->any())
                        <ul class="mt-2 list-disc list-inside">@foreach ($errors->all() as $error)<li>{{ $error }}</li>
                    @endforeach</ul>@endif
                </div>
            @endif

            {{-- KARTU FORMULIR --}}
            <div class="bg-white overflow-hidden rounded-2xl shadow-lg border border-gray-200/80">
                <div class="p-6 md:p-10">
                    <form action="{{ route('pendaftaran.store') }}" method="POST" enctype="multipart/form-data"
                        id="registration-form">
                        @csrf

                        {{-- ==================== STEP 1: DATA PRIBADI SISWA ==================== --}}
                        <div id="step-1" class="step-content">
                            <h2 class="text-xl font-bold text-gray-800 mb-6 border-b pb-4">Data Pribadi Siswa/i</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">

                                <div class="md:col-span-2">
                                    <label for="nama_siswa" class="block text-sm font-semibold text-gray-700 mb-1">Nama
                                        Lengkap *</label>
                                    <input type="text" name="nama_siswa" id="nama_siswa" value="{{ old('nama_siswa') }}"
                                        required class="form-input" placeholder="Masukkan nama lengkap">
                                    @error('nama_siswa') <p class="error-message">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Jenis Kelamin
                                        *</label>
                                    <div class="custom-select-container" id="jenisKelaminDropdown">
                                        <input type="hidden" name="jenis_kelamin" id="jenis_kelamin_value"
                                            value="{{ old('jenis_kelamin') }}">
                                        <div class="custom-select-trigger" tabindex="0">
                                            <span id="jenisKelaminText"
                                                class="{{ old('jenis_kelamin') ? 'text-gray-800' : 'text-gray-400' }}">
                                                {{ old('jenis_kelamin') ? old('jenis_kelamin') : 'Pilih Jenis Kelamin' }}
                                            </span>
                                            <svg class="h-5 w-5 text-gray-500 arrow" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.23 7.21a.75.75 0 011.06 0L10 10.94l3.71-3.73a.75.75 0 111.06 1.06l-4.25 4.25a.75.75 0 01-1.06 0L5.23 8.27a.75.75 0 010-1.06z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="custom-select-options hidden">
                                            <div class="custom-select-option" data-value="Laki-laki">Laki-laki</div>
                                            <div class="custom-select-option" data-value="Perempuan">Perempuan</div>
                                        </div>
                                    </div>
                                    @error('jenis_kelamin') <p class="error-message">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="tempat_lahir"
                                        class="block text-sm font-semibold text-gray-700 mb-1">Tempat Lahir *</label>
                                    <input type="text" name="tempat_lahir" id="tempat_lahir"
                                        value="{{ old('tempat_lahir') }}" required class="form-input"
                                        placeholder="Contoh: Yogyakarta">
                                    @error('tempat_lahir') <p class="error-message">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="tanggal_lahir"
                                        class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Lahir *</label>
                                    <input type="date" name="tanggal_lahir" id="tanggal_lahir"
                                        value="{{ old('tanggal_lahir') }}" required class="form-input">
                                    @error('tanggal_lahir') <p class="error-message">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="agama" class="block text-sm font-semibold text-gray-700 mb-1">Agama
                                        *</label>
                                    <input type="text" name="agama" id="agama" value="{{ old('agama', 'Islam') }}"
                                        required class="form-input">
                                    @error('agama') <p class="error-message">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="asal_sekolah"
                                        class="block text-sm font-semibold text-gray-700 mb-1">Sekolah Asal</label>
                                    <input type="text" name="asal_sekolah" id="asal_sekolah"
                                        value="{{ old('asal_sekolah') }}" class="form-input"
                                        placeholder="TK/RA asal (Opsional)">
                                    @error('asal_sekolah') <p class="error-message">{{ $message }}</p> @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="alamat" class="block text-sm font-semibold text-gray-700 mb-1">Alamat
                                        Lengkap *</label>
                                    <textarea name="alamat" id="alamat" rows="4" required class="form-input"
                                        placeholder="RT, RW, Dusun, Kelurahan/Desa, Kecamatan, Kabupaten/Kota, Provinsi.">{{ old('alamat') }}</textarea>
                                    @error('alamat') <p class="error-message">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- ==================== STEP 2: DATA ORANG TUA ==================== --}}
                        <div id="step-2" class="step-content hidden">
                            <h2 class="text-xl font-bold text-gray-800 mb-6 border-b pb-4">Data Orang Tua Siswa/i</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                                <div>
                                    <label for="nama_ayah" class="block text-sm font-semibold text-gray-700 mb-1">Nama
                                        Ayah</label>
                                    <input type="text" name="nama_ayah" id="nama_ayah" value="{{ old('nama_ayah') }}"
                                        class="form-input" placeholder="Nama lengkap Ayah">
                                    @error('nama_ayah') <p class="error-message">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="nama_ibu" class="block text-sm font-semibold text-gray-700 mb-1">Nama
                                        Ibu</label>
                                    <input type="text" name="nama_ibu" id="nama_ibu" value="{{ old('nama_ibu') }}"
                                        class="form-input" placeholder="Nama lengkap Ibu">
                                    @error('nama_ibu') <p class="error-message">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="pendidikan_terakhir_ayah"
                                        class="block text-sm font-semibold text-gray-700 mb-1">Pendidikan Terakhir
                                        Ayah</label>
                                    <input type="text" name="pendidikan_terakhir_ayah" id="pendidikan_terakhir_ayah"
                                        value="{{ old('pendidikan_terakhir_ayah') }}" class="form-input"
                                        placeholder="Contoh: S1">
                                    @error('pendidikan_terakhir_ayah') <p class="error-message">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="pendidikan_terakhir_ibu"
                                        class="block text-sm font-semibold text-gray-700 mb-1">Pendidikan Terakhir
                                        Ibu</label>
                                    <input type="text" name="pendidikan_terakhir_ibu" id="pendidikan_terakhir_ibu"
                                        value="{{ old('pendidikan_terakhir_ibu') }}" class="form-input"
                                        placeholder="Contoh: SMA">
                                    @error('pendidikan_terakhir_ibu') <p class="error-message">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="pekerjaan_ayah"
                                        class="block text-sm font-semibold text-gray-700 mb-1">Pekerjaan Ayah</label>
                                    <input type="text" name="pekerjaan_ayah" id="pekerjaan_ayah"
                                        value="{{ old('pekerjaan_ayah') }}" class="form-input"
                                        placeholder="Contoh: Karyawan Swasta">
                                    @error('pekerjaan_ayah') <p class="error-message">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="pekerjaan_ibu"
                                        class="block text-sm font-semibold text-gray-700 mb-1">Pekerjaan Ibu</label>
                                    <input type="text" name="pekerjaan_ibu" id="pekerjaan_ibu"
                                        value="{{ old('pekerjaan_ibu') }}" class="form-input"
                                        placeholder="Contoh: Ibu Rumah Tangga">
                                    @error('pekerjaan_ibu') <p class="error-message">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- ==================== STEP 3: DOKUMEN ==================== --}}
                        <div id="step-3" class="step-content hidden">
                            <h2 class="text-xl font-bold text-gray-800 mb-6 border-b pb-4">Upload Dokumen</h2>

                            <div class="mb-6">
                                <p class="font-bold text-gray-800 mb-2">Ketentuan upload dokumen :</p>
                                <ol class="list-decimal list-inside text-sm text-gray-700 space-y-1">
                                    <li>Format PDF, JPG, JPEG, PNG</li>
                                    <li>Gambar harus jelas dan terbaca,</li>
                                    <li>Ukuran maksimal 10MB per-file</li>
                                </ol>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                                <div>
                                    <label for="kk" class="block text-sm font-semibold text-gray-700 mb-1">Kartu
                                        Keluarga (KK) *</label>
                                    <input type="file" name="kk" id="kk" required class="form-file-input">
                                    @error('kk') <p class="error-message">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="akte" class="block text-sm font-semibold text-gray-700 mb-1">Akte
                                        Kelahiran *</label>
                                    <input type="file" name="akte" id="akte" required class="form-file-input">
                                    @error('akte') <p class="error-message">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="foto" class="block text-sm font-semibold text-gray-700 mb-1">Pas Foto
                                        *</label>
                                    <input type="file" name="foto" id="foto" required class="form-file-input">
                                    @error('foto') <p class="error-message">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="ijazah_sk"
                                        class="block text-sm font-semibold text-gray-700 mb-1">Ijazah/SKL Terakhir
                                        *</label>
                                    <input type="file" name="ijazah_sk" id="ijazah_sk" required class="form-file-input">
                                    @error('ijazah_sk') <p class="error-message">{{ $message }}</p> @enderror
                                </div>
                                <div class="md:col-span-2">
                                    <label for="bukti_bayar"
                                        class="block text-sm font-semibold text-gray-700 mb-1">Bukti Pembayaran
                                        (Opsional)</label>
                                    <input type="file" name="bukti_bayar" id="bukti_bayar" class="form-file-input">
                                    @error('bukti_bayar') <p class="error-message">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- NAVIGASI & STEPPER --}}
                        <div class="mt-10 pt-6 border-t flex items-center justify-between">
                            <button type="button" id="prev-btn" onclick="prevStep()"
                                class="btn-secondary hidden">&laquo; Kembali</button>
                            <div id="prev-placeholder"></div>
                            <div class="flex items-center space-x-2">
                                <div id="dot-1" class="stepper-dot active"></div>
                                <div id="dot-2" class="stepper-dot"></div>
                                <div id="dot-3" class="stepper-dot"></div>
                            </div>
                            <button type="button" id="next-btn" onclick="nextStep()" class="btn-primary">Selanjutnya
                                &raquo;</button>
                            <button type="submit" id="submit-btn" class="btn-primary hidden">Kirim Formulir</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ===================================
        // SCRIPT UNTUK MULTI-STEP FORM
        // ===================================
        let currentStep = 1;
        const totalSteps = 3;
        const prevBtn = document.getElementById('prev-btn');
        const prevPlaceholder = document.getElementById('prev-placeholder');
        const nextBtn = document.getElementById('next-btn');
        const submitBtn = document.getElementById('submit-btn');

        function showStep(stepNumber) {
            document.querySelectorAll('.step-content').forEach(step => step.classList.add('hidden'));
            document.getElementById('step-' + stepNumber).classList.remove('hidden');
            updateNavigation(stepNumber);
            updateStepper(stepNumber);
        }

        function updateNavigation(stepNumber) {
            prevBtn.classList.toggle('hidden', stepNumber === 1);
            prevPlaceholder.classList.toggle('hidden', stepNumber > 1);
            nextBtn.classList.toggle('hidden', stepNumber === totalSteps);
            submitBtn.classList.toggle('hidden', stepNumber !== totalSteps);
        }

        function updateStepper(stepNumber) {
            for (let i = 1; i <= totalSteps; i++) {
                document.getElementById('dot-' + i).classList.toggle('active', i === stepNumber);
            }
        }

        function nextStep() { if (currentStep < totalSteps) showStep(++currentStep); }
        function prevStep() { if (currentStep > 1) showStep(--currentStep); }

        // Inisialisasi awal
        document.addEventListener('DOMContentLoaded', () => {
            const errorField = document.querySelector('.error-message');
            if (errorField) {
                const parentStep = errorField.closest('.step-content');
                if (parentStep) currentStep = parseInt(parentStep.id.split('-')[1]);
            }
            showStep(currentStep);
        });

        // ===================================
        // SCRIPT UNTUK DROPDOWN KUSTOM
        // ===================================
        document.addEventListener('DOMContentLoaded', function () {
            const dropdown = document.getElementById('jenisKelaminDropdown');
            if (!dropdown) return;

            const trigger = dropdown.querySelector('.custom-select-trigger');
            const optionsContainer = dropdown.querySelector('.custom-select-options');
            const options = dropdown.querySelectorAll('.custom-select-option');
            const hiddenInput = document.getElementById('jenis_kelamin_value');
            const displayText = document.getElementById('jenisKelaminText');

            trigger.addEventListener('click', () => {
                optionsContainer.classList.toggle('hidden');
                trigger.classList.toggle('open');
            });

            trigger.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    optionsContainer.classList.toggle('hidden');
                    trigger.classList.toggle('open');
                }
            });

            options.forEach(option => {
                option.addEventListener('click', () => {
                    const selectedValue = option.getAttribute('data-value');
                    hiddenInput.value = selectedValue;
                    displayText.textContent = selectedValue;
                    displayText.className = 'text-gray-800'; // Hapus class warna placeholder
                    optionsContainer.classList.add('hidden');
                    trigger.classList.remove('open');
                });
            });

            document.addEventListener('click', (e) => {
                if (!dropdown.contains(e.target)) {
                    optionsContainer.classList.add('hidden');
                    trigger.classList.remove('open');
                }
            });
        });
    </script>
</x-guest-layout>