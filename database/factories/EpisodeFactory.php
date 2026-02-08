<?php

namespace Database\Factories;

use App\Models\Episode;
use App\Models\Series;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Episode>
 */
class EpisodeFactory extends Factory
{
    protected $model = Episode::class;

    public function definition(): array
    {
        $title = fake()->unique()->sentence(3);

        return [
            'series_id' => Series::factory(),
            'title' => $title,
            'slug' => Str::slug($title.'-'.fake()->unique()->numberBetween(1, 50)),
            'description' => fake()->paragraph(),
            'season_number' => 1,
            'episode_number' => fake()->numberBetween(1, 12),
            'year' => fake()->numberBetween(2015, 2025),
            'rating' => fake()->randomFloat(1, 6, 9.5),
            'duration' => fake()->numberBetween(40, 60),
            'language' => 'English',
            'country' => 'USA',
            'age_rating' => 'TV-14',
            'featured' => fake()->boolean(20),
        ];
    }
}
