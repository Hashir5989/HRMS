@extends('layouts.admin')

@section('title', 'Dashboard - HRMS Pro')

@section('content')
<div class="container-fluid">
    <!-- Welcome Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold text-dark mb-1">Welcome back, {{ auth()->user()->name }} 👋</h3>
                    <p class="text-muted mb-0">Here's what's happening with your organization today.</p>
                </div>
                <div class="text-muted d-none d-md-block">
                    <i class="bi bi-calendar3 me-1"></i>{{ now()->format('l, F j, Y') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 stat-card" style="border-left: 4px solid #4f46e5 !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1 text-uppercase fw-semibold ls-wide">Total Employees</p>
                            <h2 class="fw-bold mb-0 text-dark">{{ $totalEmployees }}</h2>
                        </div>
                        <div class="stat-icon bg-primary-subtle text-primary rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-people-fill fs-4"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <span class="badge bg-success-subtle text-success"><i class="bi bi-arrow-up"></i> Active</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 stat-card" style="border-left: 4px solid #059669 !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1 text-uppercase fw-semibold ls-wide">Projects</p>
                            <h2 class="fw-bold mb-0 text-dark">{{ $totalProjects }}</h2>
                        </div>
                        <div class="stat-icon bg-success-subtle text-success rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-kanban-fill fs-4"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <span class="badge bg-info-subtle text-info">{{ $projectsByStatus['in_progress'] ?? 0 }} In Progress</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 stat-card" style="border-left: 4px solid #f59e0b !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1 text-uppercase fw-semibold ls-wide">Total Tasks</p>
                            <h2 class="fw-bold mb-0 text-dark">{{ $totalTasks }}</h2>
                        </div>
                        <div class="stat-icon bg-warning-subtle text-warning rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-check2-square fs-4"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <span class="badge bg-warning-subtle text-warning">{{ $tasksByStatus['in_progress'] ?? 0 }} In Progress</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 stat-card" style="border-left: 4px solid #ef4444 !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1 text-uppercase fw-semibold ls-wide">Pending Leaves</p>
                            <h2 class="fw-bold mb-0 text-dark">{{ $pendingLeaves }}</h2>
                        </div>
                        <div class="stat-icon bg-danger-subtle text-danger rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-calendar-x-fill fs-4"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <span class="badge bg-danger-subtle text-danger">Needs Review</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Row: Charts & Overview -->
    <div class="row g-3 mb-4">
        <!-- Task Distribution Chart -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0"><i class="bi bi-bar-chart-fill me-2 text-primary"></i>Task Overview</h6>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @php
                            $statuses = [
                                'backlog' => ['label' => 'Backlog', 'color' => '#6c757d', 'icon' => 'inbox'],
                                'todo' => ['label' => 'To Do', 'color' => '#0ea5e9', 'icon' => 'list-task'],
                                'in_progress' => ['label' => 'In Progress', 'color' => '#f59e0b', 'icon' => 'play-circle'],
                                'review' => ['label' => 'Review', 'color' => '#8b5cf6', 'icon' => 'eye'],
                                'qa' => ['label' => 'QA', 'color' => '#ec4899', 'icon' => 'bug'],
                                'done' => ['label' => 'Done', 'color' => '#10b981', 'icon' => 'check-circle'],
                            ];
                        @endphp
                        @foreach($statuses as $key => $status)
                        <div class="col-6 col-md-4 col-lg-2">
                            <div class="text-center p-3 rounded-3" style="background: {{ $status['color'] }}15;">
                                <i class="bi bi-{{ $status['icon'] }} fs-3" style="color: {{ $status['color'] }}"></i>
                                <h3 class="fw-bold mt-2 mb-0" style="color: {{ $status['color'] }}">{{ $tasksByStatus[$key] ?? 0 }}</h3>
                                <small class="text-muted">{{ $status['label'] }}</small>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Task Progress Bar -->
                    <div class="mt-4">
                        <div class="d-flex justify-content-between mb-2">
                            <small class="text-muted">Overall Completion</small>
                            @php
                                $done = $tasksByStatus['done'] ?? 0;
                                $total = $totalTasks ?: 1;
                                $percentage = round(($done / $total) * 100);
                            @endphp
                            <small class="fw-semibold">{{ $percentage }}%</small>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success rounded-pill" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Attendance -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="fw-bold mb-0"><i class="bi bi-lightning-fill me-2 text-warning"></i>Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('employees.create') }}" class="btn btn-outline-primary btn-sm text-start">
                            <i class="bi bi-person-plus me-2"></i>Add Employee
                        </a>
                        <a href="{{ route('projects.create') }}" class="btn btn-outline-success btn-sm text-start">
                            <i class="bi bi-folder-plus me-2"></i>New Project
                        </a>
                        <a href="{{ route('leaves.create') }}" class="btn btn-outline-warning btn-sm text-start">
                            <i class="bi bi-calendar-plus me-2"></i>Apply Leave
                        </a>
                        <a href="{{ route('departments.index') }}" class="btn btn-outline-info btn-sm text-start">
                            <i class="bi bi-diagram-3 me-2"></i>Departments
                        </a>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="fw-bold mb-0 small">Today's Attendance</h6>
                        <span class="badge bg-success">{{ $presentToday }} Present</span>
                    </div>

                    @if($todayAttendance->count() > 0)
                        @foreach($todayAttendance->take(4) as $att)
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($att->user->name) }}&size=28&background=random" class="rounded-circle" width="28" height="28">
                            <div class="flex-grow-1">
                                <small class="fw-semibold d-block lh-1">{{ $att->user->name }}</small>
                                <small class="text-muted">{{ $att->clock_in }}</small>
                            </div>
                            <span class="badge bg-{{ $att->status == 'present' ? 'success' : ($att->status == 'late' ? 'warning' : 'danger') }}-subtle text-{{ $att->status == 'present' ? 'success' : ($att->status == 'late' ? 'warning' : 'danger') }}" style="font-size: 0.65rem;">{{ ucfirst($att->status) }}</span>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center text-muted py-3">
                            <i class="bi bi-clock fs-3 d-block mb-1"></i>
                            <small>No attendance recorded today</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Third Row: Recent Projects & My Tasks -->
    <div class="row g-3 mb-4">
        <!-- Recent Projects -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0"><i class="bi bi-folder-fill me-2 text-success"></i>Recent Projects</h6>
                    <a href="{{ route('projects.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    @if($recentProjects->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 ps-3">Project</th>
                                    <th class="border-0">Status</th>
                                    <th class="border-0">Priority</th>
                                    <th class="border-0 pe-3">Progress</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentProjects as $project)
                                <tr>
                                    <td class="ps-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="bg-primary-subtle text-primary rounded p-1 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                <i class="bi bi-kanban"></i>
                                            </div>
                                            <div>
                                                <a href="{{ route('projects.show', $project) }}" class="text-dark fw-semibold text-decoration-none small">{{ $project->name }}</a>
                                                <br><small class="text-muted">{{ $project->project_code }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = ['planning' => 'secondary', 'in_progress' => 'primary', 'on_hold' => 'warning', 'completed' => 'success', 'cancelled' => 'danger'];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$project->status] ?? 'secondary' }}-subtle text-{{ $statusColors[$project->status] ?? 'secondary' }}">{{ ucfirst(str_replace('_', ' ', $project->status)) }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $priorityColors = ['low' => 'info', 'medium' => 'primary', 'high' => 'warning', 'critical' => 'danger'];
                                        @endphp
                                        <span class="badge bg-{{ $priorityColors[$project->priority] ?? 'secondary' }}-subtle text-{{ $priorityColors[$project->priority] ?? 'secondary' }}">{{ ucfirst($project->priority) }}</span>
                                    </td>
                                    <td class="pe-3">
                                        <div class="progress" style="height: 6px; width: 80px;">
                                            <div class="progress-bar bg-primary rounded-pill" style="width: {{ $project->progress ?? 0 }}%"></div>
                                        </div>
                                        <small class="text-muted">{{ $project->progress ?? 0 }}%</small>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-folder fs-1 d-block mb-2"></i>
                        <p>No projects yet</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- My Tasks -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0"><i class="bi bi-check2-square me-2 text-warning"></i>My Tasks</h6>
                    <a href="{{ route('tasks.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    @if($myTasks->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($myTasks as $task)
                        <div class="list-group-item border-0 px-3 py-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="d-flex align-items-start gap-2">
                                    @php
                                        $taskColors = ['backlog' => 'secondary', 'todo' => 'info', 'in_progress' => 'warning', 'review' => 'purple', 'qa' => 'pink', 'done' => 'success'];
                                        $color = $taskColors[$task->status] ?? 'secondary';
                                    @endphp
                                    <div class="rounded-circle mt-1" style="width: 10px; height: 10px; min-width: 10px; background: {{ $statuses[$task->status]['color'] ?? '#6c757d' }}"></div>
                                    <div>
                                        <p class="mb-0 fw-semibold small">{{ $task->title }}</p>
                                        <small class="text-muted">{{ $task->project->name ?? 'No Project' }} · {{ ucfirst(str_replace('_', ' ', $task->status)) }}</small>
                                    </div>
                                </div>
                                <span class="badge bg-{{ $priorityColors[$task->priority] ?? 'secondary' }}-subtle text-{{ $priorityColors[$task->priority] ?? 'secondary' }}" style="font-size: 0.65rem;">{{ ucfirst($task->priority) }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-check-circle fs-1 d-block mb-2"></i>
                        <p>No tasks assigned to you</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Fourth Row: Leave Applications -->
    <div class="row g-3">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0"><i class="bi bi-calendar-event me-2 text-info"></i>Recent Leave Applications</h6>
                    <a href="{{ route('leaves.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    @if($recentLeaves->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 ps-3">Employee</th>
                                    <th class="border-0">Type</th>
                                    <th class="border-0">Duration</th>
                                    <th class="border-0">Days</th>
                                    <th class="border-0 pe-3">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentLeaves as $leave)
                                <tr>
                                    <td class="ps-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($leave->user->name ?? 'U') }}&size=32&background=random" class="rounded-circle" width="32" height="32">
                                            <span class="fw-semibold small">{{ $leave->user->name ?? 'Unknown' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill" style="background: {{ $leave->leaveType->color ?? '#4f46e5' }}20; color: {{ $leave->leaveType->color ?? '#4f46e5' }}">{{ $leave->leaveType->name ?? 'N/A' }}</span>
                                    </td>
                                    <td class="small text-muted">{{ $leave->start_date->format('M d') }} - {{ $leave->end_date->format('M d, Y') }}</td>
                                    <td><span class="fw-semibold">{{ $leave->total_days }}</span></td>
                                    <td class="pe-3">
                                        @php
                                            $leaveColors = ['pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger', 'cancelled' => 'secondary'];
                                        @endphp
                                        <span class="badge bg-{{ $leaveColors[$leave->status] ?? 'secondary' }}-subtle text-{{ $leaveColors[$leave->status] ?? 'secondary' }}">{{ ucfirst($leave->status) }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-calendar-check fs-1 d-block mb-2"></i>
                        <p>No leave applications yet</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .stat-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
    }
    .ls-wide { letter-spacing: 0.05em; }
</style>
@endpush
