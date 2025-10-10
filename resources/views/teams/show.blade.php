@extends('layouts.admin')

@section('title', $team->name . ' - HRMS Pro')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('teams.index') }}" class="text-decoration-none text-muted">
            <i class="bi bi-arrow-left me-1"></i>Back to Teams
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-success-subtle text-success rounded-3 p-3">
                        <i class="bi bi-people-fill fs-2"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-1">{{ $team->name }}</h3>
                        <p class="text-muted mb-0">{{ $team->department->name ?? 'N/A' }} · {{ $team->description ?? 'No description' }}</p>
                    </div>
                </div>
                @if($team->leader)
                <div class="text-end">
                    <small class="text-muted d-block">Team Lead</small>
                    <div class="d-flex align-items-center gap-2 mt-1">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($team->leader->name) }}&size=28&background=random" class="rounded-circle" width="28" height="28">
                        <span class="fw-semibold">{{ $team->leader->name }}</span>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <h6 class="fw-bold mb-0"><i class="bi bi-people me-2"></i>Members ({{ $team->members->count() }})</h6>
        </div>
        <div class="card-body p-0">
            @if($team->members->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Name</th>
                            <th>Position</th>
                            <th class="pe-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($team->members as $member)
                        <tr>
                            <td class="ps-3">
                                <div class="d-flex align-items-center gap-2">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($member->full_name) }}&size=32&background=random" class="rounded-circle" width="32" height="32">
                                    <span class="fw-semibold small">{{ $member->full_name }}</span>
                                </div>
                            </td>
                            <td class="small">{{ $member->designation->title ?? 'N/A' }}</td>
                            <td class="pe-3">
                                <span class="badge bg-{{ $member->status == 'active' ? 'success' : 'secondary' }}-subtle text-{{ $member->status == 'active' ? 'success' : 'secondary' }}">{{ ucfirst($member->status) }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-4 text-muted">
                <i class="bi bi-people fs-1 d-block mb-2"></i>
                <p>No members in this team</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
