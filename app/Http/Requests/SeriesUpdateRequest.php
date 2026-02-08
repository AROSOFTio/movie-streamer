<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SeriesUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $seriesId = $this->route('series')?->id;

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:series,slug,'.$seriesId],
            'description' => ['nullable', 'string'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'language' => ['nullable', 'string', 'max:50'],
            'language_id' => ['nullable', 'integer', 'exists:languages,id'],
            'country' => ['nullable', 'string', 'max:50'],
            'age_rating' => ['nullable', 'string', 'max:20'],
            'featured' => ['nullable', 'boolean'],
            'poster' => ['nullable', 'image', 'max:5120'],
            'backdrop' => ['nullable', 'image', 'max:8192'],
            'vjs' => ['nullable', 'array'],
            'vjs.*' => ['integer', 'exists:vjs,id'],
        ];
    }
}
