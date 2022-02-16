<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Testing
    |--------------------------------------------------------------------------
    |
    | The values described here are used to locate Inertia components on the
    | filesystem. For instance, when using `assertInertia`, the assertion
    | attempts to locate the component as a file relative to any of the
    | paths AND with any of the extensions specified here.
    |
    */

    'testing' => [
        'ensure_pages_exist' => false,

        'page_paths' => [
            resource_path('assets/admin/src/pages'),
        ],

        'page_extensions' => [
            'js',
            'svelte',
            'ts',
            'vue',
        ],
    ],
];
