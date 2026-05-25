@extends('layouts.app')

@section('title', 'Company Profile - ' . $company->name)
@section('page_title', 'Company Profile')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <a href="{{ route('companies.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to Directory
    </a>
    <div class="d-flex gap-2">
        <a href="{{ route('companies.edit', $company->id) }}" class="btn btn-primary rounded-pill fw-semibold shadow-sm px-3 btn-sm">
            <i class="fa-solid fa-pen me-1"></i> Edit Profile
        </a>
        <button type="button" class="btn btn-danger rounded-pill fw-semibold shadow-sm px-3 btn-sm"
            data-bs-toggle="modal" 
            data-bs-target="#deleteModal" 
            data-url="{{ route('companies.destroy', $company->id) }}" 
            data-name="{{ $company->name }}">
            <i class="fa-solid fa-trash-can me-1"></i> Delete
        </button>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Company Details -->
    <div class="col-12 col-lg-4">
        <!-- Profile Card -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <div class="text-center pb-3 border-bottom mb-3">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 72px; height: 72px; font-size: 2rem;">
                        <i class="fa-solid fa-building"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-1">{{ $company->name }}</h4>
                    <span class="badge bg-light text-dark border px-2.5 py-1.5 rounded-3">{{ $company->industry ?? 'Other Industry' }}</span>
                </div>

                <ul class="list-unstyled mb-0">
                    <li class="d-flex justify-content-between mb-2">
                        <span class="text-muted fw-semibold">Status:</span>
                        @if($company->status === 'active')
                            <span class="badge bg-success-subtle text-success border border-success border-opacity-10 px-2.5 py-1 rounded-pill">Active</span>
                        @else
                            <span class="badge bg-danger-subtle text-danger border border-danger border-opacity-10 px-2.5 py-1 rounded-pill">Inactive</span>
                        @endif
                    </li>
                    <li class="mb-3">
                        <span class="text-muted fw-semibold d-block mb-1">Email:</span>
                        <span class="text-dark fw-bold"><i class="fa-regular fa-envelope me-1.5 text-muted"></i>{{ $company->email ?? 'N/A' }}</span>
                    </li>
                    <li class="mb-3">
                        <span class="text-muted fw-semibold d-block mb-1">Phone:</span>
                        <span class="text-dark fw-bold"><i class="fa-solid fa-phone me-1.5 text-muted"></i>{{ $company->phone ?? 'N/A' }}</span>
                    </li>
                    <li class="mb-3">
                        <span class="text-muted fw-semibold d-block mb-1">City:</span>
                        <span class="text-dark fw-bold"><i class="fa-solid fa-location-dot me-1.5 text-muted"></i>{{ $company->city ?? 'N/A' }}</span>
                    </li>
                    <li class="mb-0">
                        <span class="text-muted fw-semibold d-block mb-1">Street Address:</span>
                        <span class="text-dark small d-block bg-light p-2.5 rounded-3 border">{{ $company->address ?? 'No address provided' }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Quick Stats Card -->
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-chart-simple text-indigo me-2"></i>Quick Metrics</h5>
            <div class="row g-2">
                <div class="col-4 text-center p-2 bg-light rounded-3">
                    <div class="h3 fw-bold text-primary mb-0">{{ $company->clients->count() }}</div>
                    <span class="small text-muted fw-semibold">Clients</span>
                </div>
                <div class="col-4 text-center p-2 bg-light rounded-3">
                    <div class="h3 fw-bold text-success mb-0">{{ $company->projects->count() }}</div>
                    <span class="small text-muted fw-semibold">Projects</span>
                </div>
                <div class="col-4 text-center p-2 bg-light rounded-3">
                    <div class="h3 fw-bold text-warning mb-0">{{ $company->tickets->count() }}</div>
                    <span class="small text-muted fw-semibold">Tickets</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Associated Listings -->
    <div class="col-12 col-lg-8">
        <!-- Related Clients -->
        <div class="card card-table border-0 mb-4">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h5 class="m-0 fw-bold"><i class="fa-solid fa-users text-info me-2"></i>Clients & Contacts</h5>
                <a href="{{ route('clients.create', ['company_id' => $company->id]) }}" class="btn btn-sm btn-outline-info rounded-pill fw-semibold">Add Client</a>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Designation</th>
                            <th>Contact</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($company->clients as $client)
                            <tr>
                                <td><div class="fw-bold text-dark">{{ $client->name }}</div></td>
                                <td><span class="badge bg-light text-dark border px-2 py-1">{{ $client->designation ?? 'N/A' }}</span></td>
                                <td>
                                    <div class="small fw-semibold">{{ $client->email ?? 'N/A' }}</div>
                                    <div class="small text-muted">{{ $client->phone ?? 'N/A' }}</div>
                                </td>
                                <td>
                                    @if($client->status === 'active')
                                        <span class="badge bg-success-subtle text-success px-2 py-1 rounded-pill">Active</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger px-2 py-1 rounded-pill">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">No clients associated with this company.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Related Projects -->
        <div class="card card-table border-0 mb-4">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h5 class="m-0 fw-bold"><i class="fa-solid fa-briefcase text-success me-2"></i>Projects</h5>
                <a href="{{ route('projects.create', ['company_id' => $company->id]) }}" class="btn btn-sm btn-outline-success rounded-pill fw-semibold">Add Project</a>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Project Title</th>
                            <th>Progress</th>
                            <th>Status</th>
                            <th class="text-end">View</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($company->projects as $project)
                            <tr>
                                <td>
                                    <a href="{{ route('projects.show', $project->id) }}" class="fw-bold text-dark text-decoration-none hover-primary">{{ $project->title }}</a>
                                    <div class="text-muted small">Due: {{ $project->deadline ? $project->deadline->format('M d, Y') : 'No Deadline' }}</div>
                                </td>
                                <td style="width: 25%;">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1" style="height: 6px; border-radius: 3px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $project->progress }}%"></div>
                                        </div>
                                        <span class="small fw-bold">{{ $project->progress }}%</span>
                                    </div>
                                </td>
                                <td>
                                    @if($project->status === 'completed')
                                        <span class="badge bg-success-subtle text-success px-2 py-1 rounded-pill">Completed</span>
                                    @elseif($project->status === 'in_progress')
                                        <span class="badge bg-primary-subtle text-primary px-2 py-1 rounded-pill">In Progress</span>
                                    @elseif($project->status === 'pending')
                                        <span class="badge bg-warning-subtle text-warning px-2 py-1 rounded-pill">Pending</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary px-2 py-1 rounded-pill">{{ ucfirst($project->status) }}</span>
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
                                <td colspan="4" class="text-center py-4 text-muted">No projects found for this company.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Related Support Tickets -->
        <div class="card card-table border-0">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h5 class="m-0 fw-bold"><i class="fa-solid fa-ticket-simple text-warning me-2"></i>Support Tickets</h5>
                <a href="{{ route('tickets.create', ['company_id' => $company->id]) }}" class="btn btn-sm btn-outline-warning rounded-pill fw-semibold">Create Ticket</a>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Ticket</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th class="text-end">View</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($company->tickets as $ticket)
                            <tr>
                                <td>
                                    <a href="{{ route('tickets.show', $ticket->id) }}" class="fw-bold text-dark text-decoration-none hover-primary">#{{ $ticket->id }}: {{ $ticket->title }}</a>
                                    <div class="text-muted small">Assigned: {{ $ticket->assignedStaff ? $ticket->assignedStaff->name : 'Unassigned' }}</div>
                                </td>
                                <td>
                                    @if($ticket->priority === 'urgent')
                                        <span class="badge bg-danger text-white px-2 py-1 rounded-pill">Urgent</span>
                                    @elseif($ticket->priority === 'high')
                                        <span class="badge bg-danger-subtle text-danger px-2 py-1 rounded-pill">High</span>
                                    @elseif($ticket->priority === 'medium')
                                        <span class="badge bg-warning-subtle text-warning px-2 py-1 rounded-pill">Medium</span>
                                    @else
                                        <span class="badge bg-info-subtle text-info px-2 py-1 rounded-pill">Low</span>
                                    @endif
                                </td>
                                <td>
                                    @if($ticket->status === 'open')
                                        <span class="badge bg-danger-subtle text-danger px-2 py-1 rounded-pill">Open</span>
                                    @elseif($ticket->status === 'in_progress')
                                        <span class="badge bg-primary-subtle text-primary px-2 py-1 rounded-pill">In Progress</span>
                                    @elseif($ticket->status === 'resolved')
                                        <span class="badge bg-success-subtle text-success px-2 py-1 rounded-pill">Resolved</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary px-2 py-1 rounded-pill">Closed</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('tickets.show', $ticket->id) }}" class="btn btn-sm btn-light border rounded-pill">
                                        <i class="fa-solid fa-chevron-right text-muted"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">No support tickets found for this company.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
