@extends('layouts.admin')

@section('title', 'Attendance - HRMS Pro')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h3 class="fw-bold text-dark mb-1">Attendance</h3>
            <p class="text-muted mb-0">Track daily attendance, break reasons, and working hours</p>
        </div>
        <div class="d-flex gap-2 align-items-center">
            @if(!$myAttendance)
                <form action="{{ route('attendance.clockIn') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success"><i class="bi bi-box-arrow-in-right me-1"></i>Clock In</button>
                </form>
            @elseif(!$myAttendance->clock_out)
                @if($myAttendance->on_break)
                    <form action="{{ route('attendance.endBreak') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-warning fw-semibold"><i class="bi bi-play-circle-fill me-1"></i>End Break & Resume Work</button>
                    </form>
                @else
                    @if($myAttendance->break_start)
                        <button type="button" class="btn btn-outline-secondary" disabled title="Only 1 break allowed per shift (already taken)">
                            <i class="bi bi-check-circle-fill me-1"></i>Break Completed (1/1)
                        </button>
                    @else
                        <button type="button" class="btn btn-warning fw-semibold" data-bs-toggle="modal" data-bs-target="#breakModal">
                            <i class="bi bi-cup-hot-fill me-1"></i>Take Break
                        </button>
                    @endif

                    @if($canClockOut)
                        <form action="{{ route('attendance.clockOut') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-danger"><i class="bi bi-box-arrow-right me-1"></i>Clock Out</button>
                        </form>
                    @else
                        <span class="badge bg-info-subtle text-info p-2 border border-info-subtle" title="8-hour working shift in progress">
                            <i class="bi bi-hourglass-split me-1"></i>Shift in Progress ({{ floor($elapsedMinutes / 60) }}h {{ $elapsedMinutes % 60 }}m / 8h)
                        </span>
                    @endif
                @endif
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

    <!-- Break Reason Modal -->
    <div class="modal fade" id="breakModal" tabindex="-1" aria-labelledby="breakModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-warning-subtle text-warning-emphasis border-0 py-3">
                    <h5 class="modal-title fw-bold" id="breakModalLabel"><i class="bi bi-cup-hot-fill me-2"></i>Take a Break</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('attendance.startBreak') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="alert alert-info py-2 px-3 mb-3 small d-flex align-items-center gap-2">
                            <i class="bi bi-info-circle-fill fs-5"></i>
                            <div><strong>Shift Policy:</strong> Only <strong>1 break</strong> is allowed per 8-hour working shift.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="break_reason" class="form-label fw-semibold">Reason for Break <span class="text-danger">*</span></label>
                            <textarea name="break_reason" id="break_reason" class="form-control" rows="3" placeholder="Enter reason (e.g. Lunch break, Tea break, Personal errand, etc.)..." required></textarea>
                            <div class="form-text">Please provide a brief reason for your break.</div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0 py-2">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning btn-sm fw-semibold"><i class="bi bi-check2-circle me-1"></i>Start Break</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- My Status Card -->
    @if($myAttendance)
    <div class="card border-0 shadow-sm mb-4" style="border-left: 4px solid {{ $myAttendance->on_break ? '#f59e0b' : '#10b981' }} !important;">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h6 class="fw-bold mb-1">Your Attendance Today</h6>
                    <div class="d-flex gap-4 flex-wrap align-items-center mt-2">
                        <div>
                            <small class="text-muted d-block">Clock In</small>
                            <span class="fw-bold text-success">{{ $myAttendance->clock_in ?? '--:--' }}</span>
                        </div>
                        <div>
                            <small class="text-muted d-block">Break</small>
                            @if($myAttendance->on_break)
                                <span class="badge bg-warning text-dark"><i class="bi bi-cup-hot me-1"></i>On Break (Started {{ $myAttendance->break_start }})</span>
                            @elseif($myAttendance->break_start)
                                <span class="fw-bold text-warning">{{ $myAttendance->break_minutes }}m</span>
                                <small class="text-muted">({{ $myAttendance->break_start }} - {{ $myAttendance->break_end ?? 'now' }})</small>
                            @else
                                <span class="text-muted">No break taken</span>
                            @endif
                        </div>
                        <div>
                            <small class="text-muted d-block">Clock Out</small>
                            <span class="fw-bold text-danger">{{ $myAttendance->clock_out ?? '--:--' }}</span>
                        </div>
                        <div>
                            <small class="text-muted d-block">Net Hours</small>
                            <span class="fw-bold text-primary">{{ $myAttendance->total_hours ?? '0.00' }}h</span>
                        </div>
                    </div>
                    @if($myAttendance->break_reason)
                        <div class="mt-2 text-muted small bg-light p-2 rounded">
                            <i class="bi bi-chat-left-text me-1 text-warning"></i><strong>Break Reason:</strong> "{{ $myAttendance->break_reason }}"
                        </div>
                    @endif
                </div>
                <div>
                    @if($myAttendance->on_break)
                        <span class="badge bg-warning text-dark fs-6"><i class="bi bi-cup-hot me-1"></i>On Break</span>
                    @else
                        <span class="badge bg-{{ $myAttendance->status == 'present' ? 'success' : ($myAttendance->status == 'late' ? 'warning' : 'danger') }} fs-6">{{ ucfirst($myAttendance->status) }}</span>
                    @endif
                </div>
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
                            <th>Break Details</th>
                            <th>Clock Out</th>
                            <th>Net Hours</th>
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
                            <td class="small">
                                @if($att->on_break)
                                    <span class="badge bg-warning text-dark"><i class="bi bi-cup-hot me-1"></i>On Break</span>
                                    @if($att->break_reason)
                                        <br><small class="text-muted text-truncate d-inline-block" style="max-width: 150px;">"{{ $att->break_reason }}"</small>
                                    @endif
                                @elseif($att->break_start)
                                    <span class="badge bg-warning-subtle text-warning-emphasis" title="Start: {{ $att->break_start }} - End: {{ $att->break_end }}">
                                        <i class="bi bi-cup-hot me-1"></i>{{ $att->break_minutes }} mins
                                    </span>
                                    @if($att->break_reason)
                                        <br><small class="text-muted text-truncate d-inline-block" style="max-width: 160px;" title="{{ $att->break_reason }}">"{{ $att->break_reason }}"</small>
                                    @endif
                                @else
                                    <span class="text-muted">--</span>
                                @endif
                            </td>
                            <td class="small fw-semibold text-danger">{{ $att->clock_out ?? '--:--' }}</td>
                            <td class="small fw-bold">{{ $att->total_hours }}h</td>
                            <td class="pe-3">
                                @if($att->on_break)
                                    <span class="badge bg-warning-subtle text-warning">On Break</span>
                                @else
                                    <span class="badge bg-{{ $att->status == 'present' ? 'success' : ($att->status == 'late' ? 'warning' : 'danger') }}-subtle text-{{ $att->status == 'present' ? 'success' : ($att->status == 'late' ? 'warning' : 'danger') }}">{{ ucfirst(str_replace('_', ' ', $att->status)) }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
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
