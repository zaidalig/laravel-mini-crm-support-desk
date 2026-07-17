<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        abort_unless($request->user()->canManageUsers(), 403);

        $query = User::withCount('teams');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        [$sort, $direction] = $this->tableSort($request, ['created_at', 'name', 'email', 'status']);
        $users = $query->orderBy($sort, $direction)->paginate($this->tablePerPage($request))->withQueryString();

        return view('users.index', compact('users'));
    }

    public function create(Request $request)
    {
        abort_unless($request->user()->canManageUsers(), 403);

        return view('users.create');
    }

    public function store(UserRequest $request)
    {
        $user = User::create($request->validated());

        return redirect()->route('users.index')->with('success', "User \"{$user->name}\" created successfully.");
    }

    public function edit(Request $request, User $user)
    {
        abort_unless($request->user()->canManageUsers(), 403);

        return view('users.edit', compact('user'));
    }

    public function update(UserRequest $request, User $user)
    {
        $data = $request->validated();

        if (blank($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', "User \"{$user->name}\" updated successfully.");
    }

    public function destroy(Request $request, User $user)
    {
        abort_unless($request->user()->canManageUsers(), 403);

        if ($request->user()->is($user)) {
            return redirect()->route('users.index')->with('error', 'You cannot delete your own account.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('users.index')->with('success', "User \"{$name}\" deleted successfully.");
    }

    public function block(Request $request, User $user)
    {
        abort_unless($request->user()->canManageUsers(), 403);

        if ($request->user()->is($user)) {
            return redirect()->route('users.index')->with('error', 'You cannot block your own account.');
        }

        $user->update(['status' => 'blocked']);

        return redirect()->route('users.index')->with('success', "User \"{$user->name}\" blocked successfully.");
    }

    public function unblock(Request $request, User $user)
    {
        abort_unless($request->user()->canManageUsers(), 403);

        $user->update(['status' => 'active']);

        return redirect()->route('users.index')->with('success', "User \"{$user->name}\" unblocked successfully.");
    }
}
