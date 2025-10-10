@extends('layouts.admin')

@section('title', $department->name . ' - HRMS Pro')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('departments.index') }}" class="text-decoration-none text-muted">
            <i class="bi bi-arrow-left me-1"></i>Back to Departments
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary-subtle text-primary rounded-3 p-3">
                        <i class="bi bi-diagram-3-fill fs-2"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-1">{{ $department->name }}</h3>
                        <p class="text-muted mb-0">{{ $department->description ?? 'No description' }}</p>
                    </div>
                </div>
                @if($department->manager)
                <div class="text-end">
                    <small class="text-muted d-block">Manager</small>
                    <div class="d-flex align-items-center gap-2 mt-1">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($department->manager->name) }}&size=28&background=random" class="rounded-circle" width="28" height="28">
                        <span class="fw-semibold">{{ $department->manager->name }}</span>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <h6 class="fw-bold mb-0"><i class="bi bi-people me-2"></i>Employees ({{ $department->employees->count() }})</h6>
        </div>
        <div class="card-body p-0">
            @if($department->employees->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Name</th>
                            <th>Email</th>
                            <th>Position</th>
                            <th class="pe-3">Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($department->employees as $emp)
                        <tr>
                            <td class="ps-3">
                                <div class="d-flex align-items-center gap-2">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($emp->user->name ?? $emp->first_name) }}&size=32&background=random" class="rounded-circle" width="32" height="32">
                                    <span class="fw-semibold small">{{ $emp->first_name }} {{ $emp->last_name }}</span>
                                </div>
                            </td>
                            <td class="small text-muted">{{ $emp->user->email ?? 'N/A' }}</td>
                            <td class="small">{{ $emp->position ?? 'N/A' }}</td>
                            <td class="pe-3 small text-muted">{{ $emp->joining_date?->format('M d, Y') ?? 'N/A' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-4 text-muted">
                <i class="bi bi-people fs-1 d-block mb-2"></i>
                <p>No employees in this department</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
