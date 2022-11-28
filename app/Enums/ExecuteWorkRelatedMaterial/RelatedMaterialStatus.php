<?php

namespace App\Enums\ExecuteWorkRelatedMaterial;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class RelatedMaterialStatus extends Enum
{
    const Inactive =   0;
    const Active =   1;
}
