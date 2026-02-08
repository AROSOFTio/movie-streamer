<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DownloadToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'download_request_id',
        'token',
        'expires_at',
        'uses_remaining',
        'used_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function downloadRequest()
    {
        return $this->belongsTo(DownloadRequest::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function canBeUsed(): bool
    {
        return ! $this->isExpired() && $this->uses_remaining > 0;
    }
}
