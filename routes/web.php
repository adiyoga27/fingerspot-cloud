<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FingerspotController;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

Route::get('/', function () {
    return view('content.dashboard');
});

Route::get('login', function(){
    return view('content.auth.login');
});

Route::post('login', [AuthController::class, 'verify']);
Route::get('test', [FingerspotController::class, 'test']);