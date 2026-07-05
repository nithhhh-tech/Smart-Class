<?php

namespace App\Console\Commands;

use App\Models\Schedule;
use App\Models\DeviceCommand;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ProcessSchedules extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-schedules';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process active schedules and queue device commands';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        $currentTime = $now->format('H:i');
        $currentDay = strtolower($now->format('D')); // e.g. mon, tue, wed...

        $this->info("Scanning schedules matching Day: {$currentDay} | Time: {$currentTime}");

        // Check if today is a registered holiday
        $today = Carbon::today()->toDateString();
        $isHoliday = \App\Models\Holiday::where('holiday_date', $today)->exists();

        if ($isHoliday) {
            $this->info("Today is a registered holiday ({$today}). Skipping schedule automation.");
            return;
        }

        $schedules = Schedule::where('is_active', true)->get();
        $triggeredCount = 0;

        foreach ($schedules as $schedule) {
            // Normalize database time run_at to H:i
            $scheduleTime = Carbon::parse($schedule->run_at)->format('H:i');

            if ($scheduleTime !== $currentTime) {
                continue;
            }

            // Parse schedule active days
            $daysArray = array_map('trim', explode(',', strtolower($schedule->days)));

            if (!in_array($currentDay, $daysArray)) {
                continue;
            }

            // Check if there is already an identical pending command to prevent duplicate runs
            $commandExists = DeviceCommand::where('device_id', $schedule->device_id)
                ->where('command', $schedule->action)
                ->where('status', 'pending')
                ->exists();

            if (!$commandExists) {
                DeviceCommand::create([
                    'device_id' => $schedule->device_id,
                    'command' => $schedule->action,
                    'status' => 'pending',
                ]);
                
                $triggeredCount++;
                $this->info("Queued scheduled command '{$schedule->action}' for Device ID {$schedule->device_id} (Schedule #{$schedule->id})");
            }
        }

        $this->info("Scan completed. Queued {$triggeredCount} command(s).");
    }
}
