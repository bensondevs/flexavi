<?php

namespace App\Enums\Quotation;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

final class QuotationDamageCause extends Enum implements LocalizedEnum
{
    const Leak = 1;
    const FungusMold = 2;
    const BirdNuisance = 3;
    const StormDamage = 4;
    const OverdueMaintenance = 5;
}
