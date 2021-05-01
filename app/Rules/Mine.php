<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Mine implements Rule
{
    protected $table;
    protected $userMatchAttribute;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($table, $userMatchAttribute = 'user_id')
    {
        $this->table = $table;
        $this->userMatchAttribute = $userMatchAttribute;
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
        $target = db($this->table)
            ->where('id', $value)
            ->first();

        return $target->{$userMatchAttribute} == auth()->user()->id;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'You cannot do this operation.';
    }
}
