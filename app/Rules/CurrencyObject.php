<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CurrencyObject implements Rule
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
        // Convert input JSON String to array
        $currency = json_encode($value, true);

        // Check if input is correct JSON
        if (! $currency) return false;

        // Check if needed properties exists
        if (! isset($currency['currency_code'])) return false;
        if (! isset($currency['limit'])) return false;

        // Check if currency code is string
        $isCurrencyCodeString = is_string($currency['currency_code']);

        // If limit is not int, atleast its float
        $isLimitInt = is_int($currency['limit']);
        $isLimitFloat = is_float($currency['limit']);

        return 
            $isCurrencyCodeString && 
            ($isLimitInt || $isLimitFloat);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('Wrong input format.');
    }
}
