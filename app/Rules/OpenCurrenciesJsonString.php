<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

use App\Models\Currency;

class OpenCurrenciesJsonString implements Rule
{
    protected $errorMessage;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function validateObjectContent($openCurrency, $key = 0)
    {
        // Check Keys
        if (! isset($openCurrency['currency'])) {
            $this->errorMessage = 'Currency number ' . $key . ' does not have currency code';
            return false;
        }

        if (! isset($openCurrency['limit'])) {
            $this->errorMessage = 'Currency number ' . $key . ' does not have limit';
            return false;
        }

        if (! Currency::findCurrency($openCurrency['currency'])) {
            $this->errorMessage = $openCurrency . ' is not valid currency';
            return false;
        }

        return true;
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
        $openCurrencies = json_decode($value, true);

        if ($openCurrencies === null) return false;

        foreach ($openCurrencies as $key => $openCurrency)
            if (! $this->validateObjectContent($openCurrency, $key)) 
                return false;

        return true; 
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->errorMessage ?
            $this->errorMessage :
            'Wrong open currencies format.';
    }
}
