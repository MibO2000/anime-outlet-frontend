<?php

session_start();

define('COMPANY_NAME', 'MibO');

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
        require __DIR__ . '/routes/customer/customer-index.php';
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
    case '/admin-item-details':
        require __DIR__ . '/routes/admin/admin-item-details.php';
        break;
    case '/admin-deliverer':
        require __DIR__ . '/routes/admin/admin-deliverer.php';
        break;
    case '/admin-order':
        require __DIR__ . '/routes/admin/admin-order.php';
        break;
    case '/admin-order-details':
        require __DIR__ . '/routes/admin/admin-order-details.php';
        break;
    case '/admin-purchase':
        require __DIR__ . '/routes/admin/admin-purchase.php';
        break;
    case '/admin-supplier':
        require __DIR__ . '/routes/admin/admin-supplier.php';
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

        /* Customer */
    case '/login':
        require __DIR__ . '/routes/customer/customer-login.php';
        break;
    case '/register':
        require __DIR__ . '/routes/customer/customer-register.php';
        break;
    case '/items':
        require __DIR__ . '/routes/customer/customer-item.php';
        break;
    case '/item-details':
        require __DIR__ . '/routes/customer/customer-item-details.php';
        break;
    case '/cart':
        require __DIR__ . '/routes/customer/customer-cart.php';
        break;

        /* Log Out */
    case '/logout':
        $redirect = '/';
        switch ($_SESSION['role'] ?? 0) {
            case ROLE_ADMIN:
                $redirect = '/admin-login';
                break;
            case ROLE_DELIVERER:
                $redirect = '/deliverer-login';
                break;
            case ROLE_SUPPLIER:
                $redirect = '/supplier-login';
                break;
        }
        session_destroy();
        header('Location: ' . $redirect, true, 301);
        break;

        /* Page Not Found */
    default:
        http_response_code(404);
        require __DIR__ . '/routes/404.php';
}
