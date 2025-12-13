<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));
        $user = auth()->user();

        if ($user->hasAnyRole(['Super Admin', 'Admin', 'HR'])) {
            $attendances = Attendance::with('user')
                ->where('date', $date)
                ->orderBy('clock_in')
                ->paginate(20);
        } else {
            $attendances = Attendance::with('user')
                ->where('user_id', $user->id)
                ->orderBy('date', 'desc')
                ->paginate(20);
        }

        $statusCounts = Attendance::where('date', $date)
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $totalUsersCount = User::count();
        $presentAndWorking = ($statusCounts['present'] ?? 0) + ($statusCounts['late'] ?? 0) + ($statusCounts['half_day'] ?? 0);

        $stats = [
            'present' => $statusCounts['present'] ?? 0,
            'absent' => $totalUsersCount - $presentAndWorking,
            'late' => $statusCounts['late'] ?? 0,
            'on_leave' => $statusCounts['on_leave'] ?? 0,
        ];

        $myAttendance = Attendance::where('user_id', $user->id)->where('date', Carbon::today())->first();

        $elapsedMinutes = 0;
        $canClockOut = false;
        if ($myAttendance && $myAttendance->clock_in) {
            $clockIn = Carbon::parse($myAttendance->clock_in);
            $elapsedMinutes = $clockIn->diffInMinutes(Carbon::now());
            // Can clock out strictly only when 8 hours (480 mins) completed
            $canClockOut = ($elapsedMinutes >= 480);
        }

        return view('attendance.index', compact('attendances', 'stats', 'date', 'myAttendance', 'elapsedMinutes', 'canClockOut'));
    }

    public function clockIn(Request $request)
    {
        $today = Carbon::today();
        $existing = Attendance::where('user_id', auth()->id())->where('date', $today)->first();

        if ($existing) {
            return back()->with('error', 'You have already clocked in today.');
        }

        $now = Carbon::now();
        $status = $now->hour >= 10 ? 'late' : 'present';

        Attendance::create([
            'user_id' => auth()->id(),
            'date' => $today,
            'clock_in' => $now->format('H:i:s'),
            'status' => $status,
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Clocked in successfully at '.$now->format('h:i A'));
    }

    public function clockOut()
    {
        $attendance = Attendance::where('user_id', auth()->id())
            ->where('date', Carbon::today())
            ->first();

        if (! $attendance) {
            return back()->with('error', 'You need to clock in first.');
        }

        if ($attendance->clock_out) {
            return back()->with('error', 'You have already clocked out today.');
        }

        $clockIn = Carbon::parse($attendance->clock_in);
        $clockOut = Carbon::now();
        $elapsedMinutes = $clockIn->diffInMinutes($clockOut);

        // Enforce 8-hour working time before clock out
        if ($elapsedMinutes < 480) {
            $remainingMins = 480 - $elapsedMinutes;
            $remH = floor($remainingMins / 60);
            $remM = $remainingMins % 60;

            return back()->with('error', "Clock out is not allowed during your 8-hour working shift. Remaining time: {$remH}h {$remM}m.");
        }

        // If employee is on break, auto end break
        $breakMinutes = $attendance->break_minutes ?? 0;
        if ($attendance->on_break && $attendance->break_start) {
            $breakStart = Carbon::parse($attendance->break_start);
            $breakMinutes = max(1, $breakStart->diffInMinutes($clockOut));
            $attendance->break_end = $clockOut->format('H:i:s');
            $attendance->break_minutes = $breakMinutes;
            $attendance->on_break = false;
        }

        $totalRawMinutes = $clockIn->diffInMinutes($clockOut);
        $netWorkingMinutes = max(0, $totalRawMinutes - $breakMinutes);
        $totalHours = round($netWorkingMinutes / 60, 2);

        $attendance->update([
            'clock_out' => $clockOut->format('H:i:s'),
            'total_hours' => $totalHours,
            'break_end' => $attendance->break_end,
            'break_minutes' => $breakMinutes,
            'on_break' => false,
        ]);

        $msg = 'Clocked out successfully. Total hours: '.$totalHours.'h';
        if ($breakMinutes > 0) {
            $msg .= ' ('.$breakMinutes.' mins break deducted)';
        }

        return back()->with('success', $msg);
    }

    public function startBreak(Request $request)
    {
        $request->validate([
            'break_reason' => 'required|string|max:500',
        ]);

        $attendance = Attendance::where('user_id', auth()->id())
            ->where('date', Carbon::today())
            ->first();

        if (! $attendance) {
            return back()->with('error', 'You need to clock in first.');
        }

        if ($attendance->clock_out) {
            return back()->with('error', 'You have already clocked out for today.');
        }

        if ($attendance->break_start) {
            return back()->with('error', 'Only 1 break is allowed per day. You have already taken your break today.');
        }

        if ($attendance->on_break) {
            return back()->with('error', 'You are already on a break.');
        }

        $now = Carbon::now();
        $attendance->update([
            'break_start' => $now->format('H:i:s'),
            'break_reason' => $request->input('break_reason'),
            'on_break' => true,
        ]);

        return back()->with('success', 'Break started at '.$now->format('h:i A').'. Reason: '.$request->input('break_reason'));
    }

    public function endBreak()
    {
        $attendance = Attendance::where('user_id', auth()->id())
            ->where('date', Carbon::today())
            ->first();

        if (! $attendance || ! $attendance->on_break) {
            return back()->with('error', 'You are not currently on a break.');
        }

        $now = Carbon::now();
        $breakStart = Carbon::parse($attendance->break_start);
        $durationMinutes = max(1, $breakStart->diffInMinutes($now));

        $attendance->update([
            'break_end' => $now->format('H:i:s'),
            'break_minutes' => $durationMinutes,
            'on_break' => false,
        ]);

        return back()->with('success', 'Break ended successfully ('.$durationMinutes.' mins). Resumed work!');
    }
}
