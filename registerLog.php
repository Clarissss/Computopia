<?php

include 'server/connection.php';

$username = $_POST['user_name'];
$email = $_POST['user_email'];
$password = $_POST['user_password'];

$query_check = "SELECT * FROM users WHERE user_name = '$username' OR user_email = '$email'";
$result_check = mysqli_query($conn, $query_check);

if (mysqli_num_rows($result_check) > 0) {
    echo "<script>alert('Username atau email sudah ada!'); window.location.href = 'index.php';</script>";
    exit;
}


$query = "INSERT INTO users VALUES ('', '$username', '$email', '$password','','','')";
mysqli_query($conn, $query);

echo "<script>alert('Berhasil Register'); window.location.href = 'index.php';</script>";

?>
