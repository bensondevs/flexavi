<?php

namespace App\Traits;

trait InputRequest 
{
	/**
	 * Set model for the validation request
	 * 
	 * @var \Illuminate\Database\Eloquent\Model
	 */
	private $model;

	/**
	 * Set of rules container
	 * 
	 * @var array
	 */
	protected $rules = [];

	/**
	 * Get requester user
	 * 
	 * @return \App\Models\User
	 */
	public function getUser()
	{
		$user = $this->user();
		$user->user_role;

		return $user;
	}

	/**
	 * Set a set of rules of the request
	 * 
	 * @param array $rules
	 * @return void
	 */
	public function setRules(array $rules)
	{
		$this->rules = $rules;
	}

	/**
	 * Add one rule to set of rules
	 * 
	 * @param string $inputKey
	 * @param array  $rule
	 * @return void
	 */
	public function addRule(string $inputKey, array $rule)
	{
		$this->rules[$inputKey] = $rule;
	}

	/**
	 * Return processed set of rules to the rules() request method
	 * 
	 * @return array
	 */
	public function returnRules()
	{
		$rules = $this->rules;

		/**
		 * UPDATE REQUEST ONLY
		 */
		if (is_updating_request()) {
			/**
			 * Loop and check all the requests. 
			 * If the old request is the same as new request, 
			 * let it just pass anyway
			 */
			foreach ($rules as $key => $rule) {
				if ($this->model) {
					if ($this->input($key) === $this->model->{$key}) {
						unset($rules[$key]);
						continue;
					}
				}

				if (! $this->has($key)) {
					unset($rules[$key]);
				}
			}
		}

		return $rules;
	}

	/**
	 * Get all requests from the validated request through rules
	 * 
	 * @return array
	 */
    public function onlyInRules()
    {
    	return $this->validated();
    }
}