<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\AdminAuth;
use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Flash;
use App\Core\Database;
use App\Models\LeadRepository;
use App\Models\OrderRepository;
use App\Models\ProductRepository;
use App\Models\UserRepository;

final class AdminController extends Controller
{
    /**
     * @param list<string> $segs
     */
    public function route(string $method, array $segs): void
    {
        $n = count($segs);
        $second = $segs[1] ?? '';

        if ($method === 'POST' && $second === 'login' && $n === 2) {
            $this->loginPost();
            return;
        }
        if ($method === 'GET' && $second === 'logout' && $n === 2) {
            $this->logout();
            return;
        }
        if ($method === 'POST' && $second === 'product' && ($segs[2] ?? '') === 'save' && $n === 3) {
            $this->requireAuth();
            $this->productSave();
            return;
        }
        if ($method === 'POST' && $second === 'product' && ($segs[2] ?? '') === 'delete' && $n === 3) {
            $this->requireAuth();
            $this->productDelete();
            return;
        }

        if ($method === 'GET' && $second === 'login' && $n === 2) {
            $this->loginForm();
            return;
        }
        if ($method === 'GET' && ($second === '' || $second === 'dashboard') && $n <= 2) {
            $this->requireAuth();
            $this->dashboard();
            return;
        }
        if ($method === 'GET' && $second === 'products' && $n === 2) {
            $this->requireAuth();
            $this->products();
            return;
        }
        if ($method === 'GET' && $second === 'product' && ($segs[2] ?? '') === 'new' && $n === 3) {
            $this->requireAuth();
            $this->productForm(null);
            return;
        }
        if ($method === 'GET' && $second === 'product' && isset($segs[2], $segs[3]) && ctype_digit((string) $segs[2]) && $segs[3] === 'edit' && $n === 4) {
            $this->requireAuth();
            $this->productForm((int) $segs[2]);
            return;
        }
        if ($method === 'GET' && $second === 'leads' && $n === 2) {
            $this->requireAuth();
            $this->leads();
            return;
        }
        if ($method === 'GET' && $second === 'orders' && $n === 2) {
            $this->requireAuth();
            $this->orders();
            return;
        }
        if ($method === 'GET' && $second === 'order' && isset($segs[2]) && ctype_digit((string) $segs[2]) && $n === 3) {
            $this->requireAuth();
            $this->orderShow((int) $segs[2]);
            return;
        }

        http_response_code(404);
        (new PageController())->notFound();
    }

    public function loginForm(): void
    {
        if (AdminAuth::id() !== null) {
            $this->redirect('/admin/dashboard');
        }
        if (Database::getInstance() === null) {
            Flash::set(Flash::NOTICE, 'Connect the database (config/database.php) to use the admin area.');
        }
        $this->render('admin/login', [
            'title' => 'Admin sign in',
            'active_nav' => '',
            'body_class' => 'page-admin',
            'layout' => 'layouts/admin',
            'admin_authed' => false,
        ]);
    }

