@extends('layouts.admin')

@section('title', 'Add Holiday - HRMS Pro')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="mb-4">
                <a href="{{ route('holidays.index') }}" class="text-decoration-none text-muted"><i class="bi bi-arrow-left me-1"></i>Back to Holidays</a>
            </div>
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold mb-0"><i class="bi bi-calendar-plus me-2 text-primary"></i>Add Holiday</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('holidays.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Holiday Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('date') is-invalid @enderror" name="date" value="{{ old('date') }}" required>
                                @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Type <span class="text-danger">*</span></label>
                                <select class="form-select" name="type" required>
                                    <option value="public" {{ old('type') == 'public' ? 'selected' : '' }}>Public Holiday</option>
                                    <option value="company" {{ old('type') == 'company' ? 'selected' : '' }}>Company Holiday</option>
                                    <option value="optional" {{ old('type') == 'optional' ? 'selected' : '' }}>Optional Holiday</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea class="form-control" name="description" rows="2">{{ old('description') }}</textarea>
                        </div>
                        <div class="mb-4 form-check">
                            <input class="form-check-input" type="checkbox" name="is_recurring" id="is_recurring" value="1" {{ old('is_recurring') ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="is_recurring">Recurring annually</label>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-circle me-1"></i>Add Holiday</button>
                            <a href="{{ route('holidays.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
