<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class AmongStrings implements Rule
{
    /**
     * Array of allowed strings to be inputted
     * 
     * @var array
     */
    protected $allowedStrings;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(array $strings)
    {
        $this->allowedStrings = $strings;
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
        return in_array($value, $this->allowedStrings);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('The type you inserted is not available or exist.');
    }
}
