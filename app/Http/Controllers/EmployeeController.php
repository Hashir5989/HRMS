<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;

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
        // Validation logic here
        
        // Employee creation logic here
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
        // Validation logic here
        
        // Employee update logic here
        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        $this->authorize('employee.delete');
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }
}
