<?php

declare(strict_types=1);

return [
    'general' => [
        'domain'   => env('APP_DOMAIN', 'localhost'),
        'protocol' => env('APP_PROTOCOL', 'https://'),
    ],
    'registration' => [
        'enabled'      => true,
        'default_role' => 'admin',
    ],
    'route' => [
        'redirect_unauthenticated' => 'account.login',
        'redirect_authenticated'   => 'home.index',
    ],
    'environment' => [
        'development' => [
            'dev',
            'local',
            'testing',
        ],
        'staging' => [
            'stage',
            'staging',
        ],
        'testing' => [
            'testing',
        ],
        'acceptance' => [
            'accept',
            'acceptance',
        ],
        'production' => [
            'production',
            'prod',
            'live',
        ],
    ],
    'format' => [
        'date'      => 'd-m-Y',
        'time'      => 'H:i',
        'datetime'  => 'd-m-Y H:i',
        'timestamp' => 'YmdHis',

        'date_moment'     => 'DD-MM-YYYY',
        'time_moment'     => 'hh:mm',
        'datetime_moment' => 'DD-MM-YYYY hh:mm',
    ],
    'regex' => [
        'price'     => '^\d*(\.\d{2}$)?',
        'date'      => '[0-9]{2}-[0-9]{2}-[0-9]{4}',
        'color-hex' => '/^#[a-fA-F0-9]{6}$/',
    ],
    'email' => [
        'address-test'       => 'assist+template@paqt.com',
        'allowed-mail-parts' => [
            '@paqt.com',
        ],
    ],
    'environments' => [
        'show-errors' => [
            'local',
            'stage',
        ],
    ],
    'piwik_key' => env('PIWIK_KEY'),
];
