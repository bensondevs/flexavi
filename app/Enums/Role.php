<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class Role extends Enum
{
    const Admin = 'admin';
    const Owner = 'owner';
    const Employee = 'employee';
    const Customer = 'customer';
}
