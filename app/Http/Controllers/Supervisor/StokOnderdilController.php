<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StokOnderdil;
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
        $periode = 'Semua Data';
        
        if ($request->filled('search')) {
            $periode = 'Pencarian: "' . $request->search . '"';
        }

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

        $stokOnderdil->update($data);
        return back()->with('sukses', 'Stok onderdil berhasil diperbarui.');
    }

    public function destroy(StokOnderdil $stokOnderdil)
    {
        $stokOnderdil->delete();
        return back()->with('sukses', 'Stok onderdil berhasil dihapus.');
    }
}