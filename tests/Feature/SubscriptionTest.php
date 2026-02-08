<?php

namespace Tests\Feature;

use App\Models\Movie;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use App\Models\VideoFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_without_subscription_cannot_watch(): void
    {
        $user = User::factory()->create();
        $movie = Movie::factory()->create();
        VideoFile::factory()->create([
            'owner_type' => Movie::class,
            'owner_id' => $movie->id,
        ]);

        $response = $this->actingAs($user)->get(route('watch.movie', $movie->slug));

        $response->assertStatus(200);
    }

    public function test_subscribed_user_can_watch(): void
    {
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
        ]);

        $response = $this->actingAs($user)->get(route('watch.movie', $movie->slug));

        $response->assertStatus(200);
    }

    public function test_user_blocked_after_free_time_used(): void
    {
        $user = User::factory()->create();
        $movie = Movie::factory()->create();
        VideoFile::factory()->create([
            'owner_type' => Movie::class,
            'owner_id' => $movie->id,
        ]);

        $key = 'free_watch_user_'.$user->id.'_'.now()->toDateString();
        cache()->put($key, 3600, 3600);

        $response = $this->actingAs($user)->get(route('watch.movie', $movie->slug));

        $response->assertRedirect(route('account'));
    }
}
