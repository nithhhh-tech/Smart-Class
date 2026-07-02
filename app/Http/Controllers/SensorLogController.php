<?php

namespace App\Http\Controllers;

use App\Models\SensorLog;
use Illuminate\Http\Request;

class SensorLogController extends Controller
{
    // POST /api/sensor-logs  (called by ESP32)
    public function store(Request $request)
    {
        $data = $request->validate([
            'room_id'     => 'required|exists:rooms,id',
            'temperature' => 'required|numeric',
            'humidity'    => 'required|numeric',
            'motion'      => 'required|boolean',
        ]);

        $log = SensorLog::create($data);

        // Broadcast to dashboard via Pusher (real-time)
        // event(new SensorDataReceived($log));

        return response()->json(['message' => 'Logged', 'data' => $log], 201);
    }

    // GET /api/sensor-logs/latest?room_id=1
    public function latest(Request $request)
    {
        $log = SensorLog::where('room_id', $request->room_id)
                        ->latest('recorded_at')
                        ->first();

        return response()->json(['data' => $log]);
    }

    // GET /api/sensor-logs/history?room_id=1&hours=24
    public function history(Request $request)
    {
        $hours = $request->get('hours', 24);
        $logs  = SensorLog::where('room_id', $request->room_id)
                          ->where('recorded_at', '>=', now()->subHours($hours))
                          ->orderBy('recorded_at')
                          ->get();

        return response()->json(['data' => $logs]);
    }

    // GET /api/dashboard/summary
    public function summary(Request $request)
    {
        $roomId = $request->get('room_id', 1);
        $latest = SensorLog::where('room_id', $roomId)->latest('recorded_at')->first();

        return response()->json([
            'data' => [
                'temperature' => $latest?->temperature,
                'humidity'    => $latest?->humidity,
                'motion'      => $latest?->motion,
                'recorded_at' => $latest?->recorded_at,
            ]
        ]);
    }
}
