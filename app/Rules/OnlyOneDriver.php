<?php

namespace App\Rules;

use App\Enums\CarRegisterTimeEmployee\PassangerType;
use App\Models\Car\CarRegisterTime;
use Illuminate\Contracts\Validation\Rule;

class OnlyOneDriver implements Rule
{
    private $time;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(CarRegisterTime $time)
    {
        $this->time = $time;
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
        if ($value == PassangerType::Driver) {
            if ($this->time->hasDriver()) {
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
        return 'There is already a driver in the car for this assigned time.';
    }
}
