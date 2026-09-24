<?php
// Configuration File
// Vaccination Management System (VMS)
// Values from environment (.env / host) take priority; fallback = local XAMPP

function env(string $key, string $default = ''): string {
    $v = getenv($key);
    return ($v === false || $v === '') ? $default : $v;
}

// Database Configuration Settings
define('DB_HOST', env('DB_HOST', '127.0.0.1:3306'));
define('DB_NAME', env('DB_NAME', 'vms_db'));
define('DB_USER', env('DB_USER', 'root'));
define('DB_PASS', env('DB_PASS', ''));

// Application Settings
define('APP_NAME', env('APP_NAME', 'VMS'));
define('BASE_URL', env('BASE_URL', '/'));
define('APP_ENV', env('APP_ENV', 'development'));

// Error reporting: on in development, off in production
$isProd = (APP_ENV === 'production');
ini_set('display_errors', $isProd ? '0' : '1');
ini_set('display_startup_errors', $isProd ? '0' : '1');
error_reporting($isProd ? E_ALL & ~E_DEPRECATED & ~E_NOTICE : E_ALL);

// Start Session securely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
