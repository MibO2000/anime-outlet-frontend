<?php
if (($_SESSION['role'] ?? 0) !== ROLE_CUSTOMER) {
    session_destroy();
    header('Location: /login', true, 301);
    exit;
}

function getFilmList($connect)
{
    $film = sprintf("SELECT title FROM ao_film order by title");
    $result = $connect->query($film);
    $result = $result->fetch_all();
    return $result;
}

function getBrandList($connect)
{
    $brand = sprintf("SELECT brand_name FROM ao_brand order by brand_name");
    $result = $connect->query($brand);
    $result = $result->fetch_all();
    return $result;
}

function getCategoryList($connect)
{
    $category = sprintf("SELECT category_name FROM ao_category order by category_name");
    $result = $connect->query($category);
    $result = $result->fetch_all();
    return $result;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="icon" type="image/x-icon" href="https://scontent.frgn10-1.fna.fbcdn.net/v/t39.30808-6/273028440_4734065929980159_2213306540146619987_n.jpg?_nc_cat=104&ccb=1-7&_nc_sid=efb6e6&_nc_ohc=8DAVbr-s2rkAX8qzgRc&_nc_oc=AQlfsbZdD8sK9fExJlOIaeZQh576v7W5GFmAZ8yRDVlHm7EeL8UPY76iqfDuTlOwhPA&_nc_ht=scontent.frgn10-1.fna&oh=00_AfCkNdNmMA1O9LPRUo_CMwKAzytRNxHZWlGb4GQWmIRtZQ&oe=65B1794E">
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

        .max-w-4 {
            max-width: 300px;
            height: auto;
        }

        .w-100 {
            width: 100%;
            height: auto;
        }

        @media (min-width: 768px) {
            .flex-md-equal>* {
                flex: 1;
            }
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
        <div>
            <form class="d-flex" role="search" method="GET" action="/items">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
        </div>

        <div class="d-flex flex-row mt-4">
            <div>
                <img class="rounded float-start img-fluid" src="https://wallpaperaccess.com/full/3704375.jpg">
            </div>
            <div>
                <p>
                    Konoha is the first Otaku Store in Myanmar since 2013. You can get many things related from Anime
                    and Comic.

                    Since 2014, our Konoha Hobby House shop has been the first in Myanmar to exclusively sell anime and
                    comic related items. Thank you very much to each and every one of you who have bought and supported
                    us so far.
                </p>
            </div>
        </div>
        <div class="d-flex flex-row mt-4">
            <div>
                <p>
                    The Konoha Hobby House, established in 2014, is a prominent hub in Myanmar for anime, comics, and
                    related merchandise. Renowned for its diverse selection, including official figures, bootlegs, and
                    third-party products, we cater to collectors and fans of various genres.

                    We also provide a wide range of items such as Gundam model kits, official Bandai products,
                    action figures, keychains, T-shirts, hoodies, and more. They collaborate with Zen Creations Studio
                    and offer a platform for customers to access figures from well-known studios like Iron Studio and
                    Prime 1 Studio.
                </p>
            </div>
            <div class="m-2">
                <img class="rounded float-end img-fluid" src="https://wallpaperaccess.com/full/3704384.jpg">
            </div>
        </div>
        <div class="d-flex flex-row mt-4">
            <div class="m-2">
                <img class="rounded float-end img-fluid" src="https://wallpaperaccess.com/full/10989863.jpg">
            </div>
            <div>
                <p>
                    Konoha Hobby House emphasizes its commitment to customer satisfaction through its Shipping & After
                    Sale Policy, ensuring a seamless purchasing experience. The policy covers various services and
                    highlights the store's dedication to delivering quality products to its customers.

                    In response to the global situation, the store encourages customers to stay at home and explore its
                    offerings through online platforms. It provides links to various official websites and third-party
                    sources, making it convenient for customers to access a diverse range of products.

                    Overall, Konoha Hobby House has become a go-to destination for collectors and enthusiasts, offering
                    a comprehensive selection of merchandise and prioritizing customer experience through its policies
                    and collaborations.
                </p>
            </div>

        </div>
        <div class="d-flex flex-row justify-content-around mt-4">
            <div class="mt-4">
                <h2>Contact Us</h2>

                <form method="POST">
                    <div class="row">
                        <div class="col">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div style="margin-top:20px;display:flex;align-items:center;justify-content:center;width:100%">
                                <button class="btn btn-primary" type="submit">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="max-w-4">
                <img class="rounded float-start img-fluid w-100" src="https://w0.peakpx.com/wallpaper/73/844/HD-wallpaper-wano-flower-capital-flower-capital-luffy-one-piece.jpg">
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
                    <p><a href="https://www.facebook.com/523016474418480/posts/3255076131212487/">Terms and
                            Conditions</a></p>
                    <small>Copyright &copy; <?= date('Y') ?>. All right reserved.</small>
                </div>
            </div>
        </div>
    </footer>

    <script src="/js/bootstrap.bundle.min.js"></script>
</body>

</html>