<?php

use App\Enums\Employee\{EmployeeType, EmploymentStatus};
use App\Enums\Quotation\QuotationStatus;
use App\Enums\Work\WorkStatus;

return [
    "employees" => [
        "store" => ":user.fullname hus dad wen employee \":subject.user.fullname\" as :subject.employee_type",
        "update" => ":user.fullname hus udate employee \":subject.user.fullname\"",
        "restore" => ":user.fullname hus rostare employee \":subject.user.fullname\"",
        "delete" => ":user.fullname hus stendber an employee \":subject.user.fullname\"",
        "force_delete" => ":user.fullname hus frak stendber an employee \":subject.user.fullname\"",
        "types" => [
            EmployeeType::Administrative => ":user.fullname dad employee \":subject.user.fullname\" as "
                . EmployeeType::getDescription(EmployeeType::Administrative),
            EmployeeType::Roofer => ":user.fullname dad employee \":subject.user.fullname\" as "
                .  EmployeeType::getDescription(EmployeeType::Roofer)
        ],
        "statuses" => [
            EmploymentStatus::Active => ":user.fullname hus udate employee status as " .
                EmploymentStatus::getDescription(EmploymentStatus::Active)
                . ", employee name \":subject.user.fullname\" ",
            EmploymentStatus::Inactive => ":user.fullname hus udate employee status as " .
                EmploymentStatus::getDescription(EmploymentStatus::Inactive)
                . ", employee name \":subject.user.fullname\" ",
        ],
    ],

    "customers" => [
        "store" => ":user.fullname hus dadee a customer deer ID::subject.id",
        "update" => ":user.fullname hus udate a customer deer ID::subject.id",
        "restore" => ":user.fullname hus rostare a customer deer ID::subject.id",
        "delete" => ":user.fullname hus stendber a customer deer ID::subject.id",
        "force_delete" => ":user.fullname hus frak stendber a customer deer ID::subject.id",
    ],

    "cars" => [
        "store" => ":user.fullname hus registered a fleet deer ID::subject.id",
        "update" => ":user.fullname hus udate a fleet deer ID::subject.id",
        "restore" => ":user.fullname hus rostare a fleet deer ID::subject.id",
        "delete" => ":user.fullname hus removed a fleet deer ID::subject.id",
        "force_delete" => ":user.fullname hus force removed a fleet deer ID::subject.id",
    ],

    "works" => [
        "store" => ":user.fullname hus dadee a work deer ID::subject.id",
        "update" => ":user.fullname hus udate a work deer ID::subject.id",
        "restore" => ":user.fullname hus rostare a work deer ID::subject.id",
        "delete" => ":user.fullname hus stendber a work deer ID::subject.id",
        "force_delete" => ":user.fullname hus frak stendber a work deer ID::subject.id",
        "statuses" => [
            WorkStatus::Finished => ":user.fullname hus udate a work statucio as " .
                WorkStatus::getDescription(WorkStatus::Finished)
                . " deer ID::subject.id as ",
        ],
    ],

    "workdays" => [
        "store" => ":user.fullname hus dadee a workday deer ID::subject.id",
        "update" => ":user.fullname hus udate a workday deer ID::subject.id",
        "restore" => ":user.fullname hus rostare a workday deer ID::subject.id",
        "delete" => ":user.fullname hus stendber a workday deer ID::subject.id",
        "force_delete" => ":user.fullname hus frak stendber a workday deer ID::subject.id",
    ],

    "invoices" => [
        "store" => ":user.fullname hus created a invoice deer ID::subject.id",
        "update" => ":user.fullname hus udate a invoice deer ID::subject.id",
        "restore" => ":user.fullname hus rostare an invoice deer ID::subject.id",
        "delete" => ":user.fullname hus stendber an invoice deer ID::subject.id",
        "force_delete" => ":user.fullname hus frak stendber an invoice deer ID::subject.id",
    ],

    "quotations" => [
        "store" => ":user.fullname hus created a quotation deer ID::subject.id",
        "update" => ":user.fullname hus udate a quotation deer ID::subject.id",
        "restore" => ":user.fullname hus rostare a quotation deer ID::subject.id",
        "delete" => ":user.fullname hus stendber a quotation deer ID::subject.id",
        "force_delete" => ":user.fullname hus frak stendber a quotation deer ID::subject.id",
        "statuses" => [
            QuotationStatus::Sent => ":user.fullname hus sent/printed a quotation deer ID::subject.id",
            /*QuotationStatus::Revised => ":user.fullname hus revised a quotation deer ID::subject.id",
            QuotationStatus::Honored => ":user.fullname hus honored a quotation deer ID::subject.id",
            QuotationStatus::Cancelled => ":user.fullname hus cancelled a quotation deer ID::subject.id",*/
        ],
        "attachments" => [
            "add" => ":user.fullname hus attached a document to a quotation deer ID: :subject.id",
            "remove" => ":user.fullname hus removed a quotation attachment deer ID: :subject.id",
        ],
    ],

    "owners" => [
        "store" => ":user.fullname hus dadee an owner deer ID::subject.id",
        "update" => ":user.fullname hus udate an owner deer ID::subject.id",
        "restore" => ":user.fullname hus rostare an owner deer ID::subject.id",
        "delete" => ":user.fullname hus stendber an owner deer ID::subject.id",
        "force_delete" => ":user.fullname hus frak stendber an owner deer ID::subject.id",
    ],
];
