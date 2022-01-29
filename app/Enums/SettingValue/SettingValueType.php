<?php

namespace App\Enums\SettingValue;

use BenSampo\Enum\Enum;

final class SettingValueType extends Enum
{
    /**
     * Setting value type of default
     * 
     * @var int
     */
    const Default = 1;

    /**
     * Setting value type of company
     * 
     * @var int
     */
    const Company = 2;
}
