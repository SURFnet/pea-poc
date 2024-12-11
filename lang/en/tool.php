<?php

declare(strict_types=1);

use App\Enums\Tool\Status;

return [
    'singular' => 'Tool',
    'plural'   => 'Tools',

    'attributes' => [
        'logo_filename'        => 'Logo',
        'image_1_filename'     => 'Image 1',
        'image_2_filename'     => 'Image 2',
        'name'                 => 'Name',
        'supplier'             => 'Supplier',
        'supplier_url'         => 'Supplier URL',
        'description_short'    => 'Short description',
        'description_short_en' => 'Short description (EN)',
        'description_short_nl' => 'Short description (NL)',
        'features'             => 'Features',
        'addons'               => 'Add-ons',
        'addons_en'            => 'Add-ons (EN)',
        'addons_nl'            => 'Add-ons (NL)',
        'updated_at'           => 'Last updated',

        'software_types'         => 'Software type',
        'devices'                => 'Devices',
        'system_requirements'    => 'System requirements',
        'system_requirements_en' => 'System requirements (EN)',
        'system_requirements_nl' => 'System requirements (NL)',
        'standards'              => 'Standards',
        'operating_systems'      => 'Operating system',

        'supplier_country'         => 'Location organization',
        'supplier_country_display' => 'Location organization',

        'personal_data'    => 'Personal data',
        'personal_data_en' => 'Personal data (EN)',
        'personal_data_nl' => 'Personal data (NL)',

        'data_processing_locations'           => 'Data processing location',
        'privacy_policy_url'                  => 'Privacy policy - URL',
        'model_processor_agreement_url'       => 'Model Processor Agreement - URL',
        'privacy_analysis'                    => 'Privacy Analysis',
        'supplier_agrees_with_surf_standards' => 'Supplier agrees with SURF Standards',
        'certifications'                      => 'Certifications',
        'dtia_by_external_url'                => 'External DTIA - URL',
        'dpia_by_external_url'                => 'External DPIA - URL',
        'jurisdiction'                        => 'Jurisdiction',

        'instructions_manual_1_url'    => 'Instruction and manual 1 - URL',
        'instructions_manual_1_url_en' => 'Instruction and manual 1 - URL (EN)',
        'instructions_manual_1_url_nl' => 'Instruction and manual 1 - URL (NL)',
        'instructions_manual_2_url'    => 'Instruction and manual 2 - URL',
        'instructions_manual_2_url_en' => 'Instruction and manual 2 - URL (EN)',
        'instructions_manual_2_url_nl' => 'Instruction and manual 2 - URL (NL)',
        'instructions_manual_3_url'    => 'Instruction and manual 3 - URL',
        'instructions_manual_3_url_en' => 'Instruction and manual 3 - URL (EN)',
        'instructions_manual_3_url_nl' => 'Instruction and manual 3 - URL (NL)',
        'support_for_teachers'         => 'Support',
        'support_for_teachers_en'      => 'Support (EN)',
        'support_for_teachers_nl'      => 'Support (NL)',
        'availability_surf'            => 'SURF offer',
        'accessibility_facilities'     => 'Digital accessibility',
        'accessibility_facilities_en'  => 'Digital accessibility (EN)',
        'accessibility_facilities_nl'  => 'Digital accessibility (NL)',

        'description_long'     => 'What is it?',
        'description_long_en'  => 'What is it? (EN)',
        'description_long_nl'  => 'What is it (NL)',
        'use_for_education'    => 'Use for education',
        'use_for_education_en' => 'Use for education (EN)',
        'use_for_education_nl' => 'Use for education (NL)',
        'working_methods'      => 'Working methods',
        'target_groups'        => 'Target group',
        'how_does_it_work'     => 'How does it work',
        'how_does_it_work_en'  => 'How does it work (EN)',
        'how_does_it_work_nl'  => 'How does it work (NL)',
        'complexity'           => 'Complexity',
        'has_concept'          => 'Concept available',

        'is_true'  => 'Yes',
        'is_false' => 'No',
    ],

    'prefills' => [
        'privacy_analysis'  => 'Who are sub-processors and where are they located?<br /><br />What data does the software share with other parties?<br /><br />Is this shared data used for marketing purposes?<br /><br />Does the company indicate what measures they take to protect the data?',
        'use_for_education' => 'How can you use it?<br /><br />Pros and cons<br /><br />Tips',
    ],

    'total_experiences' => 'Total experiences',
    'status'            => 'Status',

    'statuses' => [
        Status::CONCEPT   => 'Concept',
        Status::PUBLISHED => 'Published',
    ],

    'supplier_agrees_with_surf_standards' => [
        'yes' => 'Ja',
        'no'  => 'Nee',
    ],

    'has_concept' => [
        'yes' => 'Yes',
        'no'  => 'No',
    ],
];
