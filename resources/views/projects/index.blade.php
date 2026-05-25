@extends('layouts.app')

@section('title', 'Projects')
@section('page_title', 'Project Portfolio')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">Track customer projects, project budgets, deadlines, progress completion percentages, and status workflows.</p>
    <a href="{{ route('projects.create') }}" class="btn btn-primary rounded-pill fw-semibold shadow-sm">
        <i class="fa-solid fa-plus me-1"></i> Add Project
    </a>
</div>

<!-- Search & Filter Card -->
<div class="card filter-card border-0 mb-4">
    <div class="card-body p-3">
        <form action="{{ route('projects.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-12 col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Search projects by title..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-12 col-md-3">
                <select name="company_id" class="form-select">
                    <option value="">All Companies</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="on_hold" {{ request('status') === 'on_hold' ? 'selected' : '' }}>On Hold</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-12 col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-dark w-100 fw-semibold rounded-3">Filter</button>
                @if(request()->anyFilled(['search', 'company_id', 'status']))
                    <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary w-100 fw-semibold rounded-3">Clear</a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Projects List Card -->
<div class="card card-table border-0">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Project Title</th>
                    <th>Company & Client</th>
                    <th>Budget</th>
                    <th>Progress</th>
                    <th>Deadline</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($projects as $project)
                    <tr>
                        <td>
                            <a href="{{ route('projects.show', $project->id) }}" class="fw-bold text-dark text-decoration-none hover-primary">{{ $project->title }}</a>
                        </td>
                        <td>
                            <div class="small fw-semibold"><i class="fa-solid fa-building me-1 text-muted"></i>{{ $project->company->name }}</div>
                            <div class="small text-muted"><i class="fa-solid fa-user me-1 text-muted"></i>{{ $project->client ? $project->client->name : 'N/A' }}</div>
                        </td>
                        <td>
                            <span class="fw-bold text-dark">${{ number_format($project->budget, 2) }}</span>
                        </td>
                        <td style="width: 18%;">
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-grow-1" style="height: 6px; border-radius: 3px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $project->progress }}%"></div>
                                </div>
                                <span class="small fw-bold">{{ $project->progress }}%</span>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-1.5 flex-wrap">
                                <span class="small fw-semibold text-muted">{{ $project->deadline ? $project->deadline->format('M d, Y') : 'No Deadline' }}</span>
                                @if($project->isOverdue())
                                    <span class="badge bg-danger-subtle text-danger border border-danger border-opacity-10 overdue-pulse px-2 py-0.5 rounded-pill" style="font-size: 0.7rem;">
                                        <i class="fa-solid fa-triangle-exclamation me-0.5"></i>Overdue
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td>
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
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-1">
                                <a href="{{ route('projects.show', $project->id) }}" class="btn btn-sm btn-outline-info rounded-pill" title="View Project Details">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-sm btn-outline-primary rounded-pill" title="Edit Project">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal" 
                                    data-url="{{ route('projects.destroy', $project->id) }}" 
                                    data-name="{{ $project->title }}"
                                    title="Delete Project">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-briefcase fs-1 mb-2 text-light"></i>
                            <p class="mb-0">No projects found matching filters.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($projects->hasPages())
        <div class="card-footer bg-white border-top py-3">
            {{ $projects->links() }}
        </div>
    @endif
</div>
@endsection
