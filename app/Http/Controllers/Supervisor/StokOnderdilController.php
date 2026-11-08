<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StokOnderdil;
use App\Models\JadwalPemeliharaan;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StokOnderdilExport;

class StokOnderdilController extends Controller
{
    private function buildQuery(Request $request)
    {
        $query = StokOnderdil::query();

        if ($request->filled('search')) {
            $query->where('nama_sparepart', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('kondisi_stok')) {
            if ($request->kondisi_stok == 'menipis') {
                $query->where('stok_terbaru', '<', 10);
            } elseif ($request->kondisi_stok == 'aman') {
                $query->where('stok_terbaru', '>=', 10);
            }
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('periode', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('periode', $request->tahun);
        }

        return $query;
    }

    public function index(Request $request)
    {
        $stokOnderdil = $this->buildQuery($request)->get();
        return view('supervisor.stok-onderdil.index', compact('stokOnderdil'));
    }

    public function exportPdf(Request $request)
    {
        $stokOnderdil = $this->buildQuery($request)->get();
        $judul = 'Laporan Stok Onderdil';
        $parts = [];
        
        if ($request->filled('search')) {
            $parts[] = 'Pencarian: "' . $request->search . '"';
        }
        if ($request->filled('kondisi_stok')) {
            $kondisiText = ['menipis' => 'Stok Menipis', 'aman' => 'Stok Aman',];
            $parts[] = 'Kondisi Stok: ' . ($kondisiText[$request->kondisi_stok] ?? $request->kondisi_stok);
        }

        if ($request->filled('bulan')) {
            $daftarBulan = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
            ];
            $parts[] = 'Bulan: ' . ($daftarBulan[(int) $request->bulan] ?? $request->bulan);
        }

        if ($request->filled('tahun')) {
            $parts[] = 'Tahun: ' . $request->tahun;
        }

        $periode = empty($parts) ? 'Semua Data' : implode(' | ', $parts);
        $pdf = Pdf::loadView('supervisor.stok-onderdil.pdf', compact('stokOnderdil', 'judul', 'periode'))
                    ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-stok-onderdil-' . date('Ymd-His') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(
            new StokOnderdilExport($request),
            'laporan-stok-onderdil-' . date('Ymd-His') . '.xlsx'
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_sparepart' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'stok_periode_sebelum' => 'required|integer|min:0',
            'periode' => 'required|date',
            'kondisi_sekarang' => 'required|string|max:255',
            'pemakaian' => 'required|integer|min:0',
            'stok_terbaru' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
        ], [
            'gambar.image' => 'File yang dipilih harus berupa gambar.',
            'gambar.mimes' => 'Format gambar harus JPG, JPEG, PNG, atau GIF.',
            'gambar.max' => 'Ukuran gambar maksimal adalah 5 MB.',
        ]);

