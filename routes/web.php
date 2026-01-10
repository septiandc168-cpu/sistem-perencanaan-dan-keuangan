<?php

use App\Http\Controllers\BagianController;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('auth.login');
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/pegawai', function () {
    return view('pegawai');
});

Route::fallback(function () {
    return view('404');
});

Route::resource('pegawai', PegawaiController::class);

Route::middleware('isSupervisor')->group(function () {
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::post('users', [UserController::class, 'store'])->name('users.store');
    Route::delete('users/{id}/destroy', [UserController::class, 'destroy'])->name('users.destroy');
    Route::post('users/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
});

Route::post('users/ganti-password', [UserController::class, 'gantiPassword'])->name('users.ganti-password');
Route::resource('users', UserController::class)->middleware('isSupervisor');
Route::post('user-update-role', [UserController::class, 'updateRole'])->name('users.update-role');
Route::resource('bagian', BagianController::class);

Route::get('/rencana_kegiatan', [App\Http\Controllers\RencanaKegiatanController::class, 'index'])->name('rencana_kegiatan.index');
Route::get('/rencana_kegiatan/create', [App\Http\Controllers\RencanaKegiatanController::class, 'create'])->name('rencana_kegiatan.create');
Route::post('/rencana_kegiatan', [App\Http\Controllers\RencanaKegiatanController::class, 'store'])->name('rencana_kegiatan.store');
Route::resource('rencana_kegiatan', App\Http\Controllers\RencanaKegiatanController::class)->except(['index', 'create', 'store']);

// Public/front map (full-screen map with markers)
Route::get('/front_rencana_kegiatan', [App\Http\Controllers\RencanaKegiatanController::class, 'frontIndex'])->name('rencana_kegiatan.front');

// Laporan Kegiatan Routes
Route::middleware('auth')->group(function () {
    // Create route (admin only but needs to be outside to accept query parameter)
    Route::get('/laporan_kegiatan/create', [App\Http\Controllers\LaporanKegiatanController::class, 'create'])
        ->middleware('isAdmin')
        ->name('laporan_kegiatan.create');
    
    // Admin only routes (store, edit, update, destroy)
    Route::middleware('isAdmin')->group(function () {
        Route::post('/laporan_kegiatan', [App\Http\Controllers\LaporanKegiatanController::class, 'store'])->name('laporan_kegiatan.store');
        Route::get('/laporan_kegiatan/{laporanKegiatan}/edit', [App\Http\Controllers\LaporanKegiatanController::class, 'edit'])->name('laporan_kegiatan.edit');
        Route::put('/laporan_kegiatan/{laporanKegiatan}', [App\Http\Controllers\LaporanKegiatanController::class, 'update'])->name('laporan_kegiatan.update');
        Route::delete('/laporan_kegiatan/{laporanKegiatan}', [App\Http\Controllers\LaporanKegiatanController::class, 'destroy'])->name('laporan_kegiatan.destroy');
    });
    
    // Both admin and supervisor can view
    Route::get('/laporan_kegiatan', [App\Http\Controllers\LaporanKegiatanController::class, 'index'])->name('laporan_kegiatan.index');
    Route::get('/laporan_kegiatan/{laporanKegiatan}', [App\Http\Controllers\LaporanKegiatanController::class, 'show'])->name('laporan_kegiatan.show');
    
    // Print route (both admin and supervisor)
    Route::get('/laporan_kegiatan/{laporanKegiatan}/print', [App\Http\Controllers\LaporanKegiatanController::class, 'print'])->name('laporan_kegiatan.print');
});


// Route::get('/truncate', function () {
//     Pegawai::truncate();
// });