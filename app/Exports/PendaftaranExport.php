<?php

namespace App\Exports;

use App\Models\Pendaftaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PendaftaranExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    /**
     * Mengambil semua data dari tabel pendaftaran.
     */
    public function collection()
    {
        return Pendaftaran::select(
            'id_pendaftaran',
            'id_user',
            'nama_siswa',
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
            'pekerjaan_ibu',
            'kk',
            'akte',
            'foto',
            'ijazah_sk',
            'bukti_bayar',
            'status' // ✅ tambahkan kolom status di sini
        )->get();
    }

    /**
     * Menentukan header kolom di file Excel.
     */
    public function headings(): array
    {
        return [
            'ID Pendaftaran',
            'ID User',
            'Nama Siswa',
            'Tempat & Tanggal Lahir',
            'Jenis Kelamin',
            'Agama',
            'Asal Sekolah',
            'Alamat',
            'Nama Ayah',
            'Nama Ibu',
            'Pendidikan Terakhir Ayah',
            'Pendidikan Terakhir Ibu',
            'Pekerjaan Ayah',
            'Pekerjaan Ibu',
            'KK',
            'Akte',
            'Foto',
            'Ijazah/SK',
            'Bukti Bayar',
            'Status', // ✅ header baru
        ];
    }

    /**
     * Menambahkan styling pada file Excel.
     */
    public function styles(Worksheet $sheet)
    {
        // Styling untuk header
        $sheet->getStyle('A1:T1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12,
            ],
            'fill' => [
                'fillType' => 'solid',
                'color' => ['rgb' => '0070C0'], // biru elegan
            ],
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center',
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => 'thin',
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Tambahkan border untuk seluruh data
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('A1:T' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => 'thin',
                    'color' => ['rgb' => '808080'],
                ],
            ],
        ]);

        // Freeze header agar tidak scroll
        $sheet->freezePane('A2');

        // Set tinggi baris header
        $sheet->getRowDimension(1)->setRowHeight(25);
    }
}
