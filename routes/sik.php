<?php

use App\Http\Controllers\Sik\ApprovalSIKController;
use App\Http\Controllers\Sik\LaporanSIKController;
use App\Http\Controllers\Sik\SecurityScanController;
use App\Http\Controllers\Sik\SIKController;
use Illuminate\Support\Facades\Route;

Route::prefix('sik')->name('sik.')->group(function () {

    // Dashboard SIK — tersedia untuk siapa pun yang punya akses lihat SIK.
    Route::get('/dashboard', [SIKController::class, 'dashboard'])->name('dashboard')->middleware('permission:sik.view');

    // Pengajuan SIK & Riwayat SIK (Employee dan role lain sesuai permission).
    Route::get('/', [SIKController::class, 'index'])->name('index')->middleware('permission:sik.view');
    Route::get('/create', [SIKController::class, 'create'])->name('create')->middleware('permission:sik.create');
    Route::post('/', [SIKController::class, 'store'])->name('store')->middleware('permission:sik.create');

    // Approval SIK (Manager, Admin, GA Staff).
    Route::prefix('approvals')->name('approvals.')->group(function () {
        Route::get('/', [ApprovalSIKController::class, 'index'])->name('index')->middleware('permission:sik.approve');
        Route::get('/{suratIzinKeluar}', [ApprovalSIKController::class, 'show'])->name('show')->middleware('permission:sik.approve');
        Route::post('/{suratIzinKeluar}/process', [ApprovalSIKController::class, 'process'])->name('process')->middleware('permission:sik.approve');
    });

    // Scan Security (role Security, Admin, GA Staff).
    Route::prefix('security')->name('security.')->group(function () {
        Route::get('/dashboard', [SecurityScanController::class, 'dashboard'])->name('dashboard')->middleware('permission:sik.scan');
        Route::get('/scan', [SecurityScanController::class, 'scanForm'])->name('scan')->middleware('permission:sik.scan');
        Route::post('/scan', [SecurityScanController::class, 'scan'])->name('scan.process')->middleware('permission:sik.scan');
        Route::get('/verify/{token}', [SecurityScanController::class, 'verify'])->name('verify')->middleware('permission:sik.scan');
        Route::get('/history', [SecurityScanController::class, 'historyToday'])->name('history')->middleware('permission:sik.scan');
    });

    // Laporan SIK (Admin / GA Staff / export).
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [LaporanSIKController::class, 'index'])->name('index')->middleware('permission:sik.export');
        Route::get('/export/pdf', [LaporanSIKController::class, 'exportPdf'])->name('export.pdf')->middleware('permission:sik.export');
        Route::get('/export/excel', [LaporanSIKController::class, 'exportExcel'])->name('export.excel')->middleware('permission:sik.export');
    });

    // Detail, edit, cancel, PDF & cetak — pakai wildcard {suratIzinKeluar} di paling
    // bawah supaya tidak menabrak route statis di atas (create, dashboard, dst).
    Route::get('/{suratIzinKeluar}', [SIKController::class, 'show'])->name('show')->middleware('permission:sik.view');
    Route::get('/{suratIzinKeluar}/edit', [SIKController::class, 'edit'])->name('edit')->middleware('permission:sik.edit');
    Route::put('/{suratIzinKeluar}', [SIKController::class, 'update'])->name('update')->middleware('permission:sik.edit');
    Route::post('/{suratIzinKeluar}/cancel', [SIKController::class, 'cancel'])->name('cancel')->middleware('permission:sik.edit');
    Route::get('/{suratIzinKeluar}/pdf', [SIKController::class, 'pdf'])->name('pdf')->middleware('permission:sik.view');
});
