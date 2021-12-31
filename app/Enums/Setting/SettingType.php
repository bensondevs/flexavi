<?php

namespace App\Enums\Setting;

use BenSampo\Enum\Enum;

final class SettingType extends Enum
{
    /**
     * System setting type
     * 
     * @var int
     */
    const System = 1;

    /**
     * Company setting type
     * 
     * @var int
     */
    const Company = 2;

    /**
     * Appointment setting type
     * 
     * @var int
     */
    const Appointment = 3;

    /**
     * Workday setting type
     * 
     * @var int
     */
    const Workday = 4;

    /**
     * Worklist setting type
     * 
     * @var int
     */
    const Worklist = 5;

    /**
     * Invoice setting type
     * 
     * @var int
     */
    const Invoice = 6;

    /**
     * Quotation setting type
     * 
     * @var int
     */
    const Quotation = 7;
}
