<?php

use App\Enums\Quotation\QuotationStatus;
use App\Enums\Quotation\QuotationDamageCause;

return [
    // Quotations
    QuotationStatus::class => [
        QuotationStatus::Draft => 'Draft / Created',
        QuotationStatus::Sent => 'Sent',
        QuotationStatus::Revised => 'Revised',
        QuotationStatus::Honored => 'Honored',
        QuotationStatus::Cancelled => 'Cancelled',
    ],

    QuotationDamageCause::class => [
        QuotationDamageCause::Leak => 'Leak',
        QuotationDamageCause::FungusMold => 'Fungus/Mold',
        QuotationDamageCause::BirdNuisance => 'Bird Nuisance',
        QuotationDamageCause::StormDamage => 'Storm Damage',
        QuotationDamageCause::OverdueMaintenance => 'Overdue Maintenance',
    ],
];