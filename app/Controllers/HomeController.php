<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

final class HomeController extends Controller
{
    public function index(): void
    {
        $slides = [
            [
                'image' => 'https://images.unsplash.com/photo-1519167758481-83f550bb49b3?auto=format&fit=crop&w=1920&q=80',
                'alt' => 'Elegant evening event with floral arch',
                'eyebrow' => 'Saint John & beyond',
                'title' => 'The kind of night people talk about on Monday',
                'subtitle' => 'Corporate, weddings, and family celebrations—planned like we’re hosting our own, with clear budgets and a team that’s there when the mic cuts out.',
                'cta_label' => 'Start with a call',
                'cta_href' => app_url('book-your-event'),
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
                'cta_href' => app_url('book-your-event'),
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
            [
                'image' => 'https://images.unsplash.com/photo-1530103862676-de8c9de59e71?auto=format&fit=crop&w=1920&q=80',
                'alt' => 'Colourful party balloons and decor',
                'eyebrow' => 'Kids & families',
                'title' => 'For kids: chaos (the fun kind). For parents: coffee.',
                'subtitle' => 'Play packages, add-ons, and a setup that’s cleaned up before the driveway’s dark.',
                'cta_label' => 'Kids packages',
                'cta_href' => app_url('kids-party'),
                'secondary_label' => 'Contact us',
                'secondary_href' => app_url('contact'),
            ],
            [
                'image' => 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=1920&q=80',
                'alt' => 'Elegant place settings and glassware',
                'eyebrow' => 'Rentals',
                'title' => 'The chairs match. The backdrop isn’t a bedsheet.',
                'subtitle' => 'Real inventory, real photos—add to cart and check out when you’re ready.',
                'cta_label' => 'Browse rentals',
                'cta_href' => app_url('rentals'),
                'secondary_label' => 'How it works',
                'secondary_href' => app_url('contact'),
            ],
        ];

        $this->render('home/index', [
            'title' => 'Home',
            'active_nav' => 'home',
            'body_class' => 'page-home',
            'slides' => $slides,
        ]);
    }
}
