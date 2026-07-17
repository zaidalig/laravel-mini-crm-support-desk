<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Http\Requests\StaffRequest;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    /**
     * Display a listing of the staff.
     */
    public function index(Request $request)
    {
        $query = Staff::query();

        // Search by name, email, or role
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('role', 'like', '%' . $search . '%');
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        [$sort, $direction] = $this->tableSort($request, ['created_at', 'name', 'email', 'role']);
        $staffList = $query->orderBy($sort, $direction)->paginate($this->tablePerPage($request))->withQueryString();

        return view('staff.index', compact('staffList'));
    }

    /**
     * Show the form for creating a new staff member.
     */
    public function create()
    {
        return view('staff.create');
    }

    /**
     * Store a newly created staff member in storage.
     */
    public function store(StaffRequest $request)
    {
        $staff = Staff::create($request->validated());

        return redirect()->route('staff.index')
            ->with('success', "Staff member \"{$staff->name}\" added successfully.");
    }

    /**
     * Display the specified staff member.
     */
    public function show(Staff $staff)
    {
        return redirect()->route('staff.index');
    }

    /**
     * Show the form for editing the specified staff member.
     */
    public function edit(Staff $staff)
    {
        return view('staff.edit', compact('staff'));
    }

    /**
     * Update the specified staff member in storage.
     */
    public function update(StaffRequest $request, Staff $staff)
    {
        $staff->update($request->validated());

        return redirect()->route('staff.index')
            ->with('success', "Staff member \"{$staff->name}\" updated successfully.");
    }

    /**
     * Remove the specified staff member from storage.
     */
    public function destroy(Staff $staff)
    {
        $name = $staff->name;
        $staff->delete();

        return redirect()->route('staff.index')
            ->with('success', "Staff member \"{$name}\" deleted successfully.");
    }
}
