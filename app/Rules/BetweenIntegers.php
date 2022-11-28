<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class BetweenIntegers implements Rule
{
    /**
     * The minimum value of the allowed input
     * 
     * @var int
     */
    protected $minimum;

    /**
     * The maximum value of the allowed input
     * 
     * @var int
     */
    protected $maximum;

    /**
     * Create a new rule instance.
     *
     * @param  int  $minimum
     * @param  int  $maximum
     * @return void
     */
    public function __construct(int $minimum, int $maximum)
    {
        $this->minimum = $minimum;
        $this->maximum = $maximum;
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
        $integer = (int) $value;

        return 
            ($integer >= $this->minimum) && 
            ($integer <= $this->maximum);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('The choice you pick is not available');
    }
}
