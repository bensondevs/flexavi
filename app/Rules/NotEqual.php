<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NotEqual implements Rule
{
    private $attribute;
    private $comparison;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $comparison)
    {
        $this->comparison = request()->input($comparison);
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
        $this->attribute = $attribute;

        return $this->comparison != $value;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The ' . $this->attribute . ' cannot be the same as ' . $this->comparison;
    }
}
