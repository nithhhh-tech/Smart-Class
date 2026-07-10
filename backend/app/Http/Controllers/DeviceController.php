<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\DeviceCommand;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    // GET /api/devices?room_id=1
    public function index(Request $request)
    {
        $query = Device::with('room');
        if ($request->has('room_id')) {
            $query->where('room_id', $request->room_id);
        }
        $devices = $query->get();
        return response()->json(['data' => $devices]);
    }

    // POST /api/devices
    public function store(Request $request)
    {
        $data = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'name'    => 'required|string|max:255',
            'type'    => 'required|in:light,fan',
            'status'  => 'boolean',
        ]);

        $device = Device::create($data);
        return response()->json(['message' => 'Device created', 'data' => $device], 201);
    }

    // GET /api/devices/{id}
    public function show($id)
    {
        $device = Device::with('room')->findOrFail($id);
        return response()->json(['data' => $device]);
    }

    // PUT/PATCH /api/devices/{id}
    public function update(Request $request, $id)
    {
        $device = Device::findOrFail($id);
        $data = $request->validate([
            'room_id' => 'sometimes|required|exists:rooms,id',
            'name'    => 'sometimes|required|string|max:255',
            'type'    => 'sometimes|required|in:light,fan',
            'status'  => 'boolean',
        ]);

        $device->update($data);
        return response()->json(['message' => 'Device updated', 'data' => $device]);
    }

    // DELETE /api/devices/{id}
    public function destroy($id)
    {
        $device = Device::findOrFail($id);
        $device->delete();
        return response()->json(['message' => 'Device deleted']);
    }

    // POST /api/devices/{id}/toggle
    public function toggle($id)
    {
        $device = Device::findOrFail($id);

        // Check if there is a pending command to determine the current effective status
        $pendingCommand = DeviceCommand::where('device_id', $device->id)
            ->where('status', 'pending')
            ->latest()
            ->first();

        $effectiveStatus = $device->status;
        if ($pendingCommand) {
            $effectiveStatus = ($pendingCommand->command === 'on');
        }

        $newStatus = !$effectiveStatus;
        $targetCommand = $newStatus ? 'on' : 'off';

        // Update device status
        $device->update(['status' => $newStatus]);

        // Queue a command for the ESP32 to pick up
        $commandRecord = DeviceCommand::create([
            'device_id' => $device->id,
            'command'   => $targetCommand,
            'status'    => 'pending',
        ]);

        return response()->json([
            'message' => "Toggle command '$targetCommand' queued",
            'data'    => [
                'id'        => $commandRecord->id,
                'device_id' => $device->id,
                'command'   => $targetCommand,
                'status'    => $newStatus, // Retain boolean for frontend UI toggles
            ],
        ]);
    }
}
