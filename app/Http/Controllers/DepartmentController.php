<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::with('manager')->withCount('employees')->orderBy('name')->paginate(15);

        return view('departments.index', compact('departments'));
    }

    public function create()
    {
        $managers = User::role(['Manager', 'Admin', 'Super Admin'])->get();

        return view('departments.create', compact('managers'));
    }

    public function store(Request $request)
    {
        $this->authorize('department.manage');

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments',
            'description' => 'nullable|string|max:1000',
            'manager_id' => 'nullable|exists:users,id',
        ]);

        Department::create($validated);

        return redirect()->route('departments.index')->with('success', 'Department created successfully.');
    }

    public function show(Department $department)
    {
        $department->load(['employees.user', 'manager']);

        return view('departments.show', compact('department'));
    }

    public function edit(Department $department)
    {
        $managers = User::role(['Manager', 'Admin', 'Super Admin'])->get();

        return view('departments.edit', compact('department', 'managers'));
    }

    public function update(Request $request, Department $department)
    {
        $this->authorize('department.manage');

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,'.$department->id,
            'description' => 'nullable|string|max:1000',
            'manager_id' => 'nullable|exists:users,id',
        ]);

        $department->update($validated);

        return redirect()->route('departments.index')->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        $this->authorize('department.manage');

        if ($department->employees()->count() > 0) {
            return back()->with('error', 'Cannot delete department with existing employees.');
        }

        $department->delete();

        return redirect()->route('departments.index')->with('success', 'Department deleted successfully.');
    }
}
