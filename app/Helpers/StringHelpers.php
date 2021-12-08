<?php

use Illuminate\Support\Str;

/**
 * Get the last character of a string
 * 
 * @param string  $string
 * @return string
 */
if (! function_exists('last_character')) {
	function last_character(string $string)
	{
		return substr($string, -1);
	}
}

/**
 * Check if last character of string
 * is match specified string
 * 
 * @param  string  $string
 * @param  string  $match
 * @return  bool
 */
if (! function_exists('is_last_character')) {
	function is_last_character(string $string, string $match)
	{
		return last_character($string) === $match;
	}
}

/**
 * Get the first character of a string
 * 
 * @param string  $string
 * @return string
 */
if (! function_exists('first_character')) {
	function first_character(string $string)
	{
		return substr($string, 1);
	}
}

/**
 * Check if first character of string
 * is match specified string
 * 
 * @param  string  $string
 * @param  string  $match
 * @return  bool
 */
if (! function_exists('is_first_character')) {
	function is_first_character(string $string, string $match)
	{
		return first_character($string) === $match;
	}
}

/**
 * Check if string is starts with certain string
 * 
 * @param  string  $string
 * @param  string  $match
 * @return bool
 */
if (! function_exists('is_str_starts_with')) {
	function is_str_starts_with(string $string, string $match)
	{
		return substr($string, 0, strlen($match)) === $match;
	}
}

/**
 * Convert string to singular version
 * 
 * @param string  $string
 * @return string
 */
if (! function_exists('str_to_singular')) {
	function str_to_singular(string $string)
	{
		return Str::singular($string);
	}
}

/**
 * Convert string to plural version
 * 
 * @param string  $string
 * @return string
 */
if (! function_exists('str_to_plural')) {
	function str_to_plural(string $string)
	{
	    return Str::plural($string);
	}
}

/**
 * Convert string to snake case
 * 
 * @param string  $string
 * @return string
 */
if (! function_exists('str_snake_case')) {
	function str_snake_case(string $string)
	{
	    return Str::snake($string);
	}
}

/**
 * Convert string to camel case
 * 
 * @param string  $string
 * @return string
 */
if (! function_exists('str_camel_case')) {
	function str_camel_case(string $string)
	{
	    return Str::camel($string);
	}
}

/**
 * Convert boolean written in string to real boolean
 * 
 * @param mixed  $string
 * @return bool
 */
if (! function_exists('strtobool')) {
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
 * Generate random string by specified 
 * amount of characters. Default amount is 5
 * 
 * @param int  $length
 * @return string
 */
if (! function_exists('random_string')) {
	function random_string(int $length = 5) 
	{
		return Str::random($length);
	}
}

/**
 * Alias for "random_string" function for camel case version
 * 
 * @param  int  $length
 * @return  string
 */
if (! function_exists('randomString')) {
	function randomString(int $length = 5)
	{
		return random_string($length);
	}
}

/**
 * Random alphabeths genetor
 * 
 * @param  int  $length
 * @return  string
 */
if (! function_exists('random_alphabeth')) {
	function random_alphabeth(int $length = 5)
	{
		if ($length <= 0) return ''; // Recursive breaker

		$chars = [
			// Uppercase
			'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K',
			'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V',
			'W', 'X', 'Y', 'Z',

			// Lowercase
			'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k',
			'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v',
			'w', 'x', 'y', 'Z'
		];
		$index = rand(0, count($chars));
		
		return $chars[$index] . random_alphabeth($length - 1);
	}
}

/**
 * Search string in array
 * 
 * @param  array  $pool
 * @param  string  $keyword
 * @return  mixed
 */
if (! function_exists('array_str_search')) {
	function array_str_search(array $pool, string $keyword)
	{
		foreach ($pool as $element) {
			if (stripos($element, $keyword) !== false)
				return $element;
		}
	}
}