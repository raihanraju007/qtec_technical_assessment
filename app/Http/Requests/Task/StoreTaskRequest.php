<?php

namespace App\Http\Requests\Task;

use App\Enums\TaskEnums;
use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Traits\SnakeCaseValidatedDataTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends FormRequest
{
    use SnakeCaseValidatedDataTrait;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:'.TaskEnums::TITLE_MAX_LENGTH],
            'description' => ['nullable', 'string', 'max:'.TaskEnums::DESCRIPTION_MAX_LENGTH],
            'status' => ['sometimes', 'integer', Rule::enum(TaskStatus::class)],
            'priority' => ['sometimes', 'integer', Rule::enum(TaskPriority::class)],
            'dueDate' => ['nullable', 'date'],
        ];
    }
}
