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

        $stats = [
            'present' => Attendance::where('date', $date)->where('status', 'present')->count(),
            'absent' => User::count() - Attendance::where('date', $date)->whereIn('status', ['present', 'late', 'half_day'])->count(),
            'late' => Attendance::where('date', $date)->where('status', 'late')->count(),
            'on_leave' => Attendance::where('date', $date)->where('status', 'on_leave')->count(),
        ];

        $myAttendance = Attendance::where('user_id', $user->id)->where('date', Carbon::today())->first();

        return view('attendance.index', compact('attendances', 'stats', 'date', 'myAttendance'));
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

        return back()->with('success', 'Clocked in successfully at ' . $now->format('h:i A'));
    }

    public function clockOut()
    {
        $attendance = Attendance::where('user_id', auth()->id())
            ->where('date', Carbon::today())
            ->first();

        if (!$attendance) {
            return back()->with('error', 'You need to clock in first.');
        }

        if ($attendance->clock_out) {
            return back()->with('error', 'You have already clocked out today.');
        }

        $clockIn = Carbon::parse($attendance->clock_in);
        $clockOut = Carbon::now();
        $totalHours = $clockIn->diffInMinutes($clockOut) / 60;

        $attendance->update([
            'clock_out' => $clockOut->format('H:i:s'),
            'total_hours' => round($totalHours, 2),
        ]);

        return back()->with('success', 'Clocked out successfully. Total hours: ' . round($totalHours, 2));
    }
}
