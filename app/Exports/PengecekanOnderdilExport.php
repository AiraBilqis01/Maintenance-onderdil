<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PengecekanOnderdilExport implements
    FromCollection,
    WithHeadings,
    WithStyles,
    ShouldAutoSize
{
    protected $jadwal;

    public function __construct($jadwal)
    {
        $this->jadwal = $jadwal;
    }

    public function collection()
    {
        $hasil = collect();
        $no = 1;

        foreach ($this->jadwal as $jadwal) {
            if ($jadwal->details->isEmpty()) {
                $hasil->push([
                    'no' => $no++,
                    'judul_pengecekan' => $jadwal->judul_pengecekan,
                    'tanggal_pengecekan' => optional($jadwal->tanggal_pengecekan)->format('d/m/Y') ?? '-',
                    'tanggal_kirim_hasil' => ($jadwal->status === 'selesai' && !empty($jadwal->tanggal_kirim_hasil)) ? \Carbon\Carbon::parse($jadwal->tanggal_kirim_hasil)->format('d/m/Y') : '-',
                    'tenaga_kerja' => $jadwal->tenagaKerja->name ?? '-',
                    'nama_sparepart' => '-',
                    'stok_sistem' => '-',
                    'stok_fisik' => '-',
                    'selisih' => '-',
                    'kondisi' => '-',
                    'penyebab_selisih' => '-',
                    'keterangan_selisih' => '-',
                    'catatan_pengecekan' => '-',
                    'status' => $this->formatStatus($jadwal->status),
                    'keterangan_jadwal' => $jadwal->keterangan ?? '-',
                    'alasan_penolakan' => $jadwal->alasan_penolakan ?? '-',
                ]);
                continue;
            }

            foreach ($jadwal->details as $detail) {
                $hasil->push([
                    'no' => $no++,
                    'judul_pengecekan' => $jadwal->judul_pengecekan,
                    'tanggal_pengecekan' => optional($jadwal->tanggal_pengecekan)->format('d/m/Y') ?? '-',
                    'tanggal_kirim_hasil' => ($jadwal->status === 'selesai' && !empty($jadwal->tanggal_kirim_hasil)) ? \Carbon\Carbon::parse($jadwal->tanggal_kirim_hasil)->format('d/m/Y') : '-',
                    'tenaga_kerja' => $jadwal->tenagaKerja->name ?? '-',
                    'nama_sparepart' => $detail->nama_sparepart_snapshot ?? '-',

                    'stok_sistem' => !is_null($detail->stok_sistem_saat_cek)
                        ? $detail->stok_sistem_saat_cek : ($detail->stokOnderdil->stok_terbaru ?? '-'),
                    'stok_fisik' => !is_null($detail->stok_fisik)
                        ? $detail->stok_fisik : '-',
                    'selisih' => !is_null($detail->selisih)
                        ? $detail->selisih : '-',

                    'kondisi' => $detail->kondisi ?? '-',
                    'penyebab_selisih' => $detail->penyebab_selisih ?? '-',
                    'keterangan_selisih' => $detail->keterangan_selisih ?? '-',
                    'catatan_pengecekan' => $detail->catatan_pengecekan ?? '-',
                    'status' => $this->formatStatus($jadwal->status),
                    'keterangan_jadwal' => $jadwal->keterangan ?? '-',
                    'alasan_penolakan' => $jadwal->alasan_penolakan ?? '-',
                ]);
            }
        }
        return $hasil;
    }

    public function headings(): array
    {
        return [
            'No',
            'Judul Pengecekan',
            'Tanggal Pengecekan',
            'Hasil Dikirim (ACC)',
            'Tenaga Kerja',
            'Nama Sparepart',
            'Stok Sistem',
            'Stok Fisik',
            'Selisih',
            'Kondisi',
            'Penyebab Selisih',
            'Keterangan Selisih',
            'Catatan Pengecekan',
            'Status',
            'Keterangan Jadwal',
            'Alasan Penolakan',
        ];
    }

    private function formatStatus($status)
    {
        switch ($status) {
            case 'pending':
                return 'Pending';

            case 'menunggu_verifikasi':
                return 'Menunggu Verifikasi';

            case 'selesai':
                return 'Selesai';

            case 'ditolak':
                return 'Ditolak';

            default:
                return ucfirst(str_replace('_', ' ', $status));
        }
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:P1')->getFont()->setBold(true);
        $sheet->getStyle('A1:P1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A:P')->getAlignment()->setVertical('top');
        $sheet->getStyle('A:P')->getAlignment()->setWrapText(true);
        return [];
    }
}
