<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SensorLogController;
use App\Http\Controllers\DeviceCommandController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\AuthController;

// ── Public: Device Authentication ───────────────────────────
Route::post('/device/login', [AuthController::class, 'deviceLogin']);

// ── Web UI routes: Blade dashboard and management pages ─────
Route::middleware('auth')->group(function () {
    Route::apiResource('/rooms', RoomController::class);
    Route::apiResource('/devices', DeviceController::class);
    Route::post('/devices/{id}/toggle', [DeviceController::class, 'toggle']);

    Route::get('/dashboard/summary', [SensorLogController::class, 'summary']);
    Route::get('/sensor-logs/history', [SensorLogController::class, 'history']);
    Route::get('/sensor-logs/latest', [SensorLogController::class, 'latest']);
});

// ── Protected: ESP32 / device endpoints ──────────────────────
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/sensor-logs', [SensorLogController::class, 'store']);
    Route::get('/commands/pending', [DeviceCommandController::class, 'pending']);
    Route::put('/commands/{id}/executed', [DeviceCommandController::class, 'markExecuted']);
});
