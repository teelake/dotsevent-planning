<?php

declare(strict_types=1);

return [
    'version' => 1,

    'hero' => [
        'enabled'          => true,
        'kicker'           => 'Event-ready inventory',
        'title'            => 'Rent the pieces that make the room.',
        'subtitle'         => 'Chairs, tables, backdrops & décor — available for pickup or delivery in Saint John, NB.',
        'cta_primary_label'   => 'Browse rentals',
        'cta_primary_href'    => '#rentals-catalog',
        'cta_secondary_label' => 'View cart',
        'cta_secondary_href'  => '/cart',
        'bg_image_path'    => '',
        'show_breadcrumbs' => true,
    ],

    'categories' => [
        'enabled'   => true,
        'all_label' => 'All items',
        'items'     => [
            [
                'key'   => 'chairs',
                'label' => 'Chairs',
                'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect x="6" y="2" width="12" height="10" rx="2"/><path d="M4 12h16M6 12v8M18 12v8M6 20h12"/></svg>',
            ],
            [
                'key'   => 'tables',
                'label' => 'Tables',
                'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="8" width="20" height="4" rx="1"/><line x1="6" y1="12" x2="6" y2="20"/><line x1="18" y1="12" x2="18" y2="20"/></svg>',
            ],
            [
                'key'   => 'backdrops',
                'label' => 'Backdrops',
                'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 3v18"/></svg>',
            ],
            [
                'key'   => 'linens',
                'label' => 'Linens',
                'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M3 4h18v4H3z"/><path d="M5 8v12h14V8"/><line x1="9" y1="12" x2="15" y2="12"/></svg>',
            ],
            [
                'key'   => 'decor',
                'label' => 'Décor',
                'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a9 9 0 0 1 9 9c0 5-9 13-9 13S3 16 3 11a9 9 0 0 1 9-9z"/><circle cx="12" cy="11" r="3"/></svg>',
            ],
            [
                'key'   => 'accessories',
                'label' => 'Accessories',
                'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 8v4l3 3"/></svg>',
            ],
        ],
    ],

    'controls' => [
        'enabled'                  => true,
        'show_search'              => true,
        'search_placeholder'       => 'Search rentals…',
        'result_label_singular'    => 'rental',
        'result_label_plural'      => 'rentals',
        'sort_options'             => [
            ['value' => 'default',    'label' => 'Default sorting'],
            ['value' => 'price_asc',  'label' => 'Price: Low to High'],
            ['value' => 'price_desc', 'label' => 'Price: High to Low'],
            ['value' => 'name_asc',   'label' => 'Name A–Z'],
        ],
    ],

    'how_it_works' => [
        'enabled'   => true,
        'title'     => 'How renting works',
        'steps'     => [
            [
                'number'      => '01',
                'title'       => 'Browse & add to cart',
                'description' => 'Choose from our curated rental inventory and add items to your cart. Checkout is powered by Square.',
            ],
            [
                'number'      => '02',
                'title'       => 'We confirm your event date',
                'description' => 'After checkout DOTS reaches out to confirm availability and your event logistics.',
            ],
            [
                'number'      => '03',
                'title'       => 'Pickup or delivery',
                'description' => 'Items arrive clean and event-ready. Return instructions are included with your booking.',
            ],
        ],
        'cta_label' => 'Browse rentals',
        'cta_href'  => '#rentals-catalog',
    ],

    'logistics' => [
        'enabled' => true,
        'items'   => [
            [
                'icon'  => 'truck',
                'label' => 'Saint John & area delivery available',
            ],
            [
                'icon'  => 'sparkle',
                'label' => 'All items cleaned between bookings',
            ],
            [
                'icon'  => 'shield',
                'label' => 'Damage deposit may apply on select items',
            ],
            [
                'icon'  => 'lock',
                'label' => 'Secure checkout via Square',
            ],
        ],
    ],

    'newsletter_cta' => [
        'enabled'      => true,
        'title'        => 'Sign Up Today for Exclusive Event Planning Secrets!',
        'text_html'    => '<p>Make every occasion unforgettable. Join our email list now.</p>',
        'button_label' => 'Submit',
        'placeholder'  => 'Your email address',
    ],
];
