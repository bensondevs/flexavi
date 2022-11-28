<?php declare(strict_types=1);

namespace App\Enums\User;

use BenSampo\Enum\Enum;

final class UserIdCardType extends Enum
{
    const NationalIdCard = 1;
    const Passport = 2;
    const DrivingLicense = 3;
}
