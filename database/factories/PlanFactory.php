<?php

namespace Database\Factories;

use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Plan>
 */
class PlanFactory extends Factory
{
    protected $model = Plan::class;

    public function definition(): array
    {
        $name = fake()->unique()->word().' Plan';

        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'price' => fake()->numberBetween(1500, 29000),
            'currency' => 'UGX',
            'interval' => Plan::INTERVAL_WEEKLY,
            'interval_count' => 1,
            'description' => fake()->sentence(),
            'features' => ['HD streaming', 'Multiple devices'],
            'is_active' => true,
        ];
    }
}
