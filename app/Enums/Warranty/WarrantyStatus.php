<?php

namespace App\Enums\Warranty;

use BenSampo\Enum\Enum;

final class WarrantyStatus extends Enum
{
    /**
     * Warranty is created
     * 
     * @var int
     */
    const Created = 1;

    /**
     * Warranty work is in process
     * 
     * @var int
     */
    const InProcess = 2;

    /**
     * Warranty work is finished
     * 
     * @var int
     */
    const Finished = 3;

    /**
     * Warranty work is unfinished
     * 
     * @var int
     */
    const Unfinished = 4;
}
