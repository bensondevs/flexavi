<?php

namespace App\Enums\OwnerInvitation;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class OwnerInvitationStatus extends Enum
{
    public const Active = 1;
    public const Used = 2;
    public const Expired = 3;
    public const Cancelled = 4 ;
}
