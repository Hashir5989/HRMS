@extends('layouts.admin')

@section('title', 'Tasks - HRMS Pro')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Tasks</h3>
            <p class="text-muted mb-0">Manage and track all tasks across projects</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-2">
            <form action="{{ route('tasks.index') }}" method="GET" class="d-flex align-items-center gap-3 flex-wrap">
                <select class="form-select form-select-sm" name="status" style="max-width:160px;">
                    <option value="">All Statuses</option>
                    @foreach(['Backlog','QA Ready','QA','Rework','Ready to Live','Live','Completed'] as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
                <select class="form-select form-select-sm" name="priority" style="max-width:140px;">
                    <option value="">All Priorities</option>
                    @foreach(['low','medium','high','urgent'] as $p)
                    <option value="{{ $p }}" {{ request('priority') == $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>
                    @endforeach
                </select>
                <button class="btn btn-sm btn-primary">Filter</button>
                <a href="{{ route('tasks.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            </form>
        </div>
    </div>

    <!-- Task Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">ID</th>
                            <th>Task</th>
                            <th>Project</th>
                            <th>Assignee</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th class="pe-3">Updated</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tasks as $task)
                        <tr>
                            <td class="ps-3"><code class="small">{{ $task->task_id }}</code></td>
                            <td>
                                <a href="{{ route('tasks.show', $task) }}" class="fw-semibold text-dark text-decoration-none small">{{ $task->title }}</a>
                            </td>
                            <td>
                                @if($task->project)
                                <a href="{{ route('projects.show', $task->project) }}" class="text-decoration-none small">{{ $task->project->name }}</a>
                                @else
                                <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td>
                                @if($task->assignee)
                                <div class="d-flex align-items-center gap-2">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($task->assignee->name) }}&size=24&background=random" class="rounded-circle" width="24" height="24">
                                    <span class="small">{{ $task->assignee->name }}</span>
                                </div>
                                @else
                                <span class="text-muted small">Unassigned</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $pColors = ['low' => 'info', 'medium' => 'primary', 'high' => 'warning', 'urgent' => 'danger'];
                                @endphp
                                <span class="badge bg-{{ $pColors[$task->priority] ?? 'secondary' }}-subtle text-{{ $pColors[$task->priority] ?? 'secondary' }}">{{ ucfirst($task->priority) }}</span>
                            </td>
                            <td>
                                @php
                                    $sColors = ['Backlog' => 'secondary', 'QA Ready' => 'info', 'QA' => 'primary', 'Rework' => 'warning', 'Ready to Live' => 'success', 'Live' => 'success', 'Completed' => 'dark'];
                                @endphp
                                <span class="badge bg-{{ $sColors[$task->status] ?? 'secondary' }}-subtle text-{{ $sColors[$task->status] ?? 'secondary' }}">{{ $task->status }}</span>
                            </td>
                            <td class="pe-3 small text-muted">{{ $task->updated_at->diffForHumans() }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="bi bi-check2-square fs-1 d-block mb-2"></i>
                                No tasks found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($tasks->hasPages())
        <div class="card-footer bg-white border-0">{{ $tasks->links() }}</div>
        @endif
    </div>
</div>
@endsection
