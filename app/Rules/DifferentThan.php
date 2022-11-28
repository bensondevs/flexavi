<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class DifferentThan implements Rule
{
    protected $comparison;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($comparison)
    {
        $this->comparison = $comparison;
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
        return request()->input($this->comparison) != $value;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The value should not be equal with ' . str_replace('_', ' ', $this->comparison);
    }
}
