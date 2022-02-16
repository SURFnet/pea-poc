<?php

declare(strict_types=1);

return [
    'open-menu'  => 'Open menu',
    'select-tab' => 'Select a tab',
    'search'     => 'Search',

    'app-name' => 'Project educatieve applicaties',

    'menu' => [
        'our-tools'        => 'Our tools',
        'other-tools'      => 'Other tools',
        'manage-our-tools' => 'Manage our tools',
        'manage-all-tools' => 'Manage all tools',
        'about'            => 'About',
    ],

    'shared' => [
        'section-header' => [
            'search-our'   => 'Search within Our tools',
            'search-other' => 'Search within Other tools',
        ],

        'tool' => [
            'experiences'       => 'Experiences',
            'total_experiences' => ':count total',
            'no_experiences'    => 'No experiences have been shared yet.',
            'share_experience'  => 'Share experience',
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
                        // phpcs:ignore Generic.Files.LineLength.TooLong
                        'Through this website you can find educational applications that you would like to use and that meet your, and your institutions’ requirements. Within ‘our tools’ you will find all applications that are checked by your institution and reviewed by your peers. You can publish your experience working with these particular apps and therefore create useful information for colleagues.',

                        // phpcs:ignore Generic.Files.LineLength.TooLong
                        'If the application you are looking for is not available within your institution, please either use the search field or browse through ‘other tools’. These are the applications that are not yet identified within your institution, but are known within the market or sometimes used by other institutions. To notify the people within your institution of your interest, you can request to use a certain application.',
                    ],
                ],
                'pea-support' => [
                    'title' => 'Project Educatieve Applicaties: support at our institute',

                    // phpcs:ignore Generic.Files.LineLength.TooLong
                    'text' => 'In a later stage, you can find information from our institution concerning the usage of this platform here. For example about the design, processes, support and so on. Because the project is still in a Proof-of-Concept stage, there is no specific information here yet.',
                ],
                'about-edutools-project-and-surf-title' => [
                    'title' => 'Background information Project Educatieve Applicaties and SURF',

                    // phpcs:ignore Generic.Files.LineLength.TooLong
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
                    'description'    => 'Description',
                    'technical_info' => 'Technical info',
                ],

                'headings' => [
                    'product_description'   => 'Product description',
                    'technical_information' => 'Technical information',
                    'features'              => 'Features',
                    'institutes'            => 'Used by :count institute|Used by :count institutes',
                ],

                'labels' => [
                    'description_tool'         => 'Description tool',
                    'examples_of_usage'        => 'Examples of usage',
                    'more_info'                => 'More information on this tool',
                    'supported_standards'      => 'Supported integration standards',
                    'additional_standards'     => 'Additional standards',
                    'supported_authentication' => 'Supported authentication',
                    'data_stored'              => 'Data being stored',
                    'storage_location'         => 'Data storage location',
                    'surf_framework'           => 'SURF framework',
                    'processing_agreement'     => 'General processing agreement',
                ],

                'options' => [
                    'europe'         => 'Europe',
                    'outside_europe' => 'Outside Europe',

                    'present'     => 'Present',
                    'not_present' => 'Not present',

                    'complies'        => 'Complies',
                    'does_not_comply' => 'Does not comply',
                ],
            ],
        ],
    ],

    'our' => [
        'tool' => [
            'index' => [
                'title'   => 'Our tools',
                'heading' => 'Results (:count)',
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
                    'description'    => 'Description',
                    'how_to_use'     => 'How to use',
                    'why_not_use'    => 'Why not use',
                    'technical_info' => 'Technical info',
                ],

                'headings' => [
                    'product_description'   => 'Product description',
                    'technical_information' => 'Technical information',
                    'features'              => 'Features',
                    'categories'            => ':institute categories',
                    'why_not_use'           => 'Why not use this tool',
                    'alternative_tool'      => 'Alternative tool',
                    'support'               => 'Support',
                ],

                'labels' => [
                    'description_tool'  => 'Description tool',
                    'examples_of_usage' => 'Examples of usage',
                    'more_info'         => 'More information on this tool',

                    'how_it_works'       => 'How it works',
                    'tips'               => 'Tips',
                    'extra_information'  => 'Extra information',
                    'manuals'            => 'Manuals',
                    'instruction_videos' => 'Instruction video\'s',

                    'supported_standards'      => 'Supported integration standards',
                    'additional_standards'     => 'Additional standards',
                    'supported_authentication' => 'Supported authentication',
                    'data_stored'              => 'Data being stored',
                    'storage_location'         => 'Data storage location',
                    'surf_framework'           => 'SURF framework',
                    'processing_agreement'     => 'General processing agreement',
                ],

                'options' => [
                    'europe'         => 'Europe',
                    'outside_europe' => 'Outside Europe',

                    'present'     => 'Present',
                    'not_present' => 'Not present',

                    'complies'        => 'Complies',
                    'does_not_comply' => 'Does not comply',
                ],

                'why_not_use' => [
                    'no_alternative' => 'Your institute has not marked an alternative for this tool.',
                ],
            ],
            'tip' => [
                'tip'   => 'Tip',
                'title' => 'Looking for something else?',
                'text'  => 'If the application within Our Tools does not meet your requirements,
                    look what other institutes are using.',
                'button' => 'Search within other tools',
            ],
        ],
    ],

    'content-manager' => [
        'tool' => [
            'form' => [
                'headings' => [
                    'general'     => 'General information',
                    'technical'   => 'Technical information',
                    'description' => 'Description',
                ],
                'captions' => [
                    'image-format-sm'   => 'Image must be .PNG or .JPEG, at least 300x300, square format',
                    'short-description' => 'Describe in two lines what the tool can do',
                    'use-comma'         => 'Use a comma to add multiple services',
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
                'title'   => 'Edit :name',
                'heading' => 'Edit :name',
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
                    'general'           => 'General',
                    'description'       => 'Description',
                    'extra-information' => 'Extra information',
                    'support'           => 'Support',
                ],
                'captions' => [
                    'image-format' => 'Image must be .PNG or .JPEG',
                ],
            ],

            'create' => [
                'title' => 'Add :tool',
            ],
            'edit' => [
                'title' => 'Edit :tool',
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
    ],

    'manage-our-tools' => [
        'index' => [
            'title' => 'Manage our tools',
        ],
    ],
];
