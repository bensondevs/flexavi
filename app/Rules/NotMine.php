<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NotMine implements Rule
{
    protected $property;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($property = null)
    {
        $this->property = $property;
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
        $this->property = $this->property ?
            $this->property : 
            $attribute;

        $isNotMine = (auth()->user()->{$this->property} != $value);

        return ($isNotMine);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Cannot pick your own self as the target of this operation.';
    }
}
