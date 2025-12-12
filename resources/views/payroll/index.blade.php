@extends('layouts.admin')

@section('title', 'ERP Payroll & Salary Management - HRMS Pro')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h3 class="fw-bold text-dark mb-1"><i class="bi bi-cash-stack text-success me-2"></i>ERP Payroll & Salary Management</h3>
            <p class="text-muted mb-0">Manage employee salaries, allowances, deductions, bank details, and payslips (HR & Super Admin Only)</p>
        </div>
        <div>
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 fs-6">
                <i class="bi bi-shield-lock-fill me-1"></i>Restricted Access (HR / Super Admin)
            </span>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- ERP Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #10b981 !important;">
                <div class="card-body">
                    <p class="text-muted small mb-1 text-uppercase fw-semibold">Total Monthly Payroll</p>
                    <h3 class="fw-bold text-dark mb-0">${{ number_format($stats['total_payroll'], 2) }}</h3>
                    <small class="text-success fw-semibold"><i class="bi bi-person-check-fill me-1"></i>{{ $stats['employee_count'] }} Employees</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #4f46e5 !important;">
                <div class="card-body">
                    <p class="text-muted small mb-1 text-uppercase fw-semibold">Base Salaries</p>
                    <h3 class="fw-bold text-dark mb-0">${{ number_format($stats['total_base'], 2) }}</h3>
                    <small class="text-muted">Total Gross Base</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #06b6d4 !important;">
                <div class="card-body">
                    <p class="text-muted small mb-1 text-uppercase fw-semibold">Total Allowances</p>
                    <h3 class="fw-bold text-info mb-0">${{ number_format($stats['total_allowances'], 2) }}</h3>
                    <small class="text-muted">Housing, Medical & Transport</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #ef4444 !important;">
                <div class="card-body">
                    <p class="text-muted small mb-1 text-uppercase fw-semibold">Total Deductions</p>
                    <h3 class="fw-bold text-danger mb-0">${{ number_format($stats['total_deductions'], 2) }}</h3>
                    <small class="text-muted">Tax & PF Deductions</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Salary List Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0"><i class="bi bi-wallet2 me-2 text-primary"></i>Employee Payroll Directory</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Employee</th>
                            <th>Department & Role</th>
                            <th>Base Salary</th>
                            <th>Allowances</th>
                            <th>Deductions</th>
                            <th>Net Salary</th>
                            <th>Bank Account</th>
                            <th class="pe-3 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $emp)
                        @php
                            $allowances = ($emp->allowance_housing ?? 0) + ($emp->allowance_medical ?? 0) + ($emp->allowance_transport ?? 0);
                            $deductions = ($emp->deduction_tax ?? 0) + ($emp->deduction_other ?? 0);
                            $net = $emp->calculateNetSalary();
                            $bank = $emp->bank_details ?? [];
                        @endphp
                        <tr>
                            <td class="ps-3">
                                <div class="d-flex align-items-center gap-2">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($emp->full_name) }}&size=36&background=random" class="rounded-circle" width="36" height="36">
                                    <div>
                                        <a href="{{ route('payroll.edit', $emp) }}" class="fw-semibold text-dark text-decoration-none small d-block">{{ $emp->full_name }}</a>
                                        <small class="text-muted">{{ $emp->employee_id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <small class="fw-semibold text-dark d-block">{{ $emp->department->name ?? 'N/A' }}</small>
                                <small class="text-muted">{{ $emp->designation->name ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <span class="fw-semibold text-dark">${{ number_format($emp->salary ?? 0, 2) }}</span>
                            </td>
                            <td>
                                <span class="badge bg-success-subtle text-success">+${{ number_format($allowances, 2) }}</span>
                            </td>
                            <td>
                                <span class="badge bg-danger-subtle text-danger">-${{ number_format($deductions, 2) }}</span>
                            </td>
                            <td>
                                <span class="fw-bold text-primary fs-6">${{ number_format($net, 2) }}</span>
                                <small class="d-block text-muted" style="font-size: 0.7rem;">{{ ucfirst($emp->pay_frequency ?? 'monthly') }}</small>
                            </td>
                            <td>
                                @if(!empty($bank['account_no']))
                                    <small class="fw-semibold text-dark d-block"><i class="bi bi-bank me-1"></i>{{ $bank['bank_name'] ?? 'Bank' }}</small>
                                    <small class="text-muted font-monospace">{{ $bank['account_no'] }}</small>
                                @else
                                    <small class="text-muted italic">No bank set</small>
                                @endif
                            </td>
                            <td class="pe-3 text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('payroll.edit', $emp) }}" class="btn btn-sm btn-outline-primary" title="Edit ERP Salary Details">
                                        <i class="bi bi-pencil-square me-1"></i>Edit ERP
                                    </a>
                                    <a href="{{ route('payroll.payslip', $emp) }}" class="btn btn-sm btn-outline-success" title="Generate Monthly Payslip">
                                        <i class="bi bi-receipt me-1"></i>Payslip
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-folder-x fs-1 d-block mb-2"></i>
                                No employee payroll records found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($employees->hasPages())
        <div class="card-footer bg-white border-0 py-3">{{ $employees->links() }}</div>
        @endif
    </div>
</div>
@endsection
