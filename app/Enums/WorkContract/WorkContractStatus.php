<?php declare(strict_types=1);

namespace App\Enums\WorkContract;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class WorkContractStatus extends Enum
{
    const Drafted = 1;
    const Sent = 2;
    const Signed = 3;
    const Nullified = 4;
}
