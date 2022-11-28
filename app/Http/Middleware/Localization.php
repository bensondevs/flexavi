<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response|RedirectResponse) $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($lang = $this->getLanguagePreference()) {
            \App::setLocale($lang);
        }

        return $next($request);
    }

    /**
     * Get User Preference Language
     *
     * @return string
     */
    private function getLanguagePreference(): string
    {
        return request("lang", config("app.locale"));
    }
}
