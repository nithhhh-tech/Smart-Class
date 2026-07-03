<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    // GET /api/rooms
    public function index()
    {
        $rooms = Room::withCount('devices')->get();
        return response()->json(['data' => $rooms]);
    }

    // POST /api/rooms
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        $room = Room::create($data);
        return response()->json(['message' => 'Room created', 'data' => $room], 201);
    }

    // GET /api/rooms/{id}
    public function show($id)
    {
        $room = Room::with('devices')->findOrFail($id);
        return response()->json(['data' => $room]);
    }

    // PUT/PATCH /api/rooms/{id}
    public function update(Request $request, $id)
    {
        $room = Room::findOrFail($id);
        $data = $request->validate([
            'name'     => 'sometimes|required|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        $room->update($data);
        return response()->json(['message' => 'Room updated', 'data' => $room]);
    }

    // DELETE /api/rooms/{id}
    public function destroy($id)
    {
        $room = Room::findOrFail($id);
        $room->delete();
        return response()->json(['message' => 'Room deleted']);
    }
}
