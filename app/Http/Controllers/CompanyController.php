<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Http\Requests\CompanyRequest;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the companies.
     */
    public function index(Request $request)
    {
        $query = Company::query();

        // Search by name, email, industry, or city
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('industry', 'like', '%' . $search . '%')
                  ->orWhere('city', 'like', '%' . $search . '%');
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        [$sort, $direction] = $this->tableSort($request, ['created_at', 'name', 'status']);
        $companies = $query->orderBy($sort, $direction)->paginate($this->tablePerPage($request))->withQueryString();

        return view('companies.index', compact('companies'));
    }

    /**
     * Show the form for creating a new company.
     */
    public function create()
    {
        return view('companies.create');
    }

    /**
     * Store a newly created company in storage.
     */
    public function store(CompanyRequest $request)
    {
        $company = Company::create($request->validated());

        return redirect()->route('companies.index')
            ->with('success', "Company \"{$company->name}\" created successfully.");
    }

    /**
     * Display the specified company with its clients, projects, and tickets.
     */
    public function show(Company $company)
    {
        $company->load([
            'clients' => fn($q) => $q->latest(),
            'projects' => fn($q) => $q->latest(),
            'tickets' => fn($q) => $q->latest()
        ]);

        return view('companies.show', compact('company'));
    }

    /**
     * Show the form for editing the specified company.
     */
    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

    /**
     * Update the specified company in storage.
     */
    public function update(CompanyRequest $request, Company $company)
    {
        $company->update($request->validated());

        return redirect()->route('companies.show', $company->id)
            ->with('success', "Company \"{$company->name}\" updated successfully.");
    }

    /**
     * Remove the specified company from storage.
     */
    public function destroy(Company $company)
    {
        $name = $company->name;
        $company->delete();

        return redirect()->route('companies.index')
            ->with('success', "Company \"{$name}\" deleted successfully.");
    }
}
