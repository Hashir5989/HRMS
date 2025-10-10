<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use App\Models\Project;
use App\Models\Task;
use App\Models\LeaveApplication;
use App\Models\Attendance;
use App\Models\Department;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

        // Departments count
        $totalDepartments = Department::count();

        // Recent projects
        $recentProjects = Project::orderBy('created_at', 'desc')->take(4)->get();

        return view('home', compact(
            'totalEmployees', 'totalProjects', 'totalTasks', 'pendingLeaves',
            'tasksByStatus', 'projectsByStatus', 'myTasks', 'recentLeaves',
            'todayAttendance', 'presentToday', 'totalDepartments', 'recentProjects'
        ));
    }
}
