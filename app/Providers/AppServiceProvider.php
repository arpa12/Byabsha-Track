<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register simple plan helpers for convenience in controllers and views
        if (! function_exists('planFeature')) {
            function planFeature(string $feature, $user = null): bool
            {
                $user = $user ?: auth()->user();
                if (! $user) {
                    return false;
                }
                return app(\App\Services\PlanService::class)->isFeatureEnabled($user, $feature);
            }
        }

        if (! function_exists('planLimit')) {
            function planLimit(string $limitKey, $user = null)
            {
                $user = $user ?: auth()->user();
                if (! $user) {
                    return null;
                }
                $plan = $user->currentPlan();
                if ($plan instanceof \Modules\Subscription\Models\SubscriptionPlan) {
                    return $plan->getLimit($limitKey);
                }
                return null;
            }
        }

        if (! function_exists('has_module_access')) {
            function has_module_access(string $moduleKey, $user = null): bool
            {
                $user = $user ?: auth()->user();
                if (! $user) {
                    return false;
                }
                return $user->hasModuleAccess($moduleKey);
            }
        }
    }
}
