<?php

declare(strict_types=1);

// phpcs:disable Generic.Files.LineLength.TooLong

use App\Enums\InstituteTool\DataClassification;
use App\Enums\InstituteTool\Sort;
use App\Enums\InstituteTool\Status;

return [
    'attributes' => [
        'short_name'   => 'Code',
        'full_name'    => 'Name',
        'full_name_en' => 'Name (EN)',
        'full_name_nl' => 'Name (NL)',
        'domain'       => 'Domain',
    ],

    'tool' => [
        'singular' => 'Tool',
        'plural'   => 'Tools',

        'attributes' => [
            'alternative_tools' => 'Alternative tools',

            'status'        => 'Status',
            'category'      => 'Category',
            'categories'    => 'Categories',
            'conditions'    => 'Conditions',
            'conditions_en' => 'Conditions (EN)',
            'conditions_nl' => 'Conditions (NL)',

            'links_with_other_tools'    => 'Links with other tools',
            'links_with_other_tools_en' => 'Links with other tools (EN)',
            'links_with_other_tools_nl' => 'Links with other tools (NL)',
            'sla_url'                   => 'SLA - URL',

            'privacy_contact'         => 'Privacy contact',
            'privacy_evaluation_url'  => 'Privacy evaluation - URL',
            'security_evaluation_url' => 'Security evaluation - URL',
            'data_classification'     => 'Data classification',

            'how_to_login'              => 'How to login',
            'how_to_login_en'           => 'How to login (EN)',
            'how_to_login_nl'           => 'How to login (NL)',
            'availability'              => 'Availability',
            'availability_en'           => 'Availability (EN)',
            'availability_nl'           => 'Availability (NL)',
            'licensing'                 => 'License',
            'licensing_en'              => 'License (EN)',
            'licensing_nl'              => 'License (NL)',
            'request_access'            => 'Request access',
            'request_access_en'         => 'Request access (EN)',
            'request_access_nl'         => 'Request access (NL)',
            'instructions'              => 'Instructions',
            'instructions_en'           => 'Instructions (EN)',
            'instructions_nl'           => 'Instructions (NL)',
            'instructions_manual_1_url' => 'Instructions 1 - URL',
            'instructions_manual_2_url' => 'Instructions 2 - URL',
            'instructions_manual_3_url' => 'Instructions 3 - URL',

            'faq'                        => 'FAQ',
            'faq_en'                     => 'FAQ (EN)',
            'faq_nl'                     => 'FAQ (NL)',
            'examples_of_usage'          => 'Examples of usage',
            'examples_of_usage_en'       => 'Examples of usage (EN)',
            'examples_of_usage_nl'       => 'Examples of usage (NL)',
            'additional_info_heading_en' => 'Additional info heading (EN)',
            'additional_info_heading_nl' => 'Additional info heading (NL)',
            'additional_info_text_en'    => 'Additional info text (EN)',
            'additional_info_text_nl'    => 'Additional info text (NL)',

            'why_unfit'    => 'Why is this tool unfit?',
            'why_unfit_en' => 'Why is this tool unfit? (EN)',
            'why_unfit_nl' => 'Why is this tool unfit? (NL)',

            'prohibited_tools' => 'The following alternative tools were previously selected but are now marked as prohibited. They will be removed as alternatives when you save this tool:',
        ],
        'tooltip' => [
            'marked_as_fit' => 'Tooltip: marked_as_fit',

            'status'        => 'Tooltip: status',
            'categories'    => 'Tooltip: categories',
            'conditions_en' => 'Tooltip: conditions_en',
            'conditions_nl' => 'Tooltip: conditions_nl',
            'addons'        => 'Tooltip: addons',
            'updated_at'    => 'Tooltip: updated_at',

            'links_with_other_tools_en' => 'Tooltip: links_with_other_tools_en',
            'links_with_other_tools_nl' => 'Tooltip: links_with_other_tools_nl',
            'sla_url'                   => 'Tooltip: sla_url',
            'system_requirements'       => 'Tooltip: system_requirements',
            'software_types'            => 'Tooltip: software_types',
            'devices'                   => 'Tooltip: devices',
            'operating_systems'         => 'Tooltip: operating_systems',
            'accessibility_facilities'  => 'Tooltip: accessibility_facilities',

            'privacy_contact'           => 'Tooltip: privacy_contact',
            'privacy_evaluation_url'    => 'Tooltip: privacy_evaluation_url',
            'security_evaluation_url'   => 'Tooltip: security_evaluation_url',
            'data_classification'       => 'Tooltip: data_classification',
            'privacy_analysis'          => 'Tooltip: privacy_analysis',
            'personal_data'             => 'Tooltip: personal_data',
            'supplier_country'          => 'Tooltip: supplier_country',
            'certifications'            => 'Tooltip: certifications',
            'data_processing_locations' => 'Tooltip: data_processing_locations',

            'how_to_login_en'           => 'Tooltip: how_to_login_en',
            'how_to_login_nl'           => 'Tooltip: how_to_login_nl',
            'availability_en'           => 'Tooltip: availability_en',
            'availability_nl'           => 'Tooltip: availability_nl',
            'licensing_en'              => 'Tooltip: licensing_en',
            'licensing_nl'              => 'Tooltip: licensing_nl',
            'request_access_en'         => 'Tooltip: request_access_en',
            'request_access_nl'         => 'Tooltip: request_access_nl',
            'instructions_en'           => 'Tooltip: instructions_en',
            'instructions_nl'           => 'Tooltip: instructions_nl',
            'instructions_manual_1_url' => 'Tooltip: instructions_manual_1_url',
            'instructions_manual_2_url' => 'Tooltip: instructions_manual_2_url',
            'instructions_manual_3_url' => 'Tooltip: instructions_manual_3_url',
            'support_for_teachers'      => 'Tooltip: support_for_teachers',

            'faq_en'                     => 'Tooltip: faq_en',
            'faq_nl'                     => 'Tooltip: faq_nl',
            'examples_of_usage_en'       => 'Tooltip: examples_of_usage_en',
            'examples_of_usage_nl'       => 'Tooltip: examples_of_usage_nl',
            'additional_info_heading_en' => 'Tooltip: additional_info_heading_en',
            'additional_info_heading_nl' => 'Tooltip: additional_info_heading_nl',
            'additional_info_text_en'    => 'Tooltip: additional_info_text_en',
            'additional_info_text_nl'    => 'Tooltip: additional_info_text_nl',

            'why_unfit'    => 'Tooltip: why_unfit',
            'why_unfit_en' => 'Tooltip: why_unfit_en',
            'why_unfit_nl' => 'Tooltip: why_unfit_nl',

            'alternative_tools' => 'Tooltip: alternative_tools',

            'privacy_policy_url'            => 'Tooltip: privacy_policy_url',
            'model_processor_agreement_url' => 'Tooltip: model_processor_agreement_url',
            'dtia_by_external_url'          => 'Tooltip: dtia_by_external_url',
            'dpia_by_external_url'          => 'Tooltip: dpia_by_external_url',
            'jurisdiction'                  => 'Tooltip: jurisdiction',
            'standards'                     => 'Tooltip: standards',
        ],

        'data_classifications' => [
            DataClassification::PUBLIC       => 'Public',
            DataClassification::INTERNAL     => 'Internal',
            DataClassification::CONFIDENTIAL => 'Confidential',
            DataClassification::SECRET       => 'Secret',
        ],

        'statuses' => [
            Status::ALLOWED                  => 'Allowed',
            Status::ALLOWED_UNDER_CONDITIONS => 'Allowed under conditions',
            Status::DISALLOWED               => 'Disallowed',
            Status::UNRATED                  => 'Unrated',
            Status::UNPUBLISHED              => 'Unpublished',
        ],

        'no-alternative-tools' => 'There are currently no alternative tools available to select.',

        'sort' => [
            Sort::UPDATED_AT_ASC  => 'Last updated oldest - latest',
            Sort::UPDATED_AT_DESC => 'Last updated latest - oldest',
        ],
    ],

    'homepage-information' => [
        'homepage' => 'Homepage',

        'attributes' => [
            'title_en' => 'Title (EN)',
            'body_en'  => 'Body (EN)',
            'title_nl' => 'Title (NL)',
            'body_nl'  => 'Body (NL)',
        ],
    ],

    'notifications' => [
        'create' => 'Followers',
        'title'  => 'Update followers',

        'attributes' => [
            'tool_name' => ':tool (:count Follower)|:tool (:count Followers)',
            'tool'      => 'Tool',
            'subject'   => 'Subject',
            'message'   => 'Message',
        ],
    ],
];
