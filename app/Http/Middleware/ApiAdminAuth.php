<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiAdminAuth
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $admin = Auth::guard('sanctum')->user();

        if (!$admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.'
            ], 401);
        }

        // Admin bloklangan bo‘lsa
        if ($admin->block == 1) {
            return response()->json([
                'success' => false,
                'message' => 'Siz bloklangansiz.'
            ], 403);
        }

        return $next($request);
    }
}
