<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

final class PageController extends Controller
{
    public function notFound(): void
    {
        http_response_code(404);
        $this->render('errors/404', [
            'title' => 'Page not found',
            'active_nav' => '',
            'meta_description' => 'That page could not be found. Explore event planning services, book a call, browse rentals, or contact DOTS in Saint John, NB.',
        ]);
    }

    public function about(): void
    {
        $this->render('pages/about', [
            'title' => 'About us',
            'active_nav' => 'about',
            'meta_description' => 'About DOTS Event Planning in Saint John, NB—our story, what we value, and how we run weddings, corporate events, and celebrations with on-site hustle.',
        ]);
    }

    public function services(): void
    {
        $this->render('pages/services', [
            'title' => 'Services',
            'active_nav' => 'services',
            'meta_description' => 'Event planning for corporate, weddings, kids parties, and rentals in Saint John and the region—timelines, vendors, and production you can trust.',
        ]);
    }

    public function kidsParty(): void
    {
        $this->render('pages/kids-party', [
            'title' => 'Kids party packages',
            'active_nav' => 'kids',
            'meta_description' => 'Kids and family party packages in Saint John—themed decor, games, and teardown. Book by inquiry; we confirm details before you pay for packages.',
        ]);
    }

    public function portfolio(): void
    {
        $this->render('pages/portfolio', [
            'title' => 'Portfolio',
            'active_nav' => 'portfolio',
            'meta_description' => 'Case highlights from DOTS—weddings, corporate nights, and family celebrations we’ve planned in New Brunswick, with a focus on flow and real outcomes.',
        ]);
    }

    public function bookYourEvent(): void
    {
        $raw = (string) ($_GET['package'] ?? '');
        $allowed = ['basic', 'premium', 'vip', 'not_sure'];
        $preselect = in_array($raw, $allowed, true) ? $raw : null;
        $this->render('pages/book-your-event', [
            'title' => 'Book',
            'active_nav' => 'book',
            'preselect_package' => $preselect,
            'meta_description' => 'Request a booking for your event—package tiers from basic to VIP. We follow up to confirm; event packages are not paid on this form.',
        ]);
    }

    public function contact(): void
    {
        $this->render('pages/contact', [
            'title' => 'Contact',
            'active_nav' => 'contact',
            'meta_description' => 'Contact DOTS Event Planning in Saint John—email, phone, and map. Send a message for rentals, weddings, or corporate event planning.',
        ]);
    }

}
