<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class UniqueWithConditions implements Rule
{
    private $existedRecords;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($model, array $conditions)
    {
        foreach ($conditions as $key => $value) 
            $model->where($key, $value);
        $this->existedRecords = $model->get();
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
        return $this->existedRecords
            ->where($attribute, $value)
            ->count() < 1;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The record is exists in database.';
    }
}
