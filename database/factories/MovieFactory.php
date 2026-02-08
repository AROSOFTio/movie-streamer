<?php

namespace Database\Factories;

use App\Models\Movie;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Movie>
 */
class MovieFactory extends Factory
{
    protected $model = Movie::class;

    public function definition(): array
    {
        $title = fake()->unique()->sentence(3);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => fake()->paragraph(),
            'year' => fake()->numberBetween(2000, 2025),
            'rating' => fake()->randomFloat(1, 6, 9.5),
            'duration' => fake()->numberBetween(80, 150),
            'language' => 'English',
            'country' => 'USA',
            'age_rating' => 'PG-13',
            'featured' => fake()->boolean(20),
        ];
    }
}
