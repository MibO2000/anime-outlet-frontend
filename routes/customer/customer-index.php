<?php
if (($_SESSION['role'] ?? 0) !== ROLE_CUSTOMER) {
    session_destroy();
    header('Location: /login', true, 301);
    exit;
}

function getFilmList($connect)
{
    $film = sprintf("SELECT title, film_id FROM ao_film order by title");
    $result = $connect->query($film);
    $result = $result->fetch_all();
    return $result;
}

function getBrandList($connect)
{
    $brand = sprintf("SELECT brand_name, brand_id FROM ao_brand order by brand_name");
    $result = $connect->query($brand);
    $result = $result->fetch_all();
    return $result;
}

function getCategoryList($connect)
{
    $category = sprintf("SELECT category_name, category_id FROM ao_category order by category_name");
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon"
        href="https://scontent.frgn10-1.fna.fbcdn.net/v/t39.30808-6/273028440_4734065929980159_2213306540146619987_n.jpg?_nc_cat=104&ccb=1-7&_nc_sid=efb6e6&_nc_ohc=8DAVbr-s2rkAX8qzgRc&_nc_oc=AQlfsbZdD8sK9fExJlOIaeZQh576v7W5GFmAZ8yRDVlHm7EeL8UPY76iqfDuTlOwhPA&_nc_ht=scontent.frgn10-1.fna&oh=00_AfCkNdNmMA1O9LPRUo_CMwKAzytRNxHZWlGb4GQWmIRtZQ&oe=65B1794E">
    <style>
    .shadow {
        -webkit-box-shadow: 28px 14px 76px -36px rgba(0, 0, 0, 0.68);
        -moz-box-shadow: 28px 14px 76px -36px rgba(0, 0, 0, 0.68);
        box-shadow: 28px 14px 76px -36px rgba(0, 0, 0, 0.68);
    }

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

    .mt-10 {
        margin-top: 10px;
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
        <div class="container-md d-md-none">
            <a class="navbar-brand d-md-none mx-2" href="/">
                <?= COMPANY_NAME ?>
            </a>
            <button class="navbar-toggler" type="button"
                onclick="document.getElementById('navbars').classList.toggle('show')">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="navbar-collapse collapse" id="navbars">
                <ul class="navbar-nav me-auto mb-2">
                    <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="/items">Items</a></li>
                    <li class="nav-item position-relative">
                        <a class="nav-link" href="/cart">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" style="width:24px;height:24px;">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                <?php if (!empty($_SESSION['__cart'])) : ?>
                                <?php
                                    $totalQuantity = array_sum(array_column($_SESSION['__cart'], 'quantity'));
                                    ?>
                                <circle cx="18" cy="6" r="6" fill="#FF3333"></circle>
                                <text x="18" y="9" font-size="10" fill="white"
                                    text-anchor="middle"><?= $totalQuantity ?></text>
                                <?php endif; ?>
                            </svg>
                        </a>
                    </li>
                    <li class="nav-item">
                        <div class="dropdown fs-6">
                            <button class="btn dropdown-toggle text-white " type="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
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
        <div class="container-md">
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
                        <li class="nav-item position-relative">
                            <a class="nav-link" href="/cart">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" style="width:24px;height:24px;">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                    <?php if (!empty($_SESSION['__cart'])) : ?>
                                    <?php
                                        $totalQuantity = array_sum(array_column($_SESSION['__cart'], 'quantity'));
                                        ?>
                                    <circle cx="18" cy="6" r="6" fill="#FF3333"></circle>
                                    <text x="18" y="9" font-size="10" fill="white"
                                        text-anchor="middle"><?= $totalQuantity ?></text>
                                    <?php endif; ?>
                                </svg>
                            </a>
                        </li>

                        <li class="nav-item">
                            <div class="dropdown fs-6">
                                <button class="btn dropdown-toggle text-white " type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    Welcome back, <?= $_SESSION['name'] ?>!
                                </button>
                                <ul class="dropdown-menu position-relative">
                                    <li><a class="dropdown-item" href="/logout">Log Out</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <nav class="navbar navbar-expand-md bg-light border-bottom" data-bs-theme="light">
        <div class="container-md">
            <ul class="navbar-nav m-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="filmDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Film
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="filmDropdown">
                        <?php
                        $filmList = getFilmList($connect);
                        foreach ($filmList as $film) {
                            echo '<li><a class="dropdown-item" href="/items?film_id=' . urlencode($film[1]) . '">' . $film[0] . '</a></li>';
                        }
                        ?>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="categoryDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Category
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="categoryDropdown">
                        <?php
                        $categoryList = getCategoryList($connect);
                        foreach ($categoryList as $category) {
                            echo '<li><a class="dropdown-item" href="/items?category_id=' . urlencode($category[1]) . '">' . $category[0] . '</a></li>';
                        }
                        ?>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="brandDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Brand
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="brandDropdown">
                        <?php
                        $brandList = getBrandList($connect);
                        foreach ($brandList as $brand) {
                            echo '<li><a class="dropdown-item" href="/items?brand_id=' . urlencode($brand[1]) . '">' . $brand[0] . '</a></li>';
                        }
                        ?>
                    </ul>
                </li>
                <li class="nav-item">
                    <div class="col-md">
                        <form class="d-flex" role="search" method="GET" action="/items">
                            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                            <button class="btn btn-outline-success" type="submit">Search</button>
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <main class="container mt-4">
        <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active" data-bs-interval="5000">
                    <img src="https://images-wixmp-ed30a86b8c4ca887773594c2.wixmp.com/f/5298bac0-b8bf-4c80-af67-725c1272dbb0/dd55ja2-8ba5495d-00bf-4eff-91bf-8e55671aacb6.jpg?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJ1cm46YXBwOjdlMGQxODg5ODIyNjQzNzNhNWYwZDQxNWVhMGQyNmUwIiwiaXNzIjoidXJuOmFwcDo3ZTBkMTg4OTgyMjY0MzczYTVmMGQ0MTVlYTBkMjZlMCIsIm9iaiI6W1t7InBhdGgiOiJcL2ZcLzUyOThiYWMwLWI4YmYtNGM4MC1hZjY3LTcyNWMxMjcyZGJiMFwvZGQ1NWphMi04YmE1NDk1ZC0wMGJmLTRlZmYtOTFiZi04ZTU1NjcxYWFjYjYuanBnIn1dXSwiYXVkIjpbInVybjpzZXJ2aWNlOmZpbGUuZG93bmxvYWQiXX0.pr7o7qeeKM6hPBlKfErM8-gL7z7N0EnHy0vXbjNhvVg"
                        class="d-block w-100" alt="...">
                </div>
                <div class="carousel-item" data-bs-interval="5000">
                    <img src="https://r4.wallpaperflare.com/wallpaper/838/806/561/satoru-gojo-one-piece-hd-wallpaper-c940388dd1aa5dbb86d7680f5011465d.jpg"
                        class="d-block w-100" alt="...">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>


        <div class="row mt-10">
            <div class="col d-flex justify-content-center mx-5">
                <div class="card mb-3 shadow" style="max-width: 900px;">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img src="https://wallpaperaccess.com/full/3704375.jpg"
                                class="img-fluid rounded-start w-100" alt="..." style="width: 100%;">
                        </div>
                        <div class="col-md-8 d-flex align-items-center">
                            <div class="card-body">
                                <div>
                                    <p>Konoha is the first Otaku Store in Myanmar since 2013. You can get many things
                                        related from Anime
                                        and Comic.

                                        Since 2014, our Konoha Hobby House shop has been the first in Myanmar to
                                        exclusively sell anime and
                                        comic related items. Thank you very much to each and every one of you who have
                                        bought and supported
                                        us so far.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-10">
            <div class="col d-flex justify-content-center mx-5">
                <div class="card mb-3 shadow" style="max-width: 900px;">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img src="https://wallpaperaccess.com/full/10989863.jpg"
                                class="img-fluid rounded-start w-100" alt="..." style="width: 100%;">
                        </div>
                        <div class="col-md-8 d-flex align-items-center">
                            <div class="card-body">
                                <div>
                                    <p>Konoha Hobby House emphasizes its commitment to customer satisfaction through its
                                        Shipping & After
                                        Sale Policy, ensuring a seamless purchasing experience. The policy covers
                                        various services and
                                        highlights the store's dedication to delivering quality products to its
                                        customers.

                                        In response to the global situation, the store encourages customers to stay at
                                        home and explore its
                                        offerings through online platforms. It provides links to various official
                                        websites and third-party
                                        sources, making it convenient for customers to access a diverse range of
                                        products.

                                        Overall, Konoha Hobby House has become a go-to destination for collectors and
                                        enthusiasts, offering
                                        a comprehensive selection of merchandise and prioritizing customer experience
                                        through its policies
                                        and collaborations.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>

        <div class=" d-flex mt-4 justify-content-center">
            <div class="mt-4 row">
                <div class="col-md-6 px-5">
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
                                <div
                                    style="margin-top:20px;display:flex;align-items:center;justify-content:center;width:100%">
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-6 px-5">
                    <div style="width: 100%; border-radius: 20px"><iframe width="100%" height="300" frameborder="0"
                            scrolling="no" marginheight="0" marginwidth="0"
                            src="https://maps.google.com/maps?width=100%25&amp;height=600&amp;hl=en&amp;q=Konoha%20Hobby%20House+(My%20Business%20Nam)&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"><a
                                href="https://www.maps.ie/population/">Population calculator map</a></iframe></div>
                </div>
            </div>
        </div>

    </main>

    <hr>

    <footer class="container py-5">
        <div class="row">
            <div class="col-6 col-md">
                <h5><?= COMPANY_NAME ?></h5>
                <p><b>Address</b><br>Shwe Gon Taing Rd, Yangon, Myanmar (Burma)</p>
                <p><b>Telephone</b><br>095199145</p>
            </div>

            <div class="col-6 col-md">
                <div class="d-flex align-items-center justify-content-center">
                    <a class="m-1 btn btn-primary" href="https://www.facebook.com">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-facebook" viewBox="0 0 16 16">
                            <path
                                d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951" />
                        </svg>
                    </a>
                    <a class="m-1 btn btn-primary" href="https://www.pintrest.com">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-pinterest" viewBox="0 0 16 16">
                            <path
                                d="M8 0a8 8 0 0 0-2.915 15.452c-.07-.633-.134-1.606.027-2.297.146-.625.938-3.977.938-3.977s-.239-.479-.239-1.187c0-1.113.645-1.943 1.448-1.943.682 0 1.012.512 1.012 1.127 0 .686-.437 1.712-.663 2.663-.188.796.4 1.446 1.185 1.446 1.422 0 2.515-1.5 2.515-3.664 0-1.915-1.377-3.254-3.342-3.254-2.276 0-3.612 1.707-3.612 3.471 0 .688.265 1.425.595 1.826a.24.24 0 0 1 .056.23c-.061.252-.196.796-.222.907-.035.146-.116.177-.268.107-1-.465-1.624-1.926-1.624-3.1 0-2.523 1.834-4.84 5.286-4.84 2.775 0 4.932 1.977 4.932 4.62 0 2.757-1.739 4.976-4.151 4.976-.811 0-1.573-.421-1.834-.919l-.498 1.902c-.181.695-.669 1.566-.995 2.097A8 8 0 1 0 8 0" />
                        </svg>
                    </a>
                    <a class="m-1 btn btn-primary" href="https://twitter.com">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-twitter-x" viewBox="0 0 16 16">
                            <path
                                d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865z" />
                        </svg>
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