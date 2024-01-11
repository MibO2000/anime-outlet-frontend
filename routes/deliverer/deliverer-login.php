<?php

$hasError = false;
$errorMessage = '';
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $query = sprintf('SELECT `deliverer_name`,`deliverer_user`,`deliverer_password` FROM ao_deliverer WHERE deliverer_user=\'%s\'', mysqli_real_escape_string($connect, $username));
    $result = $connect->query($query);
    $result = $result->fetch_all();
    if (!$result) {
        $hasError = 1;
        $errorMessage = 'Username does not exist.';
    } else
    if (!password_verify($password, $result[0][2])) {
        $hasError = 1;
        $errorMessage = 'Incorrect password.';
    } else {
        $_SESSION['name'] = $result[0][0];
        $_SESSION['username'] = $result[0][1];
        $_SESSION['role'] = ROLE_DELIVERER;
        header('Location: /deliverer', true, 301);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deliverer Login</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }

        .b-example-divider {
            width: 100%;
            height: 3rem;
            background-color: rgba(0, 0, 0, .1);
            border: solid rgba(0, 0, 0, .15);
            border-width: 1px 0;
            box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
        }

        .b-example-vr {
            flex-shrink: 0;
            width: 1.5rem;
            height: 100vh;
        }

        .bi {
            vertical-align: -.125em;
            fill: currentColor;
        }

        .nav-scroller {
            position: relative;
            z-index: 2;
            height: 2.75rem;
            overflow-y: hidden;
        }

        .nav-scroller .nav {
            display: flex;
            flex-wrap: nowrap;
            padding-bottom: 1rem;
            margin-top: -1px;
            overflow-x: auto;
            text-align: center;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
        }

        .btn-bd-primary {
            --bd-violet-bg: #712cf9;
            --bd-violet-rgb: 112.520718, 44.062154, 249.437846;

            --bs-btn-font-weight: 600;
            --bs-btn-color: var(--bs-white);
            --bs-btn-bg: var(--bd-violet-bg);
            --bs-btn-border-color: var(--bd-violet-bg);
            --bs-btn-hover-color: var(--bs-white);
            --bs-btn-hover-bg: #6528e0;
            --bs-btn-hover-border-color: #6528e0;
            --bs-btn-focus-shadow-rgb: var(--bd-violet-rgb);
            --bs-btn-active-color: var(--bs-btn-hover-color);
            --bs-btn-active-bg: #5a23c8;
            --bs-btn-active-border-color: #5a23c8;
        }

        .bd-mode-toggle {
            z-index: 1500;
        }

        .bd-mode-toggle .dropdown-menu .active .bi {
            display: block !important;
        }

        html,
        body {
            height: 100%;
        }

        .form-signin {
            max-width: 330px;
            padding: 1rem;
        }

        .form-signin .form-floating:focus-within {
            z-index: 2;
        }

        .form-signin input[type="text"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }

        .form-signin input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }

        .bg-image {
            background-color: #9c8570;
            background-image: url('https://images.unsplash.com/photo-1682695794947-17061dc284dd?q=80&w=3540&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDF8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');
            background-repeat: no-repeat;
            background-position: center center;
            background-size: cover;
        }
    </style>

</head>

<body>

    <body class="bg-image">
        <div class="d-flex align-items-center py-4 h-100" style="min-width:320px;max-width:520px;background-color:#f5f5f5">
            <main class="form-signin w-100 m-auto">
                <form method="POST">
                    <h1 class="h3 mb-3 fw-normal">Deliverer Login</h1>


                    <div class="form-floating">
                        <input type="text" class="form-control" id="floatingInput" autocapitalize="off" name="username" value="<?= $_POST['username'] ?? '' ?>" required>
                        <label for="floatingInput">Username</label>
                    </div>
                    <div class="form-floating">
                        <input type="password" class="form-control" id="floatingPassword" name="password" required>
                        <label for="floatingPassword">Password</label>
                    </div>

                    <?php if ($hasError) { ?>
                        <div class="my-2 alert alert-danger" role="alert">
                            <?= $errorMessage ?>
                        </div>
                    <?php } ?>
                    <button class="btn btn-primary w-100 py-2" type="submit">Log In</button>
                    <p class="mt-5 mb-3 text-body-secondary">&copy; <?= date("Y") ?>. All right reserved.</p>
                </form>
            </main>
        </div>
        <script src="/js/bootstrap.bundle.min.js"></script>
    </body>

</html>