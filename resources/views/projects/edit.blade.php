@extends('layouts.app')

@section('title', 'Edit Project')
@section('page_title', 'Update Project Details')

@section('content')
<div class="mb-4">
    <a href="{{ route('projects.show', $project->id) }}" class="btn btn-sm btn-outline-secondary rounded-pill">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to Project Details
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-lg-9">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="m-0 fw-bold"><i class="fa-solid fa-briefcase text-primary me-2"></i>Update Details for "{{ $project->title }}"</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('projects.update', $project->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <!-- Company & Client selector -->
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-6">
                            <label for="company_id" class="form-label fw-bold">Company Client <span class="text-danger">*</span></label>
                            <select name="company_id" id="company_id" class="form-select @error('company_id') is-invalid @enderror" required>
                                <option value="">-- Select Company --</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" {{ old('company_id', $project->company_id) == $company->id ? 'selected' : '' }}>
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('company_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-12 col-md-6">
                            <label for="client_id" class="form-label fw-bold">Primary Contact Person</label>
                            <select name="client_id" id="client_id" class="form-select @error('client_id') is-invalid @enderror">
                                <option value="">-- Select Contact --</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" data-company-id="{{ $client->company_id }}" {{ old('client_id', $project->client_id) == $client->id ? 'selected' : '' }}>
                                        {{ $client->name }} ({{ $client->designation ?? 'Contact' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('client_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label fw-bold">Project Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" placeholder="e.g. Website Redesign" value="{{ old('title', $project->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label fw-bold">Project Description</label>
                        <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror" placeholder="Outline scope, deliverables, and targets...">{{ old('description', $project->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Budget & Dates -->
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-4">
                            <label for="budget" class="form-label fw-bold">Budget (USD)</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" name="budget" id="budget" class="form-control @error('budget') is-invalid @enderror" placeholder="0.00" value="{{ old('budget', $project->budget) }}">
                            </div>
                            @error('budget')
                                <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-12 col-md-4">
                            <label for="start_date" class="form-label fw-bold">Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', $project->start_date ? $project->start_date->format('Y-m-d') : '') }}">
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-4">
                            <label for="deadline" class="form-label fw-bold">Deadline Date</label>
                            <input type="date" name="deadline" id="deadline" class="form-control @error('deadline') is-invalid @enderror" value="{{ old('deadline', $project->deadline ? $project->deadline->format('Y-m-d') : '') }}">
                            @error('deadline')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Progress & Status -->
                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-6">
                            <label for="progress" class="form-label fw-bold">Completion Progress (<span id="progress-val">{{ $project->progress }}</span>%)</label>
                            <input type="range" name="progress" id="progress" class="form-range @error('progress') is-invalid @enderror" min="0" max="100" value="{{ old('progress', $project->progress) }}" style="padding-top: 0.5rem;">
                            @error('progress')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="status" class="form-label fw-bold">Project Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="pending" {{ old('status', $project->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ old('status', $project->status) === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ old('status', $project->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="on_hold" {{ old('status', $project->status) === 'on_hold' ? 'selected' : '' }}>On Hold</option>
                                <option value="cancelled" {{ old('status', $project->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 border-top pt-3">
                        <a href="{{ route('projects.show', $project->id) }}" class="btn btn-light border px-4 rounded-pill">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4 rounded-pill fw-semibold">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Live update completion range display
    const rangeInput = document.getElementById('progress');
    const rangeVal = document.getElementById('progress-val');
    if (rangeInput && rangeVal) {
        rangeInput.addEventListener('input', function() {
            rangeVal.textContent = this.value;
        });
        // Initial setup
        rangeVal.textContent = rangeInput.value;
    }

    // Dynamic filtering of Clients based on selected Company
    const companySelect = document.getElementById('company_id');
    const clientSelect = document.getElementById('client_id');
    
    if (companySelect && clientSelect) {
        function filterClients() {
            const companyId = companySelect.value;
            const options = clientSelect.querySelectorAll('option');
            
            options.forEach(option => {
                const optCompanyId = option.getAttribute('data-company-id');
                if (!optCompanyId) return; // Skip placeholder
                
                if (companyId === '' || optCompanyId === companyId) {
                    option.style.display = 'block';
                    option.disabled = false;
                } else {
                    option.style.display = 'none';
                    option.disabled = true;
                }
            });
            
            // Check if selected option is disabled/hidden, if so reset select
            const selectedOption = clientSelect.options[clientSelect.selectedIndex];
            if (selectedOption && selectedOption.value && selectedOption.disabled) {
                clientSelect.value = '';
            }
        }
        
        companySelect.addEventListener('change', filterClients);
        // Fire initially
        filterClients();
    }
</script>
@endsection
