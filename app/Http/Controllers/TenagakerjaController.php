<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalPemeliharaan;
use App\Models\JadwalPengecekanOnderdil;
use App\Models\StokOnderdil;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TenagakerjaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $jenisTugas = $user->jenis_tugas;

        $jadwalPemeliharaan = collect();
        $jadwalPengecekan = collect();

        if (in_array($jenisTugas, ['pemeliharaan_peralatan', 'keduanya'])) {
            $jadwalPemeliharaan = JadwalPemeliharaan::where('nama_tenaga_kerja', $user->id)->orderBy('tanggal_selesai', 'desc')->get();
        }

        if (in_array($jenisTugas, ['pengecekan_onderdil', 'keduanya'])) {
            $jadwalPengecekan = JadwalPengecekanOnderdil::with('details')->where('tenaga_kerja_id', $user->id)->orderBy('tanggal_pengecekan', 'desc')->get();
        }

        $total = $jadwalPemeliharaan->count() + $jadwalPengecekan->count();
        $pending = $jadwalPemeliharaan->whereIn('status', ['pending', 'menunggu_verifikasi'])->count() + $jadwalPengecekan->whereIn('status', ['pending', 'menunggu_verifikasi'])->count();
        $selesai = $jadwalPemeliharaan->where('status', 'selesai')->count() + $jadwalPengecekan->where('status', 'selesai')->count();
        $ditolak = $jadwalPemeliharaan->where('status', 'ditolak')->count() + $jadwalPengecekan->where('status', 'ditolak')->count();

        $jadwalPemeliharaanAktif = $jadwalPemeliharaan->whereIn('status', ['pending', 'menunggu_verifikasi']);
        $jadwalPengecekanAktif = $jadwalPengecekan->whereIn('status', ['pending', 'menunggu_verifikasi']);

        return view('tenagakerja.dashboard', compact(
            'jenisTugas',
            'jadwalPemeliharaan',
            'jadwalPengecekan',
            'jadwalPemeliharaanAktif',
            'jadwalPengecekanAktif',
            'total',
            'pending',
            'selesai',
            'ditolak'
        ));
    }

    public function checklist()
    {
        $user = Auth::user();
        if (!in_array($user->jenis_tugas, ['pemeliharaan_peralatan', 'keduanya'])) {
            abort(403, 'Anda tidak memiliki akses ke jadwal pemeliharaan.');
        }

        $jadwal = JadwalPemeliharaan::with(['riwayatRevisi', 'pemakaianOnderdil'])->where('nama_tenaga_kerja', Auth::id())->get();
        $stokOnderdil = StokOnderdil::orderBy('nama_sparepart')->get();

        $events = $jadwal->map(function ($item) {
            /* Menghitung ketepatan waktu pemeliharaan menggunakan helper yang sudah dibuat. */
            $ketepatan = \App\Support\KetepatanJadwal::hitung($item, 'pemeliharaan');
            return [
                'id' => $item->id,
                'title' => $item->judul_pemeliharaan,
                'start' => $item->tanggal_selesai->format('Y-m-d'),
                'allDay' => true,
                'extendedProps' => [
                    'status' => $item->status ?? 'pending',
                    
                    /* Tanggal penting untuk menampilkan ketepatan waktu pekerjaan. */
                    'tanggal_target_awal' => $item->tanggal_target_awal ? \Carbon\Carbon::parse($item->tanggal_target_awal)->format('Y-m-d') : null,
                    'tanggal_kirim_hasil' => $item->tanggal_kirim_hasil ? \Carbon\Carbon::parse($item->tanggal_kirim_hasil)->format('Y-m-d H:i:s') : null,
                    'tanggal_verifikasi' => $item->tanggal_verifikasi ? \Carbon\Carbon::parse($item->tanggal_verifikasi)->format('Y-m-d H:i:s') : null,
                    
                    /* Hasil perhitungan dari helper.*/
                    'ketepatan_label' => $ketepatan['label'],
                    'ketepatan_warna' => $ketepatan['warna'],
                    'hari_terlambat' => $ketepatan['hari_terlambat'],
                             
                    /* Rincian seluruh revisi jadwal pemeliharaan. Diurutkan berdasarkan revisi paling awal. */
                    'riwayat_revisi_detail' => $item->riwayatRevisi->sortBy('id')->values()->map(function ($revisi) {
                        return [
                            'tanggal_lama' => $revisi->tanggal_lama ? \Carbon\Carbon::parse($revisi->tanggal_lama)->format('Y-m-d') : null,
                            'tanggal_baru' => $revisi->tanggal_baru ? \Carbon\Carbon::parse($revisi->tanggal_baru)->format('Y-m-d') : null,
                            'direvisi_pada' => $revisi->direvisi_pada ? \Carbon\Carbon::parse($revisi->direvisi_pada)->format('Y-m-d H:i:s') : null,
                            'hari_terlambat' => (int) $revisi->hari_terlambat_sebelum_revisi,
                            'alasan_revisi' => $revisi->alasan_revisi,
                        ];
                    })->all(),

                    /* Informasi riwayat revisi. */
                    'jumlah_revisi' => $ketepatan['jumlah_revisi'],
                    'pernah_terlambat_sebelum_revisi' => $ketepatan['pernah_terlambat_sebelum_revisi'],
                    'hari_terlambat_tercatat' => $ketepatan['hari_terlambat_tercatat'],
                    'nama_peralatan' => $item->nama_peralatan,
                    'lokasi' => $item->lokasi,
                    'quantity' => $item->quantity,
                    'serial_number' => $item->serial_number,
                    'kapasitas' => $item->kapasitas,
                    'merek' => $item->merek,
                    'tipe' => $item->tipe,
                    'tahun_pembuatan' => $item->tahun_pembuatan,
                    'keterangan' => $item->keterangan,
                    'gambar' => $item->gambar,
                    'nama_onderdil' => $item->nama_onderdil,
                    'jumlah_pemakaian' => $item->jumlah_pemakaian,
                    // Rincian beberapa jenis sparepart dalam satu pemeliharaan
                    'pemakaian_onderdil' => $item->pemakaianOnderdil
                        ->map(function ($pemakaian) {
                            return [
                                'nama' => $pemakaian->nama_sparepart_snapshot,
                                'jumlah' => $pemakaian->jumlah,
                            ];
                        })->values()->all(),
                    'detail_penyelesaian' => $item->detail_penyelesaian,
                    'gambar_bukti' => $item->gambar_bukti,
                    'bukti_pemakaian_onderdil' => $item->bukti_pemakaian_onderdil,
                    'keterangan_tolak' => $item->keterangan_tolak,
                    'alasan_penolakan' => $item->alasan_penolakan,
                    // Informasi untuk menghitung ketepatan waktu
                    'tanggal_target_awal' => \Carbon\Carbon::parse($item->tanggal_target_awal ?: $item->tanggal_selesai)->format('Y-m-d'),
                    'tanggal_kirim_hasil' => $item->tanggal_kirim_hasil ? \Carbon\Carbon::parse($item->tanggal_kirim_hasil)->format('Y-m-d H:i') : null,
                    'tanggal_verifikasi' => $item->tanggal_verifikasi ? \Carbon\Carbon::parse($item->tanggal_verifikasi)->format('Y-m-d H:i') : null,
                ],
            ];
        });

        return view('tenagakerja.checklist.index', compact('jadwal', 'events', 'stokOnderdil'));
    }

    public function pengecekanOnderdil()
    {
        /* =====================================================
        *       AMBIL TENAGA KERJA YANG SEDANG LOGIN
        * ===================================================== */
        $user = Auth::user();

        /* =====================================================
        *       BATASI AKSES BERDASARKAN JENIS TUGAS
        * =====================================================
        * Hanya Tenaga Kerja dengan jenis tugas:
        * - pengecekan_onderdil
        * - keduanya
        * yang boleh membuka halaman ini. */
        if (!in_array($user->jenis_tugas, ['pengecekan_onderdil', 'keduanya']))
        {
            abort(403, 'Anda tidak memiliki akses ke jadwal pengecekan sparepart.');
        }

        /* =====================================================
        *       AMBIL JADWAL MILIK TENAGA KERJA YANG LOGIN
        * ===================================================== */
        $jadwal = JadwalPengecekanOnderdil::with(['details.stokOnderdil', 'riwayatRevisi'])->where('tenaga_kerja_id', $user->id)->orderBy('tanggal_pengecekan', 'asc')->get();

        /* =====================================================
        *   UBAH DATA JADWAL MENJADI FORMAT EVENT FULLCALENDAR
        * ===================================================== */
        $events = $jadwal->map(function ($item) {
            /* Menghitung ketepatan waktu pengecekan berdasarkan tanggal target aktif. */
            $ketepatan = \App\Support\KetepatanJadwal::hitung($item, 'pengecekan');
            /* Daftar sparepart pada setiap jadwal. */
            $details = $item->details->map(function ($detail) {
                return [
                    'id' => $detail->id,
                    'nama_sparepart' => $detail->nama_sparepart_snapshot,
                    /* Stok terbaru dari Manajemen Stok.
                    * Ini BELUM menjadi stok_sistem_saat_cek.
                    * Snapshot baru disimpan saat Tenaga Kerja mengirim hasil pengecekan nanti. */
                    'stok_saat_ini' => $detail->stokOnderdil ? $detail->stokOnderdil->stok_terbaru : null,
                    /* Data hasil nantinya. Untuk sekarang kemungkinan masih null. */
                    'stok_sistem_saat_cek' => $detail->stok_sistem_saat_cek,
                    'stok_fisik' => $detail->stok_fisik,
                    'selisih' => $detail->selisih,
                    'kondisi' => $detail->kondisi,
                    'penyebab_selisih' => $detail->penyebab_selisih,
                    'keterangan_selisih' => $detail->keterangan_selisih,
                    'catatan_pengecekan' => $detail->catatan_pengecekan,
                    'gambar_bukti' => $detail->gambar_bukti,
                ];
            })->values();

            return [
                'id' => $item->id,
                'title' => $item->judul_pengecekan,
                'start' => $item->tanggal_pengecekan->format('Y-m-d'),
                'allDay' => true,
                /* Class untuk menentukan warna kalender. */
                'classNames' => ['status-' . $item->status],
                /* Data tambahan yang dibaca saat jadwal diklik. */
                'extendedProps' => [
                    'status' => $item->status,
                    'keterangan' => $item->keterangan,
                    'keterangan_tolak' => $item->keterangan_tolak,
                    'alasan_penolakan' => $item->alasan_penolakan,

                    /* Tanggal penting untuk evaluasi ketepatan waktu pengecekan. */
                    'tanggal_target_awal' => $item->tanggal_target_awal ? \Carbon\Carbon::parse($item->tanggal_target_awal)->format('Y-m-d') : null,
                    'tanggal_kirim_hasil' => $item->tanggal_kirim_hasil ? \Carbon\Carbon::parse($item->tanggal_kirim_hasil)->format('Y-m-d H:i:s') : null,
                    'tanggal_verifikasi' => $item->tanggal_verifikasi ? \Carbon\Carbon::parse($item->tanggal_verifikasi)->format('Y-m-d H:i:s') : null,
                    
                    /* Hasil perhitungan ketepatan waktu dari helper Laravel. */
                    'ketepatan_label' => $ketepatan['label'],
                    'ketepatan_warna' => $ketepatan['warna'],
                    'hari_terlambat' => $ketepatan['hari_terlambat'],
     
                    /* Rincian setiap revisi jadwal pengecekan. Diurutkan dari revisi paling awal. */
                    'riwayat_revisi_detail' => $item->riwayatRevisi->sortBy('id')->values()->map(function ($revisi) {
                        return [
                            'tanggal_lama' => $revisi->tanggal_lama ? \Carbon\Carbon::parse($revisi->tanggal_lama)->format('Y-m-d') : null,
                            'tanggal_baru' => $revisi->tanggal_baru ? \Carbon\Carbon::parse($revisi->tanggal_baru)->format('Y-m-d') : null,
                            'direvisi_pada' => $revisi->direvisi_pada ? \Carbon\Carbon::parse($revisi->direvisi_pada)->format('Y-m-d H:i:s') : null,
                            'hari_terlambat' => (int) $revisi->hari_terlambat_sebelum_revisi,
                            'alasan_revisi' => $revisi->alasan_revisi,
                        ];
                    })->all(),
                    
                    /* Informasi riwayat revisi tenggat. */
                    'jumlah_revisi' => $ketepatan['jumlah_revisi'],
                    'pernah_terlambat_sebelum_revisi' => $ketepatan['pernah_terlambat_sebelum_revisi'],
                    'hari_terlambat_tercatat' => $ketepatan['hari_terlambat_tercatat'],
                    
                    /* Daftar sparepart beserta data hasil pengecekan yang sudah ada. */
                    'details' => $details,
                ],
            ];
        })->values();

        return view('tenagakerja.pengecekan-onderdil.index', compact('jadwal', 'events'));
    }

    public function selesaiPengecekanOnderdil(Request $request, JadwalPengecekanOnderdil $jadwal)
    {
        $user = Auth::user();

        if ((int) $jadwal->tenaga_kerja_id !== (int) $user->id) {
            abort(403, 'Jadwal pengecekan ini bukan tugas Anda.');
        }

        if (!in_array($user->jenis_tugas, ['pengecekan_onderdil', 'keduanya'])) {
            abort(403, 'Anda tidak memiliki akses untuk melakukan pengecekan sparepart.');
        }

        if ($jadwal->status !== 'pending') {
            return back()->withErrors([
                'error' => 'Hasil pengecekan hanya dapat dikirim ketika status masih Pending.'
            ]);
        }

        $data = $request->validate([
            'detail_ids' => 'required|array|min:1',
            'detail_ids.*' => 'required|integer|distinct|exists:detail_pengecekan_onderdil,id',

            'stok_fisik' => 'required|array',
            'stok_fisik.*' => 'required|integer|min:0',

            'kondisi' => 'required|array',
            'kondisi.*' => 'required|in:Baik,Rusak',

            'penyebab_selisih' => 'nullable|array',
            'penyebab_selisih.*' => 'nullable|in:Rusak,Hilang,Kesalahan Pencatatan,Stok Ditemukan,Lainnya',

            'keterangan_selisih' => 'nullable|array',
            'keterangan_selisih.*' => 'nullable|string|max:1000',

            'catatan_pengecekan' => 'nullable|array',
            'catatan_pengecekan.*' => 'nullable|string|max:2000',

            'gambar_bukti' => 'nullable|array',
            'gambar_bukti.*' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:5120',
        ], [
            'stok_fisik.*.required' => 'Stok fisik setiap sparepart wajib diisi.',
            'stok_fisik.*.integer' => 'Stok fisik harus berupa angka.',
            'stok_fisik.*.min' => 'Stok fisik tidak boleh kurang dari 0.',

            'kondisi.*.required' => 'Kondisi setiap sparepart wajib dipilih.',
            'kondisi.*.in' => 'Kondisi sparepart tidak valid.',

            'gambar_bukti.*.image' => 'Bukti pengecekan harus berupa gambar.',
            'gambar_bukti.*.mimes' => 'Format bukti hanya boleh JPG, JPEG, PNG, atau GIF.',
            'gambar_bukti.*.max' => 'Ukuran bukti pengecekan maksimal 5 MB.',
        ]);

        $detailIdsJadwal = $jadwal->details()
            ->pluck('id')
            ->map(function ($id) {
                return (int) $id;
            })
            ->sort()
            ->values()
            ->all();

        $detailIdsDikirim = collect($data['detail_ids'])
            ->map(function ($id) {
                return (int) $id;
            })
            ->sort()
            ->values()
            ->all();

        /* Semua sparepart pada jadwal harus dikirim.
        * Tidak boleh hanya sebagian. */
        if ($detailIdsJadwal !== $detailIdsDikirim) {
            return back()->withErrors([
                'error' => 'Seluruh sparepart pada jadwal harus diisi sebelum hasil dikirim.'
            ])->withInput();
        }

        $uploadedPaths = [];
        try {
            DB::transaction(function () use (
                $request,
                $jadwal,
                $data,
                $detailIdsJadwal,
                &$uploadedPaths
            ) {
                /* Kunci jadwal agar tidak diproses dua kali
                * pada waktu bersamaan. */
                $jadwalTerkunci = JadwalPengecekanOnderdil::whereKey($jadwal->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($jadwalTerkunci->status !== 'pending') {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'error' => 'Jadwal pengecekan sudah diproses sebelumnya.'
                    ]);
                }

                $details = $jadwalTerkunci->details()
                    ->whereIn('id', $detailIdsJadwal)
                    ->get()
                    ->keyBy('id');

                foreach ($detailIdsJadwal as $detailId) {
                    $detail = $details->get($detailId);
                    if (!$detail) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'error' => 'Detail sparepart tidak ditemukan.'
                        ]);
                    }

                    /*
                    * Pastikan semua input tersedia.
                    */
                    if (!array_key_exists($detailId, $data['stok_fisik']) || !array_key_exists($detailId, $data['kondisi'])) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'error' => 'Seluruh hasil pengecekan sparepart wajib diisi.'
                        ]);
                    }

                    /* Ambil stok MASTER terbaru saat Tenaga Kerja
                    * benar-benar mengirim hasil.
                    * Angka dari browser TIDAK dipercaya sebagai snapshot. */
                    if (!$detail->stok_onderdil_id) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'error' => 'Data master sparepart ' . $detail->nama_sparepart_snapshot . ' sudah tidak tersedia.'
                        ]);
                    }

                    $stokMaster = StokOnderdil::whereKey($detail->stok_onderdil_id)
                        ->lockForUpdate()
                        ->first();

                    if (!$stokMaster) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'error' => 'Data stok ' . $detail->nama_sparepart_snapshot . ' tidak ditemukan.'
                        ]);
                    }

                    /* Snapshot stok sistem diambil SEKARANG. */
                    $stokSistemSaatCek = (int) $stokMaster->stok_terbaru;
                    $stokFisik = (int) $data['stok_fisik'][$detailId];

                    /* Rumus selisih:
                    * Stok Fisik - Stok Sistem */
                    $selisih = $stokFisik - $stokSistemSaatCek;

                    $penyebabSelisih = $data['penyebab_selisih'][$detailId] ?? null;
                    $keteranganSelisih = $data['keterangan_selisih'][$detailId] ?? null;
                    $catatanPengecekan = $data['catatan_pengecekan'][$detailId] ?? null;

                    /* Jika ada selisih, penyebab dan keterangan wajib. */
                    if ($selisih !== 0) {
                        if (empty($penyebabSelisih)) {
                            throw \Illuminate\Validation\ValidationException::withMessages([
                                'error' => 'Penyebab selisih untuk ' . $detail->nama_sparepart_snapshot . ' wajib dipilih.'
                            ]);
                        }

                        if (empty(trim((string) $keteranganSelisih))) {
                            throw \Illuminate\Validation\ValidationException::withMessages([
                                'error' => 'Keterangan selisih untuk ' . $detail->nama_sparepart_snapshot . ' wajib diisi.'
                            ]);
                        }
                    } else {
                        /* Jika tidak ada selisih, jangan simpan
                        * penyebab/keterangan yang tidak diperlukan. */
                        $penyebabSelisih = null;
                        $keteranganSelisih = null;
                    }

                    /* Upload bukti gambar. */
                    $gambarPath = null;
                    if ($request->hasFile('gambar_bukti.' . $detailId)) {
                        $file = $request->file('gambar_bukti.' . $detailId);
                        $folder = public_path('assets/img/bukti-pengecekan');

                        if (!file_exists($folder)) {
                            mkdir($folder, 0755, true);
                        }

                        $namaFile = 'pengecekan_' . $jadwalTerkunci->id . '_' . $detailId . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension(); $file->move($folder, $namaFile);
                        $gambarPath = 'assets/img/bukti-pengecekan/' . $namaFile;
                        $uploadedPaths[] = $gambarPath;
                    }

                    /* Simpan hasil masing-masing sparepart. */
                    $detail->update([
                        'stok_sistem_saat_cek' => $stokSistemSaatCek,
                        'stok_fisik' => $stokFisik,
                        'selisih' => $selisih,
                        'penyebab_selisih' => $penyebabSelisih,
                        'keterangan_selisih' => $keteranganSelisih,
                        'kondisi' => $data['kondisi'][$detailId],
                        'catatan_pengecekan' => $catatanPengecekan,
                        'gambar_bukti' => $gambarPath,
                    ]);
                }
                /* Hasil sudah dikirim ke Supervisor.
                * STOK MASTER BELUM DIUBAH. */
                $jadwalTerkunci->update([
                    'status' => 'menunggu_verifikasi',
                    'tanggal_kirim_hasil' => now('Asia/Jakarta'),
                    'tanggal_verifikasi' => null,
                    'keterangan_tolak' => null,
                    'alasan_penolakan' => null,
                ]);
            });
        } catch (\Throwable $e) {
            /* Jika transaksi gagal setelah ada file yang sempat
            * terunggah, hapus kembali file tersebut. */
            foreach ($uploadedPaths as $path) {
                $fullPath = public_path($path);
                if (file_exists($fullPath)) {
                    @unlink($fullPath);
                }
            }
            throw $e;
        }
        return redirect()
            ->route('tenagakerja.pengecekan-onderdil.index')
            ->with('sukses', 'Hasil pengecekan berhasil dikirim dan sedang menunggu verifikasi Supervisor.');
    }

    public function tolakPengecekanOnderdil(Request $request, JadwalPengecekanOnderdil $jadwal)
    {
        $user = Auth::user();
        if ((int) $jadwal->tenaga_kerja_id !== (int) $user->id) {
            abort(403, 'Jadwal pengecekan ini bukan tugas Anda.');
        }

        if (!in_array($user->jenis_tugas, ['pengecekan_onderdil', 'keduanya'])) {
            abort(403, 'Anda tidak memiliki akses untuk menolak tugas pengecekan sparepart.');
        }

        if ($jadwal->status !== 'pending') {
            return back()->withErrors([
                'error' => 'Jadwal pengecekan hanya dapat ditolak ketika status masih Pending.']);
        }

        $data = $request->validate([
            'keterangan_tolak' => 'required|string|max:1000',
            'alasan_penolakan' => 'required|string|max:2000',
        ], [
            'keterangan_tolak.required' => 'Keterangan penolakan wajib diisi.',
            'alasan_penolakan.required' => 'Alasan penolakan wajib diisi.',
        ]);

        DB::transaction(function () use ($jadwal, $data) {

            $jadwalTerkunci = JadwalPengecekanOnderdil::whereKey($jadwal->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($jadwalTerkunci->status !== 'pending') {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'error' => 'Jadwal pengecekan sudah diproses sebelumnya.'
                ]);
            }

            /* Pastikan tidak ada data hasil yang tertinggal.
            * Ini penting supaya Supervisor dapat membedakan:
            * - menunggu_verifikasi + stok_fisik ada
            *   = HASIL PENGECEKAN
            * - menunggu_verifikasi + stok_fisik kosong
            *   = PENOLAKAN TENAGA KERJA */
            $jadwalTerkunci->details()->update([
                'stok_sistem_saat_cek' => null,
                'stok_fisik' => null,
                'selisih' => null,
                'penyebab_selisih' => null,
                'keterangan_selisih' => null,
                'kondisi' => null,
                'catatan_pengecekan' => null,
                'gambar_bukti' => null,
            ]);

            $jadwalTerkunci->update([
                'status' => 'menunggu_verifikasi',
                'tanggal_kirim_hasil' => null,
                'tanggal_verifikasi' => null,
                'keterangan_tolak' => $data['keterangan_tolak'],
                'alasan_penolakan' => $data['alasan_penolakan'],
            ]);
        });

        return redirect()
            ->route('tenagakerja.pengecekan-onderdil.index')
            ->with('sukses', 'Penolakan tugas pengecekan berhasil dikirim dan sedang menunggu verifikasi Supervisor.');
    }

    public function selesai(Request $request, JadwalPemeliharaan $jadwal)
    {
        $data = $request->validate([
            'nama_onderdil' => 'nullable|string|max:255',
            'jumlah_pemakaian' => 'nullable|integer|min:1',
            'spareparts' => 'nullable|array|max:100',
            'spareparts.*' => 'required|array',
            'spareparts.*.stok_onderdil_id' => 'required|integer|distinct|exists:stok_onderdil,id',
            'spareparts.*.jumlah' => 'required|integer|min:1',
            'detail_penyelesaian' => 'required|string',
            'gambar_bukti' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'bukti_pemakaian_onderdil' => 'required_with:spareparts|image|mimes:jpeg,png,jpg,gif|max:5120',
        ], [
            'gambar_bukti.image' => 'File bukti penyelesaian harus berupa gambar.',
            'gambar_bukti.mimes' => 'Format file bukti penyelesaian hanya boleh JPG, JPEG, PNG, atau GIF.',
            'gambar_bukti.max' => 'Ukuran file bukti penyelesaian maksimal 5 MB.',
            'gambar_bukti.uploaded' => 'File bukti penyelesaian gagal diunggah. Pastikan ukuran file maksimal 5 MB.',
            'bukti_pemakaian_onderdil.image' => 'File bukti pemakaian onderdil harus berupa gambar.',
            'bukti_pemakaian_onderdil.mimes' => 'Format file bukti pemakaian onderdil hanya boleh JPG, JPEG, PNG, atau GIF.',
            'bukti_pemakaian_onderdil.max' => 'Ukuran file bukti pemakaian onderdil maksimal 5 MB.',
            'bukti_pemakaian_onderdil.uploaded' => 'File bukti pemakaian onderdil gagal diunggah. Pastikan ukuran file maksimal 5 MB.',
            'bukti_pemakaian_onderdil.required_with' => 'Bukti pemakaian sparepart wajib diunggah apabila menggunakan satu atau beberapa jenis sparepart.',
        ]);
     
        /* ============================================
        PEMERIKSAAN KETERSEDIAAN STOK SPAREPART
        ============================================ */
        // Ambil seluruh sparepart yang dipilih Tenaga Kerja
        $pemakaian = collect($data['spareparts'] ?? []);
        // Ambil data stok berdasarkan ID sparepart
        $stokDipilih = StokOnderdil::whereIn('id', $pemakaian->pluck('stok_onderdil_id') )->get()->keyBy('id');

        // Periksa setiap jenis sparepart
        foreach ($pemakaian as $item) {
            $stok = $stokDipilih->get((int) $item['stok_onderdil_id']);

            // Jika sparepart tidak ditemukan
            if (!$stok) {
                return back()
                    ->withErrors(['spareparts' => 'Sparepart yang dipilih tidak ditemukan.'])->withInput();
            }

            // Jika jumlah pemakaian melebihi stok tersedia
            if ((int) $item['jumlah'] > (int) $stok->stok_terbaru) {

                return back()
                    ->withErrors([
                        'spareparts' =>
                            'Stok ' . $stok->nama_sparepart . ' tidak mencukupi. ' .
                            'Stok tersedia: ' . $stok->stok_terbaru .
                            ' unit, sedangkan jumlah yang diminta: ' .
                            $item['jumlah'] . ' unit.'
                    ])->withInput();
            }
        }

        if ($request->hasFile('gambar_bukti')) {
            $image = $request->file('gambar_bukti');
            $imageName = time() . '_bukti.' . $image->getClientOriginalExtension();
            $image->move(public_path('assets/img'), $imageName);
            $data['gambar_bukti'] = 'assets/img/' . $imageName;
        }

        if ($request->hasFile('bukti_pemakaian_onderdil')) {
            $imageOnderdil = $request->file('bukti_pemakaian_onderdil');
            $imageNameOnderdil = time() . '_pemakaian.' . $imageOnderdil->getClientOriginalExtension();
            $imageOnderdil->move(public_path('assets/img'), $imageNameOnderdil);
            $data['bukti_pemakaian_onderdil'] = 'assets/img/' . $imageNameOnderdil;
        }
        
        // Pengajuan terbaru menggantikan catatan penolakan sebelumnya
        $data['keterangan_tolak'] = null;
        $data['alasan_penolakan'] = null;

        $data['tanggal_kirim_hasil'] = now('Asia/Jakarta');
        $data['tanggal_verifikasi'] = null;

        // UBAH STATUS MENJADI MENUNGGU VERIFIKASI
        $data['status'] = 'menunggu_verifikasi';
        
        /* ============================================
        SIMPAN CHECKLIST DAN RINCIAN MULTI-SPAREPART
        ============================================ */
        DB::transaction(function () use ($jadwal, $data, $pemakaian, $stokDipilih) {
            // Mengunci jadwal selama proses penyimpanan
            $jadwalTerkunci = JadwalPemeliharaan::whereKey($jadwal->id)
                ->lockForUpdate()
                ->firstOrFail();

            // Pastikan jadwal benar-benar milik tenaga kerja yang login
            if ((int) $jadwalTerkunci->nama_tenaga_kerja !== (int) Auth::id()) {
                abort(403, 'Jadwal pemeliharaan ini bukan tugas Anda.');
            }

            // Hanya jadwal Pending yang boleh mengirim checklist
            if ($jadwalTerkunci->status !== 'pending') {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'error' => 'Checklist hanya dapat dikirim ketika status jadwal Pending.'
                ]);
            }

            // Hapus rincian pengajuan sebelumnya, jika ada
            $jadwalTerkunci->pemakaianOnderdil()->delete();

            // Simpan setiap jenis sparepart sebagai baris tersendiri
            foreach ($pemakaian as $item) {
                $stok = $stokDipilih->get((int) $item['stok_onderdil_id']);

                if (!$stok) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'spareparts' => 'Sparepart yang dipilih sudah tidak tersedia.'
                    ]);
                }

                $jadwalTerkunci->pemakaianOnderdil()->create([
                    'stok_onderdil_id' => $stok->id,
                    'nama_sparepart_snapshot' => $stok->nama_sparepart,
                    'jumlah' => (int) $item['jumlah'],
                ]);
            }

            // Array spareparts bukan kolom pada tabel jadwal_pemeliharaan
            unset($data['spareparts']);

            // Jika menggunakan format multi-sparepart,
            // kosongkan kolom tunggal lama agar tidak terjadi duplikasi
            if ($pemakaian->isNotEmpty()) {
                $data['nama_onderdil'] = null;
                $data['jumlah_pemakaian'] = null;
            }

            // Simpan detail penyelesaian, bukti gambar dan status
            $jadwalTerkunci->update($data);
        });

        return back()->with('sukses', 'Laporan pemeliharaan berhasil dikirim. Menunggu verifikasi Supervisor.');
    }

    public function tolak(Request $request, JadwalPemeliharaan $jadwal)
    {
        $data = $request->validate([
            'keterangan_tolak' => 'required|string',
            'alasan_penolakan' => 'required|string',
        ]);

        // UBAH STATUS MENJADI MENUNGGU VERIFIKASI
        $data['status'] = 'menunggu_verifikasi';

        $data['tanggal_kirim_hasil'] = null;
        $data['tanggal_verifikasi'] = null;

        // Karena tenaga kerja memilih Tolak,
        // data penyelesaian tidak boleh ikut terbawa.
        $data['nama_onderdil'] = null;
        $data['jumlah_pemakaian'] = null;
        $data['detail_penyelesaian'] = null;
        $data['gambar_bukti'] = null;
        $data['bukti_pemakaian_onderdil'] = null;

        DB::transaction(function () use ($jadwal, $data) {
            // Kunci jadwal agar tidak diproses bersamaan
            $jadwalTerkunci = JadwalPemeliharaan::whereKey($jadwal->id)
                ->lockForUpdate()
                ->firstOrFail();

            // Pastikan jadwal milik Tenaga Kerja yang sedang login
            if ((int) $jadwalTerkunci->nama_tenaga_kerja !== (int) Auth::id()) {
                abort(403, 'Jadwal pemeliharaan ini bukan tugas Anda.');
            }

            // Penolakan hanya boleh dilakukan ketika status Pending
            if ($jadwalTerkunci->status !== 'pending') {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'error' => 'Jadwal hanya dapat ditolak ketika status Pending.'
                ]);
            }

            // Hapus rincian pemakaian yang belum disetujui Supervisor
            $jadwalTerkunci->pemakaianOnderdil()->delete();

            // Simpan alasan penolakan dan status Menunggu Verifikasi
            $jadwalTerkunci->update($data);
        });
        return back()->with('sukses', 'Pengajuan penolakan berhasil dikirim. Menunggu persetujuan Supervisor.');
    }

    public function stokOnderdil()
    {
        $stokOnderdil = StokOnderdil::orderBy('nama_sparepart')->get();
        return view('tenagakerja.stok-onderdil.index', compact('stokOnderdil'));
    }
}
