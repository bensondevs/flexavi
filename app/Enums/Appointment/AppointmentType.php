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
     * The appointment activity is warranty
     * @var int
     */
    const Warranty = 4;

    /**
     * The appointment activity is Payment Pickup
     * @var int
     */
    const PaymentPickUp = 5;

    /**
     * The appointment activity is Payment Reminder
     * @var int
     */
    const PaymentReminder = 6;
}
