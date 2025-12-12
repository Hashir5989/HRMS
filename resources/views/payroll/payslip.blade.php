@extends('layouts.admin')

@section('title', 'Payslip - ' . $employee->full_name)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 d-print-none">
        <div>
            <h3 class="fw-bold text-dark mb-1"><i class="bi bi-receipt text-success me-2"></i>Official Monthly Payslip</h3>
            <p class="text-muted mb-0">Payroll statement for <strong>{{ $employee->full_name }}</strong> ({{ now()->format('F Y') }})</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('payroll.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back
            </a>
            <button onclick="window.print()" class="btn btn-success fw-semibold">
                <i class="bi bi-printer me-1"></i>Print Payslip
            </button>
        </div>
    </div>

    <!-- Printable Payslip Document -->
    <div class="card border-0 shadow-sm mx-auto" style="max-width: 850px; background: #fff;" id="printablePayslip">
        <div class="card-body p-4 p-md-5">
            <!-- Company Header -->
            <div class="d-flex justify-content-between align-items-start border-bottom pb-4 mb-4">
                <div>
                    <h3 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                        <span class="bg-primary text-white rounded p-1 d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="bi bi-buildings"></i>
                        </span>
                        HRMS Pro Enterprise
                    </h3>
                    <p class="text-muted mb-0 small">Corporate Headquarters · Enterprise HR & Payroll Division</p>
                    <small class="text-muted">Generated on {{ now()->format('M d, Y') }}</small>
                </div>
                <div class="text-end">
                    <span class="badge bg-success-subtle text-success fs-6 border border-success-subtle mb-1 px-3 py-2">
                        <i class="bi bi-check-circle-fill me-1"></i>PAYSLIP CONFIRMED
                    </span>
                    <p class="mb-0 fw-semibold text-dark small">Pay Period: {{ now()->startOfMonth()->format('M 1') }} - {{ now()->endOfMonth()->format('M t, Y') }}</p>
                </div>
            </div>

            <!-- Employee & Banking Details Grid -->
            <div class="row g-3 bg-light rounded-3 p-3 mb-4">
                <div class="col-md-6">
                    <h6 class="fw-bold text-primary mb-2">Employee Information</h6>
                    <table class="table table-borderless table-sm mb-0 small">
                        <tr>
                            <td class="text-muted px-0 py-1" style="width: 120px;">Employee Name:</td>
                            <td class="fw-bold text-dark px-0 py-1">{{ $employee->full_name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted px-0 py-1">Employee ID:</td>
                            <td class="fw-semibold text-dark px-0 py-1">{{ $employee->employee_id }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted px-0 py-1">Department:</td>
                            <td class="fw-semibold text-dark px-0 py-1">{{ $employee->department->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted px-0 py-1">Designation:</td>
                            <td class="fw-semibold text-dark px-0 py-1">{{ $employee->designation->name ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6 border-start border-md-0 ps-md-4">
                    <h6 class="fw-bold text-primary mb-2">Payment Details</h6>
                    @php $bank = $employee->bank_details ?? []; @endphp
                    <table class="table table-borderless table-sm mb-0 small">
                        <tr>
                            <td class="text-muted px-0 py-1" style="width: 120px;">Bank Name:</td>
                            <td class="fw-semibold text-dark px-0 py-1">{{ $bank['bank_name'] ?? 'Bank Transfer' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted px-0 py-1">Account No:</td>
                            <td class="fw-semibold text-dark px-0 py-1 font-monospace">{{ $bank['account_no'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted px-0 py-1">IFSC / IBAN:</td>
                            <td class="fw-semibold text-dark px-0 py-1">{{ $bank['ifsc_code'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted px-0 py-1">Pay Frequency:</td>
                            <td class="fw-semibold text-dark px-0 py-1">{{ ucfirst($employee->pay_frequency ?? 'monthly') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Earnings vs Deductions Breakdown Table -->
            <div class="row g-4 mb-4">
                <!-- Earnings -->
                <div class="col-md-6">
                    <div class="border rounded-3 p-3 h-100">
                        <h6 class="fw-bold text-success border-bottom pb-2 mb-3"><i class="bi bi-plus-circle me-1"></i>Earnings & Allowances</h6>
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted">Basic Salary:</span>
                            <span class="fw-semibold">${{ number_format($employee->salary ?? 0, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted">Housing Allowance:</span>
                            <span class="fw-semibold">${{ number_format($employee->allowance_housing ?? 0, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted">Medical Allowance:</span>
                            <span class="fw-semibold">${{ number_format($employee->allowance_medical ?? 0, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted">Transport Allowance:</span>
                            <span class="fw-semibold">${{ number_format($employee->allowance_transport ?? 0, 2) }}</span>
                        </div>
                        @php
                            $totalEarnings = ($employee->salary ?? 0) + ($employee->allowance_housing ?? 0) + ($employee->allowance_medical ?? 0) + ($employee->allowance_transport ?? 0);
                        @endphp
                        <div class="d-flex justify-content-between border-top pt-2 mt-3 fw-bold text-dark">
                            <span>Total Gross Earnings:</span>
                            <span class="text-success">${{ number_format($totalEarnings, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Deductions -->
                <div class="col-md-6">
                    <div class="border rounded-3 p-3 h-100">
                        <h6 class="fw-bold text-danger border-bottom pb-2 mb-3"><i class="bi bi-dash-circle me-1"></i>Deductions</h6>
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted">Income Tax:</span>
                            <span class="fw-semibold">${{ number_format($employee->deduction_tax ?? 0, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted">Provident Fund & Other:</span>
                            <span class="fw-semibold">${{ number_format($employee->deduction_other ?? 0, 2) }}</span>
                        </div>
                        @php
                            $totalDeductions = ($employee->deduction_tax ?? 0) + ($employee->deduction_other ?? 0);
                        @endphp
                        <div class="d-flex justify-content-between border-top pt-2 mt-3 fw-bold text-dark" style="margin-top: 54px !important;">
                            <span>Total Deductions:</span>
                            <span class="text-danger">-${{ number_format($totalDeductions, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Net Salary Highlight -->
            <div class="card border-0 bg-primary text-white p-3 rounded-3 mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fw-semibold mb-0 opacity-75">NET SALARY PAYABLE</h6>
                        <small class="opacity-75">Gross Earnings minus Total Deductions</small>
                    </div>
                    <h2 class="fw-bold mb-0">${{ number_format($netSalary, 2) }}</h2>
                </div>
            </div>

            <!-- Signatures -->
            <div class="row pt-5 mt-4 text-center">
                <div class="col-6">
                    <div class="border-top pt-2 w-75 mx-auto">
                        <small class="fw-semibold text-muted d-block">Employee Signature</small>
                    </div>
                </div>
                <div class="col-6">
                    <div class="border-top pt-2 w-75 mx-auto">
                        <small class="fw-semibold text-muted d-block">Authorized HR Manager Signature</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
@media print {
    body * { visibility: hidden; }
    #printablePayslip, #printablePayslip * { visibility: visible; }
    #printablePayslip { position: absolute; left: 0; top: 0; width: 100%; max-width: 100% !important; shadow: none !important; }
    .d-print-none { display: none !important; }
}
</style>
@endpush
@endsection
