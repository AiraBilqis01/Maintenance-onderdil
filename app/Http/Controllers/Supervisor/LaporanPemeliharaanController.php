<?php
namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JadwalPemeliharaan;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanPemeliharaanExport;

class LaporanPemeliharaanController extends Controller
{
    private function buildQuery(Request $request)
    {
        $query = JadwalPemeliharaan::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tenaga_kerja')) {
            $query->where('nama_tenaga_kerja', $request->tenaga_kerja);
        }

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_selesai', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_selesai', '<=', $request->tanggal_sampai);
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_selesai', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_selesai', $request->tahun);
        }

        return $query;
    }

    public function index(Request $request)
    {
        $laporan = $this->buildQuery($request)->orderBy('tanggal_selesai', 'desc')->get();

        $total   = JadwalPemeliharaan::count();
        $pending = JadwalPemeliharaan::where('status', 'pending')->count();
        $selesai = JadwalPemeliharaan::where('status', 'selesai')->count();
        $ditolak = JadwalPemeliharaan::where('status', 'ditolak')->count();

        $tenagaKerja = User::where('role', 'tenagakerja')->get();

        return view('supervisor.laporan-pemeliharaan.index', compact(
            'laporan',
            'total',
            'pending',
            'selesai',
            'ditolak',
            'tenagaKerja'
        ));
    }

    public function exportPdf(Request $request)
    {
        $laporan = $this->buildQuery($request)->orderBy('tanggal_selesai', 'desc')->get();
        $judul = 'Laporan Pemeliharaan';
        $periode = $this->getPeriodeText($request);

        $pdf = Pdf::loadView('supervisor.laporan-pemeliharaan.pdf', compact('laporan', 'judul', 'periode'))
                    ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-pemeliharaan-' . date('Ymd-His') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(
            new LaporanPemeliharaanExport($request),
            'laporan-pemeliharaan-' . date('Ymd-His') . '.xlsx'
        );
    }

    public function exportSatuPdf(JadwalPemeliharaan $jadwal)
    {
        $laporan = collect([$jadwal->load('user')]);
        $judul = 'Laporan Pemeliharaan - ' . $jadwal->judul_pemeliharaan;
        $periode = \Carbon\Carbon::parse($jadwal->tanggal_selesai)->format('d/m/Y');

        $pdf = Pdf::loadView('supervisor.laporan-pemeliharaan.pdf', compact('laporan', 'judul', 'periode'))
                    ->setPaper('a4', 'portrait');

        return $pdf->download('laporan-' . $jadwal->id . '.pdf');
    }

    private function getPeriodeText(Request $request)
    {
        $parts = [];

        if ($request->filled('bulan')) {
            $bulanArr = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];
            $parts[] = $bulanArr[(int)$request->bulan] ?? $request->bulan;
        }

        if ($request->filled('tahun')) {
            $parts[] = $request->tahun;
        }

        if ($request->filled('tanggal_dari') && $request->filled('tanggal_sampai')) {
            $parts[] = \Carbon\Carbon::parse($request->tanggal_dari)->format('d/m/Y')
                     . ' s/d '
                     . \Carbon\Carbon::parse($request->tanggal_sampai)->format('d/m/Y');
        }

        if ($request->filled('status')) {
            $parts[] = 'Status: ' . ucfirst($request->status);
        }

        if ($request->filled('tenaga_kerja')) {
            $tk = User::find($request->tenaga_kerja);
            if ($tk) $parts[] = 'Tenaga Kerja: ' . $tk->name;
        }

        return empty($parts) ? 'Semua Periode' : implode(' | ', $parts);
    }
}