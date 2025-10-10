<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::with(['department', 'leader'])->withCount('members')->paginate(15);
        return view('teams.index', compact('teams'));
    }

    public function create()
    {
        $departments = Department::all();
        $leaders = User::role(['Manager', 'Super Admin'])->get();
        return view('teams.create', compact('departments', 'leaders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:teams',
            'department_id' => 'required|exists:departments,id',
            'team_lead_id' => 'nullable|exists:users,id',
            'description' => 'nullable|string|max:1000',
        ]);

        Team::create($validated);
        return redirect()->route('teams.index')->with('success', 'Team created successfully.');
    }

    public function show(Team $team)
    {
        $team->load(['department', 'leader', 'members.user']);
        return view('teams.show', compact('team'));
    }

    public function edit(Team $team)
    {
        $departments = Department::all();
        $leaders = User::role(['Manager', 'Super Admin'])->get();
        return view('teams.edit', compact('team', 'departments', 'leaders'));
    }

    public function update(Request $request, Team $team)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:teams,name,' . $team->id,
            'department_id' => 'required|exists:departments,id',
            'team_lead_id' => 'nullable|exists:users,id',
            'description' => 'nullable|string|max:1000',
        ]);

        $team->update($validated);
        return redirect()->route('teams.index')->with('success', 'Team updated successfully.');
    }

    public function destroy(Team $team)
    {
        $team->delete();
        return redirect()->route('teams.index')->with('success', 'Team deleted successfully.');
    }
}
