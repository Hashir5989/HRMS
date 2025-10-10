@extends('layouts.admin')

@section('title', 'Edit Holiday - HRMS Pro')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="mb-4">
                <a href="{{ route('holidays.index') }}" class="text-decoration-none text-muted"><i class="bi bi-arrow-left me-1"></i>Back</a>
            </div>
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold mb-0"><i class="bi bi-pencil me-2 text-warning"></i>Edit: {{ $holiday->name }}</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('holidays.update', $holiday) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" value="{{ old('name', $holiday->name) }}" required>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="date" value="{{ old('date', $holiday->date->format('Y-m-d')) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Type <span class="text-danger">*</span></label>
                                <select class="form-select" name="type" required>
                                    @foreach(['public','company','optional'] as $t)
                                    <option value="{{ $t }}" {{ old('type', $holiday->type) == $t ? 'selected' : '' }}>{{ ucfirst($t) }} Holiday</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea class="form-control" name="description" rows="2">{{ old('description', $holiday->description) }}</textarea>
                        </div>
                        <div class="mb-4 form-check">
                            <input class="form-check-input" type="checkbox" name="is_recurring" id="is_recurring" value="1" {{ $holiday->is_recurring ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="is_recurring">Recurring annually</label>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-circle me-1"></i>Update</button>
                            <a href="{{ route('holidays.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
