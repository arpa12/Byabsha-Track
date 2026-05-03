<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use Modules\Shop\Models\Shop;
use Tests\TestCase;

class MultiTenantIsolationTest extends TestCase
{
    /**
     * Test that queries are automatically filtered by tenant
     */
    public function test_products_are_filtered_by_tenant()
    {
        // Create two tenants
        $tenant1 = Tenant::create([
            'name' => 'Tenant One',
            'slug' => 'tenant-one',
            'is_active' => true,
        ]);

        $tenant2 = Tenant::create([
            'name' => 'Tenant Two',
            'slug' => 'tenant-two',
            'is_active' => true,
        ]);

        // Create shops for each tenant
        tenancy()->initialize($tenant1);
        $shop1 = Shop::create([
            'name' => 'Shop One',
            'location' => 'Location 1',
        ]);

        tenancy()->initialize($tenant2);
        $shop2 = Shop::create([
            'name' => 'Shop Two',
            'location' => 'Location 2',
        ]);

        // Test that each tenant only sees their own data
        tenancy()->initialize($tenant1);
        $this->assertCount(1, Shop::all());
        $this->assertTrue(Shop::first()->is($shop1));

        tenancy()->initialize($tenant2);
        $this->assertCount(1, Shop::all());
        $this->assertTrue(Shop::first()->is($shop2));
    }

    /**
     * Test that new records automatically get tenant_id
     */
    public function test_new_records_automatically_get_tenant_id()
    {
        $tenant = Tenant::create([
            'name' => 'Test Tenant',
            'slug' => 'test-tenant',
            'is_active' => true,
        ]);

        tenancy()->initialize($tenant);
        $shop = Shop::create([
            'name' => 'Auto Tenant Shop',
            'location' => 'Test Location',
        ]);

        $this->assertEquals($tenant->id, $shop->tenant_id);
    }

    /**
     * Test that users belong to tenants
     */
    public function test_user_belongs_to_tenant()
    {
        $tenant = Tenant::create([
            'name' => 'User Test Tenant',
            'slug' => 'user-test-tenant',
            'is_active' => true,
        ]);

        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'tenant_id' => $tenant->id,
        ]);

        $this->assertEquals($tenant->id, $user->tenant_id);
        $this->assertTrue($user->tenant->is($tenant));
    }

    /**
     * Test that tenant has many users
     */
    public function test_tenant_has_many_users()
    {
        $tenant = Tenant::create([
            'name' => 'Multi User Tenant',
            'slug' => 'multi-user-tenant',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'User One',
            'email' => 'user1@example.com',
            'password' => bcrypt('password'),
            'tenant_id' => $tenant->id,
        ]);

        User::create([
            'name' => 'User Two',
            'email' => 'user2@example.com',
            'password' => bcrypt('password'),
            'tenant_id' => $tenant->id,
        ]);

        $this->assertCount(2, $tenant->users);
    }

    /**
     * Test withoutGlobalScopes allows querying all data
     */
    public function test_without_global_scopes_bypasses_tenant_filter()
    {
        $tenant1 = Tenant::create([
            'name' => 'Scope Test One',
            'slug' => 'scope-test-one',
            'is_active' => true,
        ]);

        $tenant2 = Tenant::create([
            'name' => 'Scope Test Two',
            'slug' => 'scope-test-two',
            'is_active' => true,
        ]);

        tenancy()->initialize($tenant1);
        Shop::create([
            'name' => 'Shop A',
            'location' => 'Location A',
        ]);

        tenancy()->initialize($tenant2);
        Shop::create([
            'name' => 'Shop B',
            'location' => 'Location B',
        ]);

        // With tenant filter
        tenancy()->initialize($tenant1);
        $this->assertCount(1, Shop::all());

        // Without tenant filter
        $this->assertCount(2, Shop::withoutGlobalScopes()->get());
    }

    /**
     * Test that data can be queried by specific tenant_id when bypassing scope
     */
    public function test_can_query_specific_tenant_data()
    {
        $tenant = Tenant::create([
            'name' => 'Query Test Tenant',
            'slug' => 'query-test-tenant',
            'is_active' => true,
        ]);

        tenancy()->initialize($tenant);
        $shop = Shop::create([
            'name' => 'Query Test Shop',
            'location' => 'Test',
        ]);

        // Query all tenant's shops
        tenancy()->forget();
        $tenantShops = Shop::withoutGlobalScopes()
            ->where('tenant_id', $tenant->id)
            ->get();

        $this->assertCount(1, $tenantShops);
        $this->assertTrue($tenantShops->first()->is($shop));
    }
}
