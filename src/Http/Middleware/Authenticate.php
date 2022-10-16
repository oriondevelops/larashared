<?php

namespace Orion\Larashared\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     *
     * @throws Exception
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if ($request->bearerToken() !== config('larashared.token')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 403);
        }

        return $next($request);
    }
}
