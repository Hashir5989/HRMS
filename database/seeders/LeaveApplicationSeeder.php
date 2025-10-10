<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LeaveApplication;
use App\Models\LeaveType;
use App\Models\User;
use Carbon\Carbon;

class LeaveApplicationSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['Employee', 'Team Lead', 'Manager']);
        })->get();

        $leaveTypes = LeaveType::all();
        $adminUser  = User::where('email', 'admin@example.com')->first();

        if ($users->isEmpty() || $leaveTypes->isEmpty()) {
            return;
        }

        $applications = [
            // Approved leaves in the past
            ['email' => 'emily.davis@company.com',     'type' => 'Annual Leave',  'start' => '2025-10-06', 'end' => '2025-10-08', 'status' => 'approved',  'reason' => 'Family vacation trip.'],
            ['email' => 'james.wilson@company.com',    'type' => 'Sick Leave',    'start' => '2025-10-02', 'end' => '2025-10-03', 'status' => 'approved',  'reason' => 'Fever and cold.'],
            ['email' => 'olivia.martinez@company.com', 'type' => 'Casual Leave',  'start' => '2025-10-09', 'end' => '2025-10-09', 'status' => 'approved',  'reason' => 'Personal errands.'],
            ['email' => 'david.kim@company.com',       'type' => 'Annual Leave',  'start' => '2025-09-25', 'end' => '2025-09-26', 'status' => 'approved',  'reason' => 'Short break.'],
            // Pending leaves
            ['email' => 'jessica.brown@company.com',  'type' => 'Casual Leave',  'start' => '2025-10-13', 'end' => '2025-10-14', 'status' => 'pending',   'reason' => 'Personal appointment.'],
            ['email' => 'robert.taylor@company.com',  'type' => 'Annual Leave',  'start' => '2025-10-20', 'end' => '2025-10-24', 'status' => 'pending',   'reason' => 'Pre-planned vacation.'],
            ['email' => 'amanda.garcia@company.com',  'type' => 'Sick Leave',    'start' => '2025-10-10', 'end' => '2025-10-10', 'status' => 'pending',   'reason' => 'Not feeling well.'],
            // Rejected
            ['email' => 'chris.patel@company.com',    'type' => 'Annual Leave',  'start' => '2025-10-01', 'end' => '2025-10-05', 'status' => 'rejected',  'reason' => 'Urgent project deadline.'],
            // Work From Home
            ['email' => 'daniel.lee@company.com',     'type' => 'Work From Home','start' => '2025-10-09', 'end' => '2025-10-09', 'status' => 'approved',  'reason' => 'Internet maintenance at office.'],
            ['email' => 'laura.thompson@company.com', 'type' => 'Work From Home','start' => '2025-10-10', 'end' => '2025-10-10', 'status' => 'approved',  'reason' => 'Personal preference.'],
        ];

        foreach ($applications as $app) {
            $user = User::where('email', $app['email'])->first();
            $leaveType = $leaveTypes->firstWhere('name', $app['type']);

            if (!$user || !$leaveType) continue;

            $start = Carbon::parse($app['start']);
            $end   = Carbon::parse($app['end']);
            $days  = $start->diffInDays($end) + 1;

            LeaveApplication::firstOrCreate(
                ['user_id' => $user->id, 'start_date' => $app['start']],
                [
                    'leave_type_id' => $leaveType->id,
                    'start_date'    => $app['start'],
                    'end_date'      => $app['end'],
                    'total_days'    => $days,
                    'reason'        => $app['reason'],
                    'status'        => $app['status'],
                    'approved_by'   => in_array($app['status'], ['approved', 'rejected']) ? $adminUser?->id : null,
                    'admin_remarks' => $app['status'] === 'rejected' ? 'Leave denied due to critical project timeline.' : null,
                    'responded_at'  => in_array($app['status'], ['approved', 'rejected']) ? now()->subDays(rand(1, 5)) : null,
                ]
            );
        }
    }
}
