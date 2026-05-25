<div class="comment-bubble {{ $comment->is_internal_note ? 'internal' : '' }}">
    <div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-1">
        <div class="d-flex align-items-center gap-1.5 flex-wrap">
            <span class="fw-bold text-dark">{{ $comment->commented_by }}</span>
            @if($comment->is_internal_note)
                <span class="badge bg-warning text-warning-emphasis border border-warning border-opacity-25 px-2 py-0.5 rounded-pill" style="font-size: 0.75rem;">
                    <i class="fa-solid fa-user-lock me-1"></i>Internal Note
                </span>
            @else
                <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-0.5 rounded-pill" style="font-size: 0.75rem;">
                    Public Reply
                </span>
            @endif
        </div>
        <span class="text-muted small" style="font-size: 0.8rem;">
            <i class="fa-regular fa-clock me-1"></i>{{ $comment->created_at->diffForHumans() }}
        </span>
    </div>
    <p class="mb-0 text-dark" style="white-space: pre-line; line-height: 1.5; font-size: 0.95rem;">{{ $comment->comment }}</p>
</div>
