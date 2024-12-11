<?php

declare(strict_types=1);

return [
    'default-locale'       => 'en',
    'import-locale'        => 'en',
    'locales'              => [],
    'cache-duration'       => 30,
    'register-breadcrumbs' => false,
    'editable-languages'   => true,

    // The key is the namespace and the value is the actual folder
    'language-folders' => [
        '*'             => base_path('lang'),
        'Way2Translate' => base_path('modules/Way2Translate/resources/lang'),
    ],

    'js-translations' => [
        'auto-generate'   => true,
        'include-lang-js' => true,
        'minify-js'       => true,
        'path-relative'   => 'dist/lang.js',
        'path-absolute'   => public_path('dist/lang.js'),
    ],

    'theme' => [
        'name'           => 'tailwind',
        'master-extends' => 'layouts.admin.way2translate',
        'master-section' => 'content',

        'classes' => [
            'form'   => '',
            'label'  => 'block | text-sm font-medium text-gray-700',
            'static' => 'block | text-sm font-medium text-gray-700',

            'select' => 'w-full max-w-lg block | focus:ring-blue-500 focus:border-blue-500
                | shadow-sm rounded sm:max-w-xs sm:text-sm border-gray-300',
            'textarea' => 'w-full block | focus:ring-blue-500 focus:border-blue-500
                | shadow-sm rounded sm:text-sm border-gray-300',
            'input' => 'w-full max-w-lg block | focus:ring-blue-500 focus:border-blue-500
                | shadow-sm rounded sm:max-w-xs sm:text-sm border-gray-300',
        ],
    ],
];
