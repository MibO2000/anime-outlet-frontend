<?php
if (($_SESSION['role'] ?? 0) !== ROLE_ADMIN) {
    session_destroy();
    header('Location: /admin-login', true, 301);
    exit;
}
if (isset($_POST['btn-deliverer-post'])) {
    $did = AutoID('ao_deliverer', 'deliverer_id', 'DR', 4);
    $dname = $_POST['name'];
    $dphone = $_POST['phone'];
    $duser = $_POST['username'];
    $dpass = $_POST['password'];
    $davail = $_POST['availableDays'];
    $dzone = $_POST['delieveryZone'];

    $checkquery = sprintf("SELECT * from ao_deliverer where deliverer_user = '%s'", mysqli_real_escape_string($connect, $duser));
    $result = $connect->query($checkquery);
    $result = $result->fetch_all();
    if ($result) {
        $hasError = 1;
        $errorMessage = 'Username already exist.';
    } else {
        $query = sprintf("INSERT INTO ao_deliverer(deliverer_id, deliverer_name, deliverer_user, deliverer_password, phone, available_days, delivery_zone)
        VALUES ('%s','%s','%s','%s','%s','%s','%s')", mysqli_real_escape_string($connect, $did), mysqli_real_escape_string($connect, $dname), mysqli_real_escape_string($connect, $duser), password_hash($dpass, PASSWORD_BCRYPT), mysqli_real_escape_string($connect, $dphone), mysqli_real_escape_string($connect, $davail), mysqli_real_escape_string($connect, $dzone));
        $connect->query($query);
        // SUCCESS
        header('Location: /admin-deliverer');
    }
}

if (isset($_POST['btn-deliverer-update'])) {
    $did = $_POST['id'];

    $checkquery = sprintf("SELECT * FROM ao_delivery where deliverer_id = '%s'", mysqli_real_escape_string($connect, $did));
    $result = $connect->query($checkquery);
    $result = $result->fetch_all();
    if (!$result) {
        $hasError = 1;
        $errorMessage = 'ID does not exist.';
        header('Location: /admin-deliverer');
    } else {

        $dname = $_POST['name'];
        $dphone = $_POST['phone'];
        $duser = $_POST['username'];
        $dpass = $_POST['password'];
        $davail = $_POST['availableDays'];
        $dzone = $_POST['delieveryZone'];

        $checkquery = sprintf("SELECT * from ao_deliverer where deliverer_user = '%s' and deliverer_id != '%s'", mysqli_real_escape_string($connect, $duser), mysqli_real_escape_string($connect, $did));
        $result = $connect->query($checkquery);
        $result = $result->fetch_all();
        if ($result) {
            $hasError = 1;
            $errorMessage = 'Username already exist.';
        } else {
            $query = sprintf(
                "UPDATE ao_deliverer
        SET deliverer_name = '%s',
            deliverer_user = '%s',
            deliverer_password = '%s',
            phone = '%s',
            available_days = '%s',
            delivery_zone = '%s'
        WHERE deliverer_id = '%s'",
                mysqli_real_escape_string($connect, $dname),
                mysqli_real_escape_string($connect, $duser),
                password_hash($dpass, PASSWORD_BCRYPT),
                mysqli_real_escape_string($connect, $dphone),
                mysqli_real_escape_string($connect, $davail),
                mysqli_real_escape_string($connect, $dzone),
                mysqli_real_escape_string($connect, $did)
            );
            $connect->query($query);
            // SUCCESS
            header('Location: /admin-deliverer');
        }
    }
}
if (isset($_POST['btn-deliverer-delete'])) {
    $deliverer_id = $_POST['id'];

    $check_query = sprintf("SELECT * FROM ao_deliverer WHERE deliverer_id = '%s'", mysqli_real_escape_string($connect, $deliverer_id));
    $result = $connect->query($check_query);
    $result = $result->fetch_all();

    if (!$result) {
        $hasError = 1;
        $errorMessage = 'Deliverer ID does not exist.';
        header('Location: /admin-deliverer');
    } else {
        $delete_query = sprintf("DELETE FROM ao_deliverer WHERE deliverer_id = '%s'", mysqli_real_escape_string($connect, $deliverer_id));
        $connect->query($delete_query);

        // SUCCESS
        header('Location: /admin-deliverer');
    }
}

$results = [];
$query = "select ad.deliverer_id, ad.deliverer_name , ad.deliverer_user, ad.phone, ad.available_days, ad.delivery_zone from `ASSIGNMENT`.ao_deliverer ad";
$result = mysqli_query($connect, $query);
while ($row = mysqli_fetch_assoc($result)) {
    array_push($results, $row);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Deliverer</title>
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

    <div id="main">
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
                        <h1>Deliverer</h1>
                        <div class="float float-end">
                            <button class="btn btn-primary" @click="createItem()" data-bs-toggle="modal" data-bs-target="#editModal">Create</button>

                        </div>
                        <table class="table table-responsive table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Phone</th>
                                    <th>Available Days</th>
                                    <th>Delivery Zone</th>
                                    <th>Option</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in items">
                                    <td>{{item.deliverer_name}}</td>
                                    <td>{{item.deliverer_user}}</td>
                                    <td>{{item.phone}}</td>
                                    <td>{{item.available_days}}</td>
                                    <td>{{item.delivery_zone}}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" @click="selectItem(item)" data-bs-toggle="modal" data-bs-target="#editModal">Edit</button>
                                        <button class="btn btn-sm btn-danger" @click="selectItem(item)" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </main>
            </div>
        </div>

        <div class="modal fade" tabindex="-1" id="editModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{title}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" v-model="selectedItem.deliverer_name">
                        </div>
                        <div>
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" v-model="selectedItem.deliverer_user">
                        </div>
                        <div>
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" v-model="selectedItem.password">
                        </div>
                        <div>
                            <label for="phone" class="form-label">Phone</label>
                            <input type="tel" class="form-control" id="phone" v-model="selectedItem.phone">
                        </div>
                        <div>
                            <label for="availableDays" class="form-label">Available Days</label>
                            <input type="number" class="form-control" id="availableDays" v-model="selectedItem.available_days">
                        </div>
                        <div>
                            <label for="deliveryZones" class="form-label">Delivery Zone</label>
                            <input type="tel" class="form-control" id="deliveryZones" v-model="selectedItem.delivery_zones">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="createOrEditItem()">Save</button>
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
                        <button type="button" class="btn btn-primary" @click="confirmDelete()">Confirm</button>
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
                selectedItem: {},
            },
            methods: {
                selectItem(item) {
                    this.selectedItem = {
                        ...item,
                        password: '',
                    };
                    this.title = 'Edit';
                },
                createItem() {
                    this.selectedItem = {
                        deliverer_name: '',
                        deliverer_user: '',
                        password: '',
                        phone: '',
                        available_days: '',
                        delivery_zones: '',
                    };
                    this.title = 'Create';
                },
                confirmDelete() {
                    let formData = new FormData();
                    formData.append('btn-deliverer-delete', 1);
                    formData.append('id', this.selectedItem.deliverer_id);
                    axios.post('', formData).then(() => location.reload());
                },
                createOrEditItem() {
                    if (this.title === 'Create') {
                        let formData = new FormData();
                        formData.append('btn-deliverer-post', 1);
                        formData.append('name', this.selectedItem.deliverer_name);
                        formData.append('phone', this.selectedItem.phone);
                        formData.append('username', this.selectedItem.deliverer_user);
                        formData.append('password', this.selectedItem.password);
                        formData.append('availableDays', this.selectedItem.available_days);
                        formData.append('delieveryZone', this.selectedItem.delivery_zones);
                        axios.post('', formData).then(() => location.reload());
                    } else {
                        let formData = new FormData();
                        formData.append('btn-deliverer-update', 1);
                        formData.append('id', this.selectedItem.deliverer_id);
                        formData.append('name', this.selectedItem.deliverer_name);
                        formData.append('phone', this.selectedItem.phone);
                        formData.append('username', this.selectedItem.deliverer_user);
                        formData.append('password', this.selectedItem.password);
                        formData.append('availableDays', this.selectedItem.available_days);
                        formData.append('delieveryZone', this.selectedItem.delivery_zones);
                        axios.post('', formData).then(() => location.reload());
                    }
                }
            }
        })
    </script>
</body>

</html>