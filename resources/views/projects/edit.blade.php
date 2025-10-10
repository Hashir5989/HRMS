@extends('layouts.admin')

@section('title', 'Edit Project - HRMS Pro')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="mb-4">
                <a href="{{ route('projects.index') }}" class="text-decoration-none text-muted">
                    <i class="bi bi-arrow-left me-1"></i>Back to Projects
                </a>
            </div>
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold mb-0"><i class="bi bi-pencil me-2 text-warning"></i>Edit: {{ $project->name }}</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('projects.update', $project) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Project Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" value="{{ old('name', $project->name) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Project Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="project_code" value="{{ old('project_code', $project->project_code) }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea class="form-control" name="description" rows="3">{{ old('description', $project->description) }}</textarea>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Client</label>
                                <input type="text" class="form-control" name="client" value="{{ old('client', $project->client) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Start Date</label>
                                <input type="date" class="form-control" name="start_date" value="{{ old('start_date', $project->start_date?->format('Y-m-d')) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">End Date</label>
                                <input type="date" class="form-control" name="end_date" value="{{ old('end_date', $project->end_date?->format('Y-m-d')) }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Manager</label>
                                <select class="form-select" name="project_manager_id">
                                    <option value="">Select</option>
                                    @foreach($managers as $mgr)
                                    <option value="{{ $mgr->id }}" {{ old('project_manager_id', $project->project_manager_id) == $mgr->id ? 'selected' : '' }}>{{ $mgr->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Team</label>
                                <select class="form-select" name="team_id">
                                    <option value="">Select</option>
                                    @foreach($teams as $team)
                                    <option value="{{ $team->id }}" {{ old('team_id', $project->team_id) == $team->id ? 'selected' : '' }}>{{ $team->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Priority</label>
                                <select class="form-select" name="priority" required>
                                    @foreach(['low','medium','high','urgent'] as $p)
                                    <option value="{{ $p }}" {{ old('priority', $project->priority) == $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Status</label>
                                <select class="form-select" name="status" required>
                                    @foreach(['planning','active','on_hold','completed','cancelled'] as $s)
                                    <option value="{{ $s }}" {{ old('status', $project->status) == $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Progress</label>
                                <input type="number" class="form-control" name="progress" value="{{ old('progress', $project->progress ?? 0) }}" min="0" max="100">
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-circle me-1"></i>Update Project</button>
                            <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
