<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Base64MaxSize implements Rule
{
    private $errorMessage;

    private $maxByte = 0;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(int $maxByte)
    {
        $this->errorMessage = 'Unknown error occured.';

        $this->maxByte = $maxByte;
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
        $stringSize = strlen($value);
        if ($stringSize > $maxByte) {
            $this->errorMessage = 'The base64 string is too large. allowed max: ' . $this->maxByte / 1000 . ' MB';
            return false;
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
