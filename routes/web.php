<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SupervisorController;
use App\Http\Controllers\TenagakerjaController;
use App\Http\Controllers\Supervisor\StokOnderdilController;
use App\Http\Controllers\Supervisor\JadwalPemeliharaanController;
use App\Http\Controllers\Supervisor\PengecekanOnderdilController;
use App\Http\Controllers\Supervisor\LaporanPemeliharaanController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('password/reset', [ForgotPasswordController::class, 'showForgotForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'submitForgotRequest'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

Auth::routes(['reset' => false, 'verify' => true]);

Route::middleware(['auth', 'prevent-back-history'])->group(function () {

    Route::middleware(['supervisor'])->group(function () {
        Route::get('/supervisor', [SupervisorController::class, 'index'])->name('supervisor.dashboard');

        Route::get('/kelola-supervisor', [SupervisorController::class, 'kelolaSupervisor'])->name('supervisor.kelola-supervisor.index');
        Route::post('/kelola-supervisor', [SupervisorController::class, 'storeSupervisor'])->name('supervisor.kelola-supervisor.store');
        Route::put('/kelola-supervisor/{supervisor}', [SupervisorController::class, 'updateSupervisor'])->name('supervisor.kelola-supervisor.update');
        Route::delete('/kelola-supervisor/{supervisor}', [SupervisorController::class, 'destroySupervisor'])->name('supervisor.kelola-supervisor.destroy');
        Route::patch('/kelola-tenagakerja/{id}/restore', [SupervisorController::class, 'restoreTenagaKerja'])->whereNumber('id')->name('supervisor.kelola-tenagakerja.restore');

        Route::get('/kelola-tenagakerja', [SupervisorController::class, 'kelolaTenagaKerja'])->name('supervisor.kelola-tenagakerja.index');
        Route::post('/kelola-tenagakerja', [SupervisorController::class, 'storeTenagaKerja'])->name('supervisor.kelola-tenagakerja.store');
        Route::put('/kelola-tenagakerja/{tenagaKerja}', [SupervisorController::class, 'updateTenagaKerja'])->name('supervisor.kelola-tenagakerja.update');
        Route::delete('/kelola-tenagakerja/{tenagaKerja}', [SupervisorController::class, 'destroyTenagaKerja'])->name('supervisor.kelola-tenagakerja.destroy');

        Route::get('/stok-onderdil', [StokOnderdilController::class, 'index'])->name('supervisor.stok-onderdil.index');
        Route::get('/stok-onderdil/export-pdf', [StokOnderdilController::class, 'exportPdf'])->name('supervisor.stok-onderdil.pdf');
        Route::get('/stok-onderdil/export-excel', [StokOnderdilController::class, 'exportExcel'])->name('supervisor.stok-onderdil.excel');
        Route::post('/stok-onderdil', [StokOnderdilController::class, 'store'])->name('supervisor.stok-onderdil.store');
        Route::put('/stok-onderdil/{stokOnderdil}', [StokOnderdilController::class, 'update'])->name('supervisor.stok-onderdil.update');
        Route::delete('/stok-onderdil/{stokOnderdil}', [StokOnderdilController::class, 'destroy'])->name('supervisor.stok-onderdil.destroy');

        Route::get('/jadwal-pemeliharaan', [JadwalPemeliharaanController::class, 'index'])->name('supervisor.jadwal-pemeliharaan.index');
        Route::post('/jadwal-pemeliharaan', [JadwalPemeliharaanController::class, 'store'])->name('supervisor.jadwal-pemeliharaan.store');
        Route::put('/jadwal-pemeliharaan/{jadwalPemeliharaan}', [JadwalPemeliharaanController::class, 'update'])->name('supervisor.jadwal-pemeliharaan.update');
        Route::delete('/jadwal-pemeliharaan/{jadwalPemeliharaan}', [JadwalPemeliharaanController::class, 'destroy'])->name('supervisor.jadwal-pemeliharaan.destroy');

        Route::get('/jadwal-pemeliharaan/export-pdf', [JadwalPemeliharaanController::class, 'exportPdf'])->name('supervisor.jadwal-pemeliharaan.pdf');
        Route::get('/jadwal-pemeliharaan/export-excel', [JadwalPemeliharaanController::class, 'exportExcel'])->name('supervisor.jadwal-pemeliharaan.excel');

        Route::put('/jadwal-pemeliharaan/{jadwalPemeliharaan}/verifikasi-selesai', [JadwalPemeliharaanController::class, 'verifikasiSelesai'])->name('supervisor.jadwal-pemeliharaan.verifikasi-selesai');
        Route::put('/jadwal-pemeliharaan/{jadwalPemeliharaan}/verifikasi-tolak', [JadwalPemeliharaanController::class, 'verifikasiTolak'])->name('supervisor.jadwal-pemeliharaan.verifikasi-tolak');
        Route::put('/jadwal-pemeliharaan/{jadwalPemeliharaan}/tolak-jadwal', [JadwalPemeliharaanController::class, 'tolakJadwal'])->name('supervisor.jadwal-pemeliharaan.tolak-jadwal');
        
        Route::get('/jadwal-pemeliharaan/arsip', [JadwalPemeliharaanController::class, 'arsip'])->name('supervisor.jadwal-pemeliharaan.arsip');
        Route::patch('/jadwal-pemeliharaan/arsip/{id}/restore', [JadwalPemeliharaanController::class, 'restore'])->whereNumber('id')->name('supervisor.jadwal-pemeliharaan.restore');

        // Halaman utama Pengecekan Onderdil
        Route::get('/pengecekan-onderdil', [PengecekanOnderdilController::class, 'index'])->name('supervisor.pengecekan-onderdil.index');
        // Simpan jadwal pengecekan baru
        Route::post('/pengecekan-onderdil', [PengecekanOnderdilController::class, 'store'])->name('supervisor.pengecekan-onderdil.store');
        // Edit jadwal pengecekan
        Route::put('/pengecekan-onderdil/{jadwal}', [PengecekanOnderdilController::class, 'update'])->name('supervisor.pengecekan-onderdil.update');
        // Hapus jadwal pengecekan
        Route::delete('/pengecekan-onderdil/{jadwal}', [PengecekanOnderdilController::class, 'destroy'])->name('supervisor.pengecekan-onderdil.destroy');

        // Export laporan Pengecekan Onderdil ke PDF
        Route::get('/pengecekan-onderdil/export-pdf', [PengecekanOnderdilController::class, 'exportPdf'])->name('supervisor.pengecekan-onderdil.pdf');
        // Export laporan Pengecekan Onderdil ke Excel
        Route::get('/pengecekan-onderdil/export-excel', [PengecekanOnderdilController::class, 'exportExcel'])->name('supervisor.pengecekan-onderdil.excel');
        
        // Supervisor ACC hasil pengecekan Tenaga Kerja
        Route::put('/pengecekan-onderdil/{jadwal}/verifikasi', [PengecekanOnderdilController::class, 'verifikasi'])->name('supervisor.pengecekan-onderdil.verifikasi');
        // Supervisor menolak hasil pengecekan Tenaga Kerja
        Route::put('/pengecekan-onderdil/{jadwal}/tolak-hasil', [PengecekanOnderdilController::class, 'tolakHasil'])->name('supervisor.pengecekan-onderdil.tolak-hasil');
        // Supervisor menyetujui penolakan tugas dari Tenaga Kerja
        Route::put('/pengecekan-onderdil/{jadwal}/verifikasi-tolak', [PengecekanOnderdilController::class, 'verifikasiTolak'])->name('supervisor.pengecekan-onderdil.verifikasi-tolak');

        Route::get('/pengecekan-onderdil/arsip', [PengecekanOnderdilController::class, 'arsip'])->name('supervisor.pengecekan-onderdil.arsip');
        Route::patch('/pengecekan-onderdil/arsip/{id}/restore', [PengecekanOnderdilController::class, 'restore'])->whereNumber('id')->name('supervisor.pengecekan-onderdil.restore');
        Route::get('/pengecekan-onderdil/arsip/export-pdf', [PengecekanOnderdilController::class, 'exportArsipPdf'])->name('supervisor.pengecekan-onderdil.arsip.pdf');
        Route::get('/pengecekan-onderdil/arsip/export-excel', [PengecekanOnderdilController::class, 'exportArsipExcel'])->name('supervisor.pengecekan-onderdil.arsip.excel');

        Route::get('/laporan-pemeliharaan', [LaporanPemeliharaanController::class, 'index'])->name('supervisor.laporan-pemeliharaan.index');
        Route::get('/laporan-pemeliharaan/export-pdf', [LaporanPemeliharaanController::class, 'exportPdf'])->name('supervisor.laporan-pemeliharaan.pdf');
        Route::get('/laporan-pemeliharaan/export-excel', [LaporanPemeliharaanController::class, 'exportExcel'])->name('supervisor.laporan-pemeliharaan.excel');
        Route::get('/laporan-pemeliharaan/{jadwal}/export-pdf', [LaporanPemeliharaanController::class, 'exportSatuPdf'])->name('supervisor.laporan-pemeliharaan.pdf.satu')->withTrashed();
    });

    Route::middleware(['tenagakerja'])->group(function () {
        Route::get('/tenagakerja', [TenagakerjaController::class, 'index'])->name('tenagakerja.dashboard');
        Route::get('/tenagakerja/checklist', [TenagakerjaController::class, 'checklist'])->name('tenagakerja.checklist.index');
        Route::put('/tenagakerja/checklist/{jadwal}/selesai', [TenagakerjaController::class, 'selesai'])->name('tenagakerja.checklist.selesai');
        Route::put('/tenagakerja/checklist/{jadwal}/tolak', [TenagakerjaController::class, 'tolak'])->name('tenagakerja.checklist.tolak');
        Route::get('/tenagakerja/pengecekan-onderdil', [TenagakerjaController::class, 'pengecekanOnderdil'])->name('tenagakerja.pengecekan-onderdil.index');
        Route::put('/tenagakerja/pengecekan-onderdil/{jadwal}/selesai', [TenagakerjaController::class, 'selesaiPengecekanOnderdil'])->name('tenagakerja.pengecekan-onderdil.selesai');
        Route::put('/tenagakerja/pengecekan-onderdil/{jadwal}/tolak', [TenagakerjaController::class, 'tolakPengecekanOnderdil'])->name('tenagakerja.pengecekan-onderdil.tolak');
        Route::get('/tenagakerja/stok-onderdil', [TenagakerjaController::class, 'stokOnderdil'])->name('tenagakerja.stok-onderdil.index');
    });
});
