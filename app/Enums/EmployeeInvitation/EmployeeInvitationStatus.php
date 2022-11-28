<?php

namespace App\Enums\EmployeeInvitation;

use BenSampo\Enum\Enum;

final class EmployeeInvitationStatus extends Enum
{
    /**
     * Indicate that employee invitation is still active
     * and usable to be used in the registration.
     *
     * @const
     */
    const Active = 1;

    /**
     * Indicate that employee invitation is already used
     * and no longer can be used for the registration.
     *
     * @const
     */
    const Used = 2;

    /**
     * Indicate that employee invitation is already expired
     * and no longer can be used for the registration.
     *
     * @const
     */
    const Expired = 3;

    /**
     * Indicate that employee invitation is already cancelled
     * and no longer can be used for the registration.
     *
     * @const
     */
    const Cancelled = 4;
}
