<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\AdminAuth;
use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Flash;
use App\Core\Database;
use App\Models\CmsMediaRepository;
use App\Models\CmsPagesRepository;
use App\Models\CmsSettingsRepository;
use App\Models\CmsSlidesRepository;
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
        action_log('admin', 'route', [
            'method' => $method,
            'path' => implode('/', array_slice($segs, 0, 20)),
        ]);
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
        if ($method === 'GET' && $second === 'analytics' && $n === 2) {
            $this->requireAuth();
            $this->analytics();
            return;
        }
        if ($method === 'GET' && $second === 'cms' && ($segs[2] ?? '') === 'pages' && $n === 3) {
            $this->requireAuth();
            $this->cmsPagesHub();
            return;
        }
        if ($method === 'GET' && $second === 'cms' && $n === 2) {
            $this->requireAuth();
            $this->cmsHome();
            return;
        }
        if ($method === 'POST' && $second === 'cms' && ($segs[2] ?? '') === 'settings' && $n === 3) {
            $this->requireAuth();
            $this->cmsSettingsSave();
            return;
        }
        if ($method === 'GET' && $second === 'cms' && ($segs[2] ?? '') === 'page' && isset($segs[3]) && $n === 4) {
            $this->requireAuth();
            $this->cmsPageForm((string) $segs[3]);
            return;
        }
        if ($method === 'POST' && $second === 'cms' && ($segs[2] ?? '') === 'page' && isset($segs[3]) && ($segs[4] ?? '') === 'save' && $n === 5) {
            $this->requireAuth();
            $this->cmsPageSave((string) $segs[3]);
            return;
        }
        if ($method === 'GET' && $second === 'cms' && ($segs[2] ?? '') === 'slides' && $n === 3) {
            $this->requireAuth();
            $this->cmsSlidesList();
            return;
        }
        if ($method === 'GET' && $second === 'cms' && ($segs[2] ?? '') === 'slide' && ($segs[3] ?? '') === 'new' && $n === 4) {
            $this->requireAuth();
            $this->cmsSlideForm(null);
            return;
        }
        if ($method === 'GET' && $second === 'cms' && ($segs[2] ?? '') === 'slide' && isset($segs[3], $segs[4]) && ctype_digit((string) $segs[3]) && $segs[4] === 'edit' && $n === 5) {
            $this->requireAuth();
            $this->cmsSlideForm((int) $segs[3]);
            return;
        }
        if ($method === 'POST' && $second === 'cms' && ($segs[2] ?? '') === 'slide' && ($segs[3] ?? '') === 'save' && $n === 4) {
            $this->requireAuth();
            $this->cmsSlideSave();
            return;
        }
        if ($method === 'POST' && $second === 'cms' && ($segs[2] ?? '') === 'slide' && isset($segs[3], $segs[4]) && ctype_digit((string) $segs[3]) && $segs[4] === 'delete' && $n === 5) {
            $this->requireAuth();
            $this->cmsSlideDelete((int) $segs[3]);
            return;
        }
        if ($method === 'POST' && $second === 'cms' && ($segs[2] ?? '') === 'slide' && isset($segs[3], $segs[4]) && ctype_digit((string) $segs[3]) && $segs[4] === 'move-up' && $n === 5) {
            $this->requireAuth();
            $this->cmsSlideReorder((int) $segs[3], 'up');
            return;
        }
        if ($method === 'POST' && $second === 'cms' && ($segs[2] ?? '') === 'slide' && isset($segs[3], $segs[4]) && ctype_digit((string) $segs[3]) && $segs[4] === 'move-down' && $n === 5) {
            $this->requireAuth();
            $this->cmsSlideReorder((int) $segs[3], 'down');
            return;
        }
        if ($method === 'POST' && $second === 'media' && ($segs[2] ?? '') === 'upload' && $n === 3) {
            $this->requireAuth();
            $this->mediaUpload();
            return;
        }
        if ($method === 'GET' && $second === 'profile' && $n === 2) {
            $this->requireAuth();
            $this->profileForm();
            return;
        }
        if ($method === 'POST' && $second === 'profile' && ($segs[2] ?? '') === 'save' && $n === 3) {
            $this->requireAuth();
            $this->profileSave();
            return;
        }
        if ($method === 'GET' && $second === 'password' && $n === 2) {
            $this->requireAuth();
            $this->passwordForm();
            return;
        }
        if ($method === 'POST' && $second === 'password' && ($segs[2] ?? '') === 'save' && $n === 3) {
            $this->requireAuth();
            $this->passwordSave();
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
            'active_admin_nav' => '',
            'body_class' => '',
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
            'title' => 'Dashboard',
            'active_nav' => '',
            'active_admin_nav' => 'dashboard',
            'body_class' => '',
            'layout' => 'layouts/admin',
            'admin_authed' => true,
            'lead_count' => $leads->countAll(),
            'order_count' => $orders->countAll(),
            'product_count' => count($products->allForAdmin()),
        ]);
    }

    public function analytics(): void
    {
        $this->requireAuth();
        $orders = new OrderRepository();
        $leads = new LeadRepository();
        $now = new \DateTimeImmutable('now');
        $d30 = $now->modify('-30 days');
        $d7 = $now->modify('-7 days');
        $this->render('admin/analytics', [
            'title' => 'Analytics',
            'active_nav' => '',
            'active_admin_nav' => 'analytics',
            'body_class' => '',
            'layout' => 'layouts/admin',
            'admin_authed' => true,
            'revenue_cents_30d' => $orders->sumPaidRevenueCentsSince($d30),
            'revenue_cents_all' => $orders->sumPaidRevenueCents(),
            'orders_paid_30d' => $orders->countPaidSince($d30),
            'orders_paid_7d' => $orders->countPaidSince($d7),
            'orders_paid_all' => $orders->countPaid(),
            'leads_30d' => $leads->countSince($d30),
            'leads_7d' => $leads->countSince($d7),
            'leads_all' => $leads->countAll(),
            'orders_by_day' => $orders->paidOrdersPerDay(7),
            'leads_by_day' => $leads->leadsPerDay(7),
        ]);
    }

    public function cmsHome(): void
    {
        $this->requireAuth();
        $this->assertDb();
        $settings = (new CmsSettingsRepository())->all();
        $this->render('admin/cms', [
            'title' => 'Site settings',
            'active_nav' => '',
            'active_admin_nav' => 'cms-overview',
            'body_class' => 'admin-body--cms',
            'layout' => 'layouts/admin',
            'admin_authed' => true,
            'settings' => $settings,
        ]);
    }

    public function cmsPagesHub(): void
    {
        $this->requireAuth();
        $this->assertDb();
        $media = (new CmsMediaRepository())->listRecent(24);
        $this->render('admin/cms-pages-hub', [
            'title' => 'Pages & content',
            'active_nav' => '',
            'active_admin_nav' => 'cms-pages',
            'body_class' => 'admin-body--cms',
            'layout' => 'layouts/admin',
            'admin_authed' => true,
            'media' => $media,
        ]);
    }

    private function cmsSettingsSave(): void
    {
        if (!Csrf::validate($_POST['_csrf'] ?? null)) {
            Flash::set(Flash::ERROR, 'Invalid session.');
            $this->redirect('/admin/cms');
        }
        $this->assertDb();

        $logoUpload = $this->ingestBrandUpload($_FILES['logo_upload'] ?? null, 'brand-logo', 5 * 1024 * 1024, false, 'assets/images/logo');
        if ($logoUpload === false) {
            $this->redirect('/admin/cms');

            return;
        }
        $faviconUpload = $this->ingestBrandUpload($_FILES['favicon_upload'] ?? null, 'brand-favicon', 2 * 1024 * 1024, true, 'assets/images/favicon');
        if ($faviconUpload === false) {
            $this->redirect('/admin/cms');

            return;
        }

        $pairs = [
            'logo_path' => trim((string) ($_POST['logo_path'] ?? '')),
            'favicon_path' => trim((string) ($_POST['favicon_path'] ?? '')),
            'map_embed_url' => trim((string) ($_POST['map_embed_url'] ?? '')),
            'social_facebook' => trim((string) ($_POST['social_facebook'] ?? '')),
            'social_instagram' => trim((string) ($_POST['social_instagram'] ?? '')),
            'social_youtube' => trim((string) ($_POST['social_youtube'] ?? '')),
            'social_whatsapp' => trim((string) ($_POST['social_whatsapp'] ?? '')),
            'email' => trim((string) ($_POST['email'] ?? '')),
            'phone_display' => trim((string) ($_POST['phone_display'] ?? '')),
            'phone_tel' => trim((string) ($_POST['phone_tel'] ?? '')),
            'address_line1' => trim((string) ($_POST['address_line1'] ?? '')),
            'address_line2' => trim((string) ($_POST['address_line2'] ?? '')),
        ];

        if (is_string($logoUpload)) {
            $pairs['logo_path'] = $logoUpload;
        }
        if (is_string($faviconUpload)) {
            $pairs['favicon_path'] = $faviconUpload;
        }

        // allow empty = fallback; store empty string explicitly
        (new CmsSettingsRepository())->setMany($pairs);
        Flash::set(Flash::SUCCESS, 'Settings saved.');
        $this->redirect('/admin/cms');
    }

    /**
     * Store a brand image under /public/{publicRelativeDir}/. Registers row in cms_media.
     *
     * @param array<string, mixed>|null $file $_FILES[*] slice
     * @param string $publicRelativeDir path under /public/, e.g. uploads, assets/images/logo, assets/images/favicon
     * @param string|false|null string = public-relative path, null = skipped, false = error (Flash set)
     */
    private function ingestBrandUpload(?array $file, string $namePrefix, int $maxBytes, bool $allowIco, string $publicRelativeDir = 'uploads'): string|false|null
    {
        if ($file === null || ! isset($file['error'])) {
            return null;
        }
        $err = (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE);
        if ($err === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        if ($err !== UPLOAD_ERR_OK) {
            Flash::set(Flash::ERROR, 'File upload failed. Try a smaller image or a different format.');

            return false;
        }
        $tmp = (string) ($file['tmp_name'] ?? '');
        $size = (int) ($file['size'] ?? 0);
        $orig = (string) ($file['name'] ?? '');
        if ($tmp === '' || ! is_uploaded_file($tmp)) {
            Flash::set(Flash::ERROR, 'Invalid upload.');

            return false;
        }
        if ($size < 1 || $size > $maxBytes) {
            Flash::set(Flash::ERROR, 'File is too large (max ' . (int) floor($maxBytes / 1048576) . ' MB).');

            return false;
        }
        $fi = new \finfo(FILEINFO_MIME_TYPE);
        $mime = (string) $fi->file($tmp);

        $extFromName = strtolower((string) (pathinfo($orig, PATHINFO_EXTENSION)));

        /** @var array<string, string> */
        static $mimeToExt = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
        ];
        $ext = $mimeToExt[$mime] ?? '';
        if ($ext === '') {
            if (! $allowIco) {
                Flash::set(Flash::ERROR, 'Unsupported file type for logo. Use PNG, JPG, WEBP, or GIF.');

                return false;
            }
            $peek = @file_get_contents($tmp, false, null, 0, 8) ?: '';
            $icoMagic = strlen($peek) >= 4 && substr($peek, 0, 4) === "\x00\x00\x01\x00";
            $icoMimeOk = ($mime === 'image/x-icon' || $mime === 'image/vnd.microsoft.icon');
            $icoNameOk = ($extFromName === 'ico');
            $octetStreams = ($mime === 'application/octet-stream' || $mime === 'application/x-msdownload');
            if ($icoMimeOk || ($icoMagic && ($icoNameOk || $octetStreams))) {
                $mime = 'image/x-icon';
                $ext = 'ico';
            } elseif ($mime === 'image/svg+xml') {
                Flash::set(Flash::ERROR, 'SVG uploads are disabled for safety. Use PNG or ICO for favicon.');

                return false;
            } else {
                Flash::set(Flash::ERROR, 'Unsupported favicon type. Use PNG, JPG, WEBP, GIF, or ICO.');

                return false;
            }
        }

        $publicRelativeDir = trim(str_replace('\\', '/', $publicRelativeDir), '/');
        if ($publicRelativeDir === '' || str_contains($publicRelativeDir, '..')) {
            $publicRelativeDir = 'uploads';
        }

        $uploadDir = dirname(__DIR__, 2) . '/public/' . $publicRelativeDir;
        if (! is_dir($uploadDir)) {
            @mkdir($uploadDir, 0775, true);
        }
        $name = $namePrefix . '-' . date('Ymd-His') . '-' . bin2hex(random_bytes(3)) . '.' . $ext;
        $dest = $uploadDir . '/' . $name;
        if (! move_uploaded_file($tmp, $dest)) {
            Flash::set(Flash::ERROR, 'Could not save uploaded file.');

            return false;
        }
        $publicPath = $publicRelativeDir . '/' . $name;
        (new CmsMediaRepository())->create($publicPath, $mime, $size > 0 ? $size : (int) (@filesize($dest) ?: 0), $orig);

        return $publicPath;
    }

    private function cmsPageForm(string $slug): void
    {
        $this->assertDb();
        $slug = preg_replace('/[^a-z0-9\\-]/', '', strtolower($slug)) ?? '';
        if ($slug === '') {
            $this->redirect('/admin/cms');
        }
        $repo = new CmsPagesRepository();
        $row = $repo->findBySlug($slug);
        $content = $row !== null ? (string) ($row['content_json'] ?? '') : '';
        $title = $row !== null ? (string) ($row['title'] ?? '') : '';
        $this->render('admin/cms-page', [
            'title' => 'Edit: ' . $slug,
            'active_nav' => '',
            'active_admin_nav' => 'cms-pages',
            'body_class' => 'admin-body--cms',
            'layout' => 'layouts/admin',
            'admin_authed' => true,
            'slug' => $slug,
            'page_title' => $title,
            'content_json' => $content,
        ]);
    }

    private function cmsPageSave(string $slug): void
    {
        if (!Csrf::validate($_POST['_csrf'] ?? null)) {
            Flash::set(Flash::ERROR, 'Invalid session.');
            $this->redirect('/admin/cms');
        }
        $this->assertDb();
        $slug = preg_replace('/[^a-z0-9\\-]/', '', strtolower($slug)) ?? '';
        if ($slug === '') {
            $this->redirect('/admin/cms');
        }
        $title = trim((string) ($_POST['title'] ?? ''));
        $contentPayload = trim((string) ($_POST['content_json'] ?? ''));
        if ($contentPayload === '') {
            $contentPayload = '{}';
        }
        $incoming = json_decode($contentPayload, true);
        if (!is_array($incoming)) {
            Flash::set(Flash::ERROR, 'Content payload is invalid. Please try again.');
            $this->redirect('/admin/cms/page/' . $slug);
        }
        unset($incoming['_csrf']);

        if ($slug === 'services') {
            $svcBlocks = $incoming['blocks'] ?? null;
            if (!is_array($svcBlocks) || $svcBlocks === []) {
                Flash::set(
                    Flash::ERROR,
                    'Structured Services data was missing from this save. Refresh the editor page and try again.'
                );
                $this->redirect('/admin/cms/page/' . $slug);
            }
        }

        $repo = new CmsPagesRepository();
        $oldRow = $repo->findBySlug($slug);
        $old = [];
        if ($oldRow !== null) {
            $decoded = json_decode((string) ($oldRow['content_json'] ?? ''), true);
            if (is_array($decoded)) {
                $old = $decoded;
            }
        }
        $merged = array_merge($old, $incoming);

        /*
         * Prevent duplicate Services block trees at the JSON root (legacy merges + relational rows).
         * Only `blocks.*` should carry structured sections — root copies hide catalogue rows from readers.
         */
        if ($slug === 'services' && isset($merged['blocks']) && is_array($merged['blocks'])) {
            foreach (['hero', 'offerings', 'faq', 'newsletter_cta', 'version'] as $dupRootKey) {
                unset($merged[$dupRootKey]);
            }
        }

        $flags = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;
        if (\defined('JSON_INVALID_UTF8_SUBSTITUTE')) {
            $flags |= \JSON_INVALID_UTF8_SUBSTITUTE;
        }
        $encoded = json_encode($merged, $flags);
        if ($encoded === false) {
            error_log(
                'AdminController::cmsPageSave json_encode failed (slug=' . $slug . '): ' . json_last_error_msg()
            );
            Flash::set(Flash::ERROR, 'Could not save content. Try again.');
            $this->redirect('/admin/cms/page/' . $slug);
        }
        if (! $repo->upsert($slug, $title, $encoded)) {
            error_log('AdminController::cmsPageSave upsert returned false (slug=' . $slug . ')');
            Flash::set(Flash::ERROR, 'Could not save to the database. Check the server error log.');
            $this->redirect('/admin/cms/page/' . $slug);
        }
        Flash::set(Flash::SUCCESS, 'Page saved.');
        $this->redirect('/admin/cms/page/' . $slug);
    }

    private function mediaUpload(): void
    {
        if (!Csrf::validate($_POST['_csrf'] ?? null)) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'error' => 'Invalid session']);
            exit;
        }
        $this->assertDb();
        if (!isset($_FILES['file']) || !is_array($_FILES['file'])) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'error' => 'No file uploaded']);
            exit;
        }
        $f = $_FILES['file'];
        $err = (int) ($f['error'] ?? UPLOAD_ERR_NO_FILE);
        if ($err !== UPLOAD_ERR_OK) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'error' => 'Upload failed']);
            exit;
        }
        $tmp = (string) ($f['tmp_name'] ?? '');
        $size = (int) ($f['size'] ?? 0);
        $orig = (string) ($f['name'] ?? '');
        $mime = '';
        if ($tmp !== '' && is_file($tmp)) {
            $fi = new \finfo(FILEINFO_MIME_TYPE);
            $mime = (string) $fi->file($tmp);
        }
        $allowed = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
            'video/mp4' => 'mp4',
            'video/webm' => 'webm',
        ];
        if (!isset($allowed[$mime])) {
            http_response_code(415);
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'error' => 'Unsupported file type']);
            exit;
        }
        $ext = $allowed[$mime];
        $uploadSubdir = trim((string) ($_POST['upload_subdir'] ?? ''));
        $subdirDirs = [
            'slides' => 'uploads/slides',
            'about' => 'uploads/about',
        ];
        $relativeUploadDir = 'uploads';
        if ($uploadSubdir !== '') {
            if (!isset($subdirDirs[$uploadSubdir])) {
                http_response_code(400);
                header('Content-Type: application/json');
                echo json_encode(['ok' => false, 'error' => 'Invalid upload target']);
                exit;
            }
            $relativeUploadDir = $subdirDirs[$uploadSubdir];
        }
        $uploadDir = dirname(__DIR__, 2) . '/public/' . $relativeUploadDir;
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0775, true);
        }
        $name = 'cms-' . date('Ymd-His') . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
        $dest = $uploadDir . '/' . $name;
        if (!move_uploaded_file($tmp, $dest)) {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'error' => 'Could not save file']);
            exit;
        }
        $publicPath = $relativeUploadDir . '/' . $name;
        $id = (new CmsMediaRepository())->create($publicPath, $mime, $size, $orig);
        header('Content-Type: application/json');
        echo json_encode([
            'ok' => true,
            'id' => $id,
            'url' => app_url($publicPath),
            'mime' => $mime,
            'path' => $publicPath,
            'name' => $orig,
        ]);
        exit;
    }

    public function cmsSlidesList(): void
    {
        $this->requireAuth();
        $this->assertDb();
        $repo = new CmsSlidesRepository();
        $slides = $repo->listAllForAdmin();
        $this->render('admin/cms-slides', [
            'title' => 'Hero carousel',
            'active_nav' => '',
            'active_admin_nav' => 'cms-slides',
            'body_class' => 'admin-body--cms',
            'layout' => 'layouts/admin',
            'admin_authed' => true,
            'slides' => $slides,
            'storefront_url' => app_url(''),
        ]);
    }

    public function cmsSlideForm(?int $id): void
    {
        $this->requireAuth();
        $this->assertDb();
        $repo = new CmsSlidesRepository();
        $slide = null;
        if ($id !== null) {
            $slide = $repo->findById($id);
            if ($slide === null) {
                http_response_code(404);
                (new PageController())->notFound();

                return;
            }
        }
        $this->render('admin/cms-slide-form', [
            'title' => $slide === null ? 'New slide' : 'Edit slide',
            'active_nav' => '',
            'active_admin_nav' => 'cms-slides',
            'body_class' => 'page-admin-slide-form admin-body--cms',
            'layout' => 'layouts/admin',
            'admin_authed' => true,
            'slide' => $slide,
            'upload_action' => app_url('admin/media/upload'),
            'csrf_token' => Csrf::token(),
        ]);
    }

    public function cmsSlideSave(): void
    {
        if (!Csrf::validate($_POST['_csrf'] ?? null)) {
            Flash::set(Flash::ERROR, 'Invalid session.');
            $this->redirect('/admin/cms/slides');
        }
        $this->assertDb();
        $repo = new CmsSlidesRepository();
        $id = (int) ($_POST['id'] ?? 0);

        $badge = $this->clipStr(trim((string) ($_POST['badge'] ?? '')), 24);
        $headline = trim((string) ($_POST['headline'] ?? ''));
        $headline = $this->clipStr($headline, 160);
        $supporting = $this->clipStr(trim((string) ($_POST['supporting'] ?? '')), 200);
        $imageAlt = $this->clipStr(trim((string) ($_POST['image_alt'] ?? '')), 255);
        $desk = trim((string) ($_POST['image_desktop_path'] ?? ''));
        $mob = trim((string) ($_POST['image_mobile_path'] ?? ''));
        $pLabel = $this->clipStr(trim((string) ($_POST['btn_primary_label'] ?? '')), 24);
        $pHref = trim((string) ($_POST['btn_primary_href'] ?? ''));
        $sLabel = $this->clipStr(trim((string) ($_POST['btn_secondary_label'] ?? '')), 24);
        $sHref = trim((string) ($_POST['btn_secondary_href'] ?? ''));
        $isLive = isset($_POST['is_live']) ? 1 : 0;
        if ($pLabel === '') {
            $pHref = '';
        }
        if ($sLabel === '') {
            $sHref = '';
        }

        if ($headline === '') {
            Flash::set(Flash::ERROR, 'Headline is required.');
            $this->redirect($id > 0 ? '/admin/cms/slide/' . $id . '/edit' : '/admin/cms/slide/new');
        }
        if (!$this->slidePublicPathValid($desk)) {
            Flash::set(Flash::ERROR, 'Desktop image is required. Upload an image or enter a path under uploads/ or assets/.');
            $this->redirect($id > 0 ? '/admin/cms/slide/' . $id . '/edit' : '/admin/cms/slide/new');
        }
        if ($mob !== '' && !$this->slidePublicPathValid($mob)) {
            Flash::set(Flash::ERROR, 'Mobile image path is invalid.');
            $this->redirect($id > 0 ? '/admin/cms/slide/' . $id . '/edit' : '/admin/cms/slide/new');
        }
        if ($pLabel !== '' && !$this->slideHrefOk($pHref)) {
            Flash::set(Flash::ERROR, 'Primary button needs a valid link (e.g. /book or https://…).');
            $this->redirect($id > 0 ? '/admin/cms/slide/' . $id . '/edit' : '/admin/cms/slide/new');
        }
        if ($pLabel !== '' && trim($pHref) === '') {
            Flash::set(Flash::ERROR, 'Primary button label needs a link.');
            $this->redirect($id > 0 ? '/admin/cms/slide/' . $id . '/edit' : '/admin/cms/slide/new');
        }
        if ($sLabel !== '' && !$this->slideHrefOk($sHref)) {
            Flash::set(Flash::ERROR, 'Secondary button needs a valid link.');
            $this->redirect($id > 0 ? '/admin/cms/slide/' . $id . '/edit' : '/admin/cms/slide/new');
        }
        if ($sLabel !== '' && trim($sHref) === '') {
            Flash::set(Flash::ERROR, 'Secondary button label needs a link.');
            $this->redirect($id > 0 ? '/admin/cms/slide/' . $id . '/edit' : '/admin/cms/slide/new');
        }

        $startsAt = $this->parseSlideDatetime($_POST['starts_at'] ?? null);
        $endsAt = $this->parseSlideDatetime($_POST['ends_at'] ?? null);
        if ($startsAt !== null && $endsAt !== null && strtotime($endsAt) < strtotime($startsAt)) {
            Flash::set(Flash::ERROR, 'End date must be on or after the start date.');
            $this->redirect($id > 0 ? '/admin/cms/slide/' . $id . '/edit' : '/admin/cms/slide/new');
        }

        $payload = [
            'is_live' => $isLive,
            'badge' => $badge,
            'headline' => $headline,
            'supporting' => $supporting,
            'btn_primary_label' => $pLabel,
            'btn_primary_href' => $pHref,
            'btn_secondary_label' => $sLabel,
            'btn_secondary_href' => $sHref,
            'image_desktop_path' => $desk,
            'image_mobile_path' => $mob !== '' ? $mob : null,
            'image_alt' => $imageAlt,
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
        ];

        if ($id > 0) {
            $existing = $repo->findById($id);
            if ($existing === null) {
                Flash::set(Flash::ERROR, 'Slide not found.');
                $this->redirect('/admin/cms/slides');
            }
            $repo->update($id, $payload);
            Flash::set(Flash::SUCCESS, 'Slide saved.');
            $this->redirect('/admin/cms/slide/' . $id . '/edit');
        }

        $newId = $repo->create($payload);
        if ($newId <= 0) {
            Flash::set(Flash::ERROR, 'Could not create slide.');
            $this->redirect('/admin/cms/slide/new');
        }
        Flash::set(Flash::SUCCESS, 'Slide created.');
        $this->redirect('/admin/cms/slide/' . $newId . '/edit');
    }

    public function cmsSlideDelete(int $id): void
    {
        if (!Csrf::validate($_POST['_csrf'] ?? null)) {
            Flash::set(Flash::ERROR, 'Invalid session.');
            $this->redirect('/admin/cms/slides');
        }
        $this->assertDb();
        $repo = new CmsSlidesRepository();
        if ($repo->findById($id) === null) {
            Flash::set(Flash::ERROR, 'Slide not found.');
            $this->redirect('/admin/cms/slides');
        }
        $repo->delete($id);
        Flash::set(Flash::SUCCESS, 'Slide deleted.');
        $this->redirect('/admin/cms/slides');
    }

    public function cmsSlideReorder(int $id, string $direction): void
    {
        if (!Csrf::validate($_POST['_csrf'] ?? null)) {
            Flash::set(Flash::ERROR, 'Invalid session.');
            $this->redirect('/admin/cms/slides');
        }
        $this->assertDb();
        $repo = new CmsSlidesRepository();
        $all = $repo->listAllForAdmin();
        $ids = array_map(static fn (array $r): int => (int) ($r['id'] ?? 0), $all);
        $ids = array_values(array_filter($ids, static fn (int $i): bool => $i > 0));
        $idx = array_search($id, $ids, true);
        if ($idx === false) {
            $this->redirect('/admin/cms/slides');
        }
        if ($direction === 'up' && $idx > 0) {
            $tmp = $ids[$idx - 1];
            $ids[$idx - 1] = $ids[$idx];
            $ids[$idx] = $tmp;
        } elseif ($direction === 'down' && $idx < count($ids) - 1) {
            $tmp = $ids[$idx + 1];
            $ids[$idx + 1] = $ids[$idx];
            $ids[$idx] = $tmp;
        }
        $repo->applyOrder($ids);
        $this->redirect('/admin/cms/slides');
    }

    private function clipStr(string $s, int $max): string
    {
        if (function_exists('mb_substr')) {
            return mb_strlen($s) > $max ? mb_substr($s, 0, $max) : $s;
        }

        return strlen($s) > $max ? substr($s, 0, $max) : $s;
    }

    private function slidePublicPathValid(string $p): bool
    {
        $p = trim($p);
        if ($p === '' || str_contains($p, '..')) {
            return false;
        }

        return str_starts_with($p, 'uploads/') || str_starts_with($p, 'assets/');
    }

    private function slideHrefOk(string $h): bool
    {
        $h = trim($h);
        if ($h === '') {
            return true;
        }
        if (stripos($h, 'javascript:') === 0 || stripos($h, 'data:') === 0) {
            return false;
        }
        if (str_starts_with($h, '/') && !str_starts_with($h, '//')) {
            return !str_contains($h, '..');
        }

        return preg_match('#^https?://#i', $h) === 1;
    }

    private function parseSlideDatetime(mixed $raw): ?string
    {
        $raw = trim((string) $raw);
        if ($raw === '') {
            return null;
        }
        $raw = str_replace('T', ' ', $raw);
        $t = strtotime($raw);
        if ($t === false) {
            return null;
        }

        return date('Y-m-d H:i:s', $t);
    }

    public function products(): void
    {
        $this->assertDb();
        $repo = new ProductRepository();
        $this->render('admin/products', [
            'title' => 'Products',
            'active_nav' => '',
            'active_admin_nav' => 'products',
            'body_class' => '',
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
        $options = $id === null ? [] : $repo->findOptions($id);
        $this->render('admin/product-form', [
            'title' => $p === null ? 'New product' : 'Edit product',
            'active_nav' => '',
            'active_admin_nav' => 'products',
            'body_class' => '',
            'layout' => 'layouts/admin',
            'admin_authed' => true,
            'p' => $p,
            'options' => $options,
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
        $priceMaxCents = $this->parseMoneyToCents((string) ($_POST['price_max'] ?? ''));
        $currency = strtoupper(substr((string) ($_POST['currency'] ?? 'CAD'), 0, 3)) ?: 'CAD';
        $imageUrl = trim((string) ($_POST['image_url'] ?? ''));
        $imageUrl = $imageUrl === '' ? null : $imageUrl;
        $stockRaw = trim((string) ($_POST['stock'] ?? ''));
        $stock = $stockRaw === '' ? null : (int) $stockRaw;
        $categoryKey = slugify(trim((string) ($_POST['category_key'] ?? '')));
        $categoryKey = $categoryKey === '' ? null : $this->clipStr($categoryKey, 60);
        $badgeLabel = $this->clipStr(trim((string) ($_POST['badge_label'] ?? '')), 40);
        $badgeLabel = $badgeLabel === '' ? null : $badgeLabel;
        $details = $this->normaliseTextareaLines((string) ($_POST['details'] ?? ''), 20, 180);
        $idealFor = $this->normaliseTextareaLines((string) ($_POST['ideal_for'] ?? ''), 20, 120);
        $policyNote = $this->clipStr(trim((string) ($_POST['policy_note'] ?? '')), 500);
        $policyNote = $policyNote === '' ? null : $policyNote;
        $options = $this->parseProductOptions($_POST['options'] ?? []);
        $hasOpts = (int) !empty($_POST['has_options']);
        if ($options !== []) {
            $hasOpts = 1;
        }
        if ($name === '') {
            Flash::set(Flash::ERROR, 'Name is required.');
            $this->redirect($id > 0 ? '/admin/product/' . $id . '/edit' : '/admin/product/new');
        }
        if ($priceCents === null || $priceCents < 0) {
            Flash::set(Flash::ERROR, 'Valid price is required (e.g. 12.99).');
            $this->redirect($id > 0 ? '/admin/product/' . $id . '/edit' : '/admin/product/new');
        }
        if ($priceMaxCents !== null && $priceMaxCents < $priceCents) {
            Flash::set(Flash::ERROR, 'Max price must be greater than or equal to the base price.');
            $this->redirect($id > 0 ? '/admin/product/' . $id . '/edit' : '/admin/product/new');
        }
        if (!preg_match('/^[A-Z]{3}$/', $currency)) {
            Flash::set(Flash::ERROR, 'Currency must be a 3-letter code like CAD or USD.');
            $this->redirect($id > 0 ? '/admin/product/' . $id . '/edit' : '/admin/product/new');
        }
        if ($stock !== null && $stock < 0) {
            Flash::set(Flash::ERROR, 'Stock cannot be negative.');
            $this->redirect($id > 0 ? '/admin/product/' . $id . '/edit' : '/admin/product/new');
        }
        if ($imageUrl !== null && !$this->productImagePathValid($imageUrl)) {
            Flash::set(Flash::ERROR, 'Image URL must be http(s), or a local uploads/assets path.');
            $this->redirect($id > 0 ? '/admin/product/' . $id . '/edit' : '/admin/product/new');
        }
        $slug = $slugIn !== '' ? slugify($slugIn) : slugify($name);
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        $sort = max(0, (int) ($_POST['sort_order'] ?? 0));
        $payload = [
            'slug' => $slug,
            'name' => $name,
            'description' => $description,
            'price_cents' => $priceCents,
            'price_max_cents' => $priceMaxCents,
            'currency' => $currency,
            'image_url' => $imageUrl,
            'stock' => $stock,
            'has_options' => $hasOpts,
            'category_key' => $categoryKey,
            'badge_label' => $badgeLabel,
            'details' => $details,
            'ideal_for' => $idealFor,
            'policy_note' => $policyNote,
            'is_active' => $isActive,
            'sort_order' => $sort,
        ];
        if ($id > 0) {
            if ($repo->slugExists($slug, $id)) {
                $slug = $this->makeUniqueSlug($repo, $slug, $id);
                $payload['slug'] = $slug;
            }
            $ok = $repo->update($id, $payload);
            if ($ok) {
                $repo->replaceOptions($id, $options);
            }
            Flash::set($ok ? Flash::SUCCESS : Flash::ERROR, $ok ? 'Product updated.' : 'Update failed.');
            $this->redirect('/admin/product/' . $id . '/edit');
            return;
        }
        if ($repo->slugExists($slug, null)) {
            $slug = $this->makeUniqueSlug($repo, $slug, null);
            $payload['slug'] = $slug;
        }
        $newId = $repo->create($payload);
        if ($newId > 0) {
            $repo->replaceOptions($newId, $options);
            Flash::set(Flash::SUCCESS, 'Product created.');
            $this->redirect('/admin/product/' . $newId . '/edit');
            return;
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
            'active_admin_nav' => 'leads',
            'body_class' => '',
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
            'active_admin_nav' => 'orders',
            'body_class' => '',
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
            'active_admin_nav' => 'orders',
            'body_class' => '',
            'layout' => 'layouts/admin',
            'admin_authed' => true,
            'o' => $data['order'],
            'items' => $data['items'],
        ]);
    }

    public function profileForm(): void
    {
        $this->assertDb();
        $uid = AdminAuth::id();
        if ($uid === null) {
            $this->redirect('/admin/login');
        }
        $users = new UserRepository();
        $u = $users->findById($uid);
        if ($u === null) {
            AdminAuth::logout();
            Flash::set(Flash::ERROR, 'Session expired. Sign in again.');
            $this->redirect('/admin/login');
        }
        $this->render('admin/profile', [
            'title' => 'Profile',
            'active_nav' => '',
            'active_admin_nav' => 'profile',
            'body_class' => '',
            'layout' => 'layouts/admin',
            'admin_authed' => true,
            'user' => $u,
        ]);
    }

    public function profileSave(): void
    {
        if (!Csrf::validate($_POST['_csrf'] ?? null)) {
            Flash::set(Flash::ERROR, 'Invalid session.');
            $this->redirect('/admin/profile');
        }
        $this->assertDb();
        $uid = AdminAuth::id();
        if ($uid === null) {
            $this->redirect('/admin/login');
        }
        $users = new UserRepository();
        if ($users->findById($uid) === null) {
            AdminAuth::logout();
            Flash::set(Flash::ERROR, 'Session expired. Sign in again.');
            $this->redirect('/admin/login');
        }
        $email = trim((string) ($_POST['email'] ?? ''));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Flash::set(Flash::ERROR, 'Enter a valid email address.');
            $this->redirect('/admin/profile');
        }
        if ($users->emailTakenByOtherUser($email, $uid)) {
            Flash::set(Flash::ERROR, 'That email is already in use.');
            $this->redirect('/admin/profile');
        }
        if (!$users->updateEmail($uid, $email)) {
            Flash::set(Flash::ERROR, 'Could not update profile.');
            $this->redirect('/admin/profile');
        }
        Flash::set(Flash::SUCCESS, 'Profile updated.');
        $this->redirect('/admin/profile');
    }

    public function passwordForm(): void
    {
        $this->assertDb();
        $uid = AdminAuth::id();
        if ($uid === null) {
            $this->redirect('/admin/login');
        }
        if ((new UserRepository())->findById($uid) === null) {
            AdminAuth::logout();
            Flash::set(Flash::ERROR, 'Session expired. Sign in again.');
            $this->redirect('/admin/login');
        }
        $this->render('admin/password', [
            'title' => 'Password',
            'active_nav' => '',
            'active_admin_nav' => 'password',
            'body_class' => '',
            'layout' => 'layouts/admin',
            'admin_authed' => true,
        ]);
    }

    public function passwordSave(): void
    {
        if (!Csrf::validate($_POST['_csrf'] ?? null)) {
            Flash::set(Flash::ERROR, 'Invalid session.');
            $this->redirect('/admin/password');
        }
        $this->assertDb();
        $uid = AdminAuth::id();
        if ($uid === null) {
            $this->redirect('/admin/login');
        }
        $users = new UserRepository();
        if ($users->findById($uid) === null) {
            AdminAuth::logout();
            Flash::set(Flash::ERROR, 'Session expired. Sign in again.');
            $this->redirect('/admin/login');
        }
        $current = (string) ($_POST['current_password'] ?? '');
        $new = (string) ($_POST['new_password'] ?? '');
        $confirm = (string) ($_POST['confirm_password'] ?? '');
        $hash = $users->findPasswordHashById($uid);
        if ($hash === null || !password_verify($current, $hash)) {
            Flash::set(Flash::ERROR, 'Current password is incorrect.');
            $this->redirect('/admin/password');
        }
        if (strlen($new) < 10) {
            Flash::set(Flash::ERROR, 'New password must be at least 10 characters.');
            $this->redirect('/admin/password');
        }
        if ($new !== $confirm) {
            Flash::set(Flash::ERROR, 'New password and confirmation do not match.');
            $this->redirect('/admin/password');
        }
        if (!$users->updatePasswordHash($uid, password_hash($new, PASSWORD_DEFAULT))) {
            Flash::set(Flash::ERROR, 'Could not update password.');
            $this->redirect('/admin/password');
        }
        Flash::set(Flash::SUCCESS, 'Password changed.');
        $this->redirect('/admin/password');
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
     * @return array<string, mixed>
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
            'price_max_cents' => isset($p['price_max_cents']) && $p['price_max_cents'] !== null && $p['price_max_cents'] !== '' ? (int) $p['price_max_cents'] : null,
            'currency' => (string) ($p['currency'] ?? 'CAD'),
            'image_url' => isset($p['image_url']) && $p['image_url'] !== '' && $p['image_url'] !== null ? (string) $p['image_url'] : null,
            'stock' => $stockVal,
            'has_options' => (int) ($p['has_options'] ?? 0),
            'category_key' => isset($p['category_key']) && $p['category_key'] !== '' ? (string) $p['category_key'] : null,
            'badge_label' => isset($p['badge_label']) && $p['badge_label'] !== '' ? (string) $p['badge_label'] : null,
            'details' => isset($p['details']) && $p['details'] !== '' ? (string) $p['details'] : null,
            'ideal_for' => isset($p['ideal_for']) && $p['ideal_for'] !== '' ? (string) $p['ideal_for'] : null,
            'policy_note' => isset($p['policy_note']) && $p['policy_note'] !== '' ? (string) $p['policy_note'] : null,
            'is_active' => (int) ($p['is_active'] ?? 1),
            'sort_order' => (int) ($p['sort_order'] ?? 0),
        ];
    }

    private function normaliseTextareaLines(string $raw, int $maxLines, int $maxLineLength): ?string
    {
        $lines = [];
        foreach (preg_split('/\r\n|\r|\n/', $raw) ?: [] as $line) {
            $line = trim((string) $line);
            if ($line === '') {
                continue;
            }
            $lines[] = $this->clipStr($line, $maxLineLength);
            if (count($lines) >= $maxLines) {
                break;
            }
        }
        return $lines === [] ? null : implode("\n", $lines);
    }

    /** @return list<array{label:string,price_cents:int,sort_order:int}> */
    private function parseProductOptions(mixed $raw): array
    {
        if (!is_array($raw)) {
            return [];
        }
        $out = [];
        $i = 0;
        foreach ($raw as $row) {
            if (!is_array($row)) {
                continue;
            }
            $label = $this->clipStr(trim((string) ($row['label'] ?? '')), 255);
            $price = $this->parseMoneyToCents((string) ($row['price'] ?? ''));
            $sort = max(0, (int) ($row['sort_order'] ?? $i));
            if ($label === '' && ($price === null || $price === 0)) {
                continue;
            }
            if ($label === '' || $price === null || $price < 0) {
                Flash::set(Flash::ERROR, 'Every product option needs a label and valid price.');
                $id = (int) ($_POST['id'] ?? 0);
                $this->redirect($id > 0 ? '/admin/product/' . $id . '/edit' : '/admin/product/new');
            }
            $out[] = [
                'label' => $label,
                'price_cents' => $price,
                'sort_order' => $sort,
            ];
            $i++;
            if (count($out) >= 30) {
                break;
            }
        }
        return $out;
    }

    private function productImagePathValid(string $path): bool
    {
        $path = trim($path);
        if ($path === '' || str_contains($path, '..')) {
            return false;
        }
        if (preg_match('#^https?://#i', $path) === 1) {
            return true;
        }
        $clean = ltrim($path, '/');
        return str_starts_with($clean, 'uploads/') || str_starts_with($clean, 'assets/');
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
