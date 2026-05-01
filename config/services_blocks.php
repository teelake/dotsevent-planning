<?php

declare(strict_types=1);

/**
 * Defaults for cms_pages.slug=services merged with content_json.blocks.
 */
return [
    'version' => 1,
    'hero' => [
        'enabled' => true,
        'show_breadcrumbs' => true,
        'kicker' => '',
        'title' => 'Services',
    ],
    'intro' => [
        'enabled' => true,
        'eyebrow' => 'Our services',
        'title' => 'Capability across your whole event stack',
        'lead_html' => '<p>From brief to strike, DOTS plans weddings, private parties, corporate nights, immersive photo experiences, kids celebrations, and end-to-end production—everywhere in New Brunswick with one calm command channel.</p>',
    ],
    'offerings' => [
        /* Services page: full offerings band */
        'enabled' => true,
        /* Home page: teaser grid (independent of `enabled`) */
        'home_teaser_enabled' => true,
        'eyebrow' => 'Capability',
        'section_title' => 'What we configure for you',
        'home_teaser_cta_label' => '',
        'home_teaser_cta_href' => '',
        'items' => [
            [
                'title' => 'Wedding planning',
                'summary_html' => '<p>Ceremony through reception logistics—family dynamics, pacing, paper cues guests never see.</p>',
                'accent' => false,
                'muted' => false,
            ],
            [
                'title' => 'Private parties',
                'summary_html' => '<p>Birthdays, anniversaries, and themed nights with décor and vendor arcs that behave.</p>',
                'accent' => true,
                'muted' => false,
            ],
            [
                'title' => 'Corporate events',
                'summary_html' => '<p>Launches, town halls, and galas with signage, AV rehearsal, and run-of-show you can circulate early.</p>',
                'accent' => false,
                'muted' => false,
            ],
            [
                'title' => '360° photo booth',
                'summary_html' => '<p>Guests step in; teams wrangle cabling, pacing, lighting, and egress so queues stay humane.</p>',
                'accent' => false,
                'muted' => true,
            ],
            [
                'title' => 'Kids parties',
                'summary_html' => '<p>Themes, teardown, parental nerves—timed like a sprint with joy baked in.</p>',
                'accent' => false,
                'muted' => false,
            ],
            [
                'title' => 'Expert planning',
                'summary_html' => '<p>Umbrella production when multiple vendors need one decisive lead on headset.</p>',
                'accent' => false,
                'muted' => false,
            ],
        ],
    ],
    'faq' => [
        'enabled' => true,
        'eyebrow' => 'FAQ',
        'title' => 'Answers to common questions',
        'lead_html' => '<p>Quick clarification before we talk dates and crew.</p>',
        'open_first' => true,
        'items' => [
            [
                'question' => 'Can DOTS work within different budgets?',
                'answer_html' => '<p>Yes—we align scope tiers early, disclose trade-offs, and steward estimates so approvals happen before linens ship.</p>',
            ],
            [
                'question' => 'What types of events do you specialize in?',
                'answer_html' => '<p>Corporate programs, weddings, civic celebrations, family milestones, experiential tech like 360 rigs, plus kids-first packages when parents want joy without chaos.</p>',
            ],
            [
                'question' => 'How does DOTS ensure each event succeeds?',
                'answer_html' => '<p>Single planner-of-record with documented run sheets, rehearsals for anything with a cue, contingency math on weather and AV, plus on-site comms crews understand.</p>',
            ],
            [
                'question' => 'What sets DOTS apart?',
                'answer_html' => '<p>We merge logistics empathy with decisive production—fewer spreadsheets in your inbox at midnight, clearer ownership when something deviates.</p>',
            ],
        ],
    ],
    'newsletter_cta' => [
        'enabled' => true,
        'title' => 'Notes worth opening',
        'text_html' => '<p>Limited-send field notes—one playbook idea per drop, unsubscribe anytime.</p>',
        'button_label' => 'Subscribe',
        'placeholder' => 'Your email address',
    ],
];
