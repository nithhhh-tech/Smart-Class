<?php

namespace App\Console\Commands;

use App\Models\Room;
use App\Models\Device;
use App\Models\SensorLog;
use App\Models\DeviceCommand;
use App\Models\Alert;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ProcessAutomation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-automation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process PIR motion and temperature automation for classrooms';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Starting Smart Classroom automation processor...");

        // Inactivity timeout configuration (Demo Mode: 30 seconds / Production Mode: 15 minutes)
        // Set this to 30 seconds for the live presentation, or read from env.
        $inactivitySeconds = env('AUTO_OFF_TIMEOUT_SECONDS', 30);
        $tempThreshold = env('AUTO_FAN_TEMP_THRESHOLD', 30.0);

        $rooms = Room::all();

        foreach ($rooms as $room) {
            $this->info("Processing Room: {$room->name}");

            // 1. Check if there are any recent logs to confirm the ESP32 is online
            $hasRecentLogs = SensorLog::where('room_id', $room->id)
                ->where('recorded_at', '>=', Carbon::now()->subMinutes(3))
                ->exists();

            if (!$hasRecentLogs) {
                $this->info("-> No recent sensor telemetry from Room {$room->name} in the last 3 minutes. Skipping automation.");
                continue;
            }

            // 2. Check if there has been ANY motion detected in the last X seconds
            $recentMotion = SensorLog::where('room_id', $room->id)
                ->where('motion', true)
                ->where('recorded_at', '>=', Carbon::now()->subSeconds($inactivitySeconds))
                ->exists();

            // Fetch the latest sensor readings
            $latestLog = SensorLog::where('room_id', $room->id)
                ->latest('recorded_at')
                ->first();

            // 3. ENERGY SAVING AUTOMATION: Temporarily disabled (PIR sensor hardware fix pending)
            /*
            if (!$recentMotion) {
                $this->info("-> No motion detected in the last {$inactivitySeconds} seconds.");

                // Turn off any active lights or fans to conserve energy
                $activeDevices = Device::where('room_id', $room->id)
                    ->where('status', true)
                    ->get();

                if ($activeDevices->isNotEmpty()) {
                    foreach ($activeDevices as $device) {
                        // Check if there is already a pending off command to avoid duplicate entries
                        $pendingOff = DeviceCommand::where('device_id', $device->id)
                            ->where('command', 'off')
                            ->where('status', 'pending')
                            ->exists();

                        if (!$pendingOff) {
                            DeviceCommand::create([
                                'device_id' => $device->id,
                                'command'   => 'off',
                                'status'    => 'pending',
                            ]);
                            $device->update(['status' => false]);
                            $this->info("-> Queued AUTO-OFF command for Device: {$device->name}");
                        }
                    }

                    // Log a system warning alert
                    Alert::create([
                        'room_id' => $room->id,
                        'type'    => 'warning',
                        'message' => "Energy Saving: Room empty for {$inactivitySeconds}s. Automatically shut down active device(s).",
                    ]);
                }
            } else {
                $this->info("-> Motion detected. Classroom is active.");
            }
            */

            // 4. CLIMATE CONTROL AUTOMATION: Turn on Fan if temperature is high (Bypassed PIR motion check for testing)
            if ($latestLog && $latestLog->temperature >= $tempThreshold) {
                $this->info("-> Temperature {$latestLog->temperature}°C is above threshold {$tempThreshold}°C.");

                // Find any fans in this room that are currently OFF
                $inactiveFans = Device::where('room_id', $room->id)
                    ->where('type', 'fan')
                    ->where('status', false)
                    ->get();

                if ($inactiveFans->isNotEmpty()) {
                    foreach ($inactiveFans as $fan) {
                        $pendingOn = DeviceCommand::where('device_id', $fan->id)
                            ->where('command', 'on')
                            ->where('status', 'pending')
                            ->exists();

                        if (!$pendingOn) {
                            DeviceCommand::create([
                                'device_id' => $fan->id,
                                'command'   => 'on',
                                'status'    => 'pending',
                            ]);
                            $fan->update(['status' => true]);
                            $this->info("-> Queued AUTO-ON command for Fan: {$fan->name}");
                        }
                    }

                    // Log an info alert
                    Alert::create([
                        'room_id' => $room->id,
                        'type'    => 'info',
                        'message' => "Climate Control: Temperature is high ({$latestLog->temperature}°C). Automatically activated fan.",
                    ]);
                }
            }

            // 5. HIGH-HEAT ANOMALY ALERT: Potential fire warning
            if ($latestLog && $latestLog->temperature >= 45.0) {
                // Check if this alert has already been created in the last 5 minutes to avoid spam
                $recentFireAlert = Alert::where('room_id', $room->id)
                    ->where('type', 'danger')
                    ->where('message', 'like', '%Fire Hazard%')
                    ->where('triggered_at', '>=', Carbon::now()->subMinutes(5))
                    ->exists();

                if (!$recentFireAlert) {
                    Alert::create([
                        'room_id' => $room->id,
                        'type'    => 'danger',
                        'message' => "CRITICAL: Extreme heat detected ({$latestLog->temperature}°C). Potential Fire Hazard in classroom!",
                    ]);
                    $this->info("-> CRITICAL FIRE WARNING ALERT TRIGGERED!");
                }
            }
        }

        $this->info("Smart Classroom automation completed.");
    }
}
