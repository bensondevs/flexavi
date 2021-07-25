<?php

use App\Enums\User\UserIdCardType;

use App\Enums\Appointment\AppointmentStatus;
use App\Enums\Appointment\AppointmentType;

use App\Enums\Quotation\QuotationStatus;
use App\Enums\Quotation\QuotationDamageCause;
use App\Enums\Quotation\QuotationPaymentMethod;

use App\Enums\Work\WorkStatus;

use App\Enums\Invoice\InvoiceStatus;
use App\Enums\Invoice\InvoicePaymentMethod;

use App\Enums\PaymentTerm\PaymentTermStatus;

return [
    // User
    UserIdCardType::class => [
        UserIdCardType::NationalIdCard => 'National ID Card',
        UserIdCardType::Passport => 'Passport',
        UserIdCardType::DrivingLicense => 'Driving License',
    ],

    // Appointment
    AppointmentStatus::class => [
        AppointmentStatus::Created => 'Created',
        AppointmentStatus::InProcess => 'In Process',
        AppointmentStatus::Processed => 'Processed',
        AppointmentStatus::Calculated => 'Calculated',
        AppointmentStatus::Cancelled => 'Cancelled',
    ],

    AppointmentType::class => [
        AppointmentType::Inspection => 'Inspection',
        AppointmentType::Quotation => 'Quotation',
        AppointmentType::ExecuteWork => 'Execute Work',
        AppointmentType::Warranty => 'Warranty',
        AppointmentType::PaymentPickUp => 'Payment Pick-Up',
        AppointmentType::PaymentReminder => 'Payment Reminder',
    ],

    // Quotation
    QuotationStatus::class => [
        QuotationStatus::Draft => 'Draft / Created',
        QuotationStatus::Sent => 'Sent',
        QuotationStatus::Revised => 'Revised',
        QuotationStatus::Honored => 'Honored',
        QuotationStatus::Cancelled => 'Cancelled',
    ],

    QuotationDamageCause::class => [
        QuotationDamageCause::Leak => 'Leak',
        QuotationDamageCause::FungusMold => 'Fungus / Mold',
        QuotationDamageCause::BirdNuisance => 'Bird Nuisance',
        QuotationDamageCause::StormDamage => 'Storm Damage',
        QuotationDamageCause::OverdueMaintenance => 'Overdue Maintenance',
    ],

    QuotationPaymentMethod::class => [
        QuotationPaymentMethod::Cash => 'Cash',
        QuotationPaymentMethod::BankTransfer => 'Bank Transfer',
    ],

    // Work
    WorkStatus::class => [
        WorkStatus::Created => 'Created',
        WorkStatus::InProcess => 'In Process',
        WorkStatus::Finished => 'Finished',
        WorkStatus::Unfinished => 'Unfinished',
    ],

    // Invoice
    InvoiceStatus::class => [
        InvoiceStatus::Created => 'Created / Draft',
        InvoiceStatus::Sent => 'Sent / Definitive',
        InvoiceStatus::Paid => 'Paid',
        InvoiceStatus::PaymentOverdue => 'Payment Overdue',
        InvoiceStatus::FirstReminder => 'Overdue, send first reminder?',
        InvoiceStatus::FirstReminderSent => 'First Reminder Sent',
        InvoiceStatus::SecondReminder => 'First reminder sent, send the second reminder?',
        InvoiceStatus::SecondReminderSent => 'Second Reminder Sent',
        InvoiceStatus::ThirdReminder => 'Second Reminder Sent, send the third reminder?',
        InvoiceStatus::ThirdReminderSent => 'Third Reminder Sent',
        InvoiceStatus::OverdueDebtCollector => 'Overdue, debt collector?',
        InvoiceStatus::SentDebtCollector => 'Sent to debt collector',
        InvoiceStatus::PaidViaDebtCollector => 'Paid via Debt collector',
    ],

    InvoicePaymentMethod::class => [
        InvoicePaymentMethod::Cash => 'Cash',
        InvoicePaymentMethod::BankTransfer => 'Bank Transfer',
    ],

    // Payment Term
    PaymentTermStatus::class => [
        PaymentTermStatus::Unpaid => 'Unpaid',
        PaymentTermStatus::Paid => 'Paid',
        PaymentTermStatus::ForwardedToDebtCollector => 'Forwarded to Debt Collector',
    ],
];