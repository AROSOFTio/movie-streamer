<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Episode extends Model
{
    use HasFactory;

    protected $fillable = [
        'series_id',
        'title',
        'slug',
        'description',
        'season_number',
        'episode_number',
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
    ];

    protected $casts = [
        'featured' => 'boolean',
    ];

    public function series()
    {
        return $this->belongsTo(Series::class);
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'genre_episode');
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function castMembers()
    {
        return $this->belongsToMany(Cast::class, 'cast_episode')->withPivot('role_name');
    }

    public function vjs()
    {
        return $this->belongsToMany(Vj::class, 'episode_vj');
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
