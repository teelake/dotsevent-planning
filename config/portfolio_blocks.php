<?php

declare(strict_types=1);

return [
    'version' => 1,
    'hero' => [
        'enabled' => true,
        'kicker' => 'Our portfolio',
        'show_breadcrumbs' => true,
    ],
    'intro' => [
        'enabled' => true,
        'eyebrow' => 'Portfolio',
        'title' => 'Immerse Yourself in Memories',
        'lead_html' => '<p>Welcome to a glimpse of our recent moments. From immersive 360 setups to elegant decor installations, every project is curated to feel memorable and unmistakably yours.</p>',
    ],
    'controls' => [
        'enabled' => true,
        'show_search' => true,
        'show_sort' => true,
        'default_sort' => 'featured',
        'filters' => [
            [
                'key' => 'category',
                'label' => 'Category',
                'options' => ['All', 'Weddings', 'Corporate', 'Kids Parties', '360 Booth'],
            ],
        ],
    ],
    'featured' => [
        'enabled' => true,
        'title' => 'Featured work',
        'subtitle' => 'Signature executions with strong visual storytelling.',
        'items' => [
            [
                'title' => '360 Booth Immersive Activation',
                'tag' => '360 Booth',
                'summary' => 'Interactive setup with branded surround and queue flow design.',
                'image_path' => '',
                'alt' => '360 booth setup',
                'location' => 'Saint John, NB',
                'event_date' => '',
                'featured' => true,
            ],
            [
                'title' => 'Elegant Floral Ceremony Framing',
                'tag' => 'Wedding',
                'summary' => 'Ceremony focal structure with premium floral detailing.',
                'image_path' => '',
                'alt' => 'Ceremony arch decor',
                'location' => 'New Brunswick',
                'event_date' => '',
                'featured' => true,
            ],
        ],
    ],
    'gallery' => [
        'enabled' => true,
        'title' => 'Project gallery',
        'layout_mode' => 'editorial',
        'items' => [
            [
                'title' => 'Immersive 360 experience',
                'tag' => '360 Booth',
                'summary' => 'Photo-first experience with optimized flow and staging.',
                'image_path' => '',
                'alt' => '360 booth event',
                'location' => '',
                'event_date' => '',
                'services' => ['360 booth', 'event styling'],
            ],
            [
                'title' => 'Corporate showcase floor',
                'tag' => 'Corporate',
                'summary' => 'Multi-zone event execution with coordinated floor plan.',
                'image_path' => '',
                'alt' => 'Corporate event floor setup',
                'location' => '',
                'event_date' => '',
                'services' => ['event planning', 'vendor coordination'],
            ],
        ],
    ],
    'newsletter_cta' => [
        'enabled' => true,
        'title' => 'Sign Up Today for Exclusive Event Planning Secrets!',
        'text_html' => '<p>Make every occasion unforgettable. Join our email list now.</p>',
        'button_label' => 'Submit',
        'placeholder' => 'Your email address',
    ],
];

