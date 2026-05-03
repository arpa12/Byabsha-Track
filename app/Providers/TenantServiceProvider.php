<?php

namespace App\Providers;

use App\Models\Tenant;
use Illuminate\Support\ServiceProvider;

class TenantServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register a singleton for tenant context
        $this->app->singleton('tenant.context', function () {
            return [
                'tenant' => null,
            ];
        });

        // Register helper function
        if (!function_exists('tenant')) {
            function tenant($key = null)
            {
                $context = app('tenant.context');
                $tenantModel = $context['tenant'] ?? null;

                if ($key === null) {
                    return $tenantModel;
                }

                return $tenantModel ? $tenantModel->{$key} : null;
            }
        }

        // Register tenancy service
        if (!function_exists('tenancy')) {
            function tenancy()
            {
                return new class {
                    public function initialize($tenant)
                    {
                        $context = app('tenant.context');
                        $context['tenant'] = $tenant;
                        app()->instance('tenant.context', $context);
                    }

                    public function forget()
                    {
                        $context = app('tenant.context');
                        $context['tenant'] = null;
                        app()->instance('tenant.context', $context);
                    }

                    public function current()
                    {
                        return tenant();
                    }
                };
            }
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Load tenant context from session if authenticated
        if (auth()->check() && auth()->user()->tenant_id) {
            $tenant = Tenant::find(auth()->user()->tenant_id);
            if ($tenant) {
                tenancy()->initialize($tenant);
            }
        }
    }
}
