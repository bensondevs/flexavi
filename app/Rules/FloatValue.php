<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class FloatValue implements Rule
{
    protected $allowInteger;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($allowInteger = false)
    {
        $this->allowInteger = $allowInteger;
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
        $isFloat = is_float($value);

        if ($this->allowInteger && (! $isFloat)) {
            $value = (int) $value;
            return is_int($value);
        }

        return $isFloat;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('Wrong type, must be float or double type of value');
    }
}
