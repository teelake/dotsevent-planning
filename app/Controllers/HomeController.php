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
                'title' => 'Exceptional events, crafted with clarity',
                'subtitle' => 'From first idea to the last detail—corporate, weddings, and celebrations that feel unmistakably yours.',
                'cta_label' => 'Book a free session',
                'cta_href' => app_url('book-your-event'),
                'secondary_label' => 'View services',
                'secondary_href' => app_url('services'),
            ],
            [
                'image' => 'https://images.unsplash.com/photo-1464366400600-7168b8af9bc3?auto=format&fit=crop&w=1920&q=80',
                'alt' => 'Wedding tablescape with candlelight',
                'eyebrow' => 'Weddings & social',
                'title' => 'Memories in every moment',
                'subtitle' => 'Thoughtful design, calm coordination, and a team obsessed with the details that guests remember.',
                'cta_label' => 'Plan your day',
                'cta_href' => app_url('book-your-event'),
                'secondary_label' => 'See portfolio',
                'secondary_href' => app_url('portfolio'),
            ],
            [
                'image' => 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=1920&q=80',
                'alt' => 'Corporate event with stage lighting',
                'eyebrow' => 'Corporate & brand',
                'title' => 'Flawless experiences for your audience',
                'subtitle' => 'Launches, galas, and company milestones—produced to reflect your brand with polish.',
                'cta_label' => 'Start a brief',
                'cta_href' => app_url('contact'),
                'secondary_label' => 'Our services',
                'secondary_href' => app_url('services'),
            ],
            [
                'image' => 'https://images.unsplash.com/photo-1530103862676-de8c9de59e71?auto=format&fit=crop&w=1920&q=80',
                'alt' => 'Colourful party balloons and decor',
                'eyebrow' => 'Kids & families',
                'title' => 'Joy-filled parties, stress-free for you',
                'subtitle' => 'Packages, add-ons, and play experiences designed for big smiles and smooth hosting.',
                'cta_label' => 'Kids party packages',
                'cta_href' => app_url('kids-party'),
                'secondary_label' => 'Contact us',
                'secondary_href' => app_url('contact'),
            ],
            [
                'image' => 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=1920&q=80',
                'alt' => 'Elegant place settings and glassware',
                'eyebrow' => 'Rentals',
                'title' => 'Chairs, backdrops, and finishing touches',
                'subtitle' => 'Curated inventory to elevate your venue—shop rentals with secure checkout online.',
                'cta_label' => 'Shop rentals',
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
