<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

use DB;

class PhoneNotExistsRule implements Rule
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
        $countryCode = request()->input('country_code');
        $phone = request()->input('phone');

        $exist = db('phones')
            ->where('country_code', $countryCode)
            ->where('phone', $phone)
            ->whereNull('deleted_at')
            ->first();

        if (! $exist) return true;

        // If exist, let it pass, 
        // later we will only send OTP verification
        return (! $exist->verified_at);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Phone number is exist!';
    }
}
