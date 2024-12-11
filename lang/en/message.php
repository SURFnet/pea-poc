<?php

declare(strict_types=1);

return [
    'data-saved'         => 'The data has been saved.',
    'entity-deleted'     => ':Entity has been deleted.',
    'entity-published'   => ':Entity has been published.',
    'entity-unpublished' => 'Publication of :Entity has been undone.',

    'concept-published' => 'The concept version of :Entity has been published.',
    'concept-discarded' => 'The concept version of :Entity has been discarded.',

    'experience' => [
        'shared'  => 'Your experience has been shared.',
        'updated' => 'Your experience has been updated.',
        'deleted' => 'Your experience has been deleted.',
    ],

    'request_for_change_sent' => 'Your request for change has been sent. You will receive a confirmation by e-mail.',

    'update-sent' => 'Your message is sent.',

    'file-too-large'   => 'The file is too large.',
    'file-is-uploaded' => 'File ":file" has been uploaded.',

    'following-tool'         => 'You will now receive updates for this application',
    'stopped-following-tool' => 'You will no longer receive updates for this application',

    'no-data' => 'There is no data.',

    'error' => [
        'api' => [
            'unauthenticated' => 'Not logged in.',
            'not-found'       => 'Not found.',
            'csrf-failed'     => 'CSRF token validation failed.',
            'unauthorized'    => 'You are not allowed to perform this action.',
        ],
        'general'      => 'An unexpected error has occurred. We apologize for the inconvenience. Try again later.',
        'maintenance'  => 'We are busy with maintenance for a while. Try again later.',
        'not-found'    => 'The page could not be found.',
        'validation'   => 'The validation failed. Please correct the errors and try again. ',
        'forbidden'    => 'You are not authorized to perform this action.',
        'expired'      => 'The page has expired. Please go back and try again.',
        'inactivity'   => 'You have been automatically logged out due to prolonged inactivity.',
        'unauthorized' => 'You are not authorized to access this page.',

        'login' => [
            'unknown_institute'   => 'Unknown institute',
            'invalid_affiliation' => 'Invalid affiliation',
            'invalid_roles'       => 'Invalid roles',
        ],
    ],
];
