@extends('layouts.admin')

@section('title', 'Designations - HRMS Pro')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Designations</h3>
            <p class="text-muted mb-0">Manage job titles and departmental roles</p>
        </div>
        @can('department.manage')
        <a href="{{ route('designations.create') }}" class="btn btn-primary premium-btn rounded-pill px-4 shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Add Designation
        </a>
        @endcan
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 px-4 py-3 text-muted fw-semibold">Designation Title</th>
                            <th class="border-0 px-4 py-3 text-muted fw-semibold">Department</th>
                            <th class="border-0 px-4 py-3 text-muted fw-semibold">Description</th>
                            <th class="border-0 px-4 py-3 text-muted fw-semibold text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($designations as $designation)
                        <tr>
                            <td class="px-4 py-3 fw-bold text-dark">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="p-2 rounded bg-primary bg-opacity-10 text-primary">
                                        <i class="bi bi-award fs-5"></i>
                                    </div>
                                    <span>{{ $designation->name }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3">
                                    {{ $designation->department->name ?? 'Unassigned' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-muted small">
                                {{ Str::limit($designation->description ?? 'No description provided.', 60) }}
                            </td>
                            <td class="px-4 py-3 text-end">
                                @can('department.manage')
                                <div class="btn-group">
                                    <a href="{{ route('designations.edit', $designation) }}" class="btn btn-sm btn-light text-secondary border px-3" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('designations.destroy', $designation) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this designation?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light text-danger border rounded-end-pill px-3" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                                @endcan
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <div class="text-muted mb-3"><i class="bi bi-award fs-1"></i></div>
                                <h5>No Designations Found</h5>
                                <p class="text-muted">Start by adding your first designation.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($designations->hasPages())
            <div class="px-4 py-3 border-top">
                {{ $designations->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
