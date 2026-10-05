<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\TenantController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ReportController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/rooms', [RoomController::class, 'index']);
Route::get('/tenants', [TenantController::class, 'index']);
Route::get('/dashboard', [DashboardController::class, 'index']);
Route::get('/payments', [PaymentController::class, 'index']);
Route::get('/reports', [ReportController::class, 'index']);
