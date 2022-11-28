<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use App\Models\Owner\Owner;
use App\Models\User\User;
use Closure;
use Illuminate\Http\Request;

class OwnerAccess
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
        $user = User::findOrFail($request->user()->id);

        if (!$user->hasRole(Role::Owner)) {
            $message = 'Current user is not an owner of a company';
            return response()->json(['message' => $message], 403);
        }

        return $next($request);
    }
}
