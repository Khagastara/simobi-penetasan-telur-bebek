<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Mail;

use App\Http\Controllers\PengepulRegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\StokDistribusiController;
use App\Http\Controllers\KeuanganController;

use App\Http\Controllers\OwnerProfilController;
use App\Http\Controllers\PenjadwalanKegiatanController;
use App\Http\Controllers\TransaksiController;

use App\Http\Controllers\PengepulProfilController;


Route::get('/test-email', function () {
    try {
        Mail::raw('Test email from Laravel', function ($message) {
            $message->to('fadhluaqil@gmail.com')
                    ->subject('Test Email');
        });
        return 'Email sent successfully! Check your Mailtrap inbox.';
    } catch (Exception $e) {
        return 'Email failed: ' . $e->getMessage();
    }
});

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/send-notification', [NotificationController::class, 'sendNotification'])
    ->name('send.notification');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])
    ->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendOtp'])
    ->name('password.email');
Route::get('/otp-verification', [ForgotPasswordController::class, 'showOtpForm'])
    ->name('password.otp');
Route::post('/otp-verification', [ForgotPasswordController::class, 'verifyOtp'])
    ->name('password.otp.verify');
Route::get('/reset-password', [ForgotPasswordController::class, 'showResetForm'])
    ->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])
    ->name('password.update');

Route::middleware(['auth'])->group(function () {
    Route::get('/owner/dashboard', function () {
        return view('owner.dashboard');
    })->name('owner.dashboard');

    Route::get('/pengepul/dashboard', function () {
        return view('pengepul.stok.index');
    })->name('pengepul.stok.index');
});

// Owner
Route::post('/logout', [OwnerProfilController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('o/profil', [OwnerProfilController::class, 'show'])->name('owner.profil.show');
    Route::get('o/profil/edit', [OwnerProfilController::class, 'edit'])->name('owner.profil.edit');
    Route::post('o/profil/update', [OwnerProfilController::class, 'update'])->name('owner.profil.update');

    Route::get('/penjadwalan', [PenjadwalanKegiatanController::class, 'index'])->name('owner.penjadwalan.index');
    Route::get('/owner/penjadwalan/{id}', [PenjadwalanKegiatanController::class, 'show'])->name('owner.penjadwalan.show');
    Route::put('/penjadwalan/update-status/{id}', [PenjadwalanKegiatanController::class, 'duration'])->name('owner.penjadwalan.duration');
    Route::get('/penjadwalan/create', [PenjadwalanKegiatanController::class, 'create'])->name('owner.penjadwalan.create');
    Route::post('/penjadwalan', [PenjadwalanKegiatanController::class, 'store'])->name('owner.penjadwalan.store');
    Route::get('/penjadwalan/{id}/edit', [PenjadwalanKegiatanController::class, 'edit'])->name('owner.penjadwalan.edit');
    Route::delete('/penjadwalan/{id}', [PenjadwalanKegiatanController::class, 'delete'])->name('owner.penjadwalan.delete');
    Route::put('/penjadwalan/{id}', [PenjadwalanKegiatanController::class, 'update'])->name('owner.penjadwalan.update');

    Route::get('/stok', [StokDistribusiController::class, 'index'])->name('owner.stok.index');
    Route::get('/stok/create', [StokDistribusiController::class, 'create'])->name('owner.stok.create');
    Route::post('/stok', [StokDistribusiController::class, 'store'])->name('owner.stok.store');
    Route::get('/stok/{id}', [StokDistribusiController::class, 'show'])->name('owner.stok.show');
    Route::get('/stok/{id}/edit', [StokDistribusiController::class, 'edit'])->name('owner.stok.edit');
    Route::put('/stok/{id}', [StokDistribusiController::class, 'update'])->name('owner.stok.update');

    Route::get('o/riwayat-transaksi', [TransaksiController::class, 'index'])->name('owner.transaksi.index');
    Route::get('o/riwayat-transaksi/{id}', [TransaksiController::class, 'show'])->name('owner.transaksi.show');
    Route::put('o/riwayat-transaksi/{id}/update-status', [TransaksiController::class, 'updateStatus'])->name('owner.transaksi.update-status');

    Route::get('/keuangan', [KeuanganController::class, 'index'])->name('owner.keuangan.index');
    Route::get('/keuangan/create', [KeuanganController::class, 'create'])->name('owner.keuangan.create');
    Route::post('/keuangan', [KeuanganController::class, 'store'])->name('owner.keuangan.store');
    Route::get('/keuangan/{id}', [KeuanganController::class, 'show'])->name('owner.keuangan.show');
    Route::get('/keuangan/{id}/edit', [KeuanganController::class, 'edit'])->name('owner.keuangan.edit');
    Route::put('/keuangan/{id}', [KeuanganController::class, 'update'])->name('owner.keuangan.update');
});

// Pengepul
Route::get('/pengepuls/register', [PengepulRegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/pengepuls/register', [PengepulRegisterController::class, 'register'])->name('register.submit');

Route::post('/logout', [OwnerProfilController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('p/profil', [PengepulProfilController::class, 'show'])->name('pengepul.profil.show');
    Route::get('p/profil/edit', [PengepulProfilController::class, 'edit'])->name('pengepul.profil.edit');
    Route::post('p/profil/update', [PengepulProfilController::class, 'update'])->name('pengepul.profil.update');

    Route::get('/stok-distribusi', [StokDistribusiController::class, 'indexPengepul'])->name('pengepul.stok.index');
    Route::get('/stok-distribusi/{id}', [StokDistribusiController::class, 'showPengepul'])->name('pengepul.stok.show');

    Route::get('p/riwayat-transaksi', [TransaksiController::class, 'indexPengepul'])->name('pengepul.transaksi.index');
    Route::get('p/riwayat-transaksi/{id}', [TransaksiController::class, 'showPengepul'])->name('pengepul.transaksi.show');
    Route::get('p/transaksi/create/{stokId}', [TransaksiController::class, 'create'])->name('pengepul.transaksi.create');
    Route::post('p/transaksi/store/{stokId}', [TransaksiController::class, 'store'])->name('pengepul.transaksi.store');

    Route::get('/pengepul/transaksi/{id}/payment', [TransaksiController::class, 'payment'])->name('pengepul.transaksi.payment');
});

Route::post('/midtrans/callback', [TransaksiController::class, 'handleCallback'])->name('midtrans.callback');
Route::get('/payment/status/{id}', [TransaksiController::class, 'checkPaymentStatus'])->name('payment.status');
