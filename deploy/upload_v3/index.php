<?php
// index.php — Front Controller (htdocs root layout)

// 1. Include core configuration
require_once __DIR__ . '/config.php';

// 2. Custom PSR-like Autoloader for MVC classes
spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/core/',
        __DIR__ . '/app/Models/',
        __DIR__ . '/app/Controllers/',
    ];
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// 3. Instantiate Router
$router = new Router();

// 4. Define Application Routes

// --- Authentication Routes ---
$router->get('/', 'AuthController@login');
$router->get('login', 'AuthController@login');
$router->post('login', 'AuthController@authenticate');
$router->get('register', 'AuthController@register');
$router->post('register', 'AuthController@storeParent');
$router->get('logout', 'AuthController@logout');
$router->get('forgot-password', 'AuthController@forgotPassword');
$router->post('forgot-password', 'AuthController@forgotPassword');
$router->get('reset-password', 'AuthController@resetPassword');
$router->post('reset-password', 'AuthController@resetPassword');

// --- Admin Module Routes ---
$router->get('admin/dashboard', 'AdminController@dashboard');
$router->get('admin/hospitals', 'AdminController@hospitals');
$router->post('admin/hospitals/create', 'AdminController@createHospital');
$router->post('admin/hospitals/update', 'AdminController@updateHospital');
$router->get('admin/hospitals/delete/{id}', 'AdminController@deleteHospital');
$router->get('admin/setup-hospital-users', 'AdminController@setupHospitalUsers');
$router->get('admin/inventory', 'AdminController@inventory');
$router->post('admin/inventory/toggle', 'AdminController@toggleVaccine');
$router->get('admin/appointments', 'AdminController@appointments');
$router->post('admin/appointments/update', 'AdminController@updateAppointment');

// --- Parent Module Routes ---
$router->get('parent/dashboard', 'ParentController@dashboard');
$router->post('parent/dashboard/add-child', 'ParentController@addChild');
$router->get('parent/child/delete/{id}', 'ParentController@deleteChild');
$router->get('parent/book', 'ParentController@book');
$router->post('parent/book/create', 'ParentController@createAppointment');

// --- Hospital Module Routes ---
$router->get('hospital/dashboard', 'HospitalController@dashboard');
$router->get('hospital/appointments', 'HospitalController@appointments');
$router->post('hospital/appointments/approve', 'HospitalController@approveAppointment');
$router->post('hospital/appointments/reject', 'HospitalController@rejectAppointment');
$router->post('hospital/appointments/vaccinate', 'HospitalController@vaccinateAppointment');
$router->get('hospital/inventory', 'HospitalController@inventory');
$router->post('hospital/inventory/order', 'HospitalController@placeOrder');
$router->post('hospital/inventory/restock', 'HospitalController@restock');
$router->get('hospital/orders', 'HospitalController@orders');

// --- Admin Orders Routes ---
$router->get('admin/orders', 'AdminController@orders');
$router->post('admin/orders/update', 'AdminController@updateOrder');

// 5. Dispatch Request
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$requestUriClean = parse_url($requestUri, PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';

$router->dispatch($requestUriClean, $requestMethod);
