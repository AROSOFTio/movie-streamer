<?php

namespace Tests\Feature;

use App\Models\DownloadRequest;
use App\Models\DownloadToken;
use App\Models\Movie;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use App\Models\VideoFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DownloadAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_download_without_approved_request(): void
    {
        Storage::disk('local')->put('uploads/test.mp4', 'dummy');

        $user = User::factory()->create();
        $plan = Plan::factory()->create();
        Subscription::factory()->create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'ends_at' => now()->addDays(10),
        ]);

        $movie = Movie::factory()->create();
        VideoFile::factory()->create([
            'owner_type' => Movie::class,
            'owner_id' => $movie->id,
            'path' => 'uploads/test.mp4',
        ]);

        $request = DownloadRequest::factory()->create([
            'user_id' => $user->id,
            'downloadable_type' => Movie::class,
            'downloadable_id' => $movie->id,
            'status' => DownloadRequest::STATUS_PENDING,
        ]);

        $token = DownloadToken::create([
            'user_id' => $user->id,
            'download_request_id' => $request->id,
            'token' => 'downloadtoken',
            'expires_at' => now()->addMinutes(5),
            'uses_remaining' => 1,
        ]);

        $response = $this->actingAs($user)->get(route('downloads.download', ['token' => $token->token]));
        $response->assertStatus(403);
    }
}
