@csrf

<div class="row g-3">
    <div class="col-12 col-md-6">
        <label for="name" class="form-label fw-bold">Name <span class="text-danger">*</span></label>
        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name ?? '') }}" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 col-md-6">
        <label for="email" class="form-label fw-bold">Email <span class="text-danger">*</span></label>
        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email ?? '') }}" required>
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 col-md-4">
        <label for="password" class="form-label fw-bold">Password {{ isset($user) ? '' : '*' }}</label>
        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" {{ isset($user) ? '' : 'required' }}>
        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        @isset($user)<div class="form-text">Leave blank to keep current password.</div>@endisset
    </div>

    <div class="col-12 col-md-4">
        <label for="role" class="form-label fw-bold">Role <span class="text-danger">*</span></label>
        <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
            @foreach(['owner' => 'Owner', 'manager' => 'Manager', 'support' => 'Support', 'viewer' => 'Viewer'] as $value => $label)
                <option value="{{ $value }}" {{ old('role', $user->role ?? 'support') === $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 col-md-4">
        <label for="status" class="form-label fw-bold">Status <span class="text-danger">*</span></label>
        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
            @foreach(['active' => 'Active', 'inactive' => 'Inactive', 'blocked' => 'Blocked'] as $value => $label)
                <option value="{{ $value }}" {{ old('status', $user->status ?? 'active') === $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="d-flex justify-content-end gap-2 border-top pt-3 mt-4">
    <a href="{{ route('users.index') }}" class="btn btn-light border px-4 rounded-pill">Cancel</a>
    <button type="submit" class="btn btn-primary px-4 rounded-pill fw-semibold">Save User</button>
</div>
