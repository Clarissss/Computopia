<?php
session_start();
require 'server/connection.php';

if (!isset($_GET['order_id'])) {
    echo "Invalid order. <a href='shopping_cart.php'>Go back to shopping cart</a>";
    exit();
}

$order_id = $_GET['order_id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 100px auto;
            padding: 20px;
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #4CAF50;
        }
        p {
            font-size: 18px;
            color: #555;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            color: #fff;
            background-color: #4CAF50;
            text-decoration: none;
            border-radius: 5px;
        }
        a:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Thank You for Your Purchase!</h1>
        <p>Your order has been successfully processed.</p>
        <p>Order ID: <?php echo htmlspecialchars($order_id); ?></p>
        <p>Thank you for shopping at our store :)</p>
        <a href="index.php">Back to Home</a>
    </div>
</body>
</html>
