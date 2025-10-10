@extends('layouts.admin')

@section('title', 'Leave Details - HRMS Pro')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="mb-4">
                <a href="{{ route('leaves.index') }}" class="text-decoration-none text-muted">
                    <i class="bi bi-arrow-left me-1"></i>Back to Leave Management
                </a>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0"><i class="bi bi-calendar-event me-2 text-primary"></i>Leave Application Details</h5>
                    @php
                        $colors = ['pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger', 'cancelled' => 'secondary'];
                    @endphp
                    <span class="badge bg-{{ $colors[$leave->status] ?? 'secondary' }} fs-6">{{ ucfirst($leave->status) }}</span>
                </div>
                <div class="card-body p-4">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="text-muted small">Employee</label>
                            <div class="d-flex align-items-center gap-2 mt-1">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($leave->user->name ?? 'U') }}&size=36&background=random" class="rounded-circle" width="36" height="36">
                                <div>
                                    <p class="fw-semibold mb-0">{{ $leave->user->name ?? 'Unknown' }}</p>
                                    <small class="text-muted">{{ $leave->user->email ?? '' }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Leave Type</label>
                            <p class="fw-semibold mt-1">
                                <span class="badge rounded-pill fs-6" style="background: {{ $leave->leaveType->color ?? '#4f46e5' }}20; color: {{ $leave->leaveType->color ?? '#4f46e5' }}">{{ $leave->leaveType->name ?? 'N/A' }}</span>
                            </p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label class="text-muted small">Start Date</label>
                            <p class="fw-semibold">{{ $leave->start_date->format('M d, Y') }}</p>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small">End Date</label>
                            <p class="fw-semibold">{{ $leave->end_date->format('M d, Y') }}</p>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small">Total Days</label>
                            <p class="fw-bold fs-5 text-primary">{{ $leave->total_days }}</p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="text-muted small">Reason</label>
                        <div class="bg-light rounded-3 p-3 mt-1">{{ $leave->reason }}</div>
                    </div>

                    @if($leave->admin_remarks)
                    <div class="mb-4">
                        <label class="text-muted small">Admin Remarks</label>
                        <div class="bg-light rounded-3 p-3 mt-1">{{ $leave->admin_remarks }}</div>
                    </div>
                    @endif

                    @if($leave->approver)
                    <div class="mb-4">
                        <label class="text-muted small">Responded By</label>
                        <p class="fw-semibold">{{ $leave->approver->name }} <small class="text-muted">on {{ $leave->responded_at?->format('M d, Y h:i A') }}</small></p>
                    </div>
                    @endif

                    @if(auth()->user()->hasAnyRole(['Super Admin', 'Admin', 'HR']) && $leave->status === 'pending')
                    <hr>
                    <form action="{{ route('leaves.update', $leave) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="mb-3">
                            <label for="admin_remarks" class="form-label fw-semibold">Admin Remarks</label>
                            <textarea class="form-control" id="admin_remarks" name="admin_remarks" rows="2" placeholder="Optional remarks..."></textarea>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" name="status" value="approved" class="btn btn-success"><i class="bi bi-check-circle me-1"></i>Approve</button>
                            <button type="submit" name="status" value="rejected" class="btn btn-danger"><i class="bi bi-x-circle me-1"></i>Reject</button>
                        </div>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
