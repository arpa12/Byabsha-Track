<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckBranchAccess
{
    /**
     * Handle an incoming request.
     *
     * Ensures users can only access their own branch data.
     * Owners have access to all branches.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.'
            ], 401);
        }

        // Owners can access all branches
        if ($user->isOwner()) {
            return $next($request);
        }

        // Get branch_id from request (query param or route param or body)
        $requestedBranchId = $request->input('branch_id')
            ?? $request->route('branch')
            ?? $request->query('branch_id');

        // If no branch_id in request, allow (will default to user's branch)
        if (!$requestedBranchId) {
            return $next($request);
        }

        // Check if user is trying to access a different branch
        if ($user->branch_id != $requestedBranchId) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden. You can only access data from your assigned branch.',
                'your_branch_id' => $user->branch_id,
                'requested_branch_id' => $requestedBranchId
            ], 403);
        }

        return $next($request);
    }
}
