<?php

session_start();

$uri = $_SERVER['REQUEST_URI'];
$path = parse_url($uri, PHP_URL_PATH);

require_once __DIR__ . '/connect.php';
require_once __DIR__ . '/helper.php';

switch ($path) {
    case '/':
        require __DIR__ . '/routes/homepage.php';
        break;
    case '/admin-login':
        require __DIR__ . '/routes/adminLogin.php';
        break;
    case '/admin-register':
        require __DIR__ . '/routes/adminRegister.php';
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
    case '/logout':
        session_destroy();
    case '/login':
        require __DIR__ . '/routes/login.php';
        break;
    case '/login-error':
        require __DIR__ . '/routes/login-error.php';
        break;
    case '/register':
        require __DIR__ . '/routes/register.php';
        break;

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
    case '/test';
        require __DIR__ . '/routes/test.php';
        break;
    default:
        http_response_code(404);
        require __DIR__ . '/routes/404.php';
}
