<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\JadwalPemeliharaan;
use App\Models\StokOnderdil;
use App\Models\JadwalPengecekanOnderdil;
use Illuminate\Support\Facades\Hash;

class SupervisorController extends Controller
{
    public function index()
    {
        $totalTenagaKerja = User::where('role', 'tenagakerja')->count();
        $totalLaporan = JadwalPemeliharaan::count();
        $totalSparepart = StokOnderdil::count();
        $totalJadwalPengecekan = JadwalPengecekanOnderdil::count();
        
        $stokMenipis = StokOnderdil::where('stok_terbaru', '<', 10)->orderBy('stok_terbaru', 'asc')->take(5)->get();
        $totalStokMenipis = StokOnderdil::where('stok_terbaru', '<', 10)->count();

        return view('supervisor.dashboard', compact(
            'totalTenagaKerja',
            'totalLaporan',
            'totalSparepart',
            'totalJadwalPengecekan',
            'stokMenipis',
            'totalStokMenipis'
        ));
    }

    public function kelolaSupervisor()
    {
        $supervisors = User::where('role', 'supervisor')->get();
        return view('supervisor.kelola-supervisor.index', compact('supervisors'));
    }

    public function storeSupervisor(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'no_telepon' => 'required|string|max:15|unique:users,no_telepon',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['role'] = 'supervisor';

        User::create($data);
        return back()->with('sukses', 'Supervisor berhasil ditambahkan.');
    }

    public function updateSupervisor(Request $request, User $supervisor)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'no_telepon' => 'required|string|max:15|unique:users,no_telepon,' . $supervisor->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $supervisor->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $supervisor->update($data);
        return back()->with('sukses', 'Supervisor berhasil diperbarui.');
    }

    public function destroySupervisor(User $supervisor)
    {
        $supervisor->delete();
        return back()->with('sukses', 'Supervisor berhasil dihapus.');
    }
    
    public function kelolaTenagaKerja()
    {
        // 1. Ambil akun Tenaga Kerja yang masih aktif
        $tenagaKerja = User::where('role', 'tenagakerja')->orderBy('name', 'asc')->get();

        // 2. Ambil akun Tenaga Kerja yang sudah di-Soft Delete
        $tenagaKerjaTerhapus = User::onlyTrashed()->where('role', 'tenagakerja')->orderBy('deleted_at', 'desc')->get();

        // 3. Kirim kedua kelompok data ke halaman Kelola Tenaga Kerja
        return view('supervisor.kelola-tenagakerja.index', compact('tenagaKerja', 'tenagaKerjaTerhapus'));
    }

    public function storeTenagaKerja(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'no_telepon' => 'required|string|max:15|unique:users,no_telepon',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'jenis_pelaksana' => 'required|in:Internal,Subkontraktor',
            'jenis_tugas' => 'required|in:pemeliharaan_peralatan,pengecekan_onderdil,keduanya',
            'keahlian' => 'required|string|max:255',
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['role'] = 'tenagakerja';

        User::create($data);
        return back()->with('sukses', 'Tenaga Kerja berhasil ditambahkan.');
    }

    public function updateTenagaKerja(Request $request, User $tenagaKerja)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'no_telepon' => 'required|string|max:15|unique:users,no_telepon,' . $tenagaKerja->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $tenagaKerja->id,
            'password' => 'nullable|string|min:6|confirmed',
            'jenis_pelaksana' => 'required|in:Internal,Subkontraktor',
            'jenis_tugas' => 'required|in:pemeliharaan_peralatan,pengecekan_onderdil,keduanya',
            'keahlian' => 'required|string|max:255',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $tenagaKerja->update($data);
        return back()->with('sukses', 'Tenaga Kerja berhasil diperbarui.');
    }

    public function destroyTenagaKerja(User $tenagaKerja)
    {
        // Pastikan akun yang dihapus adalah Tenaga Kerja
        if ($tenagaKerja->role !== 'tenagakerja') {
            return back()->withErrors([
                'error' => 'Akun yang dipilih bukan akun Tenaga Kerja.'
            ]);
        }

        // 1. Periksa tugas aktif pada Jadwal Pemeliharaan
        $adaPemeliharaanAktif = JadwalPemeliharaan::where('nama_tenaga_kerja', $tenagaKerja->id)->whereIn('status', ['pending', 'menunggu_verifikasi'])->exists();

        // 2. Periksa tugas aktif pada Jadwal Pengecekan Sparepart
        $adaPengecekanAktif = JadwalPengecekanOnderdil::where('tenaga_kerja_id', $tenagaKerja->id)->whereIn('status', ['pending', 'menunggu_verifikasi'])->exists();

        // 3. Tolak penghapusan jika masih memiliki tugas aktif
        if ($adaPemeliharaanAktif || $adaPengecekanAktif) {
            return back()->withErrors([
                'error' => 'Akun Tenaga Kerja tidak dapat dihapus karena masih memiliki tugas pemeliharaan atau pengecekan sparepart yang belum selesai atau belum diverifikasi. Selesaikan atau alihkan tugas terlebih dahulu.'
            ]);
        }

        // 4. Hapus akun menggunakan Soft Delete
        $tenagaKerja->delete();
        return back()->with('sukses', 'Tenaga Kerja berhasil dihapus. Riwayat pekerjaan tetap tersimpan.');
    }

    /* =====================================================
    *        PEMULIHAN AKUN TENAGA KERJA
    * =====================================================
    * Mengaktifkan kembali akun yang sebelumnya dihapus menggunakan Soft Delete.
    * ID akun tetap sama agar riwayat pekerjaan dan hubungan dengan jadwal tidak berubah. */

    public function restoreTenagaKerja(int $id)
    {
        // 1. Cari akun Tenaga Kerja yang sudah di-Soft Delete
        $tenagaKerja = User::onlyTrashed()->where('role', 'tenagakerja')->findOrFail($id);

        // 2. Pulihkan akun tanpa membuat pengguna baru
        $tenagaKerja->restore();

        // 3. Berikan notifikasi keberhasilan
        return back()->with('sukses', 'Akun Tenaga Kerja ' . $tenagaKerja->name . ' berhasil dipulihkan dan dapat digunakan kembali.');
    }
}
