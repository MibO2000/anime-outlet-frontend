<?php
if (($_SESSION['role'] ?? 0) !== ROLE_ADMIN) {
    session_destroy();
    header('Location: /admin-login', true, 301);
    exit;
}

// when pressed on each purchase, this will show in pop up
function getPurchaseDetailPopUp($connect, $purchaseId)
{
    $results = [];
    // image , name, unit price, quantity, sub total price
    $query = sprintf("select ai.item_image_1, ai.item_name, aod.unit_price, aod.quantity, aod.sub_total  from `assignment`.ao_purchase_detail aod join `assignment`.ao_item ai on ai.item_id = aod.item_id where aod.purchase_id  = '%s' order by aod.purchase_detail_id", mysqli_real_escape_string($connect, $purchaseId));
    $result = mysqli_query($connect, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($results, $row);
    }
    return $results;
}

// current purchase items
$purchaseItems = array_values($_SESSION['__purchase'] ?? []);
echo "<script>console.log('" . json_encode($purchaseItems) . "');</script>";

$supplierquery = sprintf("SELECT supplier_name from ao_supplier order by supplier_id");
$result = $connect->query($supplierquery);;
$suppliers = $result->fetch_all();

function getSupplierId($connect, $suppliername)
{
    $supplierquery = sprintf("SELECT supplier_id from ao_supplier where supplier_name = '%s'", mysqli_real_escape_string($connect, $suppliername));
    $result = $connect->query($supplierquery);;
    $result = $result->fetch_all();
    return $result[0][0];
}

if (isset($_POST['btnremove'])) {
    $newPurchaseItems = [];
    $purchaseItems = array_filter($purchaseItems, function ($purchase) {
        $id = $_POST['item_id'];
        return $purchase['id'] !== $id;
    });
    foreach ($purchaseItems as $purchase) {
        $newPurchaseItems[$purchase['id']] = $purchase;
    }
    $_SESSION['__purchase'] = $newpurchaseItems;
    exit("Success");
}

