<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use App\Models\Department;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    public function index()
    {
        $designations = Designation::with('department')->orderBy('name')->paginate(20);
        return view('designations.index', compact('designations'));
    }

    public function create()
    {
        $this->authorize('department.manage');
        $departments = Department::orderBy('name')->get();
        return view('designations.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $this->authorize('department.manage');
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'description'   => 'nullable|string|max:500',
        ]);
        Designation::create($validated);
        return redirect()->route('designations.index')->with('success', 'Designation created successfully.');
    }

    public function edit(Designation $designation)
    {
        $this->authorize('department.manage');
        $departments = Department::orderBy('name')->get();
        return view('designations.edit', compact('designation', 'departments'));
    }

    public function update(Request $request, Designation $designation)
    {
        $this->authorize('department.manage');
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'description'   => 'nullable|string|max:500',
        ]);
        $designation->update($validated);
        return redirect()->route('designations.index')->with('success', 'Designation updated successfully.');
    }

    public function destroy(Designation $designation)
    {
        $this->authorize('department.manage');
        if ($designation->employees()->count() > 0) {
            return back()->with('error', 'Cannot delete designation with assigned employees.');
        }
        $designation->delete();
        return redirect()->route('designations.index')->with('success', 'Designation deleted.');
    }
}
