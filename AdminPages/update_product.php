<?php
include '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = (int)$_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $image = $_POST['image'];
    $stock = $_POST['stock'];

    $sql = "UPDATE Product 
            SET Name='$name',
                Price='$price',
                Image='$image',
                Stock='$stock'
            WHERE ProductID=$id";

    if (mysqli_query($conn, $sql)) {
        header("Location: manage_product.php");
        exit;
    } else {
        die("Update failed: " . mysqli_error($conn));
    }

} else {
    die("Invalid request");
}
?>