<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ExistInDB implements Rule
{
    /**
     * Table name as the target of checking
     * 
     * @var string
     */
    protected $table;

    /**
     * Table column to as the target of checking
     * 
     * @var string
     */
    protected $column;

    /**
     * Create a new rule instance.
     *
     * @param string  $table
     * @param string|null  $column
     * @return void
     */
    public function __construct(string $table, string $column = '')
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
        $table = $this->table;
        $column = $this->column ?: $attribute;

        return db($table)->where($column, $value)->exists();
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
