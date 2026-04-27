<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Cart;
use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Flash;
use App\Models\ProductRepository;

final class CartController extends Controller
{
    public function index(): void
    {
        $lines = $this->resolveLines();
        $this->render('cart/index', [
            'title' => 'Cart',
            'active_nav' => 'cart',
            'body_class' => 'page-cart',
            'lines' => $lines['lines'],
            'subtotal_cents' => $lines['subtotal_cents'],
            'currency' => $lines['currency'] ?? app_config()['currency'] ?? 'CAD',
            'meta_description' => 'Your rental cart at DOTS Event Planning. Review quantities, then go to secure checkout for event decor in Saint John, NB.',
        ]);
    }

    public function add(): void
    {
        if (!$this->isPost() || !Csrf::validate($_POST['_csrf'] ?? null)) {
            $this->redirect('/rentals');
        }
        $pid = (int) ($_POST['product_id'] ?? 0);
        $qty = max(1, (int) ($_POST['quantity'] ?? 1));
        $repo = new ProductRepository();
        $p = $repo->find($pid);
        if ($p === null) {
            Flash::set(Flash::ERROR, 'This item is not available.');
            $this->redirect('/rentals');
        }
        Cart::add($pid, $qty);
        Flash::set(Flash::SUCCESS, 'Added to cart.');
        $return = (string) ($_POST['return'] ?? '/rentals');
        $this->redirect(allowed_return($return));
    }

    public function update(): void
    {
        if (!$this->isPost() || !Csrf::validate($_POST['_csrf'] ?? null)) {
            $this->redirect('/cart');
        }
        $pid = (int) ($_POST['product_id'] ?? 0);
        $qty = (int) ($_POST['quantity'] ?? 0);
        Cart::set($pid, $qty);
        $this->redirect('/cart');
    }

    public function remove(): void
    {
        if (!$this->isPost() || !Csrf::validate($_POST['_csrf'] ?? null)) {
            $this->redirect('/cart');
        }
        $pid = (int) ($_POST['product_id'] ?? 0);
        Cart::remove($pid);
        $this->redirect('/cart');
    }

    private function isPost(): bool
    {
        return strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? '')) === 'POST';
    }

    /**
     * @return array{lines: list<array<string, mixed>>, subtotal_cents: int, currency: string}
     */
    private function resolveLines(): array
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
            $lineTotal = $c * $qty;
            $subtotal += $lineTotal;
            $p['quantity'] = $qty;
            $p['line_total_cents'] = $lineTotal;
            $lines[] = $p;
        }
        return ['lines' => $lines, 'subtotal_cents' => $subtotal, 'currency' => $currency];
    }
}
