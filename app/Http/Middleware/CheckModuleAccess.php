<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckModuleAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $moduleKey): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->hasModuleAccess($moduleKey)) {
            return $next($request);
        }

        abort(403, __('user.module_access_denied'));
    }
}
