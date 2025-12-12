<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (! auth()->check() || ! auth()->user()->hasAnyRole(['Super Admin', 'HR'])) {
                abort(403, 'Access Restricted: ERP & Salary details are accessible exclusively to Super Admin and HR roles.');
            }

            return $next($request);
        });
    }

    public function index()
    {
        $employees = Employee::with(['department', 'designation', 'user'])
            ->orderBy('id')
            ->paginate(15);

        $totalBaseSalary = Employee::sum('salary');
        $totalAllowances = Employee::sum('allowance_housing') + Employee::sum('allowance_medical') + Employee::sum('allowance_transport');
        $totalDeductions = Employee::sum('deduction_tax') + Employee::sum('deduction_other');

        $totalPayroll = Employee::all()->sum(function ($emp) {
            return $emp->calculateNetSalary();
        });

        $stats = [
            'total_payroll' => $totalPayroll,
            'total_base' => $totalBaseSalary,
            'total_allowances' => $totalAllowances,
            'total_deductions' => $totalDeductions,
            'employee_count' => Employee::count(),
        ];

        return view('payroll.index', compact('employees', 'stats'));
    }

    public function edit(Employee $employee)
    {
        $employee->load(['department', 'designation', 'user']);

        return view('payroll.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'salary' => 'required|numeric|min:0',
            'allowance_housing' => 'nullable|numeric|min:0',
            'allowance_medical' => 'nullable|numeric|min:0',
            'allowance_transport' => 'nullable|numeric|min:0',
            'deduction_tax' => 'nullable|numeric|min:0',
            'deduction_other' => 'nullable|numeric|min:0',
            'pay_frequency' => 'required|string|in:monthly,bi_weekly,weekly',
            'bank_name' => 'nullable|string|max:255',
            'account_no' => 'nullable|string|max:255',
            'ifsc_code' => 'nullable|string|max:255',
            'account_holder' => 'nullable|string|max:255',
        ]);

        $base = (float) $validated['salary'];
        $housing = (float) ($validated['allowance_housing'] ?? 0);
        $medical = (float) ($validated['allowance_medical'] ?? 0);
        $transport = (float) ($validated['allowance_transport'] ?? 0);
        $tax = (float) ($validated['deduction_tax'] ?? 0);
        $otherDeduction = (float) ($validated['deduction_other'] ?? 0);

        $netSalary = max(0, ($base + $housing + $medical + $transport) - ($tax + $otherDeduction));

        $bankDetails = [
            'bank_name' => $validated['bank_name'] ?? null,
            'account_no' => $validated['account_no'] ?? null,
            'ifsc_code' => $validated['ifsc_code'] ?? null,
            'account_holder' => $validated['account_holder'] ?? null,
        ];

        $employee->update([
            'salary' => $base,
            'allowance_housing' => $housing,
            'allowance_medical' => $medical,
            'allowance_transport' => $transport,
            'deduction_tax' => $tax,
            'deduction_other' => $otherDeduction,
            'net_salary' => $netSalary,
            'pay_frequency' => $validated['pay_frequency'],
            'bank_details' => $bankDetails,
        ]);

        return redirect()->route('payroll.index')->with('success', "ERP Salary details updated for {$employee->full_name}. Net Salary: $".number_format($netSalary, 2));
    }

    public function payslip(Employee $employee)
    {
        $employee->load(['department', 'designation', 'user']);
        $netSalary = $employee->calculateNetSalary();

        return view('payroll.payslip', compact('employee', 'netSalary'));
    }
}
