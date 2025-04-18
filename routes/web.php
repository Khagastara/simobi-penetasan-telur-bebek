<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\;

Route::resource('akuns', App\Http\Controllers\app\http\Controllers::class);

Route::get('/akuns', [App\Http\Controllers\app\http\Controllers::class, 'index']);
Route::post('/akuns', [App\Http\Controllers\app\http\Controllersr::class, 'store']);
