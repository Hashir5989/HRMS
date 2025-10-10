@extends('layouts.admin')

@section('title', 'Meeting Details - HRMS Pro')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('meetings.index') }}" class="text-decoration-none text-muted"><i class="bi bi-arrow-left me-1"></i>Back to Meetings</a>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            @php
                                $statusColors = ['scheduled' => 'primary', 'ongoing' => 'success', 'completed' => 'secondary', 'cancelled' => 'danger'];
                            @endphp
                            <span class="badge bg-{{ $statusColors[$meeting->status] ?? 'secondary' }}-subtle text-{{ $statusColors[$meeting->status] ?? 'secondary' }} rounded-pill px-3 py-2 fw-semibold mb-2">
                                {{ ucfirst($meeting->status) }}
                            </span>
                            <h3 class="fw-bold text-dark mb-1">{{ $meeting->title }}</h3>
                        </div>
                        @if($meeting->created_by === auth()->id())
                        <div class="dropdown">
                            <button class="btn btn-light border btn-sm rounded-circle" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical"></i></button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                <li><a class="dropdown-item" href="{{ route('meetings.edit', $meeting) }}"><i class="bi bi-pencil me-2"></i>Edit Meeting</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('meetings.destroy', $meeting) }}" method="POST" onsubmit="return confirm('Cancel this meeting?')">
                                        @csrf @method('DELETE')
                                        <button class="dropdown-item text-danger"><i class="bi bi-trash me-2"></i>Cancel Meeting</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                        @endif
                    </div>

                    <div class="row g-3 py-3 border-top border-bottom my-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-primary bg-opacity-10 text-primary p-2 rounded-3">
                                    <i class="bi bi-calendar3 fs-4"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Date & Time</small>
                                    <span class="fw-semibold">{{ $meeting->start_time->format('l, M d, Y') }}</span><br>
                                    <small class="text-muted">{{ $meeting->start_time->format('h:i A') }} - {{ $meeting->end_time->format('h:i A') }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-primary bg-opacity-10 text-primary p-2 rounded-3">
                                    <i class="bi bi-geo-alt fs-4"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Location / Virtual Room</small>
                                    <span class="fw-semibold">{{ $meeting->location ?? 'Online Meeting' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($meeting->meeting_link)
                    <div class="alert alert-primary border-0 bg-primary bg-opacity-10 rounded-4 d-flex align-items-center justify-content-between p-3 mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-camera-video fs-3 text-primary"></i>
                            <div>
                                <h6 class="fw-bold text-primary mb-0">Video Conference</h6>
                                <small class="text-muted">{{ $meeting->meeting_link }}</small>
                            </div>
                        </div>
                        <a href="{{ $meeting->meeting_link }}" target="_blank" class="btn btn-primary rounded-pill px-4">
                            <i class="bi bi-box-arrow-up-right me-1"></i>Join Video Call
                        </a>
                    </div>
                    @endif

                    <h5 class="fw-bold mb-3">Agenda & Description</h5>
                    <div class="text-muted lh-lg">
                        {!! nl2br(e($meeting->description ?? 'No specific agenda or description was added for this meeting.')) !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-person me-2 text-primary"></i>Organized By</h5>
                    <div class="d-flex align-items-center gap-3">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($meeting->creator->name ?? 'Admin') }}&background=random" class="rounded-circle" width="48" height="48">
                        <div>
                            <h6 class="fw-bold mb-0">{{ $meeting->creator->name ?? 'System' }}</h6>
                            <small class="text-muted">{{ $meeting->creator->email ?? '' }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0"><i class="bi bi-people me-2 text-primary"></i>Attendees ({{ $meeting->attendees->count() }})</h5>
                    </div>
                    <div class="list-group list-group-flush">
                        @forelse($meeting->attendees as $attendee)
                        <div class="list-group-item px-0 py-2 border-0 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($attendee->name) }}&background=random" class="rounded-circle" width="36" height="36">
                                <div>
                                    <h6 class="fw-semibold mb-0 small">{{ $attendee->name }}</h6>
                                    <small class="text-muted" style="font-size: 0.75rem;">{{ $attendee->email }}</small>
                                </div>
                            </div>
                            <span class="badge bg-success-subtle text-success small rounded-pill px-2">
                                {{ ucfirst($attendee->pivot->rsvp ?? 'accepted') }}
                            </span>
                        </div>
                        @empty
                        <p class="text-muted small mb-0">No attendees explicitly invited.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
