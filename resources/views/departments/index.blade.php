@extends('layouts.admin')

@section('title', 'Departments - HRMS Pro')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Departments</h3>
            <p class="text-muted mb-0">Manage organizational departments</p>
        </div>
        @can('department.manage')
        <a href="{{ route('departments.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Add Department
        </a>
        @endcan
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row g-3">
        @forelse($departments as $department)
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100 department-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="bg-primary-subtle text-primary rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-diagram-3-fill fs-4"></i>
                        </div>
                        @can('department.manage')
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('departments.edit', $department) }}"><i class="bi bi-pencil me-2"></i>Edit</a></li>
                                <li><a class="dropdown-item" href="{{ route('departments.show', $department) }}"><i class="bi bi-eye me-2"></i>View</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('departments.destroy', $department) }}" method="POST" onsubmit="return confirm('Delete this department?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger"><i class="bi bi-trash me-2"></i>Delete</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                        @endcan
                    </div>
                    <h5 class="fw-bold mb-1">{{ $department->name }}</h5>
                    <p class="text-muted small mb-3">{{ Str::limit($department->description, 80) ?? 'No description' }}</p>
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center gap-1">
                            <i class="bi bi-people text-muted"></i>
                            <small class="fw-semibold">{{ $department->employees_count }} Employees</small>
                        </div>
                        @if($department->manager)
                        <div class="d-flex align-items-center gap-1">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($department->manager->name) }}&size=20&background=random" class="rounded-circle" width="20" height="20">
                            <small class="text-muted">{{ $department->manager->name }}</small>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="bi bi-diagram-3 fs-1 text-muted d-block mb-2"></i>
                    <p class="text-muted mb-3">No departments created yet</p>
                    @can('department.manage')
                    <a href="{{ route('departments.create') }}" class="btn btn-primary">Create First Department</a>
                    @endcan
                </div>
            </div>
        </div>
        @endforelse
    </div>

    @if($departments->hasPages())
    <div class="mt-4">{{ $departments->links() }}</div>
    @endif
</div>

@push('styles')
<style>
    .department-card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .department-card:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important; }
</style>
@endpush
@endsection
