<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Company;
use App\Http\Requests\ClientRequest;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the clients.
     */
    public function index(Request $request)
    {
        $query = Client::with('company');

        // Search by name, email, designation
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('designation', 'like', '%' . $search . '%');
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

        $clients = $query->latest()->paginate(10)->withQueryString();
        $companies = Company::orderBy('name')->get();

        return view('clients.index', compact('clients', 'companies'));
    }

    /**
     * Show the form for creating a new client.
     */
    public function create()
    {
        $companies = Company::orderBy('name')->get();
        return view('clients.create', compact('companies'));
    }

    /**
     * Store a newly created client in storage.
     */
    public function store(ClientRequest $request)
    {
        $client = Client::create($request->validated());

        return redirect()->route('clients.index')
            ->with('success', "Client \"{$client->name}\" added successfully.");
    }

    /**
     * Display the specified client. (We can just show them on list or detail, let's redirect to index or show a simple detail, redirecting to edit/index is standard, but let's provide a simple show or redirect)
     */
    public function show(Client $client)
    {
        return redirect()->route('clients.index');
    }

    /**
     * Show the form for editing the specified client.
     */
    public function edit(Client $client)
    {
        $companies = Company::orderBy('name')->get();
        return view('clients.edit', compact('client', 'companies'));
    }

    /**
     * Update the specified client in storage.
     */
    public function update(ClientRequest $request, Client $client)
    {
        $client->update($request->validated());

        return redirect()->route('clients.index')
            ->with('success', "Client \"{$client->name}\" updated successfully.");
    }

    /**
     * Remove the specified client from storage.
     */
    public function destroy(Client $client)
    {
        $name = $client->name;
        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', "Client \"{$name}\" deleted successfully.");
    }
}
