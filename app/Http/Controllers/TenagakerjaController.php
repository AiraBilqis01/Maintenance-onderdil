<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalPemeliharaan;
use App\Models\StokOnderdil;
use Illuminate\Support\Facades\Auth;

class TenagakerjaController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $jadwal = JadwalPemeliharaan::where('nama_tenaga_kerja', $userId)
                    ->orderBy('tanggal_selesai', 'desc')->get();

        $total   = $jadwal->count();
        $pending = $jadwal->where('status', 'pending')->count();
        $selesai = $jadwal->where('status', 'selesai')->count();
        $ditolak = $jadwal->where('status', 'ditolak')->count();

        return view('tenagakerja.dashboard', compact('jadwal', 'total', 'pending', 'selesai', 'ditolak'));
    }

    public function checklist()
    {
        $jadwal = JadwalPemeliharaan::where('nama_tenaga_kerja', Auth::id())->get();
        $stokOnderdil = StokOnderdil::orderBy('nama_sparepart')->get();

        $events = $jadwal->map(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->judul_pemeliharaan,
                'start' => $item->tanggal_selesai,
                'extendedProps' => [
                    'status' => $item->status ?? 'pending',
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
                    'detail_penyelesaian' => $item->detail_penyelesaian,
                    'gambar_bukti' => $item->gambar_bukti,
                    'bukti_pemakaian_onderdil' => $item->bukti_pemakaian_onderdil,
                    'keterangan_tolak' => $item->keterangan_tolak,
                    'alasan_penolakan' => $item->alasan_penolakan,
                ],
            ];
        });

        return view('tenagakerja.checklist.index', compact('jadwal', 'events', 'stokOnderdil'));
    }

    public function selesai(Request $request, JadwalPemeliharaan $jadwal)
    {
        $data = $request->validate([
            'nama_onderdil' => 'nullable|string|max:255',
            'jumlah_pemakaian' => 'nullable|integer|min:1',
            'tanggal_selesai' => 'required|date',
            'detail_penyelesaian' => 'required|string',
            'gambar_bukti' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'bukti_pemakaian_onderdil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ], [
            'gambar_bukti.image' => 'File bukti penyelesaian harus berupa gambar.',
            'gambar_bukti.mimes' => 'Format file bukti penyelesaian hanya boleh JPG, JPEG, PNG, atau GIF.',
            'gambar_bukti.max' => 'Ukuran file bukti penyelesaian maksimal 5 MB.',
            'gambar_bukti.uploaded' => 'File bukti penyelesaian gagal diunggah. Pastikan ukuran file maksimal 5 MB.',
            'bukti_pemakaian_onderdil.image' => 'File bukti pemakaian onderdil harus berupa gambar.',
            'bukti_pemakaian_onderdil.mimes' => 'Format file bukti pemakaian onderdil hanya boleh JPG, JPEG, PNG, atau GIF.',
            'bukti_pemakaian_onderdil.max' => 'Ukuran file bukti pemakaian onderdil maksimal 5 MB.',
            'bukti_pemakaian_onderdil.uploaded' => 'File bukti pemakaian onderdil gagal diunggah. Pastikan ukuran file maksimal 5 MB.',
        ]);

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

        // UBAH STATUS MENJADI MENUNGGU VERIFIKASI
        $data['status'] = 'menunggu_verifikasi';
        $jadwal->update($data);

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

        // Karena tenaga kerja memilih Tolak,
        // data penyelesaian tidak boleh ikut terbawa.
        $data['nama_onderdil'] = null;
        $data['jumlah_pemakaian'] = null;
        $data['detail_penyelesaian'] = null;
        $data['gambar_bukti'] = null;
        $data['bukti_pemakaian_onderdil'] = null;

        $jadwal->update($data);

        return back()->with('sukses', 'Pengajuan penolakan berhasil dikirim. Menunggu persetujuan Supervisor.');
    }

    public function stokOnderdil()
    {
        $stokOnderdil = StokOnderdil::orderBy('nama_sparepart')->get();
        return view('tenagakerja.stok-onderdil.index', compact('stokOnderdil'));
    }
}