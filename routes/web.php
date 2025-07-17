<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KelolaTuserController;
use App\Http\Controllers\Tuser\DashboardController as tuser;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServisanController;
use App\Http\Controllers\SparepartController;
use App\Http\Controllers\ThemeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/admin', [HomeController::class, 'admin'])->name('home.admin');
Route::get('/tuser', [HomeController::class, 'tuser'])->name('home.tuser');
Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dashboard route - PERBAIKAN: Hanya satu route dashboard dengan nama yang konsisten
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // API Routes untuk Chart Data
    Route::get('/api/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chart-data');

    // Resource routes
    Route::resource('pelanggan', PelangganController::class);
    Route::resource('servisan', ServisanController::class);

    Route::get('/servisan-filter', [ServisanController::class, 'filter'])->name('servisan.filter');
    Route::patch('/servisan/{servisan}/status', [ServisanController::class, 'updateStatus'])->name('servisan.updateStatus');
    Route::patch('/servisan/{id}/complete', [ServisanController::class, 'markAsCompleted'])->name('servisan.complete');
    Route::patch('/servisan/{id}/deliver', [ServisanController::class, 'markAsDelivered'])->name('servisan.deliver');
    Route::get('/servisan/{id}/get', [ServisanController::class, 'getServisan'])->name('servisan.get');
    Route::get('/servisan-print/{id}', [ServisanController::class, 'printReceipt'])->name('servisan.print');

    // Additional pelanggan routes
    Route::get('/pelanggan/{pelanggan}/get', [PelangganController::class, 'getPelanggan'])->name('pelanggan.get');
    Route::get('/pelanggan-search', [PelangganController::class, 'search'])->name('pelanggan.search');
    Route::get('/pelanggan-export', [PelangganController::class, 'export'])->name('pelanggan.export');
    Route::delete('/pelanggan/{id}/delete', [PelangganController::class, 'destroy'])->name('pelanggan.delete');

    Route::resource('spareparts', SparepartController::class);
    // Rute untuk Sparepart
    Route::prefix('spareparts')->name('spareparts.')->group(function () {
        Route::get('/', [SparepartController::class, 'index'])->name('index');
        Route::get('/create', [SparepartController::class, 'create'])->name('create');
        Route::post('/', [SparepartController::class, 'store'])->name('store');
        Route::get('/{sparepart}', [SparepartController::class, 'show'])->name('show');
        Route::get('/{sparepart}/edit', [SparepartController::class, 'edit'])->name('edit');
        Route::put('/{sparepart}', [SparepartController::class, 'update'])->name('update');
        Route::delete('/{sparepart}', [SparepartController::class, 'destroy'])->name('destroy');
        Route::post('/{sparepart}/tambah-stok', [SparepartController::class, 'tambahStok'])->name('tambah-stok');
        Route::post('/{sparepart}/kurangi-stok', [SparepartController::class, 'kurangiStok'])->name('kurangi-stok');
    });

    // Kelola admin 
    Route::resource('kelolatuser', KelolaTuserController::class);
    Route::patch('kelolatuser/{tuser}/toggle-status', [KelolaTuserController::class, 'toggleStatus'])
        ->name('kelolatuser.toggle-status');
    Route::post('kelolatuser/bulk-action', [KelolaTuserController::class, 'bulkAction'])
        ->name('kelolatuser.bulk-action');

    Route::post('/theme/toggle', [ThemeController::class, 'toggle'])->name('theme.toggle');
    Route::get('/theme/current', [ThemeController::class, 'current'])->name('theme.current');
});

Route::middleware(['web', 'tuser.auth'])->prefix('tuser')->name('tuser.')->group(function () {
    // Dashboard Route
    Route::get('tuser/dashboard', [tuser::class, 'index'])
        ->name('tuser.dashboard');
});

require __DIR__ . '/auth.php';
