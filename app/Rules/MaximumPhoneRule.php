<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MaximumPhoneRule implements Rule
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
        return (auth()->user()->total_phone <= 10);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('You`ve reached the maximum amount of adding the phone number of 10');
    }
}
