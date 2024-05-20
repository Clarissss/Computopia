<?php
session_start();
include('server/connection.php');

// Check connection
if (!$conn) {
    die("Connection failed: ". mysqli_connect_error());
}

// Retrieve product ID from form submission
$product_id = $_POST['product_id'];

// Validate product ID
if (!is_numeric($product_id) || $product_id <= 0) {
    die("Invalid product ID");
}

// Retrieve product information from database
$stmt = $conn->prepare("SELECT * FROM product WHERE product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

// Add product to cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Use session ID as the key for the cart data
$session_id = session_id();
$_SESSION['cart'][$session_id][] = $product;

// Close connection
mysqli_close($conn);

// Redirect back to product section
header('Location: shopping_cart.php');
exit;
?>