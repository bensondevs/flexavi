<?php

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Webpatser\Uuid\Uuid;

/**
 * getEnvironment
 *
 * @return  string
 */
if (!function_exists('getMollieWebhookUrl')) {
    function getMollieWebhookUrl(): string
    {
        return config('app.url') . '/api/dashboard/companies/subscriptions/webhook';
    }
}

/**
 * Generate UUID
 *
 * @return  string
 */
if (!function_exists('getFileNameFromPath')) {
    function getFileNameFromPath($path): string
    {
        return (new SplFileInfo($path))->getFilename();
    }
}


/**
 * Generate UUID
 *
 * @return  string
 */
if (!function_exists('generate_uuid')) {
    function generate_uuid(): string
    {
        return Uuid::generate()->string;
    }
}

/**
 * Alias for "generate_uuid"
 *
 * @return  string
 */
if (!function_exists('generateUuid')) {
    function generateUuid(): string
    {
        return generate_uuid();
    }
}

/**
 * Generate random token
 *
 * @return  string
 */
if (!function_exists('random_token')) {
    function random_token(int $length = 6): string
    {
        return substr(
            str_shuffle('0123456789abcdefghijklmnopqrstvwxyz'),
            0,
            $length
        );
    }
}

/**
 * Alias for "random_token"
 *
 * @return  string
 */
if (!function_exists('randomToken')) {
    function randomToken(int $length = 6): string
    {
        return random_token($length);
    }
}

/**
 * Search value in collection
 *
 * @param Collection $collection
 * @param mixed $search
 * @return bool
 */
if (!function_exists('searchInCollection')) {
    function searchInCollection(Collection $collection, $search): array
    {
        return $collection
            ->filter(function ($item) use ($search) {
                $attributes = array_keys($item);
                foreach ($attributes as $attribute) {
                    if (
                        isset($item[$attribute]) &&
                        !is_array($item[$attribute])
                    ) {
                        if (stripos($item[$attribute], $search) !== false) {
                            return true;
                        }
                    }
                }

                return false;
            })
            ->toArray();
    }
}

/**
 * Convert full url to username
 *
 * @param string $url
 * @return  string
 */
if (!function_exists('urlToUsername')) {
    function urlToUsername(string $urlString): string
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
 * @param mixed $class
 * @return string
 */
if (!function_exists('get_pure_class')) {
    function get_pure_class($class): string
    {
        $class = get_class($class);
        $explode = explode('\\', (string)$class);
        return $explode[count($explode) - 1];
    }
}

/**
 * Get object lower class version
 *
 * @param mixed $class
 * @return string
 */
if (!function_exists('get_lower_class')) {
    function get_lower_class($class): string
    {
        $lowerClassname = get_pure_class($class);
        return strtolower($lowerClassname);
    }
}

/**
 * Get object plural lower case name
 *
 * This will be helpful to create variable name
 *
 * @param mixed $class
 * @return string
 */
if (!function_exists('get_plural_lower_class')) {
    function get_plural_lower_class($class): string
    {
        return str_to_plural(get_lower_class($class));
    }
}

/**
 * Convert any number value to float
 *
 * @param numeric $number
 * @return float
 */
if (!function_exists('numbertofloat')) {
    function numbertofloat($number): string
    {
        return sprintf('%.2f', $number);
    }
}

/**
 * Format any numeric value to currency
 *
 * @param numeric $amount
 * @param string $currencyCode
 * @param string $locale
 * @return string
 */
if (!function_exists('currency_format')) {
    function currency_format(
        $amount,
        string $currencyCode = 'EUR',
        string $locale = 'nl_NL.UTF-8'
    ): bool|string {
        $formatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);
        return $formatter->formatCurrency($amount, $currencyCode);
    }
}

/**
 * Alias for "currency_format"
 *
 * @param numeric $amount
 * @param string $currencyCode
 * @param string $locale
 * @return string
 */
if (!function_exists('currencyFormat')) {
    function currencyFormat(
        $amount,
        string $currencyCode = 'EUR',
        string $locale = 'nl_NL.UTF-8'
    ): bool|string {
        return currency_format($amount, $currencyCode, $locale);
    }
}

