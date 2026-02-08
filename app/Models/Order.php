<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_PAID = 'paid';
    public const STATUS_FAILED = 'failed';
    public const TYPE_SUBSCRIPTION = 'subscription';
    public const TYPE_DOWNLOAD = 'download';

    protected $fillable = [
        'user_id',
        'plan_id',
        'amount',
        'currency',
        'status',
        'provider',
        'provider_reference',
        'checkout_url',
        'type',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
