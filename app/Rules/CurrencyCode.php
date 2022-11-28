<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

use App\Models\Currency;

class CurrencyCode implements Rule
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
        $this->input = $value;

        return (bool) Currency::findCurrency($this->input);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'We think ' . $this->input . ' is not a currency code, please try again.';
    }
}
