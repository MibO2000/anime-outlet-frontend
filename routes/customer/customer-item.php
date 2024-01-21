<?php
if (($_SESSION['role'] ?? 0) !== ROLE_CUSTOMER) {
    session_destroy();
    header('Location: /login', true, 301);
    exit;
}
$films = [];
$categories = [];
$brands = [];

$query = "SELECT * from ASSIGNMENT.ao_film";
$result = mysqli_query($connect, $query);
while ($row = mysqli_fetch_assoc($result)) {
    array_push($films, $row);
}

$query = "SELECT * from ASSIGNMENT.ao_category";
$result = mysqli_query($connect, $query);
while ($row = mysqli_fetch_assoc($result)) {
    array_push($categories, $row);
}

$query = "SELECT * from ASSIGNMENT.ao_brand";
$result = mysqli_query($connect, $query);
while ($row = mysqli_fetch_assoc($result)) {
    array_push($brands, $row);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Items</title>
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

        .container {
            max-width: 960px;
        }

        .icon-link>.bi {
            width: .75em;
            height: .75em;
        }

        .product-device {
            position: absolute;
            right: 10%;
            bottom: -30%;
            width: 300px;
            height: 540px;
            background-color: #333;
            border-radius: 21px;
            transform: rotate(30deg);
        }

        .product-device::before {
            position: absolute;
            top: 10%;
            right: 10px;
            bottom: 10%;
            left: 10px;
            content: "";
            background-color: rgba(255, 255, 255, .1);
            border-radius: 5px;
        }

        .product-device-2 {
            top: -25%;
            right: auto;
            bottom: 0;
            left: 5%;
            background-color: #e5e5e5;
        }

        .flex-equal>* {
            flex: 1;
        }

        @media (min-width: 768px) {
            .flex-md-equal>* {
                flex: 1;
            }
        }

        a {
            text-decoration: none;
        }
    </style>
</head>


<body>
    <nav class="navbar navbar-expand-md bg-dark sticky-top border-bottom" data-bs-theme="dark">
        <div class="container">
            <div class="offcanvas offcanvas-end" tabindex="-1" id="#offcanvas" aria-labelledby="#offcanvasLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="#offcanvasLabel">Aperture</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav flex-grow-1 justify-content-between">
                        <li class="nav-item">
                            <a class="nav-link text-white" href="/">
                                <?= COMPANY_NAME ?>
                            </a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="/items">Items</a></li>
                        <li class="nav-item"><a class="nav-link" href="/cart">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:24px;height:24px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                </svg>
                            </a></li>
                        <li class="nav-item">
                            <div class="dropdown fs-6">
                                <button class="btn dropdown-toggle text-white " type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Welcome back, <?= $_SESSION['name'] ?>!
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="/logout">Log Out</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <main class="container mt-4">
        <div class="row">
            <div class="col-md-4">
                <div>
                    <h5>Film</h5>
                    <ul>
                        <?php foreach ($films as $film) : ?>
                            <li>
                                <a href="?film_id=<?= $film['film_id'] ?>"><?= $film['title'] ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <hr>
                <div>
                    <h5>Category</h5>
                    <ul>
                        <?php foreach ($categories as $category) : ?>
                            <li>
                                <a href="?category_id=<?= $category['category_id'] ?>"><?= $category['category_name'] ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <hr>
                <div>
                    <h5>Brand</h5>
                    <ul>
                        <?php foreach ($brands as $brand) : ?>
                            <li>
                                <a href="?brand_id=<?= $brand['brand_id'] ?>"><?= $brand['brand_name'] ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <div class="col-md-8">
                <form method="GET" action="/items">
                    <div class="mb-3 d-flex">
                        <div style="width:100%;">
                            <input type="search" class="form-control" placeholder="Search" name="search">
                        </div>
                        <div class="w-100" style="padding-left:10px;">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </div>
                </form>

                <!-- items -->
                <div class="row">
                    <div class="col-md-3">
                        <img style="width:100%;height:auto;" src="https://images.unsplash.com/photo-1705609397754-2b98f8197811?w=200&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHwxNHx8fGVufDB8fHx8fA%3D%3D" alt="">
                    </div>
                    <div class="col-md-5">
                        <p>
                            <a href="/item-details?id=1">
                                <span class="fw-bold">Andrei J Castanha</span>
                            </a>
                            <small>(2023)</small>
                        </p>
                        <div>
                            <span class="badge text-bg-primary">Flim 1</span>
                            <span class="badge text-bg-warning">Category 1</span>
                            <span class="badge text-bg-secondary">Brand 1</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <p class="fw-bold text-end">$ 2500</p>
                        <button class=" float-end btn btn-outline-primary">
                            Cart
                        </button>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-3">
                        <img style="width:100%;height:auto;" src="https://images.unsplash.com/photo-1682687982501-1e58ab814714?w=200&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDF8MHxlZGl0b3JpYWwtZmVlZHwxNnx8fGVufDB8fHx8fA%3D%3D" alt="">
                    </div>
                    <div class="col-md-5">
                        <p>
                            <a href="/item-details?id=2">
                                <span class="fw-bold">NEOM</span>
                            </a>
                            <small>(2016)</small>
                        </p>
                        <div>
                            <span class="badge text-bg-primary">Flim 2</span>
                            <span class="badge text-bg-warning">Category 1</span>
                            <span class="badge text-bg-secondary">Brand 4</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <p class="fw-bold text-end">$ 3500</p>
                        <button class=" float-end btn btn-outline-primary">
                            Cart
                        </button>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-3">
                        <img style="width:100%;height:auto;" src="https://images.unsplash.com/photo-1698051300591-8c8bb88e9851?w=200&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHwyM3x8fGVufDB8fHx8fA%3D%3D" alt="">
                    </div>
                    <div class="col-md-5">
                        <p>
                            <a href="/item-details?id=3">
                                <span class="fw-bold">Annie Spratt</span>
                            </a>
                            <small>(2016)</small>
                        </p>
                        <div>
                            <span class="badge text-bg-primary">Flim 4</span>
                            <span class="badge text-bg-warning">Category 3</span>
                            <span class="badge text-bg-secondary">Brand 2</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <p class="fw-bold text-end">$ 4500</p>
                        <button class=" float-end btn btn-outline-primary">
                            Cart
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="container py-5">
        <div class="row">
            <div class="col-12 col-md">
                <h5><?= COMPANY_NAME ?></h5>
                <p><b>Address</b><br>No.123, University Avenue Street,<br>Bahan Township, Yangon.</p>
                <p><b>Telephone</b><br>0968686868</p>
            </div>

            <div class="col-6 col-md">
                <div class="d-flex align-items-center justify-content-center">
                    <a class="m-1 btn btn-primary" href="#">
                        F
                    </a>
                    <a class="m-1 btn btn-primary" href="#">
                        G
                    </a>
                    <a class="m-1 btn btn-primary" href="#">
                        T
                    </a>
                </div>
                <div>
                    <small>Copyright &copy; <?= date('Y') ?>. All right reserved.</small>
                </div>
            </div>
        </div>
    </footer>

    <script src="/js/bootstrap.bundle.min.js"></script>
</body>

</html>