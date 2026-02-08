<?php

namespace App\Services\Media;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class MediaService
{
    public function storePoster(?UploadedFile $file): ?string
    {
        if (! $file) {
            return null;
        }

        return $file->store('posters', 'public');
    }

    public function storeBackdrop(?UploadedFile $file): ?string
    {
        if (! $file) {
            return null;
        }

        return $file->store('backdrops', 'public');
    }

    public function storeVideo(?UploadedFile $file): ?string
    {
        if (! $file) {
            return null;
        }

        $filename = Str::uuid().'.'.$file->getClientOriginalExtension();

        return $file->storeAs('uploads', $filename, 'local');
    }
}
