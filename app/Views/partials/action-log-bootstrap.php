<?php
declare(strict_types=1);

use App\Core\Csrf;
use App\Core\Session;

$__alogSurface = $__alogSurface ?? 'public';

Session::start();
$_cfg = app_config()['action_logging'] ?? [];
$_browserOn = is_array($_cfg)
    && (($_cfg['enabled'] ?? false) === true)
    && (($_cfg['log_browser'] ?? true) !== false);

if ($_browserOn) {
    $__dotsLog = [
        'url' => app_url('frontend-log'),
        'csrf' => Csrf::token(),
        'surface' => (string) $__alogSurface,
    ];
    $__enc = json_encode($__dotsLog, JSON_UNESCAPED_SLASHES);
    if (is_string($__enc)): ?>
<script>window.__DOTS_ACTION_LOG__=<?= $__enc ?>;</script>
<script src="<?= e(asset('js/action-log.js')) ?>" defer></script>
<?php
    endif;
}
