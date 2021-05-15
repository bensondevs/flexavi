<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class SignableType extends Enum
{
    const Quotation = 'App\Models\Quotation';
    const WorkContract = 'App\Models\WorkContrat';
}
