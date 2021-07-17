<?php

namespace App\Enums\RegisterInvitation;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

final class RegisterInvitationStatus extends Enum implements LocalizedEnum
{
    const Active = 1;
    const Used = 2;
    const Expired = 3;
}