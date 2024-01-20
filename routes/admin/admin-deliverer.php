<?php
if (($_SESSION['role'] ?? 0) !== ROLE_ADMIN) {
    session_destroy();
    header('Location: /admin-login', true, 301);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Deliverer</title>
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

    <main>
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
                            <a class="btn  btn-primary" href="/admin-create-deliverer">Create</a>

                        </div>
                        <table class="table table-responsive table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Phone</th>
                                    <th>Available Days</th>
                                    <th>Delivery Zones</th>
                                    <th>Option</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>John Smith</td>
                                    <td>johnsmith</td>
                                    <td>09686983852</td>
                                    <td>5</td>
                                    <td>2</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModal">Edit</button>
                                        <button class="btn btn-sm btn-danger" href="#">Delete</button>
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
                        <h5 class="modal-title">Edit</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" name="name">
                        </div>
                        <div>
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" name="username">
                        </div>
                        <div>
                            <label for="phone" class="form-label">Phone</label>
                            <input type="tel" class="form-control" name="phone">
                        </div>
                        <div>
                            <label for="availableDays" class="form-label">Available Days</label>
                            <input type="number" class="form-control" name="availableDays">
                        </div>
                        <div>
                            <label for="deliveryZones" class="form-label">Delivery Zones</label>
                            <input type="tel" class="form-control" name="deliveryZones">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" type="submit">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="/js/vue.min.js"></script>
    <script src="/js/bootstrap.bundle.min.js"></script>
    <script>
        new Vue({
            el: 'main',
            data: {
                // items:
            },

        })
    </script>
</body>

</html>