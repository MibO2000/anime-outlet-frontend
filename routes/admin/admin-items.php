<?php
if (($_SESSION['role'] ?? 0) !== ROLE_ADMIN) {
    session_destroy();
    header('Location: /admin-login', true, 301);
    exit;
}

if (isset($_POST['btn-item-purchase'])) {
    if (!isset($_SESSION['__purchase'])) {
        $_SESSION['__purchase'] = [];
    }
    $id = $_POST['id'];
    $checkquery = sprintf("SELECT item_name, price, item_image_1 from ao_item where item_id = '%s'", mysqli_real_escape_string($connect, $id));
    $result = $connect->query($checkquery);
    $result = $result->fetch_all();
    $item = $result[0];
    $name = $item['0'];
    $price = $item['1'];
    $image = $item['2'];
    if (!key_exists($id, $_SESSION['__purchase'])) {
        $_SESSION['__purchase'][$id] = ['id' => $id, 'name' => $name, 'price' => $price, 'image' => $image, 'quantity' => 0];
    }
    $_SESSION['__purchase'][$id]['quantity'] += 1;
}

if (isset($_POST['btn-item-save'])) {
    $iid = AutoID('ao_item', 'item_id', 'I', 4);

    $ftitle = $_POST['title'];
    $film = sprintf("SELECT film_id FROM ao_film where title = '%s'", mysqli_real_escape_string($connect, $ftitle));
    $result = $connect->query($film);
    $result = $result->fetch_all();
    $ifilm = $result[0][0];

    $cname = $_POST['category'];
    $category = sprintf("SELECT category_id FROM ao_category where category_name = '%s'", mysqli_real_escape_string($connect, $cname));
    $result = $connect->query($category);
    $result = $result->fetch_all();

    $icategory = $result[0][0];

    $bname = $_POST['brand'];
    $brand = sprintf("SELECT brand_id FROM ao_brand where brand_name = '%s'", mysqli_real_escape_string($connect, $bname));
    $result = $connect->query($brand);
    $result = $result->fetch_all();

    $ibrand = $result[0][0];

    $iname = $_POST['name'];
    $image1 = $_POST['image1'];
    $image2 = $_POST['image2'];
    $image3 = $_POST['image3'];

    $idate = $_POST['releasedate'];
    $idesc = $_POST['description'];
    $iscale = $_POST['scale'];
    $istock = $_POST['stock'];
    $iprice = $_POST['price'];

    $checkquery = sprintf("SELECT * from ao_item where item_name = '%s'", mysqli_real_escape_string($connect, $iname));
    $result = $connect->query($checkquery);
    $result = $result->fetch_all();
    if ($result) {
        $hasError = 1;
        $errorMessage = 'Item Name already exist.';
    } else {
        $query = sprintf("INSERT INTO ao_item(item_id, film_id, category_id, brand_id, item_name, item_image_1, item_image_2, item_image_3, release_date, item_description, scale, stock_quantity, price)
        VALUES ('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')", mysqli_real_escape_string($connect, $iid), mysqli_real_escape_string($connect, $ifilm), mysqli_real_escape_string($connect, $icategory), mysqli_real_escape_string($connect, $ibrand), mysqli_real_escape_string($connect, $iname), $image1, $image2, mysqli_real_escape_string($connect, $image3), mysqli_real_escape_string($connect, $idate), mysqli_real_escape_string($connect, $idesc), mysqli_real_escape_string($connect, $iscale), mysqli_real_escape_string($connect, $istock), mysqli_real_escape_string($connect, $iprice));
        $connect->query($query);
        // SUCCESS
        header('Location: /admin-items', true, 301);
    }
} else
if (isset($_POST['btn-item-update'])) {
    $item_id = $_POST['id'];

    $check_query = sprintf("SELECT * FROM ao_item WHERE item_id = '%s'", mysqli_real_escape_string($connect, $item_id));
    $result = $connect->query($check_query);
    $result = $result->fetch_all();

    if (!$result) {
        $hasError = 1;
        $errorMessage = 'Item ID does not exist.';
        header('Location: /admin-items', true, 301);
    }

    $ftitle = $_POST['title'];
    $film = sprintf("SELECT film_id FROM ao_film where title = '%s'", mysqli_real_escape_string($connect, $ftitle));
    $result = $connect->query($film);
    $result = $result->fetch_all();

    $ifilm = $result[0][0];

    $cname = $_POST['category'];
    $category = sprintf("SELECT category_id FROM ao_category where category_name = '%s'", mysqli_real_escape_string($connect, $cname));
    $result = $connect->query($category);
    $result = $result->fetch_all();

    $icategory = $result[0][0];

    $bname = $_POST['brand'];
    $brand = sprintf("SELECT brand_id FROM ao_brand where brand_name = '%s'", mysqli_real_escape_string($connect, $bname));
    $result = $connect->query($brand);
    $result = $result->fetch_all();

    $ibrand = $result[0][0];

    $iname = $_POST['name'];
    $image1 = $_POST['image1'];
    $image2 = $_POST['image2'];
    $image3 = $_POST['image3'];

    $idate = $_POST['releasedate'];
    $idesc = $_POST['description'];
    $iscale = $_POST['scale'];
    $istock = $_POST['stock'];
    $iprice = $_POST['price'];

    $checkquery = sprintf("SELECT * from ao_item where item_name = '%s' AND item_id != '%s'", mysqli_real_escape_string($connect, $iname), mysqli_real_escape_string($connect, $item_id));
    $result = $connect->query($checkquery);
    $result = $result->fetch_all();
    if ($result) {
        $hasError = 1;
        $errorMessage = 'Item Name already exist.';
    } else {
        $update_query = sprintf(
            "UPDATE ao_item
            SET film_id = '%s',
                category_id = '%s',
                brand_id = '%s',
                item_name = '%s',
                item_image_1 = '%s',
                item_image_2 = '%s',
                item_image_3 = '%s',
                release_date = '%s',
                item_description = '%s',
                scale = '%s',
                stock_quantity = '%s',
                price = '%s'
            WHERE item_id = '%s'",
            mysqli_real_escape_string($connect, $ifilm),
            mysqli_real_escape_string($connect, $icategory),
            mysqli_real_escape_string($connect, $ibrand),
            mysqli_real_escape_string($connect, $iname),
            mysqli_real_escape_string($connect, $image1),
            mysqli_real_escape_string($connect, $image2),
            mysqli_real_escape_string($connect, $image3),
            mysqli_real_escape_string($connect, $idate),
            mysqli_real_escape_string($connect, $idesc),
            mysqli_real_escape_string($connect, $iscale),
            mysqli_real_escape_string($connect, $istock),
            mysqli_real_escape_string($connect, $iprice),
            mysqli_real_escape_string($connect, $item_id)
        );

        $connect->query($update_query);
        // SUCCESS
        header('Location: /admin-items', true, 301);
    }
} else
if (isset($_POST['btn-item-delete'])) {
    $item_id = $_POST['id'];

    $check_query = sprintf("SELECT * FROM ao_item WHERE item_id = '%s'", mysqli_real_escape_string($connect, $item_id));
    $result = $connect->query($check_query);
    $result = $result->fetch_all();

    if (!$result) {
        $hasError = 1;
        $errorMessage = 'Item ID does not exist.';
        header('Location: /admin-items', true, 301);
    } else {
        $delete_query = sprintf("DELETE FROM ao_item WHERE item_id = '%s'", mysqli_real_escape_string($connect, $item_id));
        $connect->query($delete_query);

        // SUCCESS
        header('Location: /admin-items', true, 301);
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


$results = [];
$query = "select ai.item_id, ai.item_name , ac.category_name as category, af.title as film, ab.brand_name as brand, ai.item_image_1, ai.item_image_2, ai.item_image_3, ai.release_date, ai.item_description, ai.`scale`, ai.stock_quantity, ai.price from `ASSIGNMENT`.ao_item ai join `ASSIGNMENT`.ao_brand ab on ab.brand_id = ai.brand_id join `ASSIGNMENT`.ao_film af on af.film_id = ai.film_id join `ASSIGNMENT`.ao_category ac on ac.category_id = ai.category_id ";
$result = mysqli_query($connect, $query);

// Loop through each row and display the data
while ($row = mysqli_fetch_assoc($result)) {
    array_push($results, $row);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Items</title>
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

    <div id="main" class="container-fluid">
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
                <!-- can cru no delete-->

                <div>
                    <div class="d-flex justify-content-between align-items-center">
                        <h1>Items</h1>
                        <button class="btn btn-primary" @click="createItem()" data-bs-toggle="modal" data-bs-target="#editModal">Create</button>
                    </div>
                    <table class="table table-responsive table-hover table-striped">
                        <thead>
                            <tr>
                                <!-- <th>Item ID</th> -->
                                <th>Item Name</th>
                                <th>Category</th>
                                <th>Film</th>
                                <th>Brand</th>
                                <!-- <th>Image 1</th>
                                <th>Image 2</th>
                                <th>Image 3</th> -->
                                <th>Release date</th>
                                <th>Item Description</th>
                                <th>Scale</th>
                                <th>Stock Quantity</th>
                                <th>Price</th>
                                <th>Option</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in items">
                                <td>{{item.item_name}}</td>
                                <td>{{item.category}}</td>
                                <td>{{item.film}}</td>
                                <td>{{item.brand}}</td>
                                <!-- <td>{{item.item_image1}}</td>
                                <td>{{item.item_image2}}</td>
                                <td>{{item.item_image3}}</td> -->
                                <td>{{item.release_date}}</td>
                                <td>{{item.item_description}}</td>
                                <td>{{item.scale}}</td>
                                <td>{{item.stock_quantity}}</td>
                                <td>{{item.price}}</td>
                                <td>
                                    <button class="btn btn-primary btn-sm" @click="selectItem(item)" data-bs-toggle="modal" data-bs-target="#editModal">Edit</button>
                                    <button class="btn btn-danger btn-sm" @click="selectItem(item)" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete</button>
                                    <button class="btn btn-success btn-sm" @click="selectItem(item)" data-bs-toggle="modal" data-bs-target="#purchaseModal">Purchase</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </main>

            <div class="modal modal-lg fade" tabindex="-1" id="editModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{title}}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body row">
                            <div class="col-6">
                                <label for="film" class="form-label">Film</label>
                                <select class="form-select" id="film" v-model="selectedItem.film">
                                    <option v-for="film in films" :value="film.film_id">{{ film.title }}</option>
                                </select>
                            </div>

                            <div class="col-6">
                                <label for="category" class="form-label">Category</label>
                                <select class="form-select" id="category" v-model="selectedItem.category">
                                    <option v-for="category in categories" :value="category.category_id">
                                        {{ category.category_name }}
                                    </option>
                                </select>
                            </div>

                            <div class="col-6">
                                <label for="brand" class="form-label">Brand</label>
                                <select class="form-select" id="brand" v-model="selectedItem.brand">
                                    <option v-for="brand in brands" :value="brand.brand_id">{{ brand.brand_name }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label for="item_description" class="form-label">Item Description</label>
                                <input type="text" class="form-control" id="item_description" v-model="selectedItem.item_description">
                            </div>
                            <div class="col-6">
                                <label for="item_image_1" class="form-label">Image 1</label>
                                <input type="url" class="form-control" id="item_image_1" v-model="selectedItem.item_image_1">
                            </div>
                            <div class="col-6">
                                <label for="item_image_2" class="form-label">Image 2</label>
                                <input type="url" class="form-control" id="item_image_2" v-model="selectedItem.item_image_2">
                            </div>
                            <div class="col-6">
                                <label for="item_image_3" class="form-label">Image 3</label>
                                <input type="url" class="form-control" id="item_image_3" v-model="selectedItem.item_image_3">
                            </div>
                            <div class="col-6">
                                <label for="item_name" class="form-label">Item Name</label>
                                <input type="text" class="form-control" id="item_name" v-model="selectedItem.item_name">
                            </div>
                            <div class="col-6">
                                <label for="price" class="form-label">Price</label>
                                <input type="text" class="form-control" id="price" v-model="selectedItem.price">
                            </div>
                            <div class="col-6">
                                <label for="release_date" class="form-label">Release Date</label>
                                <input type="date" class="form-control" id="release_date" v-model="selectedItem.release_date">
                            </div>
                            <div class="col-6">
                                <label for="scale" class="form-label">Scale</label>
                                <input type="text" class="form-control" id="scale" v-model="selectedItem.scale">
                            </div>
                            <div class="col-6">
                                <label for="stock_quantity" class="form-label">Stock Quantity</label>
                                <input type="text" class="form-control" id="stock_quantity" v-model="selectedItem.stock_quantity">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" @click="submitCreateOrEditModal()">Save</button>
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
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal" @click="confirmDelete()">Confirm</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" tabindex="-1" id="purchaseModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Are you sure do you want to purchase?</h5>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal" @click="confirmPurchase()">Confirm</button>
                        </div>
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
                title: '',
                items: <?= json_encode($results) ?>,
                films: <?= json_encode($films) ?>,
                categories: <?= json_encode($categories) ?>,
                brands: <?= json_encode($brands) ?>,
                selectedItem: {},
            },
            methods: {
                createItem() {
                    this.title = 'Create';
                    this.selectedItem = {
                        film: '',
                        brand: '',
                        category: '',
                        item_description: '',
                        item_image_1: '',
                        item_image_2: '',
                        item_image_3: '',
                        item_name: '',
                        price: '',
                        release_date: '',
                        scale: '',
                        stock_quantity: '',
                    };
                },
                selectItem(item) {
                    this.title = 'Edit';
                    this.selectedItem = {
                        ...item
                    };
                },
                confirmDelete() {
                    let formData = new FormData();
                    formData.append('btn-item-delete', 1);
                    formData.append('id', this.selectedItem.item_id);
                    axios.post('', formData).then(() => location.reload());
                },
                confirmPurchase() {
                    let formData = new FormData();
                    formData.append('btn-item-purchase', 1);
                    formData.append('id', this.selectedItem.item_id);
                    axios.post('', formData).then(() => location.reload());
                },
                submitCreateOrEditModal() {
                    if (this.title === 'Create') {
                        let formData = new FormData()
                        formData.append('btn-item-save', 1);
                        formData.append('title', this.selectedItem.film);
                        formData.append('category', this.selectedItem.category);
                        formData.append('brand', this.selectedItem.brand);
                        formData.append('name', this.selectedItem.item_name);
                        formData.append('image1', this.selectedItem.item_image_1);
                        formData.append('image2', this.selectedItem.item_image_2);
                        formData.append('image3', this.selectedItem.item_image_3);
                        formData.append('releasedate', this.selectedItem.release_date);
                        formData.append('description', this.selectedItem.item_description);
                        formData.append('scale', this.selectedItem.scale);
                        formData.append('stock', this.selectedItem.stock_quantity);
                        formData.append('price', this.selectedItem.price);
                        axios.post('', formData).then((res) => location.reload());
                    } else {
                        let formData = new FormData()
                        formData.append('btn-item-update', 1);
                        formData.append('id', this.selectedItem.item_id);
                        formData.append('title', this.selectedItem.film);
                        formData.append('category', this.selectedItem.category);
                        formData.append('brand', this.selectedItem.brand);
                        formData.append('name', this.selectedItem.item_name);
                        formData.append('image1', this.selectedItem.item_image_1);
                        formData.append('image2', this.selectedItem.item_image_2);
                        formData.append('image3', this.selectedItem.item_image_3);
                        formData.append('releasedate', this.selectedItem.release_date);
                        formData.append('description', this.selectedItem.item_description);
                        formData.append('scale', this.selectedItem.scale);
                        formData.append('stock', this.selectedItem.stock_quantity);
                        formData.append('price', this.selectedItem.price);
                        axios.post('', formData).then((res) => location.reload());
                    }
                },
            }
        });
    </script>
</body>

</html>