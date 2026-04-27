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
        ]);
    }

    public function about(): void
    {
        $this->render('pages/about', [
            'title' => 'About us',
            'active_nav' => 'about',
        ]);
    }

    public function services(): void
    {
        $this->render('pages/services', [
            'title' => 'Services',
            'active_nav' => 'services',
        ]);
    }

    public function kidsParty(): void
    {
        $this->render('pages/kids-party', [
            'title' => 'Kids party packages',
            'active_nav' => 'kids-party',
        ]);
    }

    public function rentals(): void
    {
        $this->render('pages/rentals', [
            'title' => 'Rentals',
            'active_nav' => 'rentals',
        ]);
    }

    public function portfolio(): void
    {
        $this->render('pages/portfolio', [
            'title' => 'Portfolio',
            'active_nav' => 'portfolio',
        ]);
    }

    public function bookYourEvent(): void
    {
        $this->render('pages/book-your-event', [
            'title' => 'Book your event',
            'active_nav' => 'book-your-event',
        ]);
    }

    public function contact(): void
    {
        $this->render('pages/contact', [
            'title' => 'Contact',
            'active_nav' => 'contact',
        ]);
    }
}
