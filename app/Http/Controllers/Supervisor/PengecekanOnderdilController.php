<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\User;
use App\Models\StokOnderdil;
use App\Models\JadwalPengecekanOnderdil;
use App\Models\DetailPengecekanOnderdil;
use App\Models\RiwayatRevisiJadwal;

use App\Mail\JadwalPengecekanOnderdilMail;
use App\Mail\HasilPengecekanOnderdilDitolakMail;
use App\Mail\JadwalPengecekanOnderdilDiperbaruiMail;
use App\Mail\PenugasanPengecekanOnderdilDialihkanMail;

use App\Exports\PengecekanOnderdilExport;
use Maatwebsite\Excel\Facades\Excel;

class PengecekanOnderdilController extends Controller
{
    /* =========================================================
     *                      BUILD QUERY
     * =========================================================
     * Digunakan untuk filter data pada halaman index dan export PDF.
     * Jika tidak ada filter yang dipilih, seluruh jadwal tetap ditampilkan. */
    private function buildQuery(Request $request)
    {
        $query = JadwalPengecekanOnderdil::with(['tenagaKerja','details.stokOnderdil']);
        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter tenaga kerja
        if ($request->filled('tenaga_kerja')) {
            $query->where('tenaga_kerja_id', $request->tenaga_kerja);
        }

        // Filter mulai tanggal
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_pengecekan','>=', $request->tanggal_dari);
        }

