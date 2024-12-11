<?php

declare(strict_types=1);

use App\Enums\Tool\Tabs;

return [
    'singular' => 'Custom field',
    'plural'   => 'Custom fields',

    'attributes' => [
        'title'    => 'Title',
        'title_en' => 'Title (EN)',
        'title_nl' => 'Title (NL)',
        'sortkey'  => 'Sorteersleutel (numeriek)',
        'tab_type' => 'Tab Type',
    ],

    'tab_types' => [
        Tabs::PRODUCT              => 'Product',
        Tabs::TECHNICAL            => 'Technical',
        Tabs::PRIVACY_AND_SECURITY => 'Privacy & Security',
        Tabs::SUPPORT              => 'Support',
        Tabs::EDUCATION            => 'Education',
    ],
];
