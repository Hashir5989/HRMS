<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('task.view');

        $query = Task::with(['project', 'assignee']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $tasks = $query->orderBy('updated_at', 'desc')->paginate(20);
        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        $this->authorize('task.create');
        $projects = Project::all();
        $users = User::all();
        return view('tasks.create', compact('projects', 'users'));
    }

    public function show(Task $task)
    {
        $this->authorize('task.view');
        $task->load(['project', 'assignee', 'creator', 'comments.user', 'attachments']);
        $users = User::all();
        return view('tasks.show', compact('task', 'users'));
    }

    public function store(Request $request)
    {
        $this->authorize('task.create');
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'project_id' => 'required|exists:projects,id',
            'description' => 'nullable|string',
            'assigned_user_id' => 'nullable|exists:users,id',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:Backlog,QA Ready,QA,Rework,Ready to Live,Live,Completed',
        ]);

        // Generate unique task ID based on project code
        $project = Project::findOrFail($validated['project_id']);
        $taskCount = $project->tasks()->withTrashed()->count() + 1;
        $validated['task_id'] = $project->project_code . '-' . str_pad($taskCount, 3, '0', STR_PAD_LEFT);
        
        $validated['created_by'] = auth()->id();

        Task::create($validated);

        return back()->with('success', 'Task created successfully.');
    }

    public function updateStatus(Request $request, Task $task)
    {
        $this->authorize('task.edit');
        
        $validated = $request->validate([
            'status' => 'required|in:Backlog,QA Ready,QA,Rework,Ready to Live,Live,Completed'
        ]);

        $task->update(['status' => $validated['status']]);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Task status updated successfully', 'task' => $task]);
        }

        return back()->with('success', 'Task status updated.');
    }
    public function update(Request $request, Task $task)
    {
        $this->authorize('task.edit');

        $validated = $request->validate([
            'status' => 'required|in:Backlog,QA Ready,QA,Rework,Ready to Live,Live,Completed',
            'assigned_user_id' => 'nullable|exists:users,id',
            'priority' => 'required|in:low,medium,high,urgent',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $task->update($validated);

        return back()->with('success', 'Task updated successfully.');
    }
    

    public function destroy(Task $task)
    {
        $this->authorize('task.delete');
        $task->delete();
        
        if (request()->wantsJson()) {
            return response()->json(['message' => 'Task deleted successfully']);
        }
        
        return back()->with('success', 'Task deleted successfully.');
    }
}
