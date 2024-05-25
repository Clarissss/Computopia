<?php
include('server/connection.php');
session_start();

$admin_id = $_SESSION['admin_id'];
$admin_name = $_SESSION['admin_name'];
$admin_phone = $_SESSION['admin_phone'];
$admin_photo = $_SESSION['admin_photo'];

if (!isset($_SESSION['logged_in_adm'])) {
    header('location: index.php');
    exit;
}

if (isset($_GET['logout'])) {
    if (isset($_SESSION['logged_in_adm'])) {
        unset($_SESSION['logged_in_adm']);
        unset($_SESSION['admin_name']);
        header('location: index.php');
        exit;
    }
}

$query = "SELECT 
             (SELECT COUNT(*) FROM order_items) as total_orders,
             (SELECT COUNT(*) FROM users) as total_users,
             (SELECT COUNT(DISTINCT user_id) FROM order_items) as total_customer,
             (SELECT SUM(order_items.product_quantity * product.product_price) FROM order_items JOIN product ON order_items.product_id = product.product_id) as total_earnings";
$result = mysqli_query($conn, $query);

$row = mysqli_fetch_assoc($result);
$total_orders = $row['total_orders'];
$total_users = $row['total_users'];
$total_customer = $row['total_customer'];
$total_earnings = $row['total_earnings'];
$total_earnings_IDR = number_format($total_earnings, 0, ',', '.');

$query = "SELECT order_items.item_id, order_items.order_id, product.product_id, product.product_name, product.product_photo, product.product_price, order_items.product_quantity, users.user_id, users.user_name, users.user_photo, users.user_address, order_items.order_date
          FROM order_items
          JOIN product ON order_items.product_id = product.product_id
          JOIN users ON order_items.user_id = users.user_id
          ORDER BY order_items.order_date DESC
          LIMIT 5";
$customer_result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl7/1L_dstPt3HV5HzF6Gvk/e3s4Wz6iJgD/+ub2oU" crossorigin="anonymous">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/admin.css">
</head>

