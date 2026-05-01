<?php
declare(strict_types=1);

$GLOBALS['__dots_newsletter_strip_present'] = true;

$nfErr = \App\Core\Flash::get(\App\Core\Flash::NEWSLETTER_ERROR);
$nfOk = \App\Core\Flash::get(\App\Core\Flash::NEWSLETTER_SUCCESS);
?>
<?php if ($nfErr !== null): ?>
<p class="newsletter-app__feedback newsletter-app__feedback--error newsletter-app__feedback--banner" role="alert"><?= e($nfErr) ?></p>
<?php elseif ($nfOk !== null): ?>
<p class="newsletter-app__feedback newsletter-app__feedback--success newsletter-app__feedback--banner" role="status"><?= e($nfOk) ?></p>
<?php endif; ?>