    public function loginPost(): void
    {
        if (!Csrf::validate($_POST['_csrf'] ?? null)) {
            Flash::set(Flash::ERROR, 'Invalid session. Try again.');
            $this->redirect('/admin/login');
        }
        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
            Flash::set(Flash::ERROR, 'Enter a valid email and password.');
            $this->redirect('/admin/login');
        }
        $users = new UserRepository();
        $u = $users->findByEmail($email);
        if ($u === null || !password_verify($password, (string) ($u['password_hash'] ?? ''))) {
            Flash::set(Flash::ERROR, 'Sign in failed.');
            $this->redirect('/admin/login');
        }
        AdminAuth::login((int) $u['id']);
        Flash::set(Flash::SUCCESS, 'Welcome back.');
        $this->redirect('/admin/dashboard');
    }

    public function logout(): void
    {
        AdminAuth::logout();
        Flash::set(Flash::NOTICE, 'You are signed out.');
        $this->redirect('/admin/login');
    }

    public function dashboard(): void
    {
        $this->assertDb();
        $leads = new LeadRepository();
        $orders = new OrderRepository();
        $products = new ProductRepository();
        $uRepo = new UserRepository();
        $uid = AdminAuth::id();
        if ($uid === null) {
            $this->redirect('/admin/login');
        }
        $u = $uRepo->findById($uid);
        if ($u === null) {
            AdminAuth::logout();
            Flash::set(Flash::ERROR, 'Session expired. Sign in again.');
            $this->redirect('/admin/login');
        }
        $this->render('admin/dashboard', [
            'title' => 'Admin',
            'active_nav' => '',
            'body_class' => 'page-admin',
            'layout' => 'layouts/admin',
            'admin_authed' => true,
            'user_email' => (string) ($u['email'] ?? ''),
            'lead_count' => $leads->countAll(),
            'order_count' => $orders->countAll(),
            'product_count' => count($products->allForAdmin()),
        ]);
    }

    public function products(): void
    {
        $this->assertDb();
        $repo = new ProductRepository();
        $this->render('admin/products', [
            'title' => 'Products',
            'active_nav' => '',
            'body_class' => 'page-admin',
            'layout' => 'layouts/admin',
            'admin_authed' => true,
            'products' => $repo->allForAdmin(),
        ]);
    }

    public function productForm(?int $id): void
    {
        $this->assertDb();
        $repo = new ProductRepository();
        $p = $id === null ? null : $repo->findAny($id);
        if ($id !== null && $p === null) {
            http_response_code(404);
            (new PageController())->notFound();
            return;
        }
        $this->render('admin/product-form', [
            'title' => $p === null ? 'New product' : 'Edit product',
            'active_nav' => '',
            'body_class' => 'page-admin',
            'layout' => 'layouts/admin',
            'admin_authed' => true,
            'p' => $p,
        ]);
    }

    public function productSave(): void
    {
        if (!Csrf::validate($_POST['_csrf'] ?? null)) {
            Flash::set(Flash::ERROR, 'Invalid session.');
            $this->redirect('/admin/products');
        }
        $this->assertDb();
        $repo = new ProductRepository();
        $id = (int) ($_POST['id'] ?? 0);
        $name = trim((string) ($_POST['name'] ?? ''));
        $slugIn = trim((string) ($_POST['slug'] ?? ''));
        $description = trim((string) ($_POST['description'] ?? ''));
        $description = $description === '' ? null : $description;
        $priceCents = $this->parseMoneyToCents((string) ($_POST['price'] ?? ''));
        $currency = strtoupper(substr((string) ($_POST['currency'] ?? 'CAD'), 0, 3)) ?: 'CAD';
        $imageUrl = trim((string) ($_POST['image_url'] ?? ''));
        $imageUrl = $imageUrl === '' ? null : $imageUrl;
        $stockRaw = trim((string) ($_POST['stock'] ?? ''));
        $stock = $stockRaw === '' ? null : (int) $stockRaw;
        if ($name === '') {
            Flash::set(Flash::ERROR, 'Name is required.');
            $this->redirect($id > 0 ? '/admin/product/' . $id . '/edit' : '/admin/product/new');
        }
        if ($priceCents === null || $priceCents < 0) {
            Flash::set(Flash::ERROR, 'Valid price is required (e.g. 12.99).');
            $this->redirect($id > 0 ? '/admin/product/' . $id . '/edit' : '/admin/product/new');
        }
        $slug = $slugIn !== '' ? slugify($slugIn) : slugify($name);
        $hasOpts = (int) !empty($_POST['has_options']);
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        $sort = max(0, (int) ($_POST['sort_order'] ?? 0));
        if ($id > 0) {
            if ($repo->slugExists($slug, $id)) {
                $slug = $this->makeUniqueSlug($repo, $slug, $id);
            }
            $ok = $repo->update($id, [
                'slug' => $slug,
                'name' => $name,
                'description' => $description,
                'price_cents' => $priceCents,
                'currency' => $currency,
                'image_url' => $imageUrl,
                'stock' => $stock,
                'has_options' => $hasOpts,
                'is_active' => $isActive,
                'sort_order' => $sort,
            ]);
            Flash::set($ok ? Flash::SUCCESS : Flash::ERROR, $ok ? 'Product updated.' : 'Update failed.');
            $this->redirect('/admin/products');
            return;
        }
        if ($repo->slugExists($slug, null)) {
            $slug = $this->makeUniqueSlug($repo, $slug, null);
        }
        $newId = $repo->create([
            'slug' => $slug,
            'name' => $name,
            'description' => $description,
            'price_cents' => $priceCents,
            'currency' => $currency,
            'image_url' => $imageUrl,
            'stock' => $stock,
            'has_options' => $hasOpts,
            'is_active' => $isActive,
            'sort_order' => $sort,
        ]);
        if ($newId > 0) {
            Flash::set(Flash::SUCCESS, 'Product created.');
        } else {
            Flash::set(Flash::ERROR, 'Could not create product.');
        }
        $this->redirect('/admin/products');
    }

    public function productDelete(): void
    {
        if (!Csrf::validate($_POST['_csrf'] ?? null)) {
            Flash::set(Flash::ERROR, 'Invalid session.');
            $this->redirect('/admin/products');
        }
        $this->assertDb();
        $id = (int) ($_POST['product_id'] ?? 0);
        if ($id < 1) {
            $this->redirect('/admin/products');
        }
        $repo = new ProductRepository();
        $p = $repo->findAny($id);
        if ($p === null) {
            Flash::set(Flash::ERROR, 'Product not found.');
            $this->redirect('/admin/products');
        }
        $payload = $this->rowToSavePayload($p);
        $payload['is_active'] = 0;
        $repo->update($id, $payload);
        Flash::set(Flash::SUCCESS, 'Product hidden from the shop. You can re-enable it by editing the product.');
        $this->redirect('/admin/products');
    }

    public function leads(): void
    {
        $this->assertDb();
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $per = 30;
        $off = ($page - 1) * $per;
        $repo = new LeadRepository();
        $total = $repo->countAll();
        $rows = $repo->listAll($per, $off);
        $this->render('admin/leads', [
            'title' => 'Leads',
            'active_nav' => '',
            'body_class' => 'page-admin',
            'layout' => 'layouts/admin',
            'admin_authed' => true,
            'leads' => $rows,
            'page' => $page,
            'per_page' => $per,
            'total' => $total,
            'pages' => (int) ceil($total / $per) ?: 1,
        ]);
    }

    public function orders(): void
    {
        $this->assertDb();
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $per = 20;
        $off = ($page - 1) * $per;
        $repo = new OrderRepository();
        $total = $repo->countAll();
        $rows = $repo->listAll($per, $off);
        $this->render('admin/orders', [
            'title' => 'Orders',
            'active_nav' => '',
            'body_class' => 'page-admin',
            'layout' => 'layouts/admin',
            'admin_authed' => true,
            'orders' => $rows,
            'page' => $page,
            'per_page' => $per,
            'total' => $total,
            'pages' => (int) ceil($total / $per) ?: 1,
        ]);
    }

    public function orderShow(int $id): void
    {
        $this->assertDb();
        $repo = new OrderRepository();
        $data = $repo->findWithItems($id);
        if ($data === null) {
            http_response_code(404);
            (new PageController())->notFound();
            return;
        }
        $this->render('admin/order', [
            'title' => 'Order #' . $id,
            'active_nav' => '',
            'body_class' => 'page-admin',
            'layout' => 'layouts/admin',
            'admin_authed' => true,
            'o' => $data['order'],
            'items' => $data['items'],
        ]);
    }

    private function requireAuth(): void
    {
        if (Database::getInstance() === null) {
            Flash::set(Flash::ERROR, 'Database is not connected.');
            $this->redirect('/admin/login');
        }
        if (AdminAuth::id() === null) {
            Flash::set(Flash::NOTICE, 'Sign in to continue.');
            $this->redirect('/admin/login');
        }
    }

    private function assertDb(): void
    {
        if (Database::getInstance() === null) {
            Flash::set(Flash::ERROR, 'Database is not connected.');
            $this->redirect('/admin/login');
        }
    }

    private function parseMoneyToCents(string $s): ?int
    {
        $s = trim($s);
        if ($s === '') {
            return null;
        }
        if (!preg_match('/^\d+(\.\d{0,2})?$/', $s)) {
            return null;
        }
        return (int) round((float) $s * 100);
    }

    /**
     * @param array<string, mixed> $p
     * @return array{slug: string, name: string, description: ?string, price_cents: int, currency: string, image_url: ?string, stock: ?int, has_options: int, is_active: int, sort_order: int}
     */
    private function rowToSavePayload(array $p): array
    {
        $stock = $p['stock'] ?? null;
        if ($stock === '' || $stock === null) {
            $stockVal = null;
        } else {
            $stockVal = (int) $stock;
        }
        return [
            'slug' => (string) $p['slug'],
            'name' => (string) $p['name'],
            'description' => isset($p['description']) && (string) $p['description'] !== '' ? (string) $p['description'] : null,
            'price_cents' => (int) $p['price_cents'],
            'currency' => (string) ($p['currency'] ?? 'CAD'),
            'image_url' => isset($p['image_url']) && $p['image_url'] !== '' && $p['image_url'] !== null ? (string) $p['image_url'] : null,
            'stock' => $stockVal,
            'has_options' => (int) ($p['has_options'] ?? 0),
            'is_active' => (int) ($p['is_active'] ?? 1),
            'sort_order' => (int) ($p['sort_order'] ?? 0),
        ];
    }

    private function makeUniqueSlug(ProductRepository $repo, string $base, ?int $exceptId): string
    {
        $s = $base;
        $i = 2;
        while ($repo->slugExists($s, $exceptId)) {
            $s = $base . '-' . $i;
            $i++;
        }
        return $s;
    }
}
