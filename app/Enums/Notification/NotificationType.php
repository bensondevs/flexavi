<?php declare(strict_types=1);

namespace App\Enums\Notification;

use BenSampo\Enum\Enum;

final class NotificationType extends Enum
{
    const Dashboard = 1;
    const Company = 2;
    const Employee = 3;
    const Customer = 4;
    const Fleet = 5;
    const Works = 6;
    const Workday = 7;
    const Invoice = 8;
    const Quotation = 9;
    const Owner = 10;
    const Log = 11;
}
