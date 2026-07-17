<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeamRequest;
use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        abort_unless($request->user()->canManageTeams(), 403);

        $query = Team::with('lead')->withCount(['users', 'projects']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        [$sort, $direction] = $this->tableSort($request, ['created_at', 'name']);
        $teams = $query->orderBy($sort, $direction)->paginate($this->tablePerPage($request))->withQueryString();

        return view('teams.index', compact('teams'));
    }

    public function create(Request $request)
    {
        abort_unless($request->user()->canManageTeams(), 403);

        return view('teams.create', [
            'users' => User::where('status', 'active')->orderBy('name')->get(),
            'projects' => Project::orderBy('title')->get(),
        ]);
    }

    public function store(TeamRequest $request)
    {
        $team = Team::create($request->safe()->except(['users', 'projects']));
        $team->users()->sync($this->memberPayload($request->input('users', []), $team->team_lead_id));
        $team->projects()->sync($request->input('projects', []));

        return redirect()->route('teams.show', $team)->with('success', "Team \"{$team->name}\" created successfully.");
    }

    public function show(Request $request, Team $team)
    {
        abort_unless($request->user()->canManageTeams(), 403);

        $team->load(['lead', 'users', 'projects.company']);

        return view('teams.show', compact('team'));
    }

    public function edit(Request $request, Team $team)
    {
        abort_unless($request->user()->canManageTeams(), 403);

        $team->load(['users', 'projects']);

        return view('teams.edit', [
            'team' => $team,
            'users' => User::orderBy('name')->get(),
            'projects' => Project::orderBy('title')->get(),
        ]);
    }

    public function update(TeamRequest $request, Team $team)
    {
        $team->update($request->safe()->except(['users', 'projects']));
        $team->users()->sync($this->memberPayload($request->input('users', []), $team->team_lead_id));
        $team->projects()->sync($request->input('projects', []));

        return redirect()->route('teams.show', $team)->with('success', "Team \"{$team->name}\" updated successfully.");
    }

    public function destroy(Request $request, Team $team)
    {
        abort_unless($request->user()->canManageTeams(), 403);

        $name = $team->name;
        $team->delete();

        return redirect()->route('teams.index')->with('success', "Team \"{$name}\" deleted successfully.");
    }

    private function memberPayload(array $userIds, ?int $leadId): array
    {
        if ($leadId) {
            $userIds[] = $leadId;
        }

        return collect($userIds)
            ->filter()
            ->unique()
            ->mapWithKeys(fn ($id) => [(int) $id => ['member_role' => (int) $id === (int) $leadId ? 'Team Lead' : 'Member']])
            ->all();
    }
}
