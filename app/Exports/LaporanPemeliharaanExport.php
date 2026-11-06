<?php

namespace App\Exports;

use App\Models\JadwalPemeliharaan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanPemeliharaanExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = JadwalPemeliharaan::with(['user', 'pemakaianOnderdil']);

        if ($this->request->filled('status')) {
            $query->where('status', $this->request->status);
        }
        if ($this->request->filled('tenaga_kerja')) {
            $query->where('nama_tenaga_kerja', $this->request->tenaga_kerja);
        }
        if ($this->request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_selesai', '>=', $this->request->tanggal_dari);
        }
        if ($this->request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_selesai', '<=', $this->request->tanggal_sampai);
        }
        if ($this->request->filled('bulan')) {
            $query->whereMonth('tanggal_selesai', $this->request->bulan);
        }
        if ($this->request->filled('tahun')) {
            $query->whereYear('tanggal_selesai', $this->request->tahun);
        }

        return $query->orderBy('tanggal_selesai', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Judul Pemeliharaan',
            'Tenaga Kerja',
            'Nama Peralatan',
            'Lokasi',
            'Quantity',
            'Serial Number',
            'Merek',
            'Tipe',
            'Tahun Pembuatan',
            'Tanggal Selesai',
            'Hasil Dikirim (ACC)',
            'Status',
            'Sparepart yang Digunakan',
            'Detail Penyelesaian',
            'Keterangan Tolak',
            'Alasan Penolakan',
        ];
    }

    public function map($item): array
    {
        static $no = 0;
        $no++;
        
        /* ============================================
         RINCIAN PEMAKAIAN MULTI-SPAREPART UNTUK EXCEL
        ============================================ */
        // Gabungkan seluruh nama sparepart beserta jumlahnya
        $rincianSparepart = $item->pemakaianOnderdil
            ->map(function ($pemakaian) {
                return $pemakaian->nama_sparepart_snapshot . ' (' . $pemakaian->jumlah . ' Unit)';
            })
            ->implode('; ');

        // Tetap mendukung laporan pemeliharaan lama
        if ($rincianSparepart === '') {
            if (!empty($item->nama_onderdil)) {
                $rincianSparepart = $item->nama_onderdil;
                if (!empty($item->jumlah_pemakaian)) {
                    $rincianSparepart .= ' (' . $item->jumlah_pemakaian . ' Unit)';
                }
            } else {
                $rincianSparepart = '-';
            }
        }

        return [
            $no,
            $item->judul_pemeliharaan,
            $item->user ? $item->user->name : '-',
            $item->nama_peralatan,
            $item->lokasi ?? '-',
            $item->quantity ?? '-',
            $item->serial_number ?? '-',
            $item->merek ?? '-',
            $item->tipe ?? '-',
            $item->tahun_pembuatan ?? '-',
            $item->tanggal_selesai ? \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y') : '-',
            ($item->status === 'selesai' && !empty($item->tanggal_kirim_hasil)) 
                ? \Carbon\Carbon::parse($item->tanggal_kirim_hasil)->format('d/m/Y') : '-',
            ucfirst($item->status ?? 'pending'),
            $rincianSparepart,
            $item->detail_penyelesaian ?? '-',
            $item->keterangan_tolak ?? '-',
            $item->alasan_penolakan ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Kolom N berisi rincian sparepart yang digunakan
        // Perlebar kolom agar nama sparepart mudah dibaca
        $sheet->getColumnDimension('N')->setWidth(50);

        // Bungkus teks panjang ke beberapa baris dalam sel
        $barisTerakhir = $sheet->getHighestRow();

        if ($barisTerakhir >= 2) {
            $sheet->getStyle('N2:N' . $barisTerakhir)->getAlignment()->setWrapText(true);
        }

        // Pertahankan format header Excel yang sudah ada
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
