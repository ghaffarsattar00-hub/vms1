<?php
// Production config for shared hosting (InfinityFree / GoogieHost)
// DB credentials hosting panel se milenge (phpMyAdmin / MySQL Databases)

function env(string $key, string $default = ''): string {
    $v = getenv($key);
    return ($v === false || $v === '') ? $default : $v;
}

// >>> HOSTING PANEL SE YE 4 VALUES UPDATE KARO <<<
define('DB_HOST', env('DB_HOST', 'sqlXXX.byetcluster.com')); // e.g. sql305.byetcluster.com
define('DB_NAME', env('DB_NAME', 'if0_XXXXXXX_vms'));         // panel jaisa
define('DB_USER', env('DB_USER', 'if0_XXXXXXX'));             // panel jaisa
define('DB_PASS', env('DB_PASS', 'YOUR_DB_PASSWORD'));        // panel password

define('APP_NAME', env('APP_NAME', 'VMS'));
define('BASE_URL', env('BASE_URL', '/'));
define('APP_ENV', 'production');

// Production: errors public mein mat dikhao
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE & ~E_WARNING);

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => !empty($_SERVER['HTTPS']),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}
