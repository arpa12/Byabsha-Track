<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Settings\Models\Setting;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class RestrictDuringMaintenanceMode
{
    /**
     * Block manager/owner access when app maintenance mode is enabled in settings.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (app()->runningInConsole()) {
            return $next($request);
        }

        $user = $request->user();

        if (!$user) {
            return $next($request);
        }

        if ($request->routeIs('logout') || $user->isSuperAdmin()) {
            return $next($request);
        }

        if (!in_array($user->role, ['manager', 'owner'], true)) {
            return $next($request);
        }

        try {
            $maintenanceMode = (bool) Setting::get('maintenance_mode', false);

            if (!$maintenanceMode) {
                return $next($request);
            }
        } catch (Throwable) {
            return $next($request);
        }

        return response()->view('maintenance.role-maintenance', [], 503);
    }
}
