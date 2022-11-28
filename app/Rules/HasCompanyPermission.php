<?php

namespace App\Rules;

use App\Models\User\User;
use Illuminate\Contracts\Validation\Rule;

class HasCompanyPermission implements Rule
{
    protected $user;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($user = null)
    {
        $this->user = ($user && ($user instanceof User)) ?
            $user : auth()->user();
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param $companyId
     * @return bool
     */
    public function passes($attribute, $companyId): bool
    {
        return $this->user->hasCompanyPermission($companyId);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The user does not have permission to do such thing.';
    }
}
