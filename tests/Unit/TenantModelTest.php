<?php

namespace Tests\Unit;

use App\Models\Tenant;
use App\Models\User;
use App\Support\TenantManager;
use Tests\TestCase;

class TenantModelTest extends TestCase
{
    /**
     * Test that Tenant model has correct table
     */
    public function test_tenant_model_has_correct_table()
    {
        $tenant = new Tenant();
        $this->assertEquals('tenants', $tenant->getTable());
    }

    /**
     * Test that Tenant fillable attributes are set correctly
     */
    public function test_tenant_fillable_attributes()
    {
        $tenant = Tenant::create([
            'name' => 'Test Company',
            'slug' => 'test-company',
            'domain' => 'test.example.com',
            'email' => 'contact@test.com',
            'description' => 'A test company',
            'is_active' => true,
        ]);

        $this->assertEquals('Test Company', $tenant->name);
        $this->assertEquals('test-company', $tenant->slug);
        $this->assertEquals('test.example.com', $tenant->domain);
        $this->assertEquals('contact@test.com', $tenant->email);
        $this->assertEquals('A test company', $tenant->description);
        $this->assertTrue($tenant->is_active);
    }

    /**
     * Test that Tenant uses soft deletes
     */
    public function test_tenant_uses_soft_deletes()
    {
        $tenant = Tenant::create([
            'name' => 'Soft Delete Test',
            'slug' => 'soft-delete-test',
        ]);

        $tenantId = $tenant->id;
        $tenant->delete();

        $this->assertNull(Tenant::find($tenantId));
        $this->assertNotNull(Tenant::withTrashed()->find($tenantId));
    }

    /**
     * Test that Tenant name must be unique
     */
    public function test_tenant_name_can_be_created_multiple_times()
    {
        Tenant::create([
            'name' => 'Unique Name',
            'slug' => 'unique-name-1',
        ]);

        // Names don't have unique constraint, only slug does
        Tenant::create([
            'name' => 'Unique Name',
            'slug' => 'unique-name-2',
        ]);

        $this->assertEquals(2, Tenant::where('name', 'Unique Name')->count());
    }

    /**
     * Test that Tenant slug must be unique
     */
    public function test_tenant_slug_must_be_unique()
    {
        Tenant::create([
            'name' => 'First Tenant',
            'slug' => 'unique-slug',
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        Tenant::create([
            'name' => 'Second Tenant',
            'slug' => 'unique-slug',
        ]);
    }

    /**
     * Test TenantManager helper functions
     */
    public function test_tenant_manager_get_active_tenants()
    {
        Tenant::create([
            'name' => 'Active Tenant',
            'slug' => 'active-tenant',
            'is_active' => true,
        ]);

        Tenant::create([
            'name' => 'Inactive Tenant',
            'slug' => 'inactive-tenant',
            'is_active' => false,
        ]);

        $activeTenants = TenantManager::getActiveTenants();
        $this->assertTrue($activeTenants->contains('slug', 'active-tenant'));
        $this->assertFalse($activeTenants->contains('slug', 'inactive-tenant'));
    }

    /**
     * Test TenantManager get by slug
     */
    public function test_tenant_manager_get_by_slug()
    {
        $tenant = Tenant::create([
            'name' => 'Slug Test',
            'slug' => 'slug-test',
            'is_active' => true,
        ]);

        $found = TenantManager::getTenantBySlug('slug-test');
        $this->assertTrue($found->is($tenant));
    }

    /**
     * Test TenantManager get by domain
     */
    public function test_tenant_manager_get_by_domain()
    {
        $tenant = Tenant::create([
            'name' => 'Domain Test',
            'slug' => 'domain-test',
            'domain' => 'domain.test.com',
            'is_active' => true,
        ]);

        $found = TenantManager::getTenantByDomain('domain.test.com');
        $this->assertTrue($found->is($tenant));
    }

    /**
     * Test TenantManager deactivate tenant
     */
    public function test_tenant_manager_deactivate_tenant()
    {
        $tenant = Tenant::create([
            'name' => 'Deactivate Test',
            'slug' => 'deactivate-test',
            'is_active' => true,
        ]);

        TenantManager::deactivateTenant($tenant);
        $this->assertFalse($tenant->fresh()->is_active);
    }

    /**
     * Test TenantManager reactivate tenant
     */
    public function test_tenant_manager_reactivate_tenant()
    {
        $tenant = Tenant::create([
            'name' => 'Reactivate Test',
            'slug' => 'reactivate-test',
            'is_active' => false,
        ]);

        TenantManager::reactivateTenant($tenant);
        $this->assertTrue($tenant->fresh()->is_active);
    }

    /**
     * Test user belongs to tenant
     */
    public function test_user_belongs_to_tenant()
    {
        $tenant = Tenant::create([
            'name' => 'User Tenant Test',
            'slug' => 'user-tenant-test',
        ]);

        $user = User::create([
            'name' => 'Test User',
            'email' => 'testuser@test.com',
            'password' => bcrypt('password'),
            'tenant_id' => $tenant->id,
        ]);

        $this->assertTrue($user->tenant->is($tenant));
    }

    /**
     * Test tenant has many users
     */
    public function test_tenant_has_many_users()
    {
        $tenant = Tenant::create([
            'name' => 'Multi Users Test',
            'slug' => 'multi-users-test',
        ]);

        User::create([
            'name' => 'User A',
            'email' => 'usera@test.com',
            'password' => bcrypt('password'),
            'tenant_id' => $tenant->id,
        ]);

        User::create([
            'name' => 'User B',
            'email' => 'userb@test.com',
            'password' => bcrypt('password'),
            'tenant_id' => $tenant->id,
        ]);

        $this->assertCount(2, $tenant->users);
    }
}
