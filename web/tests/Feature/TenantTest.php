<?php

namespace Tests\Feature;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_tenant(): void
    {
        $tenant = Tenant::factory()->create([
            'name' => 'John Doe',
            'phone' => '081234567890',
        ]);

        $this->assertDatabaseHas('tenants', [
            'name' => 'John Doe',
            'phone' => '081234567890',
        ]);
    }

    public function test_tenant_has_stays_relationship(): void
    {
        $tenant = Tenant::factory()->create();

        $this->assertInstanceOf(Collection::class, $tenant->stays);
    }

    public function test_tenant_has_payments_relationship(): void
    {
        $tenant = Tenant::factory()->create();

        $this->assertInstanceOf(Collection::class, $tenant->payments);
    }
}
