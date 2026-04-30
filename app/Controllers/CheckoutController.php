<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Cart;
use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Flash;
use App\Models\OrderRepository;
use App\Models\ProductRepository;
use App\Services\SquarePaymentService;
use RuntimeException;
use Throwable;

final class CheckoutController extends Controller
{
    public function index(): void
    {
        $repo = new ProductRepository();
        $subtotal = 0;
        $currency = (string) (app_config()['currency'] ?? 'CAD');
        $lines = [];
        foreach (Cart::items() as $pid => $qty) {
            $p = $repo->find((int) $pid);
            if ($p === null) {
                Cart::remove((int) $pid);
                continue;
            }
            $c = (int) $p['price_cents'];
            $currency = (string) ($p['currency'] ?? $currency);
            $lines[] = array_merge($p, ['quantity' => $qty, 'line_total_cents' => $c * $qty]);
            $subtotal += $c * $qty;
        }
        if ($lines === []) {
            Flash::set(Flash::NOTICE, 'Your cart is empty.');
            $this->redirect('/rentals');
        }
        $square = square_config();
        $extraFooter = '';
        if (is_array($square) && ($square['application_id'] ?? '') !== '' && ($square['location_id'] ?? '') !== '') {
            $extraFooter = '<script src="' . e(asset('js/checkout.js')) . '" defer></script>';
        }
        $this->render('checkout/index', [
            'title' => 'Checkout',
            'active_nav' => 'cart',
            'body_class' => 'page-checkout',
            'lines' => $lines,
            'subtotal_cents' => $subtotal,
            'currency' => $currency,
            'square' => $square,
            'extra_header' => $this->squareScriptTag($square),
            'extra_footer' => $extraFooter,
            'meta_description' => 'Complete your event rental order with DOTS—secure checkout, contact details, and payment. Serving Saint John and the region.',
        ]);
    }

    public function pay(): void
    {
        if (strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? '')) !== 'POST' || !Csrf::validate($_POST['_csrf'] ?? null)) {
            action_log('checkout', 'pay.rejected', ['reason' => 'csrf_or_method']);
            Flash::set(Flash::ERROR, 'Invalid session. Please try again.');
            $this->redirect('/checkout');
        }
        $sourceId = trim((string) ($_POST['source_id'] ?? ''));
        if ($sourceId === '') {
            action_log('checkout', 'pay.rejected', ['reason' => 'no_payment_source']);
            Flash::set(Flash::ERROR, 'No payment method was provided.');
            $this->redirect('/checkout');
        }
        $email = trim((string) ($_POST['email'] ?? ''));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            action_log('checkout', 'pay.rejected', ['reason' => 'invalid_email']);
            Flash::set(Flash::ERROR, 'Please enter a valid email.');
            $this->redirect('/checkout');
        }
        $name = trim((string) ($_POST['name'] ?? ''));
        $phone = trim((string) ($_POST['phone'] ?? ''));
        $repo = new ProductRepository();
        $orderRepo = new OrderRepository();
        $subtotal = 0;
        $currency = (string) (app_config()['currency'] ?? 'CAD');
        $lineRows = [];
        foreach (Cart::items() as $pid => $qty) {
            $p = $repo->find((int) $pid);
            if ($p === null) {
                continue;
            }
            $c = (int) $p['price_cents'];
            $currency = (string) ($p['currency'] ?? $currency);
            $lineRows[] = [
                'product_id' => (int) $p['id'],
                'quantity' => $qty,
                'unit_price_cents' => $c,
            ];
            $subtotal += $c * $qty;
        }
        if ($lineRows === [] || $subtotal < 1) {
            action_log('checkout', 'pay.rejected', ['reason' => 'empty_cart']);
            Flash::set(Flash::ERROR, 'Your cart is empty.');
            $this->redirect('/rentals');
        }
        $idem = bin2hex(random_bytes(16));
        try {
            $service = new SquarePaymentService();
            $result = $service->createPayment(
                $sourceId,
                $subtotal,
                $currency,
                $idem,
                $email,
                'DOTS rentals order'
            );
            $payment = $result['payment'];
            $pid = (string) ($payment['id'] ?? '');
            if ($pid === '') {
                throw new RuntimeException('No payment id returned.');
            }
            $orderId = $orderRepo->createPaid(
                $subtotal,
                $currency,
                $pid,
                $idem,
                $email,
                $name !== '' ? $name : null,
                $phone !== '' ? $phone : null,
                $lineRows
            );
            $_SESSION['last_order_id'] = $orderId;
            action_log('checkout', 'order.paid', [
                'order_id' => $orderId,
                'total_cents' => $subtotal,
                'currency' => $currency,
            ]);
        } catch (Throwable $e) {
            action_log('checkout', 'pay.exception', [
                'exc' => $e::class,
            ]);
            $msg = app_config()['debug'] ?? false
                ? 'Payment could not be completed: ' . $e->getMessage()
                : 'Payment could not be completed. Please try again or use another card.';
            Flash::set(Flash::ERROR, $msg);
            $this->redirect('/checkout');
        }
        Cart::clear();
        Flash::set(Flash::SUCCESS, 'Thank you! Your order was received.');
        $this->redirect('/order/success');
    }

    public function success(): void
    {
        $message = Flash::get(Flash::SUCCESS);
        if ($message === null) {
            $this->redirect('/rentals');
        }
        $orderId = null;
        if (isset($_SESSION['last_order_id'])) {
            $orderId = (int) $_SESSION['last_order_id'];
            if ($orderId < 1) {
                $orderId = null;
            }
            unset($_SESSION['last_order_id']);
        }
        $this->render('checkout/success', [
            'title' => 'Order complete',
            'active_nav' => 'cart',
            'body_class' => 'page-success',
            'order_message' => $message,
            'order_id' => $orderId,
            'meta_description' => 'Thank you—your DOTS Event Planning rental order was received. We will follow up with next steps for pickup or delivery in Saint John, NB.',
        ]);
    }

    /**
     * @param array<string, mixed>|null $square
     */
    private function squareScriptTag(?array $square): string
    {
        if ($square === null) {
            return '';
        }
        $env = (string) ($square['environment'] ?? 'sandbox');
        $src = $env === 'production'
            ? 'https://web.squarecdn.com/v1/square.js'
            : 'https://sandbox.web.squarecdn.com/v1/square.js';
        return '<script src="' . e($src) . '"></script>';
    }
}
