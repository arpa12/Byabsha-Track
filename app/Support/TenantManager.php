<?php

namespace App\Support;

use App\Models\Tenant;
use App\Models\User;

/**
 * Tenant Management Helper
 *
 * Provides utility methods for tenant operations
 */
class TenantManager
{
    /**
     * Create a new tenant with a default owner user
     */
    public static function createWithOwner(array $tenantData, array $userData): Tenant
    {
        $tenant = Tenant::create($tenantData);

        // Create owner user
        User::create([
            ...$userData,
            'tenant_id' => $tenant->id,
            'role' => 'owner',
        ]);

        return $tenant;
    }

    /**
     * Get all active tenants
     */
    public static function getActiveTenants()
    {
        return Tenant::where('is_active', true)->get();
    }

    /**
     * Check if a user belongs to a tenant
     */
    public static function userBelongsToTenant(User $user, Tenant $tenant): bool
    {
        return $user->tenant_id === $tenant->id;
    }

    /**
     * Get tenant by domain
     */
    public static function getTenantByDomain(string $domain): ?Tenant
    {
        return Tenant::where('domain', $domain)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Get tenant by slug
     */
    public static function getTenantBySlug(string $slug): ?Tenant
    {
        return Tenant::where('slug', $slug)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Deactivate a tenant (soft delete through is_active flag)
     */
    public static function deactivateTenant(Tenant $tenant): void
    {
        $tenant->update(['is_active' => false]);
    }

    /**
     * Reactivate a tenant
     */
    public static function reactivateTenant(Tenant $tenant): void
    {
        $tenant->update(['is_active' => true]);
    }

    /**
     * Get tenant statistics
     */
    public static function getTenantStats(Tenant $tenant): array
    {
        tenancy()->initialize($tenant);

        return [
            'id' => $tenant->id,
            'name' => $tenant->name,
            'slug' => $tenant->slug,
            'users_count' => $tenant->users()->count(),
            'shops_count' => \Modules\Shop\Models\Shop::count(),
            'products_count' => \Modules\Product\Models\Product::count(),
            'sales_count' => \Modules\Sale\Models\Sale::count(),
            'is_active' => $tenant->is_active,
        ];
    }
}
