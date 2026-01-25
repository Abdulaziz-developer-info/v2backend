<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ApiTokenAuth
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token || !str_contains($token, '|')) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid token format',
            ], 401);
        }

        [$id, $plainToken] = explode('|', $token, 2);

        $hashedToken = hash('sha256', $plainToken);

        $accessToken = DB::table('personal_access_tokens')
            ->where('id', $id)
            ->where('token', $hashedToken)
            ->first();

        if (!$accessToken) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid token',
            ], 401);
        }

        $user = \App\Models\User::find($accessToken->tokenable_id);

        $request->setUserResolver(fn() => $user);

        return $next($request);
    }

}
