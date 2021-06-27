<?php

namespace App\Enums\Appointment;

use BenSampo\Enum\Enum;

final class AppointmentType extends Enum
{
    const Inspection = 1;
    const Quotation = 2;
    const ExecuteWork = 3;
    const Warranty = 4;
    const PaymentPickUp = 5;
    const PaymentReminder = 6;

    private $types = [
        [
            'relationship' => 'inspection',
            'model' => 'App\Models\Inspection',
        ],
        [
            'relationship' => 'quotation',
            'model' => 'App\Models\Quotation',
        ],
        [
            'relationship' => 'executeWork',
            'model' => 'App\Models\ExecuteWork',
        ],
        [
            'relationship' => 'warranty',
            'model' => 'App\Models\Warranty',
        ],
        [
            'relationship' => 'paymentPickup',
            'model' => 'App\Models\PaymentPickup',
        ],
        [
            'relationship' => 'paymentReminder',
            'model' => 'App\Models\PaymentReminder',
        ]
    ];

    public function getTypeArray($typeValue)
    {
        return $this->types[$typeValue - 1];
    }
}