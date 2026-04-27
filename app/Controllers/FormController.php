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
        $extra = json_encode(['subject' => $subject], JSON_THROW_ON_ERROR);
        $repo = new LeadRepository();
        if ($repo->create('contact', $email, $name !== '' ? $name : null, $phone !== '' ? $phone : null, $message !== '' ? $message : null, $extra)) {
            Flash::set(Flash::SUCCESS, 'Thanks — we will get back to you shortly.');
        } else {
            Flash::set(Flash::ERROR, 'Your message could not be saved. Please try again or email us directly.');
        }
        $this->redirect('/contact');
    }

    public function newsletterSubmit(): void
    {
        if (!$this->isPost() || !Csrf::validate($_POST['_csrf'] ?? null)) {
            Flash::set(Flash::ERROR, 'Something went wrong. Please try again.');
            $this->redirect('/');
        }
        $email = trim((string) ($_POST['email'] ?? ''));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Flash::set(Flash::ERROR, 'Please enter a valid email.');
            $this->redirect('/');
        }
        $repo = new LeadRepository();
        if ($repo->create('newsletter', $email, null, null, null, null)) {
            Flash::set(Flash::SUCCESS, 'You are on the list.');
        } else {
            Flash::set(Flash::ERROR, 'Could not subscribe right now. Please try again later.');
        }
        $this->redirect('/');
    }

    private function isPost(): bool
    {
        return strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? '')) === 'POST';
    }
}
