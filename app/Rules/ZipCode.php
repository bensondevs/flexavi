<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ZipCode implements Rule
{
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
        $zipcode = (string) $value;

        if (strlen($zipcode) != 5) {
            $this->message = 'The length of zipccode must be exactly 5 characters.';
            return false;
        }

        if (! is_numeric($zipcode)) {
            $this->message = 'The zipcode must only contains numbers.';
            return false;
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
        return $this->message ?: 'Wrong zipcode input format.';
    }
}
