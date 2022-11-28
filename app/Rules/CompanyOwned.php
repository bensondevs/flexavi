<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CompanyOwned implements Rule
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
    public function passes($attribute, $value): bool
    {
        $user = auth()->user();
        return $user->hasCompanyPermission($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'You don\'t have permission to use this company as input value.';
    }
}
