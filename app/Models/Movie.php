<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'year',
        'rating',
        'poster_path',
        'backdrop_path',
        'duration',
        'language',
        'language_id',
        'country',
        'age_rating',
        'featured',
        'published_at',
    ];

    protected $casts = [
        'featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function castMembers()
    {
        return $this->belongsToMany(Cast::class, 'cast_movie')->withPivot('role_name');
    }

    public function vjs()
    {
        return $this->belongsToMany(Vj::class, 'movie_vj');
    }

    public function videoFiles()
    {
        return $this->morphMany(VideoFile::class, 'owner');
    }

    public function primaryVideo()
    {
        return $this->morphOne(VideoFile::class, 'owner')->where('is_primary', true);
    }

    public function watchHistories()
    {
        return $this->morphMany(WatchHistory::class, 'watchable');
    }

    public function downloadRequests()
    {
        return $this->morphMany(DownloadRequest::class, 'downloadable');
    }

    public function getPosterUrlAttribute(): ?string
    {
        return $this->resolveMediaUrl($this->poster_path);
    }

    public function getBackdropUrlAttribute(): ?string
    {
        return $this->resolveMediaUrl($this->backdrop_path);
    }

    public function getLanguageLabelAttribute(): ?string
    {
        $relatedLanguage = $this->relationLoaded('language') ? $this->getRelation('language') : null;
        if ($relatedLanguage instanceof Language) {
            return $relatedLanguage->name;
        }

        return $this->attributes['language'] ?? null;
    }

    protected function resolveMediaUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return Storage::disk('public')->url($path);
    }
}
