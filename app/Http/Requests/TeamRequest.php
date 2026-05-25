<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canManageTeams() ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'team_lead_id' => 'nullable|exists:users,id',
            'status' => 'required|in:active,inactive',
            'users' => 'nullable|array',
            'users.*' => 'exists:users,id',
            'projects' => 'nullable|array',
            'projects.*' => 'exists:projects,id',
        ];
    }
}
