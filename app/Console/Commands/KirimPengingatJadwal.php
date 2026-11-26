<?php

namespace App\Console\Commands;

use App\Mail\PengingatJadwalPemeliharaanMail;
use App\Mail\PengingatJadwalPengecekanOnderdilMail;
use App\Models\JadwalPemeliharaan;
use App\Models\JadwalPengecekanOnderdil;
use App\Models\RiwayatPengingatJadwal;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class KirimPengingatJadwal extends Command
{
    /* Nama perintah Artisan. */
    protected $signature = 'jadwal:kirim-pengingat';

    /* Deskripsi perintah Artisan. */
    protected $description = 'Mengirim email pengingat H-1 dan hari H jadwal pemeliharaan dan pengecekan sparepart';

    private int $jumlahBerhasil = 0;
    private int $jumlahDilewati = 0;
    private int $jumlahGagal = 0;

    /* Menjalankan perintah Artisan. */
    public function handle(): int
    {
        // Mencegah command berjalan bersamaan.
        $lock = Cache::lock('kirim-pengingat-jadwal', 3600);
        if (!$lock->get()) {
            $this->warn('Command pengingat jadwal sedang berjalan.');
            return self::SUCCESS;
        }

        try {
            // Menggunakan zona waktu PLTM Sangir Hulu.
            $hariIni = now('Asia/Jakarta')->toDateString();
            $besok = now('Asia/Jakarta')
                ->addDay()
                ->toDateString();
            $this->info('Memulai pemeriksaan pengingat jadwal.');
            $this->info('Tanggal hari ini: ' . $hariIni);

            // Pemeriksaan jadwal pemeliharaan.
            JadwalPemeliharaan::query()
                ->where('status', 'pending')
                ->whereNull('tanggal_kirim_hasil')
                ->whereIn('tanggal_selesai', [$hariIni, $besok])
                ->chunkById(100, function ($daftarJadwal) {
                    foreach ($daftarJadwal as $jadwal) {
                        $this->prosesPengingat($jadwal, 'pemeliharaan');
                    }
                });

            // Pemeriksaan jadwal pengecekan sparepart.
            JadwalPengecekanOnderdil::query()
                ->where('status', 'pending')
                ->whereNull('tanggal_kirim_hasil')
                ->whereIn('tanggal_pengecekan', [$hariIni, $besok])
                ->chunkById(100, function ($daftarJadwal) {
                    foreach ($daftarJadwal as $jadwal) {
                        $this->prosesPengingat($jadwal, 'pengecekan');
                    }
                });
            $this->newLine();
            $this->info('Email berhasil dikirim: ' . $this->jumlahBerhasil);
            $this->info('Pengiriman dilewati: ' . $this->jumlahDilewati);
            $this->info('Pengiriman gagal: ' . $this->jumlahGagal);

            return $this->jumlahGagal > 0
                ? self::FAILURE
                : self::SUCCESS;

        } finally {
            // Melepaskan lock setelah command selesai.
            $lock->release();
        }
    }

    /* Memproses pengiriman pengingat untuk satu jadwal. */
    private function prosesPengingat(
        Model $jadwal, string $jenisJadwal
    ): void {
        $riwayat = null;
        try {
            // Mengambil ulang jadwal terbaru dari database.
            // Jadwal yang diarsipkan tidak akan ditemukan.
            $jadwalTerbaru = $jadwal->newQuery()
                ->find($jadwal->getKey());
            if (!$jadwalTerbaru) {
                $this->jumlahDilewati++;
                return;
            }

            $jadwal = $jadwalTerbaru;

            // Memastikan pekerjaan masih belum diselesaikan.
            if ($jadwal->status !== 'pending' || $jadwal->tanggal_kirim_hasil !== null) {
                $this->jumlahDilewati++;
                return;
            }

            // Menentukan tanggal target dan penerima.
            if ($jenisJadwal === 'pemeliharaan') {
                $tanggalTarget = $jadwal->tanggal_selesai;
                $tenagaKerja = $jadwal->user;
            } else {
                $tanggalTarget = $jadwal->tanggal_pengecekan;
                $tenagaKerja = $jadwal->tenagaKerja;
            }

            // Memastikan tanggal target tersedia.
            if (!$tanggalTarget) {
                $this->jumlahDilewati++;
                return;
            }

            // Memastikan akun tenaga kerja masih aktif.
            if (!$tenagaKerja || $tenagaKerja->trashed() || $tenagaKerja->role !== 'tenagakerja' || !filter_var($tenagaKerja->email, FILTER_VALIDATE_EMAIL)) {
                $this->jumlahDilewati++;
                return;
            }

            // Menentukan tanggal yang berlaku.
            $tanggalTarget = $tanggalTarget->toDateString();
            $waktuSekarang = now('Asia/Jakarta');
            $hariIni = $waktuSekarang->toDateString();
            $besok = $waktuSekarang->copy()
                ->addDay()
                ->toDateString();

            // Menentukan jenis email pengingat.
            if ($tanggalTarget === $besok) {
                $jenisPengingat = 'h_1';
            } elseif ($tanggalTarget === $hariIni) {
                $jenisPengingat = 'hari_h';
            } else {
                $this->jumlahDilewati++;
                return;
            }

            // Membuat atau mengambil riwayat pengingat.
            $riwayat = RiwayatPengingatJadwal::firstOrCreate(
                [
                    'jenis_jadwal' => $jenisJadwal,
                    'jadwal_id' => $jadwal->id,
                    'user_id' => $tenagaKerja->id,
                    'tanggal_target' => $tanggalTarget,
                    'jenis_pengingat' => $jenisPengingat,
                ], [
                    'dikirim_pada' => null,
                ]
            );

            // Jangan mengirim ulang jika sudah berhasil dikirim.
            if ($riwayat->dikirim_pada !== null) {
                $this->jumlahDilewati++;
                return;
            }

            // Menentukan Mailable berdasarkan jenis jadwal.
            if ($jenisJadwal === 'pemeliharaan') {
                $mail = new PengingatJadwalPemeliharaanMail($jadwal, $jenisPengingat);
            } else {
                $jadwal->load('details');
                $mail = new PengingatJadwalPengecekanOnderdilMail($jadwal, $jenisPengingat);
            }

            // Mengirim email kepada tenaga kerja terkait.
            $hasilPengiriman = Mail::to($tenagaKerja->email)
                ->send($mail);

            // Memastikan pengiriman tidak dibatalkan.
            if ($hasilPengiriman === null) {
                throw new \RuntimeException(
                    'Pengiriman email tidak berhasil diselesaikan.'
                );
            }

            // Mencatat waktu setelah pengiriman berhasil.
            $riwayat->update(['dikirim_pada' => now(), ]);
            $this->jumlahBerhasil++;
            $this->info(
                'Pengingat berhasil dikirim: ' .
                $jenisJadwal .
                ' | ID Jadwal: ' . $jadwal->id .
                ' | ' . $jenisPengingat
            );

        } catch (Throwable $e) {
            $this->jumlahGagal++;
            // Mencatat kegagalan tanpa menghentikan jadwal lainnya.
            Log::error('Gagal mengirim atau mencatat email pengingat jadwal.', [
                'jenis_jadwal' => $jenisJadwal,
                'jadwal_id' => $jadwal->id,
                'riwayat_id' => $riwayat?->id,
                'error' => $e->getMessage(),
            ]);

            $this->error(
                'Pengingat gagal: ' .
                $jenisJadwal .
                ' | ID Jadwal: ' . $jadwal->id
            );
        }
    }
}
