@extends('layouts.app')

@section('title', 'Project Details - ' . $project->title)
@section('page_title', 'Project Workspace')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <a href="{{ route('projects.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to Directory
    </a>
    <div class="d-flex gap-2">
        <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-primary rounded-pill fw-semibold shadow-sm px-3 btn-sm">
            <i class="fa-solid fa-pen me-1"></i> Edit Project
        </a>
        <button type="button" class="btn btn-danger rounded-pill fw-semibold shadow-sm px-3 btn-sm"
            data-bs-toggle="modal" 
            data-bs-target="#deleteModal" 
            data-url="{{ route('projects.destroy', $project->id) }}" 
            data-name="{{ $project->title }}">
            <i class="fa-solid fa-trash-can me-1"></i> Delete Project
        </button>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Project Stats & Clients -->
    <div class="col-12 col-lg-4">
        <!-- Overview Stats Card -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                    <h5 class="fw-bold text-dark mb-0">Project Metadata</h5>
                    @if($project->status === 'completed')
                        <span class="badge bg-success-subtle text-success border border-success border-opacity-10 px-2.5 py-1.5 rounded-pill">Completed</span>
                    @elseif($project->status === 'in_progress')
                        <span class="badge bg-primary-subtle text-primary border border-primary border-opacity-10 px-2.5 py-1.5 rounded-pill">In Progress</span>
                    @elseif($project->status === 'pending')
                        <span class="badge bg-warning-subtle text-warning border border-warning border-opacity-10 px-2.5 py-1.5 rounded-pill">Pending</span>
                    @elseif($project->status === 'on_hold')
                        <span class="badge bg-secondary-subtle text-secondary border border-secondary border-opacity-10 px-2.5 py-1.5 rounded-pill">On Hold</span>
                    @else
                        <span class="badge bg-danger-subtle text-danger border border-danger border-opacity-10 px-2.5 py-1.5 rounded-pill">Cancelled</span>
                    @endif
                </div>

                <div class="mb-4 text-center py-3 bg-light rounded-4 border">
                    <span class="text-muted small fw-semibold d-block mb-1">COMPLETION PROGRESS</span>
                    <h2 class="fw-bold text-success mb-2">{{ $project->progress }}%</h2>
                    <div class="progress mx-auto" style="height: 8px; width: 80%; border-radius: 4px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $project->progress }}%"></div>
                    </div>
                </div>

                <ul class="list-unstyled mb-0">
                    <li class="mb-3 d-flex justify-content-between">
                        <span class="text-muted fw-semibold">Budget (USD):</span>
                        <span class="text-dark fw-bold">${{ number_format($project->budget, 2) }}</span>
                    </li>
                    <li class="mb-3 d-flex justify-content-between">
                        <span class="text-muted fw-semibold">Start Date:</span>
                        <span class="text-dark fw-bold"><i class="fa-solid fa-calendar me-1.5 text-muted"></i>{{ $project->start_date ? $project->start_date->format('M d, Y') : 'N/A' }}</span>
                    </li>
                    <li class="mb-3 d-flex justify-content-between align-items-center">
                        <span class="text-muted fw-semibold">Deadline:</span>
                        <div class="text-end">
                            <span class="text-dark fw-bold"><i class="fa-solid fa-calendar-check me-1.5 text-muted"></i>{{ $project->deadline ? $project->deadline->format('M d, Y') : 'N/A' }}</span>
                            @if($project->isOverdue())
                                <div class="badge bg-danger-subtle text-danger border border-danger border-opacity-10 overdue-pulse px-2 py-0.5 rounded-pill mt-1" style="font-size: 0.7rem;">
                                    <i class="fa-solid fa-triangle-exclamation me-0.5"></i>Overdue
                                </div>
                            @endif
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Associated Company & Client Card -->
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
            <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-building text-primary me-2"></i>Client Profile</h5>
            <div class="pb-3 border-bottom mb-3">
                <span class="text-muted small fw-semibold d-block mb-1">COMPANY</span>
                <a href="{{ route('companies.show', $project->company->id) }}" class="fw-bold text-dark text-decoration-none hover-primary">
                    <i class="fa-solid fa-building me-1.5 text-muted"></i>{{ $project->company->name }}
                </a>
                <span class="d-block small text-muted mt-1">{{ $project->company->city ?? 'No Location' }} | {{ $project->company->industry ?? 'Other Industry' }}</span>
            </div>
            
            @if($project->client)
                <div>
                    <span class="text-muted small fw-semibold d-block mb-1">PRIMARY CONTACT</span>
                    <div class="fw-bold text-dark"><i class="fa-solid fa-user me-1.5 text-muted"></i>{{ $project->client->name }}</div>
                    <span class="d-block small text-muted mt-0.5">{{ $project->client->designation ?? 'Representative' }}</span>
                    <div class="small fw-semibold mt-2"><i class="fa-regular fa-envelope me-1.5 text-muted"></i>{{ $project->client->email ?? 'N/A' }}</div>
                    <div class="small text-muted"><i class="fa-solid fa-phone me-1.5 text-muted"></i>{{ $project->client->phone ?? 'N/A' }}</div>
                </div>
            @else
                <div class="text-muted small py-2"><i class="fa-solid fa-circle-info me-1"></i>No contact person assigned.</div>
            @endif
        </div>
    </div>

    <!-- Right Column: Project Tasks & Description -->
    <div class="col-12 col-lg-8">
        <!-- Project Details / Scope Card -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 p-4">
            <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-file-lines text-indigo me-2"></i>Project Scope & Description</h5>
            <div class="bg-light p-3 rounded-3 border">
                <p class="mb-0 text-dark" style="white-space: pre-line; line-height: 1.6;">{{ $project->description ?? 'No project description or scope outlined yet.' }}</p>
            </div>
        </div>

        <!-- Project Tasks Card -->
        <div class="card card-table border-0">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h5 class="m-0 fw-bold"><i class="fa-solid fa-list-check text-success me-2"></i>Project Tasks Checklist</h5>
                <a href="{{ route('tasks.create', ['project_id' => $project->id]) }}" class="btn btn-sm btn-outline-success rounded-pill fw-semibold">
                    <i class="fa-solid fa-plus me-1"></i> Add Task
                </a>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Task Title</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Assigned To</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($project->tasks as $task)
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark">{{ $task->title }}</div>
                                    <span class="text-muted small">Due: {{ $task->due_date ? $task->due_date->format('M d, Y') : 'No Due Date' }}</span>
                                </td>
                                <td>
                                    @if($task->priority === 'high')
                                        <span class="badge bg-danger-subtle text-danger border border-danger border-opacity-10 px-2 py-1 rounded-pill">High</span>
                                    @elseif($task->priority === 'medium')
                                        <span class="badge bg-warning-subtle text-warning border border-warning border-opacity-10 px-2 py-1 rounded-pill">Medium</span>
                                    @else
                                        <span class="badge bg-info-subtle text-info border border-info border-opacity-10 px-2 py-1 rounded-pill">Low</span>
                                    @endif
                                </td>
                                <td>
                                    @if($task->status === 'completed')
                                        <span class="badge bg-success-subtle text-success px-2 py-1 rounded-pill">Completed</span>
                                    @elseif($task->status === 'in_progress')
                                        <span class="badge bg-primary-subtle text-primary px-2 py-1 rounded-pill">In Progress</span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning px-2 py-1 rounded-pill">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="small fw-semibold">{{ $task->assignedStaff ? $task->assignedStaff->name : 'Unassigned' }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">No tasks currently defined for this project.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
