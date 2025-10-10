@extends('layouts.admin')

@section('title', 'Edit Employee - HRMS Pro')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="mb-4">
                <a href="{{ route('employees.index') }}" class="text-decoration-none text-muted">
                    <i class="bi bi-arrow-left me-1"></i>Back to Employees
                </a>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold mb-0"><i class="bi bi-pencil me-2 text-warning"></i>Edit Employee: {{ $employee->full_name }}</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('employees.update', $employee) }}" method="POST">
                        @csrf @method('PUT')

                        <h6 class="fw-bold text-muted mb-3 text-uppercase" style="font-size:0.75rem;">Personal Information</h6>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name', $employee->first_name) }}" required>
                                @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name', $employee->last_name) }}" required>
                                @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Phone</label>
                                <input type="text" class="form-control" name="phone" value="{{ old('phone', $employee->phone) }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Date of Birth</label>
                                <input type="date" class="form-control" name="date_of_birth" value="{{ old('date_of_birth', $employee->date_of_birth?->format('Y-m-d')) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Gender <span class="text-danger">*</span></label>
                                <select class="form-select" name="gender" required>
                                    <option value="male" {{ old('gender', $employee->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $employee->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender', $employee->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                <select class="form-select" name="status" required>
                                    <option value="active" {{ old('status', $employee->status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $employee->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="terminated" {{ old('status', $employee->status) == 'terminated' ? 'selected' : '' }}>Terminated</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Address</label>
                            <textarea class="form-control" name="address" rows="2">{{ old('address', $employee->address) }}</textarea>
                        </div>

                        <hr class="my-4">
                        <h6 class="fw-bold text-muted mb-3 text-uppercase" style="font-size:0.75rem;">Employment Details</h6>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Department <span class="text-danger">*</span></label>
                                <select class="form-select" name="department_id" required>
                                    <option value="">Select</option>
                                    @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id', $employee->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Designation</label>
                                <select class="form-select" name="designation_id">
                                    <option value="">Select</option>
                                    @foreach($designations as $desg)
                                    <option value="{{ $desg->id }}" {{ old('designation_id', $employee->designation_id) == $desg->id ? 'selected' : '' }}>{{ $desg->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Team</label>
                                <select class="form-select" name="team_id">
                                    <option value="">Select</option>
                                    @foreach($teams as $team)
                                    <option value="{{ $team->id }}" {{ old('team_id', $employee->team_id) == $team->id ? 'selected' : '' }}>{{ $team->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Manager</label>
                                <select class="form-select" name="manager_id">
                                    <option value="">Select</option>
                                    @foreach($managers as $mgr)
                                    <option value="{{ $mgr->id }}" {{ old('manager_id', $employee->manager_id) == $mgr->id ? 'selected' : '' }}>{{ $mgr->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Joining Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="joining_date" value="{{ old('joining_date', $employee->joining_date?->format('Y-m-d')) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Employment Type <span class="text-danger">*</span></label>
                                <select class="form-select" name="employment_type" required>
                                    <option value="full_time" {{ old('employment_type', $employee->employment_type) == 'full_time' ? 'selected' : '' }}>Full Time</option>
                                    <option value="part_time" {{ old('employment_type', $employee->employment_type) == 'part_time' ? 'selected' : '' }}>Part Time</option>
                                    <option value="contract" {{ old('employment_type', $employee->employment_type) == 'contract' ? 'selected' : '' }}>Contract</option>
                                    <option value="intern" {{ old('employment_type', $employee->employment_type) == 'intern' ? 'selected' : '' }}>Intern</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Salary</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" name="salary" value="{{ old('salary', $employee->salary) }}" step="0.01">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-circle me-1"></i>Update Employee</button>
                            <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
