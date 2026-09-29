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
        <!-- Analytics Charts -->
        <div class="col-lg-8">
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-0 py-3">
                            <h6 class="fw-bold mb-0"><i class="bi bi-pie-chart-fill me-2 text-primary"></i>Tasks Overview</h6>
                        </div>
                        <div class="card-body d-flex justify-content-center align-items-center" style="position: relative; height:250px;">
                            <canvas id="tasksChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-0 py-3">
                            <h6 class="fw-bold mb-0"><i class="bi bi-graph-up-arrow me-2 text-success"></i>Attendance Trends (Last 7 Days)</h6>
                        </div>
                        <div class="card-body d-flex justify-content-center align-items-center" style="position: relative; height:250px;">
                            <canvas id="attendanceChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-0 py-3">
                            <h6 class="fw-bold mb-0"><i class="bi bi-diagram-2-fill me-2 text-warning"></i>Project Status</h6>
                        </div>
                        <div class="card-body d-flex justify-content-center align-items-center" style="position: relative; height:250px;">
                            <canvas id="projectsChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-0 py-3">
                            <h6 class="fw-bold mb-0"><i class="bi bi-calendar2-x-fill me-2 text-danger"></i>Leave Distribution</h6>
                        </div>
                        <div class="card-body d-flex justify-content-center align-items-center" style="position: relative; height:250px;">
                            <canvas id="leavesChart"></canvas>
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const commonOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12, usePointStyle: true } }
            }
        };

        // 1. Tasks Chart (Doughnut)
        new Chart(document.getElementById('tasksChart'), {
            type: 'doughnut',
            data: {
                labels: ['Backlog', 'To Do', 'In Progress', 'Review', 'QA', 'Done'],
                datasets: [{
                    data: [
                        {{ $tasksByStatus['backlog'] ?? 0 }},
                        {{ $tasksByStatus['todo'] ?? 0 }},
                        {{ $tasksByStatus['in_progress'] ?? 0 }},
                        {{ $tasksByStatus['review'] ?? 0 }},
                        {{ $tasksByStatus['qa'] ?? 0 }},
                        {{ $tasksByStatus['done'] ?? 0 }}
                    ],
                    backgroundColor: ['#6c757d', '#0ea5e9', '#f59e0b', '#8b5cf6', '#ec4899', '#10b981'],
                    borderWidth: 0
                }]
            },
            options: { ...commonOptions, cutout: '70%' }
        });

        // 2. Attendance Chart (Bar)
        new Chart(document.getElementById('attendanceChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($last7Days) !!},
                datasets: [{
                    label: 'Present Employees',
                    data: {!! json_encode($attendanceData) !!},
                    backgroundColor: '#10b981',
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [4, 4] } },
                    x: { grid: { display: false } }
                }
            }
        });

        // 3. Projects Chart (Pie)
        new Chart(document.getElementById('projectsChart'), {
            type: 'pie',
            data: {
                labels: ['Planning', 'In Progress', 'On Hold', 'Completed', 'Cancelled'],
                datasets: [{
                    data: [
                        {{ $projectsByStatus['planning'] ?? 0 }},
                        {{ $projectsByStatus['in_progress'] ?? 0 }},
                        {{ $projectsByStatus['on_hold'] ?? 0 }},
                        {{ $projectsByStatus['completed'] ?? 0 }},
                        {{ $projectsByStatus['cancelled'] ?? 0 }}
                    ],
                    backgroundColor: ['#6c757d', '#0ea5e9', '#f59e0b', '#10b981', '#ef4444'],
                    borderWidth: 0
                }]
            },
            options: commonOptions
        });

        // 4. Leaves Chart (Doughnut)
        new Chart(document.getElementById('leavesChart'), {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Approved', 'Rejected', 'Cancelled'],
                datasets: [{
                    data: [
                        {{ $leavesByStatus['pending'] ?? 0 }},
                        {{ $leavesByStatus['approved'] ?? 0 }},
                        {{ $leavesByStatus['rejected'] ?? 0 }},
                        {{ $leavesByStatus['cancelled'] ?? 0 }}
                    ],
                    backgroundColor: ['#f59e0b', '#10b981', '#ef4444', '#6c757d'],
                    borderWidth: 0
                }]
            },
            options: { ...commonOptions, cutout: '70%' }
        });
    });
</script>
@endpush
