<?php
include('server/connection.php');

if (isset($_GET['id'])) {
    $productId = $_GET['id'];
    $query = "SELECT * FROM product WHERE product_id = $productId";
    $result = mysqli_query($conn, $query);
    $product = mysqli_fetch_assoc($result);
}

if (isset($_POST['submit'])) {
    $productId = $_POST['product_id'];
    $productName = $_POST['product_name'];
    $productBrand = $_POST['product_brand'];
    $productCategory = $_POST['product_category'];
    $productDesc = $_POST['product_desc'];
    $productCriteria = $_POST['product_criteria'];
    $productPrice = $_POST['product_price'];

    $query = "UPDATE product SET product_name = '$productName', product_brand = '$productBrand', product_category = '$productCategory', product_desc = '$productDesc', product_criteria = '$productCriteria', product_price = '$productPrice' WHERE product_id = $productId";
    mysqli_query($conn, $query);

    header('Location: manageProduct.php');
}
?>

<form method="post">
    <input type="hidden" name="product_id" value="<?php echo $product['product_id'];?>">
    <label>Name:</label>
    <input type="text" name="product_name" value="<?php echo $product['product_name'];?>"><br>
    <label>Brand:</label>
    <input type="text" name="product_brand" value="<?php echo $product['product_brand'];?>"><br>
    <label>Category:</label>
    <input type="text" name="product_category" value="<?php echo $product['product_category'];?>"><br>
    <label>Description:</label>
    <textarea name="product_desc"><?php echo $product['product_desc'];?></textarea><br>
    <label>Criteria:</label>
    <input type="text" name="product_criteria" value="<?php echo $product['product_criteria'];?>"><br>
    <label>Price:</label>
    <input type="number" name="product_price" step="0.01" value="<?php echo $product['product_price'];?>"><br>
    <button type="submit" name="submit">Save</button>
</form>