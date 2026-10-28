<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JadwalPemeliharaan;
use App\Models\User;
use App\Models\StokOnderdil;
use App\Mail\JadwalPemeliharaanMail;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\JadwalPemeliharaanExport;

class JadwalPemeliharaanController extends Controller
{
    public function index()
    {
        $jadwalPemeliharaan = JadwalPemeliharaan::with('user')->get();
        $tenagaKerja = User::where('role', 'tenagakerja')->get();
        return view('supervisor.jadwal-pemeliharaan.index', compact('jadwalPemeliharaan', 'tenagaKerja'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'judul_pemeliharaan' => 'required|string|max:255',
            'tanggal_selesai' => 'required|date',
            'nama_tenaga_kerja' => 'required|exists:users,id',
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
        $data = $request->validate([
            'judul_pemeliharaan' => 'required|string|max:255',
            'tanggal_selesai' => 'required|date',
            'nama_tenaga_kerja' => 'required|exists:users,id',
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

        $jadwalPemeliharaan->update($data);
        return back()->with('sukses', 'Jadwal pemeliharaan berhasil diperbarui.');
    }

    public function destroy(JadwalPemeliharaan $jadwalPemeliharaan)
    {
        $jadwalPemeliharaan->delete();
        return back()->with('sukses', 'Jadwal pemeliharaan berhasil dihapus.');
    }

    public function verifikasiSelesai(JadwalPemeliharaan $jadwalPemeliharaan)
    {
        if ($jadwalPemeliharaan->status !== 'menunggu_verifikasi' || empty($jadwalPemeliharaan->detail_penyelesaian)) {
            return back()->withErrors(['error' => 'Tidak ada laporan penyelesaian yang dapat diverifikasi.']);
        }

        if (!empty($jadwalPemeliharaan->nama_onderdil) && !empty($jadwalPemeliharaan->jumlah_pemakaian)) {
            $stok = StokOnderdil::where('nama_sparepart', $jadwalPemeliharaan->nama_onderdil)->first();
            
            if ($stok) {
                if ($stok->stok_terbaru >= $jadwalPemeliharaan->jumlah_pemakaian) {
                    $stok->decrement('stok_terbaru', $jadwalPemeliharaan->jumlah_pemakaian);
                    $stok->increment('pemakaian', $jadwalPemeliharaan->jumlah_pemakaian);
                } else {
                    return back()->withErrors(['error' => 'Gagal verifikasi: Stok fisik saat ini tidak mencukupi untuk dipotong.']);
                }
            }
        }

        $jadwalPemeliharaan->update(['status' => 'selesai', 'keterangan_tolak' => null, 'alasan_penolakan' => null,]);

        return back()->with('sukses', 'Laporan berhasil diverifikasi. Stok dan pemakaian onderdil telah diperbarui.');
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

        /*
        * Kembalikan pekerjaan menjadi Pending.
        * Artinya pekerjaan belum dianggap selesai
        * dan tenaga kerja harus mengisi checklist kembali.
        */
        $jadwalPemeliharaan->update(['status' => 'pending',
            // Simpan alasan dari Supervisor
            'keterangan_tolak' => $data['keterangan_tolak'],
            'alasan_penolakan' => $data['alasan_penolakan'],

            // Hapus hasil penyelesaian sebelumnya
            // supaya tenaga kerja mengisi ulang data terbaru.
            'nama_onderdil' => null,
            'jumlah_pemakaian' => null,
            'detail_penyelesaian' => null,
            'gambar_bukti' => null,
            'bukti_pemakaian_onderdil' => null,
        ]);

        return back()->with('sukses', 'Hasil pemeliharaan berhasil ditolak oleh Supervisor. Tenaga kerja harus melakukan perbaikan dan mengirim ulang checklist.');
    }

    public function exportPdf(Request $request)
    {
        $jadwal = JadwalPemeliharaan::with('user')->get();
        $judul = 'Laporan Jadwal Pemeliharaan';
        $periode = 'Semua Data';

        $pdf = Pdf::loadView('supervisor.jadwal-pemeliharaan.pdf', compact('jadwal', 'judul', 'periode'))
                    ->setPaper('a4', 'landscape');

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