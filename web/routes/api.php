<?php

use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\StayController;
use App\Http\Controllers\Api\TenantController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    // Tenants
    Route::get('tenants', [TenantController::class, 'index']);
    Route::get('tenants/{id}', [TenantController::class, 'show']);
    Route::get('tenants/phone/{phone}', [TenantController::class, 'findByPhone']);
    Route::post('tenants', [TenantController::class, 'store']);

    // Rooms
    Route::get('rooms', [RoomController::class, 'index']);
    Route::get('rooms/available', [RoomController::class, 'available']);
    Route::get('rooms/{id}', [RoomController::class, 'show']);

    // Stays
    Route::get('stays', [StayController::class, 'index']);
    Route::get('stays/active', [StayController::class, 'active']);
    Route::post('stays', [StayController::class, 'store']);
    Route::get('stays/{id}', [StayController::class, 'show']);
    Route::put('stays/{id}', [StayController::class, 'update']);

    // Payments
    Route::get('payments', [PaymentController::class, 'index']);
    Route::post('payments', [PaymentController::class, 'store']);
    Route::get('payments/{id}', [PaymentController::class, 'show']);
});
