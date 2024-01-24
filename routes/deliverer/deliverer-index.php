<?php
if (($_SESSION['role'] ?? 0) !== ROLE_DELIVERER) {
    session_destroy();
    header('Location: /deliverer-login', true, 301);
    exit;
}

//  can only view delivery items and edit them for the status
if (isset($_POST['btnchangestatus'])) {
    $trackcode = $_POST['trackingCode'];
    $checkquery = sprintf("SELECT * from ao_delivery where tracking_code = '%s'", mysqli_real_escape_string($connect, $trackcode));
    $result = $connect->query($checkquery);
    $result = $result->fetch_all();
    if ($result) {
        $updatequery = sprintf("UPDATE ao_delivery SET delivery_status = 'ACCEPTED' where tracking_code='%s'", mysqli_real_escape_string($connect, $trackcode));
        $connect->query($updatequery);
        // SUCCESS
        header('Location: /deliverer');
    } else {
        $hasError = 1;
        $errorMessage = 'Tracking code not exist.';
    }
}
// purchased items
$results = [];
$delivererid = $_SESSION['id'];
$query = "SELECT ad.estimate_delivery_date , ad.tracking_code , ad.delivery_status , ac.full_name , ac.phone , ac.customer_address , sum(aod.quantity) as quantity from `ASSIGNMENT`.ao_delivery ad join `ASSIGNMENT`.ao_order ao on ao.order_id  = ad.order_id join `ASSIGNMENT`.ao_customer ac on ac.customer_id = ao.customer_id join `ASSIGNMENT`.ao_order_detail aod on aod.order_id = ao.order_id where ad.deliverer_id = '$delivererid' group by ad.estimate_delivery_date , ad.tracking_code , ad.delivery_status , ac.full_name , ac.phone , ac.customer_address";
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

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" style="min-height:100vh" id="main">
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
                            <!-- ad.estimate_delivery_date , ad.tracking_code , ad.delivery_status , ac.full_name , ac.phone , ac.customer_address , sum(aod.quantity) as quantity -->
                            <?php foreach ($results as $delivery) : ?>
                                <tr>
                                    <td><?= $delivery['estimate_delivery_date'] ?></td>
                                    <td :value="trackingCode"><?= $delivery['tracking_code'] ?></td>
                                    <td>
                                        <span class='badge <?= $delivery['delivery_status'] === 'ACCEPTED' ? 'text-bg-success' : 'text-bg-info' ?>'>
                                            <?= $delivery['delivery_status'] ?>
                                        </span>
                                    </td>
                                    <td><?= $delivery['full_name'] ?></td>
                                    <td><?= $delivery['phone'] ?></td>
                                    <td><?= $delivery['customer_address'] ?></td>
                                    <td><?= $delivery['quantity'] ?></td>
                                    <td>
                                        <?php if ($delivery['delivery_status'] !== 'ACCEPTED') : ?>
                                            <button class="btn btn-sm btn-secondary" type="button" @click="changeStatus('<?= $delivery['tracking_code'] ?>')">
                                                Change Status
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

    <script src="/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.7.16/dist/vue.js"></script>
    <!-- <script src="/js/vue.min.js"></script> -->
    <script src="/js/axios.min.js"></script>
    <script>
        new Vue({
            el: '#main',
            data: {
                trackingCode: '',
            },
            methods: {
                changeStatus(trackingCode) {
                    let formData = new FormData();
                    formData.append('btnchangestatus', 1);
                    formData.append('trackingCode', trackingCode);
                    axios.post('', formData).then(res => {
                        location.reload();
                    })
                },
            }
        })
    </script>
</body>

</html>