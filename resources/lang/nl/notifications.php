<?php

return [
    'customer' => [
        'created' => [
            'title' => 'Klant toevoegen',
            'message' => ':actor.fullname heeft een nieuwe klant toegevoegd “:extras.salutation_description :object.fullname”',
            'body' => null,
        ],
        'updated' => [
            'title' => 'Klant bijwerken',
            'message' => ':actor.fullname klantinformatie bewerken “:extras.salutation_description :object.fullname”',
            'body' => null,
        ],
        'deleted' => [
            'title' => 'Klant verwijderen',
            'message' => ':actor.fullname verwijderde klant “:extras.salutation_description :object.fullname”',
            'body' => null,
        ],
        'restored' => [
            'title' => 'Klant herstellen',
            'message' => ':actor.fullname herstelde klant “:extras.salutation_description :object.fullname”',
            'body' => null,
        ],
        'permanently_deleted' => [
            'title' => 'Klant definitief verwijderen',
            'message' => ':actor.fullname permanent verwijderd klant “:extras.salutation_description :object.user.fullname” permanent',
            'body' => null,
        ],
    ],
    'employee' => [
        'created' => [
            'titel' => 'Werknemer toevoegen',
            'message' => ':actor.fullname heeft een nieuwe medewerker toegevoegd “:object.user.fullname”',
            'body' => null,
        ],
        'updated' => [
            'titel' => 'Werknemer bijwerken',
            'message' => ':actor.fullname bewerk werknemersinformatie “:object.user.fullname”',
            'body' => null,
        ],
        'deleted' => [
            'titel' => 'Werknemer verwijderen',
            'message' => ':actor.fullname verwijderde werknemer “:object.user.fullname”',
            'body' => null,
        ],
        'restored' => [
            'titel' => 'Werknemer herstellen',
            'message' => ':actor.fullname herstelde werknemer “:object.user.fullname”',
            'body' => null,
        ],
        'permanently_deleted' => [
            'title' => 'Medewerker definitief verwijderen',
            'message' => ':actor.fullname permanent verwijderd werknemer “:object.user.fullname” permanent',
            'body' => null,
        ],
    ],
    'owner' => [
        'created' => [
            'title' => 'Eigenaar toevoegen',
            'message' => ':actor.fullname heeft een nieuwe eigenaar toegevoegd “:object.user.fullname”',
            'body' => null,
        ],
        'updated' => [
            'title' => 'Eigenaar bijwerken',
            'message' => ':actor.fullname eigenaar informatie bewerken “:object.user.fullname”',
            'body' => null,
        ],
        'deleted' => [
            'title' => 'Eigenaar verwijderen',
            'message' => ':actor.fullname verwijderde eigenaar “:object.user.fullname”',
            'body' => null,
        ],
        'restored' => [
            'title' => 'Eigenaar herstellen',
            'message' => ':actor.fullname herstelde eigenaar “:object.user.fullname”',
            'body' => null,
        ],
        'permanently_deleted' => [
            'title' => 'Eigenaar permanent verwijderen',
            'message' => ':actor.fullname permanent verwijderd eigenaar “:object.user.fullname” permanent',
            'body' => null,
        ],
    ],
    'work_service' => [
        'created' => [
            'title' => 'Werkservice toevoegen',
            'message' => ':actor.fullname heeft een nieuwe werkservice toegevoegd “:object.name”',
            'body' => null,
        ],
        'updated' => [
            'title' => 'Werkservice bijwerken',
            'message' => ':actor.fullname werkservice-informatie bewerken “:object.name”',
            'body' => null,
        ],
        'deleted' => [
            'title' => 'Werkservice verwijderen',
            'message' => ':actor.fullname verwijderde werkservice “:object.name”',
            'body' => null,
        ],
        'restored' => [
            'title' => 'Restore Work Service',
            'message' => ':actor.fullname herstelde werkservice “:object.name”',
            'body' => null,
        ],
        'permanently_deleted' => [
            'title' => 'Werkservice permanent verwijderen',
            'message' => ':actor.fullname verwijderd permanent werk service “:object.name” permanent',
            'body' => null,
        ],
    ],
    'company' => [
        'updated' => [
            'titel' => 'Bedrijf bijwerken',
            'message' => ':actor.fullname bewerk bedrijfsinformatie “:object.company_name”',
            'body' => null,
        ],
    ]
];
