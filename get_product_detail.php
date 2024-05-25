<?php
include('server/connection.php');

if (isset($_GET['product_id'])) {
  // Pastikan nilai id adalah bilangan bulat positif
  $productId = filter_var($_GET['product_id'], FILTER_VALIDATE_INT);
  if ($productId === false || $productId <= 0) {
    // Jika id tidak valid, kirim respon JSON dengan pesan error
    echo json_encode(['error' => 'Invalid product ID']);
    exit;
  }

  // Gunakan prepared statement untuk mencegah SQL Injection
  $query = "SELECT product_id, product_name, product_desc, product_price, product_photo FROM product WHERE product_id = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param('i', $productId);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
    // Kirim detail produk dalam format JSON
    echo json_encode($product);
  } else {
    // Jika produk tidak ditemukan
    echo json_encode(['error' => 'Product not found']);
  }
} else {
  // Jika tidak ada ID produk yang dikirim
  echo json_encode(['error' => 'No product ID provided']);
}
?>
