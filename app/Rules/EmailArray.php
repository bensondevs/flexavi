<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class EmailArray implements Rule
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

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $emails = json_decode($value);

        if (! $emails) {
            $this->errorMessage = 'This is not correct array format';
            return false;
        }

        foreach ($emails as $key => $email) {
            $cleanEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
            $correctEmail = filter_var($cleanEmail, FILTER_VALIDATE_EMAIL);

            if (! $correctEmail) {
                $this->errorMessage = '`' . $cleanEmail . '` is not correct email format';
                return false;
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->errorMessage;
    }
}
