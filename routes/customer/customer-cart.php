<?php
if (($_SESSION['role'] ?? 0) !== ROLE_CUSTOMER) {
    session_destroy();
    header('Location: /login', true, 301);
    exit;
}

function updateItemQuantity($connect, $orderId)
{
    $query = sprintf("SELECT apd.item_id, apd.quantity from `ASSIGNMENT`.ao_order_detail apd where apd.order_id = '%s'", mysqli_real_escape_string($connect, $orderId));
    $result = mysqli_query($connect, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $itemid = $row['item_id'];
        $quantity = $row['quantity'];
        $updatequery = sprintf("UPDATE `ASSIGNMENT`.ao_item SET stock_quantity = stock_quantity - '%s' where item_id = '%s'", mysqli_real_escape_string($connect, $quantity), mysqli_real_escape_string($connect, $itemid));
        $connect->query($updatequery);
    }
}

$cartItems = array_values($_SESSION['__cart'] ?? []);

if (isset($_POST['btnremove'])) {
    $newCartItems = [];
    $cartItems = array_filter($cartItems, function ($cart) {
        $id = $_POST['item_id'];
        return $cart['id'] !== $id;
    });
    foreach ($cartItems as $cart) {
        $newCartItems[$cart['id']] = $cart;
    }
    $_SESSION['__cart'] = $newCartItems;
    exit("Success");
}

$paymentMethods = array("cash on delivery", "visa", "mastercard", "kpay", "wavepay");

$delivererquery = sprintf("SELECT deliverer_name from ao_deliverer order by deliverer_id");
$result = $connect->query($delivererquery);;
$deliverers = $result->fetch_all();

function getDelivererId($connect, $deliverername)
{
    $delivererquery = sprintf("SELECT deliverer_id from ao_deliverer where deliverer_name = '%s'", mysqli_real_escape_string($connect, $deliverername));
    $result = $connect->query($delivererquery);;
    $result = $result->fetch_all();
    return $result[0][0];
}

