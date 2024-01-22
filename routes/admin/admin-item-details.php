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
    $result = $connect->query($checkquery);
    $result = $result->fetch_all();
    if ($result) {
        $hasError = 1;
        $errorMessage = 'Title already exist.';
        echo $errorMessage;
        die;
    } else {
        $query = sprintf("INSERT INTO ao_film(film_id, title, film_image, release_date, film_description)
        VALUES ('%s','%s','%s','%s','%s')", mysqli_real_escape_string($connect, $fid), mysqli_real_escape_string($connect, $ftitle), mysqli_real_escape_string($connect, $image), mysqli_real_escape_string($connect, $freleasedate), mysqli_real_escape_string($connect, $fdesc));
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
    $result = $connect->query($checkquery);
    $result = $result->fetch_all();
    if ($result) {
        $hasError = 1;
        $errorMessage = 'Name already exist.';
        echo $errorMessage;
        die;
    } else {
        echo  $query = sprintf("INSERT INTO ao_category(category_id, category_name, category_image, category_description)
        VALUES ('%s','%s','%s','%s')", mysqli_real_escape_string($connect, $cid), mysqli_real_escape_string($connect, $cname), mysqli_real_escape_string($connect, $image), mysqli_real_escape_string($connect, $cdesc));
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
    $result = $connect->query($checkquery);
    $result = $result->fetch_all();
    if ($result) {
        $hasError = 1;
        $errorMessage = 'Name already exist.';
        echo $errorMessage;
        die;
    } else {
        $query = sprintf("INSERT INTO ao_brand(brand_id, brand_name, brand_image, brand_description)
        VALUES ('%s','%s','%s','%s')", mysqli_real_escape_string($connect, $bid), mysqli_real_escape_string($connect, $bname), mysqli_real_escape_string($connect, $image), mysqli_real_escape_string($connect, $bdesc));
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
        echo $errorMessage;
        die;
    }

    $ftitle = $_POST['fname'];
    $freleasedate = $_POST['freleasedate'];
    $fdesc = $_POST['fdescription'];
    $image = $_POST['fimage'];

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

if (isset($_POST['btn-category-update'])) {
    $cid = $_POST['category_id'];

    $check_query = sprintf("SELECT * FROM ao_category WHERE category_id = '%s'", mysqli_real_escape_string($connect, $cid));
    $result = $connect->query($check_query);
    $result = $result->fetch_all();

    if (!$result) {
        $hasError = 1;
        $errorMessage = 'Category ID does not exist.';
        echo $errorMessage;
        die;
    }

    $cname = $_POST['cname'];
    $cdesc = $_POST['cdescription'];
    $image = $_POST['cimage'];

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

if (isset($_POST['btn-brand-update'])) {
    $bid = $_POST['brand_id'];

    $check_query = sprintf("SELECT * FROM ao_brand WHERE brand_id = '%s'", mysqli_real_escape_string($connect, $bid));
    $result = $connect->query($check_query);
    $result = $result->fetch_all();

    if (!$result) {
        $hasError = 1;
        $errorMessage = 'Brand ID does not exist.';
        echo $errorMessage;
        die;
    }

    $bname = $_POST['bname'];
    $bdesc = $_POST['bdescription'];
    $image = $_POST['bimage'];

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
    <title>Admin Order</title>
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

    <div class="container-fluid" id="main">
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
                    <div class="d-flex justify-content-between align-items-center">
                        <h1>Film</h1>
                        <button class="btn btn-primary" @click="createItem()" data-bs-toggle="modal" data-bs-target="#editFilmModal">Create</button>
                    </div>
                    <table class="table table-responsive table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Film ID</th>
                                <th>Title</th>
                                <th>Film Image</th>
                                <th>Release Date</th>
                                <th>Description</th>
                                <th>Option</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="film in films">
                                <td>{{film.film_id}}</td>
                                <td>{{film.title}}</td>
                                <td>{{film.film_image}}</td>
                                <td>{{film.release_date}}</td>
                                <td>{{film.film_description}}</td>
                                <td>
                                    <button class="btn btn-primary btn-sm" @click="selectItem(film,'FILM')" data-bs-toggle="modal" data-bs-target="#editFilmModal">Edit</button>
                                    <button class="btn btn-danger btn-sm" @click="selectItem(film,'FILM')" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <br>
                <div>
                    <div class="d-flex justify-content-between align-items-center">
                        <h1>Category</h1>
                        <button class="btn btn-primary" @click="createItem()" data-bs-toggle="modal" data-bs-target="#editCategoryModal">Create</button>
                    </div>
                    <table class="table table-responsive table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Category ID</th>
                                <th>Category Nam</th>
                                <th>Category Image</th>
                                <th>Category Description</th>
                                <th>Option</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="category in categories">
                                <td>{{category.category_id}}</td>
                                <td>{{category.category_name}}</td>
                                <td>{{category.category_image}}</td>
                                <td>{{category.category_description}}</td>
                                <td>
                                    <button class="btn btn-primary btn-sm" @click="selectItem(category,'CATEGORY')" data-bs-toggle="modal" data-bs-target="#editCategoryModal">Edit</button>
                                    <button class="btn btn-danger btn-sm" @click="selectItem(category,'CATEGORY')" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <br>
                <div>
                    <div class="d-flex justify-content-between align-items-center">
                        <h1>Brand</h1>
                        <button class="btn btn-primary" @click="createItem()" data-bs-toggle="modal" data-bs-target="#editBrandModal">Create</button>
                    </div>
                    <table class="table table-responsive table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Brand ID</th>
                                <th>Brand Name</th>
                                <th>Brand Image</th>
                                <th>Brand Description</th>
                                <th>Option</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="brand in brands">
                                <td>{{brand.brand_id}}</td>
                                <td>{{brand.brand_name}}</td>
                                <td>{{brand.brand_image}}</td>
                                <td>{{brand.brand_description}}</td>
                                <td>
                                    <button class="btn btn-primary btn-sm" @click="selectItem(brand,'BRAND')" data-bs-toggle="modal" data-bs-target="#editBrandModal">Edit</button>
                                    <button class="btn btn-danger btn-sm" @click="selectItem(brand,'BRAND')" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>

        <div class="modal modal-lg fade" tabindex="-1" id="editFilmModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Film</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" v-model="selectedItem.title" />
                        </div>
                        <div>
                            <label for="description" class="form-label">Description</label>
                            <input type="text" class="form-control" id="category" v-model="selectedItem.film_description" />
                        </div>
                        <div>
                            <label for="image" class="form-label">Image</label>
                            <input type="url" class="form-control" id="image" v-model="selectedItem.film_image" />
                        </div>
                        <div>
                            <label for="release_date" class="form-label">Released Date</label>
                            <input type="date" class="form-control" id="release_date" v-model="selectedItem.release_date" />
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Close
                            </button>
                            <button type="button" class="btn btn-primary" @click="submitCreateOrEditFilm()">
                                Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal modal-lg fade" tabindex="-1" id="editCategoryModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" v-model="selectedItem.category_name" />
                        </div>
                        <div>
                            <label for="description" class="form-label">Description</label>
                            <input type="text" class="form-control" id="category" v-model="selectedItem.category_description" />
                        </div>
                        <div>
                            <label for="image" class="form-label">Image</label>
                            <input type="url" class="form-control" id="image" v-model="selectedItem.category_image" />
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Close
                            </button>
                            <button type="button" class="btn btn-primary" @click="submitCreateOrEditCategory()">
                                Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal modal-lg fade" tabindex="-1" id="editBrandModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Brand</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <label for="title" class="form-label">Name</label>
                            <input type="text" class="form-control" id="title" v-model="selectedItem.brand_name" />
                        </div>
                        <div>
                            <label for="description" class="form-label">Description</label>
                            <input type="text" class="form-control" id="category" v-model="selectedItem.brand_description" />
                        </div>
                        <div>
                            <label for="image" class="form-label">Image</label>
                            <input type="url" class="form-control" id="image" v-model="selectedItem.brand_image" />
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Close
                            </button>
                            <button type="button" class="btn btn-primary" @click="submitCreateOrEditBrand()">
                                Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" tabindex="-1" id="deleteModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Are you sure do you want to delete?</h5>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal" @click="confirmDelete()">
                            Confirm
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/js/bootstrap.bundle.min.js"></script>
    <script src="/js/vue.min.js"></script>
    <script src="/js/axios.min.js"></script>
    <script>
        new Vue({
            el: '#main',
            data: {
                films: <?= json_encode($films) ?>,
                categories: <?= json_encode($categories) ?>,
                brands: <?= json_encode($brands) ?>,
                selectedItem: {},
                selectedType: '',
            },
            methods: {
                createItem() {
                    this.selectedItem = {};
                    this.selectedType = ''
                },
                selectItem(item, type) {
                    this.selectedItem = {
                        ...item
                    };
                    this.selectedType = type;
                },
                confirmDelete() {
                    let formData = new FormData();
                    switch (this.selectedType) {
                        case 'FILM':
                            formData.append('btn-film-delete', 1);
                            formData.append('film_id', this.selectedItem.film_id);
                            break;
                        case 'CATEGORY':
                            formData.append('btn-category-delete', 1);
                            formData.append('category_id', this.selectedItem.category_id);
                            break;
                        case 'BRAND':
                            formData.append('btn-brand-delete', 1);
                            formData.append('brand_id', this.selectedItem.brand_id);
                            break;
                    }
                    axios.post('', formData).then(() => location.reload());
                },
                submitCreateOrEditFilm() {
                    if (this.selectedType === '') {
                        let data = new FormData();
                        data.append('btn-film-save', 1);
                        data.append('fname', this.selectedItem.title);
                        data.append('freleasedate', this.selectedItem.release_date);
                        data.append('fdescription', this.selectedItem.film_description);
                        data.append('fimage', this.selectedItem.film_image);
                        axios.post('', data).then((res) => location.reload());
                    } else {
                        let data = new FormData();
                        data.append('btn-film-update', 1);
                        data.append('film_id', this.selectedItem.film_id);
                        data.append('fname', this.selectedItem.title);
                        data.append('freleasedate', this.selectedItem.release_date);
                        data.append('fdescription', this.selectedItem.film_description);
                        data.append('fimage', this.selectedItem.film_image);
                        axios.post('', data).then((res) => location.reload());
                    }
                },
                submitCreateOrEditCategory() {
                    if (this.selectedType === '') {
                        let data = new FormData();
                        data.append('btn-category-save', 1);
                        data.append('cname', this.selectedItem.category_name);
                        data.append('cdescription', this.selectedItem.category_description);
                        data.append('cimage', this.selectedItem.category_image);
                        axios.post('', data).then((res) => location.reload());
                    } else {
                        let data = new FormData();
                        data.append('btn-category-update', 1);
                        data.append('category_id', this.selectedItem.category_id);
                        data.append('cname', this.selectedItem.category_name);
                        data.append('cdescription', this.selectedItem.category_description);
                        data.append('cimage', this.selectedItem.category_image);
                        axios.post('', data).then((res) => location.reload());
                    }
                },
                submitCreateOrEditBrand() {
                    if (this.selectedType === '') {
                        let data = new FormData();
                        data.append('btn-brand-save', 1);
                        data.append('bname', this.selectedItem.brand_name);
                        data.append('bdescription', this.selectedItem.brand_description);
                        data.append('bimage', this.selectedItem.brand_image);
                        axios.post('', data).then((res) => location.reload());
                    } else {
                        let data = new FormData();
                        data.append('btn-brand-update', 1);
                        data.append('brand_id', this.selectedItem.brand_id);
                        data.append('bname', this.selectedItem.brand_name);
                        data.append('bdescription', this.selectedItem.brand_description);
                        data.append('bimage', this.selectedItem.brand_image);
                        axios.post('', data).then((res) => location.reload());
                    }
                },
            },

        });
    </script>
</body>

</html>