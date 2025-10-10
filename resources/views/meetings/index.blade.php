@extends('layouts.admin')

@section('title', 'Meetings - HRMS Pro')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Meetings</h3>
            <p class="text-muted mb-0">Schedule and manage team meetings</p>
        </div>
        <a href="{{ route('meetings.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Schedule Meeting
        </a>
    </div>

    <!-- Upcoming meetings -->
    @if($upcomingMeetings->count())
    <div class="row g-3 mb-4">
        @foreach($upcomingMeetings as $meeting)
        @php $diff = now()->diffInHours($meeting->start_time, false); @endphp
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100" style="border-left: 3px solid #4f46e5 !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-primary-subtle text-primary small">
                            <i class="bi bi-clock me-1"></i>{{ $meeting->start_time->diffForHumans() }}
                        </span>
                        <span class="badge bg-success-subtle text-success">Scheduled</span>
                    </div>
                    <h6 class="fw-bold mb-1">{{ $meeting->title }}</h6>
                    <small class="text-muted d-block mb-2">
                        <i class="bi bi-calendar3 me-1"></i>{{ $meeting->start_time->format('M d, Y') }}
                        · {{ $meeting->start_time->format('h:i A') }} – {{ $meeting->end_time->format('h:i A') }}
                    </small>
                    @if($meeting->location)
                    <small class="text-muted"><i class="bi bi-geo-alt me-1"></i>{{ $meeting->location }}</small>
                    @endif
                    @if($meeting->meeting_link)
                    <div class="mt-2">
                        <a href="{{ $meeting->meeting_link }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill">
                            <i class="bi bi-camera-video me-1"></i>Join
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <!-- All Meetings Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <h6 class="fw-bold mb-0"><i class="bi bi-calendar-week me-2 text-primary"></i>All Meetings</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Meeting</th>
                            <th>Date & Time</th>
                            <th>Duration</th>
                            <th>Attendees</th>
                            <th>Status</th>
                            <th class="pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($meetings as $meeting)
                        @php
                            $statusColors = ['scheduled' => 'primary', 'ongoing' => 'success', 'completed' => 'secondary', 'cancelled' => 'danger'];
                            $duration = $meeting->start_time->diffInMinutes($meeting->end_time);
                            $hours = intdiv($duration, 60);
                            $mins  = $duration % 60;
                        @endphp
                        <tr>
                            <td class="ps-3">
                                <div>
                                    <a href="{{ route('meetings.show', $meeting) }}" class="fw-semibold text-dark text-decoration-none small">{{ $meeting->title }}</a>
                                    @if($meeting->location)
                                    <br><small class="text-muted"><i class="bi bi-geo-alt me-1"></i>{{ $meeting->location }}</small>
                                    @endif
                                </div>
                            </td>
                            <td class="small">
                                <span class="fw-semibold">{{ $meeting->start_time->format('M d, Y') }}</span><br>
                                <span class="text-muted">{{ $meeting->start_time->format('h:i A') }}</span>
                            </td>
                            <td class="small text-muted">
                                {{ $hours > 0 ? $hours . 'h ' : '' }}{{ $mins }}m
                            </td>
                            <td>
                                <div class="d-flex">
                                    @foreach($meeting->attendees->take(3) as $att)
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($att->name) }}&size=24&background=random" class="rounded-circle border border-white" width="24" height="24" title="{{ $att->name }}" style="margin-left:-6px;">
                                    @endforeach
                                    @if($meeting->attendees->count() > 3)
                                    <span class="rounded-circle bg-secondary-subtle text-secondary d-flex align-items-center justify-content-center border border-white" style="width:24px;height:24px;font-size:.6rem;margin-left:-6px;">+{{ $meeting->attendees->count() - 3 }}</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $statusColors[$meeting->status] ?? 'secondary' }}-subtle text-{{ $statusColors[$meeting->status] ?? 'secondary' }}">{{ ucfirst($meeting->status) }}</span>
                            </td>
                            <td class="pe-3">
                                <div class="d-flex gap-1">
                                    <a href="{{ route('meetings.show', $meeting) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                                    @if($meeting->created_by === auth()->id())
                                    <a href="{{ route('meetings.edit', $meeting) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('meetings.destroy', $meeting) }}" method="POST" onsubmit="return confirm('Cancel this meeting?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="bi bi-calendar-x fs-1 d-block mb-2"></i>No meetings found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($meetings->hasPages())
        <div class="card-footer bg-white border-0">{{ $meetings->links() }}</div>
        @endif
    </div>
</div>
@endsection
