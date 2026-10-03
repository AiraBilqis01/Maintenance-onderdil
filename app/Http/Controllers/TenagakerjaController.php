<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalPemeliharaan;
use Illuminate\Support\Facades\Auth;

class TenagakerjaController extends Controller
{
    public function index()
    {
        return view('tenagakerja.dashboard');
    }

    public function checklist()
{
    $jadwal = JadwalPemeliharaan::where('nama_tenaga_kerja', Auth::id())->get();

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
                'detail_penyelesaian' => $item->detail_penyelesaian,
                'gambar_bukti' => $item->gambar_bukti,
                'keterangan_tolak' => $item->keterangan_tolak,
                'alasan_penolakan' => $item->alasan_penolakan,
            ],
        ];
    });

    return view('tenagakerja.checklist.index', compact('jadwal', 'events'));
}

    public function selesai(Request $request, JadwalPemeliharaan $jadwal)
    {
        $data = $request->validate([
            'nama_onderdil' => 'nullable|string|max:255',
            'tanggal_selesai' => 'required|date',
            'detail_penyelesaian' => 'required|string',
            'gambar_bukti' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('gambar_bukti')) {
            $image = $request->file('gambar_bukti');
            $imageName = time() . '_bukti.' . $image->getClientOriginalExtension();
            $image->move(public_path('assets/img'), $imageName);
            $data['gambar_bukti'] = 'assets/img/' . $imageName;
        }

        $data['status'] = 'selesai';
        $jadwal->update($data);

        return back()->with('sukses', 'Pemeliharaan berhasil diselesaikan.');
    }

    public function tolak(Request $request, JadwalPemeliharaan $jadwal)
    {
        $data = $request->validate([
            'keterangan_tolak' => 'required|string',
            'alasan_penolakan' => 'required|string',
        ]);

        $data['status'] = 'ditolak';
        $jadwal->update($data);

        return back()->with('sukses', 'Pemeliharaan berhasil ditolak.');
    }
}