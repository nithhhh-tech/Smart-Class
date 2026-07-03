<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // POST /api/device/login
    public function deviceLogin(Request $request)
    {
        $request->validate([
            'email'       => 'required|email',
            'password'    => 'required',
            'device_name' => 'nullable|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Standard Sanctum Token Generation
        $deviceName = $request->input('device_name', 'ESP32-Device');
        $token = $user->createToken($deviceName)->plainTextToken;

        return response()->json([
            'token'   => $token,
            'message' => 'Login successful',
        ]);
    }
}
