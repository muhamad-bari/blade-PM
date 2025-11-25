<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'leader_employee_id' => ['required', 'exists:employees,id'],
            'is_pinned' => ['boolean'],
            'priority' => ['required', 'in:low,medium,high'],
            'deadline' => ['required', 'date'], // Allow past dates on update? Or keep strict.
            'status' => ['required', 'in:pending,in_progress,completed'],
            'employee_ids' => ['array'],
            'employee_ids.*' => ['exists:employees,id'],
        ];
    }
}
