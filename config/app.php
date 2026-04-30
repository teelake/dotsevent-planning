<?php

declare(strict_types=1);

return [
    'name' => 'DOTS Event Planning',
    // Staging / subfolder: site lives at https://yoursite.com/new/ — used for every link and asset URL.
    // Must match public/.htaccess RewriteBase. If left empty, the app infers the prefix from PHP's
    // SCRIPT_NAME (e.g. /new/index.php → /new). Prefer setting this explicitly on production.
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
    'address_line1' => '473 Suite E, Millidge Avenue',
    'address_line2' => 'Saint John, NB',
    'address_country' => 'CA',
    /**
     * Google Maps embed (no API key). Use Maps “Share → Embed a map”; override in Admin → CMS → Global settings.
     */
    'map_embed_url' => 'https://maps.google.com/maps?q=473+Millidge+Avenue+Suite+E+Saint+John+NB+Canada&hl=en&z=16&output=embed',

    /**
     * Social profile URLs — leave empty to hide that icon in the footer.
     */
    'social_facebook' => '',
    'social_instagram' => '',
    'social_youtube' => '',
    'social_whatsapp' => '',

    /**
     * Public site URL (no trailing slash) for canonical links, Open Graph, and Twitter.
     * Example: https://dotseventplanning.com — leave empty to skip absolute og:url and canonical.
     */
    'public_origin' => '',

    /**
     * Default meta description; pages may override per-route in controllers.
     */
    'meta_description' => 'DOTS Event Planning: weddings, corporate events, kids parties, and decor rentals in Saint John, NB. Honest budgets, on-site coordination, and inventory you can add to cart.',

    /**
     * Optional: path under /public/ for og:image, e.g. "assets/og/og-1200x630.png". Empty = no og:image.
     */
    'og_image' => '',
];
