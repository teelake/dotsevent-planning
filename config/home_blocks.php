<?php

declare(strict_types=1);

/**
 * Default structured homepage sections (CMS). Keys merged with cms_pages.slug=home content_json.blocks.
 */
return [
    'version' => 1,
    'confidence' => [
        'enabled' => true,
        'eyebrow' => 'Saint John & region',
        'title' => 'Events are loud—planning shouldn’t be',
        'lead' => 'We sweat the brief, the budget, and the backup plan so you’re not scrambling the night before. Clear costs, timelines you can trust, and planners who answer when it matters.',
        'cta_label' => 'How we work',
        'cta_href' => '', // resolved in merge to app_url about
        'metrics' => [
            ['label' => 'Happy clients', 'display' => '300+', 'target' => 300, 'suffix' => '+'],
            ['label' => 'Events delivered', 'display' => '150+', 'target' => 150, 'suffix' => '+'],
            ['label' => 'Pieces in inventory', 'display' => '109+', 'target' => 109, 'suffix' => '+'],
            ['label' => 'Years crafting nights', 'display' => '18+', 'target' => 18, 'suffix' => '+'],
        ],
    ],
    'partnership' => [
        'enabled' => true,
        'kicker' => 'Your ultimate partner',
        'title' => 'From first sketch to last dance',
        'lead' => 'DOTS blends logistics with décor—floor plans beside mood boards—so timelines, signage, and the quiet moments all feel considered. Corporate launches, weddings, and celebrations across New Brunswick with one north star: you enjoy the room.',
        'pull_quote' => 'We host your night like we’re hosting ours—calm backstage, radiant up front.',
        'cta_label' => 'About DOTS',
        'cta_href' => '',
    ],
    'packages' => [
        'enabled' => true,
        'eyebrow' => 'Investment',
        'title' => 'Transparent packages',
        'subtitle' => 'Starting points—we tailor after we hear the room size, guest count, and level of sparkle.',
        'items' => [
            [
                'name' => 'Essential',
                'price_display' => 'Starting at CAD $1,500',
                'featured' => false,
                'booking_package' => 'basic',
                'cta_label' => 'Discuss Essential',
                'cta_href' => '',
                'features' => [
                    'Discovery call & written brief',
                    'Single-venue itinerary & checklist',
                    'Vendor shortlist introductions',
                    'Day-of cue sheet (+ email support)',
                ],
            ],
            [
                'name' => 'Signature',
                'price_display' => 'Starting at CAD $2,500',
                'featured' => true,
                'booking_package' => 'premium',
                'cta_label' => 'Reserve Signature',
                'cta_href' => '',
                'features' => [
                    'Everything in Essential',
                    'Two design-direction sessions',
                    'Floor plan revisions & signage pack',
                    'On-site planner (up to 8 hours)',
                    'Incident triage playbook',
                ],
            ],
            [
                'name' => 'Elevate',
                'price_display' => 'Starting at CAD $4,000',
                'featured' => false,
                'booking_package' => 'vip',
                'cta_label' => 'Plan Elevate',
                'cta_href' => '',
                'features' => [
                    'Everything in Signature',
                    'Dedicated senior planner tour',
                    'Multi-day rehearsal & vendor tech check',
                    'Extended on-site roster & strike plan',
                    'Post-event recap + photo delivery assist',
                ],
            ],
        ],
    ],
    'testimonials' => [
        'enabled' => true,
        'title' => 'Voices from the room',
        'subtitle' => 'Clients who traded stress for swagger.',
        'quotes' => [
            ['quote' => 'Guests thought family ran the whole evening—that’s exactly the quiet miracle we hoped for.', 'name' => 'Alex & Mara', 'role' => 'Wedding, Saint John'],
            ['quote' => 'Brief was chaos. Delivered program was immaculate. Exec team still talks about the pacing.', 'name' => 'Regional Director', 'role' => 'Corporate gala'],
            ['quote' => 'Kids tore through the cupcake wall and we still smiled in the recap photos—actually smiled.', 'name' => 'Sarah P.', 'role' => 'Birthday, Quispamsis'],
        ],
    ],
    'newsletter' => [
        'enabled' => true,
        'title' => 'Short notes, zero fluff',
        'text' => 'A few times a year: one useful idea, one photo worth borrowing, where we\'re staffing next.',
        'button_label' => 'Subscribe',
        'placeholder' => 'Your email',
    ],
];
