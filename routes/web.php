<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Http\Controllers\PengepulRegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OwnerProfilController;
use App\Http\Controllers\PenjadwalanKegiatanController;
use App\Http\Controllers\TransaksiController;

use App\Http\Controllers\PengepulProfilController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('auth.welcome');
});

Route::get('/pengepuls/register', [PengepulRegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/pengepuls/register', [PengepulRegisterController::class, 'register'])->name('register.submit');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/owner/dashboard', function () {
        return view('owner.dashboard');
    })->name('owner.dashboard');

    Route::get('/pengepul/dashboard', function () {
        return view('pengepul.dashboard');
    })->name('pengepul.dashboard');
});

// Owner
Route::middleware(['auth:owner'])->group(function () {
    Route::get('o/profil', [OwnerProfilController::class, 'show'])->name('owner.profil.show');
    Route::get('o/profil/edit', [OwnerProfilController::class, 'edit'])->name('owner.profil.edit');
    Route::post('o/profil/update', [OwnerProfilController::class, 'update'])->name('owner.profil.update');
});

Route::middleware(['auth:owner'])->group(function () {
    Route::get('/penjadwalan', [PenjadwalanKegiatanController::class, 'index'])->name('owner.penjadwalan.index');
    Route::get('/penjadwalan/create', [PenjadwalanKegiatanController::class, 'create'])->name('owner.penjadwalan.create');
    Route::post('/penjadwalan', [PenjadwalanKegiatanController::class, 'store'])->name('owner.penjadwalan.store');
    Route::get('/penjadwalan/{id}/edit', [PenjadwalanKegiatanController::class, 'edit'])->name('owner.penjadwalan.edit');
    Route::put('/penjadwalan/{id}', [PenjadwalanKegiatanController::class, 'update'])->name('owner.penjadwalan.update');
});

Route::middleware(['auth:owner'])->group(function () {
    Route::get('o/riwayat-transaksi', [TransaksiController::class, 'index'])->name('owner.transaksi.index');
    Route::get('o/riwayat-transaksi/{id}', [TransaksiController::class, 'show'])->name('owner.transaksi.show');
});

// Pengepul

Route::middleware(['auth:pengepul'])->group(function () {
    Route::get('p/profil', [PengepulProfilController::class, 'show'])->name('pengepul.profil.show');
    Route::get('p/profil/edit', [PengepulProfilController::class, 'edit'])->name('pengepul.profil.edit');
    Route::post('p/profil/update', [PengepulProfilController::class, 'update'])->name('pengepul.profil.update');
});
