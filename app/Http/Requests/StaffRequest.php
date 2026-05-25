<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StaffRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $staffId = $this->route('staff') ? (is_object($this->route('staff')) ? $this->route('staff')->id : $this->route('staff')) : 'NULL';

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:staff,email,' . $staffId,
            'role' => 'required|string|max:100',
            'status' => 'required|in:active,inactive',
        ];
    }
}
