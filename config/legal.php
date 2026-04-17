<?php

return [

    // Public-facing data — shown in Privacy and Terms pages.
    'responsible' => [
        'brand' => 'Citora',
        'email' => 'webcitora@gmail.com',
        'city' => 'Barrancabermeja',
        'state' => 'Santander',
        'country' => 'Colombia',
        'website' => 'https://citora.com.co',
    ],

    // Private data — kept here so it can be disclosed upon formal request
    // from a data subject or a regulator (SIC). Not shown publicly.
    'responsible_private' => [
        'name' => env('LEGAL_RESPONSIBLE_NAME'),
        'id_type' => env('LEGAL_RESPONSIBLE_ID_TYPE'),
        'id_number' => env('LEGAL_RESPONSIBLE_ID_NUMBER'),
        'address' => env('LEGAL_RESPONSIBLE_ADDRESS'),
        'economic_activity' => env('LEGAL_RESPONSIBLE_ACTIVITY'),
    ],

    'hosting' => [
        'provider' => 'LatinoamericaHosting',
        'country' => 'Colombia',
    ],

    'policy' => [
        'retention_years' => 5,
        'effective_date' => '2026-04-17',
        'last_updated' => '2026-04-17',
    ],

    'refund' => [
        'policy' => 'non_refundable',
    ],

];
