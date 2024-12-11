<?php

declare(strict_types=1);

use App\Enums\Tags\TagTypes;

return [
    'singular' => 'Tag',
    'plural'   => 'Tags',

    'index' => [
        'heading' => 'Tags',
    ],

    'attributes' => [
        'name'           => 'Name',
        'type'           => 'Type',
        'name_en'        => 'Name (EN)',
        'name_nl'        => 'Name (NL)',
        'description_en' => 'Description (EN)',
        'description_nl' => 'Description (NL)',
    ],

    'tag_types' => [
        TagTypes::FEATURES                  => 'Features',
        TagTypes::SOFTWARE_TYPES            => 'Software type',
        TagTypes::DEVICES                   => 'Devices',
        TagTypes::STANDARDS                 => 'Standards',
        TagTypes::OPERATING_SYSTEMS         => 'Operating system',
        TagTypes::DATA_PROCESSING_LOCATIONS => 'Data processing location',
        TagTypes::CERTIFICATIONS            => 'Certifications',
        TagTypes::WORKING_METHODS           => 'Working methods',
        TagTypes::TARGET_GROUPS             => 'Target group',
        TagTypes::COMPLEXITY                => 'Complexity',
        TagTypes::CATEGORIES                => 'Categories',
    ],

    'filter' => [
        'placeholder'    => 'Select a filter',
        'select_label'   => 'Click or enter to select',
        'selected_label' => 'Selected',
    ],
];
