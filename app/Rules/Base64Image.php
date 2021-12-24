<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Base64Image implements Rule
{
    /**
     * Allowed extensions of files
     * 
     * @var  array
     */
    protected $allowedExtensions = [
        'jpg',
        'png',
        'svg',
        'jpeg',
    ];

    /**
     * Error message for the user
     * 
     * @var string
     */
    private $errorMessage;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
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
        return __($this->errorMessage);
    }
}
