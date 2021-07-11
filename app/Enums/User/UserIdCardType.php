<?php

namespace App\Enums\User;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

final class UserIdCardType extends Enum implements LocalizedEnum
{
    const NationalIdCard = 1;
    const Passport = 2;
    const DrivingLicense = 3;
}
