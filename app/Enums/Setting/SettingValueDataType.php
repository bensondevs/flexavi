<?php

namespace App\Enums\Setting;

use BenSampo\Enum\Enum;

final class SettingValueDataType extends Enum
{
    /**
     * Value data type is string
     * 
     * @var int
     */
    const String = 1;

    /**
     * Value data type is integer
     * 
     * @var int
     */
    const Int = 2;

    /**
     * Value data type is float
     * 
     * @var int
     */
    const Float = 3;

    /**
     * Value data type is double
     * 
     * @var int
     */
    const Double = 4;

    /**
     * Value data type is boolean
     * 
     * @var int
     */
    const Bool = 5;

    /**
     * Value data type is JSON string
     * 
     * This will need a attribute value cast to convert it to array
     * 
     * @var int
     */
    const Json = 6;
}
