<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Cast extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'bio',
        'photo_path',
    ];

    public function movies()
    {
        return $this->belongsToMany(Movie::class, 'cast_movie')->withPivot('role_name');
    }

    public function episodes()
    {
        return $this->belongsToMany(Episode::class, 'cast_episode')->withPivot('role_name');
    }

    public function getPhotoUrlAttribute(): ?string
    {
        if (! $this->photo_path) {
            return null;
        }

        if (str_starts_with($this->photo_path, 'http://') || str_starts_with($this->photo_path, 'https://')) {
            return $this->photo_path;
        }

        return Storage::disk('public')->url($this->photo_path);
    }
}
