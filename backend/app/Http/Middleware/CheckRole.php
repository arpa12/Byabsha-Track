<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * Allows multiple roles to be checked with OR logic.
     * Example: ->middleware('check.role:owner,manager')
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Check if user is authenticated
        if (!$request->user()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated. Please login to access this resource.'
            ], 401);
        }

        // Check if user is active
        if (!$request->user()->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Your account has been deactivated. Please contact administrator.'
            ], 403);
        }

        // Check if user has any of the required roles
        $userRole = $request->user()->role;

        if (!in_array($userRole, $roles)) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden. You do not have permission to access this resource.',
                'required_roles' => $roles,
                'your_role' => $userRole
            ], 403);
        }

        return $next($request);
    }
}
