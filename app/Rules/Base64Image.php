<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Base64Image implements Rule
{
    public $extension;
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
        $extension = explode('/', explode(':', substr($value, 0, strpos($value, ';')))[1])[1];
        $this->extension = $extension;

        return in_array($extension, $this->allowedExtensions);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('The image uplaoded is not Base64 Image! The extension is: ') . $this->extension;
    }
}