/**
 * Make alias for Laravel DB class
 *
 * @param string $table
 * @return DB
 */
if (!function_exists('db')) {
    function db(string $table = ''): DB|\Illuminate\Database\Query\Builder
    {
        return $table ? DB::table($table) : new DB();
    }
}

/**
 * Clean file name from directory special characters
 *
 * @param string $filename
 * @return string
 */
if (!function_exists('clean_filename')) {
    function clean_filename(string $filename): array|string
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
        return str_replace(' ', '_', $filename);
    }
}

/**
 * Generate random hex color
 *
 * @return  string
 */
if (!function_exists('random_hex_color')) {
    function random_hex_color(): string
    {
        return '#' .
            str_pad(dechex(mt_rand(0, 0xffffff)), 6, '0', STR_PAD_LEFT);
    }
}

/**
 * Generate random phone number
 * by specifying the length of digits
 *
 * @param int $lenth
 * @return  string
 */
if (!function_exists('random_phone')) {
    /**
     * @throws Exception
     */
    function random_phone(int $length = 12): string
    {
        if ($length <= 0) {
            return '';
        }

        $digit = ((string)random_int(0, 9));
        return $digit . random_phone($length - 1);
    }
}

/**
 * Shorter version for laravel response json
 *
 * @param array $response
 * @return \Illuminate\Support\Facades\Response
 */
if (!function_exists('jsonResponse')) {
    function jsonResponse(array $response): JsonResponse
    {
        return response()->json($response);
    }
}

/**
 * Prepare and set response for repository class result
 *
 * @param mixed $respotoryObject
 * @param mixed|null $responseData
 * @return \Illuminate\Support\Facades\Response
 */
if (!function_exists('apiResponse')) {
    function apiResponse($repositoryObject, $responseData = null): JsonResponse
    {
        $response = [];

        if (is_array($responseData)) {
            $attribute = array_keys($responseData)[0];
            $response[$attribute] = $responseData[$attribute];
        } elseif ($responseData !== null) {
            $response['data'] = $responseData;
        }

        if ($status = $repositoryObject->status) {
            $response['status'] =
                count($repositoryObject->statuses) > 1
                    ? $repositoryObject->statuses
                    : $status;
        }

        if ($message = $repositoryObject->message) {
            $response['message'] =
                count($repositoryObject->messages) > 1
                    ? $repositoryObject->messages
                    : $message;
        }

        if ($queryError = $repositoryObject->queryError) {
            $response['query_error'] =
                count($repositoryObject->queryErrors) > 1
                    ? $repositoryObject->queryErrors
                    : $queryError;
        }

        return response()->json($response, $repositoryObject->httpStatus);
    }
}

/**
 * Convert every element to uppercase in array
 *
 * @param array $array
 * @return array
 */
if (!function_exists('uppercaseArray')) {
    function uppercaseArray(array $array): array
    {
        return array_map('strtoupper', $array);
    }
}

/**
 * Check if current request is update request
 *
 * @return bool
 */
if (!function_exists('is_updating_request')) {
    function is_updating_request(): bool
    {
        return request()->isMethod('PUT') or request()->isMethod('PATCH');
    }
}

/**
 * Mollie format amount
 *
 * @return bool
 */
if (!function_exists('mollieFormatAmount')) {
    function mollieFormatAmount($amount): string
    {
        return number_format($amount, 2, '.', '');
    }
}

/**
 * Generate random password
 *
 * @return string
 */
if (!function_exists('generateRandomPassword')) {
    function generateRandomPassword(): string
    {
        $comb = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $shfl = str_shuffle($comb);
        return substr($shfl, 0, 16);
    }
}

/**
 * Partially hide phone number
 *
 * @return string
 */
if (!function_exists('hidePartiallyPhone')) {
    function hidePartiallyPhone($phone): string
    {
        return substr($phone, 0, 3) . '******' . substr($phone, -3);
    }
}

/**
 * Partially hide email
 *
 * @return string
 */