<body>
    <!-- page loader start -->
    <div class="loader">
        <span class="loader_dot" style="--d: 200ms"></span>
        <span class="loader_dot" style="--d: 400ms"></span>
        <span class="loader_dot" style="--d: 600ms"></span>
        <span class="loader_dot" style="--d: 800ms"></span>
        <span class="loader_dot" style="--d: 1000ms"></span>
    </div>
    <!-- page loader end -->

    <!-- navigation -->
    <div class="container">
        <div class="navigation">
            <ul>
                <li>
                    <a href="#">
                        <span class="icon">
                            <ion-icon name="planet-outline"></ion-icon>
                        </span>
                        <span class="tittle">Computopia</span>
                    </a>
                </li>
                <li>
                    <a href="adminDashboard.php">
                        <span class="icon">
                            <ion-icon name="home-outline"></ion-icon>
                        </span>
                        <span class="tittle">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="manageUserAcc.php">
                        <span class="icon">
                            <ion-icon name="people-outline"></ion-icon>
                        </span>
                        <span class="tittle">Users</span>
                    </a>
                </li>
                <li>
                    <a href="manageProduct.php">
                        <span class="icon">
                            <ion-icon name="laptop-outline"></ion-icon>
                        </span>
                        <span class="tittle">Products</span>
                    </a>
                </li>
                <li>
                    <a href="adminTransaction.php">
                        <span class="icon">
                            <ion-icon name="cash-outline"></ion-icon>
                        </span>
                        <span class="tittle">Transactions</span>
                    </a>
                </li>
                <li>
                    <a href="rePassword.php">
                        <span class="icon">
                            <ion-icon name="lock-closed-outline"></ion-icon>
                        </span>
                        <span class="tittle">Re-Password</span>
                    </a>
                </li>
                <li>
                    <a href="adminDashboard.php?logout=1">
                        <span class="icon">
                            <ion-icon name="log-out-outline"></ion-icon>
                        </span>
                        <span class="tittle">Sign Out</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- main -->
        <div class="main">
            <div class="topbar">
                <div class="toggle">
                    <ion-icon name="menu-outline"></ion-icon>
                </div>

                <div class="search">
                    <label>
                        <input type="text" placeholder="Search here...">
                        <ion-icon name="search-outline"></ion-icon>
                    </label>
                </div>

                <div class="mode">
                    <input type="checkbox" class="checkbox" id="checkbox">
                    <label for="checkbox" class="checkbox-label">
                        <ion-icon name="moon" class="moon"></ion-icon>
                        <ion-icon name="sunny" class="sun"></ion-icon>
                        <span class="ball"></span>
                    </label>
                </div>

                <div class="user">
            <img src='img/admin/<?php echo $admin_photo; ?>' alt='' id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <!-- Added role="button" and data-bs-toggle="dropdown" -->
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                <li><a class="dropdown-item" href="adminProfile.php">Profile</a></li>
                <li><a class="dropdown-item" href="adminDashboard.php?logout=1">Logout</a></li>
            </ul>
        </div>
    </div>

            <!-- cards -->
            <div class="cardBox">
                <div class="card">
                    <div>
                        <div class="numbers"><?php echo $total_customer; ?></div>
                        <div class="cardName">Customers</div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="people-outline"></ion-icon>
                    </div>
                </div>
                <div class="card">
                    <div>
                        <div class="numbers"><?php echo $total_orders; ?></div>
                        <div class="cardName">Sales</div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="cart-outline"></ion-icon>
                    </div>
                </div>
                <div class="card">
                    <div>
                        <div class="numbers"><?php echo $total_users; ?></div>
                        <div class="cardName">Users</div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="person-outline"></ion-icon>
                    </div>
                </div>
                <div class="card">
                    <div>
                        <div class="numbers"><?php echo $total_earnings_IDR; ?></div>
                        <div class="cardName">Earning</div>
                    </div>
                    <div class="iconBx">
                        <ion-icon name="wallet-outline"></ion-icon>
                    </div>
                </div>
            </div>

            <!-- order details list -->
            <div class="details">
                <div class="recentOrders">
                    <div class="cardHeader">
                        <h2>Recent Orders</h2>
                        <a href="#" class="btn">View All</a>
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <td>Name</td>
                                <td>Price</td>
                                <td>Payment</td>
                                <td>Status</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>HyperX Cloud 3r</td>
                                <td>$80</td>
                                <td>Paid</td>
                                <td><span class="status-delivered">Delivered</span></td>
                            </tr>
                            <tr>
                                <td>Logitech G Hero</td>
                                <td>$99</td>
                                <td>Due</td>
                                <td><span class="status-pending">Pending</span></td>
                            </tr>
                            <tr>
                                <td>Ajazz AK680 Mechanical Keyboard</td>
                                <td>$30</td>
                                <td>Paid</td>
                                <td><span class="status-return">Return</span></td>
                            </tr>
                            <tr>
                                <td>Logitech C270 Webcam</td>
                                <td>$150</td>
                                <td>Due</td>
                                <td><span class="status-inProgress">In Progress</span></td>
                            </tr>
                            <tr>
                                <td>HyperX Cloud 3r</td>
                                <td>$80</td>
                                <td>Paid</td>
                                <td><span class="status-delivered">Delivered</span></td>
                            </tr>
                            <tr>
                                <td>Logitech G Hero</td>
                                <td>$99</td>
                                <td>Due</td>
                                <td><span class="status-pending">Pending</span></td>
                            </tr>
                            <tr>
                                <td>Ajazz AK680 Mechanical Keyboard</td>
                                <td>$30</td>
                                <td>Paid</td>
                                <td><span class="status-return">Return</span></td>
                            </tr>
                            <tr>
                                <td>Logitech C270 Webcam</td>
                                <td>$150</td>
                                <td>Due</td>
                                <td><span class="status-inProgress">In Progress</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- new customers -->
                <div class="recentCustomers">
                    <div class="cardHeader">
                        <h2>Recent Customers</h2>
                    </div>
                    <table>
                        <tbody>
                            <?php
                            while ($row = mysqli_fetch_assoc($customer_result)) {
                                $user_name = $row['user_name'];
                                $user_photo = $row['user_photo'];
                                $user_address = $row['user_address'];
                                $order_id = $row['order_id'];
                                $order_date = $row['order_date'];

                                echo "<tr>";
                                echo "<td width='60px'>";
                                echo "<div class='imgBx'><img src='img/user/" . $user_photo . "' alt=''></div>";
                                echo "</td>";
                                echo "<td>";
                                echo "<h4>$user_name<br><span>$user_address</span></h4>";
                                echo "<p><small>Order ID: $order_id<br>Order Date: $order_date</small></p>";
                                echo "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- my js -->
    <script src="js/admin.js"></script>

    <!-- ion icon -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybB5IXNxFwWQfE7u8Lj+XJHAxKlXiG/8rsrtpb6PEdzD828Ii" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-cn7l7gDp0eyniUwwAZgrzD06kc/tftFf19TOAs2zVinnD/C7E91j9yyk5//jjpt/" crossorigin="anonymous"></script>
</body>

</html>
