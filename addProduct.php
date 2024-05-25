
<form action="addProductLog.php" method="post" enctype="multipart/form-data">
    <label for="product_name">Product Name:</label><br>
    <input type="text" id="product_name" name="product_name" required><br>
    <label for="product_brand">Product Brand:</label><br>
    <input type="text" id="product_brand" name="product_brand" required><br>
    <label for="product_category">Product Category:</label><br>
    <input type="text" id="product_category" name="product_category" required><br>
    <label for="product_desc">Product Description:</label><br>
    <textarea id="product_desc" name="product_desc" required></textarea><br>
    <label for="product_criteria">Product Criteria:</label><br>
    <input type="text" id="product_criteria" name="product_criteria" required><br>
    <label for="product_photo">Product Photo:</label><br>
    <input type="file" id="product_photo" name="product_photo" required><br>
    <label for="product_price">Product Price:</label><br>
    <input type="number" id="product_price" name="product_price" step="0.01" required><br>
    <button type="submit" name="add_product">Add Product</button> <a href="manageProduct.php"><button type="button">Kembali</button></a>
</form>