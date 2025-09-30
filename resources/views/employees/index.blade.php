@extends('layouts.admin')

@section('title', 'Employees - HRMS Pro')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Employees</h3>
            <p class="text-muted mb-0">Manage your workforce and their profiles</p>
        </div>
        @can('employee.create')
        <a href="{{ route('employees.create') }}" class="btn btn-primary premium-btn rounded-pill px-4 shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Add Employee
        </a>
        @endcan
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 px-4 py-3 text-muted fw-semibold">Employee</th>
                            <th class="border-0 px-4 py-3 text-muted fw-semibold">ID</th>
                            <th class="border-0 px-4 py-3 text-muted fw-semibold">Department</th>
                            <th class="border-0 px-4 py-3 text-muted fw-semibold">Designation</th>
                            <th class="border-0 px-4 py-3 text-muted fw-semibold">Status</th>
                            <th class="border-0 px-4 py-3 text-muted fw-semibold text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $employee)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $employee->profile_photo ?? 'https://ui-avatars.com/api/?name='.urlencode($employee->full_name).'&background=random' }}" class="rounded-circle" width="40" height="40">
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $employee->full_name }}</h6>
                                        <small class="text-muted">{{ $employee->user->email ?? 'N/A' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 fw-medium text-dark">{{ $employee->employee_id }}</td>
                            <td class="px-4 py-3">{{ $employee->department->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-muted">{{ $employee->designation->name ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <span class="badge {{ $employee->status === 'active' ? 'bg-success' : 'bg-secondary' }} bg-opacity-10 text-{{ $employee->status === 'active' ? 'success' : 'secondary' }} rounded-pill px-3">
                                    {{ ucfirst($employee->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-end">
                                <div class="btn-group">
                                    <a href="{{ route('employees.show', $employee) }}" class="btn btn-sm btn-light text-primary border rounded-start-pill px-3" title="View Profile">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @can('employee.edit')
                                    <a href="{{ route('employees.edit', $employee) }}" class="btn btn-sm btn-light text-secondary border px-3" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @endcan
                                    @can('employee.delete')
                                    <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light text-danger border rounded-end-pill px-3" onclick="return confirm('Are you sure you want to delete this employee?')" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted mb-3"><i class="bi bi-people fs-1"></i></div>
                                <h5>No Employees Found</h5>
                                <p class="text-muted">Start by adding your first employee to the system.</p>
                                @can('employee.create')
                                <a href="{{ route('employees.create') }}" class="btn btn-outline-primary rounded-pill px-4 mt-2">Add Employee</a>
                                @endcan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($employees->hasPages())
            <div class="px-4 py-3 border-top">
                {{ $employees->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
