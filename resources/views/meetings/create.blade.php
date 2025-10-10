@extends('layouts.admin')

@section('title', 'Schedule Meeting - HRMS Pro')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="mb-4">
                <a href="{{ route('meetings.index') }}" class="text-decoration-none text-muted"><i class="bi bi-arrow-left me-1"></i>Back to Meetings</a>
            </div>
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold mb-0"><i class="bi bi-calendar-plus me-2 text-primary"></i>Schedule New Meeting</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('meetings.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Meeting Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" placeholder="e.g. Q4 Sprint Planning" required>
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Start Time <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control @error('start_time') is-invalid @enderror" name="start_time" value="{{ old('start_time') }}" required>
                                @error('start_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">End Time <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control @error('end_time') is-invalid @enderror" name="end_time" value="{{ old('end_time') }}" required>
                                @error('end_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Location / Room</label>
                                <input type="text" class="form-control" name="location" value="{{ old('location') }}" placeholder="e.g. Conference Room B / Remote">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Meeting Link (Video Call URL)</label>
                                <input type="url" class="form-control @error('meeting_link') is-invalid @enderror" name="meeting_link" value="{{ old('meeting_link') }}" placeholder="https://meet.google.com/xyz">
                                @error('meeting_link')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description / Agenda</label>
                            <textarea class="form-control" name="description" rows="3" placeholder="Outline key topics to discuss...">{{ old('description') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Invite Attendees</label>
                            <select class="form-select select2" name="attendees[]" multiple style="min-height: 120px;">
                                @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ in_array($user->id, old('attendees', [])) ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Hold Ctrl/Cmd to select multiple participants.</small>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-circle me-1"></i>Schedule Meeting</button>
                            <a href="{{ route('meetings.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
