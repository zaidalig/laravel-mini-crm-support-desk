@extends('layouts.app')

@section('title', 'Ticket Workspace - #' . $ticket->id)
@section('page_title', 'Ticket Workspace')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <a href="{{ route('tickets.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to Queue
    </a>
    <div class="d-flex gap-2">
        <a href="{{ route('tickets.edit', $ticket->id) }}" class="btn btn-primary rounded-pill fw-semibold shadow-sm px-3 btn-sm">
            <i class="fa-solid fa-pen me-1"></i> Edit Ticket
        </a>
        <button type="button" class="btn btn-danger rounded-pill fw-semibold shadow-sm px-3 btn-sm"
            data-bs-toggle="modal" 
            data-bs-target="#deleteModal" 
            data-url="{{ route('tickets.destroy', $ticket->id) }}" 
            data-name="Ticket #{{ $ticket->id }}">
            <i class="fa-solid fa-trash-can me-1"></i> Delete Ticket
        </button>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Ticket Details & Client Contact Profile -->
    <div class="col-12 col-lg-4">
        <!-- Ticket Meta Card -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">Ticket #{{ $ticket->id }} Details</h5>
                
                <ul class="list-unstyled mb-0">
                    <li class="mb-3 d-flex justify-content-between">
                        <span class="text-muted fw-semibold">Status:</span>
                        @if($ticket->status === 'open')
                            <span class="badge bg-danger-subtle text-danger border border-danger border-opacity-10 px-2.5 py-1 rounded-pill">Open</span>
                        @elseif($ticket->status === 'in_progress')
                            <span class="badge bg-primary-subtle text-primary border border-primary border-opacity-10 px-2.5 py-1 rounded-pill">In Progress</span>
                        @elseif($ticket->status === 'resolved')
                            <span class="badge bg-success-subtle text-success border border-success border-opacity-10 px-2.5 py-1 rounded-pill">Resolved</span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary border-opacity-10 px-2.5 py-1 rounded-pill">Closed</span>
                        @endif
                    </li>
                    <li class="mb-3 d-flex justify-content-between">
                        <span class="text-muted fw-semibold">Priority:</span>
                        @if($ticket->priority === 'urgent')
                            <span class="badge bg-danger text-white px-2.5 py-1 rounded-pill">Urgent</span>
                        @elseif($ticket->priority === 'high')
                            <span class="badge bg-danger-subtle text-danger border border-danger border-opacity-10 px-2.5 py-1 rounded-pill">High</span>
                        @elseif($ticket->priority === 'medium')
                            <span class="badge bg-warning-subtle text-warning border border-warning border-opacity-10 px-2.5 py-1 rounded-pill">Medium</span>
                        @else
                            <span class="badge bg-info-subtle text-info border border-info border-opacity-10 px-2.5 py-1 rounded-pill">Low</span>
                        @endif
                    </li>
                    <li class="mb-3 d-flex justify-content-between align-items-center">
                        <span class="text-muted fw-semibold">Due Date:</span>
                        <div class="text-end">
                            <span class="text-dark fw-bold"><i class="fa-solid fa-calendar me-1 text-muted"></i>{{ $ticket->due_date ? $ticket->due_date->format('M d, Y') : 'No Due Date' }}</span>
                            @if($ticket->isOverdue())
                                <div class="badge bg-danger-subtle text-danger border border-danger border-opacity-10 overdue-pulse px-2 py-0.5 rounded-pill mt-1" style="font-size: 0.7rem;">
                                    <i class="fa-solid fa-triangle-exclamation me-0.5"></i>Overdue
                                </div>
                            @endif
                        </div>
                    </li>
                    <li class="mb-0 d-flex justify-content-between">
                        <span class="text-muted fw-semibold">Assigned Owner:</span>
                        <span class="text-dark fw-bold"><i class="fa-solid fa-user-tie me-1.5 text-muted"></i>{{ $ticket->assignedStaff ? $ticket->assignedStaff->name : 'Unassigned' }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Associated Account Card -->
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
            <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-building text-primary me-2"></i>Reporting Client</h5>
            <div class="pb-3 border-bottom mb-3">
                <span class="text-muted small fw-semibold d-block mb-1">COMPANY ACCOUNT</span>
                <a href="{{ route('companies.show', $ticket->company->id) }}" class="fw-bold text-dark text-decoration-none hover-primary">
                    <i class="fa-solid fa-building me-1.5 text-muted"></i>{{ $ticket->company->name }}
                </a>
            </div>
            
            <div>
                <span class="text-muted small fw-semibold d-block mb-1">CONTACT PERSON</span>
                <div class="fw-bold text-dark"><i class="fa-solid fa-user me-1.5 text-muted"></i>{{ $ticket->client->name }}</div>
                <span class="d-block small text-muted mt-0.5">{{ $ticket->client->designation ?? 'Representative' }}</span>
                <div class="small fw-semibold mt-2"><i class="fa-regular fa-envelope me-1.5 text-muted"></i>{{ $ticket->client->email ?? 'N/A' }}</div>
                <div class="small text-muted"><i class="fa-solid fa-phone me-1.5 text-muted"></i>{{ $ticket->client->phone ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Right Column: Ticket Content & Separation of Comments -->
    <div class="col-12 col-lg-8">
        <!-- Ticket Issue Scope -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 p-4">
            <h4 class="fw-bold text-dark mb-3">{{ $ticket->title }}</h4>
            <span class="text-muted small d-block mb-3"><i class="fa-regular fa-clock me-1"></i>Opened {{ $ticket->created_at->format('M d, Y \a\t g:i A') }}</span>
            <div class="bg-light p-3.5 rounded-3 border">
                <p class="mb-0 text-dark" style="white-space: pre-line; line-height: 1.6; font-size: 1rem;">{{ $ticket->description }}</p>
            </div>
        </div>

        <!-- Comments & Notes Thread Card -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-bottom pt-3 pb-0">
                <h5 class="fw-bold text-dark mb-3"><i class="fa-regular fa-comments text-primary me-2"></i>Correspondence History</h5>
                
                <!-- Tab Headers -->
                <ul class="nav nav-tabs border-0" id="commentTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-bold text-muted px-3 border-0 pb-2.5" id="all-tab" data-bs-toggle="tab" data-bs-target="#all-comments" type="button" role="tab" aria-controls="all-comments" aria-selected="true">
                            All History ({{ $ticket->comments->count() }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold text-muted px-3 border-0 pb-2.5" id="public-tab" data-bs-toggle="tab" data-bs-target="#public-comments" type="button" role="tab" aria-controls="public-comments" aria-selected="false">
                            Public Replies ({{ $ticket->comments->where('is_internal_note', false)->count() }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold text-muted px-3 border-0 pb-2.5" id="internal-tab" data-bs-toggle="tab" data-bs-target="#internal-notes" type="button" role="tab" aria-controls="internal-notes" aria-selected="false">
                            Internal Notes ({{ $ticket->comments->where('is_internal_note', true)->count() }})
                        </button>
                    </li>
                </ul>
            </div>
            
            <div class="card-body p-4">
                <!-- Tab Panes -->
                <div class="tab-content" id="commentTabsContent">
                    <!-- Tab 1: All Comments -->
                    <div class="tab-pane fade show active" id="all-comments" role="tabpanel" aria-labelledby="all-tab">
                        @forelse($ticket->comments as $comment)
                            @include('tickets.partials.comment_bubble', ['comment' => $comment])
                        @empty
                            <div class="text-center py-4 text-muted">No replies posted yet.</div>
                        @endforelse
                    </div>

                    <!-- Tab 2: Public Comments Only -->
                    <div class="tab-pane fade" id="public-comments" role="tabpanel" aria-labelledby="public-tab">
                        @forelse($ticket->comments->where('is_internal_note', false) as $comment)
                            @include('tickets.partials.comment_bubble', ['comment' => $comment])
                        @empty
                            <div class="text-center py-4 text-muted">No public replies posted yet.</div>
                        @endforelse
                    </div>

                    <!-- Tab 3: Internal Notes Only -->
                    <div class="tab-pane fade" id="internal-notes" role="tabpanel" aria-labelledby="internal-tab">
                        @forelse($ticket->comments->where('is_internal_note', true) as $comment)
                            @include('tickets.partials.comment_bubble', ['comment' => $comment])
                        @empty
                            <div class="text-center py-4 text-muted text-warning">
                                <i class="fa-solid fa-lock fs-4 mb-2 text-warning opacity-50"></i>
                                <p class="mb-0">No internal staff notes posted on this ticket.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Reply Form Card -->
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-reply me-2 text-primary"></i>Post Reply or Internal Note</h5>
            <form action="{{ route('tickets.comments.store', $ticket->id) }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="commented_by" class="form-label fw-bold">Your Name / Identity <span class="text-danger">*</span></label>
                    <input type="text" name="commented_by" id="commented_by" class="form-control" placeholder="e.g. Agent John / Client Sarah" required value="{{ old('commented_by') }}">
                </div>

                <div class="mb-3">
                    <label for="comment" class="form-label fw-bold">Message Comment <span class="text-danger">*</span></label>
                    <textarea name="comment" id="comment" rows="4" class="form-control" placeholder="Write your reply or team memo here..." required>{{ old('comment') }}</textarea>
                </div>

                <div class="mb-3">
                    <div class="form-check form-switch bg-warning bg-opacity-10 border rounded-3 p-3 d-flex align-items-center justify-content-between">
                        <div class="ms-1">
                            <label class="form-check-label fw-bold text-warning-emphasis" for="is_internal_note">
                                <i class="fa-solid fa-user-lock me-1"></i> Post as Internal Note
                            </label>
                            <span class="d-block small text-muted">Internal notes are visible ONLY to team staff and hidden from clients.</span>
                        </div>
                        <input class="form-check-input me-1 fs-5" style="cursor: pointer;" type="checkbox" name="is_internal_note" id="is_internal_note" value="1">
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary px-4 rounded-pill fw-semibold"><i class="fa-regular fa-paper-plane me-1.5"></i>Post Message</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
