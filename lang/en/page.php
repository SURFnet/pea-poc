<?php

declare(strict_types=1);

// phpcs:disable Generic.Files.LineLength.TooLong

return [
    'open-menu'  => 'Open menu',
    'select-tab' => 'Select a tab',
    'search'     => 'Search',
    'sort'       => 'Sort',

    'app-name' => 'Project educatieve applicaties',

    'menu' => [
        'all-tools'        => 'All tools',
        'manage-our-tools' => 'Manage our tools',
        'manage-all-tools' => 'Manage all tools',
        'manage-all-tags'  => 'Manage all tags',
        'about'            => 'About',
    ],

    'shared' => [
        'section-header' => [
            'search-tools' => 'Search within tools',
        ],

        'tool' => [
            'experiences'       => 'Experiences',
            'total_experiences' => ':count total',
            'no_experiences'    => 'No experiences have been shared yet.',
            'share_experience'  => 'Share experience',

            'actions' => [
                'request_for_change' => 'Request a change',
            ],

            'card' => [
                'total_experiences' => ':count experience|:count total experiences',
            ],
        ],
    ],

    'login' => [
        'disclaimer' => 'Through this website you can enter the application of Project Educatieve Applicaties.
            This is a project which is in the Proof of Concept phase, and currently undergoing tests and evaluations.
            In case you have general questions, please contact
            <a href="mailto:pytrik.dijkstra@surf.nl">pytrik.dijkstra@surf.nl</a>',
    ],

    'navbar' => [
        'project'      => 'project',
        'project-name' => 'educatieve applicaties',
    ],

    'account' => [
        'login' => [
            'title' => 'Login',
        ],
    ],

    'translation' => [
        'index' => [
            'title' => 'Translations',
        ],
    ],

    'home' => [
        'index' => [
            'title'          => 'Home',
            'heading'        => 'Home',
            'section-header' => [
                'title'      => 'Discover the best tools for learning',
                'subtitle'   => 'Within :institute and beyond',
                'learn-more' => 'Learn more about PEA',
            ],
        ],
    ],

    'about' => [
        'index' => [
            'title'          => 'About',
            'section-header' => [
                'title'    => 'About',
                'subtitle' => 'About SURF and :institute',
            ],
            'sections' => [
                'how-to-use' => [
                    'title' => 'What is this website about?',
                    'text'  => [
                        'Through this website you can find educational applications that you would like to use and that meet your, and your institutions’ requirements. Within ‘our tools’ you will find all applications that are checked by your institution and reviewed by your peers. You can publish your experience working with these particular apps and therefore create useful information for colleagues.',

                        'If the application you are looking for is not available within your institution, please either use the search field or browse through ‘other tools’. These are the applications that are not yet identified within your institution, but are known within the market or sometimes used by other institutions. To notify the people within your institution of your interest, you can request to use a certain application.',
                    ],
                ],
                'pea-support' => [
                    'title' => 'Project Educatieve Applicaties: support at our institute',

                    'text' => 'In a later stage, you can find information from our institution concerning the usage of this platform here. For example about the design, processes, support and so on. Because the project is still in a Proof-of-Concept stage, there is no specific information here yet.',
                ],
                'about-edutools-project-and-surf-title' => [
                    'title' => 'Background information Project Educatieve Applicaties and SURF',

                    'text' => 'Several institutions have expressed the need to gain insight, overview and control over their own application landscape, in other words the applications that are used within the institutions. This need has so far led to a number of individual initiatives at institutions, which have many similarities. Because these solutions partly contain the same information, SURF investigated whether we can work together on this at a national level. The Project Educatieve Applicaties has been started in consultation with a number of institutions in The Netherlands. They worked together on the design, plus building this platform. For more information, please check: https://www.surf.nl/project-educatieve-applicaties',
                ],
            ],
        ],
    ],

    'other' => [
        'tool' => [
            'index' => [
                'title'    => 'Other tools',
                'heading'  => 'Results (:count)',
                'subtitle' => "Find tools that aren't available in our institution yet",
            ],
            'show' => [
                'title'   => ':name',
                'heading' => ':name',

                'header' => [
                    'status'            => 'Status',
                    'experiences'       => 'Experiences',
                    'total_experiences' => ':count total',

                    'info' => [
                        'text'     => 'This tool has not been published or prohibited yet for your institution',
                        'add'      => 'Add to collection',
                        'prohibit' => 'Make prohibited',
                    ],
                ],

                'tabs' => [
                    'product'              => 'Product',
                    'technical'            => 'Technical',
                    'privacy_and_security' => 'Privacy & Security',
                    'support'              => 'Support',
                    'education'            => 'Education',
                ],

                'headings' => [
                    'request_a_change' => 'Request a change',
                    'features'         => 'Features',
                    'institutes'       => 'Used by :count institute|Used by :count institutes',
                ],
            ],
        ],
    ],

    'our' => [
        'tool' => [
            'index' => [
                'title'       => 'Our tools',
                'heading'     => 'Results (:count)',
                'other_tools' => [
                    'title'       => 'Tools',
                    'explanation' => 'Not used within our organisation',
                ],

                'current_filters'    => 'Current filters',
                'remove_all_filters' => 'Remove all filters',

                'results_without_filter_or_search' => 'There are no results within your chosen filters or search query. Turn off one or more filters or adjust your search to see results.',
            ],
            'show' => [
                'title'   => ':name',
                'heading' => ':name',

                'header' => [
                    'for_institute'     => 'For :institute',
                    'experiences'       => 'Experiences',
                    'total_experiences' => ':count total',
                ],

                'tabs' => [
                    'product'              => 'Product',
                    'technical'            => 'Technical',
                    'privacy_and_security' => 'Privacy & Security',
                    'support'              => 'Support',
                    'education'            => 'Education',
                ],

                'headings' => [
                    'alternative_tools' => 'Alternative tools',
                    'request_a_change'  => 'Request a change',
                    'features'          => 'Features',
                    'categories'        => ':institute categories',
                    'custom_fields'     => ':institute custom fields',
                ],

                'content_manager_filled_out' => 'The following information on this tool has been filled out by the content manager',
                'none'                       => 'none',
            ],
        ],
    ],

    'content-manager' => [
        'tool' => [
            'form' => [
                'headings' => [
                    'product'              => 'Product',
                    'technical'            => 'Technical',
                    'privacy_and_security' => 'Privacy & Security',
                    'support'              => 'Support',
                    'education'            => 'Education',
                ],
                'captions' => [
                    'image-format-sm'   => 'Image must be .PNG or .JPEG, at least 300x300, square format',
                    'short-description' => 'Describe in two lines what the tool can do',
                    'image-format-lg'   => 'Image must be .PNG or .JPEG, at least 1080 x 566, landscape format',
                ],
            ],

            'index' => [
                'title'   => 'All tools',
                'heading' => 'All tools',
            ],
            'create' => [
                'title'   => 'Add a new tool',
                'heading' => 'Add a new tool',
            ],
            'edit' => [
                'title'    => 'Edit :name',
                'heading'  => 'Edit :name',
                'view_log' => 'View log',
            ],
            'log' => [
                'title' => 'Edit log',
            ],
        ],

        'tag' => [
            'form' => [
                'headings' => [
                    'general' => 'General',
                ],
            ],

            'index' => [
                'title'   => 'All tags',
                'heading' => 'All tags',
            ],

            'create' => [
                'title'   => 'Create new tag',
                'heading' => 'Create new tag',
            ],
        ],
    ],

    'information-manager' => [
        'tool' => [
            'form' => [
                'marked-as-fit'   => 'This tool has been marked as fit.',
                'marked-as-unfit' => 'This tool is marked as unfit.',

                'change-to-fit'   => 'Add to our tools',
                'change-to-unfit' => 'Change to prohibited',

                'headings' => [
                    'product'              => 'Product',
                    'technical'            => 'Technical',
                    'privacy_and_security' => 'Privacy & Security',
                    'support'              => 'Support',
                    'education'            => 'Education',
                    'custom-fields'        => 'Custom fields',
                ],
                'captions' => [
                    'image-format' => 'Image must be .PNG or .JPEG',
                ],
            ],

            'create' => [
                'title' => 'Add :tool',
            ],
            'edit' => [
                'title'    => 'Edit :tool',
                'view_log' => 'View log',
            ],
            'index' => [
                'title' => 'Manage our tools',
            ],
            'log' => [
                'title' => 'Edit log',
            ],
        ],

        'category' => [
            'form' => [
                'headings' => [
                    'general' => 'General',
                ],
                'captions' => [
                    'character-limit' => 'The maximum character limit for this field is 1024',
                ],
            ],

            'index' => [
                'title'   => 'All categories',
                'heading' => 'All categories',
            ],
            'create' => [
                'title'   => 'Add a new category',
                'heading' => 'Add a new category',
            ],
            'edit' => [
                'title'   => 'Edit :name',
                'heading' => 'Edit :name',
            ],
        ],

        'tag' => [
            'form' => [
                'headings' => [
                    'general' => 'General',
                ],
            ],

            'index' => [
                'title'   => 'All Categories',
                'heading' => 'All categories',
            ],
            'edit' => [
                'title'   => 'Edit :name',
                'heading' => 'Edit :name',
            ],
            'create' => [
                'title'   => 'Add a new category',
                'heading' => 'Add a new category',
            ],
        ],

        'custom-field' => [
            'form' => [
                'headings' => [
                    'general' => 'General',
                ],
                'captions' => [
                    'character-limit' => 'The maximum character limit for this field is 1024',
                ],
            ],

            'index' => [
                'title'   => 'All custom fields',
                'heading' => 'All custom fields',
            ],
            'create' => [
                'title'   => 'Add a new custom field',
                'heading' => 'Add a new custom field',
            ],
            'edit' => [
                'title'   => 'Edit :title',
                'heading' => 'Edit :title',
            ],
        ],

        'homepage-information' => [
            'edit' => [
                'title'   => 'Edit homepage information',
                'heading' => 'Edit homepage information',
            ],
        ],

        'notifications' => [
            'title'   => 'Update followers',
            'heading' => 'Send update to followers',

            'form' => [
                'tool'    => 'Select a tool...',
                'subject' => 'Enter a subject for the e-mail',
                'message' => 'Type a message to the followers',
            ],
        ],
    ],

    'manage-our-tools' => [
        'index' => [
            'title' => 'Manage our tools',
        ],
    ],

    'tool' => [
        'index' => [
            'title'   => 'All tools',
            'heading' => 'Results (:count)',
        ],

        'tip' => [
            'tip'   => 'Tip',
            'title' => 'Looking for something else?',
            'text'  => 'Is the tool you are looking for not listed? Send an email to the PEA support team.',
        ],

        'follow' => [
            'button' => [
                'following'     => 'Unfollow',
                'not-following' => 'Follow',
            ],

            'tooltip' => [
                'following'     => 'Click this button to stop receiving updates related to this application',
                'not-following' => 'Click this button to receive updates related to this application',
            ],
        ],
    ],

    'content-page' => [
        'form' => [
            'headings' => [
                'general' => 'General',
            ],
        ],

        'index' => [
            'title' => 'Content pages',
        ],

        'create' => [
            'heading' => 'Add a new content page',
        ],

        'edit' => [
            'title'   => 'Edit :title',
            'heading' => 'Edit :title',
        ],
    ],

    'admin' => [
        'institutes' => [
            'index' => [
                'title'   => 'Institutes',
                'heading' => 'Institutes',
            ],
        ],
    ],
];
