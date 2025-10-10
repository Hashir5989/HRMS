@extends('layouts.admin')

@section('title', 'Holidays - HRMS Pro')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Holidays</h3>
            <p class="text-muted mb-0">Company and public holiday calendar</p>
        </div>
        @can('department.manage')
        <a href="{{ route('holidays.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Add Holiday
        </a>
        @endcan
    </div>

    <!-- Upcoming Holidays Banner -->
    @if($upcomingHolidays->count())
    <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg,#4f46e5,#7c3aed); color:#fff;">
        <div class="card-body py-3">
            <h6 class="fw-bold mb-2 opacity-75 text-uppercase" style="font-size:.7rem;letter-spacing:.08em;">Upcoming Holidays</h6>
            <div class="d-flex flex-wrap gap-3">
                @foreach($upcomingHolidays as $h)
                <div class="bg-white bg-opacity-10 rounded-3 px-3 py-2 d-flex align-items-center gap-2">
                    <div class="text-center" style="min-width:36px;">
                        <div class="fw-bold lh-1" style="font-size:1.1rem;">{{ $h->date->format('d') }}</div>
                        <div style="font-size:.65rem;opacity:.8;">{{ $h->date->format('M') }}</div>
                    </div>
                    <div>
                        <div class="fw-semibold small lh-1">{{ $h->name }}</div>
                        <div style="font-size:.7rem;opacity:.75;">{{ $h->date->diffForHumans() }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Holiday list grouped by year -->
    @forelse($holidays as $year => $yearHolidays)
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0"><i class="bi bi-calendar3 me-2 text-primary"></i>{{ $year }}</h6>
            <span class="badge bg-primary-subtle text-primary">{{ $yearHolidays->count() }} holidays</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Date</th>
                            <th>Holiday</th>
                            <th>Day</th>
                            <th>Type</th>
                            <th>Recurring</th>
                            @can('department.manage')<th class="pe-3">Actions</th>@endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($yearHolidays->sortBy('date') as $holiday)
                        @php
                            $isPast = $holiday->date->isPast();
                            $typeColors = ['public' => 'primary', 'company' => 'success', 'optional' => 'warning'];
                        @endphp
                        <tr class="{{ $isPast ? 'opacity-60' : '' }}">
                            <td class="ps-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="text-center bg-primary-subtle text-primary rounded-2 px-2 py-1" style="min-width:44px;">
                                        <div class="fw-bold lh-1">{{ $holiday->date->format('d') }}</div>
                                        <div style="font-size:.65rem;">{{ $holiday->date->format('M') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="fw-semibold small">{{ $holiday->name }}</td>
                            <td class="small text-muted">{{ $holiday->date->format('l') }}</td>
                            <td>
                                <span class="badge bg-{{ $typeColors[$holiday->type] ?? 'secondary' }}-subtle text-{{ $typeColors[$holiday->type] ?? 'secondary' }}">{{ ucfirst($holiday->type) }}</span>
                            </td>
                            <td>
                                @if($holiday->is_recurring)
                                <span class="badge bg-success-subtle text-success"><i class="bi bi-arrow-repeat me-1"></i>Yes</span>
                                @else
                                <span class="badge bg-secondary-subtle text-secondary">One-time</span>
                                @endif
                            </td>
                            @can('department.manage')
                            <td class="pe-3">
                                <div class="d-flex gap-1">
                                    <a href="{{ route('holidays.edit', $holiday) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('holidays.destroy', $holiday) }}" method="POST" onsubmit="return confirm('Delete this holiday?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                            @endcan
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @empty
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="bi bi-calendar-x fs-1 text-muted d-block mb-2"></i>
            <p class="text-muted">No holidays added yet.</p>
            @can('department.manage')
            <a href="{{ route('holidays.create') }}" class="btn btn-primary">Add Holiday</a>
            @endcan
        </div>
    </div>
    @endforelse

</div>
@endsection
