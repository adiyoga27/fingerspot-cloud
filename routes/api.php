<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FingerspotController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::resource('clients', ClientController::class);
Route::resource('employee', EmployeeController::class);
Route::post('webhook', [FingerspotController::class, 'webhook']);
Route::resource('attendance', AttendanceController::class);