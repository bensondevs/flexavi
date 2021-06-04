<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OwnerAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {   
        $user = $request->user();

        if (! $user->hasRole('owner')) {
            $message = 'Current user is not an owner of a company';

            return response()->json(['message' => $message], 403);
        }

        return $next($request);
    }
}
