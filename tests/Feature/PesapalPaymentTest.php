<?php

namespace Tests\Feature;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PesapalPaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_mock_payment_activates_subscription(): void
    {
        config(['payments.pesapal.mock' => true]);

        $plan = Plan::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/checkout', [
            'plan_id' => $plan->id,
        ]);

        $response->assertRedirect();

        $followed = $this->followRedirects($response);
        $followed->assertStatus(200);

        $this->assertDatabaseHas('subscriptions', [
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'status' => 'active',
        ]);
    }
}
