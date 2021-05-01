<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class JsonArrayMaxLength implements Rule
{
    protected $entity;
    protected $max;
    protected $amount;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($max = 5000, $entity = '')
    {
        $this->max = $max;
        $this->entity = $entity ?
            $entity : null;
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
        // Set entity
        $this->entity = ($this->entity) ? 
            $this->entity : $attribute;

        // Get the array
        $array = json_decode($value, true);

        $this->amount = count($array);

        return ($this->amount <= $this->max);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The list of ' . $this->entity . ' contains ' . $this->amount . ' items, while maximum value is ' . $this->max . ' items. Request rejected.' ;
    }
}
