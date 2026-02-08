<?php

namespace Database\Factories;

use App\Models\DownloadRequest;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DownloadRequest>
 */
class DownloadRequestFactory extends Factory
{
    protected $model = DownloadRequest::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'downloadable_type' => Movie::class,
            'downloadable_id' => Movie::factory(),
            'status' => DownloadRequest::STATUS_PENDING,
            'download_count' => 0,
        ];
    }
}
