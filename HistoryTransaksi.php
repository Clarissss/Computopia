<?php
session_start();
include('server/connection.php');

if (!isset($_SESSION['logged_in'])) {
  header('Location: index.php');
  exit;
}

$user_id = $_SESSION['user_id'];

$query = "SELECT payments.transaction_id, product.product_name, order_items.product_quantity, users.user_address,product.product_price
FROM payments 
JOIN users ON payments.user_id = users.user_id 
JOIN order_items ON users.user_id = order_items.user_id 
JOIN product ON order_items.product_id = product.product_id 
WHERE order_items.order_id =? 
ORDER BY order_items.order_date DESC";

$stmt = $conn->prepare($query);

if ($stmt) {
  $stmt->bind_param('i', $user_id);
  $stmt->execute();
  $result = $stmt->get_result();
} else {
  echo "Error preparing statement: " . $conn->error;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recent Orders</title>
  <!-- Add your CSS and JavaScript files here -->
</head>
<body>
  <h1>Recent Orders</h1>
  <table>
    <thead>
      <tr>
        <th>ID Transaksi</th>
        <th>Nama Barang</th>
        <th>Quantity</th>
        <th>Total Harga</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?php echo htmlspecialchars($row['transaction_id']); ?></td>
          <td><?php echo htmlspecialchars($row['product_name']); ?></td>
          <td><?php echo htmlspecialchars($row['product_quantity']); ?></td>
          <td><?= $row['product_quantity'] * $row['product_price'] ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</body>
</html>