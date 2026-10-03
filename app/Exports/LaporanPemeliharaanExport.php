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
        $query = JadwalPemeliharaan::with('user');

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
            'Status',
            'Nama Onderdil',
            'Detail Penyelesaian',
            'Keterangan Tolak',
            'Alasan Penolakan',
        ];
    }

    public function map($item): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $item->judul_pemeliharaan,
            $item->user ? $item->user->name : '-',
            $item->nama_peralatan,
            $item->lokasi ?? '-',
            $item->quantity,
            $item->serial_number ?? '-',
            $item->merek ?? '-',
            $item->tipe ?? '-',
            $item->tahun_pembuatan ?? '-',
            \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y'),
            ucfirst($item->status ?? 'pending'),
            $item->nama_onderdil ?? '-',
            $item->detail_penyelesaian ?? '-',
            $item->keterangan_tolak ?? '-',
            $item->alasan_penolakan ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}