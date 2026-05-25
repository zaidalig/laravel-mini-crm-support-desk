@extends('layouts.app')

@section('title', 'Users')
@section('page_title', 'Users & Permissions')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">Create users, assign roles, and block or unblock account access.</p>
    <a href="{{ route('users.create') }}" class="btn btn-primary rounded-pill fw-semibold shadow-sm">
        <i class="fa-solid fa-plus me-1"></i> Add User
    </a>
</div>

<div class="card filter-card border-0 mb-4">
    <div class="card-body p-3">
        <form action="{{ route('users.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-12 col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search users..." value="{{ request('search') }}">
            </div>
            <div class="col-12 col-md-3">
                <select name="role" class="form-select">
                    <option value="">All Roles</option>
                    @foreach(['owner' => 'Owner', 'manager' => 'Manager', 'support' => 'Support', 'viewer' => 'Viewer'] as $value => $label)
                        <option value="{{ $value }}" {{ request('role') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    @foreach(['active' => 'Active', 'inactive' => 'Inactive', 'blocked' => 'Blocked'] as $value => $label)
                        <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-2 d-flex gap-2">
                <button class="btn btn-dark w-100">Filter</button>
                @if(request()->anyFilled(['search', 'role', 'status']))
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Clear</a>
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
                    <th>User</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Teams</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="fw-bold text-dark">{{ $user->name }}</div>
                            <span class="text-muted small">{{ $user->email }}</span>
                        </td>
                        <td><span class="badge bg-primary-subtle text-primary border rounded-pill">{{ ucfirst($user->role) }}</span></td>
                        <td>
                            @if($user->status === 'active')
                                <span class="badge bg-success-subtle text-success border rounded-pill">Active</span>
                            @elseif($user->status === 'blocked')
                                <span class="badge bg-danger-subtle text-danger border rounded-pill">Blocked</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border rounded-pill">Inactive</span>
                            @endif
                        </td>
                        <td>{{ $user->teams_count }}</td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-1">
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                @if($user->status === 'blocked')
                                    <form action="{{ route('users.unblock', $user) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn btn-sm btn-outline-success rounded-pill" title="Unblock">
                                            <i class="fa-solid fa-lock-open"></i>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('users.block', $user) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn btn-sm btn-outline-warning rounded-pill" title="Block">
                                            <i class="fa-solid fa-ban"></i>
                                        </button>
                                    </form>
                                @endif
                                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteModal"
                                    data-url="{{ route('users.destroy', $user) }}"
                                    data-name="{{ $user->name }}">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-5">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
        <div class="card-footer bg-white">{{ $users->links() }}</div>
    @endif
</div>
@endsection
