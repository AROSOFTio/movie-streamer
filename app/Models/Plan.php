<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Plan extends Model
{
    use HasFactory;

    public const INTERVAL_MONTHLY = 'monthly';
    public const INTERVAL_QUARTERLY = 'quarterly';
    public const INTERVAL_YEARLY = 'yearly';
    public const INTERVAL_DAILY = 'daily';
    public const INTERVAL_WEEKLY = 'weekly';
    public const INTERVAL_BI_WEEKLY = 'bi-weekly';

    protected $fillable = [
        'name',
        'slug',
        'price',
        'currency',
        'interval',
        'interval_count',
        'description',
        'features',
        'is_active',
    ];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function durationInMonths(): int
    {
        if ($this->interval_count) {
            return (int) $this->interval_count;
        }

        return match ($this->interval) {
            self::INTERVAL_QUARTERLY => 3,
            self::INTERVAL_YEARLY => 12,
            default => 1,
        };
    }

    public function calculateEndsAt(Carbon $startsAt): Carbon
    {
        return match ($this->interval) {
            self::INTERVAL_DAILY => $startsAt->copy()->addDay(),
            self::INTERVAL_WEEKLY => $startsAt->copy()->addWeek(),
            self::INTERVAL_BI_WEEKLY => $startsAt->copy()->addWeeks(2),
            self::INTERVAL_QUARTERLY => $startsAt->copy()->addMonths(3),
            self::INTERVAL_YEARLY => $startsAt->copy()->addYear(),
            default => $startsAt->copy()->addMonth(),
        };
    }
}
