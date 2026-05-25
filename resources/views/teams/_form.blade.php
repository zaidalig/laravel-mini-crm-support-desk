@csrf

<div class="row g-3">
    <div class="col-12 col-md-6">
        <label for="name" class="form-label fw-bold">Team Name <span class="text-danger">*</span></label>
        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $team->name ?? '') }}" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 col-md-3">
        <label for="team_lead_id" class="form-label fw-bold">Team Lead</label>
        <select name="team_lead_id" id="team_lead_id" class="form-select @error('team_lead_id') is-invalid @enderror">
            <option value="">No Lead</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" {{ (int) old('team_lead_id', $team->team_lead_id ?? 0) === $user->id ? 'selected' : '' }}>
                    {{ $user->name }} ({{ ucfirst($user->role) }})
                </option>
            @endforeach
        </select>
        @error('team_lead_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 col-md-3">
        <label for="status" class="form-label fw-bold">Status <span class="text-danger">*</span></label>
        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
            <option value="active" {{ old('status', $team->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status', $team->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label for="description" class="form-label fw-bold">Description</label>
        <textarea name="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $team->description ?? '') }}</textarea>
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="row g-4 mt-1">
    <div class="col-12 col-lg-6">
        <div class="border rounded-3 p-3 h-100">
            <h6 class="fw-bold mb-3"><i class="fa-solid fa-users me-1 text-primary"></i> Team Members</h6>
            <div class="row g-2">
                @foreach($users as $user)
                    <div class="col-12 col-md-6">
                        <label class="form-check border rounded-3 p-2 h-100">
                            <input class="form-check-input ms-0 me-2" type="checkbox" name="users[]" value="{{ $user->id }}"
                                {{ in_array($user->id, old('users', isset($team) ? $team->users->pluck('id')->all() : []), true) ? 'checked' : '' }}>
                            <span class="form-check-label">
                                <span class="fw-semibold">{{ $user->name }}</span>
                                <span class="d-block text-muted small">{{ $user->email }}</span>
                            </span>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6">
        <div class="border rounded-3 p-3 h-100">
            <h6 class="fw-bold mb-3"><i class="fa-solid fa-briefcase me-1 text-success"></i> Assigned Projects</h6>
            <div class="row g-2">
                @foreach($projects as $project)
                    <div class="col-12">
                        <label class="form-check border rounded-3 p-2">
                            <input class="form-check-input ms-0 me-2" type="checkbox" name="projects[]" value="{{ $project->id }}"
                                {{ in_array($project->id, old('projects', isset($team) ? $team->projects->pluck('id')->all() : []), true) ? 'checked' : '' }}>
                            <span class="form-check-label">
                                <span class="fw-semibold">{{ $project->title }}</span>
                                <span class="text-muted small">- {{ ucfirst(str_replace('_', ' ', $project->status)) }}</span>
                            </span>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-end gap-2 border-top pt-3 mt-4">
    <a href="{{ route('teams.index') }}" class="btn btn-light border px-4 rounded-pill">Cancel</a>
    <button type="submit" class="btn btn-primary px-4 rounded-pill fw-semibold">Save Team</button>
</div>
