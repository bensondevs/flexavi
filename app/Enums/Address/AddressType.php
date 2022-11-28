<?php

namespace App\Enums\Address;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

final class AddressType extends Enum implements LocalizedEnum
{
    /**
     * Address for visiting
     *
     * @var int
     */
    const VisitingAddress = 1;

    /**
     * Address for invoicing
     *
     * @var int
     */
    const InvoicingAddress = 2;

    /**
     * Custom type of address
     *
     * @var int
     */
    const Other = 3;
}
