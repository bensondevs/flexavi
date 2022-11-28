<?php

namespace App\Enums\Invoice;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class InvoiceConfig extends Enum
{
    // Days before due date
    const FirstReminder =   5;


    // Days after first reminder
    const SecondReminder =   4;


    // Days after invoice due date
    const ThirdReminder =   1;


    // Days after invoice due date
    const DebtCollector =   5;
}
