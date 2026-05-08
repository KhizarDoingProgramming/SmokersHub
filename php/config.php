<?php
// ============================================
// config.php  –  Edit these values to match
//                your hosting environment
// ============================================

define('DB_HOST',     '127.0.0.1');
define('DB_USER',     'root');
define('DB_PASS',     '2405');
define('DB_NAME',     'smokers_hub');
define('DB_CHARSET',  'utf8mb4');

// Site settings
define('SITE_NAME',   "Smoker's Hub");
define('SITE_URL',    'http://localhost/smokers-hub/Project');

// Session lifetime (seconds)
define('SESSION_LIFETIME', 3600);

// Start session globally
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => SESSION_LIFETIME,
        'path'     => '/',
        'secure'   => false,
        'httponly' => true,
        'samesite' => 'Strict',
    ]);
    session_start();
}
