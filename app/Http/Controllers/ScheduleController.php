<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    // GET /api/schedules?room_id=1
    public function index(Request $request)
    {
        $query = Schedule::with(['room', 'device']);
        
        if ($request->has('room_id')) {
            $query->where('room_id', $request->room_id);
        }

        $schedules = $query->latest()->get();

        return response()->json(['data' => $schedules]);
    }

    // POST /api/schedules
    public function store(Request $request)
    {
        $data = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'device_id' => 'required|exists:devices,id',
            'action' => 'required|in:on,off',
            'run_at' => 'required|date_format:H:i',
            'days' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $schedule = Schedule::create($data);

        return response()->json([
            'message' => 'Schedule created successfully',
            'data' => $schedule->load(['room', 'device'])
        ], 201);
    }

    // GET /api/schedules/{id}
    public function show($id)
    {
        $schedule = Schedule::with(['room', 'device'])->findOrFail($id);
        return response()->json(['data' => $schedule]);
    }

    // PUT/PATCH /api/schedules/{id}
    public function update(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);

        $data = $request->validate([
            'room_id' => 'sometimes|required|exists:rooms,id',
            'device_id' => 'sometimes|required|exists:devices,id',
            'action' => 'sometimes|required|in:on,off',
            'run_at' => 'sometimes|required|date_format:H:i',
            'days' => 'sometimes|required|string',
            'is_active' => 'boolean',
        ]);

        $schedule->update($data);

        return response()->json([
            'message' => 'Schedule updated successfully',
            'data' => $schedule->load(['room', 'device'])
        ]);
    }

    // DELETE /api/schedules/{id}
    public function destroy($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();

        return response()->json(['message' => 'Schedule deleted successfully']);
    }
}
