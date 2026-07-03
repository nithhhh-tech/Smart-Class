<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SensorLogController;
use App\Http\Controllers\DeviceCommandController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AlertController;

// ── Public: User Authentication ─────────────────────────────
Route::post('/register', [ApiAuthController::class, 'register']);
Route::post('/login', [ApiAuthController::class, 'login']);

// ── Public: Device Authentication ───────────────────────────
Route::post('/device/login', [AuthController::class, 'deviceLogin']);

// ── Protected API Endpoints ─────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {
    // User Session actions
    Route::post('/logout', [ApiAuthController::class, 'logout']);
    Route::get('/user', [ApiAuthController::class, 'me']);

    // Classroom & Device Resources
    Route::apiResource('/rooms', RoomController::class);
    Route::apiResource('/devices', DeviceController::class);
    Route::post('/devices/{id}/toggle', [DeviceController::class, 'toggle']);

    // Schedules & Alerts
    Route::apiResource('/schedules', ScheduleController::class);
    Route::apiResource('/alerts', AlertController::class)->only(['index', 'destroy']);

    // Telemetry Dashboard
    Route::get('/dashboard/summary', [SensorLogController::class, 'summary']);
    Route::get('/sensor-logs/history', [SensorLogController::class, 'history']);
    Route::get('/sensor-logs/latest', [SensorLogController::class, 'latest']);

    // ESP32 / device endpoints
    Route::post('/sensor-logs', [SensorLogController::class, 'store']);
    Route::get('/commands/pending', [DeviceCommandController::class, 'pending']);
    Route::put('/commands/{id}/executed', [DeviceCommandController::class, 'markExecuted']);
});

