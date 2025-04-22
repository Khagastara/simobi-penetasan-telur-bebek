<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Dashboard\Owner\PenjadwalanController;
use App\Http\Controllers\Homepage\Owner\OwnerLoginController;
use App\Http\Controllers\Homepage\Pengepul\PengepulLoginController;
use App\Http\Controllers\Homepage\Pengepul\PengepulRegisterController;
use App\Http\Controllers\Dashboard\Pengepul\PengepulProfilController;
use App\Http\Controllers\Homepage\Owner\OwnerPasswordResetController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// routes/web.php
Route::middleware(['auth', 'role:owner'])->prefix('dashboard')->group(function () {
    Route::resource('penjadwalan', PenjadwalanController::class)->except(['destroy']);

    Route::get('penjadwalan/date/{date}', [PenjadwalanController::class, 'showDate'])
        ->name('penjadwalan.show.date');

    Route::get('penjadwalan/detail/{id}', [PenjadwalanController::class, 'showDetail'])
        ->name('penjadwalan.show.detail');

    Route::patch('penjadwalan/{id}/status', [PenjadwalanController::class, 'updateStatus'])
        ->name('penjadwalan.update.status');
});

Route::prefix('owner')->group(function () {
    Route::get('/login', [OwnerLoginController::class, 'showLoginForm'])
         ->name('owner.login');

    Route::post('/login', [OwnerLoginController::class, 'login'])
         ->name('owner.login.submit');
});

// Pengepul routes
Route::prefix('pengepul')->group(function () {
    Route::get('/login', [PengepulLoginController::class, 'showLoginForm'])
         ->name('pengepul.login');

    Route::post('/login', [PengepulLoginController::class, 'login'])
         ->name('pengepul.login.submit');
});

Route::post('/logout', function (Request $request) {
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

Route::prefix('pengepul')->group(function () {
    // Registration routes
    Route::get('/register', [\App\Http\Controllers\Homepage\Pengepul\PengepulRegisterController::class, 'showRegistrationForm'])
         ->name('pengepul.register');

    Route::post('/register', [\App\Http\Controllers\Homepage\Pengepul\PengepulRegisterController::class, 'register'])
         ->name('pengepul.register.submit');
});

// Protected profile routes
Route::middleware(['auth', 'role:pengepul'])->prefix('dashboard/pengepul')->group(function () {
    Route::get('/profil', [PengepulProfilController::class, 'show'])
         ->name('dashboard.pengepul.profile.show');

    Route::get('/profil/edit', [PengepulProfilController::class, 'edit'])
         ->name('dashboard.pengepul.profile.edit');

    Route::put('/profil', [PengepulProfilController::class, 'update'])
         ->name('dashboard.pengepul.profile.update');
});

Route::get('/forgot-password', [OwnerPasswordResetController::class, 'showForgotPasswordForm'])->name('forgot.password.form');
Route::post('/forgot-password', [OwnerPasswordResetController::class, 'sendOtp'])->name('forgot.password.send');
Route::get('/verify-otp', [OwnerPasswordResetController::class, 'showVerifyOtpForm'])->name('verify.otp.form');
Route::post('/verify-otp', [OwnerPasswordResetController::class, 'verifyOtp'])->name('verify.otp');
Route::get('/reset-password', [OwnerPasswordResetController::class, 'showResetPasswordForm'])->name('reset.password.form');
Route::post('/reset-password', [OwnerPasswordResetController::class, 'resetPassword'])->name('reset.password');
