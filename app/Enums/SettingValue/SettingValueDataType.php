<?php

namespace App\Enums\SettingValue;

use BenSampo\Enum\Enum;

final class SettingValueDataType extends Enum
{
    const Text = 1;
    const Numeric = 2;
    const Json = 3;
}
