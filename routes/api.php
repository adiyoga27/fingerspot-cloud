<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FingerspotController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('login', [AuthController::class, 'login']);
Route::post('registration', [AuthController::class, 'registration']);
Route::middleware('auth:sanctum')->group(function () {
    Route::resource('clients', ClientController::class);
    Route::resource('employee', EmployeeController::class);
    Route::resource('attendance', AttendanceController::class);
    Route::get('attendance',[AttendanceController::class, 'getAttendanceByDate']);
});
Route::post('webhook', [FingerspotController::class, 'webhook']);