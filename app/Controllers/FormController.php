<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Flash;
use App\Models\LeadRepository;

final class FormController extends Controller
{
    public function contactSubmit(): void
    {
        if (!$this->isPost() || !Csrf::validate($_POST['_csrf'] ?? null)) {
            action_log('forms', 'contact.rejected', ['reason' => 'method_or_csrf']);
            Flash::set(Flash::ERROR, 'Invalid session. Please reload the page.');
            $this->redirect('/contact');
        }
        $name = trim((string) ($_POST['name'] ?? ''));
        $email = trim((string) ($_POST['email'] ?? ''));
        $phone = trim((string) ($_POST['phone'] ?? ''));
        $subject = trim((string) ($_POST['subject'] ?? ''));
        $message = trim((string) ($_POST['message'] ?? ''));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Flash::set(Flash::ERROR, 'Please enter a valid email.');
            $this->redirect('/contact');
        }
        $repo = new LeadRepository();
        if ($repo->create('contact', $email, $name !== '' ? $name : null, $phone !== '' ? $phone : null, $message !== '' ? $message : null, $subject !== '' ? $subject : null)) {
            action_log('forms', 'lead.created', ['type' => 'contact']);
            Flash::set(Flash::SUCCESS, 'Thanks — we will get back to you shortly.');
        } else {
            action_log('forms', 'lead.persist_failed', ['type' => 'contact']);
            Flash::set(Flash::ERROR, 'Your message could not be saved. Please try again or email us directly.');
        }
        $this->redirect('/contact');
    }

    public function newsletterSubmit(): void
    {
        $returnUrl = $this->newsletterReturnUrl();

        if (!$this->isPost() || !Csrf::validate($_POST['_csrf'] ?? null)) {
            action_log('forms', 'newsletter.rejected', ['reason' => 'method_or_csrf']);
            Flash::set(Flash::NEWSLETTER_ERROR, 'Something went wrong. Please try again.');
            $this->redirectAbsolute($returnUrl);
        }
        $email = trim((string) ($_POST['email'] ?? ''));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Flash::set(Flash::NEWSLETTER_ERROR, 'Please enter a valid email.');
            $this->redirectAbsolute($returnUrl);
        }
        $repo = new LeadRepository();
        if ($repo->create('newsletter', $email, null, null, null, null)) {
            action_log('forms', 'lead.created', ['type' => 'newsletter']);
            Flash::set(Flash::NEWSLETTER_SUCCESS, 'You are on the list.');
        } else {
            action_log('forms', 'lead.persist_failed', ['type' => 'newsletter']);
            Flash::set(Flash::NEWSLETTER_ERROR, 'Could not subscribe right now. Please try again later.');
        }
        $this->redirectAbsolute($returnUrl);
    }

    /**
     * Same-origin path only (slug allowlist + optional fragment to the newsletter band).
     */
    private function newsletterReturnUrl(): string
    {
        $slug = strtolower(trim((string) ($_POST['_newsletter_return'] ?? '')));
        $allowed = ['services', 'contact', 'about', 'portfolio'];
        $fragments = [
            'services' => 'services-nw-heading',
            'contact' => 'contact-news-title',
            'about' => 'about-nw-heading',
            'portfolio' => 'portfolio-news-title',
        ];
        if ($slug === 'home') {
            return app_url('') . '#home-newsletter-heading';
        }
        if ($slug === '') {
            return app_url('');
        }
        if (!in_array($slug, $allowed, true)) {
            return app_url('');
        }

        return app_url($slug) . '#' . $fragments[$slug];
    }

    private function redirectAbsolute(string $url): void
    {
        header('Location: ' . $url, true, 302);
        exit;
    }

    public function bookYourEventSubmit(): void
    {
        if (!$this->isPost() || !Csrf::validate($_POST['_csrf'] ?? null)) {
            action_log('forms', 'booking.rejected', ['reason' => 'method_or_csrf']);
            Flash::set(Flash::ERROR, 'Invalid session. Please reload the page.');
            $this->redirect('book');
        }
        $name = trim((string) ($_POST['name'] ?? ''));
        $email = trim((string) ($_POST['email'] ?? ''));
        $phone = trim((string) ($_POST['phone'] ?? ''));
        $package = trim((string) ($_POST['package'] ?? ''));
        $eventDate = trim((string) ($_POST['event_date'] ?? ''));
        $guests = trim((string) ($_POST['guest_count'] ?? ''));
        $venue = trim((string) ($_POST['venue_city'] ?? ''));
        $message = trim((string) ($_POST['message'] ?? ''));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Flash::set(Flash::ERROR, 'Please enter a valid email.');
            $this->redirect('book');
        }
        $allowedP = ['basic', 'premium', 'vip', 'not_sure'];
        if (!in_array($package, $allowedP, true)) {
            $package = 'not_sure';
        }
        $msg = $message;
        if ($msg === '' && $eventDate . $venue . $guests !== '') {
            $msg = 'See structured fields in extra.';
        }
        $repo = new LeadRepository();
        if ($repo->create(
            'booking',
            $email,
            $name !== '' ? $name : null,
            $phone !== '' ? $phone : null,
            $msg !== '' ? $msg : null,
            null,
            $package,
            $eventDate !== '' ? $eventDate : null,
            $guests !== '' ? $guests : null,
            $venue !== '' ? $venue : null
        )) {
            action_log('forms', 'lead.created', ['type' => 'booking']);
            Flash::set(Flash::SUCCESS, 'Thanks — we have your request and will be in touch.');
        } else {
            action_log('forms', 'lead.persist_failed', ['type' => 'booking']);
            Flash::set(Flash::ERROR, 'Could not save your request. Please try again or email us.');
        }
        $this->redirect('book');
    }

    private function isPost(): bool
    {
        return strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? '')) === 'POST';
    }
}