        // Filter sampai tanggal
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_pengecekan','<=', $request->tanggal_sampai);
        }
        return $query;
    }

    /* =========================================================
     *                          INDEX
     * =========================================================
     * Menampilkan halaman utama Pengecekan Onderdil. */
    public function index(Request $request)
    {
        /* Jika filter tidak dipilih, buildQuery() akan mengembalikan seluruh data. */
        $jadwalPengecekan = $this->buildQuery($request)->with('riwayatRevisi')->orderBy('tanggal_pengecekan', 'desc')->get();

        /* Hanya Tenaga Kerja dengan jenis tugas:
         * - pengecekan_onderdil
         * - keduanya
         * yang dapat menerima jadwal pengecekan. */
        $tenagaKerja = User::where('role', 'tenagakerja')->whereIn('jenis_tugas',['pengecekan_onderdil', 'keduanya'])->orderBy('name')->get();
        /* Data Manajemen Stok digunakan pada form tambah dan edit jadwal. */
        $stokOnderdil = StokOnderdil::orderBy('nama_sparepart')->get();

        return view('supervisor.pengecekan-onderdil.index',compact(
            'jadwalPengecekan',
            'tenagaKerja',
            'stokOnderdil'
        ));
    }

    /* =========================================================
     *                      STORE
     * =========================================================
     * Membuat jadwal pengecekan baru. Setelah berhasil disimpan,
     * email notifikasi dikirim kepada Tenaga Kerja yang ditugaskan. */
    public function store(Request $request)
    {
        $data = $request->validate([
            'judul_pengecekan' => 'required|string|max:255',
            'tanggal_pengecekan' => 'required|date_format:Y-m-d|after_or_equal:' . now('Asia/Jakarta')->toDateString(),
            'tenaga_kerja_id' => 'required|exists:users,id',
            /* Supervisor minimal memilih satu onderdil. Boleh memilih satu atau lebih. */
            'stok_onderdil_ids' => 'required|array|min:1',
            'stok_onderdil_ids.*' => 'required|distinct|exists:stok_onderdil,id',
            'keterangan' => 'nullable|string',
        ], [
            'tanggal_pengecekan.after_or_equal' => 'Tanggal pengecekan tidak boleh sebelum tanggal hari ini.',
            'tanggal_pengecekan.date_format' => 'Format tanggal pengecekan tidak valid.',
        ]);

        /* Pastikan tenaga kerja yang dipilih memang memiliki tugas pengecekan onderdil. */
        $tenagaKerja = User::where('id', $data['tenaga_kerja_id'])->where('role', 'tenagakerja')->whereIn('jenis_tugas', ['pengecekan_onderdil','keduanya'])->first();
        if (!$tenagaKerja) {
            return back()->withInput()->withErrors(['tenaga_kerja_id' => 'Tenaga kerja yang dipilih tidak memiliki tugas pengecekan sparepart.']);
        }
        /* Simpan jadwal utama dan detail onderdil dalam satu database transaction. */
        $jadwal = DB::transaction(function () use ($data) {
            /* Simpan jadwal utama. */
            $jadwal = JadwalPengecekanOnderdil::create([
                'judul_pengecekan' => $data['judul_pengecekan'],
                'tanggal_pengecekan' => $data['tanggal_pengecekan'],
                'tanggal_target_awal' => $data['tanggal_pengecekan'],
                'tenaga_kerja_id' => $data['tenaga_kerja_id'],
                'keterangan' => $data['keterangan'] ?? null,
                'status' => 'pending',
                'keterangan_tolak' => null,
                'alasan_penolakan' => null,
            ]);
            /* Ambil semua onderdil yang dipilih. */
            $stokDipilih = StokOnderdil::whereIn('id', $data['stok_onderdil_ids'])->get();
            /* Simpan setiap onderdil sebagai detail jadwal pengecekan. */
            foreach ($stokDipilih as $stok) {
                DetailPengecekanOnderdil::create([
                    'jadwal_pengecekan_id' => $jadwal->id,
                    'stok_onderdil_id' => $stok->id,
                    /* Snapshot nama digunakan agar riwayat nama onderdil tetap tersedia. */
                    'nama_sparepart_snapshot' => $stok->nama_sparepart,
                ]);
            }
            return $jadwal;
        });

        /* Muat data yang diperlukan untuk isi email. */
        $jadwal->load(['tenagaKerja', 'details.stokOnderdil']);
        /* Kirim email notifikasi kepada Tenaga Kerja. */
        if ($jadwal->tenagaKerja && $jadwal->tenagaKerja->email) {
            Mail::to($jadwal->tenagaKerja->email)->send(new JadwalPengecekanOnderdilMail($jadwal));
        }

        return back()->with('sukses', 'Jadwal pengecekan sparepart berhasil ditambahkan dan email notifikasi telah dikirim.');
    }

    /* =========================================================
     *                          UPDATE
     * =========================================================
     * Mengedit jadwal pengecekan.
     * Aturan Edit:
     * pending               = boleh Edit
     * menunggu_verifikasi   = tidak boleh Edit
     * selesai               = tidak boleh Edit
     * ditolak               = tidak boleh Edit */
    public function update(Request $request, JadwalPengecekanOnderdil $jadwal)
    {
        /* =====================================================
        *       HANYA JADWAL PENDING YANG BOLEH DIEDIT
        * ==================================================== */
        if ($jadwal->status !== 'pending') {
            return back()->withErrors(['error' => 'Jadwal pengecekan hanya dapat diedit ketika status masih Pending.']);
        }

        // ==========================================
        //       VALIDASI TANGGAL PENGECEKAN
        // ==========================================
        // Ambil tanggal pengecekan yang sudah tersimpan
        $tanggalLama = \Carbon\Carbon::parse($jadwal->tanggal_pengecekan)->format('Y-m-d');
        
        /* Periksa apakah Supervisor mengubah tanggal pengecekan. */
        $tanggalBerubah = $request->input('tanggal_pengecekan') !== $tanggalLama;
        
        // Tanggal lama tetap boleh dipertahankan
        $aturanTanggal = 'required|date_format:Y-m-d';
        
        /* Jika tanggal diubah, target baru tidak boleh sebelum hari ini. */
        if ($tanggalBerubah) {
            $aturanTanggal .= '|after_or_equal:' . now('Asia/Jakarta')->toDateString();
        }

        /* =====================================================
        *                       VALIDASI DATA
        * ===================================================== */
        $data = $request->validate([
            'judul_pengecekan' => 'required|string|max:255',
            'tanggal_pengecekan' => $aturanTanggal,
            'alasan_revisi' => [ Rule::requiredIf($tanggalBerubah), 'nullable', 'string', 'min:10', 'max:1000',],
            'tenaga_kerja_id' => 'required|exists:users,id',
            'stok_onderdil_ids' => 'required|array|min:1',
            'stok_onderdil_ids.*' => 'required|distinct|exists:stok_onderdil,id',
            'keterangan' => 'nullable|string',
        ], [
            'tanggal_pengecekan.after_or_equal' => 'Tanggal pengecekan tidak boleh sebelum tanggal hari ini.',
            'tanggal_pengecekan.date_format' => 'Format tanggal pengecekan tidak valid.',
            'alasan_revisi.required' => 'Alasan revisi wajib diisi ketika tanggal pengecekan diubah.',
            'alasan_revisi.min' => 'Alasan revisi minimal 10 karakter.',
        ]);

        /* Simpan alasan revisi ke variabel sementara. Alasan akan dicatat ke tabel riwayat. */
        $alasanRevisi = $data['alasan_revisi'] ?? null;

        /* Pisahkan alasan revisi dari data jadwal utama. */
        unset($data['alasan_revisi']);

        /* =====================================================
        *               VALIDASI TENAGA KERJA
        * =====================================================
        * Tenaga Kerja yang dipilih harus:
        * - role tenagakerja
        * - mempunyai jenis tugas pengecekan_onderdil atau keduanya */
        $tenagaKerjaBaru = User::where('id', $data['tenaga_kerja_id'])
            ->where('role', 'tenagakerja')
            ->whereIn('jenis_tugas', ['pengecekan_onderdil', 'keduanya'])->first();

        if (!$tenagaKerjaBaru) {
            return back()->withInput()
                ->withErrors(['tenaga_kerja_id' => 'Tenaga kerja yang dipilih tidak memiliki tugas pengecekan sparepart.']);
        }

        /* =====================================================
        *               SIMPAN TENAGA KERJA LAMA
        * =====================================================
        * Data ini harus diambil SEBELUM jadwal diubah.
        * Digunakan jika penugasan dialihkan. */
        $tenagaKerjaLamaId = $jadwal->tenaga_kerja_id;

        $tenagaKerjaLama = User::withTrashed()->find($tenagaKerjaLamaId);

        /* =====================================================
        *                  DAFTAR SPAREPART LAMA
        * ===================================================== */
        $stokLama = $jadwal->details()
            ->pluck('stok_onderdil_id')->filter()
            ->map(function ($id) {
                return (int) $id;
            })
            ->sort()
            ->values()
            ->toArray();

        /* =====================================================
        *           DAFTAR SPAREPART BARU DARI FORM
        * ===================================================== */
        $stokBaruArray = collect($data['stok_onderdil_ids'])
            ->map(function ($id) {
                return (int) $id;
            })
            ->sort()
            ->values()
            ->toArray();

        /* =====================================================
        * MASUKKAN DATA BARU KE MODEL
        * BELUM DISIMPAN
        * ===================================================== */
        $jadwal->fill([
            'judul_pengecekan' => $data['judul_pengecekan'],
            'tanggal_pengecekan' => $data['tanggal_pengecekan'],
            'tenaga_kerja_id' => $data['tenaga_kerja_id'],
            'keterangan' => $data['keterangan'] ?? null,
        ]);

        /* =====================================================
        *                   CEK PERUBAHAN
        * ===================================================== */
        // Apakah data utama jadwal berubah?
        $dataJadwalBerubah = $jadwal->isDirty();

        // Apakah Tenaga Kerja berubah?
        $tenagaKerjaBerubah = $jadwal->isDirty('tenaga_kerja_id');

        // Apakah daftar sparepart berubah?
        $sparepartBerubah = $stokLama !== $stokBaruArray;

        /* =====================================================
        *               JIKA TIDAK ADA PERUBAHAN
        * =====================================================
        * Tidak perlu update database dan tidak perlu mengirim email. */
        if (!$dataJadwalBerubah && !$sparepartBerubah) {
            return back()->with('sukses', 'Tidak ada perubahan pada jadwal pengecekan sparepart.');
        }

        /* =====================================================
        *               PROSES UPDATE DATABASE
        * ===================================================== */
        DB::transaction(function () use ($jadwal, $data, $tanggalLama, $tanggalBerubah, $alasanRevisi) {
            /* Kunci baris jadwal selama transaksi untuk mencegah perubahan bersamaan. */
            $jadwalTerkunci = JadwalPengecekanOnderdil::whereKey($jadwal->id)->lockForUpdate()->firstOrFail();

            /* Ambil tanggal terkini dari database. */
            $tanggalDatabase = \Carbon\Carbon::parse($jadwalTerkunci->tanggal_pengecekan)->format('Y-m-d');

            /* Pastikan status masih Pending dan tanggal belum berubah oleh proses lain. */
            if ($jadwalTerkunci->status !== 'pending' || $tanggalDatabase !== $tanggalLama) {
                throw ValidationException::withMessages([
                    'tanggal_pengecekan' => 'Jadwal sudah berubah. Muat ulang halaman sebelum mengedit.',
                ]);
            }

            /* Simpan perubahan data utama jadwal. Tanggal target awal tidak diubah. */
            $jadwalTerkunci->fill([
                'judul_pengecekan' => $data['judul_pengecekan'],
                'tanggal_pengecekan' => $data['tanggal_pengecekan'],
                'tenaga_kerja_id' => $data['tenaga_kerja_id'],
                'keterangan' => $data['keterangan'] ?? null,
            ]);

            $jadwalTerkunci->save();

            /* ID sparepart baru dari form. */
            $stokBaru = collect($data['stok_onderdil_ids'])->map(function ($id) {
                return (int) $id;
            });

            /* Ambil detail lama. */
            $detailLama = $jadwal->details()->get();
            /* =================================================
            *       HAPUS SPAREPART YANG SUDAH TIDAK DIPILIH
            * ================================================= */
            foreach ($detailLama as $detail) {
                if (!$stokBaru->contains((int) $detail->stok_onderdil_id)) {
                    $detail->delete();
                }
            }

            /* =================================================
            *           AMBIL SPAREPART YANG MASIH ADA
            * ================================================= */
            $stokSudahAda = $jadwal->details()->pluck('stok_onderdil_id')->map(function ($id) {
                return (int) $id;
            });

            /* =================================================
            *               CARI SPAREPART BARU
            * ================================================= */
            $stokYangDitambahkan = $stokBaru->diff($stokSudahAda);

            /* =================================================
            *           TAMBAHKAN SPAREPART BARU
            * ================================================= */
            if ($stokYangDitambahkan->isNotEmpty()) {
                $dataStokBaru = StokOnderdil::whereIn('id', $stokYangDitambahkan->values()->all())->get();

                foreach ($dataStokBaru as $stok) {
                    DetailPengecekanOnderdil::create([
                        'jadwal_pengecekan_id' => $jadwal->id,
                        'stok_onderdil_id' => $stok->id,
                        'nama_sparepart_snapshot' => $stok->nama_sparepart,
                    ]);
                }
            }
            /* =====================================================
            * CATAT RIWAYAT REVISI TANGGAL PENGECEKAN
            * =====================================================
            * Riwayat hanya dibuat apabila Supervisor
            * benar-benar mengubah tanggal pengecekan. */
            if ($tanggalBerubah) {
                /* Ambil waktu revisi sesuai WIB. */
                $waktuRevisi = now('Asia/Jakarta');

                /* Ambil tanggal target sebelum direvisi. */
                $targetLama = \Carbon\Carbon::parse($tanggalLama,'Asia/Jakarta')->startOfDay();

                /* Hitung keterlambatan sebelum revisi.
                * Jika revisi dilakukan sebelum atau tepat pada tenggat, hasilnya 0 hari. */
                $hariTerlambat = max(0, (int) $targetLama->diffInDays($waktuRevisi->copy()->startOfDay(), false));

                /* Simpan satu catatan riwayat revisi. */
                RiwayatRevisiJadwal::create([
                    'jenis_jadwal' => 'pengecekan',
                    'jadwal_id' => $jadwalTerkunci->id,
                    'tanggal_lama' => $tanggalLama,
                    'tanggal_baru' => $data['tanggal_pengecekan'],
                    'alasan_revisi' => $alasanRevisi,
                    'supervisor_id' => auth()->id(),
                    'direvisi_pada' => $waktuRevisi,
                    'terlambat_sebelum_revisi' => $hariTerlambat > 0,
                    'hari_terlambat_sebelum_revisi' => $hariTerlambat,
                ]);
            }
        });

        /* =====================================================
        *               REFRESH DATA SETELAH UPDATE
        * ===================================================== */
        /* Ambil kembali data jadwal terbaru setelah transaksi berhasil disimpan. */
        $jadwal->refresh();

        /* Muat hubungan tenaga kerja dan detail sparepart untuk kebutuhan email. */
        $jadwal->load(['tenagaKerja', 'details']);

        /* =====================================================
        * EMAIL KE TENAGA KERJA LAMA
        * JIKA PENUGASAN DIALIHKAN
        * ===================================================== */
        if ($tenagaKerjaBerubah && $tenagaKerjaLama && $tenagaKerjaLama->email) {
            Mail::to($tenagaKerjaLama->email)->send(
                new PenugasanPengecekanOnderdilDialihkanMail($jadwal, $tenagaKerjaLama)
            );
        }

        /* =====================================================
        *       EMAIL PERUBAHAN KE TENAGA KERJA TERBARU
        * =====================================================
        * Jika Tenaga Kerja tidak berubah:
        * → dikirim ke Tenaga Kerja yang sama.
        * Jika Tenaga Kerja berubah:
        * → dikirim ke Tenaga Kerja baru. */
        if ($jadwal->tenagaKerja && $jadwal->tenagaKerja->email) {
            Mail::to($jadwal->tenagaKerja->email)->send(
                new JadwalPengecekanOnderdilDiperbaruiMail($jadwal)
            );
        }

        /* =====================================================
        *                      PESAN SUKSES
        * ===================================================== */
        if ($tenagaKerjaBerubah) {
            return back()->with('sukses', 'Jadwal pengecekan sparepart berhasil diperbarui. Email pengalihan telah dikirim kepada Tenaga Kerja lama dan email perubahan telah dikirim kepada Tenaga Kerja baru.');
        }

        return back()->with('sukses', 'Jadwal pengecekan sparepart berhasil diperbarui dan email pemberitahuan perubahan telah dikirim kepada Tenaga Kerja.');
    }

    /* =========================================================
     *              VERIFIKASI HASIL PENGECEKAN
     * =========================================================
     * Supervisor melakukan ACC hasil pengecekan.
     * Pengecekan onderdil bukan pemakaian onderdil.
     * Artinya:
     * stok_terbaru dapat direkonsiliasi, tetapi kolom pemakaian TIDAK berubah. */
    public function verifikasi(JadwalPengecekanOnderdil $jadwal)
    {
        try {
            DB::transaction(function () use ($jadwal) {
                /* Kunci jadwal agar tidak dapat diproses bersamaan. */
                $jadwalTerkunci = JadwalPengecekanOnderdil::whereKey($jadwal->id)->lockForUpdate()->firstOrFail();

                /* Hanya hasil dengan status menunggu_verifikasi yang boleh di-ACC. */
                if ($jadwalTerkunci->status !== 'menunggu_verifikasi') {
                    throw new \Exception('Hasil pengecekan belum dapat diverifikasi.');
                }

                $jadwalTerkunci->load('details');
                
                /* Pastikan jadwal memiliki detail. */
                if ($jadwalTerkunci->details->isEmpty()) {
                    throw new \Exception('Tidak terdapat detail sparepart pada jadwal ini.');
                }

                /* =====================================================
                *          VALIDASI HASIL PENGECEKAN
                * ===================================================== */
                foreach ($jadwalTerkunci->details as $detail) {
                    if (is_null($detail->stok_sistem_saat_cek) || is_null($detail->stok_fisik)) {
                        throw new \Exception('Masih terdapat sparepart yang belum memiliki hasil pengecekan.');
                    }
                    if ((int) $detail->stok_fisik < 0) {
                        throw new \Exception('Stok fisik ' . $detail->nama_sparepart_snapshot . ' tidak boleh bernilai negatif.');
                    }
                    if (empty($detail->kondisi)) {
                        throw new \Exception('Kondisi Sparepart ' . $detail->nama_sparepart_snapshot . ' belum diisi.');
                    }

                    /* Selisih = stok fisik - stok sistem saat pengecekan. */
                    $selisih = (int) $detail->stok_fisik - (int) $detail->stok_sistem_saat_cek;

                    /* Jika terdapat selisih, penyebab dan keterangannya wajib tersedia. */
                    if ($selisih !== 0) {
                        if (empty($detail->penyebab_selisih)) {
                            throw new \Exception('Penyebab selisih stok ' . $detail->nama_sparepart_snapshot . ' belum diisi.');
                        }

                        if (empty($detail->keterangan_selisih)) {
                            throw new \Exception('Keterangan selisih stok ' . $detail->nama_sparepart_snapshot . ' belum diisi.');
                        }
                    }
                }

                /* =====================================================
                *                    LANGKAH 1
                *          CEK APAKAH STOK SUDAH BERUBAH
                * =====================================================
                * Stok mungkin berubah karena pemeliharaan menggunakan
                * sparepart setelah hasil pengecekan dikirim. */
                $stokTerkunci = [];

                foreach ($jadwalTerkunci->details as $detail) {
                    if (empty($detail->stok_onderdil_id)) {
                        throw new \Exception(
                            'Data sparepart ' . $detail->nama_sparepart_snapshot . ' sudah tidak tersedia pada Manajemen Stok.'
                        );
                    }

                    $stok = StokOnderdil::whereKey($detail->stok_onderdil_id)->lockForUpdate()->first();

                    if (!$stok) {
                        throw new \Exception(
                            'Data stok ' . $detail->nama_sparepart_snapshot . ' tidak ditemukan.'
                        );
                    }

                    /* Simpan object stok yang sudah dikunci agar tidak perlu melakukan query lockForUpdate() kedua kali. */
                    $stokTerkunci[$detail->stok_onderdil_id] = $stok;

                    /* Jika stok sistem sekarang sudah berbeda dari stok ketika Tenaga Kerja mengirim hasil pengecekan, ACC tidak boleh dilanjutkan. */
                    if ((int) $stok->stok_terbaru !== (int) $detail->stok_sistem_saat_cek) {
                        throw new \Exception(
                            'Stok ' . $detail->nama_sparepart_snapshot .
                            ' telah berubah sejak pengecekan dilakukan. ' .
                            'Stok sistem saat diperiksa: ' . $detail->stok_sistem_saat_cek .
                            ', sedangkan stok sistem sekarang: ' . $stok->stok_terbaru .
                            '. Silakan lakukan pengecekan kembali sebelum melakukan penyesuaian stok.'
                        );
                    }
                }

                /* =====================================================
                *                    LANGKAH 2
                *                 REKONSILIASI STOK
                * ===================================================== */
                foreach ($jadwalTerkunci->details as $detail) {

                    $stok = $stokTerkunci[$detail->stok_onderdil_id];
                    /* Stok sistem sebelum rekonsiliasi. */
                    $stokSebelumnya = (int) $stok->stok_terbaru;

                    /* Stok hasil pengecekan fisik. */
                    $stokFisik = (int) $detail->stok_fisik;

                    /* Hitung ulang selisih. */
                    $selisih = $stokFisik - (int) $detail->stok_sistem_saat_cek;

                    /* Simpan nilai selisih pada detail pengecekan. */
                    $detail->update(['selisih' => $selisih,]);

                    /* Pertahankan keterangan lama pada Manajemen Stok. */
                    $keteranganLama = trim((string) $stok->keterangan);
                    $keteranganBaru = $keteranganLama;

                    /* Jika terdapat selisih, tambahkan riwayat rekonsiliasi. */
                    if ($selisih !== 0) {
                        $tanggalPengecekan = $jadwalTerkunci->tanggal_pengecekan->format('d-m-Y');
                        $catatanRekonsiliasi =
                            'Penyesuaian stok hasil pengecekan tanggal ' . $tanggalPengecekan .
                            '. Stok sistem saat diperiksa: ' . $detail->stok_sistem_saat_cek .
                            ', stok fisik: ' . $detail->stok_fisik .
                            ', selisih: ' . ($selisih > 0 ? '+' : '') . $selisih .
                            '. Penyebab: ' . $detail->penyebab_selisih .
                            '. Keterangan: ' . $detail->keterangan_selisih;

                        if ($keteranganLama !== '') {
                            $keteranganBaru = $keteranganLama . "\n\n" . $catatanRekonsiliasi;
                        } else {
                            $keteranganBaru = $catatanRekonsiliasi;
                        }
                    }

                    /* =================================================
                    *              UPDATE MANAJEMEN STOK
                    * =================================================
                    * PENTING:
                    * Kolom "pemakaian" TIDAK DIUBAH.
                    * Pemakaian hanya diperbarui melalui verifikasi
                    * Jadwal Pemeliharaan. */
                    $stok->update([
                        'stok_periode_sebelum' => $stokSebelumnya,
                        'stok_terbaru' => $stokFisik,
                        'kondisi_sekarang' => $detail->kondisi,
                        'periode' => now()->toDateString(),
                        'keterangan' => $keteranganBaru,
                    ]);
                }

                /* Seluruh proses berhasil. */
                $jadwalTerkunci->update([
                    'status' => 'selesai',
                    'tanggal_verifikasi' => now('Asia/Jakarta'),
                    'keterangan_tolak' => null,
                    'alasan_penolakan' => null,
                ]);
            });

        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }

        return back()->with(
            'sukses',
            'Hasil pengecekan berhasil diverifikasi dan data stok telah diperbarui.'
        );
    }

    /* =========================================================
     *                  TOLAK HASIL PENGECEKAN
     * =========================================================
     * Supervisor menolak hasil pengecekan yang dikirim Tenaga Kerja.
     * Alur:
     * menunggu_verifikasi
     *          ↓
     *       pending
     * Tenaga Kerja harus melakukan pengecekan ulang. */
    public function tolakHasil(Request $request, JadwalPengecekanOnderdil $jadwal)
    {
        $data = $request->validate([
            'keterangan_tolak' => 'required|string|max:1000',
            'alasan_penolakan' => 'required|string|max:2000',
        ], [
            'keterangan_tolak.required' => 'Keterangan penolakan wajib diisi.',
            'alasan_penolakan.required' => 'Alasan penolakan wajib diisi.',
        ]);

        $gambarYangDihapus = [];

        try {
            DB::transaction(function () use ($jadwal, $data, &$gambarYangDihapus) {
                /* Kunci jadwal agar tidak dapat diproses bersamaan. */
                $jadwalTerkunci = JadwalPengecekanOnderdil::whereKey($jadwal->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                /* Hanya hasil dengan status menunggu_verifikasi
                * yang dapat ditolak Supervisor. */
                if ($jadwalTerkunci->status !== 'menunggu_verifikasi') {
                    throw new \Exception(
                        'Hanya hasil pengecekan yang menunggu verifikasi yang dapat ditolak.'
                    );
                }

                $details = $jadwalTerkunci->details()->get();

                if ($details->isEmpty()) {
                    throw new \Exception(
                        'Tidak terdapat detail sparepart pada jadwal ini.'
                    );
                }

                /* Pastikan ini benar-benar HASIL PENGECEKAN
                * Tenaga Kerja, bukan penolakan tugas. */
                $adaHasilPengecekan = $details->contains(function ($detail) {
                    return $detail->stok_fisik !== null;
                });

                if (!$adaHasilPengecekan) {
                    throw new \Exception(
                        'Data ini bukan hasil pengecekan Tenaga Kerja.'
                    );
                }

                /* Simpan alasan Supervisor.
                * Status dikembalikan menjadi pending supaya
                * Tenaga Kerja dapat mengisi ulang hasil. */
                $jadwalTerkunci->update([
                    'status' => 'pending',
                    'tanggal_kirim_hasil' => null,
                    'tanggal_verifikasi' => null,
                    'keterangan_tolak' => $data['keterangan_tolak'],
                    'alasan_penolakan' => $data['alasan_penolakan'],
                ]);

                /* Hapus hasil pengecekan sebelumnya.
                * Yang TIDAK dihapus:
                * - jadwal_pengecekan_id
                * - stok_onderdil_id
                * - nama_sparepart_snapshot
                * PENTING:
                * Tidak ada perubahan pada tabel stok_onderdil. */
                foreach ($details as $detail) {
                    /* Catat gambar lama agar file fisiknya
                    * dapat dihapus setelah transaksi berhasil. */
                    if (!empty($detail->gambar_bukti)) {
                        $gambarYangDihapus[] = $detail->gambar_bukti;
                    }

                    $detail->update([
                        'stok_sistem_saat_cek' => null,
                        'stok_fisik' => null,
                        'selisih' => null,
                        'penyebab_selisih' => null,
                        'keterangan_selisih' => null,
                        'kondisi' => null,
                        'catatan_pengecekan' => null,
                        'gambar_bukti' => null,
                    ]);
                }
            });

        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }

        /* =====================================================
        *              HAPUS FILE BUKTI LAMA
        * =====================================================
        * Dilakukan setelah transaksi database berhasil. */
        foreach ($gambarYangDihapus as $path) {
            $fullPath = public_path($path);

            if (file_exists($fullPath)) {
                @unlink($fullPath);
            }
        }

        /* =====================================================
        *                     KIRIM EMAIL
        * ===================================================== */
        $jadwal->refresh();
        $jadwal->load([
            'tenagaKerja', 'details',
        ]);

        if ($jadwal->tenagaKerja && $jadwal->tenagaKerja->email) {
            Mail::to($jadwal->tenagaKerja->email)
                ->send(new HasilPengecekanOnderdilDitolakMail($jadwal));
        }

        return back()->with(
            'sukses',
            'Hasil pengecekan berhasil ditolak. Status dikembalikan menjadi Pending dan email pemberitahuan telah dikirim kepada Tenaga Kerja.'
        );
    }

    /* =========================================================
     *                  VERIFIKASI PENOLAKAN TUGAS
     * =========================================================
     * Digunakan ketika Tenaga Kerja mengajukan penolakan tugas.
     * Alur:
     * pending
     *    ↓
     * Tenaga Kerja Tolak
     *    ↓
     * menunggu_verifikasi
     *    ↓
     * Supervisor ACC Tolak
     *    ↓
     * ditolak */
    public function verifikasiTolak(JadwalPengecekanOnderdil $jadwal)
    {
        try {
            DB::transaction(function () use ($jadwal) {
                /* Kunci jadwal agar tidak dapat diproses bersamaan oleh request lain. */
                $jadwalTerkunci = JadwalPengecekanOnderdil::whereKey($jadwal->id)->lockForUpdate()->firstOrFail();

                /* Hanya pengajuan dengan status menunggu_verifikasi yang dapat diproses. */
                if ($jadwalTerkunci->status !== 'menunggu_verifikasi') {
                    throw new \Exception('Tidak ada pengajuan penolakan yang dapat diverifikasi.');
                }

                /* Harus ada alasan penolakan dari Tenaga Kerja. */
                if (empty($jadwalTerkunci->alasan_penolakan)) {
                    throw new \Exception('Tidak ditemukan alasan penolakan dari Tenaga Kerja.');
                }

                /* Jika stok_fisik tersedia, berarti ini adalah HASIL PENGECEKAN, bukan penolakan tugas. */
                $adaHasilPengecekan = $jadwalTerkunci->details()
                    ->whereNotNull('stok_fisik')
                    ->exists();

                if ($adaHasilPengecekan) {
                    throw new \Exception('Data ini merupakan hasil pengecekan, bukan pengajuan penolakan tugas.');
                }

                /* Supervisor menyetujui penolakan tugas Tenaga Kerja.
                * PENTING:
                * Tidak ada perubahan pada stok_onderdil. */
                $jadwalTerkunci->update(['status' => 'ditolak',]);
            });

        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }

        return back()->with(
            'sukses',
            'Pengajuan penolakan tugas berhasil disetujui.'
        );
    }

    /**
     * =========================================================
     *                          DESTROY
     * =========================================================
     * Menghapus jadwal pengecekan. */
    public function destroy(JadwalPengecekanOnderdil $jadwal) {
        /* Jadwal menunggu verifikasi tidak boleh dihapus. */
        if (!in_array($jadwal->status, ['pending', 'ditolak', 'selesai'])) {
            return back()->withErrors(['error' => 'Jadwal yang sedang menunggu verifikasi tidak dapat dihapus.']);
        }

        /* Detail ikut terhapus karena foreign key menggunakan cascadeOnDelete(). */
        $jadwal->delete();
        return back()->with('sukses', 'Jadwal pengecekan sparepart berhasil dihapus.');
    }

    /**
     * =========================================================
     *                          EXPORT PDF
     * =========================================================
     * Mencetak data pengecekan onderdil. Filter yang digunakan pada halaman index juga diterapkan pada PDF. */
    public function exportPdf(Request $request){
        $jadwal = $this->buildQuery($request)->orderBy('tanggal_pengecekan', 'desc')->get();
        $judul = 'Laporan Pengecekan Sparepart';
        $periode = $this->getPeriodeText($request);

        /* View PDF: resources/views/supervisor/pengecekan-onderdil/pdf.blade.php */
        $pdf = Pdf::loadView('supervisor.pengecekan-onderdil.pdf', compact('jadwal', 'judul', 'periode'))->setPaper('a4', 'landscape');

        return $pdf->download('laporan-pengecekan-onderdil-' . date('Ymd-His') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $jadwal = $this->buildQuery($request)->orderBy('tanggal_pengecekan', 'desc')->get();
        $namaFile = 'laporan-pengecekan-sparepart-' . now()->format('d-m-Y') . '.xlsx';

        return Excel::download(new PengecekanOnderdilExport($jadwal), $namaFile);
    }

    /**
     * =========================================================
     *                      GET PERIODE TEXT
     * ========================================================
     * Membuat informasi filter/periode untuk ditampilkan pada PDF. */
    private function getPeriodeText(Request $request){
        $parts = [];

        if ($request->filled('tanggal_dari') && $request->filled('tanggal_sampai')) {
            $parts[] = 'Periode: ' . \Carbon\Carbon::parse($request->tanggal_dari)->format('d/m/Y')
                     . ' s/d ' . \Carbon\Carbon::parse($request->tanggal_sampai)->format('d/m/Y');
        }

        /* Hanya tanggal mulai. */
        elseif ($request->filled('tanggal_dari')) {
            $parts[] = 'Mulai: ' . \Carbon\Carbon::parse($request->tanggal_dari)->format('d/m/Y');
        }

        /* Hanya tanggal sampai. */
        elseif ($request->filled('tanggal_sampai')) {
            $parts[] = 'Sampai: ' . \Carbon\Carbon::parse( $request->tanggal_sampai)->format('d/m/Y');
        }

        /* Status. */
        if ($request->filled('status')) {
            $statusText = ['pending' => 'Pending', 'menunggu_verifikasi' => 'Menunggu Verifikasi', 'selesai' => 'Selesai', 'ditolak' => 'Ditolak',];
            $parts[] = 'Status: ' . ($statusText[$request->status] ?? ucwords(str_replace('_', ' ', $request->status)));
        }

        /* Tenaga Kerja. withTrashed() digunakan supaya nama Tenaga Kerja lama masih dapat ditemukan walaupun akunnya sudah dihapus secara soft delete. */
        if ($request->filled('tenaga_kerja')) {
            $tenagaKerja = User::withTrashed()->find($request->tenaga_kerja);
            if ($tenagaKerja) {
                $parts[] = 'Tenaga Kerja: ' . $tenagaKerja->name;
            }
        }

        /* Jika tidak menggunakan filter. */
        return empty($parts) ? 'Semua Periode' : implode(' | ', $parts);
    }
}
