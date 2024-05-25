<?php
session_start();
require 'server/connection.php';

if (!isset($_SESSION['logged_in'])) {
    echo "You need to log in to checkout. <a href='login.php'>Login</a>";
    exit();
}

if (empty($_SESSION['cart'])) {
    echo "Your cart is empty. <a href='shopping_cart.php'>Go back to shopping cart</a>";
    exit();
}

$products = [];
$total_price = 0;
if (!empty($_SESSION['cart'])) {
    $product_ids = implode(',', array_keys($_SESSION['cart']));
    $query = "SELECT * FROM product WHERE product_id IN ($product_ids)";
    $result = $conn->query($query);

    while ($row = $result->fetch_assoc()) {
        $products[$row['product_id']] = $row;
    }

    foreach ($_SESSION['cart'] as $product_id => $details) {
        if (isset($products[$product_id])) {
            $total_price += $products[$product_id]['product_price'] * $details['quantity'];
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['checkout'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $city = $_POST['city'];
    $address = $_POST['address'];
    $order_cost = $total_price;
    $order_status = "not paid";
    $user_id = $_SESSION['user_id'];
    $order_date = date('Y-m-d H:i:s');

    if (empty($name) || empty($address) || empty($email) || empty($phone) || empty($city)) {
        $error = "Please fill in all fields.";
    } else {
        $query_orders = "INSERT INTO orders (order_cost, order_status, user_id, user_phone, user_city, user_address, order_date) 
                         VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt_orders = $conn->prepare($query_orders);
        $stmt_orders->bind_param('dsissss', $order_cost, $order_status, $user_id, $phone, $city, $address, $order_date);

        if ($stmt_orders->execute()) {
            $order_id = $stmt_orders->insert_id;
            foreach ($_SESSION['cart'] as $product_id => $details) {
                if (isset($products[$product_id])) {
                    $product = $products[$product_id];
                    $product_name = $product['product_name'];
                    $product_image = $product['product_photo'];
                    $product_price = $product['product_price'];
                    $product_quantity = $details['quantity'];

                    $query_order_items = "INSERT INTO order_items (order_id, product_id, product_name, product_image, product_price, product_quantity, user_id, order_date) 
                                          VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt_order_items = $conn->prepare($query_order_items);
                    $stmt_order_items->bind_param('iissdiis', $order_id, $product_id, $product_name, $product_image, $product_price, $product_quantity, $user_id, $order_date);
                    $stmt_order_items->execute();
                    $stmt_order_items->close();
                }
            }
            $stmt_orders->close();

            $_SESSION['order_id'] = $order_id;
            $_SESSION['total'] = $total_price;

            header('Location: payment.php');
            exit();
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}

$user_name = $_SESSION['user_name'];
$user_address = $_SESSION['user_address'];
$user_email = $_SESSION['user_email'];
$user_phone = $_SESSION['user_phone'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="css/cart.css">
</head>
<body>
    <h1>Checkout</h1>
    <?php if (isset($error)) { echo "<p style='color: red;'>$error</p>"; } ?>
    <form method="post" action="checkout.php">
        <div>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user_name); ?>" required>
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_email); ?>" required>
        </div>
        <div>
            <label for="phone">Phone:</label>
            <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user_phone); ?>" required>
        </div>
        <div>
            <label for="city">City:</label>
            <input type="text" id="city" name="city" required>
        </div>
        <div>
            <label for="address">Address:</label>
            <textarea id="address" name="address" required><?php echo htmlspecialchars($user_address); ?></textarea>
        </div>
        <h2>Order Summary</h2>
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['cart'] as $product_id => $details) {
                    $product = $products[$product_id];
                    $quantity = $details['quantity'];
                    $price = $product['product_price'];
                    $total = $quantity * $price;
                ?>
                <tr>
                    <td><img src="img/products/<?php echo htmlspecialchars($product['product_photo']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" width="50" height="50"></td>
                    <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                    <td><?php echo $quantity; ?></td>
                    <td>IDR <?php echo number_format($price, 0, ',', '.'); ?></td>
                    <td>IDR <?php echo number_format($total, 0, ',', '.'); ?></td>
                </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4">Total Price</td>
                    <td>IDR <?php echo number_format($total_price, 0, ',', '.'); ?></td>
                </tr>
            </tfoot>
        </table>
        <button type="submit" name="checkout" class="btn">Place Order</button>
    </form>
    <a href="shopping_cart.php" class="btn">Back to Cart</a>
</body>
</html>
