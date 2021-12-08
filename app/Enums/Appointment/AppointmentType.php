<?php

namespace App\Enums\Appointment;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

final class AppointmentType extends Enum implements LocalizedEnum
{
    /**
     * The appointment activity is inspection
     * The appointment will also have one inspection record
     * 
     * @var int
     */
    const Inspection = 1;

    /**
     * The appointment activity is quotation agreement
     * The appointment will also have one quotation
     * 
     * @var int
     */
    const Quotation = 2;

    /**
     * The appointment activity is execution of work
     * The appointment will have a collection of work executions
     * 
     * @var int
     */
    const ExecuteWork = 3;
    
    /**
     * 
     */
    const Warranty = 4;
    
    /**
     * 
     */
    const PaymentPickUp = 5;
    
    /**
     * 
     * 
     */
    const PaymentReminder = 6;
}