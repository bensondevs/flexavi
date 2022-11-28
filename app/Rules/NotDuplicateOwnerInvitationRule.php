<?php

namespace App\Rules;

use App\Enums\OwnerInvitation\OwnerInvitationStatus;
use App\Models\Owner\OwnerInvitation;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;

class NotDuplicateOwnerInvitationRule implements Rule
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
    public function passes($attribute, $value)
    {
        return OwnerInvitation::whereInvitedEmail($value)
            ->whereStatus(OwnerInvitationStatus::Active) // check if there is already active invitation
            ->where("expiry_time", ">", now()) // check if there is valid (unexpired) invitation
            ->doesntExist(); // if not exist the invitation is invitiable and otherwise
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The invitation is already exist and still active.';
    }
}
