<?php
session_start();
include('server/connection.php');

// Retrieve cart items from session
$cart_items = isset($_SESSION['cart'][session_id()]) ? $_SESSION['cart'][session_id()] : array();

// Calculate total price
$total_price = 0;
foreach ($cart_items as $item) {
    $total_price += $item['product_price'];
}

// Display shopping cart
?>
<div class="shopping-cart">
  <h2>Shopping Cart</h2>
  <ul>
    <?php foreach ($cart_items as $item) {?>
    <li>
      <img src="img/products/<?php echo $item['product_photo'];?>" alt="<?php echo $item['product_name'];?>">
      <span><?php echo $item['product_name'];?></span>
      <span>IDR <?php echo number_format($item['product_price'], 0, ',', '.');?></span>
    </li>
    <?php }?>
  </ul>
  <p>Total Price: IDR <?php echo number_format($total_price, 0, ',', '.');?></p>
  <form action="" method="post">
    <input type="submit" name="clear_cart" value="Clear Cart">
  </form>
  <?php
  if (isset($_POST['clear_cart']) && isset($_SESSION['cart'][session_id()])) {
    unset($_SESSION['cart'][session_id()]);
  }
  ?>
</div>