        if ($request->hasFile('gambar')) {
            $image = $request->file('gambar');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('assets/img'), $imageName);
            $data['gambar'] = 'assets/img/' . $imageName;
        }

        StokOnderdil::create($data);
        return back()->with('sukses', 'Stok onderdil berhasil ditambahkan.');
    }

    
    public function update(Request $request, StokOnderdil $stokOnderdil)
    {
        // 1. Validasi input edit dan data pembanding.
        $data = $request->validate([
            'nama_sparepart' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'stok_periode_sebelum' => 'required|integer|min:0',
            'kondisi_sekarang' => 'required|string|max:255',
            'pemakaian' => 'required|integer|min:0',
            'stok_terbaru' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',

            // Nilai awal dari formulir Edit Stok.
            'stok_periode_sebelum_awal' => 'required|integer|min:0',
            'pemakaian_awal' => 'required|integer|min:0',
            'stok_terbaru_awal' => 'required|integer|min:0',
        ], [
            'gambar.image' => 'File yang dipilih harus berupa gambar.',
            'gambar.mimes' => 'Format gambar harus JPG, JPEG, PNG, atau GIF.',
            'gambar.max' => 'Ukuran gambar maksimal adalah 5 MB.',
        ]);

        // Catat gambar baru agar bisa dibersihkan jika gagal.
        $uploadedPath = null;

        try {
            DB::transaction(function () use ($request, $stokOnderdil, $data, &$uploadedPath) {
                /* 2. AMBIL ULANG DAN KUNCI DATA STOK
                * Mencegah perubahan bersamaan dengan proses ACC Supervisor. */
                $stok = StokOnderdil::whereKey($stokOnderdil->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                /* 3. PERIKSA APAKAH DATA SUDAH BERUBAH
                * Bandingkan nilai awal formulir dengan nilai terbaru yang ada di database. */
                $dataSudahBerubah =
                    (int) $stok->stok_periode_sebelum !== (int) $data['stok_periode_sebelum_awal'] ||
                    (int) $stok->pemakaian !== (int) $data['pemakaian_awal'] ||
                    (int) $stok->stok_terbaru !== (int) $data['stok_terbaru_awal'];

                if ($dataSudahBerubah) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'error' =>
                            'Data stok "' . $stok->nama_sparepart .
                            '" telah berubah sejak formulir Edit dibuka. ' .
                            'Perubahan mungkin berasal dari ACC pemeliharaan ' .
                            'atau pembaruan stok lainnya. ' .
                            'Silakan muat ulang halaman dan periksa ' .
                            'data stok terbaru sebelum menyimpan.'
                    ]);
                }

                /* 4. HAPUS INPUT PEMBANDING
                * Ketiga input tersembunyi bukan merupakan kolom tabel stok_onderdil. */
                unset(
                    $data['stok_periode_sebelum_awal'],
                    $data['pemakaian_awal'],
                    $data['stok_terbaru_awal']
                );

                /* 5. PERIKSA PERUBAHAN DATA STOK
                * Logika lama tetap dipertahankan. */
                $stokBerubah =
                    (int) $stok->stok_periode_sebelum !== (int) $data['stok_periode_sebelum'] ||
                    (int) $stok->pemakaian !== (int) $data['pemakaian'] ||
                    (int) $stok->stok_terbaru !== (int) $data['stok_terbaru'] || 
                    $stok->kondisi_sekarang !== $data['kondisi_sekarang'];

                // Perbarui periode jika data stok berubah.
                if ($stokBerubah) {
                    $data['periode'] = now()->toDateString();
                }

                /* 6. UPLOAD GAMBAR JIKA ADA
                * Dilakukan setelah data dinyatakan belum berubah sejak formulir dibuka. */
                if ($request->hasFile('gambar')) {
                    $image = $request->file('gambar');
                    $imageName = 'stok_' . $stok->id . '_' . \Illuminate\Support\Str::uuid() . '.' . strtolower($image->getClientOriginalExtension());
                    $image->move(public_path('assets/img'), $imageName);
                    $uploadedPath = 'assets/img/' . $imageName;
                    $data['gambar'] = $uploadedPath;
                } else {
                    // Pertahankan gambar sebelumnya.
                    unset($data['gambar']);
                }

                /* 7. SIMPAN PERUBAHAN STOK
                * Data tetap terkunci sampai transaksi selesai. */
                $stok->update($data);
            });
        } catch (\Throwable $e) {
            /* 8. BERSIHKAN GAMBAR BARU JIKA GAGAL
            * Jangan menghapus gambar lama. */
            if ($uploadedPath !== null) {
                $fullPath = public_path($uploadedPath);
                if (is_file($fullPath)) {
                    @unlink($fullPath);
                }
            }
            throw $e;
        }

        return back()->with('sukses', 'Stok onderdil berhasil diperbarui.');
    }

    public function destroy(StokOnderdil $stokOnderdil)
    {
        $hasil = DB::transaction(function () use ($stokOnderdil) {
            // Kunci data stok selama pemeriksaan dan penghapusan.
            $stok = StokOnderdil::whereKey($stokOnderdil->id)
                ->lockForUpdate()
                ->firstOrFail();

            /* 1. Periksa hubungan dengan Jadwal Pemeliharaan.
            * Termasuk jadwal yang sudah masuk arsip. */
            $pemeliharaanAktif = $stok->pemakaianPemeliharaan()
                ->whereHas('jadwalPemeliharaan', function ($query) {
                    $query->withTrashed()->where('status', '!=', 'selesai');
                })->exists();

            /* 2. Periksa hubungan dengan Jadwal Pengecekan.
            * Termasuk jadwal yang sudah masuk arsip. */
            $pengecekanAktif = $stok->detailPengecekan()
                ->whereHas('jadwal', function ($query) {
                    $query->withTrashed()->where('status', '!=', 'selesai');
                })->exists();

            /* 3. PERIKSA RIWAYAT PEMELIHARAAN LAMA
            * Data lama mungkin masih memakai nama_onderdil.
            * Hanya periksa jadwal yang belum mempunyai rincian pemakaian dengan stok_onderdil_id yang sudah terhubung.
            * Riwayat yang sudah memiliki ID stok akan diperiksa melalui relasi pemakaianPemeliharaan(). */
            $pemeliharaanLama = JadwalPemeliharaan::withTrashed()
                ->where('nama_onderdil', $stok->nama_sparepart)
                ->where('jumlah_pemakaian', '>', 0)
                ->whereDoesntHave('pemakaianOnderdil', function ($query) {
                    $query->whereNotNull('stok_onderdil_id');
                });

            /* PERIKSA NAMA SPAREPART GANDA
            * Jika terdapat riwayat lama yang belum terhubung dan lebih dari satu stok memiliki nama sama, sistem tidak boleh menebak stok mana yang digunakan. */
            $adaRiwayatBelumTerhubung = (clone $pemeliharaanLama)->exists();
            if ($adaRiwayatBelumTerhubung) {
                // Termasuk stok yang sudah diarsipkan.
                $adaNamaSama = StokOnderdil::withTrashed()
                    ->where('nama_sparepart', $stok->nama_sparepart)
                    ->where('id', '!=', $stok->id)
                    ->exists();

                if ($adaNamaSama) {
                    return ['error' =>
                            'Sparepart "' . $stok->nama_sparepart .
                            '" memiliki nama yang sama dengan data stok lain, ' .
                            'sementara masih terdapat riwayat pemeliharaan lama ' .
                            'yang belum terhubung ke ID stok. ' .
                            'Penghapusan atau pengarsipan dibatalkan untuk ' .
                            'mencegah kesalahan hubungan riwayat. ' .
                            'Periksa dan cocokkan data riwayat terlebih dahulu.'
                    ];
                }
            }

            /* Jika nama tidak ambigu, pemeriksaan status jadwal lama tetap dilakukan. */
            $pemeliharaanLamaAktif = (clone $pemeliharaanLama)->where('status', '!=', 'selesai')->exists();

            /* 4. Jika masih digunakan pekerjaan yang belum selesai, penghapusan ditolak. */
            if ($pemeliharaanAktif || $pengecekanAktif || $pemeliharaanLamaAktif) {
                return ['error' => 'Sparepart "' . $stok->nama_sparepart .
                                    '" masih terkait dengan jadwal yang belum selesai. ' .
                                    'Selesaikan atau hapus penugasan terkait terlebih dahulu.'
                ];
            }
   
            /* 5. PERIKSA HUBUNGAN DAN RIWAYAT ONDERDIL
            * Angka pemakaian pada Manajemen Stok tidak otomatis dianggap sebagai riwayat pekerjaan. */
            $punyaRiwayat = $stok->pemakaianPemeliharaan()->exists() || $stok->detailPengecekan()->exists() || (clone $pemeliharaanLama)->exists();
            
            /* 6. JIKA MEMILIKI RIWAYAT
            * Jangan hapus permanen.
            * Arsipkan hanya jika stok sudah 0 unit. */
            if ($punyaRiwayat) {
                if ((int) $stok->stok_terbaru > 0) {
                    return ['error' => 'Sparepart "' . $stok->nama_sparepart .
                            '" memiliki riwayat pekerjaan dan masih ' .
                            'tercatat memiliki stok sebanyak ' .
                            $stok->stok_terbaru . ' unit. ' .
                            'Selesaikan persediaan fisik terlebih dahulu ' .
                            'sebelum mengarsipkan data.'
                    ];
                }

                $stok->delete();

                return ['sukses' => 'Sparepart "' . $stok->nama_sparepart .
                        '" berhasil diarsipkan. ' .
                        'Riwayat pemeliharaan dan pengecekan ' .
                        'tetap dipertahankan.'
                ];
            }

            /* 7. JIKA TIDAK MEMILIKI RIWAYAT
            * Wajib ada konfirmasi khusus dari Supervisor
            * sebelum melakukan penghapusan permanen. */
            if (!request()->boolean('konfirmasi_hapus_salah_input')) {
                return ['error' => 'Penghapusan permanen dibatalkan. ' .
                        'Supervisor harus mengonfirmasi bahwa ' .
                        'data ini merupakan kesalahan input dan ' .
                        'bukan catatan persediaan fisik sebenarnya.'
                ];
            }

            /* 8. HAPUS PERMANEN DATA SALAH INPUT
            * Hanya dilakukan jika:
            * - Tidak terkait jadwal aktif.
            * - Tidak memiliki riwayat pekerjaan.
            * - Supervisor memberikan konfirmasi khusus.
            * Angka stok dan pemakaian tidak menjadi penghalang untuk koreksi data salah input. */
            $stok->forceDelete();
            return ['sukses' => 'Onderdil "' . $stok->nama_sparepart .
                    '" berhasil dihapus permanen karena ' .
                    'dikonfirmasi sebagai kesalahan input ' .
                    'dan tidak memiliki riwayat pekerjaan.'
            ];
        });

        if (isset($hasil['error'])) {
            return back()->withErrors(['error' => $hasil['error']]);
        }
        return back()->with('sukses', $hasil['sukses']);
    }
    
    /* =====================================================
     * ARSIP MANAJEMEN STOK
     * =====================================================
     * Menampilkan seluruh onderdil yang sudah diarsipkan menggunakan Soft Delete. */
    public function arsip()
    {
        $arsip = StokOnderdil::onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->get();

        return view(
            'supervisor.stok-onderdil.arsip', compact('arsip')
        );
    }

    /* =====================================================
     * PULIHKAN ONDERDIL DARI ARSIP
     * =====================================================
     * Mengembalikan onderdil ke Manajemen Stok tanpa mengubah jumlah stok atau riwayat. */
    public function restore($id)
    {
        DB::transaction(function () use ($id) {
            // Ambil hanya onderdil yang diarsipkan.
            $stok = StokOnderdil::onlyTrashed()
                ->whereKey($id)
                ->lockForUpdate()
                ->firstOrFail();

            // Pulihkan data onderdil.
            $stok->restore();
        });

        return redirect()
            ->route('supervisor.stok-onderdil.arsip')
            ->with('sukses', 'Onderdil berhasil dipulihkan ke Manajemen Stok tanpa mengubah jumlah stok.');
    }
}
