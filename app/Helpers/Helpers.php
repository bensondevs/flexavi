<?php

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Webpatser\Uuid\Uuid;

/**
 * Generate UUID
 * 
 * @return  string
 */
if (! function_exists('generate_uuid')) {
    function generate_uuid()
    {
        return Uuid::generate()->string;
    }
}

/**
 * Alias for "generate_uuid"
 * 
 * @return  string
 */
if (! function_exists('generateUuid')) {
    function generateUuid()
    {
        return generate_uuid();
    }
}

/**
 * Search value in collection
 * 
 * @param Illuminate\Support\Collection  $collection
 * @param mixed  $search
 * @return bool
 */
if (! function_exists('searchInCollection')) {
    function searchInCollection(Collection $collection, $search)
    {
        return ($collection->filter(function ($item) use ($search) {
            $attributes = array_keys($item);
            foreach ($attributes as $attribute)
                if (isset($item[$attribute]) && (! is_array($item[$attribute])))
                    if (stripos($item[$attribute], $search) !== false)
                        return true;

            return false;
        }))->toArray();
    }
}

/**
 * Convert full url to username
 * 
 * @param  string  $url
 * @return  string
 */
if (! function_exists('urlToUsername')) {
    function urlToUsername(string $urlString)
    {
        $urlString = str_replace('http://', '', $urlString);
        $urlString = str_replace('https://', '', $urlString);
        $urlString = str_replace('www.', '', $urlString);

        $clearParams = explode('/', $urlString);
        
        $mainDomain = $clearParams[0];
        $breakMainDomain = explode('.', $mainDomain);
        $domainName = $breakMainDomain[0];
        $domainExtension = $breakMainDomain[1];

        return $domainName . $domainExtension;
    }
}

/**
 * Get object pure class name without namespaces
 * 
 * @param  mixed  $class
 * @return string
 */
if (! function_exists('get_pure_class')) {
    function get_pure_class($class)
    {
        $class = get_class($class);
        $explode = explode('\\', $class);
        return $explode[count($explode) - 1];
    }
}

/**
 * Get object lower class version
 * 
 * @param  mixed  $class
 * @return string
 */
if (! function_exists('get_lower_class')) {
    function get_lower_class($class)
    {
        $lowerClassname = get_pure_class($class);
        $lowerClassname = strtolower($lowerClassname);
        return $lowerClassname;
    }
}

/**
 * Get object plural lower case name
 * 
 * This will be helpful to create variable name
 * 
 * @param  mixed  $class
 * @return string
 */
if (! function_exists('get_plural_lower_class')) {
    function get_plural_lower_class($class)
    {
        return str_to_plural(get_lower_class($class));
    }
}

/**
 * Convert any number value to float
 * 
 * @param  numeric  $number
 * @return float
 */
if (! function_exists('numbertofloat')) {
    function numbertofloat($number)
    {
        return sprintf('%.2f', $number);
    }
}

/**
 * Format any numeric value to currency
 * 
 * @param  numeric  $amount
 * @param  string  $currencyCode
 * @param  string  $locale
 * @return string
 */
if (! function_exists('currency_format')) {
    function currency_format(
        $amount, 
        string $currencyCode = 'EUR', 
        string $locale = 'nl_NL.UTF-8'
    ) {
        $formatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);
        return $formatter->formatCurrency($amount, $currencyCode);
    }
}

/**
 * Make alias for Laravel DB class
 * 
 * @param  string  $table
 * @return \Illuminate\Support\Facades\DB
 */
if (! function_exists('db')) {
    function db(string $table = '')
    {
        return ($table) ? DB::table($table) : new DB;
    }
}

/**
 * Clean file name from directory special characters
 * 
 * @param  string  $filename
 * @return string
 */
if (! function_exists('clean_filename')) {
    function clean_filename(string $filename)
    {
        // Replace > with space
        $filename = str_replace('/', ' ', $filename);

        // Replace > with space
        $filename = str_replace('>', ' ', $filename);

        // Replace | with space
        $filename = str_replace('|', ' ', $filename);

        // Replace : with space
        $filename = str_replace(':', ' ', $filename);

        // Replace & with space
        $filename = str_replace('&', ' ', $filename);

        // Replace ? with space
        $filename = str_replace(' ', '_', $filename);
        
        // Replace spaces with _
        $filename = str_replace(' ', '_', $filename);

        return $filename;
    }
}

/**
 * Generate random hex color
 * 
 * @return  string
 */
if (! function_exists('random_hex_color')) {
    function random_hex_color() {
        return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
    }
}

/**
 * Generate random phone number
 * by specifying the length of digits
 * 
 * @param  int  $lenth
 * @return  string
 */
if (! function_exists('random_phone')) {
    function random_phone(int $length = 12)
    {
        if ($length <= 0) return '';

        $digit = ((string) random_int(0, 9));
        return $digit . random_phone($length - 1);
    }
}

/**
 * Shorter version for laravel response json
 * 
 * @param  array  $response
 * @return \Illuminate\Support\Facades\Response
 */
if (! function_exists('jsonResponse')) {
    function jsonResponse(array $response)
    {
        return response()->json($response);
    }
}

/**
 * Prepare and set response for repository class result
 * 
 * @param  mixed  $respotoryObject
 * @param  mixed|null  $responseData
 * @return \Illuminate\Support\Facades\Response
 */
if (! function_exists('apiResponse')) {
    function apiResponse($repositoryObject, $responseData = null)
    {
        $response = [];

        if (is_array($responseData)) {
            $attribute = array_keys($responseData)[0];
            $response[$attribute] = $responseData[$attribute];
        } else if ($responseData !== null) {
            $response['data'] = $responseData;
        }
        
        if ($status = $repositoryObject->status) {
            $response['status'] = (count($repositoryObject->statuses) > 1) ? 
                $repositoryObject->statuses :
                $status;
        }

        if ($message = $repositoryObject->message) {
            $response['message'] = (count($repositoryObject->messages) > 1) ?
                $repositoryObject->messages : 
                $message;
        }

        if ($queryError = $repositoryObject->queryError) {
            $response['query_error'] = (count($repositoryObject->queryErrors) > 1) ?
                $repositoryObject->queryErrors :
                $queryError;
        }

        return response()->json($response, $repositoryObject->httpStatus);
    }
}

/**
 * Convery every elemtent to uppercase in array
 * 
 * @param  array  $array
 * @return array
 */
if (! function_exists('uppercaseArray')) {
    function uppercaseArray(array $array)
    {
        return array_map('strtoupper', $array);
    }
}

/**
 * Check if current request is update request
 * 
 * @return bool
 */
if (! function_exists('is_updating_request')) {
    function is_updating_request()
    {
        return 
            request()->isMethod('PUT') or 
            request()->isMethod('PATCH');
    }
}