<?php

declare(strict_types=1);

use App\Enums\InstituteTool\Status;

return [
    'shared' => [
        'experience_form' => [
            'select_stars'  => 'Select amount of stars',
            'experience_of' => 'Experience of',
            'title'         => 'Title',
            'message'       => 'Would you recommend it to others and why?',

            'info' => [
                'message' => 'The maximum character limit for this field is :max',
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
            Status::RECOMMENDED => 'The tool is recommended by our institution, which means that the tool
                is preferred above other tools with the same functionalities, is safe to use, and you can contact your
                institution for support. Therefore you should use this particular tool.',
            Status::SUPPORTED => 'The tool is supported by our institution, which means that it is safe to use
                and there is a license within the institution. Difference with a recommended tool is that this status
                means it is optional to use.',
            Status::FREE_TO_USE => 'Our institution allows you to use this tool, but does not provide support in case
                of any concerns, issues or questions concerning the usage.',
            Status::NOT_RECOMMENDED => 'Our institution does not recommend this tool, which can be due to unclear
                terms and regulations, difficult to use, or because of a preferred alternative.',
            Status::PROHIBITED => 'You are not allowed to use this tool due to f.e. safety issues, privacy
                risks, or other technical concerns.',
            Status::UNRATED => 'This tool has not yet been examined by our institution, and therefore not given
                a status yet.',
        ],
    ],
];
