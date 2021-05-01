<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Base64ImageRule implements Rule
{
    public $inputExtension;
    protected $allowedExtensions;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->allowedExtensions = [
            'jpg',
            'png',
            'svg',
            'jpeg',
        ];
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
        $imageExtension = explode(
            '/', 
            explode(
                ':', 
                substr($value, 0, strpos($value, ';')
            )
        )[1])[1];

        $this->inputExtension = $imageExtension;

        return in_array(
            $imageExtension, 
            $this->allowedExtensions
        );
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('The image uplaoded is not Base64 Image! The extension is: ') . $this->inputExtension;
    }
}
