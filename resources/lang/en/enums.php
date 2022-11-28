<?php

use App\Enums\Address\AddressType;
use App\Enums\Appointment\{AppointmentStatus, AppointmentType};
use App\Enums\Cost\CostableType;
use App\Enums\Customer\CustomerSalutation;
use App\Enums\Invoice\{InvoicePaymentMethod, InvoiceReminderSentType, InvoiceStatus};
use App\Enums\PaymentTerm\{PaymentTermPaymentMethod, PaymentTermStatus};
use App\Enums\Quotation\{QuotationDamageCause, QuotationPaymentMethod, QuotationStatus};
use App\Enums\Setting\SettingModule;
use App\Enums\User\UserIdCardType;
use App\Enums\Work\WorkStatus;

return [
    InvoiceReminderSentType::class => [
        InvoiceReminderSentType::InHouseUser => 'Sent reminder for me only',
        InvoiceReminderSentType::InHouseUserWithCustomer => 'Sent reminder for me & customer'
    ],

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
        CostableType::Appointment => 'App\Models\Appointment\Appointment',
        CostableType::Worklist => 'App\Models\Worklist\Worklist',
        CostableType::Workday => 'App\Models\Workday\Workday',
    ],

    // Quotation
    QuotationStatus::class => [
        QuotationStatus::Drafted => 'Drafted',
        QuotationStatus::Sent => 'Sent',
        QuotationStatus::Nullified => 'Nullified',
        QuotationStatus::Signed => 'Signed',
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
        InvoiceStatus::Drafted => 'Drafted',
        InvoiceStatus::Paid => 'Paid',
        InvoiceStatus::Sent => 'Sent',
        InvoiceStatus::PaymentOverdue => 'Payment Overdue',

        InvoiceStatus::FirstReminderSent => 'First Reminder Sent',
        InvoiceStatus::FirstReminderOverdue => 'First Reminder Overdue, send second reminder?',

        InvoiceStatus::SecondReminderSent => 'Second Reminder Sent',
        InvoiceStatus::SecondReminderOverdue => 'Second Reminder Overdue, send third reminder?',

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

    // Customer Salutation
    CustomerSalutation::class => [
        CustomerSalutation::Mr => 'Mr.',
        CustomerSalutation::Mrs => 'Mrs.',
        CustomerSalutation::Ms => 'Ms.',
    ],

    // Setting Module
    SettingModule::class => [
        //
    ]
];
