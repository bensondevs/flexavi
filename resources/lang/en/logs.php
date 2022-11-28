<?php

return [
    "user" => [
        "login" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has Login",
        "logout" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has Logout",
        "updates" => [
            "email" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated :subject.role_name \":subject.fullname\" email from
            \":props.old.subject.email\" to \":subject.email\"",
            "fullname" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated \":props.old.subject.fullname\" fullname
                to \":subject.fullname\"",
            "phone" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated :subject.role_name \":subject.fullname\" phone number from \":props.old.subject.phone\" to \":subject.phone\"",
        ],
    ],

    "company" => [
        "store" => "<CAUSER_ROLE> \"<USER_FULLNAME>\" has add Company \":subject.company_name\"",
        "updates" => [
            "company_name" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Company name from \":props.old.subject.company_name\" to \":subject.company_name\"",
            "email" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Company email from \":props.old.subject.email\" to \":subject.email\"",
            "phone_number" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Company phone number from \":props.old.subject.phone_number\" to \":subject.phone_number\"",
            "vat_number" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Company vat number from \":props.old.subject.vat_number\" to \":subject.vat_number\"",
            "commerce_chamber_number" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Company commerce chamber number from \":props.old.subject.commerce_chamber_number\" to \":subject.commerce_chamber_number\"",
            // "company_logo_path" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Company Logo",
            "company_website_url" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Company website url from \":props.old.subject.company_website_url\" to \":subject.company_website_url\"",
            "mollie_customer_id" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Company Mollie ID from \":props.old.subject.mollie_customer_id\" to \":subject.mollie_customer_id\"",
        ],
        "restore" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has restored Company \":subject.company_name\"",
        "delete" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has deleted Company \":subject.company_name\"",
        "force_delete" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has permanently deleted Company \":subject.company_name\"",
    ],

    "owner" => [
        "store" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has add Owner \":subject.user.fullname\"",
        "updates" => [
            "is_prime_owner" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has set to prime owner \":subject.is_prime_owner\"",
        ],
        "restore" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has restored Owner \":subject.user.fullname\"",
        "delete" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has deleted Owner \":subject.user.fullname\"",
        "force_delete" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has permanently deleted Owner \":subject.user.fullname\"",
    ],

    "employee" => [
        "store" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has add new Employee \":subject.user.fullname\" as :subject.employee_type_description",
        "updates" => [
            "type" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Employee \":subject.user.fullname\" as \":subject.employee_type_description\"",
            "status" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Employee \":subject.user.fullname\" status as \":subject.employment_status_description\""
        ],
        "restore" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has restored Employee \":subject.user.fullname\"",
        "delete" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has deleted Employee \":subject.user.fullname\"",
        "force_delete" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has permanently deleted Employee \":subject.user.fullname\"",
    ],

    "customer" => [
        "store" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has add Customer \"<CUSTOMER_FULLNAME>\", customer phone <CUSTOMER_PHONE>",
        "updates" => [
            "fullname" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Customer \"<DIRECT_VALUE.OLD.SUBJECT.FULLNAME>\" fullname to \"<CUSTOMER_FULLNAME>\"",
            "email" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Customer \"<CUSTOMER_FULLNAME>\" email from \"<DIRECT_VALUE.OLD.SUBJECT.EMAIL>\" to \"<CUSTOMER_EMAIL>\"",
            "phone" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Customer \"<CUSTOMER_FULLNAME>\" phone number from \"<DIRECT_VALUE.OLD.SUBJECT.PHONE>\" to \"<CUSTOMER_PHONE>\"",
            "second_phone" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Customer \"<CUSTOMER_FULLNAME>\" second phone number from \"<DIRECT_VALUE.OLD.SUBJECT.PHONE>\" to \"<CUSTOMER_PHONE>\"",
            "acquired_through" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Customer \"<CUSTOMER_FULLNAME>\" Acquisition from \"<DIRECT_VALUE.OLD.SUBJECT.ACQUIRED_THROUGH_DESCRIPTION\" to \"<CUSTOMER_ACQUIRED_THROUGH_DESCRIPTION>\"",
        ],
        "restore" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has restored Customer \"<CUSTOMER_FULLNAME>\"",
        "delete" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has deleted Customer \"<CUSTOMER_FULLNAME>\"",
        "force_delete" => ":<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has permanently deleted Customer \"<CUSTOMER_FULLNAME>\"",
    ],

    "car" => [
        "store" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has add Fleet \":subject.brand - :subject.car_name\"",
        "updates" => [
            "brand" => '<CAUSER_ROLE> "<CAUSER_FULLNAME>" has updated Fleet ":subject.brand - :subject.car_name" brand from ":props.old.subject.brand" to ":subject.brand"',
            "model" => '<CAUSER_ROLE> "<CAUSER_FULLNAME>" has updated Fleet ":subject.brand - :subject.car_name" model from ":props.old.subject.model" to ":subject.model"',
            "car_name" => '<CAUSER_ROLE> "<CAUSER_FULLNAME>" has updated Fleet ":subject.brand - :subject.car_name" name from ":props.old.subject.car_name" to ":subject.car_name"',
            "car_license" => '<CAUSER_ROLE> "<CAUSER_FULLNAME>" has updated Fleet ":subject.brand - :subject.car_name" license from ":props.old.subject.car_license" to ":subject.car_license"',
            "insured" => '<CAUSER_ROLE> "<CAUSER_FULLNAME>" has updated Fleet ":subject.brand - :subject.car_name" insured from ":props.old.subject.insured" to ":subject.insured"',
            "status" => '<CAUSER_ROLE> "<CAUSER_FULLNAME>" has updated Fleet ":subject.brand - :subject.car_name" status from ":props.old.subject.status_description" to ":subject.status_description"',
            "max_passenger" => '<CAUSER_ROLE> "<CAUSER_FULLNAME>" has updated Fleet ":subject.brand - :subject.car_name" max_passenger from ":props.old.subject.max_passenger" to ":subject.max_passenger"',
            "insurance_tax" => '<CAUSER_ROLE> "<CAUSER_FULLNAME>" has updated Fleet ":subject.brand - :subject.car_name" insurance tax from ":props.old.subject.insurance_tax" to ":subject.insurance_tax"',
            "apk" => '<CAUSER_ROLE> "<CAUSER_FULLNAME>" has updated Fleet ":subject.brand - :subject.car_name" apk from ":props.old.subject.apk" to ":subject.apk"',
        ],
        "restore" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has restore a Fleet \":subject.brand - :subject.car_name\"",
        "delete" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has remove a Fleet \":subject.brand - :subject.car_name\"",
        "force_delete" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has permanently remove a Fleet \":subject.brand - :subject.car_name\"",
    ],

    "car_register_time" => [
        "store" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has registered a Fleet  \":subject.car.brand - :subject.car.car_name\" at \":subject.created_at\"",
        "updates" => [
            "should_out_at" => '<CAUSER_ROLE> "<CAUSER_FULLNAME>" has updated Fleet Register Time ":subject.car.brand - :subject.car.car_name" time should out to ":subject.should_out_at"',

            "should_return_at" => '<CAUSER_ROLE> "<CAUSER_FULLNAME>" has updated Fleet Register Time ":subject.car.brand - :subject.car.car_name" time should return to ":subject.should_return_at"',

            "marked_out_at" => '<CAUSER_ROLE> "<CAUSER_FULLNAME>" has updated Fleet Register Time ":subject.car.brand - :subject.car.car_name" time should return to ":subject.marked_out_at"',

            "marked_return_at" => '<CAUSER_ROLE> "<CAUSER_FULLNAME>" has returned Fleet ":subject.car.brand - :subject.car.car_name" at ":subject.marked_return_at"',
        ],
        "restore" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has restore a Fleet Register Time  \":subject.car.brand - :subject.car.car_name\"",
        "delete" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has delete a Fleet Register Time  \":subject.car.brand - :subject.car.car_name\"",
        "force_delete" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has permanently delete a Fleet Register Time  \":subject.car.brand - :subject.car.car_name\"",
    ],

    "work" => [
        "store" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has add Work \":subject.description\"",
        "updates" => [
            "status" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Work \":subject.description\" status as \":subject.status_description\"",
            "quantity" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Work \":subject.description\" quantity to \":subject.quantity\"",
            "quantity_unit" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Work \":subject.description\" quantity_unit to \":subject.quantity_unit\"",
            "description" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Work description from \":props.old.subject.description\" to \":subject.description\"",
            "unit_price" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Work \":props.old.subject.description\" unit price to \":subject.description\"",
            "total_price" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Work \":props.old.subject.description\" total price to \":subject.description\"",
        ],
        "restore" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has restored Work \":subject.description\"",
        "delete" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has deleted Work \":subject.description\"",
        "force_delete" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has permanently deleted Work \":subject.description\"",
    ],

    "workday" => [
        "store" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has added Workday \":subject.date\" status \":subject.status_description\"",
        "updates" => [
            "date" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Workday \":subject.description\" date to \":subject.date\"",
            "status" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Workday \":subject.status_description\" status from
            \":props.old.subject.status_description\" to \":subject.status_description\"",
        ],
        "restore" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has restored Workday \":subject.date\"",
        "delete" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has deleted Workday \":subject.date\"",
        "force" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has permanently deleted Workday \":subject.date\"",
    ],

    "invoice" => [
        "store" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has added Invoice \":subject.invoice_number\" type \":subject.invoiceable_type\"",
        "updates" => [
            "customer_address" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Invoice \":subject.invoice_number\" customer address to \":subject.customer_address\"",

            "invoice_number" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Invoice number from \":props.old.subject.invoice_number\" to \":subject.invoice_number\"",

            "invoiceable_id" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Invoice \":subject.invoice_number\" invoiceable id to \":subject.invoiceable_id\"",

            "invoiceable_type" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Invoice \":subject.invoice_number\" invoiceable type to \":subject.invoiceable_type\"",

            "date" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Invoice \":subject.invoice_number\" date to \":subject.date\"",

            "expiry_date" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Invoice \":subject.invoice_number\" expiry date to \":subject.expiry_date\"",

            "amount" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Invoice \":subject.invoice_number\" amount to \":subject.amount\"",

            "vat_percentage" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Invoice \":subject.invoice_number\" vat percentage to \":subject.vat_percentage\"",

            "discount_amount" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Invoice \":subject.invoice_number\" discount amount to \":subject.discount_amount\"",

            "total_amount" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Invoice \":subject.invoice_number\" total amount to \":subject.total_amount\"",

            "total_paid" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Invoice \":subject.invoice_number\" total paid to \":subject.total_paid\"",

            "total_in_terms" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Invoice \":subject.invoice_number\" total in terms to \":subject.total_in_terms\"",

            "status" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Invoice \":subject.invoice_number\" status to \":subject.status_description\"",

            "payment_method" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Invoice \":subject.invoice_number\" payment method to \":subject.payment_method_description\"",

            "invoice_note" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Invoice \":subject.invoice_number\" invoice note to \":subject.invoice_note\"",

            "sent_at" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has sent Invoice \":subject.invoice_number\"",

            "paid_at" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has paid Invoice \":subject.invoice_number\"",

            "payment_overdue_at" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Invoice \":subject.payment_overdue_at\" payment overdue to \":subject.payment_overdue_at\"",

            "first_remider_sent_at" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Invoice \":subject.invoice_number\" first remider sent at to \":subject.first_remider_sent_at\"",

            "first_reminder_overdue_at" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Invoice \":subject.invoice_number\" first reminder overdue at to \":subject.first_reminder_overdue_at\"",

            "second_reminder_sent_at" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Invoice \":subject.invoice_number\" second reminder sent at to \":subject.second_reminder_sent_at\"",

            "second_reminder_overdue_at" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Invoice \":subject.invoice_number\" second reminder overdue at to \":subject.second_reminder_overdue_at\"",

            "third_reminder_sent_at" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Invoice \":subject.invoice_number\" third reminder sent at to \":subject.third_reminder_sent_at\"",

            "third_reminder_overdue_at" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Invoice \":subject.invoice_number\" third reminder overdue at to \":subject.third_reminder_overdue_at\"",

            "debt_collector_sent_at" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Invoice \":subject.invoice_number\" debt collector sent at to \":subject.debt_collector_sent_at\"",

            "debt_collector_overdue_at" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Invoice \":subject.invoice_number\" debt collector overdue at to \":subject.debt_collector_overdue_at\"",

            "paid_via_debt_collector_at" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Invoice \":subject.invoice_number\" paid via debt collector at to \":subject.paid_via_debt_collector_at\"",

        ],
        "restore" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has restored Invoice \":subject.invoice_number\" type \":subject.invoiceable_type\"",
        "delete" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has deleted Invoice \":subject.invoice_number\" type \":subject.invoiceable_type\"",
        "force_delete" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has permanently deleted Invoice \":subject.invoice_number\" type \":subject.invoiceable_type\"",
    ],

    "quotation" => [
        "store" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has created Quotation \":subject.number\"",
        "updates" => [
            "quotation_date" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Quotation \":subject.number\" date to \":subject.quotation_date\"",

            "number" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Quotation number from \":props.old.subject.number\" to \":subject.number\"",

            "contact_person" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Quotation \":subject.number\" contact person to \":subject.contact_person\"",

            "address" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Quotation \":subject.number\" address to \":subject.address\"",

            "zipcode" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Quotation \":subject.number\" zipcode to \":subject.zipcode\"",

            "phone_number" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Quotation \":subject.number\" phone number to \":subject.phone_number\"",

            "quotation_description" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Quotation \":subject.number\" description to \":subject.quotation_description\"",

            "quotation_note" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Quotation \":subject.number\" note to \":subject.quotation_note\"",

            "amount" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Quotation \":subject.number\" amount to \":subject.quotation_note\"",

            "vat_percentage" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Quotation \":subject.number\" vat percentage to \":subject.vat_percentage\"",

            "discount_amount" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Quotation \":subject.number\" discount amount to \":subject.discount_amount\"",

            "total_amount" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Quotation \":subject.number\" total amount to \":subject.total_amount\"",

            "expiry_date" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Quotation \":subject.number\" expiry date to \":subject.expiry_date\"",

            "status" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Quotation \":subject.number\" status to \":subject.status_description\"",

            "payment_method" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Quotation \":subject.number\" payment method to \":subject.payment_method_description\"",

            "honor_note" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Quotation \":subject.number\" honor note to \":subject.honor_note\"",

            "canceller" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Quotation \":subject.number\" canceler to \":subject.canceller_description\"",

            "cancellation_reason" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has cancelled Quotation \":subject.number\" reason \":subject.cancellation_reason\"",
        ],
        "restore" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has restored Quotation \":subject.number\"",
        "delete" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has deleted Quotation \":subject.number\"",
        "force_delete" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has permanently deleted Quotation \":subject.number\"",
    ],

    "quotation_attachment" => [
        "store" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has created Quotation Attachment \":subject.name\"",
        "updates" => [
            "name" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Quotation Attachment name from \":props.old.subject.name\" to \":subject.name\"",

            "description" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Quotation Attachment \":subject.name\" to
            \":subject.description\"",

            "attachment_path" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Quotation Attachment docoument \":subject.name\"",
        ],
        "restore" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has restored Quotation Attachment \":subject.name\"",
        "delete" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has deleted Quotation Attachment \":subject.name\"",
        "force_delete" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has permanently deleted Quotation Attachment \":subject.name\"",
    ],

    "worklist" => [
        "store" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has add Worklist \":subject.worklist_name\"",
        "updates" => [
            "status" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Worklist \":subject.worklist_number\" status to \":subject.
                status_description\"",
            "worklist_name" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Worklist name from \":props.old.subject.worklist_name\" to \":subject.worklist_name\"",
            "sorting_route_status" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Worklist \":subject.worklist_name\" sorting route status to \":subject.sorting_route_status_description\"",
            "always_sorting_route_status" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Worklist \":subject.worklist_name\" always sorting route status to \":subject.always_sorting_route_status_description\"",
        ],
        "restore" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has restored Worklist \":subject.worklist_name\"",
        "delete" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has deleted Worklist \":subject.worklist_name\"",
        "force_delete" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has permanently deleted Worklist \":subject.worklist_name\"",
    ],

    "worklist_employee" => [
        "store" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has assigned Worklist \":subject.worklist.worklist_name\" to \":subject.user.fullname\"",
        "update" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Worklist \":subject.worklist.worklist_name\"",
        "restore" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has reassigned Worklist \":subject.worklist.worklist_name\" to \":subject.user.fullname\"",
        "delete" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has unassigned Worklist \":subject.worklist.worklist_name\" to \":subject.user.fullname\"",
        "force_delete" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has permanently unassigned Worklist \":subject.worklist.worklist_name\" to \":subject.user.fullname\"",
    ],

    "worklist_car" => [
        "store" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has assigned Car \":subject.car.car_name\" to \":subject.user.fullname\"",
        "update" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has updated Car \":subject.car.car_name\" \":subject.name\"",
        "restore" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has reassigned Car \":subject.car.car_name\" to \":subject.user.fullname\"",
        "delete" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has unassigned Car \":subject.car.car_name\" \":subject.user.fullname\"",
        "force_delete" => "<CAUSER_ROLE> \"<CAUSER_FULLNAME>\" has permanently unassigned Car \":subject.car.car_name\" \":subject.user.fullname\"",
    ],
];
