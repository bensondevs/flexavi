<?php

namespace App\Enums\Setting;

use BenSampo\Enum\Enum;

final class SettingInputType extends Enum
{
    /**
     * Setting input type is standard text
     * 
     * @var int
     */
    const Text = 1;

    /**
     * Setting input type is numeric
     * 
     * @var int
     */
    const Number = 2;

    /**
     * Setting input type is select-options
     * 
     * @var int
     */
    const SelectOptions = 3;
}
