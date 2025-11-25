<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Allow all authenticated users (admin middleware handles access)
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'leader_employee_id' => ['required', 'exists:employees,id'],
            'is_pinned' => ['boolean'],
            'priority' => ['required', 'in:low,medium,high'],
            'deadline' => ['required', 'date', 'after_or_equal:today'],
            'status' => ['required', 'in:pending,in_progress,completed'],
            'employee_ids' => ['array'],
            'employee_ids.*' => ['exists:employees,id'],
        ];
    }
}
