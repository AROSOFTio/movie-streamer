<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'disk',
        'path',
        'type',
        'quality',
        'duration_seconds',
        'size_bytes',
        'is_primary',
        'meta',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'meta' => 'array',
    ];

    public function owner()
    {
        return $this->morphTo();
    }
}
