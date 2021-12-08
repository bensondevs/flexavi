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
	//
}

/**
 * Alias for "random_string" function for camel case version
 * 
 * @param int  $length
 * @return string
 */
if (! function_exists('randomString')) {
	//
}