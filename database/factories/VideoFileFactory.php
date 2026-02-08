<?php

namespace Database\Factories;

use App\Models\Movie;
use App\Models\VideoFile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VideoFile>
 */
class VideoFileFactory extends Factory
{
    protected $model = VideoFile::class;

    public function definition(): array
    {
        return [
            'owner_type' => Movie::class,
            'owner_id' => Movie::factory(),
            'disk' => 'local',
            'path' => 'uploads/demo.mp4',
            'type' => 'mp4',
            'duration_seconds' => 120,
            'size_bytes' => 1024,
            'is_primary' => true,
        ];
    }
}
