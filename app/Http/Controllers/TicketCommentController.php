<?php

namespace App\Http\Controllers;

use App\Models\TicketComment;
use Illuminate\Http\Request;

class TicketCommentController extends Controller
{
    /**
     * Store a newly created ticket comment in storage.
     */
    public function store(Request $request, $ticketId)
    {
        $request->validate([
            'comment' => 'required|string',
            'commented_by' => 'required|string|max:255',
            'is_internal_note' => 'nullable|boolean',
        ]);

        TicketComment::create([
            'ticket_id' => $ticketId,
            'comment' => $request->input('comment'),
            'commented_by' => $request->input('commented_by'),
            'is_internal_note' => $request->has('is_internal_note') ? (bool)$request->input('is_internal_note') : false,
        ]);

        return redirect()->route('tickets.show', $ticketId)
            ->with('success', 'Comment added successfully.');
    }
}
