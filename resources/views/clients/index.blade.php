@extends('layouts.app')

@section('title', 'Clients')
@section('page_title', 'Client Contacts Directory')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">Manage contact persons at customer companies, their emails, phone numbers, and professional designations.</p>
    <a href="{{ route('clients.create', ['company_id' => request('company_id')]) }}" class="btn btn-primary rounded-pill fw-semibold shadow-sm">
        <i class="fa-solid fa-plus me-1"></i> Add Client
    </a>
</div>

<!-- Search & Filter Card -->
<div class="card filter-card border-0 mb-4">
    <div class="card-body p-3">
        <form action="{{ route('clients.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-12 col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Search by name, email or designation..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-12 col-md-3">
                <select name="company_id" class="form-select form-select-compact">
                    <option value="">All Companies</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-2">
                <select name="status" class="form-select form-select-compact">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active Only</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive Only</option>
                </select>
            </div>
            <div class="col-12 col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-dark w-100 fw-semibold rounded-3">Filter</button>
                @if(request()->anyFilled(['search', 'company_id', 'status']))
                    <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary w-100 fw-semibold rounded-3">Clear</a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Clients List Card -->
<div class="card card-table border-0">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Client Name</th>
                    <th>Company</th>
                    <th>Designation</th>
                    <th>Contact details</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clients as $client)
                    <tr>
                        <td>
                            <div class="fw-bold text-dark">{{ $client->name }}</div>
                        </td>
                        <td>
                            <a href="{{ route('companies.show', $client->company->id) }}" class="fw-semibold text-primary text-decoration-none hover-primary">
                                <i class="fa-solid fa-building me-1 text-muted small"></i>{{ $client->company->name }}
                            </a>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border px-2 py-1.5 rounded-3">{{ $client->designation ?? 'Contact Person' }}</span>
                        </td>
                        <td>
                            <div class="small fw-semibold"><i class="fa-regular fa-envelope me-1.5 text-muted"></i>{{ $client->email ?? 'N/A' }}</div>
                            <div class="small text-muted"><i class="fa-solid fa-phone me-1.5 text-muted"></i>{{ $client->phone ?? 'N/A' }}</div>
                        </td>
                        <td>
                            @if($client->status === 'active')
                                <span class="badge bg-success-subtle text-success border border-success border-opacity-10 px-2 py-1.5 rounded-pill">Active</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger border-opacity-10 px-2 py-1.5 rounded-pill">Inactive</span>
                            @endif
                        </td>
                        <td class="text-end"><div class="table-actions"><div class="table-actions">
                                <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-sm btn-outline-primary rounded-pill" title="Edit Contact">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal" 
                                    data-url="{{ route('clients.destroy', $client->id) }}" 
                                    data-name="{{ $client->name }}"
                                    title="Delete Client">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div></div></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-users fs-1 mb-2 text-light"></i>
                            <p class="mb-0">No clients found matching filters.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @include('components.table-pagination', ['paginator'=>$clients, 'sorts'=>['created_at'=>'Created','name'=>'Name','email'=>'Email','status'=>'Status']])
</div>
@endsection
