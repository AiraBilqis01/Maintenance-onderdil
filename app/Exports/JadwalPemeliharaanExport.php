<?php

namespace App\Exports;

use App\Models\JadwalPemeliharaan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class JadwalPemeliharaanExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = JadwalPemeliharaan::withTrashed()->with(['user', 'pemakaianOnderdil']);

        /* Filter Tenaga Kerja */
        if ($this->request->filled('tenaga_kerja')) {
            $query->where('nama_tenaga_kerja', $this->request->tenaga_kerja);
        }

        /* Filter Dari Tanggal */
        if ($this->request->filled('tanggal_dari')) {
            $query->whereDate( 'tanggal_selesai', '>=', $this->request->tanggal_dari);
        }

        /* Filter Sampai Tanggal */
        if ($this->request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_selesai', '<=', $this->request->tanggal_sampai);
        }

        /* Filter Status */
        if ($this->request->filled('status')) {
            $query->where('status', $this->request->status);
        }

        return $query->orderBy('tanggal_selesai', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Judul Pemeliharaan',
            'Tanggal Selesai',
            'Tenaga Kerja',
            'Nama Peralatan',
            'Lokasi',
            'Qty',
            'Serial Number',
            'Kapasitas',
            'Merek',
            'Tipe',
            'Tahun',
            'Status',
            'Keterangan',
        ];
    }

    public function map($item): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $item->judul_pemeliharaan,
            \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y'),
            $item->user ? $item->user->name : '-',
            $item->nama_peralatan,
            $item->lokasi ?? '-',
            $item->quantity ?? '-',
            $item->serial_number ?? '-',
            $item->kapasitas ?? '-',
            $item->merek ?? '-',
            $item->tipe ?? '-',
            $item->tahun_pembuatan ?? '-',
            ucfirst($item->status),
            $item->keterangan ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
