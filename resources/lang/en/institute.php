<?php

declare(strict_types=1);

use App\Enums\InstituteTool\Status;

return [
    'tool' => [
        'singular' => 'Tool',
        'plural'   => 'Tools',

        'attributes' => [
            'alternative_tool_id'          => 'Alternative tool',
            'categories'                   => 'Belongs to :institute categories',
            'description_1'                => 'How it works',
            'description_1_image_filename' => 'Image',
            'description_2'                => 'What do you need',
            'description_2_image_filename' => 'Image',
            'extra_information_title'      => 'Extra information - Title',
            'extra_information'            => 'Extra information',
            'support_title_1'              => 'Support 1 - Title',
            'support_email_1'              => 'Support 1 - E-mail',
            'support_title_2'              => 'Support 2 - Title',
            'support_email_2'              => 'Support 2 - E-Mail',
            'manual_title_1'               => 'Manual 1 - Title',
            'manual_url_1'                 => 'Manual 1 - URL',
            'manual_title_2'               => 'Manual 2 - Title',
            'manual_url_2'                 => 'Manual 2 - URL',
            'video_title_1'                => 'Video 1 - Title',
            'video_url_1'                  => 'Video 1 - URL',
            'video_title_2'                => 'Video 2 - Title',
            'video_url_2'                  => 'Video 2 - URL',
            'status'                       => 'Status',
            'why_unfit'                    => 'Why is this tool unfit?',
        ],

        'statuses' => [
            Status::RECOMMENDED     => 'Recommended',
            Status::SUPPORTED       => 'Supported',
            Status::FREE_TO_USE     => 'Free to use',
            Status::NOT_RECOMMENDED => 'Not recommended',
            Status::PROHIBITED      => 'Prohibited',
            Status::UNRATED         => 'Unrated',
            Status::UNPUBLISHED     => 'Unpublished',
        ],

        'no-alternative-tools' => 'There are currently no alternative tools available to select.',
    ],
];
