<?php

declare(strict_types=1);

use App\Enums\Auth\Role;

return [
    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain'   => env('MAILGUN_DOMAIN'),
        'secret'   => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'surfconext' => [
        'client_id'     => env('SURFCONEXT_CLIENT_ID'),
        'client_secret' => env('SURFCONEXT_CLIENT_SECRET'),
        'redirect'      => env('SURFCONEXT_REDIRECT_URI'),
        'test'          => env('SURFCONEXT_TEST'),

        'roles' => [
            Role::TEACHER             => env('SURFCONEXT_ROLE_TEACHER'),
            Role::INFORMATION_MANAGER => env('SURFCONEXT_ROLE_INFORMATION_MANAGER'),
            Role::CONTENT_MANAGER     => env('SURFCONEXT_ROLE_CONTENT_MANAGER'),
        ],
    ],
];
