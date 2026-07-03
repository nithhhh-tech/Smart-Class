<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    // GET /api/alerts?room_id=1
    public function index(Request $request)
    {
        $query = Alert::with('room');

        if ($request->has('room_id')) {
            $query->where('room_id', $request->room_id);
        }

        $alerts = $query->latest('triggered_at')->get();

        return response()->json(['data' => $alerts]);
    }

    // DELETE /api/alerts/{id}
    public function destroy($id)
    {
        $alert = Alert::findOrFail($id);
        $alert->delete();

        return response()->json(['message' => 'Alert dismissed successfully']);
    }
}
