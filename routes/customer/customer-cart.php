<?php
if (($_SESSION['role'] ?? 0) !== ROLE_CUSTOMER) {
    session_destroy();
    header('Location: /login', true, 301);
    exit;
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
    foreach ($cartItems[0] as $cartItem) {
        $orderdetailid = AutoID('ao_order_detail', 'order_detail_id', 'OD', 4);

        // from section
        $quantity = $cartItem['quantity'];

        $itemsql = sprintf("SELECT * FROM ao_item where item_id = '%s'", mysqli_real_escape_string($connect, $cartItem['id']));
        $result = $connect->query($itemsql);
        $result = $result->fetch_all();
        $item = $result[0];

        $unitprice = $item[12];

        $subtotal = $unitprice * $quantity;
        $query = sprintf("INSERT INTO ao_order_detail(order_detail_id, order_id, item_id, quantity, unit_price, sub_total)
        VALUES ('%s','%s','%s','%s','%s','%s')", mysqli_real_escape_string($connect, $orderdetailid), mysqli_real_escape_string($connect, $orderid), mysqli_real_escape_string($connect, $itemid), mysqli_real_escape_string($connect, $quantity), mysqli_real_escape_string($connect, $unitprice), mysqli_real_escape_string($connect, $subtotal));
        $connect->query($query);
    }

    $paymentMethod = $_POST['paymentmethod'];
    $deliverername = $_POST['deliverer'];
    $delivererid = getDelivererId($connect, $deliverername);
    $paymentid = AutoID('ao_payment', 'payment_id', 'P', 4);

    $paymentquery = sprintf("INSERT INTO ao_payment (payment_id, order_id, payment_method, payment_date)
        VALUES ('%s','%s','%s', NOW())", mysqli_real_escape_string($connect, $paymentid), mysqli_real_escape_string($connect, $orderid), mysqli_real_escape_string($connect, $paymentMethod));
    $connect->query($paymentquery);

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
    <link rel="icon" type="image/x-icon" href="https://scontent.frgn10-1.fna.fbcdn.net/v/t39.30808-6/273028440_4734065929980159_2213306540146619987_n.jpg?_nc_cat=104&ccb=1-7&_nc_sid=efb6e6&_nc_ohc=8DAVbr-s2rkAX8qzgRc&_nc_oc=AQlfsbZdD8sK9fExJlOIaeZQh576v7W5GFmAZ8yRDVlHm7EeL8UPY76iqfDuTlOwhPA&_nc_ht=scontent.frgn10-1.fna&oh=00_AfCkNdNmMA1O9LPRUo_CMwKAzytRNxHZWlGb4GQWmIRtZQ&oe=65B1794E">
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
        <div class="container">
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
                        <li class="nav-item"><a class="nav-link" href="/cart">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:24px;height:24px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                </svg>
                            </a></li>
                        <li class="nav-item">
                            <div class="dropdown fs-6">
                                <button class="btn dropdown-toggle text-white " type="button" data-bs-toggle="dropdown" aria-expanded="false">
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

    <footer class="container py-5">
        <div class="row">
            <div class="col-12 col-md">
                <h5><?= COMPANY_NAME ?></h5>
                <p><b>Address</b><br>No.123, University Avenue Street,<br>Bahan Township, Yangon.</p>
                <p><b>Telephone</b><br>0968686868</p>
            </div>

            <div class="col-6 col-md">
                <div class="d-flex align-items-center justify-content-center">
                    <a class="m-1 btn btn-primary" href="#">
                        F
                    </a>
                    <a class="m-1 btn btn-primary" href="#">
                        G
                    </a>
                    <a class="m-1 btn btn-primary" href="#">
                        T
                    </a>
                </div>
                <div>
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