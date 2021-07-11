<?php

namespace App\Enums\Appointment;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

final class AppointmentStatus extends Enum implements LocalizedEnum
{
    const Created = 1;
    const InProcess = 2;
    const Processed = 3;
    const Calculated = 4;
    const Cancelled = 5;
}