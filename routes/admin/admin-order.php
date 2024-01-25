<?php
if (($_SESSION['role'] ?? 0) !== ROLE_ADMIN) {
    session_destroy();
    header('Location: /admin-login', true, 301);
    exit;
}
// when pressed on each order, this will show in pop up
function getOrderDetailPopUp($connect, $orderId)
{
    $results = [];
    $query = sprintf("select ai.item_image_1, ai.item_name, aod.unit_price, aod.quantity, aod.sub_total  from `assignment`.ao_order_detail aod join `assignment`.ao_item ai on ai.item_id = aod.item_id where aod.order_id = '%s' order by aod.order_detail_id", mysqli_real_escape_string($connect, $orderId));
    $result = mysqli_query($connect, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($results, $row);
    }
    return $results;
}

if (isset($_GET['orderId'])) {
    $orderDetails = getOrderDetailPopUp($connect, $_GET['orderId']);
    echo json_encode($orderDetails);
    exit;
}

$results = [];
$query = "select ao.order_id, ac.full_name, ao.order_date, ao.order_status from `ASSIGNMENT`.ao_order ao join `ASSIGNMENT`.ao_customer ac on ao.customer_id = ac.customer_id order by ao.order_date desc";
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
                                    <?php
                                    $totalQuantity = 0;
                                    if (isset($_SESSION['__purchase'])) {
                                        foreach ($_SESSION['__purchase'] as $item) {
                                            $totalQuantity += $item['quantity'];
                                        }
                                    }
                                    if ($totalQuantity > 0) {
                                        echo '<span class="badge bg-danger rounded-circle">' . $totalQuantity . '</span>';
                                    }
                                    ?>
                                </a>
                            </li>
                        </ul>

                    </div>
                </div>
            </div>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" style="min-height:100vh" id=main>
                <h1 class='mt-4'> Order List</h1>
                <div class="mt-4">
                    <!-- items -->
                    <div>
                        <!-- item -->
                        <?php foreach ($results as $item) : ?>
                            <div class="mb-2 rounded border p-4" style="background-color:#f8f9fa;border-color:e3e3e3">
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex">
                                        <div style="margin-right:25px"><small><?= $item['full_name'] ?></small>
                                        </div>
                                        <div style="margin-right:25px">
                                            <span class="badge <?= ($item['order_status'] === 'ACCEPTED') ? 'text-bg-success' : 'text-bg-info' ?>">
                                                <?= $item['order_status'] ?>
                                            </span>
                                        </div>
                                        <div style="margin-right:25px">
                                            <p class="fw-bold">$<?= $item['order_date'] ?></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-2">
                                    <table class="table table-responsive bg-transparent">
                                        <tbody>
                                            <?php
                                            foreach (getOrderDetailPopUp($connect, $item['order_id']) as $orderDetail) : ?>
                                                <tr>
                                                    <td><img src="<?= $orderDetail['item_image_1'] ?>" style="width:100px;height:auto;" alt="item image"></td>
                                                    <td><?= $orderDetail['item_name'] ?></td>
                                                    <td><?= $orderDetail['unit_price'] ?></td>
                                                    <td><?= $orderDetail['quantity'] ?></td>
                                                    <td><?= $orderDetail['sub_total'] ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="/js/bootstrap.bundle.min.js"></script>
    <script src="/js/vue.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        new Vue({
            el: '#main',
            data: {
                items: <?= json_encode($results) ?>,
                orderDetails: [],
            },
            methods: {
                loadOrderDetails(orderId) {
                    // Make an Ajax request to fetch order details
                    axios.get(`/admin-order?orderId=${orderId}`)
                        .then(response => {
                            this.orderDetails = response.data;
                            console.log(this.orderDetails);
                            $('#orderDetailModal').modal('show');
                        })
                        .catch(error => {
                            console.error('Error fetching order details', error);
                        });
                },
            },
        });
    </script>
</body>

</html>