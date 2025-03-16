<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\TaskStatus;
use Illuminate\Validation\Rules\Enum;

class StoreTaskRequest extends FormRequest
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
        return [
            'title' => 'string|max:255',
            'description' => 'nullable|string',
            'status' => [new Enum(TaskStatus::class)],
            'assigned_users' => 'array',
            'assigned_users.*' => 'exists:users,id'
        ];
    }

    public function messages(): array
    {
        return [
            'title.max' => 'The title must not exceed 255 characters.',
            'status.in' => 'Invalid status provided.',
            'assigned_users.array' => 'Assigned users must be an array.',
            'assigned_users.*.exists' => 'Some assigned users do not exist in the system.'
        ];
    }
}
