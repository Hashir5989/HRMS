@extends('layouts.admin')

@section('title', 'Edit Meeting - HRMS Pro')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="mb-4">
                <a href="{{ route('meetings.show', $meeting) }}" class="text-decoration-none text-muted"><i class="bi bi-arrow-left me-1"></i>Back to Details</a>
            </div>
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold mb-0"><i class="bi bi-pencil me-2 text-warning"></i>Edit Meeting: {{ $meeting->title }}</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('meetings.update', $meeting) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Meeting Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title', $meeting->title) }}" required>
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Start Time <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control @error('start_time') is-invalid @enderror" name="start_time" value="{{ old('start_time', $meeting->start_time ? $meeting->start_time->format('Y-m-d\TH:i') : '') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">End Time <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control @error('end_time') is-invalid @enderror" name="end_time" value="{{ old('end_time', $meeting->end_time ? $meeting->end_time->format('Y-m-d\TH:i') : '') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                <select class="form-select" name="status" required>
                                    @foreach(['scheduled', 'ongoing', 'completed', 'cancelled'] as $st)
                                    <option value="{{ $st }}" {{ old('status', $meeting->status) == $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Location / Room</label>
                                <input type="text" class="form-control" name="location" value="{{ old('location', $meeting->location) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Meeting Link</label>
                                <input type="url" class="form-control" name="meeting_link" value="{{ old('meeting_link', $meeting->meeting_link) }}">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Description / Agenda</label>
                            <textarea class="form-control" name="description" rows="3">{{ old('description', $meeting->description) }}</textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-circle me-1"></i>Update Meeting</button>
                            <a href="{{ route('meetings.show', $meeting) }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
