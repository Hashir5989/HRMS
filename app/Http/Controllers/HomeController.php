<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Department;
use App\Models\LeaveApplication;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();
        $today = Carbon::today();

        // Stats Cards
        $totalEmployees = User::count();
        $totalProjects = Project::count();
        $totalTasks = Task::count();
        $pendingLeaves = LeaveApplication::where('status', 'pending')->count();

        // Tasks by status
        $tasksByStatus = Task::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Projects by status
        $projectsByStatus = Project::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Recent tasks assigned to user
        $myTasks = Task::where('assigned_user_id', $user->id)
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        // Recent leave applications
        $recentLeaves = LeaveApplication::with(['user', 'leaveType'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Today's attendance
        $todayAttendance = Attendance::where('date', $today)
            ->with('user')
            ->take(10)
            ->get();

        $presentToday = Attendance::where('date', $today)
            ->where('status', 'present')
            ->count();

        // Attendance trends (last 7 days) — single query instead of 7
        $startDate = Carbon::today()->subDays(6);
        $trendCounts = Attendance::where('date', '>=', $startDate->toDateString())
            ->where('status', 'present')
            ->selectRaw('date, count(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();

        $last7Days = [];
        $attendanceData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $last7Days[] = $date->format('M d');
            $attendanceData[] = $trendCounts[$date->toDateString()] ?? 0;
        }

        // Leave distribution
        $leavesByStatus = LeaveApplication::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Departments count
        $totalDepartments = Department::count();

        // Recent projects
        $recentProjects = Project::withCount(['tasks', 'tasks as completed_tasks_count' => function ($query) {
            $query->where('status', 'Completed');
        }])->orderBy('created_at', 'desc')->take(4)->get();

        return view('home', compact(
            'totalEmployees', 'totalProjects', 'totalTasks', 'pendingLeaves',
            'tasksByStatus', 'projectsByStatus', 'myTasks', 'recentLeaves',
            'todayAttendance', 'presentToday', 'totalDepartments', 'recentProjects',
            'last7Days', 'attendanceData', 'leavesByStatus'
        ));
    }
}
