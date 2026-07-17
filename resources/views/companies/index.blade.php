@extends('layouts.app')

@section('title', 'Companies')
@section('page_title', 'Company Directory')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">Manage customer companies, their profile info, and view related clients, projects and tickets.</p>
    <a href="{{ route('companies.create') }}" class="btn btn-primary rounded-pill fw-semibold shadow-sm">
        <i class="fa-solid fa-plus me-1"></i> Add Company
    </a>
</div>

<!-- Search & Filter Card -->
<div class="card filter-card border-0 mb-4">
    <div class="card-body p-3">
        <form action="{{ route('companies.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-12 col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Search by name, email, industry or city..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-12 col-md-3">
                <select name="status" class="form-select form-select-compact">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active Only</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive Only</option>
                </select>
            </div>
            <div class="col-12 col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-dark w-100 fw-semibold rounded-3">Filter</button>
                @if(request()->anyFilled(['search', 'status']))
                    <a href="{{ route('companies.index') }}" class="btn btn-outline-secondary w-100 fw-semibold rounded-3">Clear</a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Companies List Card -->
<div class="card card-table border-0">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Company Name</th>
                    <th>Industry</th>
                    <th>Contact Info</th>
                    <th>City</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($companies as $company)
                    <tr>
                        <td>
                            <a href="{{ route('companies.show', $company->id) }}" class="fw-bold text-dark text-decoration-none hover-primary">{{ $company->name }}</a>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border px-2 py-1.5 rounded-3">{{ $company->industry ?? 'N/A' }}</span>
                        </td>
                        <td>
                            <div class="small fw-semibold"><i class="fa-regular fa-envelope me-1 text-muted"></i>{{ $company->email ?? 'N/A' }}</div>
                            <div class="small text-muted"><i class="fa-solid fa-phone me-1 text-muted"></i>{{ $company->phone ?? 'N/A' }}</div>
                        </td>
                        <td>
                            <span class="fw-semibold text-muted small"><i class="fa-solid fa-location-dot me-1 text-muted"></i>{{ $company->city ?? 'N/A' }}</span>
                        </td>
                        <td>
                            @if($company->status === 'active')
                                <span class="badge bg-success-subtle text-success border border-success border-opacity-10 px-2 py-1.5 rounded-pill">Active</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger border-opacity-10 px-2 py-1.5 rounded-pill">Inactive</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="table-actions">
                                <a href="{{ route('companies.show', $company->id) }}" class="btn btn-sm btn-outline-info rounded-pill" title="View Details">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('companies.edit', $company->id) }}" class="btn btn-sm btn-outline-primary rounded-pill" title="Edit Profile">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal" 
                                    data-url="{{ route('companies.destroy', $company->id) }}" 
                                    data-name="{{ $company->name }}"
                                    title="Delete Company">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-building fs-1 mb-2 text-light"></i>
                            <p class="mb-0">No companies found matching filters.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @include('components.table-pagination', ['paginator' => $companies, 'sorts' => ['created_at' => 'Created', 'name' => 'Name', 'status' => 'Status']])
</div>
@endsection
