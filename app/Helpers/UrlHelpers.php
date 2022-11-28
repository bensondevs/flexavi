<?php

use Illuminate\Support\Facades\Route;

/**
 * Check if current route name is match specified route name
 *
 * @param string $route
 * @return bool
 */
if (!function_exists('is_current_route')) {
    function is_current_route(string $route): bool
    {
        return Route::currentRouteName() === $route;
    }
}

/**
 * Alias for "is_current_route"
 *
 * @param string $route
 * @return bool
 */
if (!function_exists('isCurrentRoute')) {
    function isCurrentRoute(string $route): bool
    {
        return is_current_route($route);
    }
}

/**
 * Check if certain route name is starts with specified string
 *
 * @param string $start
 * @param string $routeName
 * @return bool
 */
if (!function_exists('is_route_starts_with')) {
    function is_route_starts_with(string $start, string $routeName = ''): bool
    {
        $routeName = $routeName ?: Route::currentRouteName();
        if (is_null($routeName)) {
            $routeName = '';
        }

        return is_str_starts_with($routeName, $start);
    }
}

/**
 * Alias for "is_route_starts_with"
 *
 * @param string $start
 * @param string $routeName
 * @return bool
 */
if (!function_exists('isRouteStartsWith')) {
    function isRouteStartsWith(string $start, string $routeName = ''): bool
    {
        return is_route_starts_with($start, $routeName);
    }
}

/**
 * Prepare localized route based on current locale
 *
 * @param string $routeName
 * @param mixed|null $parameters
 * @return  string
 */
if (!function_exists('localized_route')) {
    function localized_route(string $routeName, $parameters = null): string
    {
        $localeParameter = [
            'locale' => request()->route('locale') ?: app()->getLocale(),
        ];

        // Check if parameters is exists
        if (!$parameters) {
            return route($routeName, $localeParameter);
        }

        // Prepare parameters
        if (!is_array($parameters)) {
            $parameters = [$parameters];
        }

        $parameters = array_merge($parameters, $localeParameter);
        return route($routeName, $parameters);
    }
}

/**
 * Alias for "localized_route"
 *
 * @param string $routeName
 * @param mixed|null $parameters
 * @return string
 */
if (!function_exists('localizedRoute')) {
    function localizedRoute(string $routeName, $parameters = null): string
    {
        return localized_route($routeName, $parameters);
    }
}

/**
 * Get current link of the application
 *
 * @return  string
 */
if (!function_exists('current_link')) {
    function current_link(): string
    {
        return url()->current();
    }
}

/**
 * Alias for "current_link"
 *
 * @return  string
 */
if (!function_exists('currentLink')) {
    function currentLink(): string
    {
        return current_link();
    }
}

/**
 * Get current request method
 *
 * @return  string
 */
if (!function_exists('request_method')) {
    function request_method(): string
    {
        return request()->method();
    }
}

/**
 * Alias for "request_method"
 *
 * @return  string
 */
if (!function_exists('requestMethod')) {
    function requestMethod(): string
    {
        return request_method();
    }
}
