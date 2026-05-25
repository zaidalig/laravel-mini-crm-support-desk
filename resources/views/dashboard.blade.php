@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard Overview')

@section('content')
<!-- KPI Cards Row -->
<div class="row g-3 mb-4">
    <!-- Companies Card -->
    <div class="col-6 col-lg-2">
        <a href="{{ route('companies.index') }}" class="text-decoration-none">
            <div class="stat-card card-primary p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted small mb-1 fw-semibold">Companies</p>
                        <h3 class="fw-bold mb-0 text-dark">{{ $totalCompanies }}</h3>
                    </div>
                    <div class="card-icon bg-indigo-subtle text-primary bg-opacity-10">
                        <i class="fa-solid fa-building"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Clients Card -->
    <div class="col-6 col-lg-2">
        <a href="{{ route('clients.index') }}" class="text-decoration-none">
            <div class="stat-card card-info p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted small mb-1 fw-semibold">Clients</p>
                        <h3 class="fw-bold mb-0 text-dark">{{ $totalClients }}</h3>
                    </div>
                    <div class="card-icon bg-info-subtle text-info bg-opacity-10">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Active Projects Card -->
    <div class="col-6 col-lg-2">
        <a href="{{ route('projects.index', ['status' => 'in_progress']) }}" class="text-decoration-none">
            <div class="stat-card card-success p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted small mb-1 fw-semibold">Active Projects</p>
                        <h3 class="fw-bold mb-0 text-dark">{{ $activeProjects }}</h3>
                    </div>
                    <div class="card-icon bg-success-subtle text-success bg-opacity-10">
                        <i class="fa-solid fa-briefcase"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Open Tickets Card -->
    <div class="col-6 col-lg-2">
        <a href="{{ route('tickets.index', ['status' => 'open']) }}" class="text-decoration-none">
            <div class="stat-card card-warning p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted small mb-1 fw-semibold">Open Tickets</p>
                        <h3 class="fw-bold mb-0 text-dark">{{ $openTickets }}</h3>
                    </div>
                    <div class="card-icon bg-warning-subtle text-warning bg-opacity-10">
                        <i class="fa-solid fa-ticket-simple"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Pending Tasks Card -->
    <div class="col-6 col-lg-2">
        <a href="{{ route('tasks.index', ['status' => 'pending']) }}" class="text-decoration-none">
            <div class="stat-card card-secondary p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted small mb-1 fw-semibold">Pending Tasks</p>
                        <h3 class="fw-bold mb-0 text-dark">{{ $pendingTasks }}</h3>
                    </div>
                    <div class="card-icon bg-secondary-subtle text-secondary bg-opacity-10">
                        <i class="fa-solid fa-list-check"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Overdue Tasks Card -->
    <div class="col-6 col-lg-2">
        <a href="{{ route('tasks.index') }}" class="text-decoration-none">
            <div class="stat-card card-danger {{ $overdueTasks > 0 ? 'overdue-pulse' : '' }} p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted small mb-1 fw-semibold">Overdue Tasks</p>
                        <h3 class="fw-bold mb-0 text-dark">{{ $overdueTasks }}</h3>
                    </div>
                    <div class="card-icon bg-danger-subtle text-danger bg-opacity-10">
                        <i class="fa-solid fa-circle-exclamation"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Main Dashboard Grid -->
