<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class Locale extends Enum
{
    /**
     * Indicate that the locale used is English.
     *
     * @const
     */
    const English = 'en';

    /**
     * Indicate that the locale used is Dutch.
     *
     * @const
     */
    const Dutch = 'nl';
}
