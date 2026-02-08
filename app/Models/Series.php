<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Series extends Model
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
        'language',
        'language_id',
        'country',
        'age_rating',
        'featured',
    ];

    protected $casts = [
        'featured' => 'boolean',
    ];

    public function episodes()
    {
        return $this->hasMany(Episode::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function vjs()
    {
        return $this->belongsToMany(Vj::class, 'series_vj');
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
