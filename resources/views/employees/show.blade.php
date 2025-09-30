@extends('layouts.admin')

@section('title', 'Employee Profile - HRMS Pro')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="bg-gradient-premium" style="height: 120px;"></div>
                <div class="card-body px-4 pb-4 position-relative pt-0">
                    <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between gap-3">
                        <div class="d-flex align-items-end gap-3" style="margin-top: -50px;">
                            <img src="{{ $employee->profile_photo ?? 'https://ui-avatars.com/api/?name='.urlencode($employee->full_name).'&background=random&size=120' }}" 
                                 class="rounded-circle border border-4 border-white shadow-sm bg-white" 
                                 alt="{{ $employee->full_name }}" 
                                 width="120" height="120" style="object-fit: cover;">
                            <div class="mb-2">
                                <h3 class="fw-bold mb-1">{{ $employee->full_name }}</h3>
                                <div class="d-flex align-items-center gap-3 text-muted">
                                    <span><i class="bi bi-briefcase me-1"></i> {{ $employee->designation->name ?? 'N/A' }}</span>
                                    <span><i class="bi bi-diagram-3 me-1"></i> {{ $employee->department->name ?? 'N/A' }}</span>
                                    <span class="badge {{ $employee->status === 'active' ? 'bg-success' : 'bg-secondary' }} bg-opacity-10 text-{{ $employee->status === 'active' ? 'success' : 'secondary' }} rounded-pill px-3">
                                        {{ ucfirst($employee->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex gap-2 mb-2">
                            @can('chat.send')
                            <button class="btn btn-outline-primary rounded-pill px-4">
                                <i class="bi bi-chat-dots me-2"></i> Message
                            </button>
                            @endcan
                            @can('employee.edit')
                            <a href="{{ route('employees.edit', $employee) }}" class="btn btn-primary rounded-pill px-4 premium-btn">
                                <i class="bi bi-pencil me-2"></i> Edit Profile
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <ul class="nav nav-pills nav-fill bg-white rounded-pill p-1 shadow-sm mb-4 flex-nowrap overflow-auto" id="employeeTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill active fw-semibold" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">Overview</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill fw-semibold" data-bs-toggle="tab" data-bs-target="#personal" type="button" role="tab">Personal Info</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill fw-semibold" data-bs-toggle="tab" data-bs-target="#employment" type="button" role="tab">Employment</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill fw-semibold" data-bs-toggle="tab" data-bs-target="#attendance" type="button" role="tab">Attendance</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill fw-semibold" data-bs-toggle="tab" data-bs-target="#leave" type="button" role="tab">Leave</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill fw-semibold" data-bs-toggle="tab" data-bs-target="#tasks" type="button" role="tab">Tasks</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill fw-semibold" data-bs-toggle="tab" data-bs-target="#projects" type="button" role="tab">Projects</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill fw-semibold" data-bs-toggle="tab" data-bs-target="#documents" type="button" role="tab">Documents</button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="employeeTabsContent">
        <!-- Overview Tab -->
        <div class="tab-pane fade show active" id="overview" role="tabpanel">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4">Contact Information</h5>
                            
                            <div class="d-flex align-items-start gap-3 mb-3">
                                <div class="bg-primary bg-opacity-10 text-primary p-2 rounded-3">
                                    <i class="bi bi-envelope fs-5"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Email Address</small>
                                    <span class="fw-medium">{{ $employee->user->email ?? 'N/A' }}</span>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-start gap-3 mb-3">
                                <div class="bg-primary bg-opacity-10 text-primary p-2 rounded-3">
                                    <i class="bi bi-telephone fs-5"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Phone Number</small>
                                    <span class="fw-medium">{{ $employee->phone ?? 'N/A' }}</span>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-start gap-3">
                                <div class="bg-primary bg-opacity-10 text-primary p-2 rounded-3">
                                    <i class="bi bi-geo-alt fs-5"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Address</small>
                                    <span class="fw-medium">{{ $employee->address ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4">Hierarchy</h5>
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="border rounded-4 p-3 border-light bg-light bg-opacity-50">
                                        <small class="text-muted text-uppercase fw-bold d-block mb-3">Reporting Manager</small>
                                        <div class="d-flex align-items-center gap-3">
                                            @if($employee->manager)
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($employee->manager->name) }}&background=random" class="rounded-circle" width="48" height="48">
                                                <div>
                                                    <h6 class="fw-bold mb-0">{{ $employee->manager->name }}</h6>
                                                    <small class="text-muted">Manager</small>
                                                </div>
                                            @else
                                                <div class="text-muted fst-italic">No manager assigned</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border rounded-4 p-3 border-light bg-light bg-opacity-50">
                                        <small class="text-muted text-uppercase fw-bold d-block mb-3">Team Lead</small>
                                        <div class="d-flex align-items-center gap-3">
                                            @if($employee->teamLead)
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($employee->teamLead->name) }}&background=random" class="rounded-circle" width="48" height="48">
                                                <div>
                                                    <h6 class="fw-bold mb-0">{{ $employee->teamLead->name }}</h6>
                                                    <small class="text-muted">Team Lead</small>
                                                </div>
                                            @else
                                                <div class="text-muted fst-italic">No team lead assigned</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Personal Info Tab -->
        <div class="tab-pane fade" id="personal" role="tabpanel">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Personal Details</h5>
                    <div class="row g-4">
                        <div class="col-md-4">
                            <small class="text-muted d-block mb-1">Date of Birth</small>
                            <span class="fw-medium">{{ $employee->date_of_birth ? $employee->date_of_birth->format('M d, Y') : 'N/A' }}</span>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block mb-1">Gender</small>
                            <span class="fw-medium">{{ ucfirst($employee->gender) ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block mb-1">Emergency Contact</small>
                            @if($employee->emergency_contact)
                                @php $ec = is_array($employee->emergency_contact) ? $employee->emergency_contact : json_decode($employee->emergency_contact, true); @endphp
                                <span class="fw-medium d-block">{{ $ec['name'] ?? '' }} ({{ $ec['relationship'] ?? '' }})</span>
                                <span class="text-muted">{{ $ec['phone'] ?? '' }}</span>
                            @else
                                <span class="text-muted fst-italic">Not provided</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add other tabs similarly -->
        <div class="tab-pane fade" id="employment" role="tabpanel">
            <!-- Employment Details -->
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 text-center py-5">
                    <i class="bi bi-briefcase text-muted fs-1 mb-3"></i>
                    <h5 class="fw-bold">Employment Details</h5>
                    <p class="text-muted mb-0">Employment records will be displayed here.</p>
                </div>
            </div>
        </div>
        
        <div class="tab-pane fade" id="tasks" role="tabpanel">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 text-center py-5">
                    <i class="bi bi-check2-square text-muted fs-1 mb-3"></i>
                    <h5 class="fw-bold">Tasks Assigned</h5>
                    <p class="text-muted mb-0">Task integration pending module implementation.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
