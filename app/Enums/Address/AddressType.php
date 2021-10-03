<?php

namespace App\Enums\Address;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

final class AddressType extends Enum implements LocalizedEnum
{
    const VisitingAddress = 1;
    const InvoicingAddress = 2;
    const Other = 3;
}
