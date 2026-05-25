@extends('layouts.app')

@section('title', 'Team Details')
@section('page_title', $team->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <a href="{{ route('teams.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to Teams
    </a>
    <a href="{{ route('teams.edit', $team) }}" class="btn btn-primary rounded-pill">
        <i class="fa-solid fa-pen me-1"></i> Edit Team
    </a>
</div>

<div class="row g-4">
    <div class="col-12 col-xl-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">Team Overview</h5>
                <p class="text-muted">{{ $team->description ?: 'No description added.' }}</p>
                <div class="border-top pt-3">
                    <div class="mb-2"><span class="fw-bold">Lead:</span> {{ $team->lead?->name ?? 'No lead assigned' }}</div>
                    <div class="mb-2"><span class="fw-bold">Status:</span> {{ ucfirst($team->status) }}</div>
                    <div><span class="fw-bold">Created:</span> {{ $team->created_at->format('M d, Y') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-4">
        <div class="card card-table border-0 h-100">
            <div class="card-header bg-white"><h5 class="m-0 fw-bold">Members</h5></div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <tbody>
                        @forelse($team->users as $user)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $user->name }}</div>
                                    <span class="text-muted small">{{ $user->email }}</span>
                                </td>
                                <td><span class="badge bg-light text-dark border">{{ $user->pivot->member_role }}</span></td>
                            </tr>
                        @empty
                            <tr><td class="text-muted text-center py-4">No members assigned.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-4">
        <div class="card card-table border-0 h-100">
            <div class="card-header bg-white"><h5 class="m-0 fw-bold">Assigned Projects</h5></div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <tbody>
                        @forelse($team->projects as $project)
                            <tr>
                                <td>
                                    <a href="{{ route('projects.show', $project) }}" class="fw-bold text-decoration-none">{{ $project->title }}</a>
                                    <span class="d-block text-muted small">{{ $project->company?->name }}</span>
                                </td>
                                <td><span class="badge bg-primary-subtle text-primary border">{{ ucfirst(str_replace('_', ' ', $project->status)) }}</span></td>
                            </tr>
                        @empty
                            <tr><td class="text-muted text-center py-4">No projects assigned.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
