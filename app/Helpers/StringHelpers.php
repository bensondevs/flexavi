<?php

use Illuminate\Support\Str;

/**
 * Get the last character of a string
 *
 * @param string $string
 * @return string
 */
if (!function_exists('last_character')) {
    function last_character(string $string): string
    {
        return substr($string, -1);
    }
}

/**
 * Check if last character of string
 * is match specified string
 *
 * @param string $string
 * @param string $match
 * @return  bool
 */
if (!function_exists('is_last_character')) {
    function is_last_character(string $string, string $match): bool
    {
        return last_character($string) === $match;
    }
}

/**
 * Get the first character of a string
 *
 * @param string $string
 * @return string
 */
if (!function_exists('first_character')) {
    function first_character(string $string): string
    {
        return substr($string, 1);
    }
}

/**
 * Check if first character of string
 * is match specified string
 *
 * @param string $string
 * @param string $match
 * @return  bool
 */
if (!function_exists('is_first_character')) {
    function is_first_character(string $string, string $match): bool
    {
        return first_character($string) === $match;
    }
}

/**
 * Check if string is starts with certain string
 *
 * @param string $string
 * @param string $match
 * @return bool
 */
if (!function_exists('is_str_starts_with')) {
    function is_str_starts_with(string $string, string $match): bool
    {
        return str_starts_with($string, $match);
    }
}

/**
 * Check if parameter argument is valid uuid
 *
 * @param mixed $parameter
 * @return bool
 */
if (!function_exists('is_uuid')) {
    function is_uuid($parameter): bool
    {
        // Check if parameter is string
        // Uuid must be string
        if (!is_string($parameter)) {
            return false;
        }

        // Check if pattern is correct uuid pattern
        // Uuid must have pattern of xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx
        // where x is any hexadecimal digit and y is one of 8, 9, A, or B
        $pattern =
            '/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i';
        if (!preg_match($pattern, $parameter)) {
            return false;
        }

        return true;
    }
}

/**
 * Convert string to singular version
 *
 * @param string $string
 * @return string
 */
if (!function_exists('str_to_singular')) {
    function str_to_singular(string $string): string
    {
        return Str::singular($string);
    }
}

/**
 * Convert string to plural version
 *
 * @param string $string
 * @return string
 */
if (!function_exists('str_to_plural')) {
    function str_to_plural(string $string): string
    {
        return Str::plural($string);
    }
}

/**
 * Convert string to snake case
 *
 * @param string $string
 * @return string
 */
if (!function_exists('str_snake_case')) {
    function str_snake_case(string $string): string
    {
        return Str::snake($string);
    }
}

/**
 * Convert string to camel case
 *
 * @param string $string
 * @return string
 */
if (!function_exists('str_camel_case')) {
    function str_camel_case(string $string): string
    {
        return Str::camel($string);
    }
}

/**
 * Convert boolean written in string to real boolean
 *
 * @param mixed $string
 * @return bool
 */
if (!function_exists('strtobool')) {
    function strtobool($string = null)
    {
        if ($string === null) {
            return false;
        }

        if ($string == 'true' || $string == 'false') {
            return filter_var($string, FILTER_VALIDATE_BOOLEAN);
        }

        if ($string == '1' || $string == '0') {
            return boolval($string);
        }

        return true;
    }
}

/**
 * Cast string value to certain data type
 *
 * @param string|null $string
 * @param int $dataType
 * @return mixed
 */
if (!function_exists('cast_string')) {
    function cast_string($string = null)
    {
        return match (true) {
            is_null($string) => $string,
            is_string($string) => $string,
            is_numeric($string) => intval($string),
            is_float($string) => floatval($string),
            is_double($string) => doubleval($string),
            strtobool($string) => strtobool($string),
            !((json_decode($string, true) == null)) => json_decode($string, true),
            default => $string,
        };
    }
}

/**
 * Generate random string by specified
 * amount of characters. Default amount is 5
 *
 * @param int $length
 * @return string
 */
if (!function_exists('random_string')) {
    function random_string(int $length = 5): string
    {
        return Str::random($length);
    }
}

/**
 * Alias for "random_string" function for camel case version
 *
 * @param int $length
 * @return  string
 */
if (!function_exists('randomString')) {
    function randomString(int $length = 5): string
    {
        return random_string($length);
    }
}

/**
 * Random alphabets generator
 *
 * @param int $length
 * @return  string
 */
if (!function_exists('random_alphabeth')) {
    function random_alphabeth(int $length = 5): string
    {
        if ($length <= 0) {
            return '';
        } // Recursive breaker

        $chars = [
            // Uppercase
            'A',
            'B',
            'C',
            'D',
            'E',
            'F',
            'G',
            'H',
            'I',
            'J',
            'K',
            'L',
            'M',
            'N',
            'O',
            'P',
            'Q',
            'R',
            'S',
            'T',
            'U',
            'V',
            'W',
            'X',
            'Y',
            'Z',

            // Lowercase
            'a',
            'b',
            'c',
            'd',
            'e',
            'f',
            'g',
            'h',
            'i',
            'j',
            'k',
            'l',
            'm',
            'n',
            'o',
            'p',
            'q',
            'r',
            's',
            't',
            'u',
            'v',
            'w',
            'x',
            'y',
            'Z',
        ];
        $index = rand(0, count($chars));

        return $chars[$index] . random_alphabeth($length - 1);
    }
}

/**
 * Search string in array
 *
 * @param array $pool
 * @param string $keyword
 * @return  mixed
 */
if (!function_exists('array_str_search')) {
    function array_str_search(array $pool, string $keyword)
    {
        foreach ($pool as $element) {
            if (stripos($element, $keyword) !== false) {
                return $element;
            }
        }
    }
}

/**
 * Concat paths
 *
 * @param array $paths
 * @param bool $startSlash
 * @param bool $endSlash
 * @return string
 */
if (!function_exists('concat_paths')) {
    function concat_paths(
        array $paths,
        bool  $startSlash = false,
        bool  $endSlash = false
    ): string
    {
        $paths = array_map(function ($path) {
            // Remove / in first character
            if ($path) {
                if (first_character($path) == '/') {
                    $path = substr($path, 1);
                }

                // Remove / in last character
                if (last_character($path) == '/') {
                    $path = substr($path, 0, -1);
                }
            }


            return $path;
        }, $paths);

        $result = implode('/', $paths);
        if ($startSlash) $result = '/' . $result;
        if ($endSlash) $result .= '/';

        return $result;
    }
}

/**
 * Get the stub path.
 *
 * @param string $stubName
 * @return string
 */
if (!function_exists('stub_path')) {
    function stub_path(string $stubName): string
    {
        return resource_path(concat_paths([
            'stubs',
            $stubName
        ]));
    }
}

if (!function_exists('is_json_string')) {
    /**
     * Check whether string is JSON.
     *
     * @param mixed $checked
     * @return bool
     */
    function is_json_string(mixed $checked): bool
    {
        if (!is_string($checked)) {
            return false;
        }

        // decode the JSON data
        json_decode($checked);

        // switch and check possible JSON errors
        $error = match (json_last_error()) {
            JSON_ERROR_NONE => false,
            default => true,
        };
        return $error === false;
    }
}
