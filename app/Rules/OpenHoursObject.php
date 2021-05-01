<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class OpenHoursObject implements Rule
{
    protected $errorMessage;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->errorMessage = 'Wrong input format.';
    }

    public function isDayName($string)
    {
        $dayNames = [
            'sunday',   
            'monday', 
            'tuesday',  
            'wednesday', 
            'thursday', 
            'friday', 
            'saturday'
        ];

        $correct = in_array(strtolower($string), $dayNames);

        if (! $correct) $this->errorMessage = $string . ' is not correct day name';

        return $correct;
    }

    public function validateTimeString($time)
    {
        $hasDoubleDot = (bool) strpos($time, ':');
        $hourDigit = substr($time, 0, 2);
        $minuteDigit = substr($time, -2);

        $correct = ($hasDoubleDot === true) && 
            is_numeric($hourDigit) && 
            is_numeric($minuteDigit);

        return $correct;
    }

    public function validateOpenClose($key)
    {
        $isArray = is_array($key);
        $isNull = is_null($key);

        if ($isArray) {
            foreach ($key as $keyName => $property) {
                if (! in_array($keyName, ['from', 'to'], true)) {
                    $this->errorMessage = 'Unknown property of [' . $keyName . '], please fill either `from` or `to`';
                    return false;
                }

                if (! $this->validateTimeString($property)) {
                    $this->errorMessage = 'Wrong time format of `' . $property . '` at [`' . $keyName . '`]';
                    return false;
                }
            }
        } else if ($isNull) {
            if ($key !== null) {
                $this->errorMessage = 'Unknown input type of [' . $key . ']';
                return false;
            }
        }

        return true;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $openHours = json_decode($value, true);

        // Is input JSON array, if not should be '24/7'
        if (! $openHours) {
            if ($value != '24/7')
                return false;
            else
                return true;
        }

        foreach ($openHours as $dayname => $openHour) {
            // Check if dayname
            $correctDayName = $this->isDayName($dayname);
            if (! $correctDayName) return false;

            // Validate from to
            $correctOpenClose = $this->validateOpenClose($openHour);
            if (! $correctOpenClose) return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->errorMessage;
    }
}
