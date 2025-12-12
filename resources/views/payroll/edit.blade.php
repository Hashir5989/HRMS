@extends('layouts.admin')

@section('title', 'Edit ERP Salary - ' . $employee->full_name)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1"><i class="bi bi-pencil-square text-primary me-2"></i>Edit ERP Salary Breakdown</h3>
            <p class="text-muted mb-0">Manage compensation, allowances, deductions, and banking for <strong>{{ $employee->full_name }}</strong></p>
        </div>
        <a href="{{ route('payroll.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Back to Payroll
        </a>
    </div>

    <form action="{{ route('payroll.update', $employee) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="row g-4">
            <!-- Left Column: Salary & Allowances -->
            <div class="col-lg-7">
                <!-- Employee Summary Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center gap-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($employee->full_name) }}&size=48&background=random" class="rounded-circle" width="48" height="48">
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">{{ $employee->full_name }}</h6>
                                <small class="text-muted">{{ $employee->employee_id }} · {{ $employee->department->name ?? 'Department' }} · {{ $employee->designation->name ?? 'Role' }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Salary & Allowances Form -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h6 class="fw-bold mb-0 text-primary"><i class="bi bi-cash me-2"></i>Earnings & Allowances</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="salary" class="form-label fw-semibold">Base Monthly Salary ($) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0" name="salary" id="salary" class="form-control" value="{{ old('salary', $employee->salary ?? 0) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label for="pay_frequency" class="form-label fw-semibold">Pay Frequency</label>
                                <select name="pay_frequency" id="pay_frequency" class="form-select">
                                    <option value="monthly" {{ old('pay_frequency', $employee->pay_frequency) == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                    <option value="bi_weekly" {{ old('pay_frequency', $employee->pay_frequency) == 'bi_weekly' ? 'selected' : '' }}>Bi-Weekly</option>
                                    <option value="weekly" {{ old('pay_frequency', $employee->pay_frequency) == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="allowance_housing" class="form-label fw-semibold">Housing Allowance ($)</label>
                                <input type="number" step="0.01" min="0" name="allowance_housing" id="allowance_housing" class="form-control" value="{{ old('allowance_housing', $employee->allowance_housing ?? 0) }}">
                            </div>

                            <div class="col-md-4">
                                <label for="allowance_medical" class="form-label fw-semibold">Medical Allowance ($)</label>
                                <input type="number" step="0.01" min="0" name="allowance_medical" id="allowance_medical" class="form-control" value="{{ old('allowance_medical', $employee->allowance_medical ?? 0) }}">
                            </div>

                            <div class="col-md-4">
                                <label for="allowance_transport" class="form-label fw-semibold">Transport Allowance ($)</label>
                                <input type="number" step="0.01" min="0" name="allowance_transport" id="allowance_transport" class="form-control" value="{{ old('allowance_transport', $employee->allowance_transport ?? 0) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Deductions Form -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h6 class="fw-bold mb-0 text-danger"><i class="bi bi-dash-circle me-2"></i>Deductions</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="deduction_tax" class="form-label fw-semibold">Income Tax ($)</label>
                                <input type="number" step="0.01" min="0" name="deduction_tax" id="deduction_tax" class="form-control" value="{{ old('deduction_tax', $employee->deduction_tax ?? 0) }}">
                            </div>

                            <div class="col-md-6">
                                <label for="deduction_other" class="form-label fw-semibold">PF & Other Deductions ($)</label>
                                <input type="number" step="0.01" min="0" name="deduction_other" id="deduction_other" class="form-control" value="{{ old('deduction_other', $employee->deduction_other ?? 0) }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Banking & Actions -->
            <div class="col-lg-5">
                <!-- Banking Information -->
                @php $bank = $employee->bank_details ?? []; @endphp
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-bank me-2 text-success"></i>Bank Account Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="bank_name" class="form-label fw-semibold">Bank Name</label>
                            <input type="text" name="bank_name" id="bank_name" class="form-control" value="{{ old('bank_name', $bank['bank_name'] ?? '') }}" placeholder="e.g. Chase Bank, HSBC">
                        </div>

                        <div class="mb-3">
                            <label for="account_no" class="form-label fw-semibold">Account Number</label>
                            <input type="text" name="account_no" id="account_no" class="form-control" value="{{ old('account_no', $bank['account_no'] ?? '') }}" placeholder="e.g. 1234567890">
                        </div>

                        <div class="mb-3">
                            <label for="ifsc_code" class="form-label fw-semibold">IFSC / IBAN / Swift Code</label>
                            <input type="text" name="ifsc_code" id="ifsc_code" class="form-control" value="{{ old('ifsc_code', $bank['ifsc_code'] ?? '') }}" placeholder="e.g. CHASUS33">
                        </div>

                        <div class="mb-3">
                            <label for="account_holder" class="form-label fw-semibold">Account Holder Name</label>
                            <input type="text" name="account_holder" id="account_holder" class="form-control" value="{{ old('account_holder', $bank['account_holder'] ?? $employee->full_name) }}">
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="card border-0 shadow-sm bg-light">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary py-2 fw-semibold">
                                <i class="bi bi-check-circle me-1"></i>Save & Calculate ERP Salary
                            </button>
                            <a href="{{ route('payroll.payslip', $employee) }}" class="btn btn-outline-success py-2">
                                <i class="bi bi-receipt me-1"></i>View Payslip Preview
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
