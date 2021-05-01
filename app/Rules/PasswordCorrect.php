<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PasswordCorrect implements Rule
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
        return hashCheck(
            $value, 
            auth()->user()->password
        );
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('The password you insert does not match our record!');
    }
}
