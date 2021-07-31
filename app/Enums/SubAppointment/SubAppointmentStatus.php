<?php

namespace App\Enums\SubAppointment;

use BenSampo\Enum\Enum;

final class SubAppointmentStatus extends Enum
{
    const Created = 1;
    const InProcess = 2;
    const Processed = 3;
    const Cancelled = 4;
}
