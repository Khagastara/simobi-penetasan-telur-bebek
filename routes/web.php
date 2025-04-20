<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\Owner\OwnerBuatjadwalController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// routes/web.php
// routes/web.php
Route::prefix('jadwal')->group(function () {
    Route::get('/', [OwnerBuatjadwalController::class, 'index'])->name('penjadwalan.index');
    Route::get('/{date}', [OwnerBuatjadwalController::class, 'showDate'])->name('penjadwalan.show');
    Route::post('/', [OwnerBuatjadwalController::class, 'store'])->name('penjadwalan.store');
    Route::patch('/{id}/status', [OwnerBuatjadwalController::class, 'updateStatus'])->name('penjadwalan.update-status');
});
