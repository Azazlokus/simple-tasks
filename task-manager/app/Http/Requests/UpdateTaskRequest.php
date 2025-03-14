<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\TaskStatus;

class UpdateTaskRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'       => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'status'      => ['sometimes', new Enum(TaskStatus::class)],
            'assigned_users'   => 'sometimes|array',
            'assigned_users.*' => 'exists:users,id'
        ];
    }

    public function messages(): array
    {
        return [
            'title.string'       => 'The title must be a string.',
            'title.max'          => 'The title must not exceed 255 characters.',
            'description.string' => 'The description must be a string.',
            'status.enum'        => 'The selected status is invalid.',
            'assigned_users.array'  => 'The list of assigned users must be an array.',
            'assigned_users.*.exists' => 'The specified user does not exist.',
        ];
    }
}