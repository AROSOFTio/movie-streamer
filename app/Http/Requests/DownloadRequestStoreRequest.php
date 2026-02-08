<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DownloadRequestStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', 'in:movie,episode'],
            'id' => ['required', 'integer'],
            'reason' => ['nullable', 'string', 'max:500'],
        ];
    }
}
