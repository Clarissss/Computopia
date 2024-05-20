<?php
include('server/connection.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = $_POST['product_name'];
    $product_brand = $_POST['product_brand'];
    $product_category = $_POST['product_category'];
    $product_desc = $_POST['product_desc'];
    $product_criteria = $_POST['product_criteria'];
    $product_price = $_POST['product_price'];

    // Get the uploaded file information
    $product_photo = $_FILES['product_photo']['name'];
    $product_photo_temp = $_FILES['product_photo']['tmp_name'];

    // Move the uploaded file to the target directory
    move_uploaded_file($product_photo_temp, 'img/products/' . $product_photo);

    // Insert the new product into the database
    $query = "INSERT INTO product (product_name, product_brand, product_category, product_desc, product_criteria, product_photo, product_price) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssssss", $product_name, $product_brand, $product_category, $product_desc, $product_criteria, $product_photo, $product_price);
    mysqli_stmt_execute($stmt);

    // Close the database connection
    mysqli_close($conn);

    // Redirect back to the admin dashboard
    header('Location: manageProduct.php');
    exit;
}
?>

<form method="post" enctype="multipart/form-data">
    <label for="product_name">Product Name:</label><br>
    <input type="text" id="product_name" name="product_name"><br>
    <label for="product_brand">Product Brand:</label><br>
    <input type="text" id="product_brand" name="product_brand"><br>
    <label for="product_category">Product Category:</label><br>
    <input type="text" id="product_category" name="product_category"><br>
    <label for="product_desc">Product Description:</label><br>
    <textarea id="product_desc" name="product_desc"></textarea><br>
    <label for="product_criteria">Product Criteria:</label><br>
    <input type="text" id="product_criteria" name="product_criteria"><br>
    <label for="product_photo">Product Photo:</label><br>
    <input type="file" id="product_photo" name="product_photo"><br>
    <label for="product_price">Product Price:</label><br>
    <input type="number" id="product_price" name="product_price" step="0.01"><br>
    <button type="submit" name="add_product">Add Product</button> <a href="manageProduct.php"><button type="button">Kembali</button></a>
</form>