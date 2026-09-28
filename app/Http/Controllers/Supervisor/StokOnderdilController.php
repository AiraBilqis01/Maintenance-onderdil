<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StokOnderdil;
use Illuminate\Support\Facades\Storage;

class StokOnderdilController extends Controller
{
    public function index()
    {
        $stokOnderdil = StokOnderdil::all();
        return view('supervisor.stok-onderdil.index', compact('stokOnderdil'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_sparepart' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'stok_periode_sebelum' => 'required|integer|min:0',
            'periode' => 'required|date',
            'kondisi_sekarang' => 'required|string|max:255',
            'pemakaian' => 'required|integer|min:0',
            'stok_terbaru' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
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
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'stok_periode_sebelum' => 'required|integer|min:0',
            'periode' => 'required|date',
            'kondisi_sekarang' => 'required|string|max:255',
            'pemakaian' => 'required|integer|min:0',
            'stok_terbaru' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
        ]);

        if ($request->hasFile('gambar')) {
            $image = $request->file('gambar');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('assets/img'), $imageName);
            $data['gambar'] = 'assets/img/' . $imageName;
        }

        $stokOnderdil->update($data);
        return back()->with('sukses', 'Stok onderdil berhasil diperbarui.');
    }

    public function destroy(StokOnderdil $stokOnderdil)
    {
        $stokOnderdil->delete();
        return back()->with('sukses', 'Stok onderdil berhasil dihapus.');
    }
}