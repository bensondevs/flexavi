<?php declare(strict_types=1);

namespace App\Enums\SubscriptionPlanPeriod;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class DurationType extends Enum
{
    const Weekly = 1;
    const Monthly = 2;
    const Yearly = 3;
}
