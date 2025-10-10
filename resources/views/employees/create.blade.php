@extends('layouts.admin')

@section('title', 'Add Employee - HRMS Pro')

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
                    <h5 class="fw-bold mb-0"><i class="bi bi-person-plus me-2 text-primary"></i>Add New Employee</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('employees.store') }}" method="POST">
                        @csrf

                        <h6 class="fw-bold text-muted mb-3 text-uppercase" style="font-size:0.75rem; letter-spacing:0.05em;">Personal Information</h6>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" required>
                                @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" required>
                                @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Phone</label>
                                <input type="text" class="form-control" name="phone" value="{{ old('phone') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Date of Birth</label>
                                <input type="date" class="form-control" name="date_of_birth" value="{{ old('date_of_birth') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Gender <span class="text-danger">*</span></label>
                                <select class="form-select" name="gender" required>
                                    <option value="">Select</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Address</label>
                            <textarea class="form-control" name="address" rows="2">{{ old('address') }}</textarea>
                        </div>

                        <hr class="my-4">
                        <h6 class="fw-bold text-muted mb-3 text-uppercase" style="font-size:0.75rem; letter-spacing:0.05em;">Employment Details</h6>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Department <span class="text-danger">*</span></label>
                                <select class="form-select @error('department_id') is-invalid @enderror" name="department_id" required>
                                    <option value="">Select Department</option>
                                    @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                                @error('department_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Designation</label>
                                <select class="form-select" name="designation_id">
                                    <option value="">Select Designation</option>
                                    @foreach($designations as $desg)
                                    <option value="{{ $desg->id }}" {{ old('designation_id') == $desg->id ? 'selected' : '' }}>{{ $desg->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Team</label>
                                <select class="form-select" name="team_id">
                                    <option value="">Select Team</option>
                                    @foreach($teams as $team)
                                    <option value="{{ $team->id }}" {{ old('team_id') == $team->id ? 'selected' : '' }}>{{ $team->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Reporting Manager</label>
                                <select class="form-select" name="manager_id">
                                    <option value="">Select Manager</option>
                                    @foreach($managers as $mgr)
                                    <option value="{{ $mgr->id }}" {{ old('manager_id') == $mgr->id ? 'selected' : '' }}>{{ $mgr->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Joining Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('joining_date') is-invalid @enderror" name="joining_date" value="{{ old('joining_date') }}" required>
                                @error('joining_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Employment Type <span class="text-danger">*</span></label>
                                <select class="form-select" name="employment_type" required>
                                    <option value="">Select Type</option>
                                    <option value="full_time" {{ old('employment_type') == 'full_time' ? 'selected' : '' }}>Full Time</option>
                                    <option value="part_time" {{ old('employment_type') == 'part_time' ? 'selected' : '' }}>Part Time</option>
                                    <option value="contract" {{ old('employment_type') == 'contract' ? 'selected' : '' }}>Contract</option>
                                    <option value="intern" {{ old('employment_type') == 'intern' ? 'selected' : '' }}>Intern</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Salary</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" name="salary" value="{{ old('salary') }}" step="0.01">
                                </div>
                            </div>
                        </div>

                        <div class="bg-light rounded-3 p-3 mb-4">
                            <small class="text-muted"><i class="bi bi-info-circle me-1"></i>A user account will be created with the email above and default password <code>password123</code>.</small>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-circle me-1"></i>Create Employee</button>
                            <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
