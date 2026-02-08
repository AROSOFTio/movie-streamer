<?php

namespace Database\Factories;

use App\Models\Series;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Series>
 */
class SeriesFactory extends Factory
{
    protected $model = Series::class;

    public function definition(): array
    {
        $title = fake()->unique()->sentence(3);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => fake()->paragraph(),
            'year' => fake()->numberBetween(2015, 2025),
            'rating' => fake()->randomFloat(1, 6, 9.5),
            'language' => 'English',
            'country' => 'USA',
            'age_rating' => 'TV-14',
            'featured' => fake()->boolean(20),
        ];
    }
}
