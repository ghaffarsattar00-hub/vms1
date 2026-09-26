<?php
// Keep PHP clocks consistent for reset-token expiry + display
date_default_timezone_set('Asia/Karachi');
// VMS Production config - InfinityFree (sab kuch public_html root pe)
function env(string $key, string $default = ''): string {
    $v = getenv($key);
    return ($v === false || $v === '') ? $default : $v;
}
define('DB_HOST', env('DB_HOST', 'sql302.infinityfree.com'));
define('DB_NAME', env('DB_NAME', 'if0_42999413_vms'));
define('DB_USER', env('DB_USER', 'if0_42999413'));
define('DB_PASS', env('DB_PASS', 'ag1122GA'));
define('APP_NAME', env('APP_NAME', 'VMS'));
define('BASE_URL', env('BASE_URL', '/'));
define('APP_ENV', 'production');
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE & ~E_WARNING);
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(['lifetime'=>0,'path'=>'/','secure'=>!empty($_SERVER['HTTPS']),'httponly'=>true,'samesite'=>'Lax']);
    session_start();
}