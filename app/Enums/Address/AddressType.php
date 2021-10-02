<?php

namespace App\Enums\Address;

use BenSampo\Enum\Enum;

final class AddressType extends Enum
{
    const VisitingAddress = 1;
    const InvoicingAddress = 2;
    const Other = 3;
}
