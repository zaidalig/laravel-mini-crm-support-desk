<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Company;
use App\Models\Client;
use App\Http\Requests\ProjectRequest;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the projects.
     */
    public function index(Request $request)
    {
        $query = Project::with(['company', 'client']);

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

        $projects = $query->latest()->paginate(10)->withQueryString();
        $companies = Company::orderBy('name')->get();

        return view('projects.index', compact('projects', 'companies'));
    }

    /**
     * Show the form for creating a new project.
     */
    public function create()
    {
        $companies = Company::where('status', 'active')->orderBy('name')->get();
        $clients = Client::where('status', 'active')->orderBy('name')->get();
        return view('projects.create', compact('companies', 'clients'));
    }

    /**
     * Store a newly created project in storage.
     */
    public function store(ProjectRequest $request)
    {
        $project = Project::create($request->validated());

        return redirect()->route('projects.index')
            ->with('success', "Project \"{$project->title}\" created successfully.");
    }

    /**
     * Display the specified project with its company, client, and tasks.
     */
    public function show(Project $project)
    {
        $project->load(['company', 'client', 'tasks.assignedStaff', 'teams.lead']);
        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified project.
     */
    public function edit(Project $project)
    {
        $companies = Company::orderBy('name')->get();
        $clients = Client::orderBy('name')->get();
        return view('projects.edit', compact('project', 'companies', 'clients'));
    }

    /**
     * Update the specified project in storage.
     */
    public function update(ProjectRequest $request, Project $project)
    {
        $project->update($request->validated());

        return redirect()->route('projects.show', $project->id)
            ->with('success', "Project \"{$project->title}\" updated successfully.");
    }

    /**
     * Remove the specified project from storage.
     */
    public function destroy(Project $project)
    {
        $title = $project->title;
        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', "Project \"{$title}\" deleted successfully.");
    }
}
