<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $clientKey = $request->header('X-API-KEY');
        $serverKey = config('app.api_key');

        if (!$clientKey || $clientKey !== $serverKey) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized - Invalid API Key'
            ], 401);
        }

        return $next($request);
    }
}