if (isset($_POST['btncheckout'])) {
    echo "<script>console.log('" . json_encode($purchaseItems) . "');</script>";

    if (!empty($purchaseItems)) {
        // from section
        $itemidlist = array();
        $adminid = $_SESSION['admin_id'];

        $purchaseid = AutoID('ao_purchase', 'purchase_id', 'P', 4);

        $purchasestatus = 'PENDING';

        $suppliername = $_POST['supplier'];
        $supplierid = getSupplierId($connect, $suppliername);
        $query = sprintf("INSERT INTO ao_purchase(purchase_id, supplier_id, admin_id, purchase_date, purchase_status)
        VALUES ('%s','%s','%s', NOW(), '%s')", mysqli_real_escape_string($connect, $purchaseid), mysqli_real_escape_string($connect, $supplierid), mysqli_real_escape_string($connect, $adminid), mysqli_real_escape_string($connect, $purchasestatus));
        $connect->query($query);

        $totalamount = 0;

        foreach ($purchaseItems as $purchaseItem) {
            $purchasedetailid = AutoID('ao_purchase_detail', 'purchase_detail_id', 'PD', 4);

            // from section
            $quantity = $purchaseItem['quantity'];
            $itemid = $purchaseItem['id'];

            $itemsql = sprintf("SELECT price FROM ao_item where item_id = '%s'", mysqli_real_escape_string($connect, $purchaseItem['id']));
            $result = $connect->query($itemsql);
            $result = $result->fetch_all();
            $item = $result[0];

            $unitprice = $item[0];

            $subtotal = $unitprice * $quantity;
            $totalamount = $totalamount +  $subtotal;
            $query = sprintf("INSERT INTO ao_purchase_detail(purchase_detail_id, purchase_id, item_id, quantity, unit_price, sub_total)
        VALUES ('%s','%s','%s','%s','%s','%s')", mysqli_real_escape_string($connect, $purchasedetailid), mysqli_real_escape_string($connect, $purchaseid), mysqli_real_escape_string($connect, $itemid), mysqli_real_escape_string($connect, $quantity), mysqli_real_escape_string($connect, $unitprice), mysqli_real_escape_string($connect, $subtotal));
            $connect->query($query);
        }
        $purchasequery = sprintf("UPDATE ao_purchase SET total_amount = '%s' WHERE purchase_id = '%s'", mysqli_real_escape_string($connect, $totalamount), mysqli_real_escape_string($connect, $purchaseid));
        $connect->query($purchasequery);

        $_SESSION['__purchase'] = [];
    }
}

// purchased items
$results = [];
$query = "select ap.purchase_id, as2.supplier_name, aa.fullname, ap.purchase_date, ap.purchase_status, ap.total_amount from `ASSIGNMENT`.ao_purchase ap join `ASSIGNMENT`.ao_supplier as2 on as2.supplier_id = ap.supplier_id left join `ASSIGNMENT`.ao_admin aa on aa.admin_id = ap.admin_id";
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
    <link rel="icon" type="image/x-icon"
        href="https://scontent.frgn10-1.fna.fbcdn.net/v/t39.30808-6/273028440_4734065929980159_2213306540146619987_n.jpg?_nc_cat=104&ccb=1-7&_nc_sid=efb6e6&_nc_ohc=8DAVbr-s2rkAX8qzgRc&_nc_oc=AQlfsbZdD8sK9fExJlOIaeZQh576v7W5GFmAZ8yRDVlHm7EeL8UPY76iqfDuTlOwhPA&_nc_ht=scontent.frgn10-1.fna&oh=00_AfCkNdNmMA1O9LPRUo_CMwKAzytRNxHZWlGb4GQWmIRtZQ&oe=65B1794E">
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
                <button class="btn dropdown-toggle text-white " type="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
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
                <div class="offcanvas-md offcanvas-end bg-body-tertiary" tabindex="-1" id="sidebarMenu"
                    aria-labelledby="sidebarMenuLabel">
                    <div class="offcanvas-body d-md-flex flex-column p-0 pt-lg-3 overflow-y-auto">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2 active" aria-current="page"
                                    href="/admin-items">
                                    Items
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2 active" aria-current="page"
                                    href="/admin-item-details">
                                    Item Details
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2 active" aria-current="page"
                                    href="/admin-supplier">
                                    Supplier
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2 active" aria-current="page"
                                    href="/admin-deliverer">
                                    Deliverer
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2 active" aria-current="page"
                                    href="/admin-order">
                                    Order
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2 active" aria-current="page"
                                    href="/admin-purchase">
                                    Purchase
                                </a>
                            </li>
                        </ul>

                    </div>
                </div>
            </div>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" style="min-height:100vh">
                <div>
                    <h1>Purchase</h1>
                    <div>
                        <div>
                            <!-- item -->
                            <div v-for="(purchase,i) in purchaseItems">
                                <div class="row">
                                    <div class="col-md-3">
                                        <img v-if="purchase.image" style="width:100%;height:auto;" :src="purchase.image"
                                            alt="">
                                    </div>
                                    <div class="col-md-5">
                                        <p><span class="fw-bold">{{purchase.name}}</span></p>
                                        <p><span class="fw-bold">$ {{purchase.price}}</span></p>
                                    </div>
                                    <div class="col-md-2">
                                        <input class="form-control w-50" v-model="purchase.quantity" type="number">
                                    </div>
                                    <div class="col-md-2">
                                        <p class="fw-bold text-end">$ {{calcuateTotal(purchase)}}</p>
                                        <div class="d-flex justify-content-end">
                                            <button class="btn btn-sm btn-danger"
                                                @click="removeItem(purchase)">Remove</button>
                                        </div>
                                    </div>
                                </div>
                                <hr v-if="i+1!==purchaseItems.length">
                            </div>

                        </div>


                        <div>
                            <h5 class="text-end">Total: $ {{totalAmount}}</h5>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <select class="form-select" v-model="supplier">
                                    <option value="" disabled>Supplier name</option>
                                    <option v-for="supplier in suppliers" :value="supplier">{{supplier[0]}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-lg btn-primary" @click="checkout()">
                                Checkout
                            </button>
                        </div>

                        <div class="p-5"></div>
                    </div>

                    <hr>
                    <h2>History</h2>
                    <hr>
                    <!--  -->
                    <table class="table table-responsive table-hover table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Supplier name</th>
                                <th>Admin name</th>
                                <th>Purchase Date</th>
                                <th>Purchase Status</th>
                                <th>Total Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in items">
                                <td>{{item.purchase_id}}</td>
                                <td>{{item.supplier_name}}</td>
                                <td>{{item.fullname}}</td>
                                <td>{{item.purchase_date}}</td>
                                <td>{{item.purchase_status}}</td>
                                <td>{{item.total_amount}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.7.16/dist/vue.js"></script>
    <script src="/js/bootstrap.bundle.min.js"></script>
    <script src="/js/vue.min.js"></script>
    <script src="/js/axios.min.js"></script>
    <script>
    new Vue({
        el: '#main',
        data: {
            supplier: '',
            items: <?= json_encode($results) ?>,
            suppliers: <?= json_encode($suppliers) ?>,
            purchaseItems: <?= json_encode($purchaseItems) ?>,
        },
        methods: {
            calcuateTotal(item) {
                return item.price * item.quantity;
            },
            removeItem(item) {
                let formData = new FormData();
                formData.append('btnremove', 1);
                formData.append('item_id', item.id);
                axios.post('', formData).then(() => {
                    this.purchaseItems = this.purchaseItems.filter((purchase) => purchase.id !== item
                        .id);
                });
            },
            checkout() {
                if (!this.supplier) {
                    return alert('Choose supplier name!!');
                }
                let formData = new FormData();
                formData.append('btncheckout', 1);
                formData.append('supplier', this.supplier);
                axios.post('', formData).then(res => {
                    location.reload();
                })
            },
        },
        computed: {
            totalAmount() {
                let total = 0;
                this.purchaseItems.forEach(item => {
                    total += this.calcuateTotal(item);
                });
                return total;
            },
        }
    });
    </script>
</body>

</html>