<?php

declare(strict_types=1);

namespace App\Enums\Setting;

use BenSampo\Enum\Enum;

final class SettingModule extends Enum
{
    public const Dashboard = 1 ;
    public const Employee = 2 ;
    public const Customer = 3;
    public const Invoice = 4;
    public const Quotation = 5;
    public const Company = 6;
}
