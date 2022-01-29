<?php

namespace App\Enums\Customer;

use BenSampo\Enum\Enum;

final class CustomerAcquisition extends Enum
{
    /**
     * Customer is acquired through website
     * 
     * @var int
     */
    const Website = 1;

    /**
     * Customer is acquired through call
     * 
     * @var int
     */
    const Call = 2;

    /**
     * Customer is acquired through company itself
     * 
     * @var int
     */
    const Company = 3;
}