if (isset($_POST['btncheckout'])) {
    // from section
    $itemidlist = array();
    $cid = $_SESSION['customer_id'];
    $orderid = AutoID('ao_order', 'order_id', 'O', 4);
    $orderstatus = 'PENDING';
    $query = sprintf("INSERT INTO ao_order(order_id, customer_id, order_status, order_date)
        VALUES ('%s','%s','%s', NOW())", mysqli_real_escape_string($connect, $orderid), mysqli_real_escape_string($connect, $cid), mysqli_real_escape_string($connect, $orderstatus));
    $connect->query($query);
    foreach ($cartItems as $cartItem) {
        $orderdetailid = AutoID('ao_order_detail', 'order_detail_id', 'OD', 4);
        // from section
        $quantity = $cartItem['quantity'];
        $itemid = $cartItem['id'];

        $itemsql = sprintf("SELECT * FROM ao_item where item_id = '%s'", mysqli_real_escape_string($connect, $itemid));
        $result = $connect->query($itemsql);
        $result = $result->fetch_all();
        $item = $result[0];

        $unitprice = $item[12];

        $subtotal = $unitprice * $quantity;
        $query = sprintf("INSERT INTO ao_order_detail(order_detail_id, order_id, item_id, quantity, unit_price, sub_total)
        VALUES ('%s','%s','%s','%s','%s','%s')", mysqli_real_escape_string($connect, $orderdetailid), mysqli_real_escape_string($connect, $orderid), mysqli_real_escape_string($connect, $itemid), mysqli_real_escape_string($connect, $quantity), mysqli_real_escape_string($connect, $unitprice), mysqli_real_escape_string($connect, $subtotal));
        $connect->query($query);
    }
    updateItemQuantity($connect, $orderid);
    $paymentMethod = $_POST['paymentmethod'];
    $deliverername = $_POST['deliverer'];
    $delivererid = getDelivererId($connect, $deliverername);
    $paymentid = AutoID('ao_payment', 'payment_id', 'P', 4);
    $deliveryid = AutoID('ao_delivery', 'delivery_id', 'D', 4);
    $trackingcode = $deliverername . '-' . $delivererid;

    $paymentquery = sprintf("INSERT INTO ao_payment (payment_id, order_id, payment_method, payment_date)
        VALUES ('%s','%s','%s', NOW())", mysqli_real_escape_string($connect, $paymentid), mysqli_real_escape_string($connect, $orderid), mysqli_real_escape_string($connect, $paymentMethod));
    $connect->query($paymentquery);

    $deliveryquery = sprintf("INSERT INTO ao_delivery (delivery_id, deliverer_id, order_id, estimate_delivery_date, tracking_code, delivery_status)
        VALUES ('%s','%s','%s', DATE_ADD(NOW(), INTERVAL 5 DAY), '%s', 'PENDING')", mysqli_real_escape_string($connect, $deliveryid), mysqli_real_escape_string($connect, $delivererid), mysqli_real_escape_string($connect, $orderid), mysqli_real_escape_string($connect, $trackingcode));
    $connect->query($deliveryquery);

    $orderquery = sprintf("UPDATE ao_order SET order_status = 'SUCCESS', order_date = NOW() WHERE order_id = '%s'", mysqli_real_escape_string($connect, $orderid));
    $connect->query($orderquery);

    $_SESSION['__cart'] = [];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
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

    @media (min-width: 768px) {
        .flex-md-equal>* {
            flex: 1;
        }
    }

    a {
        text-decoration: none;
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
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="/logout">Log Out</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <main class="container mt-4" id="main">
        <h2>Cart</h2>

        <div>
            <!-- item -->
            <div v-for="(cart,i) in cartItems">
                <div class="row">
                    <div class="col-md-3">
                        <img v-if="cart.image" style="width:100%;height:auto;" :src="cart.image" alt="">
                    </div>
                    <div class="col-md-5">
                        <p><span class="fw-bold">{{cart.name}}</span></p>
                        <p><span class="fw-bold">$ {{cart.price}}</span></p>
                    </div>
                    <div class="col-md-2">
                        <input class="form-control w-50" v-model="cart.quantity" type="number">
                    </div>
                    <div class="col-md-2">
                        <p class="fw-bold text-end">$ {{calcuateTotal(cart)}}</p>
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-sm btn-danger" @click="removeItem(cart)">Remove</button>
                        </div>
                    </div>
                </div>
                <hr v-if="i+1!==cartItems.length">
            </div>

        </div>

        <hr>

        <div>
            <h5 class="text-end">Total: $ {{totalAmount}}</h5>
        </div>

        <div class="row">
            <div class="col-6">
                <select class="form-select" v-model="deliverer">
                    <option value="" disabled>Delivery Method</option>
                    <option v-for="deliverer in deliverers" :value="deliverer">{{deliverer[0]}}</option>
                </select>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-6">
                <select class="form-select" v-model="paymentMethod">
                    <option value="" disabled>Payment Methods</option>
                    <option v-for="paymentMethod in paymentMethods" :value="paymentMethod">{{paymentMethod}}</option>
                </select>
            </div>
        </div>

        <div class="d-flex justify-content-end">
            <button class="btn btn-lg btn-primary" @click="checkout()">
                Checkout
            </button>
        </div>

        <div class="p-5"></div>
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
    <script src="https://cdn.jsdelivr.net/npm/vue@2.7.16/dist/vue.js"></script>
    <!-- <script src="/js/vue.min.js"></script> -->
    <script src="/js/axios.min.js"></script>
    <script>
    new Vue({
        el: '#main',
        data: {
            deliverer: '',
            paymentMethod: '',
            cartItems: <?= json_encode($cartItems) ?>,
            deliverers: <?= json_encode($deliverers) ?>,
            paymentMethods: <?= json_encode($paymentMethods) ?>,
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
                    this.cartItems = this.cartItems.filter((cart) => cart.id !== item.id);
                    location.reload();
                });
            },
            checkout() {
                if (!this.paymentMethod) {
                    return alert('Choose payment method!!');
                }
                if (!this.deliverer) {
                    return alert('Choose deliverer!!');
                }
                let formData = new FormData();
                formData.append('btncheckout', 1);
                formData.append('paymentmethod', this.paymentMethod);
                formData.append('deliverer', this.deliverer);
                axios.post('', formData).then(res => {
                    location.reload();
                })
            },
        },
        computed: {
            totalAmount() {
                let total = 0;
                this.cartItems.forEach(item => {
                    total += this.calcuateTotal(item);
                });
                return total;
            },
        }
    })
    </script>
</body>

</html>