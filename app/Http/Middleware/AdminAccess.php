<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use Closure;
use Illuminate\Http\Request;

class AdminAccess
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $user = auth()->user()->fresh();

        if (!$user->hasRole(Role::Admin)) {
            return response()->json([
                'message' => 'This access is only for administrators',
                'status' => 'error'
            ], 403);
        }

        return $next($request);
    }
}