<div class="row g-4">
    <!-- Left Column: Tables -->
    <div class="col-12 col-xl-8">
        <!-- Recent Projects Card -->
        <div class="card card-table mb-4 border-0">
            <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom py-3">
                <h5 class="m-0 fw-bold"><i class="fa-solid fa-folder-open me-2 text-primary"></i>Recent Projects</h5>
                <a href="{{ route('projects.index') }}" class="btn btn-sm btn-outline-primary fw-semibold rounded-pill">View All Projects</a>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Project Title</th>
                            <th>Company</th>
                            <th>Progress</th>
                            <th>Status</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentProjects as $project)
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark">{{ $project->title }}</div>
                                    <span class="text-muted small">Due: {{ $project->deadline ? $project->deadline->format('M d, Y') : 'No Deadline' }}</span>
                                </td>
                                <td>
                                    <span class="fw-semibold">{{ $project->company->name }}</span>
                                </td>
                                <td style="width: 20%;">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1" style="height: 6px; border-radius: 3px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $project->progress }}%" aria-valuenow="{{ $project->progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <span class="small fw-bold">{{ $project->progress }}%</span>
                                    </div>
                                </td>
                                <td>
                                    @if($project->status === 'completed')
                                        <span class="badge bg-success-subtle text-success border border-success border-opacity-10 px-2 py-1.5 rounded-pill">Completed</span>
                                    @elseif($project->status === 'in_progress')
                                        <span class="badge bg-primary-subtle text-primary border border-primary border-opacity-10 px-2 py-1.5 rounded-pill">In Progress</span>
                                    @elseif($project->status === 'pending')
                                        <span class="badge bg-warning-subtle text-warning border border-warning border-opacity-10 px-2 py-1.5 rounded-pill">Pending</span>
                                    @elseif($project->status === 'on_hold')
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary border-opacity-10 px-2 py-1.5 rounded-pill">On Hold</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger border-opacity-10 px-2 py-1.5 rounded-pill">Cancelled</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('projects.show', $project->id) }}" class="btn btn-sm btn-light border rounded-pill">
                                        <i class="fa-solid fa-chevron-right text-muted"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No projects found. Create your first project now!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Tickets Card -->
        <div class="card card-table border-0">
            <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom py-3">
                <h5 class="m-0 fw-bold"><i class="fa-solid fa-ticket-simple me-2 text-warning"></i>Recent Support Tickets</h5>
                <a href="{{ route('tickets.index') }}" class="btn btn-sm btn-outline-warning fw-semibold rounded-pill">View All Tickets</a>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Ticket</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Assigned To</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentTickets as $ticket)
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark">#{{ $ticket->id }}: {{ $ticket->title }}</div>
                                    <span class="text-muted small">Client: {{ $ticket->client->name }} ({{ $ticket->company->name }})</span>
                                </td>
                                <td>
                                    @if($ticket->priority === 'urgent')
                                        <span class="badge bg-danger text-white px-2 py-1.5 rounded-pill">Urgent</span>
                                    @elseif($ticket->priority === 'high')
                                        <span class="badge bg-danger-subtle text-danger border border-danger border-opacity-10 px-2 py-1.5 rounded-pill">High</span>
                                    @elseif($ticket->priority === 'medium')
                                        <span class="badge bg-warning-subtle text-warning border border-warning border-opacity-10 px-2 py-1.5 rounded-pill">Medium</span>
                                    @else
                                        <span class="badge bg-info-subtle text-info border border-info border-opacity-10 px-2 py-1.5 rounded-pill">Low</span>
                                    @endif
                                </td>
                                <td>
                                    @if($ticket->status === 'open')
                                        <span class="badge bg-danger-subtle text-danger border border-danger border-opacity-10 px-2 py-1.5 rounded-pill">Open</span>
                                    @elseif($ticket->status === 'in_progress')
                                        <span class="badge bg-primary-subtle text-primary border border-primary border-opacity-10 px-2 py-1.5 rounded-pill">In Progress</span>
                                    @elseif($ticket->status === 'resolved')
                                        <span class="badge bg-success-subtle text-success border border-success border-opacity-10 px-2 py-1.5 rounded-pill">Resolved</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary border-opacity-10 px-2 py-1.5 rounded-pill">Closed</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center border" style="width: 28px; height: 28px; font-size: 0.8rem;">
                                            <i class="fa-solid fa-user text-muted"></i>
                                        </div>
                                        <span class="small fw-semibold">{{ $ticket->assignedStaff ? $ticket->assignedStaff->name : 'Unassigned' }}</span>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('tickets.show', $ticket->id) }}" class="btn btn-sm btn-light border rounded-pill">
                                        <i class="fa-solid fa-chevron-right text-muted"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No support tickets found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Column: Recent Activity Logs -->
    <div class="col-12 col-xl-4">
        <div class="card card-table border-0 h-100">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="m-0 fw-bold"><i class="fa-solid fa-clock-rotate-left me-2 text-info"></i>System Activity Logs</h5>
            </div>
            <div class="card-body p-3 overflow-auto" style="max-height: 520px;">
                <div class="position-relative">
                    @forelse($recentActivities as $log)
                        <div class="d-flex gap-3 mb-4 position-relative">
                            <!-- Vertical timeline line -->
                            @if(!$loop->last)
                                <div class="position-absolute bg-light-subtle border-start" style="left: 17px; top: 34px; bottom: -24px; width: 2px;"></div>
                            @endif

                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 36px; height: 36px; flex-shrink: 0; background-color: 
                                @if($log->action === 'Created') #10b981
                                @elseif($log->action === 'Updated') #3b82f6
                                @else #ef4444
                                @endif
                            ">
                                <i class="fa-solid 
                                    @if($log->action === 'Created') fa-plus
                                    @elseif($log->action === 'Updated') fa-pen-to-square
                                    @else fa-trash-can
                                    @endif
                                    small"></i>
                            </div>
                            <div class="bg-light p-2.5 rounded-3 flex-grow-1 border">
                                <p class="mb-0 text-dark fw-semibold" style="font-size: 0.9rem;">{{ $log->description }}</p>
                                <div class="d-flex align-items-center justify-content-between mt-1">
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary" style="font-size: 0.75rem;">{{ $log->module }}</span>
                                    <span class="text-muted small" style="font-size: 0.75rem;"><i class="fa-regular fa-clock me-1"></i>{{ $log->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <i class="fa-solid fa-database fs-2 mb-2 text-light"></i>
                            <p class="mb-0">No system activities recorded yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
