<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HasCompany
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
        $companyRole = $user->{$user->user_role};

        if (! $company = $companyRole->company) {
            $message = 'This user has no company, please register a company';

            return response()->json(['message' => $message], 403);
        }

        $request->attributes->add(['_company' => $company]);

        return $next($request);
    }
}
