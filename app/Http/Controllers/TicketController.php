<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Company;
use App\Models\Client;
use App\Models\Staff;
use App\Http\Requests\TicketRequest;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Display a listing of the tickets.
     */
    public function index(Request $request)
    {
        $query = Ticket::with(['company', 'client', 'assignedStaff']);

        // Search by title or description
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Filter by company
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->input('company_id'));
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->input('priority'));
        }

        $tickets = $query->latest()->paginate(10)->withQueryString();
        $companies = Company::orderBy('name')->get();

        return view('tickets.index', compact('tickets', 'companies'));
    }

    /**
     * Show the form for creating a new ticket.
     */
    public function create()
    {
        $companies = Company::where('status', 'active')->orderBy('name')->get();
        $clients = Client::where('status', 'active')->orderBy('name')->get();
        $staffList = Staff::where('status', 'active')->orderBy('name')->get();
        return view('tickets.create', compact('companies', 'clients', 'staffList'));
    }

    /**
     * Store a newly created ticket in storage.
     */
    public function store(TicketRequest $request)
    {
        $ticket = Ticket::create($request->validated());

        return redirect()->route('tickets.index')
            ->with('success', "Ticket #{$ticket->id} \"{$ticket->title}\" created successfully.");
    }

    /**
     * Display the specified ticket with company, client, staff, comments and tasks.
     */
    public function show(Ticket $ticket)
    {
        $ticket->load([
            'company',
            'client',
            'assignedStaff',
            'comments' => fn($q) => $q->latest(), // Load latest comments first
            'tasks'
        ]);

        return view('tickets.show', compact('ticket'));
    }

    /**
     * Show the form for editing the specified ticket.
     */
    public function edit(Ticket $ticket)
    {
        $companies = Company::orderBy('name')->get();
        $clients = Client::orderBy('name')->get();
        $staffList = Staff::orderBy('name')->get();
        return view('tickets.edit', compact('ticket', 'companies', 'clients', 'staffList'));
    }

    /**
     * Update the specified ticket in storage.
     */
    public function update(TicketRequest $request, Ticket $ticket)
    {
        $ticket->update($request->validated());

        return redirect()->route('tickets.show', $ticket->id)
            ->with('success', "Ticket #{$ticket->id} updated successfully.");
    }

    /**
     * Remove the specified ticket from storage.
     */
    public function destroy(Ticket $ticket)
    {
        $id = $ticket->id;
        $ticket->delete();

        return redirect()->route('tickets.index')
            ->with('success', "Ticket #{$id} deleted successfully.");
    }
}
