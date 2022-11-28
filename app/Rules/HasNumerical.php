<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class HasNumerical implements Rule
{
    protected $stringName;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($stringName = 'string')
    {
        $this->stringName = $stringName;
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
        return preg_match('~[0-9]+~', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $message = 'The ' . $this->stringName . ' you insert has no numerical value, please check and try again!';

        return $message;
    }
}
