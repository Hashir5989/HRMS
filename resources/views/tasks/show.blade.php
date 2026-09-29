@extends('layouts.admin')

@section('title', $task->title . ' - HRMS Pro')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('tasks.index') }}" class="text-decoration-none text-muted">
            <i class="bi bi-arrow-left me-1"></i>Back to Tasks
        </a>
        @can('task.edit')
        <button class="btn btn-primary premium-btn rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#editTaskModal">
            <i class="bi bi-pencil me-1"></i> Edit Task
        </button>
        @endcan
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

                    <!-- Add Comment Form -->
                    <form action="{{ route('tasks.comments.store', $task) }}" method="POST" class="mb-4">
                        @csrf
                        <div class="mb-2">
                            <textarea class="form-control" name="comment" rows="2" placeholder="Write a comment..." required></textarea>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3">Post Comment</button>
                        </div>
                    </form>

                    <!-- Comments -->
                    <h6 class="fw-bold mb-3"><i class="bi bi-chat-left-text me-2"></i>Comments ({{ $task->comments->count() }})</h6>
                    @forelse($task->comments as $comment)
                    <div class="d-flex gap-3 mb-3">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($comment->user->name ?? 'U') }}&size=32&background=random" class="rounded-circle" width="32" height="32">
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between">
                                <span class="fw-semibold small">{{ $comment->user->name ?? 'Unknown' }}</span>
                                <div class="d-flex align-items-center gap-2">
                                    <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                    @if($comment->user_id === auth()->id() || auth()->user()->hasRole('Super Admin'))
                                    <div class="dropdown">
                                        <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical"></i></button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" style="min-width:120px;">
                                            <li><a class="dropdown-item small" href="#" onclick="editComment({{ $comment->id }}, '{{ addslashes($comment->comment) }}')"><i class="bi bi-pencil me-2"></i>Edit</a></li>
                                            <li>
                                                <form action="{{ route('comments.destroy', $comment) }}" method="POST" onsubmit="return confirm('Delete this comment?')">
                                                    @csrf @method('DELETE')
                                                    <button class="dropdown-item text-danger small"><i class="bi bi-trash me-2"></i>Delete</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <p class="mb-0 small" id="comment-text-{{ $comment->id }}">{{ $comment->comment }}</p>
                            
                            <form action="{{ route('comments.update', $comment) }}" method="POST" class="d-none mt-2" id="edit-form-{{ $comment->id }}">
                                @csrf @method('PUT')
                                <textarea class="form-control form-control-sm mb-2" name="comment" rows="2" required></textarea>
                                <div class="d-flex gap-1">
                                    <button type="submit" class="btn btn-primary btn-sm px-3">Save</button>
                                    <button type="button" class="btn btn-light btn-sm px-3" onclick="cancelEdit({{ $comment->id }})">Cancel</button>
                                </div>
                            </form>
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

<!-- Edit Task Modal -->
<div class="modal fade" id="editTaskModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0 bg-light pb-0">
                <h5 class="modal-title fw-bold">Edit Task</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('tasks.update', $task) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Task Title</label>
                        <input type="text" class="form-control" name="title" value="{{ $task->title }}" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Status</label>
                            <select class="form-select" name="status" required>
                                @foreach(['Backlog', 'QA Ready', 'QA', 'Rework', 'Ready to Live', 'Live', 'Completed'] as $st)
                                <option value="{{ $st }}" {{ $task->status === $st ? 'selected' : '' }}>{{ $st }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Priority</label>
                            <select class="form-select" name="priority" required>
                                @foreach(['low', 'medium', 'high', 'urgent'] as $pr)
                                <option value="{{ $pr }}" {{ $task->priority === $pr ? 'selected' : '' }}>{{ ucfirst($pr) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Assignee</label>
                            <select class="form-select" name="assigned_user_id">
                                <option value="">Unassigned</option>
                                @foreach($users as $usr)
                                <option value="{{ $usr->id }}" {{ $task->assigned_user_id == $usr->id ? 'selected' : '' }}>{{ $usr->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea class="form-control" name="description" rows="5">{{ $task->description }}</textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light pt-3 pb-4 px-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary premium-btn rounded-pill px-4">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function editComment(id, text) {
        document.getElementById('comment-text-' + id).classList.add('d-none');
        const form = document.getElementById('edit-form-' + id);
        form.classList.remove('d-none');
        form.querySelector('textarea').value = text;
    }
    function cancelEdit(id) {
        document.getElementById('comment-text-' + id).classList.remove('d-none');
        document.getElementById('edit-form-' + id).classList.add('d-none');
    }
</script>
@endpush

@endsection
