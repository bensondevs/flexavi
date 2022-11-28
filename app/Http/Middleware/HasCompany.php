<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HasCompany
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()
            ->user()
            ->fresh();
        $companyRole = $user->{$user->user_role};
        $company = $companyRole->company;
        if (is_null($company)) {
            $message = 'This user has no company, please register a company';
            return abort(403, $message);
        }
        $request->attributes->add(['_company' => $company]);

        return $next($request);
    }
}
