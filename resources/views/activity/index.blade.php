@extends('layouts.app')

@section('title', 'Activity Logs')
@section('page_title', 'Activity Logs')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">Audit trail of create, update, and delete actions across the CRM.</p>
</div>

<div class="card filter-card border-0 mb-4">
    <div class="card-body p-3">
        <form action="{{ route('activity.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-12 col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search descriptions..." value="{{ request('search') }}">
            </div>
            <div class="col-12 col-md-3">
                <select name="action" class="form-select">
                    <option value="">All Actions</option>
                    @foreach(['Created', 'Updated', 'Deleted'] as $action)
                        <option value="{{ $action }}" {{ request('action') === $action ? 'selected' : '' }}>{{ $action }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-3">
                <select name="module" class="form-select">
                    <option value="">All Modules</option>
                    @foreach(['Companies', 'Clients', 'Projects', 'Staff', 'Tickets', 'Tasks', 'Teams', 'TicketComments'] as $module)
                        <option value="{{ $module }}" {{ request('module') === $module ? 'selected' : '' }}>{{ $module }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-2 d-flex gap-2">
                <button class="btn btn-dark w-100">Filter</button>
                @if(request()->anyFilled(['search', 'action', 'module']))
                    <a href="{{ route('activity.index') }}" class="btn btn-outline-secondary">Clear</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card card-table border-0">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Action</th>
                    <th>Module</th>
                    <th>Subject</th>
                    <th>When</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td>
                            @if($log->action === 'Created')
                                <span class="badge bg-success-subtle text-success border rounded-pill">{{ $log->action }}</span>
                            @elseif($log->action === 'Updated')
                                <span class="badge bg-primary-subtle text-primary border rounded-pill">{{ $log->action }}</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border rounded-pill">{{ $log->action }}</span>
                            @endif
                        </td>
                        <td><span class="badge bg-light text-dark border rounded-pill">{{ $log->module }}</span></td>
                        <td>{{ $log->description }}</td>
                        <td class="text-muted small">{{ $log->created_at->format('M d, Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-5">No activity logs found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($logs->hasPages())
        <div class="card-footer bg-white">{{ $logs->links() }}</div>
    @endif
</div>
@endsection
