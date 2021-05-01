<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class UnixTimestamp implements Rule
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
        return ((int) $value === $value) 
            && ($timestamp <= PHP_INT_MAX)
            && ($timestamp >= ~PHP_INT_MAX);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The input is not correct UNIX Timestamp!';
    }
}
