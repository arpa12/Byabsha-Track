<?php

namespace Tests\Feature;

use App\Models\Tenant;
use Tests\TestCase;

class CreateTenantCommandTest extends TestCase
{
    /**
     * Test that create tenant command creates a tenant
     */
    public function test_create_tenant_command_creates_tenant()
    {
        $this->artisan('tenant:create', [
            'name' => 'Command Test Tenant',
            '--domain' => 'command.test.com',
            '--email' => 'admin@command.test',
            '--description' => 'Test tenant created via command',
        ])
            ->assertExitCode(0)
            ->expectsOutput("Tenant 'Command Test Tenant' created successfully!");

        $tenant = Tenant::where('slug', 'command-test-tenant')->first();
        $this->assertNotNull($tenant);
        $this->assertEquals('Command Test Tenant', $tenant->name);
        $this->assertEquals('command.test.com', $tenant->domain);
        $this->assertEquals('admin@command.test', $tenant->email);
    }

    /**
     * Test that command fails if tenant with same slug exists
     */
    public function test_create_tenant_command_fails_with_duplicate_slug()
    {
        Tenant::create([
            'name' => 'Existing Tenant',
            'slug' => 'duplicate-test',
        ]);

        $this->artisan('tenant:create', [
            'name' => 'Duplicate Test',
        ])
            ->assertExitCode(1)
            ->expectsOutput("Tenant with slug 'duplicate-test' already exists.");
    }

    /**
     * Test command with minimal parameters
     */
    public function test_create_tenant_command_with_minimal_params()
    {
        $this->artisan('tenant:create', [
            'name' => 'Minimal Tenant',
        ])
            ->assertExitCode(0);

        $tenant = Tenant::where('slug', 'minimal-tenant')->first();
        $this->assertNotNull($tenant);
        $this->assertTrue($tenant->is_active);
        $this->assertNull($tenant->domain);
    }
}
