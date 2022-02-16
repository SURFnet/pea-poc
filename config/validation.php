<?php

declare(strict_types=1);

return [
    'regex' => [
        'postal_code'                => '/^[0-9]{4} [A-Z]{2}$/',
        'vat_number'                 => '/^NL[0-9]{9}B[0-9]{2}$/',
        'chamber_of_commerce_number' => '/^[0-9]{8}$/',
    ],
    'phone' => [
        'countries' => ['NL'],
    ],
    'db_string' => [
        'length' => env('DB_STRING_LENGTH', 255),
    ],

    'tool' => [
        'image' => [
            'max' => 5000,
        ],
    ],

    'experience' => [
        'message' => [
            'max' => 800,
        ],
        'rating' => [
            'in' => [1, 2, 3, 4, 5],
        ],
    ],
];
