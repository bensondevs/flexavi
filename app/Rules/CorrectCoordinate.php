<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CorrectCoordinate implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        $coordinate = json_decode($value, true);
        $latitudeExist = isset($coordinate['lat']);
        $longitudeExist = isset($coordinate['lng']);

        // Is coordinate correct JSON
        if (! $coordinate) return false;

        // Is latitude and longitude inside the JSON Array
        if (! $latitudeExist) return false;
        if (! $longitudeExist) return false;

        // Are Latitude and Longitude float
        return (is_float($coordinate['lat'])) && 
            (is_float($coordinate['lng']));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('Wrong coordinate format.');
    }
}
