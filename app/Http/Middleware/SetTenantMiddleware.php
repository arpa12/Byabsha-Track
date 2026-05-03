<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetTenantMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Try to get tenant from authenticated user
        if ($request->user() && $request->user()->tenant_id) {
            tenancy()->initialize($request->user()->tenant());
            return $next($request);
        }

        // Try to get tenant from domain
        $tenant = Tenant::where('domain', $request->getHost())
            ->where('is_active', true)
            ->first();

        if ($tenant) {
            tenancy()->initialize($tenant);
        }

        return $next($request);
    }
}
