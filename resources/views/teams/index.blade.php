@extends('layouts.app')

@section('title', 'Teams')
@section('page_title', 'Teams & Project Assignments')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">Group users into teams and assign teams to projects.</p>
    <a href="{{ route('teams.create') }}" class="btn btn-primary rounded-pill fw-semibold shadow-sm">
        <i class="fa-solid fa-plus me-1"></i> Add Team
    </a>
</div>

<div class="card filter-card border-0 mb-4">
    <div class="card-body p-3">
        <form action="{{ route('teams.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-12 col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Search teams..." value="{{ request('search') }}">
            </div>
            <div class="col-12 col-md-3">
                <select name="status" class="form-select form-select-compact">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-12 col-md-3 d-flex gap-2">
                <button class="btn btn-dark w-100">Filter</button>
                @if(request()->anyFilled(['search', 'status']))
                    <a href="{{ route('teams.index') }}" class="btn btn-outline-secondary">Clear</a>
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
                    <th>Team</th>
                    <th>Lead</th>
                    <th>Members</th>
                    <th>Projects</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($teams as $team)
                    <tr>
                        <td>
                            <div class="fw-bold">{{ $team->name }}</div>
                            <span class="text-muted small">{{ Str::limit($team->description, 60) }}</span>
                        </td>
                        <td>{{ $team->lead?->name ?? 'No lead' }}</td>
                        <td>{{ $team->users_count }}</td>
                        <td>{{ $team->projects_count }}</td>
                        <td>
                            <span class="badge {{ $team->status === 'active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }} border rounded-pill">
                                {{ ucfirst($team->status) }}
                            </span>
                        </td>
                        <td class="text-end"><div class="table-actions"><div class="table-actions">
                                <a href="{{ route('teams.show', $team) }}" class="btn btn-sm btn-outline-secondary rounded-pill">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('teams.edit', $team) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteModal"
                                    data-url="{{ route('teams.destroy', $team) }}"
                                    data-name="{{ $team->name }}">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div></div></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-5 text-muted">No teams found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @include('components.table-pagination', ['paginator'=>$teams, 'sorts'=>['created_at'=>'Created','name'=>'Name']])
</div>
@endsection
