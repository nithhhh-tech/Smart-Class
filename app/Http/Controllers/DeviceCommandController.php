<?php

namespace App\Http\Controllers;

use App\Models\DeviceCommand;
use App\Models\Device;
use Illuminate\Http\Request;

class DeviceCommandController extends Controller
{
    // GET /api/commands/pending?room_id=1  (polled by ESP32)
    public function pending(Request $request)
    {
        $commands = DeviceCommand::with('device')
            ->whereHas('device', fn($q) => $q->where('room_id', $request->room_id))
            ->where('status', 'pending')
            ->get()
            ->map(fn($cmd) => [
                'id'          => $cmd->id,
                'device_type' => $cmd->device->type,
                'command'     => $cmd->command,
            ]);

        return response()->json(['data' => $commands]);
    }

    // PUT /api/commands/{id}/executed  (called by ESP32 after execution)
    public function markExecuted($id)
    {
        $cmd = DeviceCommand::findOrFail($id);
        $cmd->update(['status' => 'executed']);

        // Sync device status
        $cmd->device->update(['status' => $cmd->command === 'on' ? 1 : 0]);

        return response()->json(['message' => 'Command marked as executed']);
    }

    // POST from web dashboard to send a command
    public function sendCommand(Request $request)
    {
        $data = $request->validate([
            'device_id' => 'required|exists:devices,id',
            'command'   => 'required|in:on,off',
        ]);

        $cmd = DeviceCommand::create([
            'device_id' => $data['device_id'],
            'command'   => $data['command'],
            'status'    => 'pending',
        ]);

        return response()->json(['message' => 'Command queued', 'data' => $cmd], 201);
    }
}
