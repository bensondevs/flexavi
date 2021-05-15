<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class AppointmentableType extends Enum
{
    const Inspection = 'App\Models\Inspection';
    const Quotation = 'App\Models\Quotation';
    const Work = 'App\Models\Work';
    const WarrantyClaim = 'App\Models\WarrantyClaim';
    const PaymentTerm = 'App\Models\PaymentTerm';

    const inspection = Inspection;
    const quotation = Quotation;
    const work = Work;
    const warranty_claim = WarrantyClaim;
    const warrantyClaim = warranty_claim;
    const payment_term = PaymentTerm;
    const paymentTerm = payment_term;
}
