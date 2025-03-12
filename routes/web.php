<?php

use App\Http\Controllers\PointsController;
use App\Http\Controllers\TableController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PointsController::class, 'index'])->name('map');

Route::get('/table', [TableController::class, 'index'])->name('table');

Route::post('/point-store', [PointsController::class, 'store'])->name('point.store');

Route::resource('points', PointsController::class);
