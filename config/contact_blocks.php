<?php

declare(strict_types=1);

return [
    'version' => 1,
    'hero' => [
        'enabled' => true,
        'kicker' => 'Say hello',
        'show_breadcrumbs' => true,
    ],
    'intro' => [
        'enabled' => true,
        'title' => 'Ready to spark a conversation? Let’s chat today and bring fresh ideas to life!',
        'lead_html' => '',
    ],
    'channels' => [
        'enabled' => true,
        'items' => [
            ['type' => 'email', 'label' => 'Our Email', 'availability_line' => '24/7 anytime'],
            ['type' => 'office', 'label' => 'Our Office', 'availability_line' => 'By appointment'],
            ['type' => 'phone', 'label' => 'Our Phone', 'availability_line' => 'Mon–Sun'],
        ],
    ],
    'contact_form' => [
        'enabled' => true,
        'heading' => 'We will reach out to you',
        'submit_label' => 'Send',
        'fields' => [
            'name' => ['label' => 'Name', 'placeholder' => 'Name', 'required' => true],
            'email' => ['label' => 'Email', 'placeholder' => 'Email', 'required' => true],
            'phone' => ['label' => 'Phone', 'placeholder' => 'Phone', 'required' => false],
            'subject' => ['label' => 'Subject', 'placeholder' => 'Subject', 'required' => true],
            'message' => ['label' => 'Message', 'placeholder' => 'Message', 'required' => true],
        ],
        'intent_subjects' => [
            ['key' => 'general', 'label' => 'General inquiry', 'subject' => 'General inquiry'],
            ['key' => 'kids', 'label' => 'Kids parties', 'subject' => 'Kids party planning request'],
            ['key' => 'corporate', 'label' => 'Corporate events', 'subject' => 'Corporate event request'],
            ['key' => 'wedding', 'label' => 'Weddings', 'subject' => 'Wedding planning request'],
            ['key' => 'rentals', 'label' => 'Rentals', 'subject' => 'Rental inquiry'],
        ],
    ],
    'location' => [
        'enabled' => true,
        'aside_title' => 'Visit or call',
        'map_title' => 'Our location',
        'open_in_maps_label' => 'Open in Maps',
    ],
    'newsletter_cta' => [
        'enabled' => true,
        'title' => 'Sign Up Today for Exclusive Event Planning Secrets!',
        'description_html' => '<p>Make every occasion unforgettable. Join our email list now.</p>',
        'email_placeholder' => 'Your email address',
        'button_label' => 'Submit',
        'privacy_note_html' => '',
    ],
    'trust' => [
        'enabled' => false,
        'star_count' => 5,
        'microcopy' => '',
    ],
    'floating_widget' => [
        'enabled' => false,
        'label' => 'Chat',
        'href' => '',
    ],
    'legacy_body' => [
        'enabled' => false,
    ],
];

