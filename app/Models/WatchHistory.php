<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WatchHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'watchable_type',
        'watchable_id',
        'last_position_seconds',
        'progress_percent',
        'completed_at',
        'last_watched_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'last_watched_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function watchable()
    {
        return $this->morphTo();
    }
}
