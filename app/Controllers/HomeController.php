<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

final class HomeController extends Controller
{
    public function index(): void
    {
        $defaultMeta = 'Weddings, corporate, and kids events in Saint John, NB—plus decor rentals. Featured services: planning, on-site support, and clear budgets from DOTS Event Planning.';
        $slides = [
            [
                'image' => 'https://images.unsplash.com/photo-1519167758481-83f550bb49b3?auto=format&fit=crop&w=1920&q=80',
                'alt' => 'Elegant evening event with floral arch',
                'eyebrow' => 'Saint John & beyond',
                'title' => 'The kind of night people talk about on Monday',
                'subtitle' => 'Corporate, weddings, and family celebrations—planned like we’re hosting our own, with clear budgets and a team that’s there when the mic cuts out.',
                'cta_label' => 'Start with a call',
                'cta_href' => app_url('book'),
                'secondary_label' => 'View services',
                'secondary_href' => app_url('services'),
            ],
            [
                'image' => 'https://images.unsplash.com/photo-1464366400600-7168b8af9bc3?auto=format&fit=crop&w=1920&q=80',
                'alt' => 'Wedding tablescape with candlelight',
                'eyebrow' => 'Weddings & social',
                'title' => 'Your names on the program—not on the to-do list',
                'subtitle' => 'Flow, food timing, and who steers the run-of-show—so you can eat dinner and see your people.',
                'cta_label' => 'Wedding & social',
                'cta_href' => app_url('book'),
                'secondary_label' => 'See portfolio',
                'secondary_href' => app_url('portfolio'),
            ],
            [
                'image' => 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=1920&q=80',
                'alt' => 'Corporate event with stage lighting',
                'eyebrow' => 'Corporate & brand',
                'title' => 'The AV works. The sign-in desk has pens. The CEO goes on at 7:10.',
                'subtitle' => 'Launches, galas, and team nights—tight production and branding that still feels human.',
                'cta_label' => 'Tell us the brief',
                'cta_href' => app_url('contact'),
                'secondary_label' => 'Our services',
                'secondary_href' => app_url('services'),
            ],
        ];

        $cms = cms_public_home($slides, $defaultMeta);

        $this->render('home/index', [
            'title' => 'Home',
            'active_nav' => 'home',
            'body_class' => 'page-home',
            'slides' => $cms['slides'],
            'home_intro_html' => $cms['intro_html'],
            'home_blocks' => $cms['home_blocks'],
            'home_services_teaser' => $cms['home_services_teaser'],
            'meta_description' => $cms['meta_description'],
        ]);
    }
}
