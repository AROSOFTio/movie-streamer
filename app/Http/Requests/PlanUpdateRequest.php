<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlanUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $planId = $this->route('plan')?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:plans,slug,'.$planId],
            'price' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'max:10'],
            'interval' => ['required', 'in:daily,weekly,bi-weekly,monthly,quarterly,yearly'],
            'interval_count' => ['nullable', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
            'features' => ['nullable'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
