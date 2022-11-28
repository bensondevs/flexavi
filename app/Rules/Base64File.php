<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Base64File implements Rule
{
    /**
     * Array of list of allowed extensions
     * 
     * @var array
     */
    private $allowedExtensions = [];

    /**
     * Create a new rule instance.
     *
     * @param  string|array  $allowedExtensions
     * @return void
     */
    public function __construct($allowedExtensions)
    {
        if (is_string($allowedExtensions)) {
            $allowedExtensions = explode(',', $allowedExtensions);
        }

        $this->allowedExtensions = $allowedExtensions;
        $this->errorMessage = 'Unknown error occured';
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
        if (! is_base64_string($value)) {
            $this->errorMessage = 'This is not base64 string file';
            return false;
        }

        $fileExtension = base64_extension($value);
        if (! in_array($fileExtension, $this->allowedExtensions)) {
            $this->errorMessage = 'The file extension does not match allowed extensions group.';
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
