<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class UniqueWithConditions implements Rule
{
    private $model;

    private $conditions;

    private $ignoreId;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($model, array $conditions, $ignoreId = null)
    {
        $this->conditions = $conditions;
        $this->ignoreId = $ignoreId;
        $this->model = $model;
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
        $model = $this->model->where($attribute, $value);
        foreach ($this->conditions as $key => $value) {
            $model->where($key, $value);
            if (request()->method() != "POST") if ($this->ignoreId) $model->whereNotIn('id', [$this->ignoreId]);
        }
        return $model->count() > 0 ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The record is already exists in database.';
    }
}
