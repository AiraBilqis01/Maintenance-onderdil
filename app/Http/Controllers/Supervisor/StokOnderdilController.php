<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JadwalPemeliharaan;
use App\Models\User;
use App\Models\StokOnderdil;
use App\Mail\JadwalPemeliharaanMail;
use Illuminate\Support\Facades\Mail;

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
            'nama_peralatan' => 'required|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'quantity' => 'required|integer|min:1',
            'serial_number' => 'nullable|string|max:255',
            'kapasitas' => 'nullable|string|max:255',
            'merek' => 'nullable|string|max:255',
            'tipe' => 'nullable|string|max:255',
            'tahun_pembuatan' => 'nullable|integer|min:1900|max:' . date('Y'),
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'keterangan' => 'nullable|string',
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
            'nama_peralatan' => 'required|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'quantity' => 'required|integer|min:1',
            'serial_number' => 'nullable|string|max:255',
            'kapasitas' => 'nullable|string|max:255',
            'merek' => 'nullable|string|max:255',
            'tipe' => 'nullable|string|max:255',
            'tahun_pembuatan' => 'nullable|integer|min:1900|max:' . date('Y'),
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'keterangan' => 'nullable|string',
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
        if ($jadwalPemeliharaan->status !== 'menunggu_verifikasi') {
            return back()->withErrors(['error' => 'Status tidak valid untuk diverifikasi.']);
        }

        if (!empty($jadwalPemeliharaan->nama_onderdil) && !empty($jadwalPemeliharaan->jumlah_pemakaian)) {
            $stok = StokOnderdil::where('nama_sparepart', $jadwalPemeliharaan->nama_onderdil)->first();
            
            if ($stok) {
                if ($stok->stok_terbaru >= $jadwalPemeliharaan->jumlah_pemakaian) {
                    // Kurangi stok terbaru
                    $stok->decrement('stok_terbaru', $jadwalPemeliharaan->jumlah_pemakaian);
                    
                    // TAMBAHKAN PEMAKAIAN
                    $stok->increment('pemakaian', $jadwalPemeliharaan->jumlah_pemakaian);
                } else {
                    return back()->withErrors(['error' => 'Gagal verifikasi: Stok fisik saat ini tidak mencukupi untuk dipotong.']);
                }
            }
        }

        $jadwalPemeliharaan->update(['status' => 'selesai']);

        return back()->with('sukses', 'Laporan berhasil diverifikasi. Stok dan pemakaian onderdil telah diperbarui.');
    }

    public function verifikasiTolak(JadwalPemeliharaan $jadwalPemeliharaan)
    {
        if ($jadwalPemeliharaan->status !== 'menunggu_verifikasi') {
            return back()->withErrors(['error' => 'Status tidak valid untuk diverifikasi.']);
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

        if ($jadwalPemeliharaan->status !== 'pending') {
            return back()->withErrors(['error' => 'Hanya jadwal dengan status Pending yang bisa ditolak.']);
        }

        $data['status'] = 'ditolak';
        $jadwalPemeliharaan->update($data);

        return back()->with('sukses', 'Jadwal pemeliharaan berhasil dibatalkan/ditolak oleh Supervisor.');
    }
}