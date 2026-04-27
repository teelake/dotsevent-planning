<?php

/**
 * When the app lives in a subfolder (e.g. /new) and the account “document root”
 * is the project folder (sibling to /public), this file is the first hit for
 * https://yoursite.com/new/ and loads the same front controller as public/.
 */
declare(strict_types=1);

require __DIR__ . '/public/index.php';
