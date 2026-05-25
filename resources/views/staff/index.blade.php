@extends('layouts.app')

@section('title', 'Staff Directory')
@section('page_title', 'Staff & Employees Directory')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">Manage internal staff members, support desk agents, and developers available for support ticket and project task assignments.</p>
    <a href="{{ route('staff.create') }}" class="btn btn-primary rounded-pill fw-semibold shadow-sm">
        <i class="fa-solid fa-plus me-1"></i> Add Staff
    </a>
</div>

<!-- Search & Filter Card -->
<div class="card filter-card border-0 mb-4">
    <div class="card-body p-3">
        <form action="{{ route('staff.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-12 col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Search staff by name, email or role..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-12 col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active Only</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive Only</option>
                </select>
            </div>
            <div class="col-12 col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-dark w-100 fw-semibold rounded-3">Filter</button>
                @if(request()->anyFilled(['search', 'status']))
                    <a href="{{ route('staff.index') }}" class="btn btn-outline-secondary w-100 fw-semibold rounded-3">Clear</a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Staff List Card -->
<div class="card card-table border-0">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Staff Name</th>
                    <th>Role / Title</th>
                    <th>Email Address</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($staffList as $staff)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center border fw-bold" style="width: 40px; height: 40px; font-size: 1.1rem;">
                                    {{ substr($staff->name, 0, 1) }}
                                </div>
                                <div class="fw-bold text-dark">{{ $staff->name }}</div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border px-2.5 py-1.5 rounded-3">{{ $staff->role }}</span>
                        </td>
                        <td>
                            <span class="fw-semibold text-muted small"><i class="fa-regular fa-envelope me-1.5 text-muted"></i>{{ $staff->email }}</span>
                        </td>
                        <td>
                            @if($staff->status === 'active')
                                <span class="badge bg-success-subtle text-success border border-success border-opacity-10 px-2.5 py-1.5 rounded-pill">Active</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger border-opacity-10 px-2.5 py-1.5 rounded-pill">Inactive</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-1">
                                <a href="{{ route('staff.edit', $staff->id) }}" class="btn btn-sm btn-outline-primary rounded-pill" title="Edit Staff Details">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal" 
                                    data-url="{{ route('staff.destroy', $staff->id) }}" 
                                    data-name="{{ $staff->name }}"
                                    title="Remove Staff">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-user-tie fs-1 mb-2 text-light"></i>
                            <p class="mb-0">No staff members found matching filters.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($staffList->hasPages())
        <div class="card-footer bg-white border-top py-3">
            {{ $staffList->links() }}
        </div>
    @endif
</div>
@endsection
