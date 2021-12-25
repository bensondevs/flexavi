<?php

use Carbon\Carbon;

/**
 * Create carbon instance
 * 
 * @param  mixed  $parameter
 * @return \Carbon\Carbon
 */
if (! function_exists('carbon')) {
	function carbon($parameter = null)
	{
		return ($parameter === null) ?
			new Carbon() :
			new Carbon($parameter);
	}
}

/**
 * Parse string of date into certain format supplied
 * 
 * @param  string  $date
 * @param  string  $format
 * @return string
 */
if (! function_exists('carbon_parse_format')) {
	function carbon_parse_format(string $date, string $format = 'Y-m-d')
	{
		return carbon()->parse($date)->format($format);
	}
}

/**
 * Convert any form of date into certain format
 * 
 * @param  mixed  $date
 * @param  string  $format
 * @return string
 */
if (! function_exists('format_datetime')) {
	function format_datetime($date, string $format = 'Y-m-d H:i:s')
	{
		return carbon($date)->format($format);
	}
}

/**
 * Convert string of date into timestamp
 * 
 * @param  string  $date
 * @return  \DateTime
 */
if (! function_exists('carbon_parse_timestamp')) {
	function carbon_parse_timestamp(string $date)
	{
		return carbon()->parse($date)->timestamp;
	}
}

/**
 * Get start datetime of certain date string
 * 
 * @param  string  $date
 */
if (! function_exists('start_of_day')) {
	function start_of_day(string $date)
	{
		return carbon()->parse($date)->copy()->startOfDay();
	}
}

/**
 * Get start date of the current month
 * 
 * @return string
 */
if (! function_exists('month_start_date')) {
	function month_start_date()
	{
		return now()->copy()->startOfMonth();
	}
}

/**
 * Get end datetime of certain date string
 * 
 * @param  string  $date
 */
if (! function_exists('end_of_day')) {
	function end_of_day(string $date)
	{
		return carbon()->parse($date)->copy()->endOfDay();
	}
}

/**
 * Get last week of current day carbon date
 * 
 * @return \Carbon\Carbon
 */
if (! function_exists('last_week')) {
	function last_week()
	{
		$now = now()->copy();
		return $now->subDays(7);
	}
}

/**
 * Alias for "last_week"
 * 
 * @return \Carbon\Carbon
 */
if (! function_exists('lastWeek')) {
	function lastWeek()
	{
		return last_week();
	}
}

/**
 * Get next week of the current day carbon date
 * 
 * @return \Carbon\Carbon
 */
if (! function_exists('next_week')) {
	function next_week()
	{
		$now = now()->copy();
		return $now->addDays(7);
	}
}

/**
 * Alias for "next_week"
 * 
 * @return \Carbon\Carbon
 */
if (! function_exists('nextWeek')) {
	function nextWeek()
	{
		return next_week();
	}
}

/**
 * Convert number to month name
 * 
 * @param int $number
 * @return string
 */
if (! function_exists('num_to_month_name')) {
	function num_to_month_name(int $number)
	{
		// Possible most minimum number
		if ($number <= 1) $number = 1;

		// If exceed, just take the modulo of 12
		if ($number > 12) {
			$number = ($number % 12);
			$number = ($number == 0) ? 12 : $number;
		}

		$index = $number - 1;
		$monthNames = [
            'January',  'February', 'March', 'April', 
            'May', 'June', 'July', 'August', 
            'September', 'October', 'November', 'December',
        ];

        return isset($monthNames[$index]) ?
        	$monthNames[$index] : 
        	$monthNames[1];
	}
}