<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vj extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'language_id',
        'bio',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function movies()
    {
        return $this->belongsToMany(Movie::class, 'movie_vj');
    }

    public function series()
    {
        return $this->belongsToMany(Series::class, 'series_vj');
    }

    public function episodes()
    {
        return $this->belongsToMany(Episode::class, 'episode_vj');
    }
}
