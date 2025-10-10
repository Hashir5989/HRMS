@extends('layouts.admin')

@section('title', 'Edit Department - HRMS Pro')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="mb-4">
                <a href="{{ route('departments.index') }}" class="text-decoration-none text-muted">
                    <i class="bi bi-arrow-left me-1"></i>Back to Departments
                </a>
            </div>
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold mb-0"><i class="bi bi-pencil me-2 text-warning"></i>Edit Department</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('departments.update', $department) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">Department Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $department->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $department->description) }}</textarea>
                        </div>
                        <div class="mb-4">
                            <label for="manager_id" class="form-label fw-semibold">Manager</label>
                            <select class="form-select" id="manager_id" name="manager_id">
                                <option value="">Select Manager</option>
                                @foreach($managers as $manager)
                                <option value="{{ $manager->id }}" {{ $department->manager_id == $manager->id ? 'selected' : '' }}>{{ $manager->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-circle me-1"></i>Update</button>
                            <a href="{{ route('departments.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
