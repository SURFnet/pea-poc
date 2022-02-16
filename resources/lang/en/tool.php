<?php

declare(strict_types=1);

use App\Enums\Tool\AuthenticationMethod;
use App\Enums\Tool\Status;
use App\Enums\Tool\StoredData;
use App\Enums\Tool\SupportedStandard;

return [
    'singular' => 'Tool',
    'plural'   => 'Tools',

    'attributes' => [
        'name'              => 'Name',
        'description_short' => 'Short description',
        'image_filename'    => 'Image',
        'description'       => 'Description',
        'category'          => 'Category',

        'supported_standards'    => 'Supported standards',
        'additional_standards'   => 'Additional standards',
        'authentication_methods' => 'Authentication methods',
        'stored_data'            => 'Stored data',
        'other_stored_data'      => 'Other stored data',

        'european_data_storage'           => 'Data is stored in Europe',
        'surf_standards_framework_agreed' => 'Supplier agrees to the SURF standards framework',
        'has_processing_agreement'        => 'Supplier has a processing agreement',

        'description_long_1'           => 'Description tool',
        'description_1_image_filename' => 'Description tool image',
        'description_long_2'           => 'Examples of usage',
        'description_2_image_filename' => 'Examples of usage image',
        'info_url'                     => 'More information URL',
    ],

    'rating' => 'Rating',
    'status' => 'Status',

    'stored_data' => [
        StoredData::PERSONAL_DATA => 'Personal data',
        StoredData::USAGE_LOGGING => 'Usage logging',
        StoredData::OTHER         => 'Other',
    ],

    'supported_standards' => [
        SupportedStandard::LIS     => 'LIS',
        SupportedStandard::QTI     => 'QTI',
        SupportedStandard::OOAPI   => 'OOAPI',
        SupportedStandard::EDUAPI  => 'EDUAPI',
        SupportedStandard::LTI     => 'LTI',
        SupportedStandard::SAML    => 'SAML',
        SupportedStandard::XZAPI   => 'xzAPI',
        SupportedStandard::CALIPER => 'Caliper',
    ],

    'authentication_methods' => [
        AuthenticationMethod::SSO        => 'SSO',
        AuthenticationMethod::SURFCONEXT => 'SURFconext',
        AuthenticationMethod::OIDC       => 'OIDC',
    ],

    'statuses' => [
        Status::CONCEPT   => 'Concept',
        Status::PUBLISHED => 'Published',
    ],
];
