@extends('layouts.app')

@section('title', 'Create Support Ticket')
@section('page_title', 'Open New Support Ticket')

@section('content')
<div class="mb-4">
    <a href="{{ route('tickets.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to Queue
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-lg-9">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="m-0 fw-bold"><i class="fa-solid fa-ticket-simple text-primary me-2"></i>Ticket details</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('tickets.store') }}" method="POST">
                    @csrf
                    
                    <!-- Company & Client selector -->
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-6">
                            <label for="company_id" class="form-label fw-bold">Company / Account <span class="text-danger">*</span></label>
                            <select name="company_id" id="company_id" class="form-select @error('company_id') is-invalid @enderror" required>
                                <option value="">-- Select Company --</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" {{ old('company_id', request('company_id')) == $company->id ? 'selected' : '' }}>
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('company_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-12 col-md-6">
                            <label for="client_id" class="form-label fw-bold">Reporting Client <span class="text-danger">*</span></label>
                            <select name="client_id" id="client_id" class="form-select @error('client_id') is-invalid @enderror" required>
                                <option value="">-- Select Client Contact (Select Company First) --</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" data-company-id="{{ $client->company_id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
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
                        <label for="title" class="form-label fw-bold">Subject / Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" placeholder="e.g. Cannot access payment gateway API" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label fw-bold">Description of the Issue <span class="text-danger">*</span></label>
                        <textarea name="description" id="description" rows="5" class="form-control @error('description') is-invalid @enderror" placeholder="Provide full details, steps to reproduce, or error messages..." required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Priority, Status, Assignee, Due date -->
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-3">
                            <label for="priority" class="form-label fw-bold">Priority <span class="text-danger">*</span></label>
                            <select name="priority" id="priority" class="form-select @error('priority') is-invalid @enderror" required>
                                <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High</option>
                                <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-3">
                            <label for="status" class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="open" {{ old('status', 'open') === 'open' ? 'selected' : '' }}>Open</option>
                                <option value="in_progress" {{ old('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="resolved" {{ old('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="closed" {{ old('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-3">
                            <label for="assigned_to" class="form-label fw-bold">Assign Staff</label>
                            <select name="assigned_to" id="assigned_to" class="form-select @error('assigned_to') is-invalid @enderror">
                                <option value="">-- Assign Employee --</option>
                                @foreach($staffList as $staff)
                                    <option value="{{ $staff->id }}" {{ old('assigned_to') == $staff->id ? 'selected' : '' }}>
                                        {{ $staff->name }} ({{ $staff->role }})
                                    </option>
                                @endforeach
                            </select>
                            @error('assigned_to')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-3">
                            <label for="due_date" class="form-label fw-bold">Resolution Due Date</label>
                            <input type="date" name="due_date" id="due_date" class="form-control @error('due_date') is-invalid @enderror" value="{{ old('due_date') }}">
                            @error('due_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 border-top pt-3">
                        <a href="{{ route('tickets.index') }}" class="btn btn-light border px-4 rounded-pill">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4 rounded-pill fw-semibold">Open Ticket</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
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
