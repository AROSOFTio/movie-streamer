<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MovieUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $movieId = $this->route('movie')?->id;

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:movies,slug,'.$movieId],
            'description' => ['nullable', 'string'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'duration' => ['nullable', 'integer', 'min:1'],
            'language' => ['nullable', 'string', 'max:50'],
            'language_id' => ['nullable', 'integer', 'exists:languages,id'],
            'country' => ['nullable', 'string', 'max:50'],
            'age_rating' => ['nullable', 'string', 'max:20'],
            'featured' => ['nullable', 'boolean'],
            'poster' => ['nullable', 'image', 'max:5120'],
            'backdrop' => ['nullable', 'image', 'max:8192'],
            'video' => ['nullable', 'file', 'mimetypes:video/mp4', 'max:512000'],
            'video_name' => ['nullable', 'string', 'max:255'],
            'video_quality' => ['nullable', 'string', 'in:360p,480p,720p,1080p,1440p,2160p,4k'],
            'genres' => ['nullable', 'array'],
            'genres.*' => ['integer', 'exists:genres,id'],
            'casts' => ['nullable', 'array'],
            'casts.*' => ['integer', 'exists:casts,id'],
            'vjs' => ['nullable', 'array'],
            'vjs.*' => ['integer', 'exists:vjs,id'],
        ];
    }
}
