<?php

use App\Enums\Address\AddressType;
use App\Enums\Appointment\{
    AppointmentStatus,
    AppointmentType
};
use App\Enums\Cost\CostableType;
use App\Enums\Quotation\{
    QuotationStatus,
    QuotationDamageCause, 
    QuotationPaymentMethod
};
use App\Enums\Work\WorkStatus;
use App\Enums\User\UserIdCardType;
use App\Enums\Invoice\{
    InvoiceStatus,
    InvoicePaymentMethod
};
use App\Enums\PaymentTerm\{
    PaymentTermStatus,
    PaymentTermPaymentMethod
};

return [
    // User
    UserIdCardType::class => [
        UserIdCardType::NationalIdCard => 'National ID Card',
        UserIdCardType::Passport => 'Passport',
        UserIdCardType::DrivingLicense => 'Driving License',
    ],

    // Address
    AddressType::class => [
        AddressType::VisitingAddress => 'Visiting Address',
        AddressType::InvoicingAddress => 'Invoicing Address',
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

    // Cost
    CostableType::class => [
        CostableType::Appointment => 'App\Models\Appointment',
        CostableType::Worklist => 'App\Models\Worklist',
        CostableType::Workday => 'App\Models\Workday',
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
        
        InvoiceStatus::FirstReminderSent => 'First Reminder Sent',
        InvoiceStatus::FirstReminderOverdue => 'First Reminder Overdue, send second reminder?',
        
        InvoiceStatus::SecondReminderSent => 'Second Reminder Sent',
        InvoiceStatus::SecondReminderOverdue => 'Second reminder overdue, send third reminder?',
        
        InvoiceStatus::ThirdReminderSent => 'Third Reminder Sent',
        InvoiceStatus::ThirdReminderOverdue => 'Third Reminder Overdue, sent debt collector?',

        InvoiceStatus::DebtCollectorSent => 'Debt Collector Sent',
        InvoiceStatus::PaidViaDebtCollector => 'Paid Via Debt Collector',
    ],

    // Invoice Payment Method
    InvoicePaymentMethod::class => [
        InvoicePaymentMethod::Cash => 'Cash',
        InvoicePaymentMethod::BankTransfer => 'Bank Transfer',
    ],

    // Payment Term Status
    PaymentTermStatus::class => [
        PaymentTermStatus::Unpaid => 'Unpaid',
        PaymentTermStatus::Paid => 'Paid',
        PaymentTermStatus::ForwardedToDebtCollector => 'Forwarded to Debt Collector',
    ],

    // Payment Term Payment
    PaymentTermPaymentMethod::class => [
        PaymentTermPaymentMethod::Cash => 'Cash',
        PaymentTermPaymentMethod::BankTransfer => 'Bank Transfer'
    ],
];