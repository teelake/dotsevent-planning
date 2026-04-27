<?php

declare(strict_types=1);

return [
    'name' => 'DOTS Event Planning',
    // Staging path on live host (review vs current site). Use '' for local dev at site root.
    'base_url' => '/new',
    'debug' => true,
    // Canada: Square location + payments should use CAD.
    'currency' => 'CAD',
    'currency_label' => 'CA$',
    // Absolute path (written by app/bootstrap). Server must allow writing to logs/.
    'error_log' => dirname(__DIR__) . '/logs/php-error.log',

    // Site contact (footer, contact page, JSON-LD later)
    'email' => 'info@dotseventplanning.com',
    'phone_display' => '+1 (506) 555-0100',
    // Digits only for tel: link (E.164 without spaces)
    'phone_tel' => '+15065550100',
    'address_line1' => '181 McNamara Drive',
    'address_line2' => 'Saint John, NB E2J 3L2',
    'address_country' => 'CA',
    /**
     * Google Maps embed (no API key). Replace with your own “Share → Embed map” URL in production if needed.
     */
    'map_embed_url' => 'https://maps.google.com/maps?q=181+McNamara+Drive%2C+Saint+John%2C+New+Brunswick%2C+Canada&hl=en&z=15&output=embed',

    /**
     * Social profile URLs — leave empty to hide that icon in the footer.
     */
    'social_facebook' => '',
    'social_instagram' => '',
    'social_youtube' => '',
];
