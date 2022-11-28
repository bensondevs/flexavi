<?php

return [
    'customer' => [
        'created' => [
            'title' => 'Add Customer',
            'message' => ':actor.fullname added a new customer “:extras.salutation_description :object.fullname”',
            'body' => null,
        ],
        'updated' => [
            'title' => 'Update Customer',
            'message' => ':actor.fullname edit customer information “:extras.salutation_description :object.fullname”',
            'body' => null,
        ],
        'deleted' => [
            'title' => 'Delete Customer',
            'message' => ':actor.fullname deleted customer “:extras.salutation_description :object.fullname”',
            'body' => null,
        ],
        'restored' => [
            'title' => 'Restore Customer',
            'message' => ':actor.fullname restored customer “:extras.salutation_description :object.fullname”',
            'body' => null,
        ],
        'permanently_deleted' => [
            'title' => 'Delete Customer Permanently',
            'message' => ':actor.fullname deleted permanently customer “:extras.salutation_description :object.user.fullname” permanently',
            'body' => null,
        ],
    ],
    'employee' => [
        'created' => [
            'title' => 'Add Employee',
            'message' => ':actor.fullname added a new employee “:object.user.fullname”',
            'body' => null,
        ],
        'updated' => [
            'title' => 'Update Employee',
            'message' => ':actor.fullname edit employee information “:object.user.fullname”',
            'body' => null,
        ],
        'deleted' => [
            'title' => 'Delete Employee',
            'message' => ':actor.fullname deleted employee “:object.user.fullname”',
            'body' => null,
        ],
        'restored' => [
            'title' => 'Restore Employee',
            'message' => ':actor.fullname restored employee “:object.user.fullname”',
            'body' => null,
        ],
        'permanently_deleted' => [
            'title' => 'Delete Employee Permanently',
            'message' => ':actor.fullname deleted permanently employee “:object.user.fullname” permanently',
            'body' => null,
        ],
    ],
    'owner' => [
        'created' => [
            'title' => 'Add Owner',
            'message' => ':actor.fullname added a new owner “:object.user.fullname”',
            'body' => null,
        ],
        'updated' => [
            'title' => 'Update Owner',
            'message' => ':actor.fullname edit owner information “:object.user.fullname”',
            'body' => null,
        ],
        'deleted' => [
            'title' => 'Delete Owner',
            'message' => ':actor.fullname deleted owner “:object.user.fullname”',
            'body' => null,
        ],
        'restored' => [
            'title' => 'Restore Owner',
            'message' => ':actor.fullname restored owner “:object.user.fullname”',
            'body' => null,
        ],
        'permanently_deleted' => [
            'title' => 'Delete Owner Permanently',
            'message' => ':actor.fullname deleted permanently owner “:object.user.fullname” permanently',
            'body' => null,
        ],
    ],
    'work_service' => [
        'created' => [
            'title' => 'Add Work Service',
            'message' => ':actor.fullname added a new work service “:object.name”',
            'body' => null,
        ],
        'updated' => [
            'title' => 'Update Work Service',
            'message' => ':actor.fullname edit work service information “:object.name”',
            'body' => null,
        ],
        'deleted' => [
            'title' => 'Delete Work Service',
            'message' => ':actor.fullname deleted work service “:object.name”',
            'body' => null,
        ],
        'restored' => [
            'title' => 'Restore Work Service',
            'message' => ':actor.fullname restored work service “:object.name”',
            'body' => null,
        ],
        'permanently_deleted' => [
            'title' => 'Delete Work Service Permanently',
            'message' => ':actor.fullname deleted permanently work service “:object.name” permanently',
            'body' => null,
        ],
    ],
    'company' => [
        'updated' => [
            'title' => 'Update Company',
            'message' => ':actor.fullname edit company information “:object.company_name”',
            'body' => null,
        ],
    ]
];
