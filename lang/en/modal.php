<?php

declare(strict_types=1);

// phpcs:disable Generic.Files.LineLength.TooLong

use App\Enums\InstituteTool\Status;

return [
    'shared' => [
        'experience_form' => [
            'select_stars'  => 'Select amount of stars',
            'experience_of' => 'Experience of',
            'title'         => 'Title',
            'message'       => 'What do you use the tool for?',

            'info' => [
                'max_length'  => 'The maximum character limit for this field is :max',
                'placeholder' => 'Describe your application and experience of this tool. For instance: purpose, target, use, experience, practical tips for other teachers, etc.',
            ],
        ],
    ],

    'share_experience' => [
        'title' => 'Share your experience',

        'actions' => [
            'share'  => 'Share experience',
            'cancel' => 'Cancel',
        ],
    ],

    'edit_experience' => [
        'title' => 'Edit your experience',

        'actions' => [
            'update' => 'Save experience',
            'cancel' => 'Cancel',
        ],
    ],

    'status_legend' => [
        'title'       => 'Status legend',
        'description' => 'Statuses are defined as badges and are mostly used within Our Tools. It indicates the level
            at which information managers encourage you to use the tool for any purpose within our institute.',

        'statuses' => [
            Status::ALLOWED => 'The tool is supported by our institution, which means that it is safe to use
                and there is a license within the institution. Difference with a recommended tool is that this status
                means it is optional to use.',
            Status::ALLOWED_UNDER_CONDITIONS => 'Our institution does not recommend this tool, which can be due to
                unclear terms and regulations, difficult to use, or because of a preferred alternative.',
            Status::DISALLOWED => 'You are not allowed to use this tool due to f.e. safety issues, privacy
                risks, or other technical concerns.',
            Status::UNRATED => 'This tool has not yet been examined by our institution, and therefore not given
                a status yet.',
        ],
    ],

    'request_for_change' => [
        'title'       => 'Request a change for tool: :tool',
        'description' => 'Your request for change will be e-mailed to us and you will receive a confirmation e-mail.',

        'label' => 'Describe as detailed as possible what you would like to see differently on this page.',

        'actions' => [
            'submit' => 'Send',
            'cancel' => 'Cancel',
        ],
    ],
];
