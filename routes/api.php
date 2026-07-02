<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SensorLogController;
use App\Http\Controllers\DeviceCommandController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\AuthController;

// ── Public: Device Authentication ───────────────────────────
Route::post('/device/login', [AuthController::class, 'deviceLogin']);

// ── Protected: ESP32 Endpoints ──────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Sensor data
    Route::post('/sensor-logs',           [SensorLogController::class, 'store']);
    Route::get('/sensor-logs/latest',     [SensorLogController::class, 'latest']);

    // Commands
    Route::get('/commands/pending',       [DeviceCommandController::class, 'pending']);
    Route::put('/commands/{id}/executed', [DeviceCommandController::class, 'markExecuted']);

    // Devices
    Route::apiResource('/devices', DeviceController::class);
    Route::post('/devices/{id}/toggle',   [DeviceController::class, 'toggle']);

    // Rooms
    Route::apiResource('/rooms', RoomController::class);

    // Dashboard data
    Route::get('/dashboard/summary',      [SensorLogController::class, 'summary']);
    Route::get('/sensor-logs/history',    [SensorLogController::class, 'history']);
});
