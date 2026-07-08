<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        DB::table('users')->insert([
            'id'         => 1,
            'name'       => 'Admin',
            'email'      => 'admin@smartclass.com',
            'password'   => Hash::make('password'),
            'role'       => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Sample room
        DB::table('rooms')->insert([
            'id'         => 1,
            'name'       => 'Class A',
            'location'   => 'Block 1, Floor 1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Devices for room 1
        DB::table('devices')->insert([
            ['room_id' => 1, 'name' => 'Main Light', 'type' => 'light', 'status' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['room_id' => 1, 'name' => 'Ceiling Fan',  'type' => 'fan',   'status' => 0, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Seed the API token used by the ESP32 (Token ID: 1, Token string: f61I8PuWummDzYalEs3SKodxnGDB5JHKMhakdZORfd229664)
        $plainToken = 'f61I8PuWummDzYalEs3SKodxnGDB5JHKMhakdZORfd229664';
        $hashedToken = hash('sha256', $plainToken);

        DB::table('personal_access_tokens')->insert([
            'id'             => 1,
            'tokenable_type' => 'App\Models\User',
            'tokenable_id'   => 1,
            'name'           => 'ESP32-Device',
            'token'          => $hashedToken,
            'abilities'      => json_encode(['*']),
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);
    }
}
