<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureShopOwnership
{
    /**
     * Verify that the shop_id in the route or request belongs to the authenticated user.
     * Superadmin always passes through.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {

















}    }        return $next($request);        }            abort(403, 'You do not have access to this shop.');        if ($shopId && !$user->ownsShop($shopId)) {        $shopId = (int) ($request->route('id') ?? $request->input('shop_id'));        }            return $next($request);        if ($user->isSuperAdmin()) {        }            return redirect()->route('login');
