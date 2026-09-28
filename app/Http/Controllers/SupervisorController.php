<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SupervisorController extends Controller
{
   public function index()
    {
        return view('supervisor.dashboard');
    }

    public function kelolaSupervisor()
    {
        $supervisors = User::where('role', 'Supervisor')->get();
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
        $data['role'] = 'Supervisor';

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
        $tenagaKerja = User::where('role', 'TenagaKerja')->get();
        return view('supervisor.kelola-tenagakerja.index', compact('tenagaKerja'));
    }

    public function storeTenagaKerja(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'no_telepon' => 'required|string|max:15|unique:users,no_telepon',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['role'] = 'TenagaKerja';

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
        $tenagaKerja->delete();
        return back()->with('sukses', 'Tenaga Kerja berhasil dihapus.');
    }
}