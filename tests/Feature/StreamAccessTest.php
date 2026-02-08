<?php

namespace Tests\Feature;

use App\Models\Movie;
use App\Models\Plan;
use App\Models\StreamToken;
use App\Models\Subscription;
use App\Models\User;
use App\Models\VideoFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StreamAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_stream_token_expires_and_cannot_be_reused(): void
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
        $video = VideoFile::factory()->create([
            'owner_type' => Movie::class,
            'owner_id' => $movie->id,
            'path' => 'uploads/test.mp4',
        ]);

        $token = StreamToken::create([
            'user_id' => $user->id,
            'video_file_id' => $video->id,
            'token' => 'testtoken',
            'expires_at' => now()->addMinutes(5),
            'uses_remaining' => 1,
        ]);

        $first = $this->actingAs($user)->get(route('stream', ['token' => $token->token]));
        $first->assertStatus(200);

        $second = $this->actingAs($user)->get(route('stream', ['token' => $token->token]));
        $second->assertStatus(403);

        $expired = StreamToken::create([
            'user_id' => $user->id,
            'video_file_id' => $video->id,
            'token' => 'expiredtoken',
            'expires_at' => now()->subMinutes(1),
            'uses_remaining' => 1,
        ]);

        $expiredResponse = $this->actingAs($user)->get(route('stream', ['token' => $expired->token]));
        $expiredResponse->assertStatus(403);
    }
}
