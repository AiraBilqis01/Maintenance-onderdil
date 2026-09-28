<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JadwalPemeliharaan;
use App\Models\User;

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

        JadwalPemeliharaan::create($data);
        return back()->with('sukses', 'Jadwal pemeliharaan berhasil ditambahkan.');
    }

    public function update(Request $request, JadwalPemeliharaan $jadwalPemeliharaan)
    {
        $data = $request->validate([
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
}