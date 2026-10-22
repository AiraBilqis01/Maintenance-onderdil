<?php

namespace App\Exports;

use App\Models\StokOnderdil;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StokOnderdilExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = StokOnderdil::query();

        if ($this->request->filled('search')) {
            $query->where('nama_sparepart', 'like', '%' . $this->request->search . '%');
        }

        if ($this->request->filled('kondisi_stok')) {
            if ($this->request->kondisi_stok == 'menipis') {
                $query->where('stok_terbaru', '<', 10);
            } elseif ($this->request->kondisi_stok == 'aman') {
                $query->where('stok_terbaru', '>=', 10);
            }
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Sparepart',
            'Stok Sebelum',
            'Periode',
            'Kondisi Sekarang',
            'Pemakaian',
            'Stok Terbaru',
            'Keterangan',
        ];
    }

    public function map($item): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $item->nama_sparepart,
            $item->stok_periode_sebelum,
            \Carbon\Carbon::parse($item->periode)->format('F Y'),
            $item->kondisi_sekarang,
            $item->pemakaian,
            $item->stok_terbaru,
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