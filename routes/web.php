<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SupervisorController;
use App\Http\Controllers\TenagakerjaController;
use App\Http\Controllers\Supervisor\StokOnderdilController;
use App\Http\Controllers\Supervisor\JadwalPemeliharaanController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {

    Route::middleware(['supervisor'])->group(function () {
        Route::get('/supervisor', [SupervisorController::class, 'index'])->name('supervisor.dashboard');

        Route::get('/kelola-supervisor', [SupervisorController::class, 'kelolaSupervisor'])->name('supervisor.kelola-supervisor.index');
        Route::post('/kelola-supervisor', [SupervisorController::class, 'storeSupervisor'])->name('supervisor.kelola-supervisor.store');
        Route::put('/kelola-supervisor/{supervisor}', [SupervisorController::class, 'updateSupervisor'])->name('supervisor.kelola-supervisor.update');
        Route::delete('/kelola-supervisor/{supervisor}', [SupervisorController::class, 'destroySupervisor'])->name('supervisor.kelola-supervisor.destroy');

        Route::get('/kelola-tenagakerja', [SupervisorController::class, 'kelolaTenagaKerja'])->name('supervisor.kelola-tenagakerja.index');
        Route::post('/kelola-tenagakerja', [SupervisorController::class, 'storeTenagaKerja'])->name('supervisor.kelola-tenagakerja.store');
        Route::put('/kelola-tenagakerja/{tenagaKerja}', [SupervisorController::class, 'updateTenagaKerja'])->name('supervisor.kelola-tenagakerja.update');
        Route::delete('/kelola-tenagakerja/{tenagaKerja}', [SupervisorController::class, 'destroyTenagaKerja'])->name('supervisor.kelola-tenagakerja.destroy');

        Route::get('/stok-onderdil', [StokOnderdilController::class, 'index'])->name('supervisor.stok-onderdil.index');
        Route::post('/stok-onderdil', [StokOnderdilController::class, 'store'])->name('supervisor.stok-onderdil.store');
        Route::put('/stok-onderdil/{stokOnderdil}', [StokOnderdilController::class, 'update'])->name('supervisor.stok-onderdil.update');
        Route::delete('/stok-onderdil/{stokOnderdil}', [StokOnderdilController::class, 'destroy'])->name('supervisor.stok-onderdil.destroy');

        Route::get('/jadwal-pemeliharaan', [JadwalPemeliharaanController::class, 'index'])->name('supervisor.jadwal-pemeliharaan.index');
        Route::post('/jadwal-pemeliharaan', [JadwalPemeliharaanController::class, 'store'])->name('supervisor.jadwal-pemeliharaan.store');
        Route::put('/jadwal-pemeliharaan/{jadwalPemeliharaan}', [JadwalPemeliharaanController::class, 'update'])->name('supervisor.jadwal-pemeliharaan.update');
        Route::delete('/jadwal-pemeliharaan/{jadwalPemeliharaan}', [JadwalPemeliharaanController::class, 'destroy'])->name('supervisor.jadwal-pemeliharaan.destroy');
    });

    Route::middleware(['tenagakerja'])->group(function () {
        Route::get('/tenagakerja', [TenagakerjaController::class, 'index'])->name('tenagakerja.dashboard');
        Route::get('/tenagakerja/checklist', [TenagakerjaController::class, 'checklist'])->name('tenagakerja.checklist.index');
        Route::put('/tenagakerja/checklist/{jadwal}/selesai', [TenagakerjaController::class, 'selesai'])->name('tenagakerja.checklist.selesai');
        Route::put('/tenagakerja/checklist/{jadwal}/tolak', [TenagakerjaController::class, 'tolak'])->name('tenagakerja.checklist.tolak');
    });
});