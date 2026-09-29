@extends('layouts.admin')

@section('title', 'Kanban Board - ' . $project->name)

@section('content')
<div class="container-fluid h-100 d-flex flex-column">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="d-flex align-items-center gap-3 mb-1">
                <h3 class="fw-bold text-dark mb-0">{{ $project->name }}</h3>
                <span class="badge bg-primary rounded-pill">{{ $project->project_code }}</span>
            </div>
            <p class="text-muted mb-0">Project Kanban Board</p>
        </div>
        <div class="d-flex gap-2">
            @can('task.create')
            <button type="button" class="btn btn-primary premium-btn rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#createTaskModal">
                <i class="bi bi-plus-lg me-1"></i> Add Task
            </button>
            @endcan
        </div>
    </div>

    <!-- Kanban Board Container -->
    <div class="kanban-container d-flex flex-nowrap gap-4 pb-4 overflow-auto flex-grow-1" style="min-height: calc(100vh - 250px);">
        
        @foreach(['Backlog', 'QA Ready', 'QA', 'Rework', 'Ready to Live', 'Live', 'Completed'] as $status)
        <!-- Kanban Column -->
        <div class="kanban-column bg-light rounded-4 p-3 d-flex flex-column" style="min-width: 320px; width: 320px;">
            <div class="d-flex justify-content-between align-items-center mb-3 px-1">
                <h6 class="fw-bold mb-0 text-dark">{{ $status }}</h6>
                <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-2 py-1 count-badge" id="count-{{ Str::slug($status) }}">
                    {{ isset($tasksByStatus[$status]) ? $tasksByStatus[$status]->count() : 0 }}
                </span>
            </div>
            
            <div class="kanban-items flex-grow-1 overflow-auto rounded-3" 
                 data-status="{{ $status }}" 
                 id="column-{{ Str::slug($status) }}"
                 style="min-height: 150px;">
                 
                @if(isset($tasksByStatus[$status]))
                    @foreach($tasksByStatus[$status] as $task)
                    <div class="kanban-item card border-0 shadow-sm rounded-3 mb-3 cursor-grab" 
                         data-task-id="{{ $task->id }}"
                         draggable="true">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge bg-light text-secondary border fs-7">{{ $task->task_id }}</span>
                                @php
                                    $priorityColors = [
                                        'low' => 'info',
                                        'medium' => 'primary',
                                        'high' => 'warning',
                                        'urgent' => 'danger'
                                    ];
                                    $pColor = $priorityColors[$task->priority] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $pColor }} bg-opacity-10 text-{{ $pColor }} rounded-pill" title="Priority: {{ ucfirst($task->priority) }}">
                                    <i class="bi bi-flag-fill"></i>
                                </span>
                            </div>
                            
                            <a href="{{ route('tasks.show', $task) }}" class="text-decoration-none">
                                <h6 class="fw-bold text-dark mb-2 task-title">{{ $task->title }}</h6>
                            </a>
                            
                            <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                                <div class="d-flex align-items-center gap-2 text-muted fs-7">
                                    <i class="bi bi-chat-square-text"></i> {{ $task->comments_count ?? 0 }}
                                    <i class="bi bi-paperclip ms-2"></i> {{ $task->attachments_count ?? 0 }}
                                </div>
                                @if($task->assignee)
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($task->assignee->name) }}&background=random" 
                                         class="rounded-circle shadow-sm" width="28" height="28" title="{{ $task->assignee->name }}">
                                @else
                                    <div class="bg-secondary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center text-muted" style="width: 28px; height: 28px;" title="Unassigned">
                                        <i class="bi bi-person"></i>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Add Task Modal -->
<div class="modal fade" id="createTaskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0 bg-light pb-0">
                <h5 class="modal-title fw-bold">Create New Task</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('tasks.store') }}" method="POST">
                @csrf
                <input type="hidden" name="project_id" value="{{ $project->id }}">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Task Title</label>
                        <input type="text" class="form-control premium-input" name="title" required placeholder="E.g., Implement login API">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select class="form-select premium-input" name="status" required>
                            <option value="Backlog">Backlog</option>
                            <option value="QA Ready">QA Ready</option>
                            <option value="QA">QA</option>
                            <option value="Rework">Rework</option>
                            <option value="Ready to Live">Ready to Live</option>
                            <option value="Live">Live</option>
                            <option value="Completed">Completed</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Priority</label>
                        <select class="form-select premium-input" name="priority" required>
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light pt-3 pb-4 px-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary premium-btn rounded-pill px-4">Create Task</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .kanban-container {
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 transparent;
    }
    .kanban-container::-webkit-scrollbar {
        height: 8px;
    }
    .kanban-container::-webkit-scrollbar-track {
        background: transparent;
    }
    .kanban-container::-webkit-scrollbar-thumb {
        background-color: #cbd5e1;
        border-radius: 20px;
    }
    .kanban-item {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .kanban-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
    }
    .kanban-item.dragging {
        opacity: 0.5;
        transform: scale(0.95);
    }
    .cursor-grab {
        cursor: grab;
    }
    .cursor-grab:active {
        cursor: grabbing;
    }
    .drag-over {
        background-color: rgba(79, 70, 229, 0.05);
        border: 2px dashed #4f46e5;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const draggables = document.querySelectorAll('.kanban-item');
        const containers = document.querySelectorAll('.kanban-items');

        draggables.forEach(draggable => {
            draggable.addEventListener('dragstart', () => {
                draggable.classList.add('dragging');
            });

            draggable.addEventListener('dragend', () => {
                draggable.classList.remove('dragging');
                
                // Update status via AJAX
                const taskId = draggable.dataset.taskId;
                const newStatus = draggable.closest('.kanban-items').dataset.status;
                
                fetch(`/tasks/${taskId}/status`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ status: newStatus })
                }).then(res => res.json())
                  .then(data => {
                      // Update counts
                      updateCounts();
                  }).catch(err => console.error(err));
            });
        });

        containers.forEach(container => {
            container.addEventListener('dragover', e => {
                e.preventDefault();
                container.classList.add('drag-over');
                const afterElement = getDragAfterElement(container, e.clientY);
                const draggable = document.querySelector('.dragging');
                if (afterElement == null) {
                    container.appendChild(draggable);
                } else {
                    container.insertBefore(draggable, afterElement);
                }
            });
            
            container.addEventListener('dragleave', e => {
                container.classList.remove('drag-over');
            });
            
            container.addEventListener('drop', e => {
                container.classList.remove('drag-over');
            });
        });

        function getDragAfterElement(container, y) {
            const draggableElements = [...container.querySelectorAll('.kanban-item:not(.dragging)')];

            return draggableElements.reduce((closest, child) => {
                const box = child.getBoundingClientRect();
                const offset = y - box.top - box.height / 2;
                if (offset < 0 && offset > closest.offset) {
                    return { offset: offset, element: child };
                } else {
                    return closest;
                }
            }, { offset: Number.NEGATIVE_INFINITY }).element;
        }
        
        function updateCounts() {
            containers.forEach(container => {
                const count = container.querySelectorAll('.kanban-item').length;
                const statusSlug = container.id.replace('column-', '');
                document.getElementById('count-' + statusSlug).textContent = count;
            });
        }
    });
</script>
@endpush
