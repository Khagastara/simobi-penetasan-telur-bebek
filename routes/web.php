<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\PengepulController;
use App\Http\Controllers\Dashboard\Owner\PasswordResetController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Owner Routes
Route::middleware(['auth', 'owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [OwnerController::class, 'dashboard'])->name('dashboard');
    Route::get('/eggs', [OwnerController::class, 'indexEggs'])->name('eggs.index');
    Route::post('/eggs', [OwnerController::class, 'storeEgg'])->name('eggs.store');
    Route::get('/transactions', [OwnerController::class, 'indexTransactions'])->name('transactions.index');
});

// Pengepul Routes
Route::middleware(['auth', 'pengepul'])->prefix('pengepul')->name('pengepul.')->group(function () {
    Route::get('/dashboard', [PengepulController::class, 'dashboard'])->name('dashboard');
    Route::get('/available-eggs', [PengepulController::class, 'availableEggs'])->name('eggs.available');
    Route::post('/collect-egg/{egg}', [PengepulController::class, 'collectEgg'])->name('eggs.collect');
    Route::get('/inventory', [PengepulController::class, 'inventory'])->name('inventory');
});

// Shared Auth Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile']);
});

Route::prefix('owner')->group(function() {
    Route::get('/forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])
         ->name('owner.password.request');

    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])
         ->name('owner.password.email');

    Route::get('/verify-otp', [PasswordResetController::class, 'showVerifyForm'])
         ->name('owner.password.verify');

    Route::post('/verify-otp', [PasswordResetController::class, 'verifyOtp'])
         ->name('owner.password.verify.submit');

    Route::get('/reset-password', [PasswordResetController::class, 'showResetForm'])
         ->name('owner.password.reset');

    Route::post('/reset-password', [PasswordResetController::class, 'reset'])
         ->name('owner.password.update');
});

Route::middleware(['auth', 'pengepul'])->prefix('pengepul')->group(function () {
    Route::post('/transaksi', [TransaksiController::class, 'store'])
         ->name('pengepul.transaksi.store');
});
