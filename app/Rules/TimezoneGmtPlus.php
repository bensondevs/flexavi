<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class TimezoneGmtPlus implements Rule
{
    protected $input;

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
        $startsWithGmtPlus = str_starts_with($value, 'GMT+');

        $explode = explode('+', $value);
        $endedWithNumber = is_numeric($explode[1]);

        return $startsWithGmtPlus && $endedWithNumber;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The timezone structure is not correct! ' . $this->input;
    }
}
