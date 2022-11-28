<?php

namespace App\Enums\Appointment;

use BenSampo\Enum\Enum;

final class AppointmentCancellationVault extends Enum
{
    /**
     * Appointment is cancelled by roofer
     * 
     * @var int
     */
    const Roofer = 1;

    /**
     * Appointment is cancelled by customer
     * 
     * @var int
     */
    const Customer = 2;
}
