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
        $cms = cms_public_page(
            'about',
            'About us',
            'About DOTS Event Planning in Saint John, NB—our story, what we value, and how we run weddings, corporate events, and celebrations with on-site hustle.'
        );
        $this->render('pages/about', [
            'title' => $cms['doc_title'],
            'active_nav' => 'about',
            'meta_description' => $cms['meta_description'],
            'cms' => $cms,
        ]);
    }

    public function services(): void
    {
        $cms = cms_public_page(
            'services',
            'Services',
            'Event planning for corporate, weddings, kids parties, and rentals in Saint John and the region—timelines, vendors, and production you can trust.'
        );
        $this->render('pages/services', [
            'title' => $cms['doc_title'],
            'active_nav' => 'services',
            'meta_description' => $cms['meta_description'],
            'cms' => $cms,
        ]);
    }

    public function kidsParty(): void
    {
        $cms = cms_public_page(
            'kids-party',
            'Kids party packages',
            'Kids and family party packages in Saint John—themed decor, games, and teardown. Book by inquiry; we confirm details before you pay for packages.'
        );
        $this->render('pages/kids-party', [
            'title' => $cms['doc_title'],
            'active_nav' => 'kids',
            'meta_description' => $cms['meta_description'],
            'cms' => $cms,
        ]);
    }

    public function portfolio(): void
    {
        $cms = cms_public_page(
            'portfolio',
            'Portfolio',
            'Case highlights from DOTS—weddings, corporate nights, and family celebrations we’ve planned in New Brunswick, with a focus on flow and real outcomes.'
        );
        $this->render('pages/portfolio', [
            'title' => $cms['doc_title'],
            'active_nav' => 'portfolio',
            'meta_description' => $cms['meta_description'],
            'cms' => $cms,
        ]);
    }

    public function bookYourEvent(): void
    {
        $raw = (string) ($_GET['package'] ?? '');
        $allowed = ['basic', 'premium', 'vip', 'not_sure'];
        $preselect = in_array($raw, $allowed, true) ? $raw : null;
        $cms = cms_public_page(
            'book',
            'Book',
            'Request a booking for your event—package tiers from basic to VIP. We follow up to confirm; event packages are not paid on this form.'
        );
        $this->render('pages/book-your-event', [
            'title' => $cms['doc_title'],
            'active_nav' => 'book',
            'preselect_package' => $preselect,
            'meta_description' => $cms['meta_description'],
            'cms' => $cms,
        ]);
    }

    public function contact(): void
    {
        $cms = cms_public_page(
            'contact',
            'Contact us',
            'Contact DOTS Event Planning in Saint John—email, phone, and map. Send a message for rentals, weddings, or corporate event planning.'
        );
        $this->render('pages/contact', [
            'title' => $cms['doc_title'],
            'active_nav' => 'contact',
            'meta_description' => $cms['meta_description'],
            'cms' => $cms,
        ]);
    }

}
