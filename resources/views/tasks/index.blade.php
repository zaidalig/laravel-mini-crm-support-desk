@extends('layouts.app')

@section('title', 'Tasks')
@section('page_title', 'Task Board Checklist')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">Track internal assignments, project tasks, or support ticket-related tasks. Assign staff and monitor deadlines.</p>
    <a href="{{ route('tasks.create') }}" class="btn btn-primary rounded-pill fw-semibold shadow-sm">
        <i class="fa-solid fa-plus me-1"></i> Add Task
    </a>
</div>

<!-- Search & Filter Card -->
<div class="card filter-card border-0 mb-4">
    <div class="card-body p-3">
        <form action="{{ route('tasks.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-12 col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Search tasks by title..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-12 col-md-3">
                <select name="priority" class="form-select">
                    <option value="">All Priorities</option>
                    <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low Priority</option>
                    <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium Priority</option>
                    <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High Priority</option>
                </select>
            </div>
            <div class="col-12 col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
            <div class="col-12 col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-dark w-100 fw-semibold rounded-3">Filter</button>
                @if(request()->anyFilled(['search', 'priority', 'status']))
                    <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary w-100 fw-semibold rounded-3">Clear</a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Tasks List Card -->
<div class="card card-table border-0">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Task Title</th>
                    <th>Associated Entity</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Assigned To</th>
                    <th>Due Date</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tasks as $task)
                    <tr>
                        <td>
                            <div class="fw-bold text-dark">{{ $task->title }}</div>
                        </td>
                        <td>
                            @if($task->project)
                                <a href="{{ route('projects.show', $task->project->id) }}" class="badge bg-success-subtle text-success border border-success border-opacity-10 text-decoration-none px-2.5 py-1.5 rounded-3">
                                    <i class="fa-solid fa-briefcase me-1"></i>Project: {{ Str::limit($task->project->title, 20) }}
                                </a>
                            @elseif($task->ticket)
                                <a href="{{ route('tickets.show', $task->ticket->id) }}" class="badge bg-warning-subtle text-warning border border-warning border-opacity-10 text-decoration-none px-2.5 py-1.5 rounded-3">
                                    <i class="fa-solid fa-ticket-simple me-1"></i>Ticket #{{ $task->ticket->id }}
                                </a>
                            @else
                                <span class="badge bg-light text-dark border px-2.5 py-1.5 rounded-3">General Task</span>
                            @endif
                        </td>
                        <td>
                            @if($task->priority === 'high')
                                <span class="badge bg-danger-subtle text-danger border border-danger border-opacity-10 px-2.5 py-1.5 rounded-pill">High</span>
                            @elseif($task->priority === 'medium')
                                <span class="badge bg-warning-subtle text-warning border border-warning border-opacity-10 px-2.5 py-1.5 rounded-pill">Medium</span>
                            @else
                                <span class="badge bg-info-subtle text-info border border-info border-opacity-10 px-2.5 py-1.5 rounded-pill">Low</span>
                            @endif
                        </td>
                        <td>
                            @if($task->status === 'completed')
                                <span class="badge bg-success-subtle text-success border border-success border-opacity-10 px-2.5 py-1.5 rounded-pill">Completed</span>
                            @elseif($task->status === 'in_progress')
                                <span class="badge bg-primary-subtle text-primary border border-primary border-opacity-10 px-2.5 py-1.5 rounded-pill">In Progress</span>
                            @else
                                <span class="badge bg-warning-subtle text-warning border border-warning border-opacity-10 px-2.5 py-1.5 rounded-pill">Pending</span>
                            @endif
                        </td>
                        <td>
                            <span class="small fw-semibold text-muted"><i class="fa-solid fa-user-tie me-1.5 text-muted"></i>{{ $task->assignedStaff ? $task->assignedStaff->name : 'Unassigned' }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-1.5 flex-wrap">
                                <span class="small fw-semibold text-muted">{{ $task->due_date ? $task->due_date->format('M d, Y') : 'No Due Date' }}</span>
                                @if($task->isOverdue())
                                    <span class="badge bg-danger-subtle text-danger border border-danger border-opacity-10 overdue-pulse px-2 py-0.5 rounded-pill" style="font-size: 0.7rem;">
                                        <i class="fa-solid fa-triangle-exclamation me-0.5"></i>Overdue
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-1">
                                <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-sm btn-outline-primary rounded-pill" title="Edit Task">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal" 
                                    data-url="{{ route('tasks.destroy', $task->id) }}" 
                                    data-name="{{ $task->title }}"
                                    title="Delete Task">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-list-check fs-1 mb-2 text-light"></i>
                            <p class="mb-0">No tasks found matching filters.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($tasks->hasPages())
        <div class="card-footer bg-white border-top py-3">
            {{ $tasks->links() }}
        </div>
    @endif
</div>
@endsection
