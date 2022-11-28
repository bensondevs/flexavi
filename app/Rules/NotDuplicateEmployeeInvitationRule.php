<?php

namespace App\Rules;

use App\Enums\EmployeeInvitation\EmployeeInvitationStatus;
use App\Models\Employee\EmployeeInvitation;
use Illuminate\Contracts\Validation\Rule;

class NotDuplicateEmployeeInvitationRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return EmployeeInvitation::whereInvitedEmail($value)
            ->whereStatus(EmployeeInvitationStatus::Active) // check if there is already active invitation
            ->where("expiry_time", ">", now()) // check if there is valid (unexpired) invitation
            ->doesntExist(); // if not exist the invitation is invitiable and otherwise
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The invitation is already exist and still active.';
    }
}
