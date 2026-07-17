<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\Ticket;
use App\Models\Staff;
use App\Http\Requests\TaskRequest;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the tasks.
     */
    public function index(Request $request)
    {
        $query = Task::with(['project', 'ticket', 'assignedStaff']);

        // Search by title or description
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->input('priority'));
        }

        [$sort, $direction] = $this->tableSort($request, ['created_at', 'title', 'priority', 'status', 'due_date']);
        $tasks = $query->orderBy($sort, $direction)->paginate($this->tablePerPage($request))->withQueryString();

        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new task.
     */
    public function create()
    {
        $projects = Project::orderBy('title')->get();
        $tickets = Ticket::orderBy('title')->get();
        $staffList = Staff::where('status', 'active')->orderBy('name')->get();
        return view('tasks.create', compact('projects', 'tickets', 'staffList'));
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(TaskRequest $request)
    {
        $task = Task::create($request->validated());

        return redirect()->route('tasks.index')
            ->with('success', "Task \"{$task->title}\" created successfully.");
    }

    /**
     * Display the specified task.
     */
    public function show(Task $task)
    {
        return redirect()->route('tasks.index');
    }

    /**
     * Show the form for editing the specified task.
     */
    public function edit(Task $task)
    {
        $projects = Project::orderBy('title')->get();
        $tickets = Ticket::orderBy('title')->get();
        $staffList = Staff::orderBy('name')->get();
        return view('tasks.edit', compact('task', 'projects', 'tickets', 'staffList'));
    }

    /**
     * Update the specified task in storage.
     */
    public function update(TaskRequest $request, Task $task)
    {
        $task->update($request->validated());

        return redirect()->route('tasks.index')
            ->with('success', "Task \"{$task->title}\" updated successfully.");
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy(Task $task)
    {
        $title = $task->title;
        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', "Task \"{$title}\" deleted successfully.");
    }
}
