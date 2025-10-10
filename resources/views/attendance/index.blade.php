@extends('layouts.admin')

@section('title', 'Attendance - HRMS Pro')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Attendance</h3>
            <p class="text-muted mb-0">Track daily attendance and working hours</p>
        </div>
        <div class="d-flex gap-2">
            @if(!$myAttendance)
            <form action="{{ route('attendance.clockIn') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success"><i class="bi bi-box-arrow-in-right me-1"></i>Clock In</button>
            </form>
            @elseif(!$myAttendance->clock_out)
            <form action="{{ route('attendance.clockOut') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger"><i class="bi bi-box-arrow-right me-1"></i>Clock Out</button>
            </form>
            @else
            <span class="btn btn-outline-success disabled"><i class="bi bi-check-circle me-1"></i>Done for today</span>
            @endif
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- My Status Card -->
    @if($myAttendance)
    <div class="card border-0 shadow-sm mb-4" style="border-left: 4px solid #10b981 !important;">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fw-bold mb-1">Your Attendance Today</h6>
                    <div class="d-flex gap-4">
                        <div>
                            <small class="text-muted">Clock In</small>
                            <p class="fw-bold mb-0 text-success">{{ $myAttendance->clock_in ?? '--:--' }}</p>
                        </div>
                        <div>
                            <small class="text-muted">Clock Out</small>
                            <p class="fw-bold mb-0 text-danger">{{ $myAttendance->clock_out ?? '--:--' }}</p>
                        </div>
                        <div>
                            <small class="text-muted">Hours</small>
                            <p class="fw-bold mb-0 text-primary">{{ $myAttendance->total_hours ?? '0.00' }}h</p>
                        </div>
                    </div>
                </div>
                <span class="badge bg-{{ $myAttendance->status == 'present' ? 'success' : ($myAttendance->status == 'late' ? 'warning' : 'danger') }} fs-6">{{ ucfirst($myAttendance->status) }}</span>
            </div>
        </div>
    </div>
    @endif

    <!-- Stats -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="card-body py-2">
                    <h3 class="fw-bold text-success mb-0">{{ $stats['present'] }}</h3>
                    <small class="text-muted">Present</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="card-body py-2">
                    <h3 class="fw-bold text-danger mb-0">{{ $stats['absent'] }}</h3>
                    <small class="text-muted">Absent</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="card-body py-2">
                    <h3 class="fw-bold text-warning mb-0">{{ $stats['late'] }}</h3>
                    <small class="text-muted">Late</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center py-3">
                <div class="card-body py-2">
                    <h3 class="fw-bold text-info mb-0">{{ $stats['on_leave'] }}</h3>
                    <small class="text-muted">On Leave</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Date Filter -->
    @if(auth()->user()->hasAnyRole(['Super Admin', 'Admin', 'HR']))
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-2">
            <form action="{{ route('attendance.index') }}" method="GET" class="d-flex align-items-center gap-3">
                <label class="fw-semibold small text-nowrap">Filter Date:</label>
                <input type="date" class="form-control form-control-sm" name="date" value="{{ $date }}" style="max-width: 200px;">
                <button type="submit" class="btn btn-sm btn-primary">Filter</button>
            </form>
        </div>
    </div>
    @endif

    <!-- Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Employee</th>
                            <th>Date</th>
                            <th>Clock In</th>
                            <th>Clock Out</th>
                            <th>Hours</th>
                            <th class="pe-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $att)
                        <tr>
                            <td class="ps-3">
                                <div class="d-flex align-items-center gap-2">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($att->user->name) }}&size=32&background=random" class="rounded-circle" width="32" height="32">
                                    <span class="fw-semibold small">{{ $att->user->name }}</span>
                                </div>
                            </td>
                            <td class="small">{{ $att->date->format('M d, Y') }}</td>
                            <td class="small fw-semibold text-success">{{ $att->clock_in ?? '--:--' }}</td>
                            <td class="small fw-semibold text-danger">{{ $att->clock_out ?? '--:--' }}</td>
                            <td class="small fw-bold">{{ $att->total_hours }}h</td>
                            <td class="pe-3">
                                <span class="badge bg-{{ $att->status == 'present' ? 'success' : ($att->status == 'late' ? 'warning' : 'danger') }}-subtle text-{{ $att->status == 'present' ? 'success' : ($att->status == 'late' ? 'warning' : 'danger') }}">{{ ucfirst(str_replace('_', ' ', $att->status)) }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="bi bi-clock-history fs-1 d-block mb-2"></i>
                                No attendance records found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($attendances->hasPages())
        <div class="card-footer bg-white border-0">{{ $attendances->links() }}</div>
        @endif
    </div>
</div>
@endsection
