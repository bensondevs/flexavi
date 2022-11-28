<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ExistInJsonColumn implements Rule
{
    protected $table;
    protected $column;
    protected $property;
    protected $userOwnership;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($table, $column, $property = 'id', $userOwnership = 'user_id')
    {
        $this->table = $table;
        $this->column = $column;
        $this->property = $property;
        $this->userOwnership = $userOwnership;
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
        $targetRecord = db($this->table)
            ->where($this->userOwnership, auth()->user()->id)
            ->first();
        $targetColoumn = $targetRecord->{$this->column};
        $columnValues = collect(json_decode($targetColoumn, true));

        foreach ($columnValues as $colval) {
            if ($colval[$this->property] == $value)
                return true;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The record you are looking for is not exist';
    }
}
