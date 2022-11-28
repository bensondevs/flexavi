<?php

declare(strict_types=1);

namespace App\Enums\Notification;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class NotificationPopulateType extends Enum
{
    public const Today = 1;
    public const Last3Days = 2;
    public const Last7Days = 3;
    public const Last30Days = 4;
    public const ThisYear = 5;
}
