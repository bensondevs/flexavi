<?php

namespace App\Enums\Appointment;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

final class AppointmentType extends Enum implements LocalizedEnum
{
    const Inspection = 1;
    const Quotation = 2;
    const ExecuteWork = 3;
    const Warranty = 4;
    const PaymentPickUp = 5;
    const PaymentReminder = 6;
}