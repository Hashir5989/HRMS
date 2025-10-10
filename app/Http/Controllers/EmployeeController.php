<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index()
    {
        $this->authorize('employee.view');
        $employees = Employee::with(['department', 'designation', 'manager'])->paginate(15);
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        $this->authorize('employee.create');
        $departments = Department::all();
        $designations = Designation::all();
        $teams = Team::all();
        $managers = User::role(['Manager', 'Super Admin'])->get();
        return view('employees.create', compact('departments', 'designations', 'teams', 'managers'));
    }

    public function store(Request $request)
    {
        $this->authorize('employee.create');

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'required|in:male,female,other',
            'department_id' => 'required|exists:departments,id',
            'designation_id' => 'nullable|exists:designations,id',
            'team_id' => 'nullable|exists:teams,id',
            'manager_id' => 'nullable|exists:users,id',
            'joining_date' => 'required|date',
            'employment_type' => 'required|in:full_time,part_time,contract,intern',
            'salary' => 'nullable|numeric|min:0',
            'address' => 'nullable|string|max:500',
        ]);

        // Create user account
        $user = User::create([
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make('password123'),
        ]);
        $user->assignRole('Employee');

        // Generate employee ID
        $lastEmp = Employee::withTrashed()->orderBy('id', 'desc')->first();
        $empId = 'EMP-' . str_pad(($lastEmp ? $lastEmp->id + 1 : 1), 5, '0', STR_PAD_LEFT);

        Employee::create([
            'user_id' => $user->id,
            'employee_id' => $empId,
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'phone' => $validated['phone'] ?? null,
            'date_of_birth' => $validated['date_of_birth'] ?? null,
            'gender' => $validated['gender'],
            'address' => $validated['address'] ?? null,
            'department_id' => $validated['department_id'],
            'designation_id' => $validated['designation_id'] ?? null,
            'team_id' => $validated['team_id'] ?? null,
            'manager_id' => $validated['manager_id'] ?? null,
            'joining_date' => $validated['joining_date'],
            'employment_type' => $validated['employment_type'],
            'salary' => $validated['salary'] ?? null,
            'status' => 'active',
        ]);

        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }

    public function show(Employee $employee)
    {
        $this->authorize('employee.view');
        $employee->load(['department', 'designation', 'team', 'manager', 'user']);
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $this->authorize('employee.edit');
        $departments = Department::all();
        $designations = Designation::all();
        $teams = Team::all();
        $managers = User::role(['Manager', 'Super Admin'])->get();
        return view('employees.edit', compact('employee', 'departments', 'designations', 'teams', 'managers'));
    }

    public function update(Request $request, Employee $employee)
    {
        $this->authorize('employee.edit');

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'required|in:male,female,other',
            'department_id' => 'required|exists:departments,id',
            'designation_id' => 'nullable|exists:designations,id',
            'team_id' => 'nullable|exists:teams,id',
            'manager_id' => 'nullable|exists:users,id',
            'joining_date' => 'required|date',
            'employment_type' => 'required|in:full_time,part_time,contract,intern',
            'salary' => 'nullable|numeric|min:0',
            'address' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive,terminated',
        ]);

        $employee->update($validated);

        // Update user name
        if ($employee->user) {
            $employee->user->update([
                'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            ]);
        }

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        $this->authorize('employee.delete');
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }
}
