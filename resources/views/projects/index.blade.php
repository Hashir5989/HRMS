@extends('layouts.admin')

@section('title', 'Projects - HRMS Pro')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Projects</h3>
            <p class="text-muted mb-0">Manage and track your company projects</p>
        </div>
        @can('project.create')
        <a href="{{ route('projects.create') }}" class="btn btn-primary premium-btn rounded-pill px-4 shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> New Project
        </a>
        @endcan
    </div>

    <div class="row g-4">
        @forelse($projects as $project)
        <div class="col-md-6 col-lg-4">
            <div class="card glass-card border-0 shadow-sm rounded-4 h-100 hover-elevate">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 fw-semibold">
                            {{ $project->project_code }}
                        </span>
                        
                        @php
                            $statusColors = [
                                'planning' => 'info',
                                'active' => 'primary',
                                'on_hold' => 'warning',
                                'completed' => 'success',
                                'cancelled' => 'danger'
                            ];
                            $color = $statusColors[$project->status] ?? 'secondary';
                        @endphp
                        <span class="badge bg-{{ $color }} rounded-pill">{{ ucfirst(str_replace('_', ' ', $project->status)) }}</span>
                    </div>
                    
                    <h5 class="fw-bold text-dark mb-2">
                        <a href="{{ route('projects.show', $project) }}" class="text-decoration-none text-dark">{{ $project->name }}</a>
                    </h5>
                    
                    <p class="text-muted small mb-4 line-clamp-2">
                        {{ $project->description ?? 'No description provided.' }}
                    </p>
                    
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted small fw-medium">Progress</span>
                            <span class="text-dark small fw-bold">{{ $project->progress }}%</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-primary rounded-pill" role="progressbar" style="width: {{ $project->progress }}%"></div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <div class="d-flex align-items-center gap-2">
                            @if($project->projectManager)
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($project->projectManager->name) }}&background=random" class="rounded-circle shadow-sm" width="32" height="32" title="Manager: {{ $project->projectManager->name }}">
                            @endif
                        </div>
                        <a href="{{ route('projects.show', $project) }}" class="btn btn-sm btn-light text-primary rounded-pill px-3 fw-semibold">
                            View Board <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <div class="text-muted mb-3"><i class="bi bi-folder2-open fs-1"></i></div>
            <h5>No Projects Found</h5>
            <p class="text-muted">Start by creating your first project.</p>
        </div>
        @endforelse
    </div>
    
    @if($projects->hasPages())
    <div class="mt-4">
        {{ $projects->links() }}
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .hover-elevate {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hover-elevate:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1) !important;
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush
