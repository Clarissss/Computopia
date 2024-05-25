<?php
session_start();
require 'server/connection.php'; // Menyertakan file koneksi database

// Inisialisasi keranjang belanja jika belum ada
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Tambahkan produk ke keranjang
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['product_id'])) {
        $product_id = $_POST['product_id'];
        // Periksa apakah produk sudah ada di keranjang
        if (isset($_SESSION['cart'][$product_id])) {
            // Jika sudah ada, tambahkan jumlahnya
            $_SESSION['cart'][$product_id]['quantity'] += 1;
        } else {
            // Jika belum ada, tambahkan produk baru dengan jumlah 1
            $_SESSION['cart'][$product_id] = array('quantity' => 1);
        }
    }

    // Logika untuk mengosongkan keranjang
    if (isset($_POST['clear_cart'])) {
        $_SESSION['cart'] = array();
    }
}

// Dapatkan detail produk dari database
$products = array();
if (!empty($_SESSION['cart'])) {
    $product_ids = implode(',', array_keys($_SESSION['cart']));
    $query = "SELECT * FROM product WHERE product_id IN ($product_ids)";
    $result = $conn->query($query);

    while ($row = $result->fetch_assoc()) {
        $products[$row['product_id']] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="css/cart.css">
</head>
<body>
    <h1>Your Shopping Cart</h1>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total_price = 0;
            foreach ($_SESSION['cart'] as $product_id => $details) {
                $product = $products[$product_id];
                $quantity = $details['quantity'];
                $price = $product['product_price'];
                $total = $quantity * $price;
                $total_price += $total;
            ?>
            <tr>
                <td><?php echo $product['product_name']; ?></td>
                <td><?php echo $quantity; ?></td>
                <td>IDR <?php echo number_format($price, 0, ',', '.'); ?></td>
                <td>IDR <?php echo number_format($total, 0, ',', '.'); ?></td>
            </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">Total Price</td>
                <td>IDR <?php echo number_format($total_price, 0, ',', '.'); ?></td>
            </tr>
        </tfoot>
    </table>

    <!-- Tombol Clear Cart -->
   <center><form method="post" action="shopping_cart.php">
        <button type="submit" name="clear_cart" class="btn">Clear Cart</button>
        <a href="shop.php"><button type="button">Kembali</button></a>
    </form></center>
    <a href="checkout.php" class="btn">Proceed to Checkout</a>
</body>
</html>