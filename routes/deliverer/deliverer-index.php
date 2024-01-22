<?php
if (($_SESSION['role'] ?? 0) !== ROLE_DELIVERER) {
    session_destroy();
    header('Location: /deliverer-login', true, 301);
    exit;
}

//  can only view delivery items and edit them for the status
if ($method === 'POST') {
    $option = $_POST['option'];
    // OPTIONS are ACCEPT and DECLINE
    $trackcode = $_POST['trackcode'];
    $checkquery = sprintf("SELECT * from ao_delivery where tracking_code = '%s'", mysqli_real_escape_string($connect, $trackcode));
    $result = $connect->query($query);
    $result = $result->fetch_all();
    if ($result) {
        $query = sprintf("UPDATE ao_delivery SET delivery_status = '%s' where tracking_code='%s'", mysqli_real_escape_string($connect, $option), mysqli_real_escape_string($connect, $trackcode));
        $connect->query($query);
        // SUCCESS
        header('Location: /deliverer');
    } else {
        $hasError = 1;
        $errorMessage = 'Tracking code not exist.';
    }
}
// purchased items
$results = [];
$query = "SELECT dy.delivery_id, dr.deliverer_name, dy.order_id, dy.estimate_delivery_date, dy.tracking_code, dy.delivery_status FROM ASSIGNMENT.ao_delivery dy join ASSIGNMENT.ao_deliverer dr on dy.deliverer_id = dr.deliverer_id join `ASSIGNMENT`.ao_order ao on ao.order_id = dy.order_id order by dy.delivery_id";
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
    <title>Deliverer</title>
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
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6 text-white" href="/deliverer"><?= COMPANY_NAME ?></a>
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
                                <a class="nav-link d-flex align-items-center gap-2 active" aria-current="page" href="/deliverer">
                                    Home
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" style="min-height:100vh">
                <!-- can edit the estimate_delivery_date and delivery status -->


                <div>
                    <table class="table table-responsive table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Estimate Delivery Date</th>
                                <th>Tracking Code</th>
                                <th>Status</th>
                                <th>Customer Name</th>
                                <th>Customer Phone</th>
                                <th>Customer Address</th>
                                <th>Item Count</th>
                                <th>Option</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            // $query = "SELECT dy.delivery_id, dr.deliverer_name, dy.order_id, dy.estimate_delivery_date, dy.tracking_code, dy.delivery_status FROM ASSIGNMENT.ao_delivery dy join ASSIGNMENT.ao_deliverer dr on dy.deliverer_id = dr.deliverer_id join `ASSIGNMENT`.ao_order ao on ao.order_id = dy.order_id where ao.order_status = 'SUCCESS' and dy.delivery_status != 'SUCCESS'";
                            // $result = mysqli_query($connect, $query);

                            // // Loop through each row and display the data
                            // while ($row = mysqli_fetch_assoc($result)) {
                            //     echo "<tr>";
                            //     echo "<td>" . $row['estimate_delivery_date'] . "</td>";
                            //     echo "<td>" . $row['tracking_code'] . "</td>";
                            //     echo "<td><span class='badge text-bg-primary'>" . $row['delivery_status'] . "</span></td>";
                            //     echo "<td>" . $row['estimate_delivery_date'] . "</td>";
                            //     echo "<td>" . $row['tracking_code'] . "</td>";
                            //     echo "<td>" . $row['delivery_status'] . "</td>";
                            //     echo "</tr>";
                            // }
                            ?>
                            <tr>
                                <td>1-1-2024</td>
                                <td>DHL3923</td>
                                <td>
                                    <span class='badge text-bg-info'>PENDING</span>
                                </td>
                                <td>John Doe</td>
                                <td>09686983855</td>
                                <td>Mingalardon, Yangon</td>
                                <td>5</td>
                                <td>
                                    <button class="btn btn-sm btn-secondary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Change Status
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>




            </main>
        </div>
    </div>

    <script src="/js/bootstrap.bundle.min.js"></script>
</body>

</html>