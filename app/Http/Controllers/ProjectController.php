<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $this->authorize('project.view');
        $projects = Project::with(['projectManager', 'team'])
            ->withCount(['tasks', 'tasks as completed_tasks_count' => function ($query) {
                $query->where('status', 'Completed');
            }])
            ->paginate(10);

        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        $this->authorize('project.create');
        $managers = User::role(['Manager', 'Super Admin'])->get();
        $teams = Team::all();

        return view('projects.create', compact('managers', 'teams'));
    }

    public function store(Request $request)
    {
        $this->authorize('project.create');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'project_code' => 'required|string|unique:projects,project_code',
            'description' => 'nullable|string',
            'client' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'project_manager_id' => 'nullable|exists:users,id',
            'team_id' => 'nullable|exists:teams,id',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:planning,active,on_hold,completed,cancelled',
        ]);

        Project::create($validated);

        return redirect()->route('projects.index')->with('success', 'Project created successfully.');
    }

    public function show(Project $project)
    {
        $this->authorize('project.view');

        $project->load(['projectManager', 'team', 'tasks.assignee']);

        // Group tasks by status for Kanban Board
        $tasksByStatus = [
            'Backlog' => $project->tasks->where('status', 'Backlog'),
            'QA Ready' => $project->tasks->where('status', 'QA Ready'),
            'QA' => $project->tasks->where('status', 'QA'),
            'Rework' => $project->tasks->where('status', 'Rework'),
            'Ready to Live' => $project->tasks->where('status', 'Ready to Live'),
            'Live' => $project->tasks->where('status', 'Live'),
            'Completed' => $project->tasks->where('status', 'Completed'),
        ];

        return view('projects.kanban', compact('project', 'tasksByStatus'));
    }

    public function edit(Project $project)
    {
        $this->authorize('project.edit');
        $managers = User::role(['Manager', 'Super Admin'])->get();
        $teams = Team::all();

        return view('projects.edit', compact('project', 'managers', 'teams'));
    }

    public function update(Request $request, Project $project)
    {
        $this->authorize('project.edit');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'project_code' => 'required|string|unique:projects,project_code,'.$project->id,
            'description' => 'nullable|string',
            'client' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'project_manager_id' => 'nullable|exists:users,id',
            'team_id' => 'nullable|exists:teams,id',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:planning,active,on_hold,completed,cancelled',
            'progress' => 'integer|min:0|max:100',
        ]);

        $project->update($validated);

        return redirect()->route('projects.index')->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        $this->authorize('project.delete');
        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
    }
}
