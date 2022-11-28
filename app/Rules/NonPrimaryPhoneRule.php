<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NonPrimaryPhoneRule implements Rule
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
        $phone = db('phones')
            ->where('id', $value)
            ->whereNull('deleted_at')
            ->first();

        if (! $phone) return true;

        return (bool) (! $phone->is_primary);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('The phone you selected is already primary!');
    }
}
