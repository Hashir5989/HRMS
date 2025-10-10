@extends('layouts.admin')

@section('title', 'Leave Management - HRMS Pro')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Leave Management</h3>
            <p class="text-muted mb-0">Manage and track leave applications</p>
        </div>
        <a href="{{ route('leaves.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Apply Leave
        </a>
    </div>

    <!-- Stats -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="bg-warning-subtle text-warning rounded-3 p-3 d-flex align-items-center justify-content-center">
                        <i class="bi bi-hourglass-split fs-3"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0">{{ $stats['pending'] }}</h4>
                        <small class="text-muted">Pending</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="bg-success-subtle text-success rounded-3 p-3 d-flex align-items-center justify-content-center">
                        <i class="bi bi-check-circle fs-3"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0">{{ $stats['approved'] }}</h4>
                        <small class="text-muted">Approved</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="bg-danger-subtle text-danger rounded-3 p-3 d-flex align-items-center justify-content-center">
                        <i class="bi bi-x-circle fs-3"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0">{{ $stats['rejected'] }}</h4>
                        <small class="text-muted">Rejected</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Employee</th>
                            <th>Leave Type</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Days</th>
                            <th>Status</th>
                            <th class="pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaves as $leave)
                        <tr>
                            <td class="ps-3">
                                <div class="d-flex align-items-center gap-2">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($leave->user->name ?? 'U') }}&size=32&background=random" class="rounded-circle" width="32" height="32">
                                    <span class="fw-semibold small">{{ $leave->user->name ?? 'You' }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge rounded-pill" style="background: {{ $leave->leaveType->color ?? '#4f46e5' }}20; color: {{ $leave->leaveType->color ?? '#4f46e5' }}">{{ $leave->leaveType->name ?? 'N/A' }}</span>
                            </td>
                            <td class="small">{{ $leave->start_date->format('M d, Y') }}</td>
                            <td class="small">{{ $leave->end_date->format('M d, Y') }}</td>
                            <td><span class="fw-bold">{{ $leave->total_days }}</span></td>
                            <td>
                                @php
                                    $colors = ['pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger', 'cancelled' => 'secondary'];
                                @endphp
                                <span class="badge bg-{{ $colors[$leave->status] ?? 'secondary' }}-subtle text-{{ $colors[$leave->status] ?? 'secondary' }}">{{ ucfirst($leave->status) }}</span>
                            </td>
                            <td class="pe-3">
                                <div class="d-flex gap-1">
                                    <a href="{{ route('leaves.show', $leave) }}" class="btn btn-sm btn-outline-primary" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if(auth()->user()->hasAnyRole(['Super Admin', 'Admin', 'HR']) && $leave->status === 'pending')
                                    <form action="{{ route('leaves.update', $leave) }}" method="POST" class="d-inline">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="btn btn-sm btn-outline-success" title="Approve"><i class="bi bi-check-lg"></i></button>
                                    </form>
                                    <form action="{{ route('leaves.update', $leave) }}" method="POST" class="d-inline">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Reject"><i class="bi bi-x-lg"></i></button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="bi bi-calendar-x fs-1 d-block mb-2"></i>
                                No leave applications found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($leaves->hasPages())
        <div class="card-footer bg-white border-0">
            {{ $leaves->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