if (!function_exists('hidePartiallyEmail')) {
    function hidePartiallyEmail($email): bool|string
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            list($first, $last) = explode('@', $email);
            $first = str_replace(substr($first, 3), str_repeat('*', strlen($first) - 3), $first);
            $last = explode('.', $last);
            $last_domain = str_replace(substr($last[0], 1), str_repeat('*', strlen($last[0]) - 1), $last[0]);
            return $first . '@' . $last_domain . '.' . $last['1'];
        }
        return false;
    }
}


/**
 * Access nested array with dot notation
 *
 * @param array|object $data
 * @param string $keyInDotNotation
 * @param bool $silent
 * @return mixed
 */
if (!function_exists('arrayobject_accessor')) {
    function arrayobject_accessor($data, $keyInDotNotation, $silent = false)
    {
        try {
            $keys = explode(".", $keyInDotNotation);
            foreach ($keys as $key) {
                $key = is_numeric($key) ? intval($key) : $key;
                if (is_array($data)) {
                    $data = $data[$key];
                } else {
                    $data = $data->{$key};
                }
            }
            return $data;
        } catch (Exception $e) {
            return $silent ? null : $e->getMessage();
        }
    }
}

if (!function_exists('object_to_array')) {
    /**
     * Parse nested object to nested array.
     *
     * @param $object
     * @return array|stdClass
     */
    function object_to_array($object): array|stdClass
    {
        return json_decode(json_encode($object), true);
    }
}

if (!function_exists('test_path')) {
    /**
     * Get the test folder relative path.
     *
     * @param string $path
     * @return string
     */
    function test_path(string $path = ''): string
    {
        return concat_paths(
            [base_path(), 'tests', $path]
        );
    }
}


if (!function_exists('urlWithParams')) {
    /**
     * Make url with params.
     *
     * @param $url
     * @param array $params
     * @return string
     */
    function urlWithParams($url, array $params): string
    {
        $index = 0;
        foreach ($params as $paramName => $paramValue) {
            if ($index == 0) {
                $url .= '?' . $paramName . '=' . $paramValue;
            } else {
                $url .= '&' . $paramName . '=' . $paramValue;
            }
            $index++;
        }
        return $url;
    }
}


if (!function_exists('customNamespace')) {
    /**
     * Make custom namespace of the class
     *
     * @param string $namespace
     * @param string $class
     * @return string
     */
    function customNamespace(string $namespace, string $class): string
    {
        $class = ucfirst(
            str_camel_case(
                \Illuminate\Support\Str::afterLast($class, "\\")
            )
        );
        $namespace = substr($namespace, -1) == '\\' ? // check if namespace ends with a slash
            \Illuminate\Support\Str::beforeLast($class, "\\")
            : $namespace;
        return "$namespace\\$class";
    }
}


if (!function_exists('urlContains')) {
    /**
     * Determine whether the current url contains string
     *
     * @param string|array> $contains
     * @param string|null $url
     * @return bool
     */
    function urlContains(string|array $contains, string|null $url = null): bool
    {
        $url = $url ? $url : request()->url();
        return \Illuminate\Support\Str::contains($url, $contains, true);
    }
}


if (!function_exists('getConstantName')) {
    /**
     * Get the constant name of the class
     *
     * @param string|object> $class
     * @param mixed $value
     * @return ?string
     */
    function getConstantName($class, $value): ?string
    {
        return array_flip((new \ReflectionClass($class))->getConstants())[$value];
    }
}

if (!function_exists('tryIsset')) {
    /**
     * access an element that may can throw an exception with default value if it is not present
     *
     * @param callable $tryBlock
     * @param mixed $default
     * @return mixed
     */
    function tryIsset(callable $tryBlock, $default = null): mixed
    {
        try {
            return $tryBlock();
        } catch (\Exception $e) {
            return $default;
        }
    }
}

if (!function_exists('isAssociativeArray')) {
    /**
     * Determine if an array is associative (it returns true if it is associative array)
     *
     * @param callable $tryBlock
     * @param mixed $default
     * @return mixed
     */
    function isAssociativeArray(array $array)
    {
        if (array() === $array) {
            return false;
        }
        return array_keys($array) !== range(0, count($array) - 1);
    }
}
