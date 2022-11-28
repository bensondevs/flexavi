<?php

namespace App\Enums\Appointment;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

final class AppointmentStatus extends Enum implements LocalizedEnum
{
    /**
     * The first stage of every appointment
     *
     * @var int
     */
    const Created = 1;

    /**
     * The appointment has been executed
     *
     * @var int
     */
    const InProcess = 2;

    /**
     * The appointment execution is done,
     * yet in the middle or after of administrative process
     *
     * @var int
     */
    const Processed = 3;

    /**
     * The appointment has been calculated by the system
     *
     * @var int
     */
    const Calculated = 4;

    /**
     * The appointment has been cancelled
     *
     * @var int
     */
    const Cancelled = 5;

    /**
     * The appointment is draft
     *
     * @var int
     */
    const Draft = 6;
}
