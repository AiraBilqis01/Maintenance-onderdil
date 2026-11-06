<?php

namespace App\Support;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class KetepatanJadwal
{
    public static function hitung(Model $jadwal, string $jenis): array
    {
        /* Tentukan kolom tanggal sesuai jenis jadwal. */
        if ($jenis === 'pemeliharaan') {
            $kolomTarget = 'tanggal_selesai';
        } elseif ($jenis === 'pengecekan') {
            $kolomTarget = 'tanggal_pengecekan';
        } else {
            throw new InvalidArgumentException('Jenis jadwal tidak dikenal.');
        }

        /* Ambil tanggal target aktif, waktu pengiriman hasil, dan status pekerjaan. */
        $tanggalTarget = $jadwal->getAttribute($kolomTarget);
        $tanggalKirim = $jadwal->getAttribute('tanggal_kirim_hasil');
        $status = $jadwal->getAttribute('status');

        /* Ambil riwayat revisi yang sudah dicatat. */
        $riwayat = $jadwal->riwayatRevisi;
        $jumlahRevisi = $riwayat->count();
        $hariTerlambatTercatat = (int) (
            $riwayat->max('hari_terlambat_sebelum_revisi') ?? 0
        );

        $pernahTerlambatSebelumRevisi =
            $riwayat->contains(function ($item) {
                return (bool) $item->terlambat_sebelum_revisi;
            });

        /* Jika tanggal target tidak tersedia. */
        if (!$tanggalTarget) {
            return [
                'label' => 'Target Belum Tersedia',
                'warna' => 'secondary',
                'hari_terlambat' => 0,
                'jumlah_revisi' => $jumlahRevisi,
                'pernah_terlambat_sebelum_revisi' => $pernahTerlambatSebelumRevisi,
                'hari_terlambat_tercatat' => $hariTerlambatTercatat,
            ];
        }

        $target = Carbon::parse($tanggalTarget, 'Asia/Jakarta')->startOfDay();

        /* Jika Tenaga Kerja sudah mengirimkan hasil. */
        if ($tanggalKirim) {
            $tanggalPengiriman = Carbon::parse($tanggalKirim,'Asia/Jakarta')->startOfDay();
            $hariTerlambat = max(0, (int) $target->diffInDays($tanggalPengiriman, false));

            if ($status === 'selesai') {
                $awalan = 'Selesai';
            } else {
                $awalan = 'Dikirim';
            }

            if ($hariTerlambat > 0) {
                $label = $awalan . ' Terlambat ' . $hariTerlambat . ' Hari';
                $warna = 'danger';
            } else {
                $label = $awalan . ' Tepat Waktu';
                $warna = 'success';
            }
        } else {
            /* Belum ada hasil yang dikirim.
             * Bandingkan target dengan hari ini. */
            $hariIni = now('Asia/Jakarta')->startOfDay();
            $hariTerlambat = max(0, (int) $target->diffInDays($hariIni, false));

            if ($status === 'ditolak') {
                $label = 'Ditolak - Belum Ada Hasil';
                $warna = 'secondary';
            } elseif ($hariTerlambat > 0) {
                $label = 'Belum Dikirim - Terlambat ' . $hariTerlambat . ' Hari';
                $warna = 'danger';
            } else {
                $label = 'Belum Dikirim - Dalam Tenggat';
                $warna = 'secondary';
            }
        }

        return [
            'label' => $label,
            'warna' => $warna,
            'hari_terlambat' => $hariTerlambat,
            'jumlah_revisi' => $jumlahRevisi,
            'pernah_terlambat_sebelum_revisi' => $pernahTerlambatSebelumRevisi,
            'hari_terlambat_tercatat' => $hariTerlambatTercatat,
        ];
    }
}
