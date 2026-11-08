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
        $data = $request->validate([
            'nama_sparepart' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'stok_periode_sebelum' => 'required|integer|min:0',
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

        $stokBerubah = (int) $stokOnderdil->stok_periode_sebelum !== (int) $data['stok_periode_sebelum'] || (int) $stokOnderdil->pemakaian !== (int) $data['pemakaian'] || (int) $stokOnderdil->stok_terbaru !== (int) $data['stok_terbaru'] || $stokOnderdil->kondisi_sekarang !== $data['kondisi_sekarang'];

        if ($stokBerubah) {
            $data['periode'] = now()->toDateString();
        }

        $stokOnderdil->update($data);
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

            /* 3. Lindungi data pemeliharaan lama yang masih menggunakan nama_onderdil. */
            $pemeliharaanLama = JadwalPemeliharaan::withTrashed()
                ->where('nama_onderdil', $stok->nama_sparepart)
                ->where('jumlah_pemakaian', '>', 0);

            $pemeliharaanLamaAktif = (clone $pemeliharaanLama)
                ->where('status', '!=', 'selesai')
                ->exists();

            /* 4. Jika masih digunakan pekerjaan yang belum selesai, penghapusan ditolak. */
            if ($pemeliharaanAktif || $pengecekanAktif || $pemeliharaanLamaAktif) {
                return [
                    'error' => 'Sparepart "' . $stok->nama_sparepart .
                                '" masih terkait dengan jadwal yang belum selesai. ' .
                                'Selesaikan atau hapus penugasan terkait terlebih dahulu.'
                ];
            }

            /* 5. Periksa apakah onderdil pernah terkait dengan pemeliharaan atau pengecekan. */
            $punyaRiwayat = $stok->pemakaianPemeliharaan()->exists() || $stok->detailPengecekan()->exists() || (clone $pemeliharaanLama)->exists();

            /* 6. Memiliki riwayat selesai:
            * Soft Delete / Arsipkan. */
            if ($punyaRiwayat) {
                $stok->delete();
                return [
                    'sukses' => 'Onderdil "' . $stok->nama_sparepart .
                                '" berhasil diarsipkan. Riwayat pemeliharaan ' .
                                'dan pengecekan tetap dipertahankan.'
                ];
            }

            /* 7. Tidak pernah terkait dengan jadwal:
            * Hapus permanen. */
            $stok->forceDelete();
            return [
                'sukses' => 'Onderdil "' . $stok->nama_sparepart .
                            '" berhasil dihapus permanen karena belum memiliki riwayat.'
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
