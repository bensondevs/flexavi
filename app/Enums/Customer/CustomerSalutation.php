<?php

namespace App\Enums\Customer;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class CustomerSalutation extends Enum implements LocalizedEnum
{
    const Mr = 1;
    const Ms = 2;
    const Mrs = 3;
}
