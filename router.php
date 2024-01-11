<?php

session_start();

define('ROLE_ADMIN', 1);
define('ROLE_CUSTOMER', 2);
define('ROLE_DELIVERER', 3);
define('ROLE_SUPPLIER', 4);

$uri = $_SERVER['REQUEST_URI'];
$path = parse_url($uri, PHP_URL_PATH);

require_once __DIR__ . '/connect.php';
require_once __DIR__ . '/helper.php';

switch ($path) {
    case '/':
        require __DIR__ . '/routes/homepage.php';
        break;

        /* Admin */
    case '/admin-login':
        require __DIR__ . '/routes/admin/admin-login.php';
        break;
    case '/admin-register':
        require __DIR__ . '/routes/admin/admin-register.php';
        break;
    case '/admin-items':
        require __DIR__ . '/routes/admin/admin-items.php';
        break;
    case '/admin-package':
        require __DIR__ . '/routes/adminPackage.php';
        break;
    case '/admin-package-type':
        require __DIR__ . '/routes/adminPackageType.php';
        break;
    case '/admin-pitch-type':
        require __DIR__ . '/routes/adminPitchType.php';
        break;
    case '/admin-pitch':
        require __DIR__ . '/routes/adminPitch.php';
        break;
    case '/admin-local':
        require __DIR__ . '/routes/adminLocalAttraction.php';
        break;
    case '/admin-location-type':
        require __DIR__ . '/routes/adminLocationType.php';
        break;

        /* Customer */
    case '/customer-login':
        require __DIR__ . '/routes/customer/customer-login.php';
        break;
    case '/customer':
        require __DIR__ . '/routes/customer/customer-index.php';
        break;

        /* Deliverer */
    case '/deliverer-login':
        require __DIR__ . '/routes/deliverer/deliverer-login.php';
        break;
    case '/deliverer':
        require __DIR__ . '/routes/deliverer/deliverer-index.php';
        break;

        /* Supplier */
    case '/supplier-login':
        require __DIR__ . '/routes/supplier/supplier-login.php';
        break;
    case '/supplier':
        require __DIR__ . '/routes/supplier/supplier-index.php';
        break;

        /* Log Out */
    case '/logout':
        session_destroy();
        header('Location: /', true, 301);
        break;

        /* Others */
    case '/about-us':
        require __DIR__ . '/routes/about-us.php';
        break;
    case '/contact-us':
        require __DIR__ . '/routes/contact-us.php';
        break;
    case '/reviews':
        require __DIR__ . '/routes/review.php';
        break;
    case '/packages':
        require __DIR__ . '/routes/packages.php';
        break;
    case '/package-detail':
        require __DIR__ . '/routes/package-detail.php';
        break;
    case '/features':
        require __DIR__ . '/routes/features.php';
        break;
    case '/pitch':
        require __DIR__ . '/routes/pitch.php';
        break;
    case '/local-attraction':
        require __DIR__ . '/routes/local-attraction.php';
        break;
    case '/cart':
        require __DIR__ . '/routes/cart.php';
        break;

        /* Page Not Found */
    default:
        http_response_code(404);
        require __DIR__ . '/routes/404.php';
}
