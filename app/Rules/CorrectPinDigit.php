<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CorrectPinDigit implements Rule
{
    protected $index;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($index)
    {
        $this->index = ($index == 1) ? 1 : 2; // Prevent input other than 1 or 2
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
        $pinIndex = request()->input('random_pin_index_' . $this->index);

        if ($pinIndex < 0 || $pinIndex > 5) 
            return false;

        $userPin = auth()->user()->unencrypted_pin;

        return ($userPin[$pinIndex] == $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The input did not match the record';
    }
}
