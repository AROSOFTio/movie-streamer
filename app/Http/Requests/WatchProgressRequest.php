<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WatchProgressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'watchable_type' => ['required', 'in:movie,episode'],
            'watchable_id' => ['required', 'integer'],
            'last_position_seconds' => ['required', 'integer', 'min:0'],
            'progress_percent' => ['required', 'integer', 'min:0', 'max:100'],
            'completed' => ['nullable', 'boolean'],
        ];
    }
}
