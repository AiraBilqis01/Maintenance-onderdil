<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use App\Models\JadwalPemeliharaan;
use App\Models\RiwayatRevisiJadwal;
use App\Models\User;
use App\Models\StokOnderdil;
use App\Mail\JadwalPemeliharaanMail;
use App\Mail\HasilPemeliharaanDitolakMail;
use App\Mail\JadwalPemeliharaanDiperbaruiMail;
use App\Mail\PenugasanPemeliharaanDialihkanMail;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\JadwalPemeliharaanExport;

class JadwalPemeliharaanController extends Controller
{
    private function buildQuery(Request $request)
    {
        $query = JadwalPemeliharaan::with('user');
        /* Filter Tenaga Kerja */
        if ($request->filled('tenaga_kerja')) {
            $query->where('nama_tenaga_kerja', $request->tenaga_kerja);
        }

        /* Filter Dari Tanggal */
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_selesai', '>=', $request->tanggal_dari);
        }

        /* Filter Sampai Tanggal */
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_selesai', '<=', $request->tanggal_sampai);
        }

        /* Filter Status */
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        return $query;
    }
    public function index(Request $request)
    {
        $jadwalPemeliharaan = $this->buildQuery($request)->with(['riwayatRevisi', 'pemakaianOnderdil'])->orderBy('tanggal_selesai', 'desc')->get();
        $tenagaKerja = User::where('role', 'tenagakerja')->whereIn('jenis_tugas', [ 'pemeliharaan_peralatan', 'keduanya'])->orderBy('name', 'asc')->get();
        /* ============================================
        FILTER RIWAYAT TENAGA KERJA
        ============================================
        Mengambil akun aktif maupun yang di-Soft Delete.
        Hanya digunakan untuk filter riwayat jadwal. */
        $tenagaKerjaFilter = User::withTrashed()->where('role', 'tenagakerja')->orderBy('name', 'asc')->get();
        return view('supervisor.jadwal-pemeliharaan.index', compact('jadwalPemeliharaan', 'tenagaKerja', 'tenagaKerjaFilter'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'judul_pemeliharaan' => 'required|string|max:255',
            'tanggal_selesai' => 'required|date_format:Y-m-d|after_or_equal:' . now('Asia/Jakarta')->toDateString(),
            'nama_tenaga_kerja' => ['required', Rule::exists('users', 'id')->where('role', 'tenagakerja')->whereIn('jenis_tugas', ['pemeliharaan_peralatan', 'keduanya'])->whereNull('deleted_at'),],
            'nama_peralatan' => 'nullable|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'quantity' => 'nullable|integer|min:1',
            'serial_number' => 'nullable|string|max:255',
            'kapasitas' => 'nullable|string|max:255',
            'merek' => 'nullable|string|max:255',
            'tipe' => 'nullable|string|max:255',
            'tahun_pembuatan' => 'nullable|integer|min:1900|max:' . date('Y'),
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'keterangan' => 'nullable|string',
        ], [
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal hari ini.',
            'tanggal_selesai.date_format' => 'Format tanggal selesai tidak valid.',
            'gambar.image' => 'File yang dipilih harus berupa gambar.',
            'gambar.mimes' => 'Format gambar harus JPG, JPEG, PNG, atau GIF.',
            'gambar.max' => 'Ukuran gambar maksimal adalah 5 MB.',
        ]);

        // Pastikan quantity bernilai NULL jika tidak diisi
        $data['quantity'] = $data['quantity'] ?? null;

        if ($request->hasFile('gambar')) {
            $image = $request->file('gambar');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('assets/img'), $imageName);
            $data['gambar'] = 'assets/img/' . $imageName;
        }

        $data['tanggal_target_awal'] = $data['tanggal_selesai'];
        $data['status'] = 'pending';

        $jadwal = JadwalPemeliharaan::create($data);

        $tenagaKerja = User::find($data['nama_tenaga_kerja']);
        if ($tenagaKerja && $tenagaKerja->email) {
            Mail::to($tenagaKerja->email)->send(new JadwalPemeliharaanMail($jadwal->load('user')));
        }

        return back()->with('sukses', 'Jadwal pemeliharaan berhasil ditambahkan & email notifikasi terkirim.');
    }

    public function update(Request $request, JadwalPemeliharaan $jadwalPemeliharaan)
    {
        /* Jadwal hanya boleh diedit ketika status masih Pending. */
        if ($jadwalPemeliharaan->status !== 'pending') {
            return back()->withErrors(['error' => 'Jadwal pemeliharaan hanya dapat diedit ketika status masih Pending.']);
        }

        // Ambil tanggal target yang sekarang tersimpan
        $tanggalLama = \Carbon\Carbon::parse($jadwalPemeliharaan->tanggal_selesai)->format('Y-m-d');

        /* Periksa apakah Supervisor mengubah tanggal */
        $tanggalBerubah = $request->input('tanggal_selesai') !== $tanggalLama;

        // Tanggal lama boleh dipertahankan
        $aturanTanggal = 'required|date_format:Y-m-d';

        /* Jika tanggal berubah, tanggal baru tidak boleh lampau */
        if ($tanggalBerubah) {
            $aturanTanggal .= '|after_or_equal:' . now('Asia/Jakarta')->toDateString();
        }

        $data = $request->validate([
            'judul_pemeliharaan' => 'required|string|max:255',
            'tanggal_selesai' => $aturanTanggal,
            // Validasi alasan perubahan tenggat
            'alasan_revisi' => [ Rule::requiredIf($tanggalBerubah), 'nullable', 'string', 'min:10', 'max:1000',],
            'nama_tenaga_kerja' => ['required', Rule::exists('users', 'id')->where('role', 'tenagakerja')->whereIn('jenis_tugas', ['pemeliharaan_peralatan', 'keduanya'])->whereNull('deleted_at'),],
            'nama_peralatan' => 'nullable|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'quantity' => 'nullable|integer|min:1',
            'serial_number' => 'nullable|string|max:255',
            'kapasitas' => 'nullable|string|max:255',
            'merek' => 'nullable|string|max:255',
            'tipe' => 'nullable|string|max:255',
            'tahun_pembuatan' => 'nullable|integer|min:1900|max:' . date('Y'),
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'keterangan' => 'nullable|string',
        ], [
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal hari ini.',
            'tanggal_selesai.date_format' => 'Format tanggal selesai tidak valid.',
            'gambar.image' => 'File yang dipilih harus berupa gambar.',
            'gambar.mimes' => 'Format gambar harus JPG, JPEG, PNG, atau GIF.',
            'gambar.max' => 'Ukuran gambar maksimal adalah 5 MB.',
            'alasan_revisi.required' => 'Alasan revisi wajib diisi ketika tanggal pengecekan diubah.',
            'alasan_revisi.min' => 'Alasan revisi minimal 10 karakter.',
        ]);

        // Pastikan quantity bernilai NULL jika dikosongkan
        $data['quantity'] = $data['quantity'] ?? null;

        /* Pisahkan alasan revisi dari data jadwal utama.
        * Alasan revisi nantinya disimpan di tabel riwayat_revisi_jadwal. */
        $alasanRevisi = $data['alasan_revisi'] ?? null;
        unset($data['alasan_revisi']);

        if ($request->hasFile('gambar')) {
            $image = $request->file('gambar');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('assets/img'), $imageName);
            $data['gambar'] = 'assets/img/' . $imageName;
        }

        /* =====================================================
        *       CEK APAKAH BENAR-BENAR ADA PERUBAHAN
        * ===================================================== */
        /* Simpan Tenaga Kerja lama SEBELUM data jadwal diubah. */
        $tenagaKerjaLamaId = $jadwalPemeliharaan->nama_tenaga_kerja;
        $tenagaKerjaLama = User::withTrashed()->find($tenagaKerjaLamaId);

        /* fill() memasukkan data baru ke model tetapi BELUM menyimpan ke database. */
        $jadwalPemeliharaan->fill($data);

        /* isDirty() akan bernilai true jika terdapat data yang berbeda dengan data sebelumnya. */
        $adaPerubahan = $jadwalPemeliharaan->isDirty();

        /* Cek secara khusus apakah Tenaga Kerja berubah. */
        $tenagaKerjaBerubah = $jadwalPemeliharaan->isDirty('nama_tenaga_kerja');
        /* Jika Supervisor membuka Edit tetapi tidak mengubah data apa pun, email tidak perlu dikirim. */
        if (!$adaPerubahan) {
            return back()->with('sukses', 'Tidak ada perubahan pada jadwal pemeliharaan.');
        }

        /* =====================================================
        *                      SIMPAN PERUBAHAN
        * ===================================================== */
        DB::transaction(function () use ($jadwalPemeliharaan, $data, $tanggalLama, $tanggalBerubah, $alasanRevisi) {
            /* Kunci jadwal selama proses penyimpanan.
            * Mencegah perubahan tanggal bersamaan oleh lebih dari satu permintaan. */
            $jadwalTerkunci = JadwalPemeliharaan::whereKey($jadwalPemeliharaan->id)->lockForUpdate()->firstOrFail();
            /* Periksa kembali status dan tanggal yang saat ini tersimpan di database. */
            $tanggalDatabase = \Carbon\Carbon::parse($jadwalTerkunci->tanggal_selesai)->format('Y-m-d');

            if ($jadwalTerkunci->status !== 'pending' || $tanggalDatabase !== $tanggalLama) {
                throw ValidationException::withMessages([
                    'tanggal_selesai' => 'Jadwal sudah berubah. Muat ulang halaman sebelum mengedit.',
                ]);
            }

            /* LANGKAH 1:
            * Simpan perubahan jadwal utama. tanggal_target_awal tidak diubah. */
            $jadwalTerkunci->fill($data);
            $jadwalTerkunci->save();

            /* LANGKAH 2:
            * Simpan riwayat HANYA jika tanggal berubah. */
            if ($tanggalBerubah) {
                // Waktu revisi berdasarkan WIB
                $waktuRevisi = now('Asia/Jakarta');
                // Tenggat yang berlaku sebelum revisi
                $targetLama = \Carbon\Carbon::parse($tanggalLama, 'Asia/Jakarta')->startOfDay();

                /* Hitung jumlah hari keterlambatan berdasarkan target SEBELUM revisi.
                * Nilai 0 berarti belum terlambat. */
                $hariTerlambat = max(0, (int) $targetLama->diffInDays($waktuRevisi->copy()->startOfDay(), false));

                /* Catat seluruh informasi revisi. */
                RiwayatRevisiJadwal::create([
                    'jenis_jadwal' => 'pemeliharaan',
                    'jadwal_id' => $jadwalTerkunci->id,
                    'tanggal_lama' => $tanggalLama,
                    'tanggal_baru' => $data['tanggal_selesai'],
                    'alasan_revisi' => $alasanRevisi,
                    'supervisor_id' => auth()->id(),
                    'direvisi_pada' => $waktuRevisi,
                    'terlambat_sebelum_revisi' => $hariTerlambat > 0,
                    'hari_terlambat_sebelum_revisi' => $hariTerlambat,
                ]);
            }
        });

        /* Perbarui objek jadwal agar email menggunakan data terbaru yang sudah tersimpan. */
        $jadwalPemeliharaan->refresh();

        /* =====================================================
        *               EMAIL KE TENAGA KERJA LAMA
        *               JIKA PENUGASAN DIALIHKAN
        * ===================================================== */
        if ($tenagaKerjaBerubah && $tenagaKerjaLama && $tenagaKerjaLama->email) {
            Mail::to($tenagaKerjaLama->email)->send(new PenugasanPemeliharaanDialihkanMail($jadwalPemeliharaan, $tenagaKerjaLama));
        }

        /* =====================================================
        *           AMBIL TENAGA KERJA TERBARU
        * =====================================================
        * Penting jika Supervisor mengganti Tenaga Kerja pada jadwal. */
        $jadwalPemeliharaan->load('user');

        /* =====================================================
        *               KIRIM EMAIL PERUBAHAN JADWAL
        * ===================================================== */
        if ($jadwalPemeliharaan->user && $jadwalPemeliharaan->user->email) {
            Mail::to($jadwalPemeliharaan->user->email)->send(new JadwalPemeliharaanDiperbaruiMail ($jadwalPemeliharaan));
        }
        return back()->with('sukses', 'Jadwal pemeliharaan berhasil diperbarui dan email pemberitahuan perubahan telah dikirim kepada Tenaga Kerja.');
    }

    public function destroy(JadwalPemeliharaan $jadwalPemeliharaan)
    {
        /* Jadwal yang sedang menunggu verifikasi tidak boleh dihapus karena masih berada dalam proses persetujuan Supervisor. */
        if ($jadwalPemeliharaan->status === 'menunggu_verifikasi') {
            return back()->withErrors(['error' => 'Jadwal pemeliharaan yang sedang menunggu verifikasi tidak dapat dihapus.']);
        }

        /* Status yang boleh dihapus:
        * - pending
        * - ditolak
        * - selesai */
        $jadwalPemeliharaan->delete();
        return back()->with('sukses', 'Jadwal pemeliharaan berhasil dihapus.');
    }

    public function verifikasiSelesai(JadwalPemeliharaan $jadwalPemeliharaan)
    {
        DB::transaction(function () use ($jadwalPemeliharaan) {
            // 1. Kunci jadwal agar tidak di-ACC dua kali bersamaan
            $jadwal = JadwalPemeliharaan::whereKey($jadwalPemeliharaan->id)->lockForUpdate()->firstOrFail();

            // 2. Pastikan laporan layak diverifikasi
            if ($jadwal->status !== 'menunggu_verifikasi' || empty($jadwal->detail_penyelesaian)) {
                throw ValidationException::withMessages([
                    'error' => 'Tidak ada laporan penyelesaian yang dapat diverifikasi.'
                ]);
            }

            // 3. Ambil semua jenis sparepart dari checklist
            $daftarPemakaian = $jadwal->pemakaianOnderdil()->orderBy('stok_onderdil_id')->get();

            // Lindungi laporan lama yang belum tersalin ke tabel baru
            if ($daftarPemakaian->isEmpty() && !empty($jadwal->nama_onderdil) && (int) $jadwal->jumlah_pemakaian > 0) {
                throw ValidationException::withMessages([
                    'error' => 'Data pemakaian lama belum terhubung ke tabel rincian. Periksa data sebelum ACC.'
                ]);
            }

            // 4. Kumpulkan semua ID sparepart
            $idStok = $daftarPemakaian
                ->pluck('stok_onderdil_id')
                ->filter()
                ->unique()
                ->sort()
                ->values();

            // 5. Kunci stok agar tidak berubah saat proses ACC
            $stokTerkunci = StokOnderdil::whereIn('id', $idStok)
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            // 6. Periksa ketersediaan SELURUH sparepart
            foreach ($daftarPemakaian as $item) {
                $stok = $stokTerkunci->get((int) $item->stok_onderdil_id);
                if (!$stok) {
                    throw ValidationException::withMessages([
                        'error' => 'Sparepart ' . $item->nama_sparepart_snapshot . ' tidak ditemukan di Manajemen Stok.'
                    ]);
                }

                if ((int) $item->jumlah < 1 || (int) $stok->stok_terbaru < (int) $item->jumlah) {
                    throw ValidationException::withMessages([
                        'error' => 'Stok ' . $item->nama_sparepart_snapshot . ' tidak mencukupi untuk ACC.'
                    ]);
                }
            }

            // 7. Kurangi stok setiap jenis sparepart
            foreach ($daftarPemakaian as $item) {
                $stok = $stokTerkunci->get((int) $item->stok_onderdil_id);

                $jumlahDigunakan = (int) $item->jumlah;
                $stokSebelum = (int) $stok->stok_terbaru;

                // Simpan stok sebelum pemakaian
                $stok->stok_periode_sebelum = $stokSebelum;

                // Kurangi stok tersedia
                $stok->stok_terbaru = $stokSebelum - $jumlahDigunakan;

                // Tambahkan total pemakaian
                $stok->pemakaian = (int) $stok->pemakaian + $jumlahDigunakan;

                // Perbarui tanggal periode stok
                $stok->periode = now('Asia/Jakarta')->toDateString();
                $stok->save();
            }

            // 8. Ubah status jadwal setelah semua stok berhasil diproses
            $jadwal->update([
                'status' => 'selesai',
                'tanggal_verifikasi' => now('Asia/Jakarta'),
                'keterangan_tolak' => null,
                'alasan_penolakan' => null,
            ]);
        });

        return back()->with('sukses', 'Laporan berhasil diverifikasi. Pemakaian sparepart telah diproses.');
    }

    public function verifikasiTolak(JadwalPemeliharaan $jadwalPemeliharaan)
    {
        if ($jadwalPemeliharaan->status !== 'menunggu_verifikasi' || empty($jadwalPemeliharaan->alasan_penolakan) || !empty($jadwalPemeliharaan->detail_penyelesaian)) {
            return back()->withErrors(['error' => 'Tidak ada pengajuan penolakan tenaga kerja yang dapat diverifikasi.']);
        }

        $jadwalPemeliharaan->update(['status' => 'ditolak']);

        return back()->with('sukses', 'Laporan penolakan telah disetujui.');
    }

    public function tolakJadwal(Request $request, JadwalPemeliharaan $jadwalPemeliharaan)
    {
        $data = $request->validate([
            'keterangan_tolak' => 'required|string',
            'alasan_penolakan' => 'required|string',
        ]);

        // Hanya laporan pekerjaan yang sedang menunggu verifikasi
        // dan mempunyai detail penyelesaian yang boleh ditolak.
        if ($jadwalPemeliharaan->status !== 'menunggu_verifikasi' || empty($jadwalPemeliharaan->detail_penyelesaian)) {
            return back()->withErrors(['error' => 'Hanya hasil pemeliharaan yang menunggu verifikasi yang dapat ditolak.']);
        }

        /* ============================================
         PENOLAKAN HASIL PEMELIHARAAN MULTI-SPAREPART
        ============================================ */
        DB::transaction(function () use ($jadwalPemeliharaan, $data) {
            // Kunci jadwal selama proses penolakan
            $jadwalTerkunci = JadwalPemeliharaan::whereKey($jadwalPemeliharaan->id)->lockForUpdate()->firstOrFail();

            // Pastikan laporan masih menunggu verifikasi
            if ($jadwalTerkunci->status !== 'menunggu_verifikasi' || empty($jadwalTerkunci->detail_penyelesaian)) {
                throw ValidationException::withMessages([
                    'error' => 'Hanya hasil pemeliharaan yang menunggu verifikasi yang dapat ditolak.'
                ]);
            }

            // Hapus rincian sparepart dari pengajuan yang ditolak
            // Stok belum dipotong karena belum ACC
            $jadwalTerkunci->pemakaianOnderdil()->delete();

            // Kembalikan status ke Pending
            $jadwalTerkunci->update([
                'status' => 'pending',
                'tanggal_kirim_hasil' => null,
                'tanggal_verifikasi' => null,

                // Pertahankan alasan penolakan Supervisor
                'keterangan_tolak' => $data['keterangan_tolak'],
                'alasan_penolakan' => $data['alasan_penolakan'],

                // Kosongkan hasil checklist sebelumnya
                'nama_onderdil' => null,
                'jumlah_pemakaian' => null,
                'detail_penyelesaian' => null,
                'gambar_bukti' => null,
                'bukti_pemakaian_onderdil' => null,
            ]);
        });

        // Muat kembali data terbaru untuk email pemberitahuan
        $jadwalPemeliharaan->refresh();

        // Kirim email pemberitahuan kepada tenaga kerja
        $jadwalPemeliharaan->load('user');

        if ($jadwalPemeliharaan->user && $jadwalPemeliharaan->user->email) {
            Mail::to($jadwalPemeliharaan->user->email)->send(
                new HasilPemeliharaanDitolakMail($jadwalPemeliharaan));
        }

        return back()->with('sukses', 'Hasil pemeliharaan berhasil ditolak oleh Supervisor. Tenaga kerja harus melakukan perbaikan dan mengirim ulang checklist.');
    }

    public function exportPdf(Request $request)
    {
        /* Ambil data sesuai filter yang sedang digunakan. */
        $jadwal = $this->buildQuery($request)->orderBy('tanggal_selesai', 'desc')->get();
        $judul = 'Laporan Jadwal Pemeliharaan';

        /* Tentukan keterangan periode pada PDF. */
        if ($request->filled('tanggal_dari') && $request->filled('tanggal_sampai'))
        {
            $periode = \Carbon\Carbon::parse($request->tanggal_dari)->format('d/m/Y') . ' - ' .
                       \Carbon\Carbon::parse($request->tanggal_sampai)->format('d/m/Y');
        } elseif ($request->filled('tanggal_dari')) {
            $periode = 'Mulai ' . \Carbon\Carbon::parse($request->tanggal_dari)->format('d/m/Y');
        } elseif ($request->filled('tanggal_sampai')) {
            $periode = 'Sampai ' . \Carbon\Carbon::parse($request->tanggal_sampai)->format('d/m/Y');
        } else {
            $periode = 'Semua Data';
        }

        /* Buat PDF. */
        $pdf = Pdf::loadView('supervisor.jadwal-pemeliharaan.pdf', compact('jadwal', 'judul', 'periode'))->setPaper('a4', 'landscape');

        /* Download PDF. */
        return $pdf->download('laporan-jadwal-pemeliharaan-' . date('Ymd-His') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(
            new JadwalPemeliharaanExport($request),
            'laporan-jadwal-pemeliharaan-' . date('Ymd-His') . '.xlsx'
        );
    }
}
