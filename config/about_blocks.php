<?php

declare(strict_types=1);

/**
 * Defaults for cms_pages.slug=about merged with content_json.blocks.
 */
return [
    'version' => 1,
    'hero' => [
        'enabled' => true,
        'show_breadcrumbs' => true,
        'kicker' => '',
        'title' => 'About us',
    ],
    'approach' => [
        'enabled' => true,
        'eyebrow' => 'More about us',
        'title' => 'Our approach',
        'lead_html' => '<p>Every event starts with translating your expectations into a workable brief—budget, tone, timelines, contingency. Strategy and sensitivity arrive together so details feel cohesive without crowding guests.</p>'
            . '<p>We obsess over run-of-show, vendor choreography, cues you did not realize had to fire, and signage that survives last-minute swaps—because meticulous attention is how planners earn back your attention on the dance floor.</p>',
        'images' => [
            ['src' => '', 'alt' => 'Venue vignette placeholder'],
            ['src' => '', 'alt' => 'Ambient lighting detail placeholder'],
            ['src' => '', 'alt' => 'Staging moment placeholder'],
        ],
    ],
    'values' => [
        'enabled' => true,
        'eyebrow' => 'Why choose us?',
        'title' => 'Our core values',
        'items' => [
            ['title' => 'Client-centric approach', 'subtitle' => 'Briefs anchored in outcomes.', 'summary_html' => '<p>Genuine listening before concepts—briefs anchored in measurable outcomes.</p>'],
            ['title' => 'Creativity & innovation', 'subtitle' => 'Fresh ideas within guardrails.', 'summary_html' => '<p>Fresh formats that still respect production constraints and brand guardrails.</p>'],
            ['title' => 'Attention to detail', 'subtitle' => 'Calm backstage, clear signage.', 'summary_html' => '<p>Micro-moments: signage readability, ADA-friendly paths, backstage calm.</p>'],
            ['title' => 'Professionalism', 'subtitle' => 'Roles clear before showtime.', 'summary_html' => '<p>Clear roles, headsets on, escalation paths ready before microphones hum.</p>'],
            ['title' => 'Integrity & transparency', 'subtitle' => 'No surprise invoices.', 'summary_html' => '<p>Numbers and trade-offs surfaced early—you approve before invoices surprise.</p>'],
        ],
    ],
    'team' => [
        'enabled' => true,
        'eyebrow' => 'More about us',
        'title' => 'Our team members',
        'intro_html' => '<p>Meet planners who steward creative direction, vendor alignment, guest flow, and the unsung backstage pass—photos optional.</p>',
        'members' => [
            [
                'name' => 'Tosin Ezekiel',
                'role' => 'Founder & lead planner',
                'photo' => '',
                'bio_html' => '',
            ],
            [
                'name' => 'Taiwo Adejugbe',
                'role' => 'Senior coordinator',
                'photo' => '',
                'bio_html' => '',
            ],
        ],
    ],
    'newsletter_cta' => [
        'enabled' => true,
        'title' => 'Sign up for notes that respect your inbox',
        'text_html' => '<p>Quarterly cues: one playbook idea, one production detail worth stealing—plus where DOTS crew is staffed next.</p>',
        'button_label' => 'Subscribe',
        'placeholder' => 'Your email address',
    ],
];
