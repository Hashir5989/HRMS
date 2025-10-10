<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['Employee', 'Team Lead', 'Manager', 'HR']);
        })->get();

        if ($users->isEmpty()) return;

        // Seed attendance for the last 14 working days
        $workingDays = [];
        $date = Carbon::now()->subDays(20);
        while (count($workingDays) < 14) {
            if (!$date->isWeekend()) {
                $workingDays[] = $date->copy();
            }
            $date->addDay();
        }

        foreach ($users as $user) {
            foreach ($workingDays as $day) {
                // Skip some days randomly to simulate absences/leaves
                $rand = rand(1, 10);
                if ($rand <= 1) {
                    // absent ~10%
                    continue;
                }

                $status = 'present';
                $clockInHour = 9;
                $clockInMinute = rand(0, 20);

                if ($rand === 2) {
                    // late ~10%
                    $status = 'late';
                    $clockInHour = 10;
                    $clockInMinute = rand(5, 45);
                }

                $clockIn  = $day->copy()->setTime($clockInHour, $clockInMinute, rand(0, 59));
                $clockOut = $clockIn->copy()->addHours(rand(7, 9))->addMinutes(rand(0, 59));
                $totalHours = round($clockIn->diffInMinutes($clockOut) / 60, 2);

                Attendance::firstOrCreate(
                    ['user_id' => $user->id, 'date' => $day->toDateString()],
                    [
                        'clock_in'    => $clockIn->format('H:i:s'),
                        'clock_out'   => $clockOut->format('H:i:s'),
                        'total_hours' => $totalHours,
                        'status'      => $status,
                        'ip_address'  => '192.168.1.' . rand(1, 50),
                    ]
                );
            }
        }
    }
}
