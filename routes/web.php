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

// Route::get('/pegawai/detail/{nama}', function (string $nama) {
//     return "nama pegawai ini adalah : $nama";
// });

Route::get('/pegawai/detail/{nama?}', function (?string $nama = "septian") {
    return "nama pegawai ini adalah : $nama";
});

Route::get('/pegawai/cek_absensi/maret', function () {
    return "absensi pegawai pada bulan maret";
})->name('cek_absensi');

// Route::get('/test', function () {
//     return redirect()->route('cek_absensi');
//     // return redirect()->to('/pegawai/cek_absensi/januari');
// });

Route::get('/coba_query', function () {
    // $pegawai = Pegawai::all();
    // dd($pegawai->toArray());

    // $pegawai = Pegawai::find(28);

    // $pegawai = Pegawai::where('nama_pegawai', "Viman Saragih")->first();

    // Pegawai::where('nama_pegawai', 'Viman Saragih')->delete();

    // Pegawai::destroy(28);

    // $pegawai = Pegawai::where('umur', '>', 35)->get();
    Pegawai::where('id', 31)->update([
        'nama_pegawai' => 'Dinda Farida'
    ]);
    // dd($pegawai->toArray());
});

Route::fallback(function () {
    return view('404');
});

Route::get('/webe', function () {
    return redirect()->away('https://www.yayasanwebe.org/');
});

Route::resource('pegawai', PegawaiController::class);
Route::resource('users', UserController::class)->middleware('isSupervisor');
Route::post('user-update-role', [UserController::class, 'updateRole'])->name('users.update-role');
Route::resource('bagian', BagianController::class);
Route::get('/maps', [App\Http\Controllers\MapController::class, 'index'])->name('maps.index');
Route::get('/maps/create', [App\Http\Controllers\MapController::class, 'create'])->name('maps.create');
Route::post('/maps', [App\Http\Controllers\MapController::class, 'store'])->name('maps.store');
Route::resource('maps', App\Http\Controllers\MapController::class)->except(['index', 'create', 'store']);

// Public/front map (full-screen map with markers)
Route::get('/frontmaps', [App\Http\Controllers\MapController::class, 'frontIndex'])->name('maps.front');


// Route::get('/truncate', function () {
//     Pegawai::truncate();
// });