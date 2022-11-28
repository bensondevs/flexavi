<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class AlphaSpace implements Rule
{
    /**
     * Error message to the user
     * 
     * @var  string
     */
    private $message;

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
        if (preg_match('/[a-z]/i')) {
            $this->message = 'The value must have alphabeth characters';
            return false;
        }

        return preg_match('/^[\p{L} ]+$/u', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message ?: 
            'The value must be alphabeth and spaces only.';
    }
}
