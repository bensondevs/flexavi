<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

use DB;

class ExistInDB implements Rule
{
    protected $table;
    protected $column;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($table, $column = null)
    {
        $this->table = $table;
        $this->column = $column;
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
        $found = db($this->table)
            ->where($this->column ? $this->column : $attribute, $value)
            ->count() > 0;

        return $found;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The value you inserted is not found in our records!';
    }
}
