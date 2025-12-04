<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: "Times-Roman", Times, serif !important;
            font-size: 12px;
            margin: 40px;
        }

        /* HEADER PERBAIKAN */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #000;
            margin-bottom: 15px;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: middle;
            padding: 0;
            height: 120px;
            /* Menyamakan tinggi baris */
        }

        .header-logo {
            width: 80px;
            text-align: center;
        }

        .header-logo img {
            height: 70px;
            /* Tinggi logo */
            width: 180px;
            /* Proporsional */
        }

        .header-text {
            text-align: center;
        }

        .header-text h2 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }

        .header-text p {
            margin: 2px 0;
            font-size: 12px;
        }

        /* TITLE */
        .title {
            text-align: center;
            margin: 10px 0 20px 0;
            font-size: 15px;
            font-weight: bold;
            text-decoration: underline;
        }

        /* TABEL UTAMA */
        .main-table {
            width: 100%;
            border-collapse: collapse;
        }

        .main-table td {
            border: 1px solid #000;
            padding: 6px;
        }

        .label {
            width: 35%;
            font-weight: bold;
        }

        .foto-cell {
            width: 160px;
            text-align: center;
            vertical-align: top;
        }

        .foto-box {
            border: 1px solid #000;
            width: 130px;
            height: 170px;
            margin: 5px auto;
        }

        .foto-box img {
            width: 120px;
            height: 160px;
            object-fit: cover;
        }

        /* PARAGRAF */
        .catatan,
        .pernyataan {
            margin-top: 15px;
            text-align: justify;
        }

        /* TANDA TANGAN */
        .ttd {
            margin-top: 40px;
            text-align: right;
        }
    </style>
</head>

<body>

    {{-- HEADER --}}
    <table class="header-table">
        <tr>
            <td class="header-logo">
                <img src="{{ public_path('storage/logosd.png') }}" alt="Logo">
            </td>

            <td class="header-text">
                <h2>SD Muhammadiyah 2 Ambarketawang</h2>
                <p>Kalimanjung, Ambarketawang, Kec. Gamping, Kabupaten Sleman</p>
                <p>Daerah Istimewa Yogyakarta 55294</p>
            </td>

            <!-- Kolom kanan kosong untuk keseimbangan layout -->
            <td class="header-logo"></td>
        </tr>
    </table>

    <div class="title">TANDA BUKTI PENDAFTARAN PESERTA DIDIK BARU</div>

    {{-- TABEL BIODATA --}}
    <table class="main-table">
        <tr>
            <td class="label">Nama Peserta</td>
            <td>{{ strtoupper($pendaftaran->nama_siswa) }}</td>
            <td class="foto-cell" rowspan="7">
                <div class="foto-box">
                    <img src="{{ public_path('storage/' . $pendaftaran->foto) }}" alt="Foto">
                </div>
            </td>
        </tr>
        <tr>
            <td class="label">NISN</td>
            <td>{{ $pendaftaran->nisn }}</td>
        </tr>
        <tr>
            <td class="label">Tempat, Tanggal Lahir</td>
            <td>{{ $pendaftaran->tempat_tgl_lahir }}</td>
        </tr>
        <tr>
            <td class="label">Jenis Kelamin</td>
            <td>{{ $pendaftaran->jenis_kelamin }}</td>
        </tr>
        <tr>
            <td class="label">Agama</td>
            <td>{{ $pendaftaran->agama }}</td>
        </tr>
        <tr>
            <td class="label">Asal Sekolah</td>
            <td>{{ $pendaftaran->asal_sekolah }}</td>
        </tr>
        <tr>
            <td class="label">Alamat</td>
            <td>{{ $pendaftaran->alamat }}</td>
        </tr>

        <tr>
            <td class="label">Tanggal Daftar</td>
            <td colspan="2">{{ $pendaftaran->created_at->translatedFormat('d F Y') }}</td>
        </tr>
    </table>

    {{-- CATATAN --}}
    <div class="catatan">
        <strong>Catatan:</strong>
        Bukti pendaftaran ini harus dibawa ketika proses verifikasi berkas di sekolah.
        Pastikan data yang diinput sudah benar dan sesuai identitas asli.
    </div>

    {{-- PERNYATAAN --}}
    <div class="pernyataan">
        <strong>Pernyataan:</strong>
        Dengan ini saya menyatakan bahwa seluruh data yang saya isi adalah benar.
        Saya siap menerima konsekuensi apabila ditemukan ketidaksesuaian data.
    </div>

    {{-- TTD --}}
    <div class="ttd">
        <p>Yogyakarta, {{ now()->translatedFormat('d F Y') }}</p>
        <br><br><br>
        <p>(..................................................)</p>
    </div>

</body>

</html>