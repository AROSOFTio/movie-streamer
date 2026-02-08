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

    public function storeVideo(?UploadedFile $file, ?string $preferredName = null): ?string
    {
        if (! $file) {
            return null;
        }

        $base = $preferredName
            ? Str::slug(pathinfo($preferredName, PATHINFO_FILENAME), '_')
            : (string) Str::uuid();

        if ($base === '') {
            $base = (string) Str::uuid();
        }

        $extension = strtolower($file->getClientOriginalExtension() ?: 'mp4');
        $filename = $base.'-'.Str::random(8).'.'.$extension;

        return $file->storeAs('uploads', $filename, 'local');
    }
}
