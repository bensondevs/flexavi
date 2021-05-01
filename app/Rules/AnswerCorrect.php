<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class AnswerCorrect implements Rule
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
        return auth()->user()->checkQuestionAnswer(strtolower($value));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('The answer you inserted does not match our record, please try again');
    }
}
