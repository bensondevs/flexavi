<?php

namespace App\Enums\Auth;

use BenSampo\Enum\Enum;

final class ResetPasswordType extends Enum
{
    const Email = 1;
    const SMS = 2;
}
