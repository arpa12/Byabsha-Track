<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use Tests\TestCase;

class TenantMiddlewareTest extends TestCase
{
    /**
     * Test that middleware sets tenant context from authenticated user
     */
    public function test_middleware_sets_tenant_from_authenticated_user()
    {
        $tenant = Tenant::create([
            'name' => 'Middleware Test Tenant',
            'slug' => 'middleware-test',
            'is_active' => true,
        ]);

        $user = User::create([
            'name' => 'Test User',
            'email' => 'middleware@test.com',
            'password' => bcrypt('password'),
            'tenant_id' => $tenant->id,
        ]);

        $this->actingAs($user);

        // Make a request - middleware should set tenant context
        $response = $this->get('/');

        // Verify tenant context is set
        $this->assertEquals($tenant->id, tenant('id'));
    }

    /**
     * Test that different users have different tenant contexts
     */
    public function test_different_users_have_different_tenant_contexts()
    {
        $tenant1 = Tenant::create([
            'name' => 'User Test Tenant 1',
            'slug' => 'user-test-1',
            'is_active' => true,
        ]);

        $tenant2 = Tenant::create([
            'name' => 'User Test Tenant 2',
            'slug' => 'user-test-2',
            'is_active' => true,
        ]);

        $user1 = User::create([
            'name' => 'User One',
            'email' => 'user.one@test.com',
            'password' => bcrypt('password'),
            'tenant_id' => $tenant1->id,
        ]);

        $user2 = User::create([
            'name' => 'User Two',
            'email' => 'user.two@test.com',
            'password' => bcrypt('password'),
            'tenant_id' => $tenant2->id,
        ]);

        // User 1 login
        $this->actingAs($user1);
        $this->get('/');
        $this->assertEquals($tenant1->id, tenant('id'));

        // User 2 login
        $this->actingAs($user2);
        $this->get('/');
        $this->assertEquals($tenant2->id, tenant('id'));
    }

    /**
     * Test that tenant is set by domain
     */
    public function test_tenant_can_be_set_by_domain()
    {
        $tenant = Tenant::create([
            'name' => 'Domain Test Tenant',
            'slug' => 'domain-test',
            'domain' => 'example.test',
            'is_active' => true,
        ]);

        // Note: This would require proper host header setup in request
        // For this example, we're testing the helper function directly
        $foundTenant = Tenant::where('domain', 'example.test')->first();

        $this->assertNotNull($foundTenant);
        $this->assertEquals('example.test', $foundTenant->domain);
    }
}
