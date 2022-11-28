<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NumberMultiplyOf implements Rule
{
    /**
     * Multiply Of
     *
     * @var int
     */
    private $multiplyOf;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($multiplyOf = 10)
    {
        $this->multiplyOf = $multiplyOf;
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
        if (($value % $this->multiplyOf) != 0) {
            // $value is not a multiple of $multiplyOf
            return false;
        };

        // $value is a multiple of $multiplyOf
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "The :attribute is not a multiply of $this->multiplyOf";
    }
}
