<?php

namespace App\Traits;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;

trait InputRequest
{
    /**
     * Set of rules container
     *
     * @var array
     */
    protected array $rules = [];
    /**
     * Set model for the validation request
     *
     * @var Model
     */
    private Model $model;

    /**
     * Get requester user
     *
     * @return User
     */
    public function getCurrentUser(): User
    {
        return $this->user()->fresh();
    }

    /**
     * Set a set of rules of the request
     *
     * @param array $rules
     * @return void
     */
    public function setRules(array $rules): void
    {
        $this->rules = $rules;
    }

    /**
     * Add one rule to set of rules
     *
     * @param string $inputKey
     * @param array $rule
     * @return void
     */
    public function addRule(string $inputKey, array $rule): void
    {
        $this->rules[$inputKey] = $rule;
    }

    /**
     * Return processed set of rules to the rules() request method
     *
     * @return array
     */
    public function returnRules(): array
    {
        /**
         *
         * **********************************************************************
         * IDK why the previous developer add this buggy logic!
         * These code will breaks the whole functionality of Laravel Validation.
         * All validation rules WILL AND SHOULD be respected by Laravel, no matter
         * whats the HTTP request type is.
         *
         * Consider to contant current backend engineer if you want to
         * update the logic below.
         *
         * latest update by Ezra Lazuardy <ezra@exclolab.com>
         * **********************************************************************
         */

        // UPDATE REQUEST ONLY
        // if (is_updating_request()) {
        //     foreach ($rules as $key => $rule) {
        //         if ($this->model) {
        //             if ($this->input($key) === $this->model->{$key}) {
        //                 unset($rules[$key]);
        //                 continue;
        //             }
        //         }
        //         if (!$this->has($key)) {
        //             unset($rules[$key]);
        //         }
        //     }
        // }

        return $this->rules;
    }

    /**
     * Get all requests from the validated request through rules
     *
     * @return array
     */
    public function onlyInRules(): array
    {
        return $this->validated();
    }
}
