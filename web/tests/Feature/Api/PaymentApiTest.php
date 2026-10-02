<?php

namespace Tests\Feature\Api;

use App\Models\Payment;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PaymentApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        Sanctum::actingAs($user);
    }

    public function test_can_list_payments(): void
    {
        $tenant = Tenant::factory()->create();
        Payment::factory()->count(3)->create(['tenant_id' => $tenant->id]);

        $response = $this->getJson('/api/v1/payments');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'tenant_id', 'amount', 'payment_date'],
            ],
        ]);
    }

    public function test_can_create_payment(): void
    {
        $tenant = Tenant::factory()->create();

        $paymentData = [
            'tenant_id' => $tenant->id,
            'period_start' => '2024-01-01',
            'period_end' => '2024-01-31',
            'payment_date' => '2024-01-05',
            'amount' => 1500000,
            'payment_method' => 'cash',
            'notes' => 'Test payment',
        ];

        $response = $this->postJson('/api/v1/payments', $paymentData);

        $response->assertStatus(201);
        $response->assertJsonFragment(['amount' => 1500000]);
    }
}
