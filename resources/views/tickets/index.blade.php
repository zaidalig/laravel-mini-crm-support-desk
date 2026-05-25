@extends('layouts.app')

@section('title', 'Support Tickets')
@section('page_title', 'Support Ticket Queue')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">Manage customer technical and billing support tickets, priorities, assign staff members, and track resolution statuses.</p>
    <a href="{{ route('tickets.create') }}" class="btn btn-primary rounded-pill fw-semibold shadow-sm">
        <i class="fa-solid fa-plus me-1"></i> Create Ticket
    </a>
</div>

<!-- Search & Filter Card -->
<div class="card filter-card border-0 mb-4">
    <div class="card-body p-3">
        <form action="{{ route('tickets.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-12 col-md-3">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Search by title..." value="{{ request('search') }}">
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
                <select name="priority" class="form-select">
                    <option value="">All Priorities</option>
                    <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                    <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                </select>
            </div>
            <div class="col-12 col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Open</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>
            <div class="col-12 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-dark w-100 fw-semibold rounded-3">Filter</button>
                @if(request()->anyFilled(['search', 'company_id', 'priority', 'status']))
                    <a href="{{ route('tickets.index') }}" class="btn btn-outline-secondary w-100 fw-semibold rounded-3">Clear</a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Tickets List Card -->
<div class="card card-table border-0">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Ticket</th>
                    <th>Company & Client</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Assigned To</th>
                    <th>Due Date</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tickets as $ticket)
                    <tr>
                        <td>
                            <a href="{{ route('tickets.show', $ticket->id) }}" class="fw-bold text-dark text-decoration-none hover-primary">#{{ $ticket->id }}: {{ $ticket->title }}</a>
                        </td>
                        <td>
                            <div class="small fw-semibold"><i class="fa-solid fa-building me-1 text-muted"></i>{{ $ticket->company->name }}</div>
                            <div class="small text-muted"><i class="fa-solid fa-user me-1 text-muted"></i>{{ $ticket->client->name }}</div>
                        </td>
                        <td>
                            @if($ticket->priority === 'urgent')
                                <span class="badge bg-danger text-white px-2.5 py-1.5 rounded-pill">Urgent</span>
                            @elseif($ticket->priority === 'high')
                                <span class="badge bg-danger-subtle text-danger border border-danger border-opacity-10 px-2.5 py-1.5 rounded-pill">High</span>
                            @elseif($ticket->priority === 'medium')
                                <span class="badge bg-warning-subtle text-warning border border-warning border-opacity-10 px-2.5 py-1.5 rounded-pill">Medium</span>
                            @else
                                <span class="badge bg-info-subtle text-info border border-info border-opacity-10 px-2.5 py-1.5 rounded-pill">Low</span>
                            @endif
                        </td>
                        <td>
                            @if($ticket->status === 'open')
                                <span class="badge bg-danger-subtle text-danger border border-danger border-opacity-10 px-2.5 py-1.5 rounded-pill">Open</span>
                            @elseif($ticket->status === 'in_progress')
                                <span class="badge bg-primary-subtle text-primary border border-primary border-opacity-10 px-2.5 py-1.5 rounded-pill">In Progress</span>
                            @elseif($ticket->status === 'resolved')
                                <span class="badge bg-success-subtle text-success border border-success border-opacity-10 px-2.5 py-1.5 rounded-pill">Resolved</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary border-opacity-10 px-2.5 py-1.5 rounded-pill">Closed</span>
                            @endif
                        </td>
                        <td>
                            <span class="small fw-semibold text-muted"><i class="fa-solid fa-user-tie me-1 text-muted"></i>{{ $ticket->assignedStaff ? $ticket->assignedStaff->name : 'Unassigned' }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-1.5 flex-wrap">
                                <span class="small fw-semibold text-muted">{{ $ticket->due_date ? $ticket->due_date->format('M d, Y') : 'No Due Date' }}</span>
                                @if($ticket->isOverdue())
                                    <span class="badge bg-danger-subtle text-danger border border-danger border-opacity-10 overdue-pulse px-2 py-0.5 rounded-pill" style="font-size: 0.7rem;">
                                        <i class="fa-solid fa-triangle-exclamation me-0.5"></i>Overdue
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-1">
                                <a href="{{ route('tickets.show', $ticket->id) }}" class="btn btn-sm btn-outline-info rounded-pill" title="View Ticket details">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('tickets.edit', $ticket->id) }}" class="btn btn-sm btn-outline-primary rounded-pill" title="Edit Ticket">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal" 
                                    data-url="{{ route('tickets.destroy', $ticket->id) }}" 
                                    data-name="Ticket #{{ $ticket->id }}"
                                    title="Delete Ticket">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-ticket-simple fs-1 mb-2 text-light"></i>
                            <p class="mb-0">No support tickets found matching filters.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($tickets->hasPages())
        <div class="card-footer bg-white border-top py-3">
            {{ $tickets->links() }}
        </div>
    @endif
</div>
@endsection
