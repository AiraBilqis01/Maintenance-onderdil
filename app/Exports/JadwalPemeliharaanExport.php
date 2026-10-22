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
        $query = JadwalPemeliharaan::with('user');

        if ($this->request->filled('status')) {
            $query->where('status', $this->request->status);
        }

        if ($this->request->filled('search')) {
            $query->where('judul_pemeliharaan', 'like', '%' . $this->request->search . '%');
        }

        return $query->get();
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
            'Nama Onderdil',
            'Jumlah Pemakaian',
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
            $item->quantity,
            $item->serial_number ?? '-',
            $item->kapasitas ?? '-',
            $item->merek ?? '-',
            $item->tipe ?? '-',
            $item->tahun_pembuatan ?? '-',
            ucfirst($item->status),
            $item->nama_onderdil ?? '-',
            $item->jumlah_pemakaian ?? '-',
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