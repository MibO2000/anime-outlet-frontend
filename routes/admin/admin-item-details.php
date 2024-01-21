<?php
if (($_SESSION['role'] ?? 0) !== ROLE_ADMIN) {
    session_destroy();
    header('Location: /admin-login', true, 301);
    exit;
}
if (isset($_POST['btn-film-save'])) {
    $fid = AutoID('ao_film', 'film_id', 'F', 4);
    $ftitle = $_POST['fname'];
    $freleasedate = $_POST['freleasedate'];
    $fdesc = $_POST['fdescription'];
    $image = $_POST['fimage'];

    $checkquery = sprintf("SELECT * from ao_film where title = '%s'", mysqli_real_escape_string($connect, $ftitle));
    $result = $connect->query($query);
    $result = $result->fetch_all();
    if ($result) {
        $hasError = 1;
        $errorMessage = 'Title already exist.';
    } else {
        $query = sprintf("INSERT INTO ao_film(film_id, title, film_image, release_date, film_description)
        VALUES '%s','%s','%s','%s','%s'", mysqli_real_escape_string($connect, $fid), mysqli_real_escape_string($connect, $ftitle), mysqli_real_escape_string($connect, $image), mysqli_real_escape_string($connect, $freleasedate), mysqli_real_escape_string($connect, $fdesc));
        $connect->query($query);
        // SUCCESS
        header('Location: /admin-item-details');
    }
}

if (isset($_POST['btn-category-save'])) {
    $cid = AutoID('ao_category', 'category_id', 'C', 4);
    $cname = $_POST['cname'];
    $cdesc = $_POST['cdescription'];
    $image = $_POST['cimage'];

    $checkquery = sprintf("SELECT * from ao_category where category_name = '%s'", mysqli_real_escape_string($connect, $cname));
    $result = $connect->query($query);
    $result = $result->fetch_all();
    if ($result) {
        $hasError = 1;
        $errorMessage = 'Name already exist.';
    } else {
        $query = sprintf("INSERT INTO ao_category(category_id, category_name, category_image, category_description)
        VALUES '%s','%s','%s','%s'", mysqli_real_escape_string($connect, $cid), mysqli_real_escape_string($connect, $cname), mysqli_real_escape_string($connect, $image), mysqli_real_escape_string($connect, $cdesc));
        $connect->query($query);
        // SUCCESS
        header('Location: /admin-item-details');
    }
}

if (isset($_POST['btn-brand-save'])) {
    $bid = AutoID('ao_brand', 'brand_id', 'B', 4);
    $bname = $_POST['bname'];
    $bdesc = $_POST['bdescription'];
    $image = $_POST['bimage'];

    $checkquery = sprintf("SELECT * from ao_brand where brand_name = '%s'", mysqli_real_escape_string($connect, $bname));
    $result = $connect->query($query);
    $result = $result->fetch_all();
    if ($result) {
        $hasError = 1;
        $errorMessage = 'Name already exist.';
    } else {
        $query = sprintf("INSERT INTO ao_brand(brand_id, brand_name, brand_image, brand_description)
        VALUES '%s','%s','%s','%s'", mysqli_real_escape_string($connect, $bid), mysqli_real_escape_string($connect, $bname), mysqli_real_escape_string($connect, $image), mysqli_real_escape_string($connect, $bdesc));
        $connect->query($query);
        // SUCCESS
        header('Location: /admin-item-details');
    }
}

// update

if (isset($_POST['btn-film-update'])) {
    $fid = $_POST['film_id'];

    $check_query = sprintf("SELECT * FROM ao_film WHERE film_id = '%s'", mysqli_real_escape_string($connect, $fid));
    $result = $connect->query($check_query);
    $result = $result->fetch_all();

    if (!$result) {
        $hasError = 1;
        $errorMessage = 'Film ID does not exist.';
        header('Location: /admin-item-details');
    }

    $ftitle = $_POST['fname'];
    $freleasedate = $_POST['freleasedate'];
    $fdesc = $_POST['fdescription'];
    $image = $_POST['fimage'];

    $checkquery = sprintf("SELECT * from ao_film where title = '%s' and film_id = '%s'", mysqli_real_escape_string($connect, $ftitle), mysqli_real_escape_string($connect, $fid));
    $result = $connect->query($query);
    $result = $result->fetch_all();
    if ($result) {
        $hasError = 1;
        $errorMessage = 'Title already exist.';
    } else {
        $update_query = sprintf(
            "UPDATE ao_film
            SET title = '%s',
                film_image = '%s',
                release_date = '%s',
                film_description = '%s'
            WHERE film_id = '%s'",
            mysqli_real_escape_string($connect, $ftitle),
            mysqli_real_escape_string($connect, $image),
            mysqli_real_escape_string($connect, $freleasedate),
            mysqli_real_escape_string($connect, $fdesc),
            mysqli_real_escape_string($connect, $fid)
        );

        $connect->query($update_query);

        // SUCCESS
        header('Location: /admin-item-details');
    }
}

if (isset($_POST['btn-category-update'])) {
    $cid = $_POST['category_id'];

    $check_query = sprintf("SELECT * FROM ao_category WHERE category_id = '%s'", mysqli_real_escape_string($connect, $cid));
    $result = $connect->query($check_query);
    $result = $result->fetch_all();

    if (!$result) {
        $hasError = 1;
        $errorMessage = 'Category ID does not exist.';
        header('Location: /admin-item-details');
    }

    $cname = $_POST['cname'];
    $cdesc = $_POST['cdescription'];
    $image = $_POST['cimage'];

    $check_query = sprintf("SELECT * FROM ao_category WHERE category_name = '%s' AND category_id != '%s'", mysqli_real_escape_string($connect, $cname), mysqli_real_escape_string($connect, $cid));
    $result = $connect->query($check_query);
    $result = $result->fetch_all();
    if ($result) {
        $hasError = 1;
        $errorMessage = 'Category name already exists.';
    } else {
        $update_query = sprintf(
            "UPDATE ao_category
            SET category_name = '%s',
                category_image = '%s',
                category_description = '%s'
            WHERE category_id = '%s'",
            mysqli_real_escape_string($connect, $cname),
            mysqli_real_escape_string($connect, $image),
            mysqli_real_escape_string($connect, $cdesc),
            mysqli_real_escape_string($connect, $cid)
        );

        $connect->query($update_query);

        // SUCCESS
        header('Location: /admin-item-details');
    }
}

if (isset($_POST['btn-brand-update'])) {
    $bid = $_POST['brand_id'];

    $check_query = sprintf("SELECT * FROM ao_brand WHERE brand_id = '%s'", mysqli_real_escape_string($connect, $bid));
    $result = $connect->query($check_query);
    $result = $result->fetch_all();

    if (!$result) {
        $hasError = 1;
        $errorMessage = 'Brand ID does not exist.';
        header('Location: /admin-item-details');
    }

    $bname = $_POST['bname'];
    $bdesc = $_POST['bdescription'];
    $image = $_POST['bimage'];

    $check_query = sprintf("SELECT * FROM ao_brand WHERE brand_name = '%s' AND brand_id != '%s'", mysqli_real_escape_string($connect, $bname), mysqli_real_escape_string($connect, $bid));
    $result = $connect->query($check_query);
    $result = $result->fetch_all();
    if ($result) {
        $hasError = 1;
        $errorMessage = 'Brand name already exists.';
    } else {
        $update_query = sprintf(
            "UPDATE ao_brand
            SET brand_name = '%s',
                brand_image = '%s',
                brand_description = '%s'
            WHERE brand_id = '%s'",
            mysqli_real_escape_string($connect, $bname),
            mysqli_real_escape_string($connect, $image),
            mysqli_real_escape_string($connect, $bdesc),
            mysqli_real_escape_string($connect, $bid)
        );

        $connect->query($update_query);

        // SUCCESS
        header('Location: /admin-item-details');
    }
}

// delete
if (isset($_POST['btn-film-delete'])) {
    $fid = $_POST['film_id'];

    $check_query = sprintf("SELECT * FROM ao_film WHERE film_id = '%s'", mysqli_real_escape_string($connect, $fid));
    $result = $connect->query($check_query);
    $result = $result->fetch_all();

    if (!$result) {
        $hasError = 1;
        $errorMessage = 'Film ID does not exist.';
        header('Location: /admin-item-details');
    } else {
        $delete_query = sprintf("DELETE FROM ao_film WHERE film_id = '%s'", mysqli_real_escape_string($connect, $fid));
        $connect->query($delete_query);

        // SUCCESS
        header('Location: /admin-item-details');
    }
}
if (isset($_POST['btn-category-delete'])) {
    $cid = $_POST['category_id'];

    $check_query = sprintf("SELECT * FROM ao_category WHERE category_id = '%s'", mysqli_real_escape_string($connect, $cid));
    $result = $connect->query($check_query);
    $result = $result->fetch_all();

    if (!$result) {
        $hasError = 1;
        $errorMessage = 'Category ID does not exist.';
        header('Location: /admin-item-details');
    } else {
        $delete_query = sprintf("DELETE FROM ao_category WHERE category_id = '%s'", mysqli_real_escape_string($connect, $cid));
        $connect->query($delete_query);

        // SUCCESS
        header('Location: /admin-item-details');
    }
}
if (isset($_POST['btn-brand-delete'])) {
    $bid = $_POST['brand_id'];

    $check_query = sprintf("SELECT * FROM ao_brand WHERE brand_id = '%s'", mysqli_real_escape_string($connect, $bid));
    $result = $connect->query($check_query);
    $result = $result->fetch_all();

    if (!$result) {
        $hasError = 1;
        $errorMessage = 'Brand ID does not exist.';
        header('Location: /admin-item-details');
    } else {
        $delete_query = sprintf("DELETE FROM ao_brand WHERE brand_id = '%s'", mysqli_real_escape_string($connect, $bid));
        $connect->query($delete_query);

        // SUCCESS
        header('Location: /admin-item-details');
    }
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Order</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
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

        .bi {
            display: inline-block;
            width: 1rem;
            height: 1rem;
        }

        /*
 * Sidebar
 */

        @media (min-width: 768px) {
            .sidebar .offcanvas-lg {
                position: -webkit-sticky;
                position: sticky;
                top: 48px;
            }

            .navbar-search {
                display: block;
            }
        }

        .sidebar .nav-link {
            font-size: .875rem;
            font-weight: 500;
        }

        .sidebar .nav-link.active {
            color: #2470dc;
        }

        .sidebar-heading {
            font-size: .75rem;
        }

        .navbar-brand {
            padding-top: .75rem;
            padding-bottom: .75rem;
            background-color: rgba(0, 0, 0, .25);
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .25);
        }

        .navbar .form-control {
            padding: .75rem 1rem;
        }
    </style>
</head>

<body>
    <header class="navbar bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6 text-white" href="/admin-items"><?= COMPANY_NAME ?></a>
        <div class="mx-4">
            <div class="dropdown fs-6">
                <button class="btn dropdown-toggle text-white " type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Welcome back, <?= $_SESSION['name'] ?>!
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="/logout">Log Out</a></li>
                </ul>
            </div>
        </div>
    </header>

    <div class="container-fluid">
        <div class="row">
            <div class="sidebar border border-right col-md-3 col-lg-2 p-0 bg-body-tertiary">
                <div class="offcanvas-md offcanvas-end bg-body-tertiary" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel">
                    <div class="offcanvas-body d-md-flex flex-column p-0 pt-lg-3 overflow-y-auto">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2 active" aria-current="page" href="/admin-items">
                                    Items
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2 active" aria-current="page" href="/admin-item-details">
                                    Item Details
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2 active" aria-current="page" href="/admin-supplier">
                                    Supplier
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2 active" aria-current="page" href="/admin-deliverer">
                                    Deliverer
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2 active" aria-current="page" href="/admin-order">
                                    Order
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2 active" aria-current="page" href="/admin-purchase">
                                    Purchase
                                </a>
                            </li>
                        </ul>

                    </div>
                </div>
            </div>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" style="min-height:100vh">

                <div>
                    <h1>Film</h1>
                    <table class="table table-responsive table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Film ID</th>
                                <th>Title</th>
                                <th>Film Image</th>
                                <th>Release Date</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            $query = "SELECT * from ASSIGNMENT.ao_film";
                            $result = mysqli_query($connect, $query);

                            // Loop through each row and display the data
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . $row['film_id'] . "</td>";
                                echo "<td>" . $row['title'] . "</td>";
                                echo "<td>" . $row['film_image'] . "</td>";
                                echo "<td>" . $row['release_date'] . "</td>";
                                echo "<td>" . $row['film_description'] . "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div>
                    <h1>Category</h1>
                    <table class="table table-responsive table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Category ID</th>
                                <th>Category Nam</th>
                                <th>Category Image</th>
                                <th>Category Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            $query = "SELECT * from ASSIGNMENT.ao_category";
                            $result = mysqli_query($connect, $query);

                            // Loop through each row and display the data
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . $row['category_id'] . "</td>";
                                echo "<td>" . $row['category_name'] . "</td>";
                                echo "<td>" . $row['category_image'] . "</td>";
                                echo "<td>" . $row['category_description'] . "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div>
                    <h1>Brand</h1>
                    <table class="table table-responsive table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Brand ID</th>
                                <th>Brand Nam</th>
                                <th>Brand Image</th>
                                <th>Brand Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            $query = "SELECT * from ASSIGNMENT.ao_brand";
                            $result = mysqli_query($connect, $query);

                            // Loop through each row and display the data
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . $row['brand_id'] . "</td>";
                                echo "<td>" . $row['brand_name'] . "</td>";
                                echo "<td>" . $row['brand_image'] . "</td>";
                                echo "<td>" . $row['brand_description'] . "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

            </main>
        </div>
    </div>

    <script src="/js/bootstrap.bundle.min.js"></script>
</body>

</html>