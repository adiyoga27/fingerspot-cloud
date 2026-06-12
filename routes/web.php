<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FingerspotController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('login', function () {
    return view('content.auth.login');
})->name('login');

Route::post('login', [AuthController::class, 'verify']);

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});

Route::get('test', [FingerspotController::class, 'test2']);
