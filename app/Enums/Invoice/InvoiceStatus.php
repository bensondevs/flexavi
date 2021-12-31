<?php

namespace App\Enums\Invoice;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

final class InvoiceStatus extends Enum implements LocalizedEnum
{
    /**
     * Invoice is just created
     * 
     * @var int
     */
    const Created = 1;

    /**
     * Invoice has been printed or sent
     * 
     * @var int
     */
    const Sent = 2;

    /**
     * Invoice is paid by the customer
     * 
     * @var int
     */
    const Paid = 3;

    /**
     * Payment of invoice is already overdue
     * 
     * @var int
     */
    const PaymentOverdue = 4;

    /**
     * First reminder has been sent
     * 
     * @var int
     */
    const FirstReminderSent = 5;

    /**
     * First reminder has been overdue
     * 
     * @var int
     */
    const FirstReminderOverdue = 6;
    
    /**
     * Second reminder has been sent
     * 
     * @var int
     */
    const SecondReminderSent = 7;

    /**
     * Second reminder has been overdue
     * 
     * @var int
     */
    const SecondReminderOverdue = 8;

    /**
     * Third reminder has been sent
     * 
     * @var int
     */
    const ThirdReminderSent = 9;

    /**
     * Third reminder has been overdue
     * 
     * @var int
     */
    const ThirdReminderOverdue = 10;

    /**
     * Debt collector has been sent to collect
     * the problematic payment
     * 
     * @var int
     */
    const DebtCollectorSent = 11;

    /**
     * Invoice has been paid via debt collector
     * 
     * @var int
     */
    const PaidViaDebtCollector = 12;
}