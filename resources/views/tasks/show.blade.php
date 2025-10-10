@extends('layouts.admin')

@section('title', $task->title . ' - HRMS Pro')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('tasks.index') }}" class="text-decoration-none text-muted">
            <i class="bi bi-arrow-left me-1"></i>Back to Tasks
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <code class="small text-muted">{{ $task->task_id }}</code>
                            <h4 class="fw-bold mt-1 mb-0">{{ $task->title }}</h4>
                        </div>
                        @php
                            $pColors = ['low' => 'info', 'medium' => 'primary', 'high' => 'warning', 'urgent' => 'danger'];
                        @endphp
                        <span class="badge bg-{{ $pColors[$task->priority] ?? 'secondary' }} fs-6">{{ ucfirst($task->priority) }}</span>
                    </div>

                    <div class="bg-light rounded-3 p-3 mb-4">
                        {!! nl2br(e($task->description ?? 'No description provided.')) !!}
                    </div>

                    <!-- Comments -->
                    <h6 class="fw-bold mb-3"><i class="bi bi-chat-left-text me-2"></i>Comments ({{ $task->comments->count() }})</h6>
                    @forelse($task->comments as $comment)
                    <div class="d-flex gap-3 mb-3">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($comment->user->name ?? 'U') }}&size=32&background=random" class="rounded-circle" width="32" height="32">
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between">
                                <span class="fw-semibold small">{{ $comment->user->name ?? 'Unknown' }}</span>
                                <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-0 small">{{ $comment->comment }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted small">No comments yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="fw-bold mb-0">Details</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">Status</small>
                        @php
                            $sColors = ['Backlog' => 'secondary', 'QA Ready' => 'info', 'QA' => 'primary', 'Rework' => 'warning', 'Ready to Live' => 'success', 'Live' => 'success', 'Completed' => 'dark'];
                        @endphp
                        <span class="badge bg-{{ $sColors[$task->status] ?? 'secondary' }} fs-6">{{ $task->status }}</span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Project</small>
                        @if($task->project)
                        <a href="{{ route('projects.show', $task->project) }}" class="text-decoration-none fw-semibold">{{ $task->project->name }}</a>
                        @else
                        <span class="text-muted">—</span>
                        @endif
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Assigned To</small>
                        @if($task->assignee)
                        <div class="d-flex align-items-center gap-2 mt-1">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($task->assignee->name) }}&size=28&background=random" class="rounded-circle" width="28" height="28">
                            <span class="fw-semibold small">{{ $task->assignee->name }}</span>
                        </div>
                        @else
                        <span class="text-muted">Unassigned</span>
                        @endif
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Created By</small>
                        <span class="fw-semibold small">{{ $task->creator->name ?? 'Unknown' }}</span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Due Date</small>
                        <span class="fw-semibold small">{{ $task->due_date ? $task->due_date->format('M d, Y') : '—' }}</span>
                    </div>
                    <div>
                        <small class="text-muted d-block">Last Updated</small>
                        <span class="small">{{ $task->updated_at->format('M d, Y h:i A') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
